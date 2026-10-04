<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Dashboard;
use App\Models\AcademicPeriod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_is_the_home_page(): void
    {
        AcademicPeriod::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(route('home'))
            ->assertOk()
            ->assertSeeLivewire(Dashboard::class);
    }

    public function test_dashboard_shows_a_message_when_no_periods_exist(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('home'))
            ->assertSee('لا توجد فترات دراسية');
    }

    public function test_switching_period_updates_mounted_period(): void
    {
        $periodA = AcademicPeriod::factory()->create();
        $periodB = AcademicPeriod::factory()->create();

        Livewire::actingAs(User::factory()->create())
            ->test(Dashboard::class)
            ->set('periodId', $periodB->id)
            ->assertSet('periodId', $periodB->id);
    }
}