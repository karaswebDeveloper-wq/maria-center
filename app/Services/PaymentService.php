<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PaymentService
{
    /**
     * @param array{
     *     amount:float, payment_date:string, payment_method:string,
     *     reference?:?string, notes?:?string
     * } $data
     */
    public function record(Enrollment $enrollment, array $data, User $receivedBy): Payment
    {
        return DB::transaction(function () use ($enrollment, $data, $receivedBy) {
            $enrollment->refresh();

            if ($data['amount'] <= 0) {
                throw new RuntimeException('قيمة الدفعة يجب أن تكون أكبر من صفر.');
            }

            $remaining = $enrollment->remaining_amount;

            if ($data['amount'] > $remaining) {
                throw new RuntimeException(
                    'قيمة الدفعة أكبر من المبلغ المتبقي ('.number_format($remaining, 2).').'
                );
            }

            return $enrollment->payments()->create(array_merge($data, [
                'received_by' => $receivedBy->id,
            ]));
        });
    }
}