<?php

namespace App\Filament\Resources\PatronageEscalationRules;

use App\Filament\Resources\PatronageEscalationRules\Pages\CreatePatronageEscalationRule;
use App\Filament\Resources\PatronageEscalationRules\Pages\EditPatronageEscalationRule;
use App\Filament\Resources\PatronageEscalationRules\Pages\ListPatronageEscalationRules;
use App\Filament\Resources\PatronageEscalationRules\Schemas\PatronageEscalationRuleForm;
use App\Filament\Resources\PatronageEscalationRules\Tables\PatronageEscalationRulesTable;
use App\Models\PatronageEscalationRule;
use BackedEnum;
use App\Filament\Resources\BaseResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PatronageEscalationRuleResource extends BaseResource
{
    protected static ?string $model = PatronageEscalationRule::class;

    protected static ?string $permission = 'manage_escalations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBellAlert;

    public static function form(Schema $schema): Schema
    {
        return PatronageEscalationRuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PatronageEscalationRulesTable::configure($table);
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
            'index' => ListPatronageEscalationRules::route('/'),
            'create' => CreatePatronageEscalationRule::route('/create'),
            'edit' => EditPatronageEscalationRule::route('/{record}/edit'),
        ];
    }
}
