<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\PatronageTask;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PatronageBotWebhookHandler
{
    public function __construct(
        private PatronageBotService $bot,
        private PatronageService $patronageService,
    ) {}

    public function handle(array $update): void
    {
        if (isset($update['callback_query'])) {
            $this->handleCallback($update['callback_query']);
            return;
        }

        if (isset($update['message'])) {
            $this->handleMessage($update['message']);
        }
    }

    private function handleMessage(array $message): void
    {
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';

        if (str_starts_with($text, '/start')) {
            $this->handleStart($chatId);
            return;
        }

        if (str_starts_with($text, '/help')) {
            $this->bot->sendMessage($chatId, implode("\n", [
                '🏥 *Patronaj Bot*',
                '',
                '📋 Buyruqlar:',
                '/start — Ro\'yxatdan o\'tish / Vazifalarni ko\'rish',
                '/tasks — Mening vazifalarim',
                '/help — Yordam',
                '',
                'Bemor chiqarilgandan keyin patronaj vazifalarini',
                'qabul qilish va bajarilganini tasdiqlash uchun.',
            ]));
            return;
        }

        if (str_starts_with($text, '/tasks')) {
            $this->showMyTasks($chatId);
            return;
        }

        // Check if doctor is writing visit notes
        $state = Cache::get("pat_bot:{$chatId}");
        if ($state && ($state['step'] ?? '') === 'visit_notes') {
            $this->handleVisitNotes($chatId, $text, $state);
            return;
        }

        $this->bot->sendMessage($chatId, "Buyruqlar: /start, /tasks, /help");
    }

    private function handleStart(int $chatId): void
    {
        // Check if doctor already registered
        $doctor = Doctor::withoutGlobalScopes()
            ->where('telegram_chat_id', (string) $chatId)
            ->where('is_active', true)
            ->first();

        if ($doctor) {
            $pending = PatronageTask::withoutGlobalScopes()
                ->where('family_doctor_id', $doctor->id)
                ->whereIn('status', ['pending', 'notified', 'accepted', 'in_progress'])
                ->count();

            $this->bot->sendMessageWithInlineKeyboard(
                $chatId,
                "👨‍⚕️ *Salom, {$doctor->full_name}!*\n\n"
                . "📋 Sizda *{$pending}* ta faol patronaj vazifasi bor.\n\n"
                . "Quyidagi tugmalardan tanlang:",
                [
                    [['text' => "📋 Mening vazifalarim ({$pending})", 'callback_data' => 'my_tasks']],
                    [['text' => '✅ Bajarilganlarni ko\'rish', 'callback_data' => 'completed_tasks']],
                ]
            );
            return;
        }

        // Not registered — ask to register
        $this->bot->sendMessageWithInlineKeyboard(
            $chatId,
            "🏥 *Patronaj Bot*\n\n"
            . "Siz hali ro'yxatdan o'tmagansiz.\n\n"
            . "Admin panelidagi Shifokorlar bo'limida Telegram ID maydoniga "
            . "quyidagi raqamni kiriting:\n\n"
            . "`{$chatId}`\n\n"
            . "_Bu raqamni nusxalang va admin paneliga kiriting._",
            [
                [['text' => '🔄 Tekshirish', 'callback_data' => 'check_reg']],
            ]
        );
    }

    private function handleCallback(array $cb): void
    {
        $chatId = $cb['message']['chat']['id'];
        $msgId = $cb['message']['message_id'];
        $data = $cb['data'];

        $this->bot->answerCallbackQuery($cb['id']);

        if ($data === 'check_reg') {
            $this->handleStart($chatId);
            return;
        }

        if ($data === 'my_tasks') {
            $this->showMyTasks($chatId, $msgId);
            return;
        }

        if ($data === 'completed_tasks') {
            $this->showCompletedTasks($chatId, $msgId);
            return;
        }

        // Accept task
        if (str_starts_with($data, 'accept:')) {
            $taskId = (int) substr($data, 7);
            $this->acceptTask($chatId, $msgId, $taskId);
            return;
        }

        // Start visit (confirm arrival)
        if (str_starts_with($data, 'visit:')) {
            $taskId = (int) substr($data, 6);
            $this->startVisit($chatId, $msgId, $taskId);
            return;
        }

        // Complete task - ask for notes
        if (str_starts_with($data, 'complete:')) {
            $taskId = (int) substr($data, 9);
            $this->askVisitNotes($chatId, $msgId, $taskId);
            return;
        }

        // Condition score
        if (str_starts_with($data, 'score:')) {
            $parts = explode(':', $data);
            $taskId = (int) $parts[1];
            $score = (int) $parts[2];
            $this->setConditionScore($chatId, $msgId, $taskId, $score);
            return;
        }

        // View task detail
        if (str_starts_with($data, 'task:')) {
            $taskId = (int) substr($data, 5);
            $this->showTaskDetail($chatId, $msgId, $taskId);
            return;
        }

        // Back to tasks
        if ($data === 'back_tasks') {
            $this->showMyTasks($chatId, $msgId);
            return;
        }
    }

    private function showMyTasks(int $chatId, ?int $msgId = null): void
    {
        $doctor = $this->getDoctor($chatId);
        if (!$doctor) {
            $this->handleStart($chatId);
            return;
        }

        $tasks = PatronageTask::withoutGlobalScopes()
            ->where('family_doctor_id', $doctor->id)
            ->whereIn('status', ['pending', 'notified', 'accepted', 'in_progress'])
            ->with('patient:id,full_name')
            ->orderBy('due_at')
            ->limit(10)
            ->get();

        if ($tasks->isEmpty()) {
            $text = "📋 *Mening vazifalarim*\n\n✅ Hozircha faol vazifalar yo'q.";
            if ($msgId) {
                $this->bot->editMessageText($chatId, $msgId, $text);
            } else {
                $this->bot->sendMessage($chatId, $text);
            }
            return;
        }

        $buttons = [];
        $text = "📋 *Mening vazifalarim* ({$tasks->count()} ta)\n\n";

        foreach ($tasks as $i => $task) {
            $statusIcon = match ($task->status) {
                'pending' => '🔴',
                'notified' => '🟡',
                'accepted' => '🟢',
                'in_progress' => '🔵',
                default => '⚪',
            };

            $overdue = $task->due_at?->isPast() ? ' ⚠️' : '';
            $dueDate = $task->due_at?->format('d.m H:i') ?? '';
            $patientName = $task->patient?->full_name ?? 'Noma\'lum';

            $text .= "{$statusIcon} *{$patientName}*\n";
            $text .= "   📅 {$dueDate}{$overdue} | ";
            $text .= match ($task->status) {
                'pending', 'notified' => "Kutilmoqda\n",
                'accepted' => "Qabul qilingan\n",
                'in_progress' => "Jarayonda\n",
                default => "{$task->status}\n",
            };

            $buttons[] = [['text' => "{$statusIcon} {$patientName} — {$dueDate}", 'callback_data' => "task:{$task->id}"]];
        }

        $text .= "\n_Batafsil ko'rish uchun bemor ismini bosing_";

        if ($msgId) {
            $this->bot->editMessageText($chatId, $msgId, $text, ['inline_keyboard' => $buttons]);
        } else {
            $this->bot->sendMessageWithInlineKeyboard($chatId, $text, $buttons);
        }
    }

    private function showCompletedTasks(int $chatId, ?int $msgId = null): void
    {
        $doctor = $this->getDoctor($chatId);
        if (!$doctor) return;

        $tasks = PatronageTask::withoutGlobalScopes()
            ->where('family_doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->with('patient:id,full_name')
            ->orderByDesc('completed_at')
            ->limit(5)
            ->get();

        $text = "✅ *Bajarilgan vazifalar*\n\n";

        if ($tasks->isEmpty()) {
            $text .= "Hali bajarilgan vazifalar yo'q.";
        } else {
            foreach ($tasks as $task) {
                $text .= "✅ *{$task->patient?->full_name}*\n";
                $text .= "   📅 {$task->completed_at?->format('d.m.Y H:i')}\n";
                if ($task->patient_condition_score) {
                    $text .= "   💊 Holat: {$task->patient_condition_score}/10\n";
                }
                $text .= "\n";
            }
        }

        $buttons = [[['text' => '◀️ Orqaga', 'callback_data' => 'back_tasks']]];

        if ($msgId) {
            $this->bot->editMessageText($chatId, $msgId, $text, ['inline_keyboard' => $buttons]);
        } else {
            $this->bot->sendMessageWithInlineKeyboard($chatId, $text, $buttons);
        }
    }

    private function showTaskDetail(int $chatId, int $msgId, int $taskId): void
    {
        $task = PatronageTask::withoutGlobalScopes()
            ->with(['patient:id,full_name,phone,address_text,address_region,address_district', 'discharge:id,diagnosis_text,severity_level,discharge_type', 'hospitalClinic:id,name'])
            ->find($taskId);

        if (!$task) {
            $this->bot->editMessageText($chatId, $msgId, "❌ Vazifa topilmadi.");
            return;
        }

        $patient = $task->patient;
        $discharge = $task->discharge;
        $overdue = $task->due_at?->isPast() && !in_array($task->status, ['completed', 'missed']);

        $sevLabel = match ($discharge?->severity_level) {
            'mild' => 'Yengil',
            'moderate' => 'O\'rtacha',
            'severe' => 'Og\'ir',
            'critical' => 'Juda og\'ir',
            default => '—',
        };

        $statusLabel = match ($task->status) {
            'pending' => '🔴 Kutilmoqda',
            'notified' => '🟡 Xabar berilgan',
            'accepted' => '🟢 Qabul qilingan',
            'in_progress' => '🔵 Jarayonda',
            'completed' => '✅ Bajarilgan',
            default => $task->status,
        };

        $text = "📋 *PATRONAJ VAZIFASI*\n\n";
        $text .= "👤 *Bemor:* {$patient?->full_name}\n";

        if ($patient?->phone) {
            $text .= "📞 *Tel:* {$patient->phone}\n";
        }

        if ($patient?->address_region) {
            $address = $patient->address_region;
            if ($patient->address_district) $address .= ", {$patient->address_district}";
            if ($patient->address_text) $address .= "\n📍 {$patient->address_text}";
            $text .= "🏠 *Manzil:* {$address}\n";
        }

        $text .= "\n";
        $text .= "🏥 *Shifoxona:* {$task->hospitalClinic?->name}\n";
        $text .= "📊 *Og'irlik:* {$sevLabel}\n";

        if ($discharge?->diagnosis_text) {
            $text .= "🔬 *Tashxis:* {$discharge->diagnosis_text}\n";
        }

        $text .= "\n";
        $text .= "📅 *Muddat:* {$task->due_at?->format('d.m.Y H:i')}\n";
        $text .= "📌 *Holat:* {$statusLabel}\n";

        if ($overdue) {
            $minutes = $task->minutesOverdue();
            $text .= "⚠️ *MUDDATI O'TGAN:* {$minutes} daqiqa\n";
        }

        // Build action buttons based on status
        $buttons = [];

        if (in_array($task->status, ['pending', 'notified'])) {
            $buttons[] = [['text' => '✅ Qabul qilish', 'callback_data' => "accept:{$task->id}"]];
        }

        if ($task->status === 'accepted') {
            $buttons[] = [['text' => '🚗 Tashrif boshlash', 'callback_data' => "visit:{$task->id}"]];
        }

        if ($task->status === 'in_progress') {
            $buttons[] = [['text' => '✅ Tashrif yakunlash', 'callback_data' => "complete:{$task->id}"]];
        }

        $buttons[] = [['text' => '◀️ Orqaga', 'callback_data' => 'back_tasks']];

        $this->bot->editMessageText($chatId, $msgId, $text, ['inline_keyboard' => $buttons]);
    }

    private function acceptTask(int $chatId, int $msgId, int $taskId): void
    {
        $doctor = $this->getDoctor($chatId);
        if (!$doctor) return;

        $task = PatronageTask::withoutGlobalScopes()->find($taskId);
        if (!$task) {
            $this->bot->editMessageText($chatId, $msgId, "❌ Vazifa topilmadi.");
            return;
        }

        try {
            $this->patronageService->acceptTask($task, $doctor);

            $this->bot->editMessageText($chatId, $msgId,
                "✅ *Vazifa qabul qilindi!*\n\n"
                . "👤 Bemor: {$task->patient?->full_name}\n"
                . "📅 Muddat: {$task->due_at?->format('d.m.Y H:i')}\n\n"
                . "Tashrif vaqti kelganda \"🚗 Tashrif boshlash\" tugmasini bosing.",
                ['inline_keyboard' => [
                    [['text' => '🚗 Tashrif boshlash', 'callback_data' => "visit:{$task->id}"]],
                    [['text' => '◀️ Vazifalarim', 'callback_data' => 'my_tasks']],
                ]]
            );
        } catch (\Throwable $e) {
            $this->bot->editMessageText($chatId, $msgId, "❌ Xatolik: {$e->getMessage()}");
        }
    }

    private function startVisit(int $chatId, int $msgId, int $taskId): void
    {
        $task = PatronageTask::withoutGlobalScopes()->find($taskId);
        if (!$task) return;

        $task->update(['status' => 'in_progress']);

        $this->bot->editMessageText($chatId, $msgId,
            "🚗 *Tashrif boshlandi!*\n\n"
            . "👤 Bemor: {$task->patient?->full_name}\n\n"
            . "Bemorni ko'rib chiqqaningizdan keyin \"✅ Yakunlash\" tugmasini bosing.",
            ['inline_keyboard' => [
                [['text' => '✅ Tashrif yakunlash', 'callback_data' => "complete:{$task->id}"]],
            ]]
        );
    }

    private function askVisitNotes(int $chatId, int $msgId, int $taskId): void
    {
        $this->bot->editMessageText($chatId, $msgId,
            "📝 *Tashrif natijasi*\n\n"
            . "Bemor holati haqida qisqacha yozing:\n"
            . "_Masalan: Holati yaxshi, harorati normal, dori qabul qilmoqda_\n\n"
            . "Yoki \"O'tkazish\" tugmasini bosing:",
            ['inline_keyboard' => [
                [['text' => '⏭ O\'tkazish', 'callback_data' => "score:{$taskId}:0"]],
            ]]
        );

        Cache::put("pat_bot:{$chatId}", [
            'step' => 'visit_notes',
            'task_id' => $taskId,
        ], now()->addMinutes(30));
    }

    private function handleVisitNotes(int $chatId, string $notes, array $state): void
    {
        $taskId = $state['task_id'];
        Cache::put("pat_bot:{$chatId}", [
            'step' => 'condition_score',
            'task_id' => $taskId,
            'notes' => $notes,
        ], now()->addMinutes(30));

        $this->askConditionScore($chatId, $taskId);
    }

    private function askConditionScore(int $chatId, int $taskId): void
    {
        $buttons = [];
        $row1 = [];
        for ($i = 1; $i <= 5; $i++) {
            $row1[] = ['text' => (string) $i, 'callback_data' => "score:{$taskId}:{$i}"];
        }
        $row2 = [];
        for ($i = 6; $i <= 10; $i++) {
            $row2[] = ['text' => (string) $i, 'callback_data' => "score:{$taskId}:{$i}"];
        }

        $this->bot->sendMessageWithInlineKeyboard($chatId,
            "💊 *Bemor holati bahosi*\n\n"
            . "1 — juda yomon\n"
            . "10 — a'lo\n\n"
            . "Baholang:",
            [$row1, $row2]
        );
    }

    private function setConditionScore(int $chatId, int $msgId, int $taskId, int $score): void
    {
        $state = Cache::get("pat_bot:{$chatId}") ?? [];
        $notes = $state['notes'] ?? null;

        $task = PatronageTask::withoutGlobalScopes()->with('patient:id,full_name')->find($taskId);
        if (!$task) return;

        try {
            $this->patronageService->confirmVisit($task, [
                'visit_notes' => $notes,
                'visit_outcome' => $score >= 7 ? 'improved' : ($score >= 4 ? 'stable' : 'worsened'),
                'patient_condition_score' => $score > 0 ? $score : null,
            ]);

            $scoreDisplay = $score > 0 ? "{$score}/10" : "belgilanmadi";
            $outcomeText = $score >= 7 ? '✅ Yaxshilangan' : ($score >= 4 ? '➖ Barqaror' : '⚠️ Yomonlashgan');

            $this->bot->editMessageText($chatId, $msgId,
                "🎉 *Tashrif muvaffaqiyatli yakunlandi!*\n\n"
                . "👤 Bemor: *{$task->patient?->full_name}*\n"
                . "💊 Holat bahosi: *{$scoreDisplay}*\n"
                . "📊 Natija: {$outcomeText}\n"
                . ($notes ? "📝 Izoh: _{$notes}_\n" : "")
                . "\n✅ Vazifa bajarildi!",
                ['inline_keyboard' => [
                    [['text' => '📋 Vazifalarim', 'callback_data' => 'my_tasks']],
                ]]
            );
        } catch (\Throwable $e) {
            $this->bot->editMessageText($chatId, $msgId, "❌ Xatolik: {$e->getMessage()}");
        }

        Cache::forget("pat_bot:{$chatId}");
    }

    private function showCompletedTasks2(int $chatId, int $msgId): void
    {
        $this->showCompletedTasks($chatId, $msgId);
    }

    private function getDoctor(int $chatId): ?Doctor
    {
        return Doctor::withoutGlobalScopes()
            ->where('telegram_chat_id', (string) $chatId)
            ->where('is_active', true)
            ->first();
    }
}
