<?php

declare(strict_types=1);

namespace App\Services\Reports;

use App\Enums\EnrollmentStatus;
use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Teacher;
use Illuminate\Support\Collection;

class TeacherPayrollReportService
{
    /**
     * Live preview of teacher payroll amounts for a period — same formula
     * as PayrollService::generate(), but read-only and never persisted.
     */
    public function get(AcademicPeriod $period): Collection
    {
        $rows = Enrollment::query()
            ->where('period_id', $period->id)
            ->where('status', EnrollmentStatus::Active)
            ->selectRaw('teacher_id, COUNT(*) as students_count, SUM(fee_amount) as fee_amount, SUM(support_amount) as support_amount')
            ->groupBy('teacher_id')
            ->get();

        $teachers = Teacher::query()->whereIn('id', $rows->pluck('teacher_id'))->get()->keyBy('id');

        return $rows->map(function ($row) use ($teachers) {
            $teacher = $teachers->get($row->teacher_id);

            if (! $teacher) {
                return null;
            }

            $gross = round((float) $row->fee_amount + (float) $row->support_amount, 2);
            $centerAmount = round($gross * (float) $teacher->percentage / 100, 2);

            return (object) [
                'teacher' => $teacher,
                'students_count' => $row->students_count,
                'fee_amount' => (float) $row->fee_amount,
                'support_amount' => (float) $row->support_amount,
                'gross_amount' => $gross,
                'center_percentage' => (float) $teacher->percentage,
                'center_amount' => $centerAmount,
                'teacher_amount' => $gross - $centerAmount,
            ];
        })->filter()->values();
    }
}