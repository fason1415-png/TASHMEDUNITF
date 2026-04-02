<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TelegramWebhookHandler
{
    // Survey steps matching the web form questions
    private array $surveySteps = [
        [
            'key' => 'service_satisfaction',
            'question' => "Bizning xizmatimizdan mamnunmisiz?",
            'type' => 'yes_no',
        ],
        [
            'key' => 'doctor_rating',
            'question' => "Shifokorni baholang",
            'type' => 'rating',
        ],
        [
            'key' => 'communication',
            'question' => "Shifokor sizga tushunarli tarzda tushuntirdimi?",
            'type' => 'rating',
        ],
        [
            'key' => 'waiting_experience',
            'question' => "Kutish vaqtidan mamnunmisiz?",
            'type' => 'rating',
        ],
        [
            'key' => 'overall_rating',
            'question' => "Umumiy baho bering",
            'type' => 'rating',
        ],
        [
            'key' => 'would_recommend',
            'question' => "Klinikamizni boshqalarga tavsiya qilasizmi?",
            'type' => 'recommend',
        ],
        [
            'key' => 'comment',
            'question' => "Taklif va shikoyatlaringiz",
            'type' => 'comment',
        ],
    ];

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

        if (str_starts_with($text, '/start')) {
            $this->handleStart($chatId);
            return;
        }

        if (str_starts_with($text, '/help')) {
            $this->bot->sendMessage($chatId, implode("\n", [
                '🏥 *ShifoReyting Bot*',
                '',
                '📋 Buyruqlar:',
                '/start — So\'rovnomani boshlash',
                '/help — Yordam',
                '',
                '7 ta savol, 30 soniya — oson va tez!',
            ]));
            return;
        }

        $state = Cache::get("tg:{$chatId}");

        if (!$state) {
            $this->bot->sendMessage($chatId, "So'rovnomani boshlash uchun /start buyrug'ini yuboring.");
            return;
        }

        // Handle text comment
        if (($state['step'] ?? '') === 'comment') {
            $this->saveAnswer($chatId, 'comment', $text);
            $this->submitSurvey($chatId);
            return;
        }

        $this->bot->sendMessage($chatId, "Tugmalardan birini tanlang yoki /start buyrug'ini yuboring.");
    }

    private function handleStart(int $chatId): void
    {
        Cache::forget("tg:{$chatId}");

        $clinics = Clinic::withoutGlobalScopes()
            ->where('is_active', true)
            ->orderBy('name')
            ->limit(10)
            ->get();

        if ($clinics->isEmpty()) {
            $this->bot->sendMessage($chatId, 'Hozircha klinikalar mavjud emas.');
            return;
        }

        $buttons = $clinics->map(fn (Clinic $c) => [
            ['text' => $c->name, 'callback_data' => "c:{$c->id}"],
        ])->toArray();

        $this->bot->sendMessageWithInlineKeyboard(
            $chatId,
            "🏥 *ShifoReyting — So'rovnoma*\n\n📍 Klinikani tanlang:",
            $buttons
        );

        Cache::put("tg:{$chatId}", ['step' => 'clinic'], now()->addMinutes(30));
    }

    private function handleCallbackQuery(array $cb): void
    {
        $chatId = $cb['message']['chat']['id'];
        $msgId = $cb['message']['message_id'];
        $data = $cb['data'];

        $this->bot->answerCallbackQuery($cb['id']);

        // Clinic selection
        if (str_starts_with($data, 'c:')) {
            $this->selectClinic($chatId, $msgId, (int) substr($data, 2));
            return;
        }

        // Doctor selection
        if (str_starts_with($data, 'd:')) {
            $this->selectDoctor($chatId, $msgId, (int) substr($data, 2));
            return;
        }

        // Yes/No answer
        if (str_starts_with($data, 'yn:')) {
            $parts = explode(':', $data);
            $this->saveAnswer($chatId, $parts[1], $parts[2] === '1');
            $this->nextQuestion($chatId, $msgId);
            return;
        }

        // Rating answer
        if (str_starts_with($data, 'r:')) {
            $parts = explode(':', $data);
            $this->saveAnswer($chatId, $parts[1], (int) $parts[2]);
            $this->nextQuestion($chatId, $msgId);
            return;
        }

        // Recommend answer
        if (str_starts_with($data, 'rec:')) {
            $val = substr($data, 4) === '1';
            $this->saveAnswer($chatId, 'would_recommend', $val);
            $this->nextQuestion($chatId, $msgId);
            return;
        }

        // Skip comment
        if ($data === 'skip') {
            $this->saveAnswer($chatId, 'comment', null);
            $this->submitSurvey($chatId, $msgId);
            return;
        }

        // Restart
        if ($data === 'restart') {
            $this->handleStart($chatId);
            return;
        }
    }

    private function selectClinic(int $chatId, int $msgId, int $clinicId): void
    {
        $clinic = Clinic::withoutGlobalScopes()->find($clinicId);
        if (!$clinic) {
            $this->bot->editMessageText($chatId, $msgId, "❌ Klinika topilmadi. /start");
            return;
        }

        $doctors = Doctor::withoutGlobalScopes()
            ->where('clinic_id', $clinicId)
            ->where('is_active', true)
            ->where('status', 'active')
            ->orderBy('full_name')
            ->limit(20)
            ->get();

        if ($doctors->isEmpty()) {
            $this->bot->editMessageText($chatId, $msgId, "Bu klinikada shifokorlar topilmadi. /start");
            return;
        }

        $buttons = $doctors->map(fn (Doctor $d) => [
            ['text' => $d->full_name . ($d->specialty ? " ({$d->specialty})" : ''), 'callback_data' => "d:{$d->id}"],
        ])->toArray();

        $this->bot->editMessageText(
            $chatId, $msgId,
            "🏥 *{$clinic->name}*\n\n👨‍⚕️ Shifokorni tanlang:",
            ['inline_keyboard' => $buttons]
        );

        Cache::put("tg:{$chatId}", [
            'step' => 'doctor',
            'clinic_id' => $clinicId,
            'answers' => [],
        ], now()->addMinutes(30));
    }

    private function selectDoctor(int $chatId, int $msgId, int $doctorId): void
    {
        $state = Cache::get("tg:{$chatId}");
        $doctor = Doctor::withoutGlobalScopes()->with('department:id,name')->find($doctorId);

        if (!$doctor) {
            $this->bot->editMessageText($chatId, $msgId, "❌ Shifokor topilmadi. /start");
            return;
        }

        $dept = $doctor->department?->name ?? '';
        $info = "👨‍⚕️ *{$doctor->full_name}*";
        if ($doctor->specialty) $info .= "\n📋 _{$doctor->specialty}_";
        if ($dept) $info .= "\n🏢 {$dept}";

        $state['doctor_id'] = $doctorId;
        $state['doctor_name'] = $doctor->full_name;
        $state['current_q'] = 0;
        $state['answers'] = [];
        Cache::put("tg:{$chatId}", $state, now()->addMinutes(30));

        // Show doctor info then first question
        $this->bot->editMessageText($chatId, $msgId,
            "{$info}\n\n📝 *So'rovnoma boshlanmoqda...*\n7 ta savol | ~30 soniya"
        );

        sleep(1);
        $this->askQuestion($chatId, null, 0);
    }

    private function askQuestion(int $chatId, ?int $msgId, int $index): void
    {
        if ($index >= count($this->surveySteps)) {
            $this->submitSurvey($chatId, $msgId);
            return;
        }

        $q = $this->surveySteps[$index];
        $num = $index + 1;
        $total = count($this->surveySteps);
        $progress = str_repeat('●', $num) . str_repeat('○', $total - $num);

        $header = "{$progress}\n*Savol {$num}/{$total}*\n\n";

        if ($q['type'] === 'yes_no') {
            $buttons = [
                [
                    ['text' => '✅ Ha', 'callback_data' => "yn:{$q['key']}:1"],
                    ['text' => '❌ Yo\'q', 'callback_data' => "yn:{$q['key']}:0"],
                ],
            ];
            $text = $header . "❓ *{$q['question']}*";
            if ($msgId) {
                $this->bot->editMessageText($chatId, $msgId, $text, ['inline_keyboard' => $buttons]);
            } else {
                $this->bot->sendMessageWithInlineKeyboard($chatId, $text, $buttons);
            }
        } elseif ($q['type'] === 'rating') {
            $buttons = [
                [
                    ['text' => '1⭐', 'callback_data' => "r:{$q['key']}:1"],
                    ['text' => '2⭐', 'callback_data' => "r:{$q['key']}:2"],
                    ['text' => '3⭐', 'callback_data' => "r:{$q['key']}:3"],
                    ['text' => '4⭐', 'callback_data' => "r:{$q['key']}:4"],
                    ['text' => '5⭐', 'callback_data' => "r:{$q['key']}:5"],
                ],
            ];
            $text = $header . "⭐ *{$q['question']}*\n_1 — yomon, 5 — a'lo_";
            if ($msgId) {
                $this->bot->editMessageText($chatId, $msgId, $text, ['inline_keyboard' => $buttons]);
            } else {
                $this->bot->sendMessageWithInlineKeyboard($chatId, $text, $buttons);
            }
        } elseif ($q['type'] === 'recommend') {
            $buttons = [
                [
                    ['text' => '👍 Ha, tavsiya qilaman', 'callback_data' => 'rec:1'],
                    ['text' => '👎 Yo\'q', 'callback_data' => 'rec:0'],
                ],
            ];
            $text = $header . "💬 *{$q['question']}*";
            if ($msgId) {
                $this->bot->editMessageText($chatId, $msgId, $text, ['inline_keyboard' => $buttons]);
            } else {
                $this->bot->sendMessageWithInlineKeyboard($chatId, $text, $buttons);
            }
        } elseif ($q['type'] === 'comment') {
            $buttons = [
                [['text' => '⏭ O\'tkazish', 'callback_data' => 'skip']],
            ];
            $text = $header . "✏️ *{$q['question']}*\n_Matn yozing yoki O'tkazish tugmasini bosing_";
            if ($msgId) {
                $this->bot->editMessageText($chatId, $msgId, $text, ['inline_keyboard' => $buttons]);
            } else {
                $this->bot->sendMessageWithInlineKeyboard($chatId, $text, $buttons);
            }

            $state = Cache::get("tg:{$chatId}");
            $state['step'] = 'comment';
            Cache::put("tg:{$chatId}", $state, now()->addMinutes(30));
        }
    }

    private function nextQuestion(int $chatId, int $msgId): void
    {
        $state = Cache::get("tg:{$chatId}");
        $next = ($state['current_q'] ?? 0) + 1;
        $state['current_q'] = $next;
        Cache::put("tg:{$chatId}", $state, now()->addMinutes(30));

        $this->askQuestion($chatId, $msgId, $next);
    }

    private function saveAnswer(int $chatId, string $key, mixed $value): void
    {
        $state = Cache::get("tg:{$chatId}");
        if (!$state) return;

        $state['answers'][$key] = $value;
        Cache::put("tg:{$chatId}", $state, now()->addMinutes(30));
    }

    private function submitSurvey(int $chatId, ?int $msgId = null): void
    {
        $state = Cache::get("tg:{$chatId}");
        if (!$state || !isset($state['doctor_id'])) {
            $this->bot->sendMessage($chatId, "Sessiya tugadi. /start buyrug'ini yuboring.");
            return;
        }

        $answers = $state['answers'] ?? [];
        $clinicId = $state['clinic_id'];
        $doctorId = $state['doctor_id'];
        $doctorName = $state['doctor_name'] ?? '';

        try {
            $survey = Survey::withoutGlobalScopes()
                ->where('clinic_id', $clinicId)
                ->where('is_default', true)
                ->where('is_active', true)
                ->first();

            if ($survey) {
                // Build answers array matching survey question keys
                $submissionAnswers = [];
                foreach ($answers as $key => $value) {
                    if ($value !== null) {
                        $submissionAnswers[$key] = $value;
                    }
                }

                $this->submissionService->submitFromSurvey($survey, [
                    'doctor_id' => $doctorId,
                    'channel' => 'telegram',
                    'language' => 'uz_latn',
                    'answers' => $submissionAnswers,
                ], [
                    'channel' => 'telegram',
                    'ip_hash' => hash('sha256', "tg:{$chatId}"),
                    'device_hash' => hash('sha256', "tg:{$chatId}"),
                    'fingerprint_hash' => hash('sha256', "tg:bot:{$chatId}"),
                    'country' => 'UZ',
                ]);
            } else {
                // Fallback: direct response
                $rating = $answers['overall_rating'] ?? $answers['doctor_rating'] ?? 3;
                SurveyResponse::create([
                    'clinic_id' => $clinicId,
                    'doctor_id' => $doctorId,
                    'channel' => 'telegram',
                    'quality_score' => $rating * 20,
                    'sentiment_score' => ($rating - 3) / 2,
                    'confidence_score' => 50,
                    'submitted_at' => now(),
                    'ip_hash' => hash('sha256', "tg:{$chatId}"),
                    'device_hash' => hash('sha256', "tg:{$chatId}"),
                    'fingerprint_hash' => hash('sha256', "tg:bot:{$chatId}"),
                ]);
            }

            // Build result summary
            $summary = "✅ *Rahmat! Bahoyingiz qabul qilindi.*\n\n";
            $summary .= "👨‍⚕️ {$doctorName}\n\n";

            foreach ($this->surveySteps as $step) {
                $val = $answers[$step['key']] ?? null;
                if ($val === null) continue;

                $icon = match ($step['type']) {
                    'yes_no' => $val ? '✅' : '❌',
                    'rating' => str_repeat('⭐', (int) $val),
                    'recommend' => $val ? '👍' : '👎',
                    'comment' => '💬',
                    default => '📝',
                };

                $display = match ($step['type']) {
                    'yes_no' => $val ? 'Ha' : 'Yo\'q',
                    'rating' => "{$val}/5",
                    'recommend' => $val ? 'Ha' : 'Yo\'q',
                    'comment' => "\"{$val}\"",
                    default => (string) $val,
                };

                $summary .= "{$icon} {$step['question']}: {$display}\n";
            }

            $summary .= "\n🔒 _Ma'lumotlaringiz anonim va xavfsiz_";

            $buttons = [
                [['text' => '🔄 Yana baholash', 'callback_data' => 'restart']],
            ];

            if ($msgId) {
                $this->bot->editMessageText($chatId, $msgId, $summary, ['inline_keyboard' => $buttons]);
            } else {
                $this->bot->sendMessageWithInlineKeyboard($chatId, $summary, $buttons);
            }
        } catch (\Throwable $e) {
            Log::error('Telegram submission error', ['error' => $e->getMessage(), 'chat_id' => $chatId]);
            $this->bot->sendMessage($chatId, "❌ Xatolik yuz berdi. Qayta urinib ko'ring: /start");
        }

        Cache::forget("tg:{$chatId}");
    }
}
