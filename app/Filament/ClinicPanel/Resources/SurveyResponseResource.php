<?php

namespace App\Filament\ClinicPanel\Resources;

use App\Filament\ClinicPanel\Resources\SurveyResponseResource\Pages;
use App\Models\SurveyResponse;
use Filament\Infolists\Components\IconEntry;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SurveyResponseResource extends Resource
{
    protected static ?string $model = SurveyResponse::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Feedback';

    protected static ?string $navigationLabel = 'So\'rov natijalari';

    protected static ?string $modelLabel = 'So\'rov natijasi';

    protected static ?string $pluralModelLabel = 'So\'rov natijalari';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static int|null $navigationSort = 50;

    public static function canCreate(): bool
    {
        return false;
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
                        TextEntry::make('branch.name')
                            ->label('Filial')
                            ->default('—'),
                        TextEntry::make('submitted_at')
                            ->label('Yuborilgan')
                            ->dateTime('d.m.Y H:i'),
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
                        TextEntry::make('quality_score')
                            ->label('Sifat bali')
                            ->numeric(1)
                            ->color(fn ($state) => $state >= 80 ? 'success' : ($state >= 60 ? 'warning' : 'danger'))
                            ->weight('bold'),
                        TextEntry::make('moderation_status')
                            ->label('Holat')
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

            Section::make('Qayta aloqa')
                ->icon('heroicon-o-phone')
                ->collapsible()
                ->collapsed()
                ->visible(fn ($record) => $record->callback_requested)
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('callback_contact')
                            ->label('Telefon raqam')
                            ->icon('heroicon-o-phone')
                            ->formatStateUsing(function ($state) {
                                if (!$state) return '—';
                                try {
                                    return \Illuminate\Support\Facades\Crypt::decryptString($state);
                                } catch (\Throwable) {
                                    return $state;
                                }
                            })
                            ->copyable()
                            ->weight('bold'),
                        TextEntry::make('callback_note')
                            ->label('Izoh'),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('doctor.full_name')
                    ->label('Shifokor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->label('Filial')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('channel')
                    ->label('Kanal')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'qr' => 'success',
                        'telegram' => 'info',
                        'shortlink' => 'warning',
                        default => 'gray',
                    }),
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
                TextColumn::make('moderation_status')
                    ->label('Holat')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'needs_review' => 'info',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('submitted_at')
                    ->label('Yuborilgan')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->filters([
                SelectFilter::make('channel')
                    ->label('Kanal')
                    ->options([
                        'qr' => 'QR Code',
                        'shortlink' => 'Shortlink',
                        'telegram' => 'Telegram',
                    ]),
                TernaryFilter::make('is_flagged')
                    ->label('Bayroqli'),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveyResponses::route('/'),
            'view' => Pages\ViewSurveyResponse::route('/{record}'),
        ];
    }
}
