<?php

namespace App\Filament\Resources\Escalations\Pages;

use App\Filament\Resources\Escalations\EscalationResource;
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
