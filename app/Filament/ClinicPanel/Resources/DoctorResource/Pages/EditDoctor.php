<?php

namespace App\Filament\ClinicPanel\Resources\DoctorResource\Pages;

use App\Filament\ClinicPanel\Resources\DoctorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDoctor extends EditRecord
{
    protected static string $resource = DoctorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
