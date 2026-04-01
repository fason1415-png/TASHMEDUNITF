<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitSurveyRequest;
use App\Models\QrCode;
use App\Models\QrScanEvent;
use App\Models\Survey;
use App\Services\RequestFingerprintService;
use App\Services\SurveySubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicSurveyController extends Controller
{
    public function showByToken(
        string $token,
        Request $request,
        RequestFingerprintService $fingerprintService,
    ): View {
        $qrCode = QrCode::query()
            ->with(['clinic', 'doctor', 'department', 'branch', 'servicePoint'])
            ->where('token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        abort_if($qrCode->expires_at && now()->greaterThan($qrCode->expires_at), 410, 'QR code has expired.');

        $survey = Survey::query()
            ->with(['questions.options'])
            ->where('clinic_id', $qrCode->clinic_id)
            ->where('is_active', true)
            ->where('is_default', true)
            ->first()
            ?? Survey::query()
                ->with(['questions.options'])
                ->where('clinic_id', $qrCode->clinic_id)
                ->where('is_active', true)
                ->firstOrFail();

        $meta = $fingerprintService->build($request);
        $scanEvent = QrScanEvent::query()->create([
            'clinic_id' => $qrCode->clinic_id,
            'qr_code_id' => $qrCode->id,
            'channel' => 'qr',
            'ip_hash' => $meta['ip_hash'],
            'device_hash' => $meta['device_hash'],
            'fingerprint_hash' => $meta['fingerprint_hash'],
            'user_agent' => substr((string) $request->userAgent(), 0, 2000),
            'language' => app()->getLocale(),
            'scanned_at' => now(),
        ]);

        $qrCode->increment('scan_count');
        $qrCode->update(['last_scanned_at' => now()]);

        session(['qr_scan_event_id' => $scanEvent->id]);

        return view('survey.show', [
            'survey' => $survey,
            'qrCode' => $qrCode,
            'sourceChannel' => 'qr',
            'supportedLocales' => config('shiforeyting.supported_locales'),
        ]);
    }

    public function submitByToken(
        string $token,
        SubmitSurveyRequest $request,
        SurveySubmissionService $submissionService,
        RequestFingerprintService $fingerprintService,
    ): RedirectResponse {
        $qrCode = QrCode::query()
            ->where('token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        $payload = $request->validated();
        $payload['language'] = app()->getLocale();
        $payload['channel'] = $payload['channel'] ?? 'qr';

        $response = $submissionService->submitFromQrCode(
            $qrCode,
            $payload,
            $fingerprintService->build($request, $payload)
        );

        if (session()->has('qr_scan_event_id')) {
            QrScanEvent::query()->whereKey((int) session('qr_scan_event_id'))->update([
                'converted_to_response_id' => $response->id,
            ]);
        }

        return redirect()->route('survey.thank-you', ['token' => $token, 'lang' => app()->getLocale()]);
    }

    public function showByShortlink(string $slug, Request $request): View
    {
        $survey = Survey::query()
            ->with(['clinic', 'questions.options'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->when(
                $request->filled('clinic_id'),
                fn ($query) => $query->where('clinic_id', $request->integer('clinic_id'))
            )
            ->firstOrFail();

        return view('survey.show', [
            'survey' => $survey,
            'qrCode' => null,
            'sourceChannel' => $request->string('channel')->toString() ?: 'shortlink',
            'supportedLocales' => config('shiforeyting.supported_locales'),
        ]);
    }

    public function submitByShortlink(
        string $slug,
        SubmitSurveyRequest $request,
        SurveySubmissionService $submissionService,
        RequestFingerprintService $fingerprintService,
    ): RedirectResponse {
        $survey = Survey::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->when(
                $request->filled('clinic_id'),
                fn ($query) => $query->where('clinic_id', $request->integer('clinic_id'))
            )
            ->firstOrFail();

        $payload = $request->validated();
        $payload['language'] = app()->getLocale();
        $payload['channel'] = $payload['channel'] ?? 'shortlink';

        $submissionService->submitFromSurvey(
            $survey,
            $payload,
            $fingerprintService->build($request, $payload)
        );

        return redirect()->route('survey.thank-you-shortlink', ['slug' => $slug, 'lang' => app()->getLocale()]);
    }

    public function thankYou(string $token): View
    {
        return view('survey.thank-you', [
            'backUrl' => route('survey.show', ['token' => $token]),
        ]);
    }

    public function thankYouShortlink(string $slug): View
    {
        return view('survey.thank-you', [
            'backUrl' => route('survey.show-shortlink', ['slug' => $slug]),
        ]);
    }
}

