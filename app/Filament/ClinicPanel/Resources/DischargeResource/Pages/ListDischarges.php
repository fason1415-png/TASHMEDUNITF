<?php

namespace App\Filament\ClinicPanel\Resources\DischargeResource\Pages;

use App\Filament\ClinicPanel\Resources\DischargeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDischarges extends ListRecords
{
    protected static string $resource = DischargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
