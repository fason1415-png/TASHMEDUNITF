<?php

namespace App\Filament\ClinicPanel\Resources\PatientResource\Pages;

use App\Filament\ClinicPanel\Resources\PatientResource;
use Filament\Resources\Pages\ListRecords;

class ListPatients extends ListRecords
{
    protected static string $resource = PatientResource::class;
}
