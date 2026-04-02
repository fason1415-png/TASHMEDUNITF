<?php

namespace App\Jobs;

use App\Models\Discharge;
use App\Services\PatronageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreatePatronageTasksJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Discharge $discharge,
    ) {
    }

    public function handle(PatronageService $patronageService): void
    {
        $tasks = $patronageService->createTasksFromDischarge($this->discharge);

        foreach ($tasks as $task) {
            SendPatronageNotificationJob::dispatch($task, 'new_task');

            $task->update([
                'status' => 'notified',
                'notified_at' => now(),
            ]);
        }
    }
}
