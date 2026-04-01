<?php

namespace App\Filament\Resources\SurveyResponses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SurveyResponseForm
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
                Select::make('doctor_id')
                    ->relationship('doctor', 'id'),
                Select::make('service_point_id')
                    ->relationship('servicePoint', 'name'),
                Select::make('qr_code_id')
                    ->relationship('qrCode', 'id'),
                Select::make('survey_id')
                    ->relationship('survey', 'name'),
                TextInput::make('channel')
                    ->required()
                    ->default('qr'),
                DateTimePicker::make('submitted_at')
                    ->required(),
                TextInput::make('language')
                    ->required()
                    ->default('uz_latn'),
                TextInput::make('ip_hash'),
                TextInput::make('device_hash'),
                TextInput::make('fingerprint_hash'),
                TextInput::make('fraud_score')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('anomaly_score')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('sentiment_score')
                    ->numeric(),
                TextInput::make('severity_score')
                    ->numeric(),
                TextInput::make('confidence_score')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('quality_score')
                    ->numeric(),
                Toggle::make('is_flagged')
                    ->required(),
                TextInput::make('moderation_status')
                    ->required()
                    ->default('pending'),
                Toggle::make('is_duplicate')
                    ->required(),
                TextInput::make('duplicate_of_response_id')
                    ->numeric(),
                Toggle::make('callback_requested')
                    ->required(),
                Textarea::make('callback_contact')
                    ->columnSpanFull(),
                Textarea::make('callback_note')
                    ->columnSpanFull(),
                TextInput::make('submitted_from_country'),
                DateTimePicker::make('ai_processed_at'),
            ]);
    }
}

