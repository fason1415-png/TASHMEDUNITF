<?php

namespace App\Filament\Resources\RewardRules;

use App\Filament\Resources\RewardRules\Pages\CreateRewardRule;
use App\Filament\Resources\RewardRules\Pages\EditRewardRule;
use App\Filament\Resources\RewardRules\Pages\ListRewardRules;
use App\Filament\Resources\RewardRules\Schemas\RewardRuleForm;
use App\Filament\Resources\RewardRules\Tables\RewardRulesTable;
use App\Models\RewardRule;
use BackedEnum;
use App\Filament\Resources\BaseResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RewardRuleResource extends BaseResource
{
    protected static ?string $model = RewardRule::class;

    protected static ?string $permission = 'manage_rewards';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    public static function form(Schema $schema): Schema
    {
        return RewardRuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RewardRulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRewardRules::route('/'),
            'create' => CreateRewardRule::route('/create'),
            'edit' => EditRewardRule::route('/{record}/edit'),
        ];
    }
}





