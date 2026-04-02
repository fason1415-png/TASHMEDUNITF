<?php

namespace App\Services;

use App\Models\SurveyAnswer;
use App\Models\SurveyResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use OpenAI\Laravel\Facades\OpenAI;

class AiCommentAnalysisService
{
    private array $negativeWords = [
        'yomon', 'qarama', 'qaram', 'ishlamay', 'sekin', 'kutish', 'kutdim', 'navbat',
        'qo\'pol', 'gap bermadi', 'tushuntirmadi', 'e\'tiborsiz', 'noqulay', 'muammo',
        'shikoyat', 'noroziman', 'norozilik', 'ko\'p kutdim', 'uzoq', 'iflos', 'kechikdi',
        'bilmaydi', 'bo\'lmadi', 'achinarli', 'dahshat', 'zo\'ravonlik', 'qimmat',
        'yuq', 'yo\'q', 'kelmadi', 'javob bermadi', 'hurmat', 'xafa', 'norozi',
        'плохо', 'долго', 'грубо', 'жалоба', 'не доволен', 'ужас',
    ];

    private array $positiveWords = [
        'raxmat', 'rahmat', 'zo\'r', 'yaxshi', 'ajoyib', 'a\'lo', 'chiroyli', 'mamnun',
        'professional', 'tez', 'toza', 'qulay', 'mehribon', 'e\'tiborli', 'malakali',
        'tavsiya', 'juda yaxshi', 'minnatdor', 'barakalla', 'like', 'super', 'excellent',
        'спасибо', 'хорошо', 'отлично', 'молодец', 'доволен',
    ];

    private array $issueCategories = [
        'kutish_vaqti' => [
            'label' => 'Uzoq kutish vaqti',
            'words' => ['kutish', 'kutdim', 'navbat', 'uzoq', 'sekin', 'kechikdi', 'soat', 'долго'],
            'severity' => 'high',
        ],
        'muloqot' => [
            'label' => 'Shifokor muloqoti yomon',
            'words' => ['qo\'pol', 'gap bermadi', 'tushuntirmadi', 'e\'tiborsiz', 'javob bermadi', 'hurmat', 'грубо'],
            'severity' => 'high',
        ],
        'sifat' => [
            'label' => 'Xizmat sifati past',
            'words' => ['yomon', 'sifat', 'bilmaydi', 'bo\'lmadi', 'qaramadi', 'qaram', 'ishlamay', 'плохо'],
            'severity' => 'medium',
        ],
        'tozalik' => [
            'label' => 'Tozalik va gigiyena',
            'words' => ['iflos', 'toza', 'kir', 'hojatxona', 'yuvish', 'dezinfek'],
            'severity' => 'medium',
        ],
        'narx' => [
            'label' => 'Narxlar qimmat',
            'words' => ['qimmat', 'narx', 'pul', 'arzon', 'to\'lov', 'дорого'],
            'severity' => 'low',
        ],
        'norozilik' => [
            'label' => 'Umumiy norozilik',
            'words' => ['noroziman', 'norozilik', 'shikoyat', 'xafa', 'norozi', 'жалоба', 'не доволен'],
            'severity' => 'medium',
        ],
    ];

