<?php

namespace App\Filament\Resources\Discharges\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DischargesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('patient.full_name')
                    ->searchable(),
                TextColumn::make('attendingDoctor.full_name'),
                TextColumn::make('severity_level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'mild' => 'gray',
                        'moderate' => 'warning',
                        'severe' => 'danger',
                        'critical' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('discharge_type'),
                IconColumn::make('requires_patronage')
                    ->boolean(),
                TextColumn::make('discharged_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
