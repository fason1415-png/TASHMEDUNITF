<?php

namespace App\Events;

use App\Models\Discharge;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PatientDischarged
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Discharge $discharge,
    ) {
    }
}
