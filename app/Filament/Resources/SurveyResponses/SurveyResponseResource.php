<?php

namespace App\Filament\Resources\SurveyResponses;

use App\Filament\Resources\SurveyResponses\Pages\CreateSurveyResponse;
use App\Filament\Resources\SurveyResponses\Pages\EditSurveyResponse;
use App\Filament\Resources\SurveyResponses\Pages\ListSurveyResponses;
use App\Filament\Resources\SurveyResponses\Pages\ViewSurveyResponse;
use App\Filament\Resources\SurveyResponses\Schemas\SurveyResponseForm;
use App\Filament\Resources\SurveyResponses\Tables\SurveyResponsesTable;
use App\Models\SurveyResponse;
use BackedEnum;
use App\Filament\Resources\BaseResource;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SurveyResponseResource extends BaseResource
{
    protected static ?string $model = SurveyResponse::class;

    protected static ?string $permission = 'manage_feedback';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;

    protected static ?string $navigationLabel = 'So\'rov javoblari';

    protected static ?string $modelLabel = 'So\'rov javobi';

    protected static ?string $pluralModelLabel = 'So\'rov javoblari';

    public static function form(Schema $schema): Schema
    {
        return SurveyResponseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Bemor javobi')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('doctor.full_name')
                            ->label('Shifokor'),
                        TextEntry::make('clinic.name')
                            ->label('Klinika'),
                        TextEntry::make('branch.name')
                            ->label('Filial')
                            ->default('—'),
                    ]),
                    Grid::make(3)->schema([
                        TextEntry::make('channel')
                            ->label('Kanal')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'qr' => 'success',
                                'telegram' => 'info',
                                'shortlink' => 'warning',
                                default => 'gray',
                            }),
                        TextEntry::make('language')
                            ->label('Til')
                            ->badge(),
                        TextEntry::make('submitted_at')
                            ->label('Yuborilgan')
                            ->dateTime('d.m.Y H:i'),
                    ]),
                ]),

            Section::make('Javoblar')
                ->icon('heroicon-o-clipboard-document-list')
                ->schema([
                    RepeatableEntry::make('answers')
                        ->label('')
                        ->schema([
                            Grid::make(3)->schema([
                                TextEntry::make('question.title')
                                    ->label('Savol')
                                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['uz_latn'] ?? $state['ru'] ?? $state['en'] ?? '-') : $state),
                                TextEntry::make('question.type')
                                    ->label('Turi')
                                    ->badge()
                                    ->color('gray'),
                                TextEntry::make('display_value')
                                    ->label('Javob')
                                    ->state(function ($record) {
                                        if ($record->text_answer) {
                                            return $record->text_answer;
                                        }
                                        if ($record->rating_value !== null) {
                                            return str_repeat('★', $record->rating_value) . str_repeat('☆', 5 - $record->rating_value) . ' (' . $record->rating_value . '/5)';
                                        }
                                        if ($record->boolean_value !== null) {
                                            return $record->boolean_value ? 'Ha ✅' : 'Yo\'q ❌';
                                        }
                                        if ($record->nps_value !== null) {
                                            return $record->nps_value . '/10';
                                        }
                                        if ($record->severity_level !== null) {
                                            return $record->severity_level . '/5';
                                        }
                                        if ($record->option_value !== null) {
                                            return $record->option_value;
                                        }
                                        return '—';
                                    })
                                    ->weight('bold')
                                    ->color(fn ($record) => match (true) {
                                        $record->rating_value >= 4, $record->boolean_value === true => 'success',
                                        $record->rating_value === 3 => 'warning',
                                        $record->rating_value !== null && $record->rating_value <= 2, $record->boolean_value === false => 'danger',
                                        default => null,
                                    }),
                            ]),
                        ]),
                ]),

            Section::make('Ballar')
                ->icon('heroicon-o-chart-bar')
                ->collapsible()
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('quality_score')
                            ->label('Sifat bali')
                            ->numeric(1)
                            ->color(fn ($state) => $state >= 80 ? 'success' : ($state >= 60 ? 'warning' : 'danger'))
                            ->weight('bold'),
                        TextEntry::make('confidence_score')
                            ->label('Ishonch bali')
                            ->numeric(1),
                        TextEntry::make('sentiment_score')
                            ->label('Kayfiyat bali')
                            ->numeric(2)
                            ->color(fn ($state) => $state > 0.3 ? 'success' : ($state > -0.3 ? 'warning' : 'danger')),
                    ]),
                    Grid::make(3)->schema([
                        TextEntry::make('fraud_score')
                            ->label('Firibgarlik bali')
                            ->numeric(1)
                            ->color(fn ($state) => $state >= 60 ? 'danger' : ($state >= 30 ? 'warning' : 'success')),
                        IconEntry::make('is_flagged')
                            ->label('Bayroqli')
                            ->boolean()
                            ->trueIcon('heroicon-o-flag')
                            ->falseIcon('heroicon-o-check-circle')
                            ->trueColor('danger')
                            ->falseColor('success'),
                        TextEntry::make('moderation_status')
                            ->label('Moderatsiya')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'approved' => 'success',
                                'pending' => 'warning',
                                'needs_review' => 'info',
                                'rejected' => 'danger',
                                default => 'gray',
                            }),
                    ]),
                ]),

            Section::make('Qayta aloqa')
                ->icon('heroicon-o-phone')
                ->collapsible()
                ->collapsed()
                ->visible(fn ($record) => $record->callback_requested)
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('callback_contact')
                            ->label('Aloqa'),
                        TextEntry::make('callback_note')
                            ->label('Izoh'),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return SurveyResponsesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSurveyResponses::route('/'),
            'create' => CreateSurveyResponse::route('/create'),
            'view' => ViewSurveyResponse::route('/{record}'),
            'edit' => EditSurveyResponse::route('/{record}/edit'),
        ];
    }
}
