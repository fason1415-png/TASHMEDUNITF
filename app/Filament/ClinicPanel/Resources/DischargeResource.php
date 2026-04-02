<?php

namespace App\Filament\ClinicPanel\Resources;

use App\Filament\ClinicPanel\Resources\DischargeResource\Pages;
use App\Models\Discharge;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DischargeResource extends Resource
{
    protected static ?string $model = Discharge::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Patronage';

    protected static int | null $navigationSort = 80;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('patient_id')
                ->relationship('patient', 'full_name')
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('severity_level')->maxLength(50),
            TextInput::make('discharge_type')->maxLength(50),
            TextInput::make('diagnosis_code')->maxLength(50),
            TextInput::make('diagnosis_text')->maxLength(500),
            Checkbox::make('requires_patronage')->default(false),
            DateTimePicker::make('discharged_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.full_name')->searchable()->sortable(),
                TextColumn::make('severity_level')->sortable(),
                TextColumn::make('discharge_type')->sortable(),
                IconColumn::make('requires_patronage')->boolean(),
                TextColumn::make('discharged_at')->dateTime()->sortable(),
            ])
            ->defaultSort('discharged_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDischarges::route('/'),
            'create' => Pages\CreateDischarge::route('/create'),
            'edit' => Pages\EditDischarge::route('/{record}/edit'),
        ];
    }
}
