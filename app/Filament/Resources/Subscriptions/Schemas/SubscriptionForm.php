<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('clinic_id')
                    ->relationship('clinic', 'name')
                    ->required(),
                TextInput::make('plan')
                    ->required()
                    ->default('start'),
                TextInput::make('billing_cycle')
                    ->required()
                    ->default('monthly'),
                TextInput::make('status')
                    ->required()
                    ->default('trial'),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                TextInput::make('currency')
                    ->required()
                    ->default('UZS'),
                Textarea::make('usage_limits')
                    ->columnSpanFull(),
                Textarea::make('usage_snapshot')
                    ->columnSpanFull(),
                Toggle::make('auto_renew')
                    ->required(),
                DateTimePicker::make('starts_at'),
                DateTimePicker::make('ends_at'),
                DateTimePicker::make('trial_ends_at'),
                TextInput::make('created_by')
                    ->numeric(),
            ]);
    }
}

