<x-layouts.app title="الطلاب">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">الطلاب</h2>
        <a href="{{ route('students.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            إضافة طالب
        </a>
    </div>

    <div class="mt-6">
        <livewire:students-table />
    </div>
</x-layouts.app>