<?php

namespace App\Filament\ClinicPanel\Resources;

use App\Filament\ClinicPanel\Resources\PatronageTaskResource\Pages;
use App\Models\PatronageTask;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PatronageTaskResource extends Resource
{
    protected static ?string $model = PatronageTask::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Patronage';

    protected static int | null $navigationSort = 90;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('patient_id')
                ->relationship('patient', 'full_name')
                ->searchable()
                ->preload()
                ->required(),
            Select::make('family_doctor_id')
                ->relationship('familyDoctor', 'full_name')
                ->searchable()
                ->preload(),
            TextInput::make('task_type')->maxLength(50),
            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'notified' => 'Notified',
                    'accepted' => 'Accepted',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'missed' => 'Missed',
                ]),
            Select::make('priority')
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                    'urgent' => 'Urgent',
                ]),
            DateTimePicker::make('due_at'),
            Textarea::make('visit_notes')->maxLength(1000),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.full_name')->searchable()->sortable(),
                TextColumn::make('familyDoctor.full_name')->label('Family Doctor')->searchable(),
                TextColumn::make('status')->sortable(),
                TextColumn::make('priority')->sortable(),
                TextColumn::make('due_at')->dateTime()->sortable(),
                IconColumn::make('sla_breached')->boolean(),
            ])
            ->defaultSort('due_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatronageTasks::route('/'),
            'create' => Pages\CreatePatronageTask::route('/create'),
            'edit' => Pages\EditPatronageTask::route('/{record}/edit'),
        ];
    }
}
