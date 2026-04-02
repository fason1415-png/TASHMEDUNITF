<?php

namespace App\Filament\Resources\SurveyResponses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SurveyResponsesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('submitted_at', 'desc')
            ->columns([
                TextColumn::make('doctor.full_name')
                    ->label(__('resources.survey_responses.doctor'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('clinic.name')
                    ->label(__('resources.survey_responses.clinic'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('branch.name')
                    ->label(__('resources.survey_responses.branch'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('department.name')
                    ->label(__('resources.survey_responses.department'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('channel')
                    ->label(__('resources.survey_responses.channel'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'qr' => 'success',
                        'telegram' => 'info',
                        'shortlink' => 'warning',
                        'kiosk' => 'gray',
                        'sms' => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('quality_score')
                    ->label(__('resources.survey_responses.quality'))
                    ->numeric(1)
                    ->sortable()
                    ->color(fn ($state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        $state >= 0 => 'danger',
                        default => 'gray',
                    })
                    ->weight('bold'),
                TextColumn::make('confidence_score')
                    ->label(__('resources.survey_responses.confidence'))
                    ->numeric(1)
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('sentiment_score')
                    ->label(__('resources.survey_responses.sentiment'))
                    ->numeric(2)
                    ->sortable()
                    ->color(fn ($state): string => match (true) {
                        $state > 0.3 => 'success',
                        $state > -0.3 => 'warning',
                        default => 'danger',
                    })
                    ->toggleable(),
                IconColumn::make('is_flagged')
                    ->label(__('resources.survey_responses.flagged'))
                    ->boolean()
                    ->trueIcon('heroicon-o-flag')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('danger')
                    ->falseColor('gray'),
                TextColumn::make('moderation_status')
                    ->label(__('resources.survey_responses.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'needs_review' => 'info',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                IconColumn::make('callback_requested')
                    ->label(__('resources.survey_responses.callback'))
                    ->boolean()
                    ->trueIcon('heroicon-o-phone-arrow-up-right')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->toggleable(),
                TextColumn::make('language')
                    ->label(__('resources.survey_responses.language'))
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fraud_score')
                    ->label(__('resources.survey_responses.fraud'))
                    ->numeric(1)
                    ->sortable()
                    ->color(fn ($state): string => $state >= 60 ? 'danger' : ($state >= 30 ? 'warning' : 'success'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('submitted_at')
                    ->label(__('resources.survey_responses.submitted'))
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('channel')
                    ->options([
                        'qr' => 'QR Code',
                        'shortlink' => 'Shortlink',
                        'kiosk' => 'Kiosk',
                        'telegram' => 'Telegram',
                        'sms' => 'SMS',
                    ]),
                SelectFilter::make('moderation_status')
                    ->options([
                        'pending' => 'Kutilmoqda',
                        'approved' => 'Tasdiqlangan',
                        'needs_review' => 'Ko\'rib chiqish kerak',
                        'rejected' => 'Rad etilgan',
                    ]),
                TernaryFilter::make('is_flagged')
                    ->label('Bayroqli'),
                TernaryFilter::make('callback_requested')
                    ->label('Qayta aloqa'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
