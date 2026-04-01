<?php

namespace App\Filament\Resources\QrCodes;

use App\Filament\Resources\QrCodes\Pages\CreateQrCode;
use App\Filament\Resources\QrCodes\Pages\EditQrCode;
use App\Filament\Resources\QrCodes\Pages\ListQrCodes;
use App\Filament\Resources\QrCodes\Schemas\QrCodeForm;
use App\Filament\Resources\QrCodes\Tables\QrCodesTable;
use App\Models\QrCode;
use BackedEnum;
use App\Filament\Resources\BaseResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QrCodeResource extends BaseResource
{
    protected static ?string $model = QrCode::class;

    protected static ?string $permission = 'manage_qr_codes';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;

    public static function form(Schema $schema): Schema
    {
        return QrCodeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QrCodesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQrCodes::route('/'),
            'create' => CreateQrCode::route('/create'),
            'edit' => EditQrCode::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}





