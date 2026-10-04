<?php // MonthlyStatistics.php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\AcademicPeriod;
use App\Services\Reports\MonthlyStatisticsService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class MonthlyStatistics extends Component
{
    public ?int $periodId = null;

    public function render()
    {
        $groups = $this->periodId
            ? app(MonthlyStatisticsService::class)->get(AcademicPeriod::findOrFail($this->periodId))
            : collect();

        return view('reports.monthly-statistics', [
            'groups' => $groups,
            'periods' => AcademicPeriod::query()->orderByDesc('year')->orderByDesc('month')->get(),
        ]);
    }
}
