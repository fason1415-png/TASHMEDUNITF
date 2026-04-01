<?php

namespace App\Services;

use App\Models\Clinic;

class ScoringEngineService
{
    /**
     * @param array<string, float|int|null> $scoresByKey
     */
    public function calculateQualityScore(array $scoresByKey, Clinic $clinic): float
    {
        $weights = $clinic->scoring_weights ?: config('shiforeyting.default_scoring_weights', []);
        $weightTotal = 0.0;
        $scoreTotal = 0.0;

        foreach ($weights as $key => $weight) {
            $normalizedWeight = (float) $weight;
            if ($normalizedWeight <= 0) {
                continue;
            }

            $value = $scoresByKey[$key] ?? null;
            if ($value === null) {
                continue;
            }

            $weightTotal += $normalizedWeight;
            $scoreTotal += ((float) $value) * $normalizedWeight;
        }

        if ($weightTotal === 0.0) {
            return 0.0;
        }

        return round($scoreTotal / $weightTotal, 2);
    }

    public function confidenceAdjustedScore(float $qualityScore, int $feedbackCount, int $minSamples): float
    {
        if ($feedbackCount <= 0 || $qualityScore <= 0) {
            return 0.0;
        }

        $ratio = min(1.0, sqrt($feedbackCount / max(1, $minSamples)));

        return round($qualityScore * $ratio, 2);
    }
}

