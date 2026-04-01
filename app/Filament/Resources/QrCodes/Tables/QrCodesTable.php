<?php

namespace App\Filament\Resources\QrCodes\Tables;

use App\Models\QrCode;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class QrCodesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('clinic.name')
                    ->searchable(),
                TextColumn::make('branch.name')
                    ->searchable(),
                TextColumn::make('department.name')
                    ->searchable(),
                TextColumn::make('doctor.full_name')
                    ->searchable(),
                TextColumn::make('servicePoint.name')
                    ->searchable(),
                TextColumn::make('target_type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('short_url')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('scan_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('printed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_scanned_at')
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
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('openSurvey')
                    ->label('Open Survey')
                    ->icon('heroicon-o-globe-alt')
                    ->url(fn (QrCode $record): string => route('survey.show', ['token' => $record->token]), shouldOpenInNewTab: true),
                Action::make('printLabel')
                    ->label('Print PDF')
                    ->icon('heroicon-o-printer')
                    ->url(fn (QrCode $record): string => route('qr.label', ['qrCode' => $record->id]), shouldOpenInNewTab: true),
                Action::make('toggleActive')
                    ->label(fn (QrCode $record): string => $record->is_active ? 'Deactivate' : 'Activate')
                    ->icon('heroicon-o-power')
                    ->action(function (QrCode $record): void {
                        $record->update(['is_active' => ! $record->is_active]);
                    }),
                Action::make('regenerateToken')
                    ->label('Regenerate')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(function (QrCode $record): void {
                        $record->update([
                            'token' => str()->random(40),
                            'code' => strtoupper(str()->random(10)),
                        ]);
                    }),
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
