<?php

namespace App\Filament\Resources\SurveyResponses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SurveyResponsesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                TextColumn::make('clinic.name')
                    ->searchable(),
                TextColumn::make('branch.name')
                    ->searchable(),
                TextColumn::make('department.name')
                    ->searchable(),
                TextColumn::make('doctor.id')
                    ->searchable(),
                TextColumn::make('servicePoint.name')
                    ->searchable(),
                TextColumn::make('qrCode.id')
                    ->searchable(),
                TextColumn::make('survey.name')
                    ->searchable(),
                TextColumn::make('channel')
                    ->searchable(),
                TextColumn::make('submitted_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('language')
                    ->searchable(),
                TextColumn::make('ip_hash')
                    ->searchable(),
                TextColumn::make('device_hash')
                    ->searchable(),
                TextColumn::make('fingerprint_hash')
                    ->searchable(),
                TextColumn::make('fraud_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('anomaly_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sentiment_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('severity_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('confidence_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quality_score')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_flagged')
                    ->boolean(),
                TextColumn::make('moderation_status')
                    ->searchable(),
                IconColumn::make('is_duplicate')
                    ->boolean(),
                TextColumn::make('duplicate_of_response_id')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('callback_requested')
                    ->boolean(),
                TextColumn::make('submitted_from_country')
                    ->searchable(),
                TextColumn::make('ai_processed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
