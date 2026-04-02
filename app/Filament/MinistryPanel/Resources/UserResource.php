<?php

namespace App\Filament\MinistryPanel\Resources;

use App\Filament\MinistryPanel\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \UnitEnum | null $navigationGroup = 'System';

    protected static int | null $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('email')->email()->required()->maxLength(255),
            TextInput::make('password')->password()->dehydrated(fn ($state) => filled($state))->required(fn (string $context): bool => $context === 'create'),
            Select::make('clinic_id')
                ->relationship('clinic', 'name')
                ->searchable()
                ->preload(),
            Select::make('roles')
                ->relationship('roles', 'name')
                ->multiple()
                ->preload(),
            Checkbox::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('roles.name')->badge(),
                TextColumn::make('clinic.name')->sortable(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
