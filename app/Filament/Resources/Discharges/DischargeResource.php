<?php

namespace App\Filament\Resources\Discharges;

use App\Filament\Resources\Discharges\Pages\CreateDischarge;
use App\Filament\Resources\Discharges\Pages\EditDischarge;
use App\Filament\Resources\Discharges\Pages\ListDischarges;
use App\Filament\Resources\Discharges\Schemas\DischargeForm;
use App\Filament\Resources\Discharges\Tables\DischargesTable;
use App\Models\Discharge;
use BackedEnum;
use App\Filament\Resources\BaseResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DischargeResource extends BaseResource
{
    protected static ?string $model = Discharge::class;

    protected static ?string $permission = null;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowRightOnRectangle;

    public static function form(Schema $schema): Schema
    {
        return DischargeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DischargesTable::configure($table);
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
            'index' => ListDischarges::route('/'),
            'create' => CreateDischarge::route('/create'),
            'edit' => EditDischarge::route('/{record}/edit'),
        ];
    }
}
