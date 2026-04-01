<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ExportCenter extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowDownTray;

    protected static ?int $navigationSort = 90;

    protected string $view = 'filament.pages.export-center';

    public string $month = '';

    public string $from = '';

    public string $to = '';

    public function mount(): void
    {
        $this->month = now()->format('Y-m');
        $this->from = now()->startOfMonth()->toDateString();
        $this->to = now()->toDateString();
    }

    public static function getNavigationLabel(): string
    {
        return __('pages.export_center.navigation');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.groups.system');
    }

    public function getTitle(): string|Htmlable
    {
        return __('pages.export_center.title');
    }
}
