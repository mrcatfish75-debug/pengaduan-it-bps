<x-guest-layout>

<div class="min-h-screen flex items-center justify-center px-4
    bg-cover bg-center relative"
    style="background-image: url('{{ ('images/bps-building.jpg') }}');">

    <!-- Overlay agar background agak gelap -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    <!-- LOGIN CARD -->
    <div class="relative w-full max-w-md
        bg-gray-200/70 backdrop-blur-md
        rounded-xl shadow-2xl p-8">

        <!-- LOGO -->
        <div class="text-center mb-6">

            <img src="{{ ('images/logo-bps.png') }}"
                 class="w-28 mx-auto mb-4">

            <h1 class="text-2xl font-bold text-gray-800 tracking-wide">
                SINTAK
            </h1>

            <p class="text-sm text-gray-700 font-medium">
                Sistem Informasi Tindak Aduan Kerusakan
            </p>

        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- EMAIL -->
            <div class="mb-4 text-gray-700 font-semibold">

                <x-input-label for="email" value="Email" class="text-gray-700"/>

                <x-text-input
                    id="email"
                    class="block mt-1 w-full rounded-lg border-gray-300
                    focus:border-blue-500 focus:ring-blue-500"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus />

                <x-input-error :messages="$errors->get('email')" class="mt-2" />

            </div>

            <!-- PASSWORD -->
            <div class="mb-4 text-gray-700 font-semibold">

                <x-input-label for="password" value="Password" class="text-gray-700"/>

                <x-text-input
                    id="password"
                    class="block mt-1 w-full rounded-lg border-gray-300
                    focus:border-blue-500 focus:ring-blue-500"
                    type="password"
                    name="password"
                    required />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />

            </div>

            <!-- REMEMBER -->
            <div class="flex items-center justify-between mb-5">

                <label class="flex items-center text-sm text-gray-700">

                    <input
                        type="checkbox"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        name="remember">

                    <span class="ml-2">
                        Remember me
                    </span>

                </label>

                @if (Route::has('password.request'))

                    <a class="text-sm text-blue-700 hover:underline"
                       href="{{ route('password.request') }}">
                        Forgot password?
                    </a>

                @endif

            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700
                text-white font-semibold py-2.5 rounded-lg transition">

                Login

            </button>

        </form>

    </div>

</div>

</x-guest-layout>