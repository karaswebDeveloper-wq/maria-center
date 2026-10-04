<x-layouts.app title="تعديل مادة">
    <h2 class="text-xl font-semibold">تعديل مادة دراسية</h2>

    <form method="POST" action="{{ route('subjects.update', $subject) }}" class="mt-6 max-w-lg space-y-5">
        @csrf
        @method('PUT')
        @include('subjects._form')

        <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2 font-medium text-white hover:bg-indigo-700">
            تحديث
        </button>
    </form>
</x-layouts.app>