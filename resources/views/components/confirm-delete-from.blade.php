@props(['action', 'label' => 'هل أنت متأكد من الحذف؟'])

<div x-data="{ open: false }" class="inline">
    <button type="button" @click="open = true" class="text-sm text-red-600 hover:underline">حذف</button>

    <div x-show="open" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
         @keydown.escape.window="open = false">
        <div @click.outside="open = false" class="w-full max-w-sm rounded-xl bg-white p-6 shadow-lg">
            <p class="text-gray-800">{{ $label }}</p>
            <div class="mt-5 flex justify-end gap-3">
                <button type="button" @click="open = false" class="rounded-lg border px-4 py-2 text-sm hover:bg-gray-50">
                    إلغاء
                </button>
                <form method="POST" action="{{ $action }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                        نعم، احذف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>