<div>
    <label for="stage_id" class="block text-sm font-medium text-gray-700">المرحلة الدراسية</label>
    <select id="stage_id" name="stage_id" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
        <option value="">اختر المرحلة</option>
        @foreach ($stages as $stage)
            <option value="{{ $stage->id }}" @selected(old('stage_id', $grade->stage_id ?? '') == $stage->id)>
                {{ $stage->name }}
            </option>
        @endforeach
    </select>
    @error('stage_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="name" class="block text-sm font-medium text-gray-700">اسم الصف</label>
    <input id="name" name="name" type="text" value="{{ old('name', $grade->name ?? '') }}"
           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="code" class="block text-sm font-medium text-gray-700">الكود</label>
    <input id="code" name="code" type="text" dir="ltr" value="{{ old('code', $grade->code ?? '') }}"
           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
    <p class="mt-1 text-xs text-gray-500">يجب أن يكون فريدًا داخل نفس المرحلة فقط.</p>
    @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="sort_order" class="block text-sm font-medium text-gray-700">ترتيب العرض</label>
    <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $grade->sort_order ?? 0) }}"
           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
    @error('sort_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<label class="flex items-center gap-2 text-sm">
    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300"
           @checked(old('is_active', $grade->is_active ?? true))>
    نشط
</label>