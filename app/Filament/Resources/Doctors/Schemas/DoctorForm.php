<?php

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DoctorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Asosiy ma\'lumotlar')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('full_name')
                                ->label('F.I.Sh')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('specialty')
                                ->label('Mutaxassisligi'),
                        ]),
                        Grid::make(2)->schema([
                            Select::make('clinic_id')
                                ->label('Klinika')
                                ->relationship('clinic', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),
                            Select::make('department_id')
                                ->label('Bo\'lim')
                                ->relationship('department', 'name')
                                ->searchable()
                                ->preload(),
                        ]),
                        Grid::make(2)->schema([
                            Select::make('branch_id')
                                ->label('Filial')
                                ->relationship('branch', 'name')
                                ->searchable()
                                ->preload(),
                            TextInput::make('experience_years')
                                ->label('Tajriba (yil)')
                                ->numeric()
                                ->default(0),
                        ]),
                    ]),

                Section::make('Aloqa ma\'lumotlari')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('phone')
                                ->label('Telefon raqam')
                                ->tel()
                                ->placeholder('+998 90 123 45 67')
                                ->maxLength(20),
                            TextInput::make('telegram_chat_id')
                                ->label('Telegram ID')
                                ->placeholder('Masalan: 123456789')
                                ->maxLength(50),
                        ]),
                    ]),

                Section::make('Qo\'shimcha')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->label('Holati')
                                ->options([
                                    'active' => 'Faol',
                                    'inactive' => 'Nofaol',
                                    'on_leave' => 'Ta\'tilda',
                                ])
                                ->default('active')
                                ->required(),
                            Select::make('consultation_type')
                                ->label('Qabul turi')
                                ->options([
                                    'offline' => 'Offline',
                                    'online' => 'Online',
                                    'both' => 'Ikkalasi',
                                ])
                                ->default('offline')
                                ->required(),
                        ]),
                        Grid::make(2)->schema([
                            Toggle::make('is_active')
                                ->label('Faol')
                                ->default(true)
                                ->inline(),
                        ]),
                        Textarea::make('bio')
                            ->label('Biografiya')
                            ->rows(3)
                            ->columnSpanFull(),
                        Grid::make(2)->schema([
                            DatePicker::make('hired_at')
                                ->label('Ishga qabul sanasi'),
                            DatePicker::make('left_at')
                                ->label('Ishdan ketgan sanasi'),
                        ]),
                    ]),
            ]);
    }
}
