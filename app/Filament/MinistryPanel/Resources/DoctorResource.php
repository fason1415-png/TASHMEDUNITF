<?php

namespace App\Filament\MinistryPanel\Resources;

use App\Filament\MinistryPanel\Resources\DoctorResource\Pages;
use App\Models\Doctor;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Structure';

    protected static int | null $navigationSort = 20;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->searchable()->sortable(),
                TextColumn::make('specialty')->searchable(),
                TextColumn::make('clinic.name')->sortable(),
                TextColumn::make('status')->sortable(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'on_leave' => 'On Leave',
                    ]),
            ])
            ->defaultSort('full_name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDoctors::route('/'),
        ];
    }
}
