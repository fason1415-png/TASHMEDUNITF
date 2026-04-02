<?php

namespace App\Filament\MinistryPanel\Resources\EscalationResource\Pages;

use App\Filament\MinistryPanel\Resources\EscalationResource;
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
