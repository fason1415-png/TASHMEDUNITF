<?php

namespace App\Filament\Resources\SurveyResponses;

use App\Filament\Resources\SurveyResponses\Pages\CreateSurveyResponse;
use App\Filament\Resources\SurveyResponses\Pages\EditSurveyResponse;
use App\Filament\Resources\SurveyResponses\Pages\ListSurveyResponses;
use App\Filament\Resources\SurveyResponses\Schemas\SurveyResponseForm;
use App\Filament\Resources\SurveyResponses\Tables\SurveyResponsesTable;
use App\Models\SurveyResponse;
use BackedEnum;
use App\Filament\Resources\BaseResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SurveyResponseResource extends BaseResource
{
    protected static ?string $model = SurveyResponse::class;

    protected static ?string $permission = 'manage_feedback';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;

    public static function form(Schema $schema): Schema
    {
        return SurveyResponseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SurveyResponsesTable::configure($table);
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
            'index' => ListSurveyResponses::route('/'),
            'create' => CreateSurveyResponse::route('/create'),
            'edit' => EditSurveyResponse::route('/{record}/edit'),
        ];
    }
}





