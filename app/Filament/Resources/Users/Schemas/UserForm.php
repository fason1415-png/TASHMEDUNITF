<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('clinic_id')
                    ->relationship('clinic', 'name'),
                Select::make('branch_id')
                    ->relationship('branch', 'name'),
                Select::make('doctor_id')
                    ->relationship('doctor', 'id'),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('preferred_language')
                    ->required()
                    ->default('uz_latn'),
                Toggle::make('is_active')
                    ->required(),
                DateTimePicker::make('last_login_at'),
            ]);
    }
}
