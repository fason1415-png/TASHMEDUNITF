<?php

namespace App\Filament\Resources\RewardRules\Pages;

use App\Filament\Resources\RewardRules\RewardRuleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRewardRule extends EditRecord
{
    protected static string $resource = RewardRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
