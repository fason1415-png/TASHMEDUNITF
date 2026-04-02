<?php

namespace App\Filament\MinistryPanel\Resources;

use App\Filament\MinistryPanel\Resources\PatronageTaskResource\Pages;
use App\Models\PatronageTask;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PatronageTaskResource extends Resource
{
    protected static ?string $model = PatronageTask::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Patronage';

    protected static int | null $navigationSort = 30;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.full_name')->searchable()->sortable(),
                TextColumn::make('familyDoctor.full_name')->label('Family Doctor')->searchable(),
                TextColumn::make('status')->sortable(),
                TextColumn::make('priority')->sortable(),
                TextColumn::make('due_at')->dateTime()->sortable(),
                IconColumn::make('sla_breached')->boolean(),
            ])
            ->defaultSort('due_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatronageTasks::route('/'),
        ];
    }
}
