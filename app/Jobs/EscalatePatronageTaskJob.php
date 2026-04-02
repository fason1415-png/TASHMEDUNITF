<?php

namespace App\Jobs;

use App\Models\PatronageTask;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class EscalatePatronageTaskJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public PatronageTask $task,
    ) {
    }

    public function handle(): void
    {
        if (in_array($this->task->status, ['completed', 'missed'])) {
            return;
        }

        $this->task->update([
            'status' => 'escalated',
        ]);

        SendPatronageNotificationJob::dispatch($this->task, 'escalation');
    }
}
