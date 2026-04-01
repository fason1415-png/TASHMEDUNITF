<?php

namespace App\Jobs;

use App\Models\SurveyResponse;
use App\Services\AiAnalysisService;
use App\Services\AlertService;
use App\Services\RatingSnapshotService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessSurveyResponseJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public int $responseId,
    ) {
    }

    public function handle(
        AiAnalysisService $aiAnalysisService,
        RatingSnapshotService $ratingSnapshotService,
        AlertService $alertService,
    ): void {
        $response = SurveyResponse::query()
            ->with(['answers', 'suspiciousFlags'])
            ->find($this->responseId);

        if (! $response) {
            return;
        }

        $comment = $response->answers
            ->where('question_type', 'comment')
            ->pluck('text_answer')
            ->filter()
            ->join("\n");

        $analysisPayload = null;
        if ($comment !== '') {
            $analysisPayload = $aiAnalysisService->analyzeComment($comment, $response->language);

            $response->commentAnalysis()->updateOrCreate(
                ['survey_response_id' => $response->id],
                [
                    'clinic_id' => $response->clinic_id,
                    'language' => $response->language,
                    'original_comment' => $comment,
                    'cleaned_comment' => $comment,
                    'sentiment_label' => $analysisPayload['sentiment_label'],
                    'sentiment_score' => $analysisPayload['sentiment_score'],
                    'toxicity_score' => $analysisPayload['toxicity_score'],
                    'topics' => $analysisPayload['topics'],
                    'keywords' => $analysisPayload['keywords'],
                    'summary' => $analysisPayload['summary'],
                    'coaching_suggestion' => $analysisPayload['coaching_suggestion'],
                    'explained_flags' => $analysisPayload['explained_flags'],
                    'model_version' => 'fastapi-v1',
                    'processed_at' => now(),
                ],
            );

            foreach ($analysisPayload['explained_flags'] as $flag) {
                $type = $flag['type'] ?? 'ai_anomaly';
                $allowedTypes = ['ip_duplicate', 'device_duplicate', 'time_burst', 'pattern', 'toxicity', 'ai_anomaly', 'manual'];
                $flagType = in_array($type, $allowedTypes, true) ? $type : 'ai_anomaly';

                $response->suspiciousFlags()->create([
                    'clinic_id' => $response->clinic_id,
                    'flag_type' => $flagType,
                    'score' => $flag['score'] ?? 0,
                    'reason' => $flag['reason'] ?? 'AI-generated anomaly flag',
                    'evidence' => ['source' => 'ai_service'],
                ]);
            }
        }

        $toxicity = (float) ($analysisPayload['toxicity_score'] ?? 0);
        $isFlagged = $response->is_flagged || $toxicity >= 0.7;

        $response->update([
            'sentiment_score' => $analysisPayload['sentiment_score'] ?? $response->sentiment_score,
            'is_flagged' => $isFlagged,
            'moderation_status' => $isFlagged ? 'needs_review' : 'approved',
            'ai_processed_at' => now(),
        ]);

        $ratingSnapshotService->refreshFromResponse($response->fresh('clinic'));
        $alertService->createEscalationIfCritical($response, $toxicity > 0 ? $toxicity : null);
    }
}

