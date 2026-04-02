<?php

namespace App\Filament\ClinicPanel\Resources;

use App\Filament\ClinicPanel\Resources\EscalationResource\Pages;
use App\Models\Escalation;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EscalationResource extends Resource
{
    protected static ?string $model = Escalation::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Feedback';

    protected static int | null $navigationSort = 60;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->required()->maxLength(255),
            Select::make('severity')
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                    'critical' => 'Critical',
                ])
                ->required(),
            Select::make('status')
                ->options([
                    'open' => 'Open',
                    'in_progress' => 'In Progress',
                    'resolved' => 'Resolved',
                    'closed' => 'Closed',
                ])
                ->required(),
            Textarea::make('description')->maxLength(1000),
            Textarea::make('resolution_notes')->maxLength(1000),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('severity')->sortable(),
                TextColumn::make('status')->sortable(),
                TextColumn::make('doctor.full_name')->searchable(),
            ])
            ->defaultSort('opened_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEscalations::route('/'),
            'create' => Pages\CreateEscalation::route('/create'),
            'edit' => Pages\EditEscalation::route('/{record}/edit'),
        ];
    }
}
