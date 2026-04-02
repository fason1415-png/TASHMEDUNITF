<?php

namespace App\Filament\ClinicPanel\Resources\EscalationResource\Pages;

use App\Filament\ClinicPanel\Resources\EscalationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEscalation extends EditRecord
{
    protected static string $resource = EscalationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
