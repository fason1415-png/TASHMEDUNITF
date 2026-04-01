<?php

namespace App\Filament\Resources\Escalations;

use App\Filament\Resources\Escalations\Pages\CreateEscalation;
use App\Filament\Resources\Escalations\Pages\EditEscalation;
use App\Filament\Resources\Escalations\Pages\ListEscalations;
use App\Filament\Resources\Escalations\Schemas\EscalationForm;
use App\Filament\Resources\Escalations\Tables\EscalationsTable;
use App\Models\Escalation;
use BackedEnum;
use App\Filament\Resources\BaseResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EscalationResource extends BaseResource
{
    protected static ?string $model = Escalation::class;

    protected static ?string $permission = 'manage_escalations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    public static function form(Schema $schema): Schema
    {
        return EscalationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EscalationsTable::configure($table);
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
            'index' => ListEscalations::route('/'),
            'create' => CreateEscalation::route('/create'),
            'edit' => EditEscalation::route('/{record}/edit'),
        ];
    }
}





