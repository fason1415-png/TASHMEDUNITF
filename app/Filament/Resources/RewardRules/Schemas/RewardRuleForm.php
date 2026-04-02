<?php

namespace App\Filament\Resources\RewardRules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RewardRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rag\'bat qoidasi')
                    ->icon('heroicon-o-gift')
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
                                ->placeholder('Masalan: Oylik eng yaxshi shifokor'),
                        ]),
                        Textarea::make('description')
                            ->label('Tavsifi')
                            ->rows(2)
                            ->placeholder('Qoidaning qisqacha tavsifi')
                            ->columnSpanFull(),
                        Grid::make(3)->schema([
                            Select::make('trigger_type')
                                ->label('Qachon beriladi')
                                ->options([
                                    'rank' => 'Reyting bo\'yicha',
                                    'threshold' => 'Chegara bo\'yicha',
                                    'milestone' => 'Belgilangan songa yetganda',
                                ])
                                ->required()
                                ->native(false),
                            Select::make('reward_type')
                                ->label('Mukofot turi')
                                ->options([
                                    'bonus' => 'Pul mukofoti',
                                    'certificate' => 'Sertifikat',
                                    'badge' => 'Nishon',
                                ])
                                ->required()
                                ->native(false),
                            TextInput::make('reward_value')
                                ->label('Miqdori')
                                ->numeric()
                                ->suffix('so\'m'),
                        ]),
                        Grid::make(2)->schema([
                            Select::make('period_type')
                                ->label('Davr')
                                ->options([
                                    'monthly' => 'Oylik',
                                    'quarterly' => 'Choraklik',
                                    'yearly' => 'Yillik',
                                ])
                                ->default('monthly')
                                ->required()
                                ->native(false),
                            Toggle::make('is_active')
                                ->label('Faol')
                                ->default(true)
                                ->inline(),
                        ]),
                    ]),
            ]);
    }
}
