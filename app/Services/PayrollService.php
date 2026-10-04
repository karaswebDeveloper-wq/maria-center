<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EnrollmentStatus;
use App\Enums\PayrollStatus;
use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Teacher;
use App\Models\TeacherPayroll;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PayrollService
{
    public function generate(AcademicPeriod $period, User $createdBy): TeacherPayroll
    {
        return DB::transaction(function () use ($period, $createdBy) {
            $payroll = TeacherPayroll::query()->where('period_id', $period->id)->first();

            if ($payroll && $payroll->status !== PayrollStatus::Draft) {
                throw new RuntimeException('لا يمكن إعادة توليد راتب تم اعتماده أو صرفه بالفعل لهذه الفترة.');
            }

            $payroll ??= TeacherPayroll::query()->create([
                'period_id' => $period->id,
                'status' => PayrollStatus::Draft,
                'created_by' => $createdBy->id,
            ]);

            // Draft regeneration: wipe previous items and recompute from current enrollments.
            $payroll->items()->delete();

            $rows = Enrollment::query()
                ->where('period_id', $period->id)
                ->where('status', EnrollmentStatus::Active)
                ->selectRaw('teacher_id, COUNT(*) as students_count, SUM(fee_amount) as fee_amount, SUM(support_amount) as support_amount')
                ->groupBy('teacher_id')
                ->get();

            $teachers = Teacher::query()->whereIn('id', $rows->pluck('teacher_id'))->get()->keyBy('id');

            foreach ($rows as $row) {
                $teacher = $teachers->get($row->teacher_id);

                if (! $teacher) {
                    continue; // defensive: teacher soft-deleted after enrollment was created
                }

                $gross = round((float) $row->fee_amount + (float) $row->support_amount, 2);
                $centerAmount = round($gross * (float) $teacher->percentage / 100, 2);

                $payroll->items()->create([
                    'teacher_id' => $teacher->id,
                    'students_count' => $row->students_count,
                    'fee_amount' => $row->fee_amount,
                    'support_amount' => $row->support_amount,
                    'gross_amount' => $gross,
                    'center_percentage' => $teacher->percentage,
                    'center_amount' => $centerAmount,
                    'teacher_amount' => $gross - $centerAmount,
                ]);
            }

            $payroll->update(['generated_at' => now()]);

            return $payroll->fresh();
        });
    }

    public function approve(TeacherPayroll $payroll): TeacherPayroll
    {
        if ($payroll->status !== PayrollStatus::Draft) {
            throw new RuntimeException('لا يمكن اعتماد راتب ليس في حالة مسودة.');
        }

        if ($payroll->items()->doesntExist()) {
            throw new RuntimeException('لا يمكن اعتماد راتب لا يحتوي على عناصر.');
        }

        $payroll->update(['status' => PayrollStatus::Approved, 'approved_at' => now()]);

        return $payroll->fresh();
    }

    public function markAsPaid(TeacherPayroll $payroll): TeacherPayroll
    {
        if ($payroll->status !== PayrollStatus::Approved) {
            throw new RuntimeException('لا يمكن صرف راتب لم يتم اعتماده بعد.');
        }

        $payroll->update(['status' => PayrollStatus::Paid, 'paid_at' => now()]);

        return $payroll->fresh();
    }
}