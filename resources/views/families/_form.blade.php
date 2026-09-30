<div>
    <label for="name" class="block text-sm font-medium text-gray-700">اسم الأسرة</label>
    <input id="name" name="name" type="text" value="{{ old('name', $family->name ?? '') }}"
           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="phone" class="block text-sm font-medium text-gray-700">الهاتف</label>
    <input id="phone" name="phone" type="text" dir="ltr" value="{{ old('phone', $family->phone ?? '') }}"
           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
    @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="address" class="block text-sm font-medium text-gray-700">العنوان</label>
    <input id="address" name="address" type="text" value="{{ old('address', $family->address ?? '') }}"
           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
    @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="notes" class="block text-sm font-medium text-gray-700">ملاحظات</label>
    <textarea id="notes" name="notes" rows="3"
              class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">{{ old('notes', $family->notes ?? '') }}</textarea>
    @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<label class="flex items-center gap-2 text-sm">
    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300"
           @checked(old('is_active', $family->is_active ?? true))>
    نشط
</label>