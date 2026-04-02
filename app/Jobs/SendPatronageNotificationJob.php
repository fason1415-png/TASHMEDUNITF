<?php

namespace App\Jobs;

use App\Models\PatronageTask;
use App\Services\PatronageNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendPatronageNotificationJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /** @var array<int, int> */
    public array $backoff = [30, 60, 120];

    public function __construct(
        public PatronageTask $task,
        public string $type = 'new_task',
    ) {
    }

    public function handle(PatronageNotificationService $notificationService): void
    {
        match ($this->type) {
            'new_task' => $notificationService->notifyDoctorOfNewTask($this->task),
            'reminder' => $notificationService->sendReminder($this->task),
            'escalation' => $notificationService->notifyEscalation($this->task, $this->task->escalation_level),
        };
    }
}
