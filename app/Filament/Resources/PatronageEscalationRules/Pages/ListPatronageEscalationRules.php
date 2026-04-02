<?php

namespace App\Filament\Resources\PatronageEscalationRules\Pages;

use App\Filament\Resources\PatronageEscalationRules\PatronageEscalationRuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPatronageEscalationRules extends ListRecords
{
    protected static string $resource = PatronageEscalationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
