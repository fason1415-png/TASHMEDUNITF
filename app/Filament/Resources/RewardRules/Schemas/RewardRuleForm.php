<?php

namespace App\Filament\Resources\RewardRules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RewardRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('clinic_id')
                    ->relationship('clinic', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('trigger_type')
                    ->required(),
                Textarea::make('conditions')
                    ->columnSpanFull(),
                TextInput::make('reward_type')
                    ->required(),
                TextInput::make('reward_value')
                    ->numeric(),
                Textarea::make('reward_meta')
                    ->columnSpanFull(),
                TextInput::make('period_type')
                    ->required()
                    ->default('monthly'),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('created_by')
                    ->numeric(),
            ]);
    }
}

