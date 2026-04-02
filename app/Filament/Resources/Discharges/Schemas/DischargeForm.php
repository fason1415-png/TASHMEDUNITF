<?php

namespace App\Filament\Resources\Discharges\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DischargeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Chiqarish')
                    ->icon('heroicon-o-arrow-right-start-on-rectangle')
                    ->schema([
                        Select::make('clinic_id')
                            ->label('Klinika')
                            ->relationship('clinic', 'name')
                            ->required()
                            ->default(fn () => auth()->user()?->clinic_id)
                            ->searchable()
                            ->preload(),
                        Grid::make(2)->schema([
                            Select::make('patient_id')
                                ->label('Bemor')
                                ->relationship('patient', 'full_name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('attending_doctor_id')
                                ->label('Shifokor')
                                ->relationship('attendingDoctor', 'full_name')
                                ->searchable()
                                ->preload(),
                        ]),
                        Grid::make(3)->schema([
                            Select::make('severity_level')
                                ->label('Og\'irlik')
                                ->options([
                                    'mild' => 'Yengil',
                                    'moderate' => 'O\'rtacha',
                                    'severe' => 'Og\'ir',
                                    'critical' => 'Juda og\'ir',
                                ])
                                ->required()
                                ->default('moderate')
                                ->native(false),
                            Select::make('discharge_type')
                                ->label('Chiqarish turi')
                                ->options([
                                    'recovery' => 'Tuzalish',
                                    'improvement' => 'Yaxshilanish',
                                    'transfer' => 'Ko\'chirish',
                                    'against_advice' => 'O\'z xohishi',
                                    'death' => 'Vafot',
                                ])
                                ->required()
                                ->default('improvement')
                                ->native(false),
                            DateTimePicker::make('discharged_at')
                                ->label('Chiqarilgan sana')
                                ->default(now()),
                        ]),
                        Textarea::make('diagnosis_text')
                            ->label('Tashxis')
                            ->rows(2)
                            ->columnSpanFull(),
                        Grid::make(2)->schema([
                            Toggle::make('requires_patronage')
                                ->label('Patronaj kerak')
                                ->default(false)
                                ->inline(),
                        ]),
                    ]),
            ]);
    }
}
