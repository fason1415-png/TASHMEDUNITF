<?php

namespace App\Filament\MinistryPanel\Resources\ClinicResource\Pages;

use App\Filament\MinistryPanel\Resources\ClinicResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClinic extends EditRecord
{
    protected static string $resource = ClinicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
