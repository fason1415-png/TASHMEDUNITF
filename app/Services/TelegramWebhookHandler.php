<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TelegramWebhookHandler
{
    public function __construct(
        private TelegramBotService $bot,
        private SurveySubmissionService $submissionService,
    ) {}

    public function handle(array $update): void
    {
        if (isset($update['callback_query'])) {
            $this->handleCallbackQuery($update['callback_query']);

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

        // /start command
        if (str_starts_with($text, '/start')) {
            $this->handleStart($chatId);

            return;
        }

        // /help command
        if (str_starts_with($text, '/help')) {
            $this->bot->sendMessage($chatId, implode("\n", [
                '*ShifoReyting Bot*',
                '',
                'Buyruqlar:',
                '/start - Shifokorni baholashni boshlash',
                '/help - Yordam',
                '',
                'Bemor sifatida shifokorni baholash uchun /start buyrug\'ini yuboring.',
                '',
                'Savollar uchun: @ShifoReyting',
            ]));

            return;
        }

        // Check current state
        $state = Cache::get("telegram_state:{$chatId}");

        if (! $state) {
            $this->bot->sendMessage($chatId, "Shifokorni baholash uchun /start buyrug'ini yuboring.");

            return;
        }

        // Handle comment step
        if ($state['step'] === 'comment') {
            $this->handleComment($chatId, $text, $state);

            return;
        }

        // Default: prompt to use /start
        $this->bot->sendMessage($chatId, "Shifokorni baholash uchun /start buyrug'ini yuboring.");
    }

    private function handleStart(int $chatId): void
    {
        // Clear any existing state
        Cache::forget("telegram_state:{$chatId}");

        // Get active clinics (limit 10) — no auth in webhook, bypass tenant scope
        $clinics = Clinic::withoutGlobalScopes()->where('is_active', true)->orderBy('name')->limit(10)->get();

        if ($clinics->isEmpty()) {
            $this->bot->sendMessage($chatId, 'Hozircha klinikalar mavjud emas.');

            return;
        }

        // Build inline keyboard with clinic buttons
        $buttons = $clinics->map(fn (Clinic $c) => [
            ['text' => $c->name, 'callback_data' => "clinic:{$c->id}"],
        ])->toArray();

        $this->bot->sendMessageWithInlineKeyboard(
            $chatId,
            "*ShifoReyting* - Shifokorni baholash\n\nKlinikani tanlang:",
            $buttons
        );

        Cache::put("telegram_state:{$chatId}", ['step' => 'select_clinic'], now()->addMinutes(30));
    }

    private function handleCallbackQuery(array $callbackQuery): void
    {
        $chatId = $callbackQuery['message']['chat']['id'];
        $messageId = $callbackQuery['message']['message_id'];
        $data = $callbackQuery['data'];
        $callbackQueryId = $callbackQuery['id'];

        $this->bot->answerCallbackQuery($callbackQueryId);

        // Handle clinic selection
        if (str_starts_with($data, 'clinic:')) {
            $clinicId = (int) str_replace('clinic:', '', $data);
            $this->handleClinicSelected($chatId, $messageId, $clinicId);

            return;
        }

        // Handle doctor selection
        if (str_starts_with($data, 'doctor:')) {
            $doctorId = (int) str_replace('doctor:', '', $data);
            $this->handleDoctorSelected($chatId, $messageId, $doctorId);

            return;
        }

        // Handle rating selection
        if (str_starts_with($data, 'rate:')) {
            $rating = (int) str_replace('rate:', '', $data);
            $this->handleRating($chatId, $messageId, $rating);

            return;
        }

        // Handle skip comment
        if ($data === 'skip_comment') {
            $this->handleComment($chatId, null, Cache::get("telegram_state:{$chatId}"));

            return;
        }

        // Handle restart
        if ($data === 'restart') {
            $this->handleStart($chatId);

            return;
        }
    }

    private function handleClinicSelected(int $chatId, int $messageId, int $clinicId): void
    {
        $clinic = Clinic::withoutGlobalScopes()->find($clinicId);
        if (! $clinic) {
            $this->bot->editMessageText($chatId, $messageId, "Klinika topilmadi. /start buyrug'ini yuboring.");

            return;
        }

        // Get active doctors for this clinic — bypass tenant scope
        $doctors = Doctor::withoutGlobalScopes()
            ->where('clinic_id', $clinicId)
            ->where('is_active', true)
            ->where('status', 'active')
            ->orderBy('full_name')
            ->limit(20)
            ->get();

        if ($doctors->isEmpty()) {
            $this->bot->editMessageText($chatId, $messageId, "Bu klinikada shifokorlar topilmadi. /start buyrug'ini yuboring.");

            return;
        }

        $buttons = $doctors->map(fn (Doctor $d) => [
            ['text' => $d->full_name.($d->specialty ? " ({$d->specialty})" : ''), 'callback_data' => "doctor:{$d->id}"],
        ])->toArray();

        $this->bot->editMessageText(
            $chatId,
            $messageId,
            "*{$clinic->name}*\n\nShifokorni tanlang:",
            ['inline_keyboard' => $buttons]
        );

        Cache::put("telegram_state:{$chatId}", [
            'step' => 'select_doctor',
            'clinic_id' => $clinicId,
        ], now()->addMinutes(30));
    }

    private function handleDoctorSelected(int $chatId, int $messageId, int $doctorId): void
    {
        $state = Cache::get("telegram_state:{$chatId}");
        $doctor = Doctor::withoutGlobalScopes()->find($doctorId);

        if (! $doctor) {
            $this->bot->editMessageText($chatId, $messageId, "Shifokor topilmadi. /start buyrug'ini yuboring.");

            return;
        }

        // Show rating buttons
        $buttons = [
            [
                ['text' => '1', 'callback_data' => 'rate:1'],
                ['text' => '2', 'callback_data' => 'rate:2'],
                ['text' => '3', 'callback_data' => 'rate:3'],
                ['text' => '4', 'callback_data' => 'rate:4'],
                ['text' => '5', 'callback_data' => 'rate:5'],
            ],
        ];

        $this->bot->editMessageText(
            $chatId,
            $messageId,
            "*{$doctor->full_name}*".($doctor->specialty ? "\n_{$doctor->specialty}_" : '')."\n\nBaholang (1-5):",
            ['inline_keyboard' => $buttons]
        );

        Cache::put("telegram_state:{$chatId}", [
            'step' => 'rate',
            'clinic_id' => $state['clinic_id'] ?? $doctor->clinic_id,
            'doctor_id' => $doctorId,
        ], now()->addMinutes(30));
    }

    private function handleRating(int $chatId, int $messageId, int $rating): void
    {
        $state = Cache::get("telegram_state:{$chatId}");

        $stars = str_repeat('*', $rating);

        $buttons = [
            [['text' => "O'tkazish", 'callback_data' => 'skip_comment']],
        ];

        $this->bot->editMessageText(
            $chatId,
            $messageId,
            "Bahoyingiz: {$rating}/5\n\nIzoh yozing yoki O'tkazish tugmasini bosing:",
            ['inline_keyboard' => $buttons]
        );

        Cache::put("telegram_state:{$chatId}", [
            'step' => 'comment',
            'clinic_id' => $state['clinic_id'],
            'doctor_id' => $state['doctor_id'],
            'rating' => $rating,
        ], now()->addMinutes(30));
    }

    private function handleComment(int $chatId, ?string $comment, ?array $state): void
    {
        if (! $state || ! isset($state['doctor_id'], $state['rating'])) {
            $this->bot->sendMessage($chatId, "Sessiya tugadi. /start buyrug'ini yuboring.");

            return;
        }

        // Find the clinic's default survey
        $survey = Survey::withoutGlobalScopes()
            ->where('clinic_id', $state['clinic_id'])
            ->where('is_default', true)
            ->where('is_active', true)
            ->first();

        // Submit the feedback
        try {
            if ($survey) {
                $this->submissionService->submitFromSurvey($survey, [
                    'doctor_id' => $state['doctor_id'],
                    'answers' => [
                        ['key' => 'overall_rating', 'type' => 'rating', 'value' => $state['rating']],
                        ...($comment ? [['key' => 'comment', 'type' => 'text', 'value' => $comment]] : []),
                    ],
                ], [
                    'channel' => 'telegram',
                    'ip_hash' => hash('sha256', "telegram:{$chatId}"),
                    'device_hash' => hash('sha256', "telegram:{$chatId}"),
                    'fingerprint_hash' => hash('sha256', "telegram:bot:{$chatId}"),
                    'country' => 'UZ',
                ]);
            } else {
                // Direct SurveyResponse creation if no default survey
                SurveyResponse::create([
                    'clinic_id' => $state['clinic_id'],
                    'doctor_id' => $state['doctor_id'],
                    'channel' => 'telegram',
                    'quality_score' => $state['rating'] * 20, // 1-5 -> 20-100
                    'sentiment_score' => ($state['rating'] - 3) / 2, // normalize to -1..1
                    'confidence_score' => 50, // default for telegram
                    'submitted_at' => now(),
                    'ip_hash' => hash('sha256', "telegram:{$chatId}"),
                    'device_hash' => hash('sha256', "telegram:{$chatId}"),
                    'fingerprint_hash' => hash('sha256', "telegram:bot:{$chatId}"),
                ]);
            }

            $buttons = [
                [['text' => 'Yana baholash', 'callback_data' => 'restart']],
            ];

            $this->bot->sendMessageWithInlineKeyboard(
                $chatId,
                "Rahmat! Bahoyingiz qabul qilindi.\n\nSizning ma'lumotlaringiz anonim va xavfsiz saqlanadi.",
                $buttons
            );
        } catch (\Throwable $e) {
            Log::error('Telegram bot submission error', ['error' => $e->getMessage(), 'chat_id' => $chatId]);
            $this->bot->sendMessage($chatId, "Xatolik yuz berdi. Iltimos, qayta urinib ko'ring. /start");
        }

        // Clear state
        Cache::forget("telegram_state:{$chatId}");
    }
}
