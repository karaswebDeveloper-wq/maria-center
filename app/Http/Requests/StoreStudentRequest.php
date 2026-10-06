<?php // StoreStudentRequest.php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'family_name' => ['nullable', 'string', 'max:255'],
            'grade_id' => ['required', 'integer', 'exists:grades,id'],
            'student_code' => ['required', 'string', 'max:30', 'unique:students,student_code'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'image' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'family_name.max' => 'اسم الأسرة يجب ألا يتجاوز 255 حرفًا.',
            'grade_id.required' => 'الصف الدراسي مطلوب.',
            'grade_id.exists' => 'الصف المختار غير موجود.',
            'student_code.required' => 'كود الطالب مطلوب.',
            'student_code.unique' => 'هذا الكود مستخدم من قبل.',
            'name.required' => 'اسم الطالب مطلوب.',
            'image.image' => 'الملف يجب أن يكون صورة.',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
