<?php

namespace App\Filament\ClinicPanel\Resources;

use App\Filament\ClinicPanel\Resources\RewardRuleResource\Pages;
use App\Models\RewardRule;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RewardRuleResource extends Resource
{
    protected static ?string $model = RewardRule::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Rewards';

    protected static int | null $navigationSort = 100;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('trigger_type')->maxLength(100),
            TextInput::make('reward_type')->maxLength(100),
            TextInput::make('reward_value')->numeric(),
            TextInput::make('period_type')->maxLength(50),
            Checkbox::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('trigger_type')->sortable(),
                TextColumn::make('reward_type')->sortable(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRewardRules::route('/'),
            'create' => Pages\CreateRewardRule::route('/create'),
            'edit' => Pages\EditRewardRule::route('/{record}/edit'),
        ];
    }
}
