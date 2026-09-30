<?php // UpdateStageRequest.php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:30', 'alpha_dash', Rule::unique('stages', 'code')->ignore($this->route('stage'))],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المرحلة مطلوب.',
            'code.required' => 'كود المرحلة مطلوب.',
            'code.unique' => 'هذا الكود مستخدم من قبل.',
        ];
    }
}