<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EnrollmentStatus;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class EnrollmentService
{
    /**
     * Create a new enrollment, or reactivate a previously cancelled
     * enrollment for the same student+subject+period instead of
     * violating the unique constraint by inserting a duplicate row.
     *
     * @param array{
     *     student_id:int, teacher_id:int, subject_id:int, period_id:int,
     *     duration_type:string, fee_amount:float, support_amount:float,
     *     notes?:?string
     * } $data
     */
    public function create(array $data): Enrollment
    {
        return DB::transaction(function () use ($data) {
            $existing = Enrollment::query()
                ->where('student_id', $data['student_id'])
                ->where('subject_id', $data['subject_id'])
                ->where('period_id', $data['period_id'])
                ->first();

            if ($existing && $existing->status === EnrollmentStatus::Active) {
                throw new RuntimeException('الطالب مسجّل بالفعل في هذه المادة خلال هذه الفترة.');
            }

            if ($existing) {
                $existing->update(array_merge($data, ['status' => EnrollmentStatus::Active]));

                return $existing->fresh();
            }

            return Enrollment::query()->create(array_merge($data, ['status' => EnrollmentStatus::Active]));
        });
    }

    public function update(Enrollment $enrollment, array $data): Enrollment
    {
        return DB::transaction(function () use ($enrollment, $data) {
            $enrollment->update($data);

            return $enrollment->fresh();
        });
    }

    public function cancel(Enrollment $enrollment): Enrollment
    {
        $enrollment->update(['status' => EnrollmentStatus::Cancelled]);

        return $enrollment->fresh();
    }

    public function reactivate(Enrollment $enrollment): Enrollment
    {
        $enrollment->update(['status' => EnrollmentStatus::Active]);

        return $enrollment->fresh();
    }
}