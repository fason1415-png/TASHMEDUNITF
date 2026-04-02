<?php

namespace App\Console\Commands;

use App\Services\PatronageSlaService;
use Illuminate\Console\Command;

class CheckPatronageSla extends Command
{
    protected $signature = 'patronage:check-sla';

    protected $description = 'Check SLA compliance for all active patronage tasks';

    public function handle(PatronageSlaService $slaService): int
    {
        $results = $slaService->checkAll();

        $this->info("SLA check completed:");
        $this->line("  Reminders sent: {$results['reminders_sent']}");
        $this->line("  Escalated: {$results['escalated']}");
        $this->line("  Breached: {$results['breached']}");

        return Command::SUCCESS;
    }
}
