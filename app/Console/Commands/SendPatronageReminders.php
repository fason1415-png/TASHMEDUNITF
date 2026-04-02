<?php

namespace App\Console\Commands;

use App\Jobs\SendPatronageNotificationJob;
use App\Models\PatronageTask;
use Illuminate\Console\Command;

class SendPatronageReminders extends Command
{
    protected $signature = 'patronage:send-reminders';

    protected $description = 'Send reminders for upcoming patronage task deadlines';

    public function handle(): int
    {
        $tasks = PatronageTask::query()
            ->where('due_at', '<=', now()->addHours(6))
            ->where('due_at', '>', now())
            ->whereIn('status', ['pending', 'notified', 'accepted'])
            ->get();

        foreach ($tasks as $task) {
            SendPatronageNotificationJob::dispatch($task, 'reminder');
        }

        $this->info("Dispatched reminders for {$tasks->count()} task(s).");

        return Command::SUCCESS;
    }
}
