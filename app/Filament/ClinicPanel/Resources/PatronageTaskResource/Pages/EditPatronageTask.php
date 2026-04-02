<?php

namespace App\Filament\ClinicPanel\Resources\PatronageTaskResource\Pages;

use App\Filament\ClinicPanel\Resources\PatronageTaskResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPatronageTask extends EditRecord
{
    protected static string $resource = PatronageTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
