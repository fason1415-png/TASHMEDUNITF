<?php

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DoctorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('clinic_id')
                    ->relationship('clinic', 'name')
                    ->required(),
                Select::make('branch_id')
                    ->relationship('branch', 'name'),
                Select::make('department_id')
                    ->relationship('department', 'name'),
                TextInput::make('full_name')
                    ->required(),
                TextInput::make('specialty'),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                TextInput::make('photo'),
                TextInput::make('experience_years')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('bio')
                    ->columnSpanFull(),
                TextInput::make('consultation_type')
                    ->required()
                    ->default('offline'),
                Toggle::make('is_active')
                    ->required(),
                DatePicker::make('hired_at'),
                DatePicker::make('left_at'),
            ]);
    }
}

