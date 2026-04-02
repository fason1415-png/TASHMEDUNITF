<?php

namespace App\Filament\Resources\PatronageTasks\Pages;

use App\Filament\Resources\PatronageTasks\PatronageTaskResource;
use Filament\Resources\Pages\ListRecords;

class ListPatronageTasks extends ListRecords
{
    protected static string $resource = PatronageTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
