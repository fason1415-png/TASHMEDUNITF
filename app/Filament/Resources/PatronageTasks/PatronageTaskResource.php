<?php

namespace App\Filament\Resources\PatronageTasks;

use App\Filament\Resources\PatronageTasks\Pages\ListPatronageTasks;
use App\Filament\Resources\PatronageTasks\Tables\PatronageTasksTable;
use App\Models\PatronageTask;
use BackedEnum;
use App\Filament\Resources\BaseResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PatronageTaskResource extends BaseResource
{
    protected static ?string $model = PatronageTask::class;

    protected static ?string $permission = null;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return PatronageTasksTable::configure($table);
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
            'index' => ListPatronageTasks::route('/'),
        ];
    }
}
