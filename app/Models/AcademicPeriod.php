<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicPeriod extends Model
{
    use HasFactory;

    protected $fillable = ['year', 'month', 'name', 'starts_at', 'ends_at', 'is_closed'];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'month' => 'integer',
            'starts_at' => 'date',
            'ends_at' => 'date',
            'is_closed' => 'boolean',
        ];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'period_id');
    }

    public function teacherPayrolls(): HasMany
    {
        return $this->hasMany(TeacherPayroll::class, 'period_id');
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('is_closed', false);
    }
}