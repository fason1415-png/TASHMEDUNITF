<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Services\QrCodeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class QrLabelController extends Controller
{
    public function show(QrCode $qrCode, QrCodeService $qrCodeService): Response
    {
        $surveyUrl = $qrCodeService->buildSurveyUrl($qrCode->token);
        $qrImageDataUri = $qrCodeService->renderImageDataUri($surveyUrl, 280);

        $pdf = Pdf::loadView('reports.qr-label', [
            'qrCode' => $qrCode->load(['clinic', 'branch', 'department', 'doctor', 'servicePoint']),
            'surveyUrl' => $surveyUrl,
            'qrImageDataUri' => $qrImageDataUri,
        ])->setPaper('a4');

        $qrCode->update(['printed_at' => now()]);

        return $pdf->download('qr-label-'.$qrCode->code.'.pdf');
    }
}
