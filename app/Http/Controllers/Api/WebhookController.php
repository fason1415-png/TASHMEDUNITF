<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use App\Models\Survey;
use App\Services\RequestFingerprintService;
use App\Services\SurveySubmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WebhookController extends Controller
{
    public function telegram(
        Request $request,
        SurveySubmissionService $submissionService,
        RequestFingerprintService $fingerprintService,
    ): JsonResponse {
        $payload = $request->validate([
            'token' => ['nullable', 'string'],
            'survey_slug' => ['nullable', 'string'],
            'clinic_id' => ['nullable', 'integer'],
            'language' => ['nullable', 'string', 'max:12'],
            'answers' => ['required', 'array', 'min:1'],
            'callback_requested' => ['nullable', 'boolean'],
            'callback_contact' => ['nullable', 'string', 'max:255'],
        ]);

        $payload['channel'] = 'telegram';

        if (! empty($payload['token'])) {
            $qrCode = QrCode::query()->where('token', $payload['token'])->where('is_active', true)->firstOrFail();
            abort_if($qrCode->expires_at && now()->greaterThan($qrCode->expires_at), 410, 'QR code has expired.');
            $response = $submissionService->submitFromQrCode(
                $qrCode,
                $payload,
                $fingerprintService->build($request, $payload)
            );
        } elseif (! empty($payload['survey_slug'])) {
            $survey = Survey::query()
                ->where('slug', $payload['survey_slug'])
                ->where('is_active', true)
                ->when(
                    ! empty($payload['clinic_id']),
                    fn ($query) => $query->where('clinic_id', (int) $payload['clinic_id'])
                )
                ->firstOrFail();

            $response = $submissionService->submitFromSurvey(
                $survey,
                $payload,
                $fingerprintService->build($request, $payload)
            );
        } else {
            throw ValidationException::withMessages([
                'token' => 'Either token or survey_slug is required.',
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Telegram feedback accepted.',
            'response_id' => $response->uuid,
        ], 202);
    }
}

