<?php

namespace App\Filament\Widgets;

use App\Services\AiCommentAnalysisService;
use Filament\Widgets\Widget;

class AiCommentInsightsWidget extends Widget
{
    protected static bool $isLazy = true;

    protected string $view = 'filament.widgets.ai-comment-insights';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -4;

    protected function getViewData(): array
    {
        $clinicId = auth()->user()?->isSuperAdmin() ? null : auth()->user()?->clinic_id;

        $service = app(AiCommentAnalysisService::class);
        $analysis = $service->analyze($clinicId);

        return [
            'analysis' => $analysis,
        ];
    }
}
