<?php

namespace App\Filament\Resources\SuspiciousFlags\Pages;

use App\Filament\Resources\SuspiciousFlags\SuspiciousFlagResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSuspiciousFlag extends EditRecord
{
    protected static string $resource = SuspiciousFlagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
