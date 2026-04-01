<?php

namespace App\Filament\Resources\ServicePoints;

use App\Filament\Resources\ServicePoints\Pages\CreateServicePoint;
use App\Filament\Resources\ServicePoints\Pages\EditServicePoint;
use App\Filament\Resources\ServicePoints\Pages\ListServicePoints;
use App\Filament\Resources\ServicePoints\Schemas\ServicePointForm;
use App\Filament\Resources\ServicePoints\Tables\ServicePointsTable;
use App\Models\ServicePoint;
use BackedEnum;
use App\Filament\Resources\BaseResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServicePointResource extends BaseResource
{
    protected static ?string $model = ServicePoint::class;

    protected static ?string $permission = 'manage_service_points';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    public static function form(Schema $schema): Schema
    {
        return ServicePointForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicePointsTable::configure($table);
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
            'index' => ListServicePoints::route('/'),
            'create' => CreateServicePoint::route('/create'),
            'edit' => EditServicePoint::route('/{record}/edit'),
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





