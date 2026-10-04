<form wire:submit="login" class="space-y-5">
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
        <input
            id="email"
            type="email"
            dir="ltr"
            autocomplete="username"
            autofocus
            wire:model="email"
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-left"
        >
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">كلمة المرور</label>
        <input
            id="password"
            type="password"
            dir="ltr"
            autocomplete="current-password"
            wire:model="password"
            class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-left"
        >
        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <label class="flex items-center gap-2 text-sm text-gray-700">
        <input type="checkbox" wire:model="remember" class="rounded border-gray-300">
        تذكرني
    </label>

    <button
        type="submit"
        wire:loading.attr="disabled"
        wire:target="login"
        class="w-full rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-60"
    >
        <span wire:loading.remove wire:target="login">دخول</span>
        <span wire:loading wire:target="login">جارٍ الدخول...</span>
    </button>
</form>
