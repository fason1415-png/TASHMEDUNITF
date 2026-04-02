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
                    ->label('Bemor')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('attendingDoctor.full_name')
                    ->label('Shifokor')
                    ->searchable(),
                TextColumn::make('severity_level')
                    ->label('Og\'irlik')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'mild' => 'Yengil',
                        'moderate' => 'O\'rtacha',
                        'severe' => 'Og\'ir',
                        'critical' => 'Juda og\'ir',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'mild' => 'gray',
                        'moderate' => 'warning',
                        'severe' => 'danger',
                        'critical' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('discharge_type')
                    ->label('Chiqarish turi')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'recovery' => 'Tuzalish',
                        'improvement' => 'Yaxshilanish',
                        'transfer' => 'Ko\'chirish',
                        'against_advice' => 'O\'z xohishi',
                        'death' => 'Vafot',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'recovery' => 'success',
                        'improvement' => 'info',
                        'transfer' => 'warning',
                        'against_advice' => 'gray',
                        'death' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('requires_patronage')
                    ->label('Patronaj')
                    ->boolean(),
                TextColumn::make('discharged_at')
                    ->label('Sana')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
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
