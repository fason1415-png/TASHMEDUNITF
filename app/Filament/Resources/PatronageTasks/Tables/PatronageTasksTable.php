<?php

namespace App\Filament\Resources\PatronageTasks\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PatronageTasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('due_at', 'asc')
            ->columns([
                TextColumn::make('patient.full_name')
                    ->label('Bemor')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('familyDoctor.full_name')
                    ->label('Shifokor')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Holat')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pending' => 'Kutilmoqda',
                        'notified' => 'Xabar berildi',
                        'accepted' => 'Qabul qilindi',
                        'in_progress' => 'Jarayonda',
                        'completed' => 'Bajarildi',
                        'missed' => 'O\'tkazib yuborildi',
                        'escalated' => 'Eskalatsiya',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'notified' => 'info',
                        'accepted' => 'primary',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'missed' => 'danger',
                        'escalated' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('priority')
                    ->label('Muhimlik')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'normal' => 'Oddiy',
                        'high' => 'Muhim',
                        'urgent' => 'Shoshilinch',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'normal' => 'gray',
                        'high' => 'warning',
                        'urgent' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('due_at')
                    ->label('Muddat')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->color(fn ($record) => $record->due_at?->isPast() && !in_array($record->status, ['completed', 'missed']) ? 'danger' : null),
                IconColumn::make('sla_breached')
                    ->label('SLA')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->trueColor('danger')
                    ->falseIcon('heroicon-o-check-circle')
                    ->falseColor('success'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Holat')
                    ->options([
                        'pending' => 'Kutilmoqda',
                        'notified' => 'Xabar berildi',
                        'accepted' => 'Qabul qilindi',
                        'in_progress' => 'Jarayonda',
                        'completed' => 'Bajarildi',
                        'missed' => 'O\'tkazib yuborildi',
                    ]),
                SelectFilter::make('priority')
                    ->label('Muhimlik')
                    ->options([
                        'normal' => 'Oddiy',
                        'high' => 'Muhim',
                        'urgent' => 'Shoshilinch',
                    ]),
                TernaryFilter::make('sla_breached')
                    ->label('SLA buzilgan'),
            ]);
    }
}
