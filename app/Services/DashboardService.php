<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EnrollmentStatus;
use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherPayroll;
use App\Services\Reports\TeacherPayrollReportService;
use Illuminate\Support\Collection;

class DashboardService
{
    /** @return array{total_students:int, active_teachers:int, monthly_revenue:float, total_support:float, teacher_entitlements:float, outstanding_payments:float} */
    public function cards(AcademicPeriod $period): array
    {
        $enrollmentTotals = Enrollment::query()
            ->where('period_id', $period->id)
            ->where('status', EnrollmentStatus::Active)
            ->selectRaw('SUM(fee_amount) as fee_total, SUM(support_amount) as support_total')
            ->first();

        $feeTotal = (float) ($enrollmentTotals->fee_total ?? 0);
        $supportTotal = (float) ($enrollmentTotals->support_total ?? 0);
        $requiredTotal = $feeTotal - $supportTotal;

        $paidTotal = (float) Payment::query()
            ->whereHas('enrollment', fn ($q) => $q->where('period_id', $period->id))
            ->sum('amount');

        return [
            'total_students' => Student::query()->where('is_active', true)->count(),
            'active_teachers' => Teacher::query()->where('is_active', true)->count(),
            'monthly_revenue' => $paidTotal,
            'total_support' => $supportTotal,
            'teacher_entitlements' => $this->teacherEntitlements($period),
            'outstanding_payments' => max($requiredTotal - $paidTotal, 0),
        ];
    }

    private function teacherEntitlements(AcademicPeriod $period): float
    {
        $payroll = TeacherPayroll::query()->where('period_id', $period->id)->first();

        // Snapshot rule: an approved/paid payroll's frozen numbers win over a live recalculation.
        if ($payroll && $payroll->status->value !== 'draft') {
            return (float) $payroll->items()->sum('teacher_amount');
        }

        return (float) app(TeacherPayrollReportService::class)->get($period)->sum('teacher_amount');
    }

    /** Last $count periods, oldest first, with revenue and active-enrollment counts. */
    public function trend(int $count = 6): Collection
    {
        $periods = AcademicPeriod::query()
            ->orderByDesc('year')->orderByDesc('month')
            ->limit($count)
            ->get()
            ->sortBy(fn ($p) => [$p->year, $p->month])
            ->values();

        return $periods->map(fn ($period) => (object) [
            'label' => $period->name,
            'revenue' => (float) Payment::query()->whereHas('enrollment', fn ($q) => $q->where('period_id', $period->id))->sum('amount'),
            'enrollments_count' => Enrollment::query()->where('period_id', $period->id)->where('status', 'active')->count(),
        ]);
    }

    /** @return array{students: \Illuminate\Support\Collection, payments: \Illuminate\Support\Collection, enrollments: \Illuminate\Support\Collection} */
    public function recentActivity(): array
    {
        return [
            'students' => Student::query()->with('family')->latest()->limit(5)->get(),
            'payments' => Payment::query()->with(['enrollment.student', 'receivedBy'])->latest()->limit(5)->get(),
            'enrollments' => Enrollment::query()->with(['student', 'subject', 'teacher'])->latest()->limit(5)->get(),
        ];
    }
}