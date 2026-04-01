<?php

namespace App\Filament\Resources\Clinics\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ClinicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('legal_name'),
                TextInput::make('region'),
                TextInput::make('city'),
                TextInput::make('address'),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('logo_path'),
                Textarea::make('branding')
                    ->columnSpanFull(),
                Textarea::make('scoring_weights')
                    ->columnSpanFull(),
                Textarea::make('ai_settings')
                    ->columnSpanFull(),
                TextInput::make('min_public_samples')
                    ->required()
                    ->numeric()
                    ->default(10),
                TextInput::make('subscription_plan')
                    ->required()
                    ->default('start'),
                DateTimePicker::make('trial_ends_at'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}

