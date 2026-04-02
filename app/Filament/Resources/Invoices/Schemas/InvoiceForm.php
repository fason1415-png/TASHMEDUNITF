<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hisob-faktura')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('clinic_id')
                                ->label('Klinika')
                                ->relationship('clinic', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),
                            TextInput::make('invoice_number')
                                ->label('Hisob raqami')
                                ->required()
                                ->disabled(),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('amount_due')
                                ->label('To\'lov summasi')
                                ->numeric()
                                ->suffix('so\'m')
                                ->required(),
                            TextInput::make('amount_paid')
                                ->label('To\'langan')
                                ->numeric()
                                ->suffix('so\'m')
                                ->required(),
                            Select::make('status')
                                ->label('Holati')
                                ->options([
                                    'draft' => 'Qoralama',
                                    'issued' => 'Chiqarilgan',
                                    'paid' => 'To\'langan',
                                    'overdue' => 'Muddati o\'tgan',
                                    'cancelled' => 'Bekor qilingan',
                                ])
                                ->default('draft')
                                ->required()
                                ->native(false),
                        ]),
                        Grid::make(3)->schema([
                            DatePicker::make('period_start')
                                ->label('Davr boshlanishi'),
                            DatePicker::make('period_end')
                                ->label('Davr tugashi'),
                            DatePicker::make('due_date')
                                ->label('To\'lov muddati'),
                        ]),
                        Textarea::make('notes')
                            ->label('Izoh')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
