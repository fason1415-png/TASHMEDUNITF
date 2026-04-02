<?php

namespace App\Jobs;

use App\Services\PatronageSlaService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CheckPatronageSlaJob implements ShouldQueue
{
    use Queueable;

    public function handle(PatronageSlaService $slaService): void
    {
        $results = $slaService->checkAll();

        Log::info('Patronage SLA check completed.', $results);
    }
}
