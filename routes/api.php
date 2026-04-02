<?php

use App\Http\Controllers\Api\DischargeApiController;
use App\Http\Controllers\Api\PatronageApiController;
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
        ->name('api.webhooks.telegram');

    Route::get('/widgets/doctor/{doctor}', [PublicWidgetController::class, 'doctor'])
        ->name('api.widgets.doctor');
    Route::get('/widgets/clinic/{clinic}', [PublicWidgetController::class, 'clinic'])
        ->name('api.widgets.clinic');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::prefix('patronage')->group(function (): void {
            Route::get('/tasks', [PatronageApiController::class, 'index'])
                ->name('api.patronage.tasks.index');
            Route::get('/tasks/{uuid}', [PatronageApiController::class, 'show'])
                ->name('api.patronage.tasks.show');
            Route::post('/tasks/{uuid}/accept', [PatronageApiController::class, 'accept'])
                ->name('api.patronage.tasks.accept');
            Route::post('/tasks/{uuid}/confirm-visit', [PatronageApiController::class, 'confirmVisit'])
                ->name('api.patronage.tasks.confirm-visit');
            Route::get('/stats', [PatronageApiController::class, 'stats'])
                ->name('api.patronage.stats');
        });

        Route::post('/discharges', [DischargeApiController::class, 'store'])
            ->name('api.discharges.store');
    });
});
