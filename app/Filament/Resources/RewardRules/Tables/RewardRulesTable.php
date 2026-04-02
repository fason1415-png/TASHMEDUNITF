<?php

namespace App\Filament\Resources\RewardRules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RewardRulesTable
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
                TextColumn::make('name')
                    ->label('Nomi')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('trigger_type')
                    ->label('Turi')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'rank' => 'Reyting',
                        'threshold' => 'Chegara',
                        'milestone' => 'Belgi',
                        default => $state,
                    })
                    ->color('info'),
                TextColumn::make('reward_value')
                    ->label('Miqdori')
                    ->numeric()
                    ->suffix(' so\'m')
                    ->sortable(),
                TextColumn::make('period_type')
                    ->label('Davr')
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'monthly' => 'Oylik',
                        'quarterly' => 'Choraklik',
                        'yearly' => 'Yillik',
                        default => $state,
                    })
                    ->badge()
                    ->color('warning'),
                IconColumn::make('is_active')
                    ->label('Faol')
                    ->boolean(),
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
