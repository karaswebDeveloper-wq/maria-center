<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\AcademicPeriod;
use App\Models\TeacherPayroll;
use App\Services\PayrollService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use RuntimeException;

#[Layout('components.layouts.app')]
class PayrollsIndex extends Component
{
    public ?int $periodId = null;

    protected function rules(): array
    {
        return ['periodId' => ['required', 'integer', 'exists:academic_periods,id']];
    }

    protected function messages(): array
    {
        return ['periodId.required' => 'اختر الفترة الدراسية أولاً.'];
    }

    public function generate(PayrollService $service): void
    {
        $this->validate();

        try {
            $payroll = $service->generate(AcademicPeriod::findOrFail($this->periodId), auth()->user());
        } catch (RuntimeException $e) {
            $this->addError('periodId', $e->getMessage());

            return;
        }

        $this->redirect(route('payrolls.show', $payroll), navigate: false);
    }

    public function render()
    {
        return view('livewire.payrolls-index', [
            'payrolls' => TeacherPayroll::query()
                ->with(['academicPeriod', 'creator'])
                ->withCount('items')
                ->orderByDesc('id')
                ->paginate(15),
            'periods' => AcademicPeriod::query()->orderByDesc('year')->orderByDesc('month')->get(),
        ]);
    }
}