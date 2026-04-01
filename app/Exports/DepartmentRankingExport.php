<?php

namespace App\Exports;

use App\Models\SurveyResponse;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DepartmentRankingExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected int $clinicId,
        protected CarbonImmutable $from,
        protected CarbonImmutable $to,
    ) {
    }

    public function collection(): Collection
    {
        return SurveyResponse::query()
            ->selectRaw('department_id, COUNT(*) as feedback_count, AVG(quality_score) as quality_score, AVG(confidence_score) as confidence_score')
            ->with('department')
            ->where('clinic_id', $this->clinicId)
            ->whereBetween('submitted_at', [$this->from->startOfDay(), $this->to->endOfDay()])
            ->where('moderation_status', 'approved')
            ->groupBy('department_id')
            ->orderByDesc('confidence_score')
            ->get()
            ->map(fn ($item) => [
                'department' => $item->department?->name ?? 'N/A',
                'feedback_count' => (int) $item->feedback_count,
                'quality_score' => round((float) $item->quality_score, 2),
                'confidence_score' => round((float) $item->confidence_score, 2),
            ]);
    }

    public function headings(): array
    {
        return [
            'Department',
            'Feedback Count',
            'Quality Score',
            'Confidence Score',
        ];
    }
}

