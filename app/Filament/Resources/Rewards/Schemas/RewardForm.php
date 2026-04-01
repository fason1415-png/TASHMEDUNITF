<?php

namespace App\Filament\Resources\Rewards\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RewardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('clinic_id')
                    ->relationship('clinic', 'name')
                    ->required(),
                TextInput::make('reward_rule_id')
                    ->numeric(),
                TextInput::make('rating_snapshot_id')
                    ->numeric(),
                Select::make('doctor_id')
                    ->relationship('doctor', 'id'),
                Select::make('branch_id')
                    ->relationship('branch', 'name'),
                Select::make('department_id')
                    ->relationship('department', 'name'),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                DatePicker::make('period_start'),
                DatePicker::make('period_end'),
                TextInput::make('eligibility_score')
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('eligible'),
                TextInput::make('approved_by')
                    ->numeric(),
                DateTimePicker::make('approved_at'),
                DateTimePicker::make('paid_at'),
                TextInput::make('amount')
                    ->numeric(),
                TextInput::make('currency')
                    ->required()
                    ->default('UZS'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                Textarea::make('meta')
                    ->columnSpanFull(),
            ]);
    }
}

