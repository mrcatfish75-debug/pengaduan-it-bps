<nav x-data="{ open: false }" 
     class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 shadow-sm">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- LEFT MENU -->
            <div class="flex items-center">

                <!-- LOGO -->
                <div class="shrink-0 flex items-center">
                    <a href="
                        @if(auth()->user()->role === 'admin')
                            {{ route('admin.dashboard') }}
                        @elseif(auth()->user()->role === 'kasubag')
                            {{ route('kasubag.dashboard') }}
                        @else
                            {{ route('pegawai.dashboard') }}
                        @endif
                    ">
                        <x-application-logo class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- DESKTOP MENU -->
                <div class="hidden sm:flex sm:items-center sm:space-x-8 sm:ms-10">

                    {{-- ================= ADMIN ================= --}}
                    @if(auth()->user()->role === 'admin')

                        <x-nav-link :href="route('admin.dashboard')"
                                    :active="request()->routeIs('admin.dashboard')"
                                    class="text-gray-700 dark:text-gray-200">
                            Dashboard
                        </x-nav-link>

                        <x-nav-link :href="route('admin.laporan')"
                                    :active="request()->routeIs('admin.laporan*')"
                                    class="text-gray-700 dark:text-gray-200">
                            Laporan Masuk
                        </x-nav-link>

                        <x-nav-link :href="route('admin.activity-log')"
                                    :active="request()->routeIs('admin.activity-log')"
                                    class="text-gray-700 dark:text-gray-200">
                            Activity Log
                        </x-nav-link>

                    {{-- ================= KASUBAG ================= --}}
                    @elseif(auth()->user()->role === 'kasubag')

                        <x-nav-link :href="route('kasubag.dashboard')"
                                    :active="request()->routeIs('kasubag.dashboard')"
                                    class="text-gray-700 dark:text-gray-200">
                            Dashboard
                        </x-nav-link>

                    {{-- ================= PEGAWAI ================= --}}
                    @else

                        <x-nav-link :href="route('pegawai.dashboard')"
                                    :active="request()->routeIs('pegawai.dashboard')"
                                    class="text-gray-700 dark:text-gray-200">
                            Dashboard
                        </x-nav-link>

                        <x-nav-link :href="route('lapor.create')"
                                    :active="request()->routeIs('lapor.create')"
                                    class="text-gray-700 dark:text-gray-200">
                            Buat Laporan
                        </x-nav-link>

                        <x-nav-link :href="route('pegawai.laporan_saya')"
                                    :active="request()->routeIs('pegawai.laporan_saya')"
                                    class="text-gray-700 dark:text-gray-200">
                            Laporan Saya
                        </x-nav-link>

                    @endif

                </div>
            </div>

            <!-- USER DROPDOWN -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                <x-dropdown align="right" width="48">

                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium 
                                       text-gray-700 dark:text-gray-200 
                                       bg-white dark:bg-gray-800 
                                       rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 
                                       transition ease-in-out duration-150">
                            {{ Auth::user()->name }}
                        </button>
                    </x-slot>

                    <x-slot name="content">

                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>

                    </x-slot>
                </x-dropdown>

            </div>

        </div>
    </div>
</nav>