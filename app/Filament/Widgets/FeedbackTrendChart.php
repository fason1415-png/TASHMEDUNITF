<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;

class FeedbackTrendChart extends ChartWidget
{
    protected ?string $pollingInterval = '30s';

    protected ?string $maxHeight = '320px';

    public function getHeading(): ?string
    {
        return __('ui.trend.heading');
    }

    public function getDescription(): ?string
    {
        return __('ui.trend.description');
    }

    protected function getData(): array
    {
        $clinicId = auth()->user()?->isSuperAdmin() ? null : auth()->user()?->clinic_id;
        $doctorId = auth()->user()?->hasRole('doctor') ? auth()->user()?->doctor_id : null;
        $start = now()->subDays(29)->startOfDay();
        $end = now()->endOfDay();

        $responses = SurveyResponse::query()
            ->selectRaw('DATE(submitted_at) as day, COUNT(*) as total, AVG(confidence_score) as avg_score')
            ->when($clinicId, fn ($query) => $query->where('clinic_id', $clinicId))
            ->when($doctorId, fn ($query) => $query->where('doctor_id', $doctorId))
            ->whereBetween('submitted_at', [$start, $end])
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $labels = [];
        $totals = [];
        $scores = [];

        foreach (CarbonPeriod::create($start, $end) as $day) {
            $key = $day->format('Y-m-d');
            $labels[] = $day->format('d M');
            $totals[] = (int) ($responses[$key]->total ?? 0);
            $scores[] = round((float) ($responses[$key]->avg_score ?? 0), 2);
        }

        return [
            'datasets' => [
                [
                    'label' => __('ui.trend.responses'),
                    'data' => $totals,
                    'borderColor' => '#2f6ef8',
                    'backgroundColor' => 'rgba(47, 110, 248, 0.14)',
                    'tension' => 0.35,
                    'fill' => true,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => __('ui.trend.avg_confidence'),
                    'data' => $scores,
                    'borderColor' => '#17a672',
                    'backgroundColor' => 'rgba(23, 166, 114, 0.10)',
                    'tension' => 0.25,
                    'fill' => false,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(148, 163, 184, 0.18)',
                    ],
                ],
                'y1' => [
                    'position' => 'right',
                    'min' => 0,
                    'max' => 100,
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}

