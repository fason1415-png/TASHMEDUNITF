<?php

namespace App\Filament\Resources\QrCodes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QrCodeForm
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
                    ->relationship('doctor', 'full_name'),
                Select::make('service_point_id')
                    ->relationship('servicePoint', 'name'),
                Select::make('target_type')
                    ->options([
                        'doctor' => 'Doctor',
                        'room' => 'Room',
                        'department' => 'Department',
                        'branch' => 'Branch',
                        'service_type' => 'Service Type',
                        'generic' => 'Generic',
                    ])
                    ->required(),
                TextInput::make('target_id')
                    ->numeric(),
                TextInput::make('code')
                    ->default(strtoupper(str()->random(10)))
                    ->required()
                    ->maxLength(64),
                TextInput::make('token')
                    ->default(str()->random(40))
                    ->required()
                    ->maxLength(64),
                TextInput::make('short_url')
                    ->url(),
                Toggle::make('is_active')
                    ->required(),
                Textarea::make('meta')
                    ->columnSpanFull(),
                TextInput::make('scan_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('printed_at'),
                DateTimePicker::make('last_scanned_at'),
                DateTimePicker::make('expires_at'),
                TextInput::make('created_by')
                    ->numeric(),
            ]);
    }
}

