<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PayrollStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeacherPayroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id', 'status', 'generated_at', 'approved_at',
        'paid_at', 'created_by', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => PayrollStatus::class,
            'generated_at' => 'datetime',
            'approved_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class, 'period_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TeacherPayrollItem::class, 'payroll_id');
    }
}