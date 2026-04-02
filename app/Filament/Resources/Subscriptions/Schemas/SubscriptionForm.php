<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Obuna')
                    ->icon('heroicon-o-credit-card')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('clinic_id')
                                ->label('Klinika')
                                ->relationship('clinic', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),
                            Select::make('plan')
                                ->label('Tarif')
                                ->options([
                                    'start' => 'Start',
                                    'standard' => 'Standard',
                                    'premium' => 'Premium',
                                ])
                                ->default('start')
                                ->required()
                                ->native(false),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('price')
                                ->label('Narxi')
                                ->numeric()
                                ->default(0)
                                ->suffix('so\'m'),
                            Select::make('billing_cycle')
                                ->label('To\'lov davri')
                                ->options([
                                    'monthly' => 'Oylik',
                                    'quarterly' => 'Choraklik',
                                    'yearly' => 'Yillik',
                                ])
                                ->default('monthly')
                                ->required()
                                ->native(false),
                            Select::make('status')
                                ->label('Holati')
                                ->options([
                                    'trial' => 'Sinov',
                                    'active' => 'Faol',
                                    'past_due' => 'Muddati o\'tgan',
                                    'cancelled' => 'Bekor qilingan',
                                ])
                                ->default('trial')
                                ->required()
                                ->native(false),
                        ]),
                        Grid::make(3)->schema([
                            DateTimePicker::make('starts_at')
                                ->label('Boshlanishi'),
                            DateTimePicker::make('ends_at')
                                ->label('Tugashi'),
                            Toggle::make('auto_renew')
                                ->label('Avtomatik uzaytirish')
                                ->default(true)
                                ->inline(),
                        ]),
                    ]),
            ]);
    }
}
