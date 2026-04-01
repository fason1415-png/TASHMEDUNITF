<?php

namespace App\Filament\Resources\Surveys\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SurveyForm
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
                TextInput::make('slug'),
                Textarea::make('title')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('is_default')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('allow_anonymous')
                    ->required(),
                Toggle::make('require_token_verification')
                    ->required(),
                Toggle::make('callback_enabled')
                    ->required(),
                TextInput::make('estimated_seconds')
                    ->required()
                    ->numeric()
                    ->default(45),
                Textarea::make('config')
                    ->columnSpanFull(),
                DateTimePicker::make('starts_at'),
                DateTimePicker::make('ends_at'),
            ]);
    }
}

