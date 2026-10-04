<?php

declare(strict_types=1);

namespace App\Services\Reports;

use App\Models\Stage;
use App\Models\Student;
use Illuminate\Support\Collection;

class GeneralStatisticsService
{
    /**
     * Current snapshot of active students grouped by stage and grade —
     * not tied to any academic period.
     */
    public function get(): Collection
    {
        return Stage::query()
            ->active()
            ->with(['grades' => function ($query) {
                $query->active()
                    ->orderBy('sort_order')
                    ->withCount(['students' => fn ($q) => $q->where('is_active', true)]);
            }])
            ->orderBy('name')
            ->get();
    }

    public function totalStudents(): int
    {
        return Student::query()->where('is_active', true)->count();
    }
}