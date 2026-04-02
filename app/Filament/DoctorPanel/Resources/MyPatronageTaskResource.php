<?php

namespace App\Filament\DoctorPanel\Resources;

use App\Filament\DoctorPanel\Resources\MyPatronageTaskResource\Pages;
use App\Models\PatronageTask;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MyPatronageTaskResource extends Resource
{
    protected static ?string $model = PatronageTask::class;

    protected static ?string $navigationLabel = 'Patronaj tasklarim';

    protected static ?string $slug = 'my-patronage-tasks';

    protected static int | null $navigationSort = 20;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('family_doctor_id', auth()->user()->doctor_id);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.full_name')->searchable()->sortable(),
                TextColumn::make('task_type')->sortable(),
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
            'index' => Pages\ListMyPatronageTasks::route('/'),
        ];
    }
}
