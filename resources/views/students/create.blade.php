<x-layouts.app title="إضافة طالب">
    <h2 class="text-xl font-semibold">إضافة طالب</h2>

    <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data" class="mt-6 max-w-lg space-y-5">
        @csrf
        @include('students._form')

        <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2 font-medium text-white hover:bg-indigo-700">
            حفظ
        </button>
    </form>
</x-layouts.app>