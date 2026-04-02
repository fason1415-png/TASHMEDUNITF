<?php

namespace App\Filament\Resources\PatronageEscalationRules\Pages;

use App\Filament\Resources\PatronageEscalationRules\PatronageEscalationRuleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPatronageEscalationRule extends EditRecord
{
    protected static string $resource = PatronageEscalationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
