<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\AcademicPeriod;
use App\Services\DashboardService;
use App\Services\Reports\GeneralStatisticsService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    public ?int $periodId = null;

    public function mount(): void
    {
        $this->periodId = AcademicPeriod::query()->orderByDesc('year')->orderByDesc('month')->value('id');
    }

    public function render()
    {
        $service = app(DashboardService::class);
        $period = $this->periodId ? AcademicPeriod::find($this->periodId) : null;

        return view('livewire.dashboard', [
            'period' => $period,
            'periods' => AcademicPeriod::query()->orderByDesc('year')->orderByDesc('month')->get(),
            'cards' => $period ? $service->cards($period) : null,
            'stagesBreakdown' => app(GeneralStatisticsService::class)->get(),
            'trend' => $service->trend(),
            'recent' => $service->recentActivity(),
        ]);
    }
}