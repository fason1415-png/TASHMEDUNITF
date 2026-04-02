<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Filial')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('clinic_id')
                                ->label('Klinika')
                                ->relationship('clinic', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),
                            TextInput::make('name')
                                ->label('Nomi')
                                ->required(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('address')
                                ->label('Manzil'),
                            TextInput::make('phone')
                                ->label('Telefon')
                                ->tel(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('latitude')
                                ->label('Kenglik (lat)')
                                ->numeric()
                                ->minValue(-90)
                                ->maxValue(90)
                                ->placeholder('41.2995'),
                            TextInput::make('longitude')
                                ->label('Uzunlik (lng)')
                                ->numeric()
                                ->minValue(-180)
                                ->maxValue(180)
                                ->placeholder('69.2401'),
                        ]),
                        Toggle::make('is_active')
                            ->label('Faol')
                            ->default(true)
                            ->inline(),
                    ]),
            ]);
    }
}
