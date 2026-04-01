<?php

namespace App\Filament\Resources\SuspiciousFlags\Pages;

use App\Filament\Resources\SuspiciousFlags\SuspiciousFlagResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSuspiciousFlags extends ListRecords
{
    protected static string $resource = SuspiciousFlagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
