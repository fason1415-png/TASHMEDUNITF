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
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('patient.full_name')
                    ->searchable(),
                TextColumn::make('familyDoctor.full_name'),
                TextColumn::make('task_type')
                    ->badge(),
                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'normal' => 'gray',
                        'high' => 'warning',
                        'urgent' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
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
                TextColumn::make('due_at')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('sla_breached')
                    ->boolean()
                    ->label('SLA'),
                TextColumn::make('escalation_level'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'notified' => 'Notified',
                        'accepted' => 'Accepted',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'missed' => 'Missed',
                        'escalated' => 'Escalated',
                    ]),
                SelectFilter::make('priority')
                    ->options([
                        'normal' => 'Normal',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ]),
                TernaryFilter::make('sla_breached'),
            ]);
    }
}
