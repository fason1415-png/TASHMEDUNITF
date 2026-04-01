<?php

use App\Http\Controllers\ExportCenterController;
use App\Http\Controllers\PublicSurveyController;
use App\Http\Controllers\QrLabelController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('f')->group(function (): void {
    Route::get('/{token}', [PublicSurveyController::class, 'showByToken'])
        ->name('survey.show');
    Route::post('/{token}/submit', [PublicSurveyController::class, 'submitByToken'])
        ->middleware('throttle:survey-submissions')
        ->name('survey.submit');
    Route::get('/{token}/thank-you', [PublicSurveyController::class, 'thankYou'])
        ->name('survey.thank-you');
});

Route::prefix('s')->group(function (): void {
    Route::get('/{slug}', [PublicSurveyController::class, 'showByShortlink'])
        ->name('survey.show-shortlink');
    Route::post('/{slug}/submit', [PublicSurveyController::class, 'submitByShortlink'])
        ->middleware('throttle:survey-submissions')
        ->name('survey.submit-shortlink');
    Route::get('/{slug}/thank-you', [PublicSurveyController::class, 'thankYouShortlink'])
        ->name('survey.thank-you-shortlink');
});

Route::middleware(['auth', 'permission:export_reports'])->prefix('exports')->name('exports.')->group(function (): void {
    Route::get('/doctor-monthly', [ExportCenterController::class, 'doctorMonthly'])->name('doctor-monthly');
    Route::get('/department-ranking', [ExportCenterController::class, 'departmentRanking'])->name('department-ranking');
    Route::get('/complaint-categories', [ExportCenterController::class, 'complaintCategories'])->name('complaint-categories');
    Route::get('/clinic-summary-pdf', [ExportCenterController::class, 'clinicSummaryPdf'])->name('clinic-summary-pdf');
});

Route::middleware('auth')->get('/qr-labels/{qrCode}.pdf', [QrLabelController::class, 'show'])->name('qr.label');

