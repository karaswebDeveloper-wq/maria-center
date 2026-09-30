<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherPayrollItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_id', 'teacher_id', 'students_count', 'fee_amount',
        'support_amount', 'gross_amount', 'center_percentage',
        'center_amount', 'teacher_amount',
    ];

    protected function casts(): array
    {
        return [
            'students_count' => 'integer',
            'fee_amount' => 'decimal:2',
            'support_amount' => 'decimal:2',
            'gross_amount' => 'decimal:2',
            'center_percentage' => 'decimal:2',
            'center_amount' => 'decimal:2',
            'teacher_amount' => 'decimal:2',
        ];
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(TeacherPayroll::class, 'payroll_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class)->withTrashed();
    }
}