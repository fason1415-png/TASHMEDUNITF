<?php

namespace App\Filament\ClinicPanel\Resources\PatronageTaskResource\Pages;

use App\Filament\ClinicPanel\Resources\PatronageTaskResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPatronageTasks extends ListRecords
{
    protected static string $resource = PatronageTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
