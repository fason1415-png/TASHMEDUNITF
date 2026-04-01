<?php

namespace App\Services;

use App\Models\RatingSnapshot;
use App\Models\SurveyResponse;
use Carbon\CarbonImmutable;

class RatingSnapshotService
{
    public function __construct(
        protected ScoringEngineService $scoringEngineService,
    ) {
    }

    public function refreshFromResponse(SurveyResponse $response): void
    {
        $periodStart = CarbonImmutable::parse($response->submitted_at)->startOfDay();
        $periodEnd = CarbonImmutable::parse($response->submitted_at)->endOfDay();

        $query = SurveyResponse::query()
            ->where('clinic_id', $response->clinic_id)
            ->whereBetween('submitted_at', [$periodStart, $periodEnd])
            ->where('moderation_status', '!=', 'rejected');

        if ($response->doctor_id) {
            $query->where('doctor_id', $response->doctor_id);
        }

        $feedbackCount = (clone $query)->count();
        $qualityScore = (float) (clone $query)->avg('quality_score');
        $sentimentScore = (float) (clone $query)->avg('sentiment_score');
        $flaggedCount = (clone $query)->where('is_flagged', true)->count();

        $minSamples = (int) ($response->clinic?->min_public_samples ?: config('shiforeyting.minimum_public_samples', 10));
        $confidenceScore = $this->scoringEngineService->confidenceAdjustedScore($qualityScore, $feedbackCount, $minSamples);

        RatingSnapshot::query()->updateOrCreate(
            [
                'clinic_id' => $response->clinic_id,
                'branch_id' => $response->branch_id,
                'department_id' => $response->department_id,
                'doctor_id' => $response->doctor_id,
                'period_type' => 'daily',
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
            ],
            [
                'feedback_count' => $feedbackCount,
                'flagged_count' => $flaggedCount,
                'quality_score' => round($qualityScore, 2),
                'confidence_adjusted_score' => $confidenceScore,
                'sentiment_score' => round($sentimentScore, 2),
            ],
        );
    }
}

