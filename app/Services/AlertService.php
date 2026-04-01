<?php

namespace App\Services;

use App\Models\Escalation;
use App\Models\SurveyResponse;

class AlertService
{
    public function createEscalationIfCritical(SurveyResponse $response, ?float $toxicityScore = null): ?Escalation
    {
        $isSeverityCritical = (float) ($response->severity_score ?? 0) >= 4.0;
        $isSentimentCritical = (float) ($response->sentiment_score ?? 0) <= -0.65;
        $isToxic = $toxicityScore !== null && $toxicityScore >= 0.7;

        if (! $isSeverityCritical && ! $isSentimentCritical && ! $isToxic) {
            return null;
        }

        return Escalation::query()->create([
            'clinic_id' => $response->clinic_id,
            'survey_response_id' => $response->id,
            'doctor_id' => $response->doctor_id,
            'branch_id' => $response->branch_id,
            'department_id' => $response->department_id,
            'severity' => $isSeverityCritical || $isToxic ? 'critical' : 'high',
            'category' => 'patient_feedback',
            'title' => 'Critical patient feedback detected',
            'description' => 'Automatic escalation created by AI and scoring rules.',
            'source' => 'auto',
            'status' => 'open',
            'opened_at' => now(),
            'sla_due_at' => now()->addHours(8),
            'meta' => [
                'severity_score' => $response->severity_score,
                'sentiment_score' => $response->sentiment_score,
                'toxicity_score' => $toxicityScore,
            ],
        ]);
    }
}

