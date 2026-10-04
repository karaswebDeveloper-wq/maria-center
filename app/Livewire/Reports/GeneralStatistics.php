<?php // GeneralStatistics.php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Services\Reports\GeneralStatisticsService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class GeneralStatistics extends Component
{
    public function render()
    {
        $service = app(GeneralStatisticsService::class);

        return view('reports.general-statistics', [
            'stages' => $service->get(),
            'totalStudents' => $service->totalStudents(),
        ]);
    }
}
