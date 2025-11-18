<x-guest-layout>
    <form method="POST" action="{{ route('login.staff') }}">
        @csrf

        @if ($errors->has('email'))
            <div class="mb-4 text-red-600 font-semibold">
                {{ $errors->first('email') }}
            </div>
        @endif

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('Log in as Staff') }}
            </x-primary-button>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('login.user') }}" class="text-sm text-blue-600 hover:underline">
                Đăng nhập với tư cách người dùng?
            </a>
        </div>

    </form>
</x-guest-layout>
