<?php

namespace App\Filament\Resources\Rewards\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RewardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rag\'bat')
                    ->icon('heroicon-o-trophy')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('clinic_id')
                                ->label('Klinika')
                                ->relationship('clinic', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),
                            Select::make('doctor_id')
                                ->label('Shifokor')
                                ->relationship('doctor', 'full_name')
                                ->searchable()
                                ->preload(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Sarlavha')
                                ->required()
                                ->placeholder('Masalan: Oylik mukofot'),
                            Select::make('status')
                                ->label('Holati')
                                ->options([
                                    'eligible' => 'Munosib',
                                    'approved' => 'Tasdiqlangan',
                                    'paid' => 'To\'langan',
                                    'rejected' => 'Rad etilgan',
                                ])
                                ->default('eligible')
                                ->required()
                                ->native(false),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('amount')
                                ->label('Miqdori')
                                ->numeric()
                                ->suffix('so\'m'),
                            TextInput::make('eligibility_score')
                                ->label('Ball')
                                ->numeric()
                                ->disabled()
                                ->helperText('Avtomatik hisoblanadi'),
                        ]),
                        Grid::make(2)->schema([
                            DatePicker::make('period_start')
                                ->label('Davr boshlanishi'),
                            DatePicker::make('period_end')
                                ->label('Davr tugashi'),
                        ]),
                        Textarea::make('description')
                            ->label('Tavsif')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
