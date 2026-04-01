<?php

namespace App\Filament\Resources\ServicePoints\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServicePointForm
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
                TextInput::make('name')
                    ->required(),
                TextInput::make('type')
                    ->required()
                    ->default('room'),
                TextInput::make('code'),
                TextInput::make('location_hint'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}

