<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DurationType;
use App\Enums\EnrollmentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'teacher_id', 'subject_id', 'period_id',
        'duration_type', 'fee_amount', 'support_amount', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'duration_type' => DurationType::class,
            'fee_amount' => 'decimal:2',
            'support_amount' => 'decimal:2',
            'status' => EnrollmentStatus::class,
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class)->withTrashed();
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class)->withTrashed();
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class)->withTrashed();
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class, 'period_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', EnrollmentStatus::Active);
    }

    protected function requiredFromParent(): Attribute
{
    return Attribute::make(
        get: fn () => round((float) $this->fee_amount - (float) $this->support_amount, 2),
    );
}

protected function paidAmount(): Attribute
{
    return Attribute::make(
        get: fn () => round((float) $this->payments()->sum('amount'), 2),
    );
}

protected function remainingAmount(): Attribute
{
    return Attribute::make(
        get: fn () => round($this->required_from_parent - $this->paid_amount, 2),
    );
}


}