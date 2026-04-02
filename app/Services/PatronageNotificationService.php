<?php

namespace App\Services;

use App\Models\PatronageEscalationRule;
use App\Models\PatronageNotification;
use App\Models\PatronageTask;

class PatronageNotificationService
{
    private const NOTIFICATION_CHANNELS = ['sms', 'telegram', 'push'];

    /**
     * Notify doctor of a new patronage task.
     * Sends via SMS, Telegram, and Push.
     * Actual sending is done by SendPatronageNotificationJob (dispatched separately).
     */
    public function notifyDoctorOfNewTask(PatronageTask $task): void
    {
        $task->loadMissing(['patient', 'discharge']);

        $patientName = $task->patient?->full_name ?? 'Noma\'lum';
        $diagnosis = $task->discharge?->diagnosis_text ?? $task->discharge?->diagnosis_code ?? '';
        $dueAt = $task->due_at?->format('d.m.Y H:i') ?? '';

        $message = "Yangi patronaj task: {$patientName}, {$diagnosis}. Muddat: {$dueAt}. Qabul qiling.";

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
