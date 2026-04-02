<?php

namespace App\Observers;

use App\Jobs\CreatePatronageTasksJob;
use App\Models\Discharge;

class DischargeObserver
{
    public function created(Discharge $discharge): void
    {
        if ($discharge->requires_patronage) {
            CreatePatronageTasksJob::dispatch($discharge);
        }
    }
}
