<?php // StoreAcademicPeriodRequest.php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAcademicPeriodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'year' => ['required', 'integer', 'between:2000,2100'],
            'month' => ['required', 'integer', 'between:1,12'],
            'name' => ['required', 'string', 'max:255'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'is_closed' => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $exists = \App\Models\AcademicPeriod::query()
                ->where('year', $this->input('year'))
                ->where('month', $this->input('month'))
                ->exists();

            if ($exists) {
                $validator->errors()->add('month', 'هذه الفترة (السنة والشهر) موجودة بالفعل.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'year.required' => 'السنة مطلوبة.',
            'month.required' => 'الشهر مطلوب.',
            'name.required' => 'اسم الفترة مطلوب.',
            'starts_at.required' => 'تاريخ البداية مطلوب.',
            'ends_at.required' => 'تاريخ النهاية مطلوب.',
            'ends_at.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية.',
        ];
    }
}