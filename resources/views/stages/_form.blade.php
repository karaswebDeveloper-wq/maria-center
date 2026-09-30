<div>
    <label for="name" class="block text-sm font-medium text-gray-700">الاسم</label>
    <input id="name" name="name" type="text" value="{{ old('name', $stage->name ?? '') }}"
           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="code" class="block text-sm font-medium text-gray-700">الكود</label>
    <input id="code" name="code" type="text" dir="ltr" value="{{ old('code', $stage->code ?? '') }}"
           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
    @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<label class="flex items-center gap-2 text-sm">
    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300"
           @checked(old('is_active', $stage->is_active ?? true))>
    نشط
</label>