<?php

use App\Http\Controllers\Api\PublicWidgetController;
use App\Http\Controllers\Api\SurveyApiController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('/survey/submit/{token}', [SurveyApiController::class, 'submitByToken'])
        ->middleware('throttle:api-survey-submissions')
        ->name('api.survey.submit-by-token');
    Route::post('/survey/submit-shortlink/{slug}', [SurveyApiController::class, 'submitByShortlink'])
        ->middleware('throttle:api-survey-submissions')
        ->name('api.survey.submit-by-shortlink');

    Route::post('/webhooks/telegram', [WebhookController::class, 'telegram'])
        ->middleware('throttle:api-survey-submissions')
        ->name('api.webhooks.telegram');

    Route::get('/widgets/doctor/{doctor}', [PublicWidgetController::class, 'doctor'])
        ->name('api.widgets.doctor');
    Route::get('/widgets/clinic/{clinic}', [PublicWidgetController::class, 'clinic'])
        ->name('api.widgets.clinic');
});
