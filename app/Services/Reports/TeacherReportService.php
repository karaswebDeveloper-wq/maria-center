<?php

declare(strict_types=1);

namespace App\Services\Reports;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Collection;

class TeacherReportService
{
    /**
     * @param array{period_id?:?int, teacher_id?:?int, subject_id?:?int, stage_id?:?int, grade_id?:?int} $filters
     */
    public function get(array $filters): Collection
    {
        return Enrollment::query()
            ->with(['student.grade.stage', 'teacher', 'subject', 'academicPeriod'])
            ->where('status', 'active')
            ->when($filters['period_id'] ?? null, fn ($q, $v) => $q->where('period_id', $v))
            ->when($filters['teacher_id'] ?? null, fn ($q, $v) => $q->where('teacher_id', $v))
            ->when($filters['subject_id'] ?? null, fn ($q, $v) => $q->where('subject_id', $v))
            ->when($filters['grade_id'] ?? null, function ($q, $v) {
                $q->whereHas('student', fn ($q) => $q->where('grade_id', $v));
            })
            ->when(($filters['stage_id'] ?? null) && empty($filters['grade_id']), function ($q) use ($filters) {
                $q->whereHas('student.grade', fn ($q) => $q->where('stage_id', $filters['stage_id']));
            })
            ->orderBy('student_id')
            ->get();
    }
}