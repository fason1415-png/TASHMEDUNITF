<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAnalysisService
{
    /**
     * @return array{
     *     sentiment_label:string|null,
     *     sentiment_score:float|null,
     *     toxicity_score:float|null,
     *     topics:array<int,string>,
     *     keywords:array<int,string>,
     *     summary:string|null,
     *     coaching_suggestion:string|null,
     *     explained_flags:array<int,array{type:string,score:float,reason:string}>
     * }
     */
    public function analyzeComment(string $comment, string $language = 'uz_latn'): array
    {
        $baseUrl = rtrim((string) config('services.shiforeyting_ai.base_url'), '/');
        $timeoutSeconds = (int) config('services.shiforeyting_ai.timeout_seconds', 8);

        if ($baseUrl === '') {
            return $this->fallback($comment);
        }

        try {
            $response = Http::timeout($timeoutSeconds)
                ->acceptJson()
                ->post($baseUrl.'/analyze', [
                    'text' => $comment,
                    'language' => $language,
                ]);

            if (! $response->successful()) {
                return $this->fallback($comment);
            }

            $payload = $response->json();

            return [
                'sentiment_label' => $payload['sentiment']['label'] ?? null,
                'sentiment_score' => isset($payload['sentiment']['score']) ? (float) $payload['sentiment']['score'] : null,
                'toxicity_score' => isset($payload['toxicity']['score']) ? (float) $payload['toxicity']['score'] : null,
                'topics' => $payload['topics'] ?? [],
                'keywords' => $payload['keywords'] ?? [],
                'summary' => $payload['summary'] ?? null,
                'coaching_suggestion' => $payload['coaching_suggestion'] ?? null,
                'explained_flags' => $payload['flags'] ?? [],
            ];
        } catch (\Throwable $exception) {
            Log::warning('AI analyze endpoint failed, fallback will be used.', [
                'error' => $exception->getMessage(),
            ]);

            return $this->fallback($comment);
        }
    }

    /**
     * @return array{
     *     sentiment_label:string,
     *     sentiment_score:float,
     *     toxicity_score:float,
     *     topics:array<int,string>,
     *     keywords:array<int,string>,
     *     summary:string,
     *     coaching_suggestion:string,
     *     explained_flags:array<int,array{type:string,score:float,reason:string}>
     * }
     */
    private function fallback(string $comment): array
    {
        $normalized = mb_strtolower(trim($comment));
        $negativeTokens = ['yomon', 'плохо', 'bad', 'kutdim', 'ожидал', 'navbat'];
        $positiveTokens = ['yaxshi', 'отлично', 'good', 'rahmat', 'спасибо'];

        $negativeHits = collect($negativeTokens)->filter(fn (string $token) => str_contains($normalized, $token))->count();
        $positiveHits = collect($positiveTokens)->filter(fn (string $token) => str_contains($normalized, $token))->count();

        $sentimentScore = 0.5 + (($positiveHits - $negativeHits) * 0.1);
        $sentimentScore = max(0.0, min(1.0, $sentimentScore));
        $sentimentLabel = $sentimentScore >= 0.65 ? 'positive' : ($sentimentScore <= 0.4 ? 'negative' : 'neutral');

        $toxicityScore = (float) (str_contains($normalized, 'ahmoq') || str_contains($normalized, 'идиот') ? 0.7 : 0.1);

        return [
            'sentiment_label' => $sentimentLabel,
            'sentiment_score' => round($sentimentScore, 2),
            'toxicity_score' => round($toxicityScore, 2),
            'topics' => $negativeHits > 0 ? ['service', 'waiting_time'] : ['service'],
            'keywords' => array_values(array_filter(explode(' ', $normalized))),
            'summary' => mb_substr($comment, 0, 180),
            'coaching_suggestion' => $negativeHits > 0
                ? 'Focus on communication and waiting-time transparency.'
                : 'Keep current quality level and reinforce strengths.',
            'explained_flags' => $toxicityScore >= 0.6 ? [[
                'type' => 'toxicity',
                'score' => $toxicityScore * 100,
                'reason' => 'Potential abusive language was detected.',
            ]] : [],
        ];
    }
}

