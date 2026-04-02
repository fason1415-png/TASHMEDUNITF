<?php

namespace App\Filament\Resources\PatronageEscalationRules\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PatronageEscalationRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('clinic_id')
                    ->relationship('clinic', 'name')
                    ->nullable()
                    ->placeholder('Global qoida'),
                Select::make('escalation_level')
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                    ])
                    ->required(),
                TextInput::make('trigger_after_minutes')
                    ->numeric()
                    ->required()
                    ->hint('daqiqalarda, masalan: 1440 = 24 soat'),
                Select::make('notify_role')
                    ->options([
                        'supervisor' => 'Supervisor',
                        'chief_doctor' => 'Chief Doctor',
                        'ministry_rep' => 'Ministry Rep',
                    ])
                    ->required(),
                CheckboxList::make('notification_channels')
                    ->options([
                        'sms' => 'SMS',
                        'telegram' => 'Telegram',
                        'push' => 'Push',
                        'email' => 'Email',
                    ]),
                Toggle::make('auto_reassign'),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
