<?php

namespace App\Filament\Resources\Subscriptions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('clinic.name')
                    ->label('Klinika')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('plan')
                    ->label('Tarif')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'premium' => 'success',
                        'standard' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),
                TextColumn::make('price')
                    ->label('Narxi')
                    ->numeric()
                    ->suffix(' so\'m')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Holati')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'active' => 'success',
                        'trial' => 'warning',
                        'past_due' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'active' => 'Faol',
                        'trial' => 'Sinov',
                        'past_due' => 'Muddati o\'tgan',
                        'cancelled' => 'Bekor',
                        default => $state,
                    }),
                IconColumn::make('auto_renew')
                    ->label('Uzaytirish')
                    ->boolean(),
                TextColumn::make('ends_at')
                    ->label('Tugash sanasi')
                    ->dateTime('d.m.Y')
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
