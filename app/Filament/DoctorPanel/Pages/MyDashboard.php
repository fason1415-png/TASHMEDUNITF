<?php

namespace App\Filament\DoctorPanel\Pages;

use Filament\Pages\Dashboard;
use Filament\Widgets\StatsOverviewWidget;

class MyDashboard extends Dashboard
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected static int | null $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
        ];
    }
}
