<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\SurveyResponse;
use Illuminate\Http\JsonResponse;

class PublicWidgetController extends Controller
{
    public function doctor(Doctor $doctor): JsonResponse
    {
        $query = SurveyResponse::query()
            ->where('clinic_id', $doctor->clinic_id)
            ->where('doctor_id', $doctor->id)
            ->where('moderation_status', 'approved');

        $feedbackCount = (clone $query)->count();
        $qualityScore = (clone $query)->avg('quality_score');
        $confidenceScore = (clone $query)->avg('confidence_score');
        $minSamples = (int) ($doctor->clinic?->min_public_samples ?: config('shiforeyting.minimum_public_samples', 10));

        return response()->json([
            'doctor' => [
                'id' => $doctor->uuid,
                'full_name' => $doctor->full_name,
                'specialty' => $doctor->specialty,
            ],
            'feedback_count' => $feedbackCount,
            'minimum_samples' => $minSamples,
            'score_visible' => $feedbackCount >= $minSamples,
            'quality_score' => $feedbackCount >= $minSamples ? round((float) $qualityScore, 2) : null,
            'confidence_adjusted_score' => $feedbackCount >= $minSamples ? round((float) $confidenceScore, 2) : null,
        ]);
    }

    public function clinic(Clinic $clinic): JsonResponse
    {
        $query = SurveyResponse::query()
            ->where('clinic_id', $clinic->id)
            ->where('moderation_status', 'approved');

        $feedbackCount = (clone $query)->count();
        $qualityScore = (clone $query)->avg('quality_score');
        $confidenceScore = (clone $query)->avg('confidence_score');
        $minSamples = (int) ($clinic->min_public_samples ?: config('shiforeyting.minimum_public_samples', 10));

        return response()->json([
            'clinic' => [
                'id' => $clinic->uuid,
                'name' => $clinic->name,
                'city' => $clinic->city,
            ],
            'feedback_count' => $feedbackCount,
            'minimum_samples' => $minSamples,
            'score_visible' => $feedbackCount >= $minSamples,
            'quality_score' => $feedbackCount >= $minSamples ? round((float) $qualityScore, 2) : null,
            'confidence_adjusted_score' => $feedbackCount >= $minSamples ? round((float) $confidenceScore, 2) : null,
        ]);
    }
}

