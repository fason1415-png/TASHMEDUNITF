<?php

namespace App\Filament\Resources\SuspiciousFlags;

use App\Filament\Resources\SuspiciousFlags\Pages\CreateSuspiciousFlag;
use App\Filament\Resources\SuspiciousFlags\Pages\EditSuspiciousFlag;
use App\Filament\Resources\SuspiciousFlags\Pages\ListSuspiciousFlags;
use App\Filament\Resources\SuspiciousFlags\Schemas\SuspiciousFlagForm;
use App\Filament\Resources\SuspiciousFlags\Tables\SuspiciousFlagsTable;
use App\Models\SuspiciousFlag;
use BackedEnum;
use App\Filament\Resources\BaseResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SuspiciousFlagResource extends BaseResource
{
    protected static ?string $model = SuspiciousFlag::class;

    protected static ?string $permission = 'moderate_feedback';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldExclamation;

    public static function form(Schema $schema): Schema
    {
        return SuspiciousFlagForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuspiciousFlagsTable::configure($table);
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
            'index' => ListSuspiciousFlags::route('/'),
            'create' => CreateSuspiciousFlag::route('/create'),
            'edit' => EditSuspiciousFlag::route('/{record}/edit'),
        ];
    }
}





