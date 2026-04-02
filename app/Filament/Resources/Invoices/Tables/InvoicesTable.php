<?php

namespace App\Filament\Resources\Invoices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InvoicesTable
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
                TextColumn::make('invoice_number')
                    ->label('Hisob raqami')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('amount_due')
                    ->label('Summa')
                    ->numeric()
                    ->suffix(' so\'m')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Holati')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'paid' => 'success',
                        'issued' => 'info',
                        'draft' => 'gray',
                        'overdue' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'paid' => 'To\'langan',
                        'issued' => 'Chiqarilgan',
                        'draft' => 'Qoralama',
                        'overdue' => 'Muddati o\'tgan',
                        'cancelled' => 'Bekor',
                        default => $state,
                    }),
                TextColumn::make('due_date')
                    ->label('To\'lov muddati')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('period_start')
                    ->label('Davr')
                    ->date('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
