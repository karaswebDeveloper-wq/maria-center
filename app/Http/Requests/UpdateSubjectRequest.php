<?php // UpdateSubjectRequest.php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:30', 'alpha_dash', Rule::unique('subjects', 'code')->ignore($this->route('subject'))],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المادة مطلوب.',
            'code.required' => 'كود المادة مطلوب.',
            'code.unique' => 'هذا الكود مستخدم من قبل.',
        ];
    }
}