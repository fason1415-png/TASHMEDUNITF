<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('clinic_id')
                    ->relationship('clinic', 'name')
                    ->required(),
                Select::make('subscription_id')
                    ->relationship('subscription', 'id'),
                TextInput::make('invoice_number')
                    ->required(),
                DatePicker::make('period_start'),
                DatePicker::make('period_end'),
                TextInput::make('amount_due')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('amount_paid')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('currency')
                    ->required()
                    ->default('UZS'),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                DatePicker::make('due_date'),
                DateTimePicker::make('paid_at'),
                TextInput::make('payment_reference'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                Textarea::make('meta')
                    ->columnSpanFull(),
            ]);
    }
}

