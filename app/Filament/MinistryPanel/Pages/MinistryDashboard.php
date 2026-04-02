<?php

namespace App\Filament\MinistryPanel\Pages;

use App\Filament\Widgets\ComplaintAlertsWidget;
use App\Filament\Widgets\ExecutiveHeroWidget;
use App\Filament\Widgets\FeedbackTrendChart;
use App\Filament\Widgets\RealtimeOverview;
use Filament\Pages\Dashboard;

class MinistryDashboard extends Dashboard
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected static int | null $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            ExecutiveHeroWidget::class,
            RealtimeOverview::class,
            FeedbackTrendChart::class,
            ComplaintAlertsWidget::class,
        ];
    }
}
