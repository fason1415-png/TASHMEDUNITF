<?php

namespace App\Services;

use App\Jobs\ProcessSurveyResponseJob;
use App\Models\QrCode;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SurveySubmissionService
{
    public function __construct(
        protected FraudDetectionService $fraudDetectionService,
        protected ScoringEngineService $scoringEngineService,
    ) {
    }

    /**
     * @param array<string,mixed> $payload
     * @param array{ip_hash:string|null,device_hash:string|null,fingerprint_hash:string|null,country:string|null} $requestMeta
     */
    public function submitFromQrCode(QrCode $qrCode, array $payload, array $requestMeta): SurveyResponse
    {
        $survey = Survey::query()
            ->where('clinic_id', $qrCode->clinic_id)
            ->where('is_active', true)
            ->where('is_default', true)
            ->first()
            ?? Survey::query()
                ->where('clinic_id', $qrCode->clinic_id)
                ->where('is_active', true)
                ->first();

        abort_unless($survey, 422, 'No active survey template found for this clinic.');

        return $this->submit($survey, $payload, $requestMeta, $qrCode);
    }

    /**
     * @param array<string,mixed> $payload
     * @param array{ip_hash:string|null,device_hash:string|null,fingerprint_hash:string|null,country:string|null} $requestMeta
     */
    public function submitFromSurvey(Survey $survey, array $payload, array $requestMeta): SurveyResponse
    {
        return $this->submit($survey, $payload, $requestMeta, null);
    }

    /**
     * @param array<string,mixed> $payload
     * @param array{ip_hash:string|null,device_hash:string|null,fingerprint_hash:string|null,country:string|null} $requestMeta
     */
    private function submit(Survey $survey, array $payload, array $requestMeta, ?QrCode $qrCode): SurveyResponse
    {
        return DB::transaction(function () use ($survey, $payload, $requestMeta, $qrCode): SurveyResponse {
            if ($survey->require_token_verification && empty($payload['verified_token'])) {
                throw ValidationException::withMessages([
                    'verified_token' => 'This survey requires a verification token.',
                ]);
            }

            $submittedAt = now();
            $answersPayload = Arr::get($payload, 'answers', []);
            $language = (string) ($payload['language'] ?? app()->getLocale());
            $callbackRequested = (bool) ($payload['callback_requested'] ?? false);
            $callbackContact = $callbackRequested && ! empty($payload['callback_contact'])
                ? Crypt::encryptString((string) $payload['callback_contact'])
                : null;

            $doctorId = $qrCode?->doctor_id ?? Arr::get($payload, 'doctor_id');
            $branchId = $qrCode?->branch_id ?? Arr::get($payload, 'branch_id');
            $departmentId = $qrCode?->department_id ?? Arr::get($payload, 'department_id');
            $servicePointId = $qrCode?->service_point_id ?? Arr::get($payload, 'service_point_id');

            $fraud = $this->fraudDetectionService->evaluate([
                'clinic_id' => (int) $survey->clinic_id,
                'branch_id' => $branchId ? (int) $branchId : null,
                'department_id' => $departmentId ? (int) $departmentId : null,
                'doctor_id' => $doctorId ? (int) $doctorId : null,
                'ip_hash' => $requestMeta['ip_hash'],
                'device_hash' => $requestMeta['device_hash'],
                'fingerprint_hash' => $requestMeta['fingerprint_hash'],
                'submitted_at' => $submittedAt,
            ]);

            $response = SurveyResponse::query()->create([
                'clinic_id' => $survey->clinic_id,
                'branch_id' => $branchId,
                'department_id' => $departmentId,
                'doctor_id' => $doctorId,
                'service_point_id' => $servicePointId,
                'qr_code_id' => $qrCode?->id,
                'survey_id' => $survey->id,
                'channel' => $payload['channel'] ?? ($qrCode ? 'qr' : 'shortlink'),
                'submitted_at' => $submittedAt,
                'language' => $language,
                'ip_hash' => $requestMeta['ip_hash'],
                'device_hash' => $requestMeta['device_hash'],
                'fingerprint_hash' => $requestMeta['fingerprint_hash'],
                'verified_token' => Arr::get($payload, 'verified_token'),
                'fraud_score' => $fraud['score'],
                'anomaly_score' => $fraud['anomaly_score'],
                'is_flagged' => $fraud['score'] >= 60,
                'moderation_status' => $fraud['score'] >= 60 ? 'needs_review' : 'pending',
                'is_duplicate' => $fraud['is_duplicate'],
                'duplicate_of_response_id' => $fraud['duplicate_of_response_id'],
                'callback_requested' => $callbackRequested,
                'callback_contact' => $callbackContact,
                'callback_note' => Arr::get($payload, 'callback_note'),
                'submitted_from_country' => $requestMeta['country'],
            ]);

            foreach ($fraud['flags'] as $flag) {
                $response->suspiciousFlags()->create([
                    'clinic_id' => $response->clinic_id,
                    'flag_type' => $flag['type'],
                    'score' => $flag['score'],
                    'reason' => $flag['reason'],
                    'evidence' => $flag['evidence'],
                ]);
            }

            $dimensionScores = [
                'service_quality' => null,
                'communication' => null,
                'waiting_experience' => null,
                'explanation_quality' => null,
                'resolution_quality' => null,
                'sentiment' => 50,
            ];
            $severityScore = null;

            $questions = $survey->questions()->with('options')->get();

            /** @var SurveyQuestion $question */
            foreach ($questions as $question) {
                $rawAnswer = $answersPayload[$question->key] ?? null;
                $parsed = $this->parseAnswer($question, $rawAnswer);

                if ($question->type === 'severity' && $parsed['severity_level'] !== null) {
                    $severityScore = (float) $parsed['severity_level'];
                }

                $mappedDimension = $this->mapDimensionByKey($question->key);
                if ($mappedDimension && $parsed['normalized_score'] !== null) {
                    $dimensionScores[$mappedDimension] = $parsed['normalized_score'];
                }

                $response->answers()->create([
                    'clinic_id' => $response->clinic_id,
                    'survey_question_id' => $question->id,
                    'question_type' => $question->type,
                    'rating_value' => $parsed['rating_value'],
                    'boolean_value' => $parsed['boolean_value'],
                    'option_value' => $parsed['option_value'],
                    'nps_value' => $parsed['nps_value'],
                    'severity_level' => $parsed['severity_level'],
                    'text_answer' => $parsed['text_answer'],
                    'normalized_score' => $parsed['normalized_score'],
                ]);
            }

            $qualityScore = $this->scoringEngineService->calculateQualityScore($dimensionScores, $survey->clinic);
            $feedbackCount = SurveyResponse::query()
                ->where('clinic_id', $response->clinic_id)
                ->where('doctor_id', $response->doctor_id)
                ->count();

            $confidenceScore = $this->scoringEngineService->confidenceAdjustedScore(
                $qualityScore,
                $feedbackCount,
                (int) ($survey->clinic->min_public_samples ?: config('shiforeyting.minimum_public_samples', 10))
            );

            $response->update([
                'quality_score' => $qualityScore,
                'severity_score' => $severityScore,
                'confidence_score' => $confidenceScore,
            ]);

            ProcessSurveyResponseJob::dispatch($response->id)->onQueue('ai');

            return $response->refresh();
        });
    }

    /**
     * @param mixed $rawAnswer
     * @return array{
     *     rating_value:int|null,
     *     boolean_value:bool|null,
     *     option_value:string|null,
     *     nps_value:int|null,
     *     severity_level:int|null,
     *     text_answer:string|null,
     *     normalized_score:float|null
     * }
     */
    private function parseAnswer(SurveyQuestion $question, mixed $rawAnswer): array
    {
        $parsed = [
            'rating_value' => null,
            'boolean_value' => null,
            'option_value' => null,
            'nps_value' => null,
            'severity_level' => null,
            'text_answer' => null,
            'normalized_score' => null,
        ];

        if ($rawAnswer === null || $rawAnswer === '') {
            return $parsed;
        }

        switch ($question->type) {
            case 'rating':
                $rating = max(1, min(5, (int) $rawAnswer));
                $parsed['rating_value'] = $rating;
                $parsed['normalized_score'] = (float) ($rating * 20);
                break;
            case 'yes_no':
            case 'recommend':
                $bool = filter_var($rawAnswer, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($bool === null) {
                    $bool = in_array((string) $rawAnswer, ['1', 'yes', 'ha', 'да'], true);
                }
                $parsed['boolean_value'] = (bool) $bool;
                $parsed['normalized_score'] = $parsed['boolean_value'] ? 100.0 : 0.0;
                break;
            case 'single_choice':
                $parsed['option_value'] = (string) $rawAnswer;
                $option = $question->options->firstWhere('value', (string) $rawAnswer);
                $parsed['normalized_score'] = $option?->score_value !== null
                    ? (float) $option->score_value
                    : 50.0;
                break;
            case 'nps':
                $nps = max(0, min(10, (int) $rawAnswer));
                $parsed['nps_value'] = $nps;
                $parsed['normalized_score'] = (float) ($nps * 10);
                break;
            case 'severity':
                $severity = max(1, min(5, (int) $rawAnswer));
                $parsed['severity_level'] = $severity;
                $parsed['normalized_score'] = (float) ($severity * 20);
                break;
            case 'comment':
            default:
                $parsed['text_answer'] = trim((string) $rawAnswer);
                break;
        }

        return $parsed;
    }

    private function mapDimensionByKey(string $key): ?string
    {
        $normalized = strtolower($key);

        return match (true) {
            str_contains($normalized, 'service') => 'service_quality',
            str_contains($normalized, 'communic') => 'communication',
            str_contains($normalized, 'wait') => 'waiting_experience',
            str_contains($normalized, 'explain') => 'explanation_quality',
            str_contains($normalized, 'resolution'),
            str_contains($normalized, 'solve') => 'resolution_quality',
            default => null,
        };
    }
}
