<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('clinic_id')
                    ->relationship('clinic', 'name')
                    ->required()
                    ->default(fn () => auth()->user()?->clinic_id),
                TextInput::make('full_name')
                    ->required(),
                TextInput::make('pinfl')
                    ->required()
                    ->maxLength(14)
                    ->minLength(14),
                DatePicker::make('birth_date'),
                Select::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ]),
                TextInput::make('phone'),
                TextInput::make('address_region'),
                TextInput::make('address_district'),
                Textarea::make('address_text')
                    ->columnSpanFull(),
                Select::make('territorial_clinic_id')
                    ->relationship('territorialClinic', 'name'),
                Select::make('family_doctor_id')
                    ->relationship('familyDoctor', 'full_name'),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
