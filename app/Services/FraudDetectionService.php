<?php

namespace App\Services;

use App\Models\SurveyResponse;
use Carbon\CarbonInterface;

class FraudDetectionService
{
    /**
     * @param array{
     *     clinic_id:int,
     *     branch_id:int|null,
     *     department_id:int|null,
     *     doctor_id:int|null,
     *     ip_hash:string|null,
     *     device_hash:string|null,
     *     fingerprint_hash:string|null,
     *     submitted_at:CarbonInterface
     * } $context
     * @return array{
     *     score:float,
     *     anomaly_score:float,
     *     is_duplicate:bool,
     *     duplicate_of_response_id:int|null,
     *     flags:array<int,array{type:string,score:float,reason:string,evidence:array}>
     * }
     */
    public function evaluate(array $context): array
    {
        $score = 0.0;
        $anomalyScore = 0.0;
        $flags = [];
        $duplicateOf = null;

        $windowStart = $context['submitted_at']->copy()->subHours(24);
        $burstWindowStart = $context['submitted_at']->copy()->subMinutes(15);

        $baseQuery = SurveyResponse::query()
            ->where('clinic_id', $context['clinic_id'])
            ->where('submitted_at', '>=', $windowStart);

        if ($context['doctor_id']) {
            $baseQuery->where('doctor_id', $context['doctor_id']);
        }

        $ipMatch = null;
        if ($context['ip_hash']) {
            $ipMatch = (clone $baseQuery)
                ->where('ip_hash', $context['ip_hash'])
                ->latest('submitted_at')
                ->first();

            if ($ipMatch) {
                $score += 30;
                $flags[] = [
                    'type' => 'ip_duplicate',
                    'score' => 30,
                    'reason' => 'Duplicate IP hash detected in a 24-hour window.',
                    'evidence' => ['response_id' => $ipMatch->id],
                ];
            }
        }

        $deviceMatch = null;
        if ($context['device_hash']) {
            $deviceMatch = (clone $baseQuery)
                ->where('device_hash', $context['device_hash'])
                ->latest('submitted_at')
                ->first();

            if ($deviceMatch) {
                $score += 40;
                $flags[] = [
                    'type' => 'device_duplicate',
                    'score' => 40,
                    'reason' => 'Duplicate device hash detected in a 24-hour window.',
                    'evidence' => ['response_id' => $deviceMatch->id],
                ];
            }
        }

        $burstCount = SurveyResponse::query()
            ->where('clinic_id', $context['clinic_id'])
            ->where('submitted_at', '>=', $burstWindowStart)
            ->when($context['ip_hash'], fn ($query) => $query->where('ip_hash', $context['ip_hash']))
            ->count();

        if ($burstCount >= 3) {
            $anomalyScore += 25;
            $flags[] = [
                'type' => 'time_burst',
                'score' => 25,
                'reason' => 'Multiple submissions in a short time window.',
                'evidence' => ['burst_count' => $burstCount, 'window_minutes' => 15],
            ];
        }

        if ($context['fingerprint_hash']) {
            $fingerprintCount = (clone $baseQuery)
                ->where('fingerprint_hash', $context['fingerprint_hash'])
                ->count();

            if ($fingerprintCount >= 2) {
                $score += 20;
                $flags[] = [
                    'type' => 'pattern',
                    'score' => 20,
                    'reason' => 'Repeated browser fingerprint detected.',
                    'evidence' => ['count' => $fingerprintCount],
                ];
            }
        }

        if ($deviceMatch) {
            $duplicateOf = $deviceMatch->id;
        } elseif ($ipMatch) {
            $duplicateOf = $ipMatch->id;
        }

        $finalScore = min(100, $score + $anomalyScore);

        return [
            'score' => round($finalScore, 2),
            'anomaly_score' => round(min(100, $anomalyScore), 2),
            'is_duplicate' => $duplicateOf !== null,
            'duplicate_of_response_id' => $duplicateOf,
            'flags' => $flags,
        ];
    }
}

