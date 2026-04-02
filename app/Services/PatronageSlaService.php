<?php

namespace App\Services;

use App\Models\PatronageEscalationRule;
use App\Models\PatronageTask;

class PatronageSlaService
{
    public function __construct(
        private PatronageNotificationService $notificationService,
    ) {
    }

    /**
     * Check all pending tasks for SLA compliance.
     * Called every 5 minutes by scheduled command.
     *
     * @return array{reminders_sent: int, escalated: int, breached: int}
     */
    public function checkAll(): array
    {
        $stats = ['reminders_sent' => 0, 'escalated' => 0, 'breached' => 0];

        $tasks = PatronageTask::query()
            ->whereIn('status', ['pending', 'notified', 'accepted', 'in_progress'])
            ->whereNotNull('due_at')
            ->get();

        foreach ($tasks as $task) {
            $this->checkTask($task, $stats);
        }

        return $stats;
    }

    /**
     * Check a single task for SLA compliance.
     *
     * @param array{reminders_sent: int, escalated: int, breached: int} $stats
     */
    public function checkTask(PatronageTask $task, array &$stats): void
    {
        $minutesUntilDue = (int) now()->diffInMinutes($task->due_at, false);

        // 6 hours (360 min) remaining -> normal reminder
        if ($minutesUntilDue <= 360 && $minutesUntilDue > 120 && ! $task->sla_breached) {
            $this->notificationService->sendReminder($task, 'normal');
            $stats['reminders_sent']++;
        }

        // 2 hours (120 min) remaining -> urgent reminder
        if ($minutesUntilDue <= 120 && $minutesUntilDue > 0 && ! $task->sla_breached) {
            $this->notificationService->sendReminder($task, 'urgent');
            $stats['reminders_sent']++;
        }

        // Overdue - SLA breached
        if ($minutesUntilDue <= 0 && ! $task->sla_breached) {
            $task->update([
                'sla_breached' => true,
                'sla_breach_minutes' => abs($minutesUntilDue),
            ]);
            $stats['breached']++;
        }

        // Check escalation levels
        if ($task->sla_breached) {
            $this->checkEscalation($task, $stats);
        }
    }

    /**
     * Handle escalation based on rules.
     *
     * @param array{reminders_sent: int, escalated: int, breached: int} $stats
     */
    private function checkEscalation(PatronageTask $task, array &$stats): void
    {
        $overdueMins = abs((int) now()->diffInMinutes($task->due_at, false));

        $rules = PatronageEscalationRule::query()
            ->where(fn ($q) => $q->where('clinic_id', $task->clinic_id)->orWhereNull('clinic_id'))
            ->where('is_active', true)
            ->where('escalation_level', '>', $task->escalation_level)
            ->orderBy('escalation_level')
            ->get();

        foreach ($rules as $rule) {
            if ($overdueMins >= $rule->trigger_after_minutes && $task->escalation_level < $rule->escalation_level) {
                $task->update([
                    'escalation_level' => $rule->escalation_level,
                    'status' => 'escalated',
                    'sla_breach_minutes' => $overdueMins,
                ]);

                $this->notificationService->notifyEscalation($task, $rule->escalation_level);
                $stats['escalated']++;
            }
        }
    }
}
