<?php

namespace App\Exports;

use App\Models\Escalation;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ComplaintCategoryExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected int $clinicId,
        protected CarbonImmutable $from,
        protected CarbonImmutable $to,
    ) {
    }

    public function collection(): Collection
    {
        return Escalation::query()
            ->selectRaw('category, severity, status, COUNT(*) as total')
            ->where('clinic_id', $this->clinicId)
            ->whereBetween('opened_at', [$this->from->startOfDay(), $this->to->endOfDay()])
            ->groupBy('category', 'severity', 'status')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($item) => [
                'category' => $item->category ?: 'uncategorized',
                'severity' => $item->severity,
                'status' => $item->status,
                'total' => (int) $item->total,
            ]);
    }

    public function headings(): array
    {
        return [
            'Category',
            'Severity',
            'Status',
            'Count',
        ];
    }
}

