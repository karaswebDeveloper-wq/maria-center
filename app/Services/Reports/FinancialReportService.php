<?php

declare(strict_types=1);

namespace App\Services\Reports;

use App\Enums\EnrollmentStatus;
use App\Enums\PayrollStatus;
use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\TeacherPayroll;

class FinancialReportService
{
    public function getPeriodReport(AcademicPeriod $period): array
    {
        $enrollments = Enrollment::query()
            ->where('period_id', $period->id)
            ->where('status', EnrollmentStatus::Active);

        $feeAmount = (float) (clone $enrollments)
            ->sum('fee_amount');

        $supportAmount = (float) (clone $enrollments)
            ->sum('support_amount');

        $grossAmount = round(
            $feeAmount + $supportAmount,
            2
        );

        $paymentsAmount = (float) Payment::query()
            ->whereHas('enrollment', function ($query) use ($period) {
                $query->where('period_id', $period->id)
                    ->where('status', EnrollmentStatus::Active);
            })
            ->sum('amount');

        $remainingAmount = round(
            $feeAmount - $paymentsAmount,
            2
        );

        $payroll = TeacherPayroll::query()
            ->where('period_id', $period->id)
            ->whereIn('status', [
                PayrollStatus::Approved,
                PayrollStatus::Paid,
            ])
            ->with('items')
            ->first();

        $teacherAmount = $payroll
            ? (float) $payroll->items->sum('teacher_amount')
            : 0.0;

        $centerAmount = $payroll
            ? (float) $payroll->items->sum('center_amount')
            : 0.0;

        return [
            'period' => $period,

            'fee_amount' => round($feeAmount, 2),

            'support_amount' => round($supportAmount, 2),

            'gross_amount' => $grossAmount,

            'payments_amount' => round($paymentsAmount, 2),

            'remaining_amount' => $remainingAmount,

            'teacher_amount' => round($teacherAmount, 2),

            'center_amount' => round($centerAmount, 2),

            'payroll_status' => $payroll?->status,

            'net_center_after_payroll' => round(
                $paymentsAmount - $teacherAmount,
                2
            ),
        ];
    }

    public function getPaymentMethodBreakdown(
        AcademicPeriod $period
    ): array {
        return Payment::query()
            ->whereHas('enrollment', function ($query) use ($period) {
                $query->where('period_id', $period->id)
                    ->where('status', EnrollmentStatus::Active);
            })
            ->selectRaw(
                'payment_method, COUNT(*) as payments_count, SUM(amount) as total_amount'
            )
            ->groupBy('payment_method')
            ->orderByDesc('total_amount')
            ->get()
            ->map(fn ($row) => [
                'payment_method' => $row->payment_method,
                'payments_count' => (int) $row->payments_count,
                'total_amount' => round(
                    (float) $row->total_amount,
                    2
                ),
            ])
            ->values()
            ->all();
    }
}