<?php

namespace App\Exports;

use App\Models\RatingSnapshot;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DoctorMonthlyPerformanceExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected int $clinicId,
        protected CarbonImmutable $month,
    ) {
    }

    public function collection(): Collection
    {
        return RatingSnapshot::query()
            ->with(['doctor', 'department', 'branch'])
            ->where('clinic_id', $this->clinicId)
            ->where('period_type', 'monthly')
            ->whereBetween('period_start', [
                $this->month->startOfMonth()->toDateString(),
                $this->month->endOfMonth()->toDateString(),
            ])
            ->orderByDesc('confidence_adjusted_score')
            ->get()
            ->map(fn (RatingSnapshot $snapshot) => [
                'doctor' => $snapshot->doctor?->full_name,
                'specialty' => $snapshot->doctor?->specialty,
                'branch' => $snapshot->branch?->name,
                'department' => $snapshot->department?->name,
                'feedback_count' => $snapshot->feedback_count,
                'quality_score' => $snapshot->quality_score,
                'confidence_adjusted_score' => $snapshot->confidence_adjusted_score,
                'sentiment_score' => $snapshot->sentiment_score,
                'flagged_count' => $snapshot->flagged_count,
            ]);
    }

    public function headings(): array
    {
        return [
            'Doctor',
            'Specialty',
            'Branch',
            'Department',
            'Feedback Count',
            'Quality Score',
            'Confidence Adjusted Score',
            'Sentiment Score',
            'Flagged Count',
        ];
    }
}

