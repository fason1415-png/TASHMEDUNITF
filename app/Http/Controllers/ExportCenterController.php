<?php

namespace App\Http\Controllers;

use App\Exports\ComplaintCategoryExport;
use App\Exports\DepartmentRankingExport;
use App\Exports\DoctorMonthlyPerformanceExport;
use App\Models\Clinic;
use App\Models\Escalation;
use App\Models\SurveyResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ExportCenterController extends Controller
{
    public function doctorMonthly(Request $request): BinaryFileResponse
    {
        $clinicId = $this->resolveClinicId($request);
        $month = CarbonImmutable::parse($request->string('month')->toString() ?: now()->format('Y-m-01'));

        return Excel::download(
            new DoctorMonthlyPerformanceExport($clinicId, $month),
            'doctor-monthly-performance-'.$month->format('Y-m').'.xlsx'
        );
    }

    public function departmentRanking(Request $request): BinaryFileResponse
    {
        $clinicId = $this->resolveClinicId($request);
        [$from, $to] = $this->resolveDateRange($request);

        return Excel::download(
            new DepartmentRankingExport($clinicId, $from, $to),
            'department-ranking-'.$from->format('Ymd').'-'.$to->format('Ymd').'.xlsx'
        );
    }

    public function complaintCategories(Request $request): BinaryFileResponse
    {
        $clinicId = $this->resolveClinicId($request);
        [$from, $to] = $this->resolveDateRange($request);

        return Excel::download(
            new ComplaintCategoryExport($clinicId, $from, $to),
            'complaint-categories-'.$from->format('Ymd').'-'.$to->format('Ymd').'.xlsx'
        );
    }

    public function clinicSummaryPdf(Request $request): Response
    {
        $clinicId = $this->resolveClinicId($request);
        [$from, $to] = $this->resolveDateRange($request);

        $clinic = Clinic::query()->findOrFail($clinicId);
        $feedbackQuery = SurveyResponse::query()
            ->where('clinic_id', $clinicId)
            ->whereBetween('submitted_at', [$from->startOfDay(), $to->endOfDay()]);

        $metrics = [
            'feedback_count' => (clone $feedbackQuery)->count(),
            'approved_count' => (clone $feedbackQuery)->where('moderation_status', 'approved')->count(),
            'avg_quality_score' => round((float) (clone $feedbackQuery)->avg('quality_score'), 2),
            'avg_confidence_score' => round((float) (clone $feedbackQuery)->avg('confidence_score'), 2),
            'flagged_count' => (clone $feedbackQuery)->where('is_flagged', true)->count(),
            'critical_escalations' => Escalation::query()
                ->where('clinic_id', $clinicId)
                ->whereIn('severity', ['high', 'critical'])
                ->whereIn('status', ['open', 'in_progress'])
                ->count(),
        ];

        $pdf = Pdf::loadView('reports.clinic-summary', [
            'clinic' => $clinic,
            'from' => $from,
            'to' => $to,
            'metrics' => $metrics,
        ])->setPaper('a4');

        return $pdf->download('clinic-summary-'.$from->format('Ymd').'-'.$to->format('Ymd').'.pdf');
    }

    private function resolveClinicId(Request $request): int
    {
        $user = $request->user();

        if ($user?->isSuperAdmin()) {
            $clinicId = (int) $request->integer('clinic_id');

            if ($clinicId > 0) {
                return $clinicId;
            }

            return (int) Clinic::query()->orderBy('id')->value('id');
        }

        return (int) $user?->clinic_id;
    }

    /**
     * @return array{0:CarbonImmutable,1:CarbonImmutable}
     */
    private function resolveDateRange(Request $request): array
    {
        $from = CarbonImmutable::parse($request->string('from')->toString() ?: now()->startOfMonth()->toDateString());
        $to = CarbonImmutable::parse($request->string('to')->toString() ?: now()->toDateString());

        return [$from, $to];
    }
}
