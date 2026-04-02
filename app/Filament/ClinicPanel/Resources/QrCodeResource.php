<?php

namespace App\Filament\ClinicPanel\Resources;

use App\Filament\ClinicPanel\Resources\QrCodeResource\Pages;
use App\Models\QrCode;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QrCodeResource extends Resource
{
    protected static ?string $model = QrCode::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Structure';

    protected static int | null $navigationSort = 40;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('target_type')->sortable(),
                TextColumn::make('doctor.full_name')->searchable(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('scan_count')->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQrCodes::route('/'),
        ];
    }
}
