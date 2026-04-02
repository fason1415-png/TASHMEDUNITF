<?php

namespace App\Filament\Resources\Discharges\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DischargeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('clinic_id')
                    ->relationship('clinic', 'name')
                    ->required()
                    ->default(fn () => auth()->user()?->clinic_id),
                Select::make('patient_id')
                    ->relationship('patient', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('branch_id')
                    ->relationship('branch', 'name'),
                Select::make('department_id')
                    ->relationship('department', 'name'),
                Select::make('attending_doctor_id')
                    ->relationship('attendingDoctor', 'full_name')
                    ->searchable()
                    ->preload(),
                TextInput::make('diagnosis_code'),
                Textarea::make('diagnosis_text')
                    ->columnSpanFull(),
                Select::make('severity_level')
                    ->options([
                        'mild' => 'Mild',
                        'moderate' => 'Moderate',
                        'severe' => 'Severe',
                        'critical' => 'Critical',
                    ])
                    ->required()
                    ->default('moderate'),
                Select::make('discharge_type')
                    ->options([
                        'recovery' => 'Recovery',
                        'improvement' => 'Improvement',
                        'transfer' => 'Transfer',
                        'against_advice' => 'Against Advice',
                        'death' => 'Death',
                    ])
                    ->required()
                    ->default('improvement'),
                Toggle::make('requires_patronage')
                    ->default(false),
                TagsInput::make('recommended_visit_days')
                    ->hint('kunlar: 1,3,7,14'),
                DateTimePicker::make('discharged_at')
                    ->default(now()),
            ]);
    }
}
