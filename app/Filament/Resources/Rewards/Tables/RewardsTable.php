<?php

namespace App\Filament\Resources\Rewards\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RewardsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('doctor.full_name')
                    ->label('Shifokor')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('clinic.name')
                    ->label('Klinika')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('title')
                    ->label('Sarlavha')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Miqdori')
                    ->numeric()
                    ->suffix(' so\'m')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('status')
                    ->label('Holati')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'eligible' => 'Munosib',
                        'approved' => 'Tasdiqlangan',
                        'paid' => 'To\'langan',
                        'rejected' => 'Rad etilgan',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'eligible' => 'info',
                        'approved' => 'success',
                        'paid' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('period_start')
                    ->label('Davr')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('eligibility_score')
                    ->label('Ball')
                    ->numeric(1)
                    ->sortable()
                    ->toggleable(),
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
