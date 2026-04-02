<?php

namespace App\Filament\Resources\Clinics\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClinicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Klinika')
                    ->icon('heroicon-o-building-office-2')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nomi')
                                ->required(),
                            TextInput::make('region')
                                ->label('Viloyat'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('address')
                                ->label('Manzil'),
                            TextInput::make('phone')
                                ->label('Telefon')
                                ->tel()
                                ->placeholder('+998 71 000-00-00'),
                        ]),
                        Grid::make(2)->schema([
                            Select::make('subscription_plan')
                                ->label('Tarif')
                                ->options([
                                    'start' => 'Start',
                                    'standard' => 'Standard',
                                    'premium' => 'Premium',
                                ])
                                ->default('start')
                                ->required()
                                ->native(false),
                            Toggle::make('is_active')
                                ->label('Faol')
                                ->default(true)
                                ->inline(),
                        ]),
                    ]),
            ]);
    }
}
