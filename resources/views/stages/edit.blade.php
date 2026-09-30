<x-layouts.app title="تعديل مرحلة">
    <h2 class="text-xl font-semibold">تعديل مرحلة دراسية</h2>

    <form method="POST" action="{{ route('stages.update', $stage) }}" class="mt-6 max-w-lg space-y-5">
        @csrf
        @method('PUT')
        @include('stages._form')

        <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2 font-medium text-white hover:bg-indigo-700">
            تحديث
        </button>
    </form>
</x-layouts.app>