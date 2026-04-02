<?php

namespace App\Filament\DoctorPanel\Resources;

use App\Filament\DoctorPanel\Resources\MyFeedbackResource\Pages;
use App\Models\SurveyResponse;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MyFeedbackResource extends Resource
{
    protected static ?string $model = SurveyResponse::class;

    protected static ?string $navigationLabel = 'Menga qo\'yilgan baholar';

    protected static ?string $modelLabel = 'Baho';

    protected static ?string $pluralModelLabel = 'Baholar';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $slug = 'my-feedback';

    protected static int|null $navigationSort = 10;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('doctor_id', auth()->user()->doctor_id);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Bemor bahosi')
                ->icon('heroicon-o-star')
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('quality_score')
                            ->label('Sifat bali')
                            ->numeric(1)
                            ->color(fn ($state) => $state >= 80 ? 'success' : ($state >= 60 ? 'warning' : 'danger'))
                            ->weight('bold'),
                        TextEntry::make('channel')
                            ->label('Kanal')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'qr' => 'success',
                                'telegram' => 'info',
                                default => 'gray',
                            }),
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
                            Grid::make(2)->schema([
                                TextEntry::make('question.title')
                                    ->label('Savol')
                                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['uz_latn'] ?? $state['ru'] ?? $state['en'] ?? '-') : $state),
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
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('quality_score')
                    ->label('Sifat bali')
                    ->numeric(1)
                    ->sortable()
                    ->color(fn ($state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        $state >= 0 => 'danger',
                        default => 'gray',
                    })
                    ->weight('bold'),
                TextColumn::make('sentiment_score')
                    ->label('Kayfiyat')
                    ->numeric(2)
                    ->sortable()
                    ->color(fn ($state): string => match (true) {
                        $state > 0.3 => 'success',
                        $state > -0.3 => 'warning',
                        default => 'danger',
                    }),
                IconColumn::make('is_flagged')
                    ->label('Bayroq')
                    ->boolean()
                    ->trueIcon('heroicon-o-flag')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('danger')
                    ->falseColor('gray'),
                TextColumn::make('channel')
                    ->label('Kanal')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'qr' => 'success',
                        'telegram' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('submitted_at')
                    ->label('Sana')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyFeedback::route('/'),
            'view' => Pages\ViewMyFeedback::route('/{record}'),
        ];
    }
}
