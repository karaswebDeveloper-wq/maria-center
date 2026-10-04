<?php

declare(strict_types=1);

namespace App\Services\Reports;

use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use Illuminate\Support\Collection;

class MonthlyStatisticsService
{
    /**
     * Active enrollments for the period, grouped by stage then grade.
     */
    public function get(AcademicPeriod $period): Collection
    {
        $rows = Enrollment::query()
            ->join('students', 'students.id', '=', 'enrollments.student_id')
            ->join('grades', 'grades.id', '=', 'students.grade_id')
            ->join('stages', 'stages.id', '=', 'grades.stage_id')
            ->where('enrollments.period_id', $period->id)
            ->where('enrollments.status', 'active')
            ->selectRaw('
                stages.name as stage_name,
                grades.name as grade_name,
                grades.sort_order as grade_sort_order,
                COUNT(DISTINCT enrollments.student_id) as students_count,
                SUM(enrollments.fee_amount - enrollments.support_amount) as parent_amount,
                SUM(enrollments.support_amount) as support_amount,
                SUM(enrollments.fee_amount) as total_amount
            ')
            ->groupBy('stages.id', 'stages.name', 'grades.id', 'grades.name', 'grades.sort_order')
            ->orderBy('stages.name')
            ->orderBy('grade_sort_order')
            ->get();

        return $rows->groupBy('stage_name');
    }
}