<?php

namespace App\Filament\Widgets;

use App\Models\Escalation;
use App\Models\QrScanEvent;
use App\Models\SurveyResponse;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class ExecutiveHeroWidget extends Widget
{
    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.executive-hero-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -5;

    protected function getViewData(): array
    {
        $clinicId = auth()->user()?->isSuperAdmin() ? null : auth()->user()?->clinic_id;
        $doctorId = auth()->user()?->hasRole('doctor') ? auth()->user()?->doctor_id : null;
        $monthStart = now()->startOfMonth();
        $windowStart = now()->subDays(30)->startOfDay();
        $windowEnd = now()->endOfDay();
        $previousWindowStart = now()->subDays(60)->startOfDay();
        $previousWindowEnd = (clone $windowStart)->subSecond();

        $responseQuery = SurveyResponse::query()
            ->when($clinicId, fn ($query) => $query->where('clinic_id', $clinicId))
            ->when($doctorId, fn ($query) => $query->where('doctor_id', $doctorId));

        $scanQuery = QrScanEvent::query()
            ->when($clinicId, fn ($query) => $query->where('clinic_id', $clinicId))
            ->when($doctorId, fn ($query) => $query->whereHas('qrCode', fn ($query) => $query->where('doctor_id', $doctorId)));

        $monthResponses = (clone $responseQuery)
            ->where('submitted_at', '>=', $monthStart)
            ->count();

        $avgConfidence = round((float) (clone $responseQuery)
            ->where('submitted_at', '>=', $windowStart)
            ->avg('confidence_score'), 1);

        $criticalCount = Escalation::query()
            ->when($clinicId, fn ($query) => $query->where('clinic_id', $clinicId))
            ->when($doctorId, fn ($query) => $query->where('doctor_id', $doctorId))
            ->whereIn('severity', ['high', 'critical'])
            ->whereIn('status', ['open', 'in_progress'])
            ->count();

        $scanCount = (clone $scanQuery)->where('scanned_at', '>=', $windowStart)->count();
        $convertedCount = (clone $scanQuery)
            ->where('scanned_at', '>=', $windowStart)
            ->whereNotNull('converted_to_response_id')
            ->count();

        $conversionRate = $scanCount > 0 ? round(($convertedCount / $scanCount) * 100, 1) : 0;

        $topDepartments = (clone $responseQuery)
            ->selectRaw('department_id, COUNT(*) as total, AVG(confidence_score) as avg_score')
            ->whereNotNull('department_id')
            ->where('submitted_at', '>=', $windowStart)
            ->with('department:id,name')
            ->groupBy('department_id')
            ->orderByDesc('total')
            ->limit(4)
            ->get();

        $monthBars = [];

        foreach (range(5, 0) as $offset) {
            $from = now()->subMonths($offset)->startOfMonth();
            $to = now()->subMonths($offset)->endOfMonth();
            $count = (clone $responseQuery)
                ->whereBetween('submitted_at', [$from, $to])
                ->count();

            $monthBars[] = [
                'label' => $from->format('M'),
                'count' => $count,
            ];
        }

        $maxBar = collect($monthBars)->max('count') ?: 1;
        $doctorMetrics = (clone $responseQuery)
            ->selectRaw('doctor_id')
            ->selectRaw('COUNT(*) as responses')
            ->selectRaw('COALESCE(AVG(quality_score), 0) as avg_quality')
            ->selectRaw('COALESCE(AVG(confidence_score), 0) as avg_confidence')
            ->selectRaw('COALESCE(AVG(sentiment_score), 0) as avg_sentiment')
            ->selectRaw('SUM(CASE WHEN is_flagged = 1 THEN 1 ELSE 0 END) as flagged_count')
            ->whereNotNull('doctor_id')
            ->whereBetween('submitted_at', [$windowStart, $windowEnd])
            ->with('doctor:id,full_name,specialty')
            ->groupBy('doctor_id')
            ->get();

        $previousDoctorQuality = (clone $responseQuery)
            ->selectRaw('doctor_id')
            ->selectRaw('COALESCE(AVG(quality_score), 0) as previous_quality')
            ->whereNotNull('doctor_id')
            ->whereBetween('submitted_at', [$previousWindowStart, $previousWindowEnd])
            ->groupBy('doctor_id')
            ->pluck('previous_quality', 'doctor_id');

        $openAlertsByDoctor = Escalation::query()
            ->selectRaw('doctor_id, COUNT(*) as total')
            ->when($clinicId, fn ($query) => $query->where('clinic_id', $clinicId))
            ->when($doctorId, fn ($query) => $query->where('doctor_id', $doctorId))
            ->whereNotNull('doctor_id')
            ->whereIn('severity', ['high', 'critical'])
            ->whereIn('status', ['open', 'in_progress'])
            ->groupBy('doctor_id')
            ->pluck('total', 'doctor_id');

        $doctorReports = $this->buildDoctorReports(
            $doctorMetrics,
            $previousDoctorQuality,
            $openAlertsByDoctor,
        );

        $topDoctorReports = $doctorReports
            ->sortByDesc('performance_score')
            ->values()
            ->take(6)
            ->values();

        $riskDoctorReports = $doctorReports
            ->sortByDesc('risk_score')
            ->values()
            ->take(6)
            ->values();

        $growthDoctorReports = $doctorReports
            ->sortByDesc('trend_delta')
            ->values()
            ->take(6)
            ->values();

        $doctorPanelSummary = [
            'analyzed_count' => $doctorReports->count(),
            'avg_performance' => round((float) $doctorReports->avg('performance_score'), 1),
            'risk_count' => $doctorReports
                ->filter(fn (array $report): bool => $report['open_alerts'] > 0 || $report['flagged_rate'] >= 20)
                ->count(),
            'from' => $windowStart->format('d.m.Y'),
            'to' => now()->format('d.m.Y'),
        ];

        $chartQuality = $doctorReports
            ->sortByDesc('quality')
            ->take(8)
            ->map(fn (array $r) => [
                'name' => str($r['doctor_name'])->limit(12)->toString(),
                'value' => $r['quality'],
            ])
            ->values();

        $chartConfidence = $doctorReports
            ->sortByDesc('confidence')
            ->take(8)
            ->map(fn (array $r) => [
                'name' => str($r['doctor_name'])->limit(12)->toString(),
                'value' => $r['confidence'],
            ])
            ->values();

        $chartTrend = $doctorReports
            ->sortByDesc(fn (array $r) => abs($r['trend_delta']))
            ->take(8)
            ->map(fn (array $r) => [
                'name' => str($r['doctor_name'])->limit(12)->toString(),
                'value' => $r['trend_delta'],
            ])
            ->values();

        $chartRisk = $doctorReports
            ->sortByDesc('risk_score')
            ->take(8)
            ->map(fn (array $r) => [
                'name' => str($r['doctor_name'])->limit(12)->toString(),
                'value' => $r['risk_score'],
                'alerts' => $r['open_alerts'],
            ])
            ->values();

        // TOP 15 shifokorlar - eng ko'p javob olganlar
        $top15Doctors = (clone $responseQuery)
            ->selectRaw('doctor_id, COUNT(*) as total_responses, COALESCE(AVG(quality_score), 0) as avg_quality, COALESCE(AVG(confidence_score), 0) as avg_confidence')
            ->whereNotNull('doctor_id')
            ->where('submitted_at', '>=', $windowStart)
            ->with([
                'doctor:id,full_name,specialty,clinic_id,department_id,branch_id,experience_years,status,telegram_chat_id,phone,bio',
                'doctor.clinic:id,name,phone',
                'doctor.department:id,name',
                'doctor.branch:id,name',
            ])
            ->groupBy('doctor_id')
            ->orderByDesc('total_responses')
            ->limit(15)
            ->get()
            ->map(fn (SurveyResponse $r) => [
                'full_name' => $r->doctor?->full_name ?? '—',
                'specialty' => $r->doctor?->specialty ?? '—',
                'clinic' => $r->doctor?->clinic?->name ?? '—',
                'department' => $r->doctor?->department?->name ?? '—',
                'branch' => $r->doctor?->branch?->name ?? '—',
                'experience' => $r->doctor?->experience_years ?? '—',
                'status' => $r->doctor?->status ?? '—',
                'phone' => $r->doctor?->phone ?? '—',
                'telegram' => $r->doctor?->telegram_chat_id ?? '—',
                'clinic_phone' => $r->doctor?->clinic?->phone ?? '—',
                'bio' => $r->doctor?->bio ?? '',
                'responses' => (int) $r->total_responses,
                'quality' => round((float) $r->avg_quality, 1),
                'confidence' => round((float) $r->avg_confidence, 1),
            ]);

        return [
            'monthResponses' => $monthResponses,
            'avgConfidence' => $avgConfidence,
            'criticalCount' => $criticalCount,
            'scanCount' => $scanCount,
            'conversionRate' => $conversionRate,
            'topDepartments' => $topDepartments,
            'monthBars' => $monthBars,
            'maxBar' => $maxBar,
            'doctorPanelSummary' => $doctorPanelSummary,
            'topDoctorReports' => $topDoctorReports,
            'riskDoctorReports' => $riskDoctorReports,
            'growthDoctorReports' => $growthDoctorReports,
            'chartQuality' => $chartQuality,
            'chartConfidence' => $chartConfidence,
            'chartTrend' => $chartTrend,
            'chartRisk' => $chartRisk,
            'top15Doctors' => $top15Doctors,
        ];
    }

    private function buildDoctorReports(
        Collection $doctorMetrics,
        Collection $previousDoctorQuality,
        Collection $openAlertsByDoctor,
    ): Collection {
        return $doctorMetrics
            ->map(function (SurveyResponse $metric) use ($previousDoctorQuality, $openAlertsByDoctor): array {
                $responses = (int) $metric->responses;
                $quality = round((float) $metric->avg_quality, 2);
                $confidence = round((float) $metric->avg_confidence, 2);
                $sentiment = round((float) $metric->avg_sentiment, 2);
                $flaggedCount = (int) $metric->flagged_count;
                $flaggedRate = $responses > 0 ? round(($flaggedCount / $responses) * 100, 1) : 0.0;
                $openAlerts = (int) ($openAlertsByDoctor->get($metric->doctor_id) ?? 0);
                $previousQuality = round((float) ($previousDoctorQuality->get($metric->doctor_id) ?? 0.0), 2);
                $trendDelta = round($quality - $previousQuality, 2);
                $performanceScore = round(($quality * 0.65) + ($confidence * 0.35), 2);
                $riskScore = round(($openAlerts * 12) + ($flaggedRate * 0.7) + max(0.0, 50 - $confidence), 2);

                return [
                    'doctor_name' => $metric->doctor?->full_name ?? __('ui.executive.unknown_doctor'),
                    'specialty' => $metric->doctor?->specialty ?? '-',
                    'responses' => $responses,
                    'quality' => $quality,
                    'confidence' => $confidence,
                    'sentiment' => $sentiment,
                    'flagged_count' => $flaggedCount,
                    'flagged_rate' => $flaggedRate,
                    'open_alerts' => $openAlerts,
                    'trend_delta' => $trendDelta,
                    'performance_score' => $performanceScore,
                    'risk_score' => $riskScore,
                ];
            })
            ->filter(fn (array $report): bool => $report['responses'] > 0);
    }
}

