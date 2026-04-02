<?php

namespace App\Filament\Resources\QrCodes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QrCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('QR kod yaratish')
                    ->icon('heroicon-o-qr-code')
                    ->description('Klinika va shifokorni tanlang — QR kod avtomatik yaratiladi')
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
                            Select::make('branch_id')
                                ->label('Filial')
                                ->relationship('branch', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('department_id')
                                ->label('Bo\'lim')
                                ->relationship('department', 'name')
                                ->searchable()
                                ->preload(),
                        ]),
                        Grid::make(2)->schema([
                            Toggle::make('is_active')
                                ->label('Faol')
                                ->default(true)
                                ->inline(),
                            DateTimePicker::make('expires_at')
                                ->label('Amal qilish muddati'),
                        ]),
                    ]),

                Section::make('Texnik ma\'lumotlar')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('code')
                                ->label('Kod')
                                ->default(strtoupper(str()->random(10)))
                                ->required()
                                ->maxLength(64),
                            TextInput::make('token')
                                ->label('Token')
                                ->default(str()->random(40))
                                ->required()
                                ->maxLength(64),
                        ]),
                        Grid::make(2)->schema([
                            Select::make('target_type')
                                ->label('Maqsad turi')
                                ->options([
                                    'doctor' => 'Shifokor',
                                    'department' => 'Bo\'lim',
                                    'branch' => 'Filial',
                                    'room' => 'Xona',
                                    'generic' => 'Umumiy',
                                ])
                                ->default('doctor')
                                ->required(),
                            Select::make('service_point_id')
                                ->label('Xizmat nuqtasi')
                                ->relationship('servicePoint', 'name')
                                ->searchable()
                                ->preload(),
                        ]),
                    ]),
            ]);
    }
}
