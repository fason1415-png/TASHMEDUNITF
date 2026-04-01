<?php

namespace App\Filament\Widgets;

use App\Models\Escalation;
use App\Models\QrScanEvent;
use App\Models\SurveyResponse;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RealtimeOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -6;

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = '20s';

    protected function getStats(): array
    {
        $clinicId = auth()->user()?->isSuperAdmin() ? null : auth()->user()?->clinic_id;
        $doctorId = auth()->user()?->hasRole('doctor') ? auth()->user()?->doctor_id : null;
        $today = now()->startOfDay();
        $weekAgo = now()->subDays(7);

        $responseQuery = SurveyResponse::query()
            ->when($clinicId, fn ($query) => $query->where('clinic_id', $clinicId))
            ->when($doctorId, fn ($query) => $query->where('doctor_id', $doctorId));

        $scanQuery = QrScanEvent::query()
            ->when($clinicId, fn ($query) => $query->where('clinic_id', $clinicId))
            ->when($doctorId, fn ($query) => $query->whereHas('qrCode', fn ($query) => $query->where('doctor_id', $doctorId)));

        $feedbackToday = (clone $responseQuery)->where('submitted_at', '>=', $today)->count();
        $avgScoreWeek = (float) (clone $responseQuery)->where('submitted_at', '>=', $weekAgo)->avg('confidence_score');
        $flaggedOpen = (clone $responseQuery)->where('is_flagged', true)->count();
        $criticalOpen = Escalation::query()
            ->when($clinicId, fn ($query) => $query->where('clinic_id', $clinicId))
            ->when($doctorId, fn ($query) => $query->where('doctor_id', $doctorId))
            ->whereIn('severity', ['high', 'critical'])
            ->whereIn('status', ['open', 'in_progress'])
            ->count();

        $scanCount = (clone $scanQuery)->where('scanned_at', '>=', $weekAgo)->count();
        $conversionCount = (clone $scanQuery)
            ->where('scanned_at', '>=', $weekAgo)
            ->whereNotNull('converted_to_response_id')
            ->count();
        $conversion = $scanCount > 0 ? round(($conversionCount / $scanCount) * 100, 1) : 0;

        return [
            Stat::make(__('ui.realtime.feedback_today'), (string) $feedbackToday)
                ->description(__('ui.realtime.feedback_today_desc'))
                ->color('success'),
            Stat::make(__('ui.realtime.avg_score_7d'), number_format($avgScoreWeek, 2))
                ->description(__('ui.realtime.avg_score_7d_desc'))
                ->color('info'),
            Stat::make(__('ui.realtime.flagged'), (string) $flaggedOpen)
                ->description(__('ui.realtime.flagged_desc'))
                ->color($flaggedOpen > 0 ? 'warning' : 'success'),
            Stat::make(__('ui.realtime.scan_conversion'), $conversion.'%')
                ->description(__('ui.realtime.scan_conversion_desc', [
                    'converted' => $conversionCount,
                    'scans' => $scanCount,
                ]))
                ->color('primary'),
            Stat::make(__('ui.realtime.critical'), (string) $criticalOpen)
                ->description(__('ui.realtime.critical_desc'))
                ->color($criticalOpen > 0 ? 'danger' : 'success'),
        ];
    }
}

