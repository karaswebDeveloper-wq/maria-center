<div class="grid grid-cols-2 gap-4">
    <div>
        <label for="year" class="block text-sm font-medium text-gray-700">السنة</label>
        <input id="year" name="year" type="number" value="{{ old('year', $period->year ?? now()->year) }}"
               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
        @error('year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="month" class="block text-sm font-medium text-gray-700">الشهر</label>
        <input id="month" name="month" type="number" min="1" max="12" value="{{ old('month', $period->month ?? now()->month) }}"
               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
        @error('month') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label for="name" class="block text-sm font-medium text-gray-700">اسم الفترة</label>
    <input id="name" name="name" type="text" value="{{ old('name', $period->name ?? '') }}"
           placeholder="مثال: أغسطس 2026"
           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label for="starts_at" class="block text-sm font-medium text-gray-700">تاريخ البداية</label>
        <input id="starts_at" name="starts_at" type="date"
               value="{{ old('starts_at', isset($period) ? $period->starts_at->format('Y-m-d') : '') }}"
               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
        @error('starts_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="ends_at" class="block text-sm font-medium text-gray-700">تاريخ النهاية</label>
        <input id="ends_at" name="ends_at" type="date"
               value="{{ old('ends_at', isset($period) ? $period->ends_at->format('Y-m-d') : '') }}"
               class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
        @error('ends_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<label class="flex items-center gap-2 text-sm">
    <input type="checkbox" name="is_closed" value="1" class="rounded border-gray-300"
           @checked(old('is_closed', $period->is_closed ?? false))>
    الفترة مغلقة
</label>