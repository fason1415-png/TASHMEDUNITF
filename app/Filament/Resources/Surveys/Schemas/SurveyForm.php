<?php

namespace App\Filament\Resources\Surveys\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SurveyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Asosiy ma\'lumotlar')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('clinic_id')
                                ->label('Klinika')
                                ->relationship('clinic', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),
                            TextInput::make('name')
                                ->label('Nomi')
                                ->required()
                                ->maxLength(255),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('slug')
                                ->label('Slug (havola)')
                                ->maxLength(255)
                                ->helperText('Avtomatik yaratiladi'),
                            TextInput::make('estimated_seconds')
                                ->label('Taxminiy vaqt (soniya)')
                                ->numeric()
                                ->default(60)
                                ->suffix('soniya'),
                        ]),
                    ]),

                Section::make('Sarlavha va tavsif')
                    ->icon('heroicon-o-language')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title.uz_latn')
                                ->label('Sarlavha (O\'zbekcha)')
                                ->required(),
                            TextInput::make('title.ru')
                                ->label('Sarlavha (Ruscha)'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('title.uz_cyrl')
                                ->label('Sarlavha (Кирилл)'),
                            TextInput::make('title.en')
                                ->label('Sarlavha (English)'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('description.uz_latn')
                                ->label('Tavsif (O\'zbekcha)'),
                            TextInput::make('description.ru')
                                ->label('Tavsif (Ruscha)'),
                        ]),
                    ]),

                Section::make('Sozlamalar')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(3)->schema([
                            Toggle::make('is_active')
                                ->label('Faol')
                                ->default(true),
                            Toggle::make('is_default')
                                ->label('Asosiy so\'rov')
                                ->default(true),
                            Toggle::make('allow_anonymous')
                                ->label('Anonim ruxsat')
                                ->default(true),
                        ]),
                        Grid::make(3)->schema([
                            Toggle::make('callback_enabled')
                                ->label('Qayta aloqa')
                                ->default(true),
                            Toggle::make('require_token_verification')
                                ->label('Token tekshiruvi'),
                        ]),
                        Grid::make(2)->schema([
                            DateTimePicker::make('starts_at')
                                ->label('Boshlanish sanasi'),
                            DateTimePicker::make('ends_at')
                                ->label('Tugash sanasi'),
                        ]),
                    ]),

                Section::make('Savollar')
                    ->icon('heroicon-o-question-mark-circle')
                    ->description('So\'rovdagi savollarni qo\'shing, o\'zgartiring yoki o\'chiring')
                    ->schema([
                        Repeater::make('questions')
                            ->label('')
                            ->relationship()
                            ->orderColumn('order_index')
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string =>
                                ($state['title']['uz_latn'] ?? $state['title']['ru'] ?? 'Yangi savol')
                                . ' (' . ($state['type'] ?? '?') . ')'
                            )
                            ->defaultItems(0)
                            ->addActionLabel('+ Savol qo\'shish')
                            ->schema([
                                Grid::make(3)->schema([
                                    TextInput::make('key')
                                        ->label('Kalit (ID)')
                                        ->required()
                                        ->maxLength(100)
                                        ->helperText('Masalan: service_quality')
                                        ->alphaDash(),
                                    Select::make('type')
                                        ->label('Turi')
                                        ->required()
                                        ->options([
                                            'rating' => '⭐ Baho (1-5 yulduz)',
                                            'yes_no' => '✅ Ha / Yo\'q',
                                            'recommend' => '👍 Tavsiya qilasizmi?',
                                            'comment' => '💬 Izoh (matn)',
                                            'single_choice' => '🔘 Tanlov',
                                            'nps' => '📊 NPS (0-10)',
                                            'severity' => '⚠️ Darajasi (1-5)',
                                        ])
                                        ->native(false),
                                    TextInput::make('weight')
                                        ->label('Vazni')
                                        ->numeric()
                                        ->default(1.00)
                                        ->step(0.1)
                                        ->minValue(0)
                                        ->maxValue(10),
                                ]),
                                Grid::make(2)->schema([
                                    TextInput::make('title.uz_latn')
                                        ->label('Savol matni (O\'zbekcha)')
                                        ->required(),
                                    TextInput::make('title.ru')
                                        ->label('Savol matni (Ruscha)'),
                                ]),
                                Grid::make(2)->schema([
                                    TextInput::make('title.uz_cyrl')
                                        ->label('Savol matni (Кирилл)'),
                                    TextInput::make('title.en')
                                        ->label('Savol matni (English)'),
                                ]),
                                Grid::make(2)->schema([
                                    Toggle::make('is_required')
                                        ->label('Majburiy')
                                        ->default(true)
                                        ->inline(),
                                ]),
                                Repeater::make('options')
                                    ->label('Variantlar (faqat "Tanlov" turi uchun)')
                                    ->relationship()
                                    ->orderColumn('order_index')
                                    ->defaultItems(0)
                                    ->addActionLabel('+ Variant qo\'shish')
                                    ->collapsible()
                                    ->collapsed()
                                    ->visible(fn ($get) => $get('type') === 'single_choice')
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('label.uz_latn')
                                                ->label('Variant (O\'zbekcha)')
                                                ->required(),
                                            TextInput::make('value')
                                                ->label('Qiymati')
                                                ->required(),
                                            TextInput::make('score_value')
                                                ->label('Ball')
                                                ->numeric()
                                                ->default(50),
                                        ]),
                                        Grid::make(2)->schema([
                                            TextInput::make('label.ru')
                                                ->label('Variant (Ruscha)'),
                                            Toggle::make('is_active')
                                                ->label('Faol')
                                                ->default(true)
                                                ->inline(),
                                        ]),
                                    ])
                                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data, $livewire) {
                                        $data['clinic_id'] = $livewire->data['clinic_id'] ?? $livewire->record?->clinic_id;
                                        return $data;
                                    }),
                            ])
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data, $livewire) {
                                $data['clinic_id'] = $livewire->data['clinic_id'] ?? $livewire->record?->clinic_id;
                                return $data;
                            }),
                    ]),
            ]);
    }
}
