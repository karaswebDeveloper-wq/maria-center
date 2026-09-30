<?php // StoreGradeRequest.php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'stage_id' => ['required', 'integer', 'exists:stages,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required', 'string', 'max:30',
                Rule::unique('grades')->where('stage_id', $this->input('stage_id')),
            ],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'stage_id.required' => 'المرحلة الدراسية مطلوبة.',
            'stage_id.exists' => 'المرحلة الدراسية غير موجودة.',
            'name.required' => 'اسم الصف مطلوب.',
            'code.required' => 'كود الصف مطلوب.',
            'code.unique' => 'هذا الكود مستخدم بالفعل في نفس المرحلة.',
        ];
    }
}