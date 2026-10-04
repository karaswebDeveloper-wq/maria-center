@if (isset($student) && $student->image_path)
    <div>
        <img src="{{ $student->photoUrl() }}" alt="{{ $student->name }}" class="h-20 w-20 rounded-full object-cover">
    </div>
@endif

<div>
    <label for="image" class="block text-right text-sm font-medium text-secondary">
        الصورة الشخصية {{ isset($student) ? '(اتركها فارغة للإبقاء على الصورة الحالية)' : '' }}
    </label>
    <input id="image" name="image" type="file" dir="rtl" accept="image/*"
           class="mt-1 w-full rounded-lg border border-secondary/20 bg-surface px-3 py-2 text-right focus:border-primary focus:ring-primary file:ml-4 file:rounded-md file:border-0 file:bg-primary/10 file:px-4 file:py-2 file:text-primary">
    @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="family_id" class="block text-right text-sm font-medium text-secondary">الأسرة</label>
    <select id="family_id" name="family_id" class="mt-1 w-full rounded-lg border border-secondary/20 bg-surface px-3 py-2 text-right focus:border-primary focus:ring-primary">
        <option value="">اختر الأسرة</option>
        @foreach ($families as $family)
            <option value="{{ $family->id }}" @selected(old('family_id', $student->family_id ?? '') == $family->id)>
                {{ $family->name }}
            </option>
        @endforeach
    </select>
    @error('family_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

{{-- Stage/Grade cascading select: Alpine-only, no server round-trip. --}}
<div x-data="{
        stageId: {{ old('stage_id', $student->grade->stage_id ?? 'null') }},
        grades: {{ \Illuminate\Support\Js::from($grades->map(fn ($g) => ['id' => $g->id, 'name' => $g->name, 'stage_id' => $g->stage_id])->values()) }},
     }">
    <div>
        <label for="stage_id" class="block text-right text-sm font-medium text-secondary">المرحلة الدراسية</label>
        <select id="stage_id" x-model.number="stageId" class="mt-1 w-full rounded-lg border border-secondary/20 bg-surface px-3 py-2 text-right focus:border-primary focus:ring-primary">
            <option value="">اختر المرحلة</option>
            @foreach ($stages as $stage)
                <option value="{{ $stage->id }}">{{ $stage->name }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500">اختر المرحلة أولاً لعرض الصفوف المتاحة.</p>
    </div>

    <div class="mt-4">
        <label for="grade_id" class="block text-right text-sm font-medium text-secondary">الصف</label>
        <select id="grade_id" name="grade_id" class="mt-1 w-full rounded-lg border border-secondary/20 bg-surface px-3 py-2 text-right focus:border-primary focus:ring-primary">
            <option value="">اختر الصف</option>
            <template x-for="grade in grades.filter(g => g.stage_id === stageId)" :key="grade.id">
                <option :value="grade.id" x-text="grade.name" :selected="grade.id === {{ old('grade_id', $student->grade_id ?? 'null') }}"></option>
            </template>
        </select>
        @error('grade_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label for="student_code" class="block text-right text-sm font-medium text-secondary">كود الطالب</label>
    <input id="student_code" name="student_code" type="text" dir="ltr" value="{{ old('student_code', $student->student_code ?? '') }}"
           class="mt-1 w-full rounded-lg border border-secondary/20 bg-surface px-3 py-2 text-right focus:border-primary focus:ring-primary">
    @error('student_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="name" class="block text-right text-sm font-medium text-secondary">الاسم</label>
    <input id="name" name="name" type="text" value="{{ old('name', $student->name ?? '') }}"
           class="mt-1 w-full rounded-lg border border-secondary/20 bg-surface px-3 py-2 text-right focus:border-primary focus:ring-primary">
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="phone" class="block text-right text-sm font-medium text-secondary">الهاتف</label>
    <input id="phone" name="phone" type="text" dir="ltr" value="{{ old('phone', $student->phone ?? '') }}"
           class="mt-1 w-full rounded-lg border border-secondary/20 bg-surface px-3 py-2 text-right focus:border-primary focus:ring-primary">
    @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="notes" class="block text-right text-sm font-medium text-secondary">ملاحظات</label>
    <textarea id="notes" name="notes" rows="3"
              class="mt-1 w-full rounded-lg border border-secondary/20 bg-surface px-3 py-2 text-right focus:border-primary focus:ring-primary">{{ old('notes', $student->notes ?? '') }}</textarea>
    @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<label class="flex items-center gap-2 text-right text-sm text-secondary">
    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300"
           @checked(old('is_active', $student->is_active ?? true))>
    نشط
</label>