    public function analyze(?int $clinicId = null): array
    {
        $cacheKey = 'ai_comment_analysis_' . ($clinicId ?? 'all');

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($clinicId) {
            $comments = $this->getRecentComments($clinicId);

            if ($comments->isEmpty()) {
                return [
                    'summary' => 'Hozircha izohlar mavjud emas.',
                    'problems' => [],
                    'positive' => [],
                    'stats' => ['total' => 0, 'negative' => 0, 'positive' => 0, 'neutral' => 0],
                ];
            }

            // Try OpenAI first
            $aiResult = $this->tryOpenAi($comments);
            if ($aiResult) {
                return $aiResult;
            }

            // Fallback: local smart analysis
            return $this->localAnalysis($comments, $clinicId);
        });
    }

    public function clearCache(?int $clinicId = null): void
    {
        Cache::forget('ai_comment_analysis_' . ($clinicId ?? 'all'));
    }

    private function tryOpenAi(Collection $comments): ?array
    {
        if (!config('openai.api_key')) {
            return null;
        }

        $commentTexts = $comments->map(function ($c) {
            $doctor = $c->response?->doctor?->full_name ?? 'Noma\'lum';
            $clinic = $c->response?->clinic?->name ?? '';
            $rating = $c->response?->quality_score ?? '?';
            return "- \"{$c->text_answer}\" (shifokor: {$doctor}, klinika: {$clinic}, sifat bali: {$rating})";
        })->implode("\n");

        try {
            $result = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'temperature' => 0.3,
                'max_tokens' => 1500,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Sen tibbiyot sohasida bemor shikoyatlarini tahlil qiluvchi AI assistantsan. Javobni faqat JSON formatda ber. O\'zbek tilida javob ber.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Quyidagi bemor izohlarini tahlil qil va JSON formatda javob ber:\n\n{$commentTexts}\n\n{\"summary\":\"...\",\"problems\":[{\"issue\":\"...\",\"count\":1,\"severity\":\"high/medium/low\",\"description\":\"...\",\"doctors\":[\"...\"]}],\"positive\":[{\"theme\":\"...\",\"count\":1,\"description\":\"...\"}],\"stats\":{\"total\":0,\"negative\":0,\"positive\":0,\"neutral\":0}}",
                    ],
                ],
            ]);

            $content = $result->choices[0]->message->content;
            $content = preg_replace('/```json\s*|\s*```/', '', $content);
            $parsed = json_decode(trim($content), true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $parsed;
            }
        } catch (\Throwable $e) {
            // Fall through to local analysis
        }

        return null;
    }

    private function localAnalysis(Collection $comments, ?int $clinicId): array
    {
        $total = $comments->count();
        $negativeComments = collect();
        $positiveComments = collect();
        $neutralComments = collect();

        // Classify each comment
        foreach ($comments as $comment) {
            $text = mb_strtolower($comment->text_answer);
            $isNegative = $this->matchesWords($text, $this->negativeWords);
            $isPositive = $this->matchesWords($text, $this->positiveWords);

            if ($isNegative && !$isPositive) {
                $negativeComments->push($comment);
            } elseif ($isPositive && !$isNegative) {
                $positiveComments->push($comment);
            } else {
                $neutralComments->push($comment);
            }
        }

        // Detect problems by category
        $problems = [];
        foreach ($this->issueCategories as $key => $category) {
            $matched = $comments->filter(fn ($c) => $this->matchesWords(mb_strtolower($c->text_answer), $category['words']));

            if ($matched->isNotEmpty()) {
                $doctors = $matched
                    ->map(fn ($c) => $c->response?->doctor?->full_name)
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();

                $problems[] = [
                    'issue' => $category['label'],
                    'count' => $matched->count(),
                    'severity' => $category['severity'],
                    'description' => $this->getIssueDescription($key, $matched->count()),
                    'doctors' => $doctors,
                ];
            }
        }

        // Sort problems by count desc
        usort($problems, fn ($a, $b) => $b['count'] <=> $a['count']);

        // Detect positive themes
        $positiveThemes = [];
        $gratitude = $positiveComments->filter(fn ($c) => $this->matchesWords(mb_strtolower($c->text_answer), ['raxmat', 'rahmat', 'minnatdor', 'спасибо', 'barakalla']));
        if ($gratitude->isNotEmpty()) {
            $positiveThemes[] = [
                'theme' => 'Minnatdorchilik',
                'count' => $gratitude->count(),
                'description' => 'Bemorlar shifokorlarga minnatdorchilik bildirmoqda',
            ];
        }

        $praiseQuality = $positiveComments->filter(fn ($c) => $this->matchesWords(mb_strtolower($c->text_answer), ['zo\'r', 'ajoyib', 'a\'lo', 'super', 'excellent', 'отлично', 'professional']));
        if ($praiseQuality->isNotEmpty()) {
            $positiveThemes[] = [
                'theme' => 'Yuqori sifat',
                'count' => $praiseQuality->count(),
                'description' => 'Xizmat sifati yuqori deb baholangan',
            ];
        }

        $kindness = $positiveComments->filter(fn ($c) => $this->matchesWords(mb_strtolower($c->text_answer), ['mehribon', 'e\'tiborli', 'yaxshi', 'хорошо', 'молодец']));
        if ($kindness->isNotEmpty()) {
            $positiveThemes[] = [
                'theme' => 'Mehribonlik',
                'count' => $kindness->count(),
                'description' => 'Shifokorlarning mehribonligi ta\'kidlangan',
            ];
        }

        // Get low-rated responses for additional context
        $lowRatedCount = $this->getLowRatedCount($clinicId);

        // Build summary
        $summary = $this->buildSummary($total, $negativeComments->count(), $positiveComments->count(), $problems, $lowRatedCount);

        return [
            'summary' => $summary,
            'problems' => array_slice($problems, 0, 5),
            'positive' => array_slice($positiveThemes, 0, 3),
            'stats' => [
                'total' => $total,
                'negative' => $negativeComments->count(),
                'positive' => $positiveComments->count(),
                'neutral' => $neutralComments->count(),
            ],
        ];
    }

    private function matchesWords(string $text, array $words): bool
    {
        foreach ($words as $word) {
            if (str_contains($text, $word)) {
                return true;
            }
        }
        return false;
    }

    private function getIssueDescription(string $key, int $count): string
    {
        return match ($key) {
            'kutish_vaqti' => "{$count} ta bemor kutish vaqti uzoqligidan shikoyat qilgan",
            'muloqot' => "{$count} ta bemor shifokor muloqotidan norozi",
            'sifat' => "{$count} ta bemor xizmat sifatidan qoniqmagan",
            'tozalik' => "{$count} ta bemor tozalik haqida shikoyat bildirgan",
            'narx' => "{$count} ta bemor narxlar qimmatligini ta'kidlagan",
            'norozilik' => "{$count} ta bemor umumiy norozilik bildirgan",
            default => "{$count} ta izohda qayd etilgan",
        };
    }

    private function getLowRatedCount(?int $clinicId): int
    {
        return SurveyResponse::query()
            ->when($clinicId, fn ($q) => $q->where('clinic_id', $clinicId))
            ->where('submitted_at', '>=', now()->subDays(30))
            ->where('quality_score', '<', 40)
            ->count();
    }

    private function buildSummary(int $total, int $negative, int $positive, array $problems, int $lowRated): string
    {
        $parts = ["Oxirgi 30 kunda {$total} ta izoh tahlil qilindi."];

        if ($negative > 0) {
            $parts[] = "{$negative} ta salbiy izoh aniqlandi.";
        }

        if ($positive > 0) {
            $parts[] = "{$positive} ta ijobiy izoh bor.";
        }

        if (!empty($problems)) {
            $topProblem = $problems[0]['issue'] ?? '';
            $parts[] = "Eng ko'p takrorlanuvchi muammo: {$topProblem}.";
        }

        if ($lowRated > 0) {
            $parts[] = "Past baholangan javoblar soni: {$lowRated} ta.";
        }

        if ($negative === 0 && empty($problems)) {
            $parts[] = "Jiddiy muammolar aniqlanmadi.";
        }

        return implode(' ', $parts);
    }

    private function getRecentComments(?int $clinicId = null): Collection
    {
        return SurveyAnswer::query()
            ->whereNotNull('text_answer')
            ->where('text_answer', '!=', '')
            ->whereHas('response', function ($q) use ($clinicId) {
                $q->where('submitted_at', '>=', now()->subDays(30));
                if ($clinicId) {
                    $q->where('clinic_id', $clinicId);
                }
            })
            ->with([
                'response:id,doctor_id,clinic_id,quality_score',
                'response.doctor:id,full_name',
                'response.clinic:id,name',
            ])
            ->latest()
            ->limit(50)
            ->get();
    }
}
