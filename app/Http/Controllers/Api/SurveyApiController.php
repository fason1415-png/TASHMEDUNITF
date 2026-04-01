<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitSurveyRequest;
use App\Models\QrCode;
use App\Models\Survey;
use App\Services\RequestFingerprintService;
use App\Services\SurveySubmissionService;
use Illuminate\Http\JsonResponse;

class SurveyApiController extends Controller
{
    public function submitByToken(
        string $token,
        SubmitSurveyRequest $request,
        SurveySubmissionService $submissionService,
        RequestFingerprintService $fingerprintService,
    ): JsonResponse {
        $qrCode = QrCode::query()
            ->where('token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        $payload = $request->validated();
        $payload['channel'] = $payload['channel'] ?? 'qr';
        $payload['language'] = $payload['language'] ?? app()->getLocale();

        $response = $submissionService->submitFromQrCode(
            $qrCode,
            $payload,
            $fingerprintService->build($request, $payload)
        );

        return response()->json([
            'status' => 'ok',
            'message' => 'Feedback accepted.',
            'response_id' => $response->uuid,
            'is_flagged' => $response->is_flagged,
        ], 202);
    }

    public function submitByShortlink(
        string $slug,
        SubmitSurveyRequest $request,
        SurveySubmissionService $submissionService,
        RequestFingerprintService $fingerprintService,
    ): JsonResponse {
        $survey = Survey::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->when(
                $request->filled('clinic_id'),
                fn ($query) => $query->where('clinic_id', $request->integer('clinic_id'))
            )
            ->firstOrFail();

        $payload = $request->validated();
        $payload['channel'] = $payload['channel'] ?? 'shortlink';
        $payload['language'] = $payload['language'] ?? app()->getLocale();

        $response = $submissionService->submitFromSurvey(
            $survey,
            $payload,
            $fingerprintService->build($request, $payload)
        );

        return response()->json([
            'status' => 'ok',
            'message' => 'Feedback accepted.',
            'response_id' => $response->uuid,
            'is_flagged' => $response->is_flagged,
        ], 202);
    }
}

