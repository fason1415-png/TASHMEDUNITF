<?php

namespace App\Filament\ClinicPanel\Resources;

use App\Filament\ClinicPanel\Resources\DoctorResource\Pages;
use App\Models\Doctor;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Structure';

    protected static int | null $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('full_name')->required()->maxLength(255),
            TextInput::make('specialty')->maxLength(255),
            Select::make('department_id')
                ->relationship('department', 'name')
                ->searchable()
                ->preload(),
            Select::make('branch_id')
                ->relationship('branch', 'name')
                ->searchable()
                ->preload(),
            Select::make('status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'on_leave' => 'On Leave',
                ]),
            Checkbox::make('is_active')->default(true),
            TextInput::make('experience_years')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->searchable()->sortable(),
                TextColumn::make('specialty')->searchable(),
                TextColumn::make('department.name')->sortable(),
                TextColumn::make('status')->sortable(),
            ])
            ->defaultSort('full_name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),
        ];
    }
}
