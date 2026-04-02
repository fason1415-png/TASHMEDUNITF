<?php

namespace App\Filament\Resources\PatronageEscalationRules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PatronageEscalationRulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('escalation_level'),
                TextColumn::make('trigger_after_minutes')
                    ->formatStateUsing(fn (int $state): string => round($state / 60, 1) . ' soat'),
                TextColumn::make('notify_role'),
                TextColumn::make('notification_channels')
                    ->badge()
                    ->separator(','),
                IconColumn::make('auto_reassign')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->boolean(),
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
