<?php

namespace App\Filament\Resources\SuspiciousFlags\Pages;

use App\Filament\Resources\SuspiciousFlags\SuspiciousFlagResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSuspiciousFlag extends CreateRecord
{
    protected static string $resource = SuspiciousFlagResource::class;
}
