<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\PaymentMethod;
use App\Models\Enrollment;
use App\Services\PaymentService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use RuntimeException;

#[Layout('components.layouts.app')]
class EnrollmentPayments extends Component
{
    public Enrollment $enrollment;

    public ?float $amount = null;

    public string $paymentDate;

    public string $paymentMethod = '';

    public ?string $reference = null;

    public ?string $notes = null;

    public function mount(Enrollment $enrollment): void
    {
        $this->enrollment = $enrollment;
        $this->paymentDate = now()->format('Y-m-d');
    }

    protected function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'paymentDate' => ['required', 'date'],
            'paymentMethod' => ['required', Rule::in(array_column(PaymentMethod::cases(), 'value'))],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function messages(): array
    {
        return [
            'amount.required' => 'قيمة الدفعة مطلوبة.',
            'amount.min' => 'قيمة الدفعة يجب أن تكون أكبر من صفر.',
            'paymentDate.required' => 'تاريخ الدفع مطلوب.',
            'paymentMethod.required' => 'طريقة الدفع مطلوبة.',
        ];
    }

    public function save(PaymentService $service): void
    {
        $this->validate();

        try {
            $service->record($this->enrollment, [
                'amount' => $this->amount,
                'payment_date' => $this->paymentDate,
                'payment_method' => $this->paymentMethod,
                'reference' => $this->reference,
                'notes' => $this->notes,
            ], auth()->user());
        } catch (RuntimeException $e) {
            $this->addError('amount', $e->getMessage());

            return;
        }

        $this->reset(['amount', 'reference', 'notes']);
        $this->paymentMethod = '';
        $this->paymentDate = now()->format('Y-m-d');

        session()->flash('success', 'تم تسجيل الدفعة بنجاح.');
    }

    public function render()
    {
        return view('livewire.enrollment-payments', [
            'payments' => $this->enrollment->payments()
                ->with('receivedBy')
                ->latest('payment_date')
                ->latest('id')
                ->get(),
            'paymentMethods' => PaymentMethod::cases(),
        ]);
    }
}