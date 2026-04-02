<?php

namespace App\Filament\ClinicPanel\Resources\DoctorResource\Pages;

use App\Filament\ClinicPanel\Resources\DoctorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDoctors extends ListRecords
{
    protected static string $resource = DoctorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
