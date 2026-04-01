<?php

namespace App\Filament\Resources\Escalations\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EscalationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('clinic_id')
                    ->relationship('clinic', 'name')
                    ->required(),
                TextInput::make('survey_response_id')
                    ->numeric(),
                Select::make('doctor_id')
                    ->relationship('doctor', 'id'),
                Select::make('branch_id')
                    ->relationship('branch', 'name'),
                Select::make('department_id')
                    ->relationship('department', 'name'),
                TextInput::make('severity')
                    ->required()
                    ->default('medium'),
                TextInput::make('category'),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('source')
                    ->required()
                    ->default('auto'),
                TextInput::make('status')
                    ->required()
                    ->default('open'),
                TextInput::make('assigned_to')
                    ->numeric(),
                Textarea::make('resolution_notes')
                    ->columnSpanFull(),
                DateTimePicker::make('opened_at'),
                DateTimePicker::make('resolved_at'),
                DateTimePicker::make('sla_due_at'),
                Textarea::make('meta')
                    ->columnSpanFull(),
            ]);
    }
}

