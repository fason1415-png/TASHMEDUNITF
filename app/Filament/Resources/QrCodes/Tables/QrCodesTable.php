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
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('clinic.name')
                    ->label('Klinika')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('doctor.full_name')
                    ->label('Shifokor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->label('Filial')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('code')
                    ->label('Kod')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                IconColumn::make('is_active')
                    ->label('Faol')
                    ->boolean(),
                TextColumn::make('scan_count')
                    ->label('Skanlar')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('last_scanned_at')
                    ->label('Oxirgi skan')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('expires_at')
                    ->label('Muddati')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->placeholder('Cheksiz')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('openSurvey')
                    ->label('Ochish')
                    ->icon('heroicon-o-globe-alt')
                    ->url(fn (QrCode $record): string => route('survey.show', ['token' => $record->token]), shouldOpenInNewTab: true),
                Action::make('printLabel')
                    ->label('Chop etish')
                    ->icon('heroicon-o-printer')
                    ->url(fn (QrCode $record): string => route('qr.label', ['qrCode' => $record->id]), shouldOpenInNewTab: true),
                Action::make('toggleActive')
                    ->label(fn (QrCode $record): string => $record->is_active ? 'O\'chirish' : 'Yoqish')
                    ->icon('heroicon-o-power')
                    ->action(function (QrCode $record): void {
                        $record->update(['is_active' => ! $record->is_active]);
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
