@if (isset($teacher) && $teacher->image_path)
    <div>
        <img src="{{ $teacher->photoUrl() }}" alt="{{ $teacher->name }}" class="h-20 w-20 rounded-full object-cover">
    </div>
@endif

<div>
    <label for="image" class="block text-sm font-medium text-gray-700">
        الصورة الشخصية {{ isset($teacher) ? '(اتركها فارغة للإبقاء على الصورة الحالية)' : '' }}
    </label>
    <input id="image" name="image" type="file" accept="image/*"
           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 file:me-3 file:rounded-md file:border-0 file:bg-gray-100 file:px-3 file:py-1.5">
    @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="name" class="block text-sm font-medium text-gray-700">الاسم</label>
    <input id="name" name="name" type="text" value="{{ old('name', $teacher->name ?? '') }}"
           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="phone" class="block text-sm font-medium text-gray-700">الهاتف</label>
    <input id="phone" name="phone" type="text" dir="ltr" value="{{ old('phone', $teacher->phone ?? '') }}"
           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
    @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
    <input id="email" name="email" type="email" dir="ltr" value="{{ old('email', $teacher->email ?? '') }}"
           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="percentage" class="block text-sm font-medium text-gray-700">نسبة المركز (%)</label>
    <input id="percentage" name="percentage" type="number" step="0.01" min="0" max="100"
           value="{{ old('percentage', $teacher->percentage ?? '') }}"
           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
    @error('percentage') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="notes" class="block text-sm font-medium text-gray-700">ملاحظات</label>
    <textarea id="notes" name="notes" rows="3"
              class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">{{ old('notes', $teacher->notes ?? '') }}</textarea>
    @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<label class="flex items-center gap-2 text-sm">
    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300"
           @checked(old('is_active', $teacher->is_active ?? true))>
    نشط
</label>