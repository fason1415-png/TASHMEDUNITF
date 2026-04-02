<?php

namespace App\Filament\ClinicPanel\Resources;

use App\Filament\ClinicPanel\Resources\PatientResource\Pages;
use App\Models\Patient;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Patronage';

    protected static int | null $navigationSort = 70;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->searchable()->sortable(),
                TextColumn::make('gender')->sortable(),
                TextColumn::make('address_region')->sortable(),
                TextColumn::make('familyDoctor.full_name')->label('Family Doctor')->searchable(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->defaultSort('full_name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
        ];
    }
}
