<?php

namespace App\Filament\MinistryPanel\Resources\ClinicResource\Pages;

use App\Filament\MinistryPanel\Resources\ClinicResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClinics extends ListRecords
{
    protected static string $resource = ClinicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
