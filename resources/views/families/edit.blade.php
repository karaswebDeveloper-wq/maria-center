<x-layouts.app title="تعديل أسرة">
    <h2 class="text-xl font-semibold">تعديل بيانات الأسرة</h2>

    <form method="POST" action="{{ route('families.update', $family) }}" class="mt-6 max-w-lg space-y-5">
        @csrf
        @method('PUT')
        @include('families._form')

        <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2 font-medium text-white hover:bg-indigo-700">
            تحديث
        </button>
    </form>
</x-layouts.app>