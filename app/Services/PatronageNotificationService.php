<?php

namespace App\Services;

use App\Models\PatronageEscalationRule;
use App\Models\PatronageNotification;
use App\Models\PatronageTask;
use Illuminate\Support\Facades\Log;

class PatronageNotificationService
{
    private const NOTIFICATION_CHANNELS = ['telegram'];

    public function __construct(
        private PatronageBotService $bot,
    ) {}

    /**
     * Notify doctor of a new patronage task via Telegram bot.
     */
    public function notifyDoctorOfNewTask(PatronageTask $task): void
    {
        $task->loadMissing(['patient', 'discharge', 'familyDoctor', 'hospitalClinic']);

        $patientName = $task->patient?->full_name ?? 'Noma\'lum';
        $diagnosis = $task->discharge?->diagnosis_text ?? $task->discharge?->diagnosis_code ?? '';
        $dueAt = $task->due_at?->format('d.m.Y H:i') ?? '';
        $hospital = $task->hospitalClinic?->name ?? '';

        $sevLabel = match ($task->discharge?->severity_level) {
            'mild' => 'Yengil',
            'moderate' => 'O\'rtacha',
            'severe' => 'Og\'ir',
            'critical' => 'Juda og\'ir',
            default => '—',
        };

        $message = "🆕 *YANGI PATRONAJ VAZIFASI*\n\n"
            . "👤 *Bemor:* {$patientName}\n"
            . "🏥 *Shifoxona:* {$hospital}\n"
            . "🔬 *Tashxis:* {$diagnosis}\n"
            . "📊 *Og'irlik:* {$sevLabel}\n"
            . "📅 *Muddat:* {$dueAt}\n\n"
            . "Qabul qilish uchun tugmani bosing:";

        // Save notification record
        $recipientId = $task->familyDoctor?->id;
        PatronageNotification::query()->create([
            'patronage_task_id' => $task->id,
            'channel' => 'telegram',
            'recipient_type' => 'doctor',
            'recipient_id' => $recipientId,
            'message_body' => $message,
            'status' => 'pending',
            'attempt_count' => 0,
        ]);

        // Send via Telegram if doctor has chat_id
        $chatId = $task->familyDoctor?->telegram_chat_id;
        if ($chatId) {
            try {
                $this->bot->sendMessageWithInlineKeyboard(
                    (int) $chatId,
                    $message,
                    [
                        [['text' => '✅ Qabul qilish', 'callback_data' => "accept:{$task->id}"]],
                        [['text' => '📋 Batafsil', 'callback_data' => "task:{$task->id}"]],
                    ]
                );

                PatronageNotification::query()
                    ->where('patronage_task_id', $task->id)
                    ->where('channel', 'telegram')
                    ->latest()
                    ->first()
                    ?->update(['status' => 'sent', 'sent_at' => now(), 'attempt_count' => 1]);
            } catch (\Throwable $e) {
                Log::error('Patronage Telegram notification failed', ['error' => $e->getMessage(), 'task_id' => $task->id]);
            }
        }

        if (! in_array($task->status, ['notified', 'accepted', 'in_progress', 'completed'], true)) {
            $task->update([
                'status' => 'notified',
                'notified_at' => now(),
            ]);
        }
    }

    /**
     * Send a reminder to the doctor.
     *
     * @param string $urgency 'normal' (6h left) or 'urgent' (2h left)
     */
    public function sendReminder(PatronageTask $task, string $urgency = 'normal'): void
    {
        $task->loadMissing(['patient']);

        $patientName = $task->patient?->full_name ?? 'Noma\'lum';
        $dueAt = $task->due_at?->format('d.m.Y H:i') ?? '';

        $message = match ($urgency) {
            'urgent' => "SHOSHILINCH: Patronaj tashrif muddati tugaydi! Bemor: {$patientName}. Muddat: {$dueAt}. Darhol tashrif buyuring!",
            default => "Eslatma: Patronaj tashrif muddati yaqinlashmoqda. Bemor: {$patientName}. Muddat: {$dueAt}.",
        };

        $recipientId = $task->familyDoctor?->user_id;

        foreach (self::NOTIFICATION_CHANNELS as $channel) {
            PatronageNotification::query()->create([
                'patronage_task_id' => $task->id,
                'channel' => $channel,
                'recipient_type' => 'doctor',
                'recipient_id' => $recipientId,
                'message_body' => $message,
                'status' => 'pending',
                'attempt_count' => 0,
            ]);
        }
    }

    /**
     * Notify about escalation.
     * Level 1: notify supervisor. Level 2: notify chief doctor. Level 3: notify ministry representative.
     */
    public function notifyEscalation(PatronageTask $task, int $escalationLevel): void
    {
        $task->loadMissing(['patient', 'discharge']);

        $patientName = $task->patient?->full_name ?? 'Noma\'lum';
        $diagnosis = $task->discharge?->diagnosis_text ?? '';

        $recipientType = match ($escalationLevel) {
            1 => 'supervisor',
            2 => 'chief_doctor',
            3 => 'ministry',
            default => 'supervisor',
        };

        $levelLabel = match ($escalationLevel) {
            1 => 'Nazoratchi',
            2 => 'Bosh shifokor',
            3 => 'Vazirlik vakili',
            default => "Daraja {$escalationLevel}",
        };

        $message = "ESKALATSIYA (Daraja {$escalationLevel} - {$levelLabel}): Patronaj topshirig'i bajarilmadi. "
            . "Bemor: {$patientName}, Tashxis: {$diagnosis}. "
            . "SLA buzildi: {$task->sla_breach_minutes} daqiqa.";

        $rule = PatronageEscalationRule::query()
            ->where(fn ($q) => $q->where('clinic_id', $task->clinic_id)->orWhereNull('clinic_id'))
            ->where('is_active', true)
            ->where('escalation_level', $escalationLevel)
            ->first();

        $channels = $rule?->notification_channels ?? self::NOTIFICATION_CHANNELS;

        foreach ($channels as $channel) {
            PatronageNotification::query()->create([
                'patronage_task_id' => $task->id,
                'channel' => $channel,
                'recipient_type' => $recipientType,
                'recipient_id' => null,
                'message_body' => $message,
                'status' => 'pending',
                'attempt_count' => 0,
            ]);
        }
    }

    /**
     * Mark a notification as sent.
     */
    public function markAsSent(PatronageNotification $notification): void
    {
        $notification->update([
            'status' => 'sent',
            'sent_at' => now(),
            'attempt_count' => $notification->attempt_count + 1,
        ]);
    }

    /**
     * Mark a notification as failed.
     */
    public function markAsFailed(PatronageNotification $notification, string $error): void
    {
        $notification->update([
            'status' => 'failed',
            'error_message' => $error,
            'attempt_count' => $notification->attempt_count + 1,
        ]);
    }
}
