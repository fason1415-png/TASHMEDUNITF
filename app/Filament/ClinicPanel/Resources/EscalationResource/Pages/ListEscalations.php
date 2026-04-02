<?php

namespace App\Filament\ClinicPanel\Resources\EscalationResource\Pages;

use App\Filament\ClinicPanel\Resources\EscalationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEscalations extends ListRecords
{
    protected static string $resource = EscalationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
