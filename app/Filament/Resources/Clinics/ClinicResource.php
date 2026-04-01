<?php

namespace App\Filament\Resources\Clinics;

use App\Filament\Resources\Clinics\Pages\CreateClinic;
use App\Filament\Resources\Clinics\Pages\EditClinic;
use App\Filament\Resources\Clinics\Pages\ListClinics;
use App\Filament\Resources\Clinics\Schemas\ClinicForm;
use App\Filament\Resources\Clinics\Tables\ClinicsTable;
use App\Models\Clinic;
use BackedEnum;
use App\Filament\Resources\BaseResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClinicResource extends BaseResource
{
    protected static ?string $model = Clinic::class;

    protected static ?string $permission = 'manage_clinics';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    public static function form(Schema $schema): Schema
    {
        return ClinicForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClinicsTable::configure($table);
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
            'index' => ListClinics::route('/'),
            'create' => CreateClinic::route('/create'),
            'edit' => EditClinic::route('/{record}/edit'),
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





