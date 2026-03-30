<nav x-data="{ open: false }"
     class="bg-white border-b border-gray-200 shadow-sm">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center h-16">

            {{-- LEFT SECTION --}}
            <div class="flex items-center gap-4">

                {{-- MOBILE MENU BUTTON --}}
                <button
                    @click="open = ! open"
                    class="sm:hidden text-gray-600 hover:text-gray-900 focus:outline-none">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>

                    </svg>

                </button>

                {{-- LOGO --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3">

                    <img src="/images/logo.png" alt="Logo BPS" class="h-10 w-auto">

                    <span class="font-semibold text-gray-700 text-sm tracking-wide hidden md:block">
                        SINTAK
                    </span>

                </a>

            </div>



            {{-- RIGHT SECTION --}}
            <div class="hidden sm:flex sm:items-center">

                <x-dropdown align="right" width="48">

                    <x-slot name="trigger">

                        <button
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white hover:text-blue-600 focus:outline-none transition">

                            {{ Auth::user()->name }}

                            <svg class="ml-2 h-4 w-4"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>

                            </svg>

                        </button>

                    </x-slot>



                    <x-slot name="content">

                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST"
                              action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault();
                                this.closest('form').submit();">

                                Log Out

                            </x-dropdown-link>

                        </form>

                    </x-slot>

                </x-dropdown>

            </div>

        </div>

    </div>



    {{-- MOBILE MENU --}}
    <div x-show="open"
         x-transition
         class="sm:hidden border-t border-gray-200 bg-white">

        <div class="px-4 py-3 space-y-2">

            <a href="{{ route('dashboard') }}"
               class="block text-sm text-gray-700 hover:text-blue-600">
                Dashboard
            </a>

            <a href="{{ route('profile.edit') }}"
               class="block text-sm text-gray-700 hover:text-blue-600">
                Profile
            </a>

            <form method="POST"
                  action="{{ route('logout') }}">
                @csrf

                <button
                    class="block w-full text-left text-sm text-red-500 hover:text-red-700">

                    Logout

                </button>

            </form>

        </div>

    </div>

</nav>