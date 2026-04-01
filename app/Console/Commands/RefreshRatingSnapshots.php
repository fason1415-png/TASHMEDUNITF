<?php

namespace App\Console\Commands;

use App\Models\SurveyResponse;
use App\Services\RatingSnapshotService;
use Illuminate\Console\Command;

class RefreshRatingSnapshots extends Command
{
    protected $signature = 'ratings:refresh {--days=1 : Number of days to refresh}';

    protected $description = 'Refresh rating snapshots for recent responses';

    public function handle(RatingSnapshotService $ratingSnapshotService): int
    {
        $days = max(1, (int) $this->option('days'));
        $since = now()->subDays($days);

        $responses = SurveyResponse::query()
            ->with('clinic')
            ->where('submitted_at', '>=', $since)
            ->orderBy('submitted_at')
            ->get();

        foreach ($responses as $response) {
            $ratingSnapshotService->refreshFromResponse($response);
        }

        $this->info("Refreshed rating snapshots for {$responses->count()} responses.");

        return self::SUCCESS;
    }
}

