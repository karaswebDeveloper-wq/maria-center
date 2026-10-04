<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\TeacherPayroll;
use App\Services\PayrollService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use RuntimeException;

#[Layout('components.layouts.app')]
class PayrollShow extends Component
{
    public TeacherPayroll $payroll;

    public function mount(TeacherPayroll $payroll): void
    {
        $this->payroll = $payroll;
    }

    public function regenerate(PayrollService $service): void
    {
        try {
            $this->payroll = $service->generate($this->payroll->academicPeriod, auth()->user());
            session()->flash('success', 'تم إعادة توليد الراتب بنجاح.');
        } catch (RuntimeException $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function approve(PayrollService $service): void
    {
        try {
            $this->payroll = $service->approve($this->payroll);
            session()->flash('success', 'تم اعتماد الراتب بنجاح.');
        } catch (RuntimeException $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function markAsPaid(PayrollService $service): void
    {
        try {
            $this->payroll = $service->markAsPaid($this->payroll);
            session()->flash('success', 'تم تسجيل صرف الراتب بنجاح.');
        } catch (RuntimeException $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.payroll-show', [
            'items' => $this->payroll->items()->with('teacher')->orderBy('teacher_id')->get(),
        ]);
    }
}