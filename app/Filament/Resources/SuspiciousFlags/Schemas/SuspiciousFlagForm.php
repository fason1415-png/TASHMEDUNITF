<?php

namespace App\Filament\Resources\SuspiciousFlags\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SuspiciousFlagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('clinic_id')
                    ->relationship('clinic', 'name')
                    ->required(),
                TextInput::make('survey_response_id')
                    ->required()
                    ->numeric(),
                TextInput::make('flag_type')
                    ->required(),
                TextInput::make('score')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('reason')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('evidence')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('open'),
                TextInput::make('reviewed_by')
                    ->numeric(),
                DateTimePicker::make('reviewed_at'),
            ]);
    }
}

