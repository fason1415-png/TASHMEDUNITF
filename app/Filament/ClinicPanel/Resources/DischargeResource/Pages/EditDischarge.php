<?php

namespace App\Filament\ClinicPanel\Resources\DischargeResource\Pages;

use App\Filament\ClinicPanel\Resources\DischargeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDischarge extends EditRecord
{
    protected static string $resource = DischargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
