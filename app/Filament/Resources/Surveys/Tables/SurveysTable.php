<?php

namespace App\Filament\Resources\Surveys\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class SurveysTable
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
                TextColumn::make('questions_count')
                    ->label('Savollar')
                    ->counts('questions')
                    ->badge()
                    ->color('info'),
                IconColumn::make('is_active')
                    ->label('Faol')
                    ->boolean(),
                IconColumn::make('is_default')
                    ->label('Asosiy')
                    ->boolean(),
                TextColumn::make('estimated_seconds')
                    ->label('Vaqt')
                    ->suffix(' s')
                    ->sortable(),
                TextColumn::make('responses_count')
                    ->label('Javoblar')
                    ->counts('responses')
                    ->badge()
                    ->color('success'),
                TextColumn::make('created_at')
                    ->label('Yaratilgan')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
