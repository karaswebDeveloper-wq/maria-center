<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'phone', 'email', 'image_path',
        'sort_order', 'percentage', 'is_active', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'percentage' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function payrollItems(): HasMany
    {
        return $this->hasMany(TeacherPayrollItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    public function photoUrl(): string
{
    return $this->image_path
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->image_path)
        : asset('images/teacher-placeholder.png'); // ضيف صورة افتراضية هنا، أو شيل السطر واستخدم null في الـ view
}


}