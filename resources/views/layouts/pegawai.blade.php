<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pegawai - Pengaduan IT BPS</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

</head>


<body class="bg-gray-50 font-sans antialiased">


    {{-- ================= NAVBAR ================= --}}
    <nav class="bg-white border-b border-gray-200 shadow-sm">

        <div class="max-w-7xl mx-auto px-6">

            <div class="flex items-center justify-between h-16">


                {{-- LEFT SIDE --}}
                <div class="flex items-center gap-6">

                    <a href="{{ route('pegawai.dashboard') }}"
                       class="text-lg font-bold text-gray-800">

                        Pengaduan IT

                    </a>


                    {{-- DESKTOP MENU --}}
                    <div class="hidden md:flex items-center gap-6">

                        <a href="{{ route('pegawai.lapor.create') }}"
                           class="text-sm transition
                           {{ request()->routeIs('pegawai.lapor.*') ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">

                            Buat Laporan

                        </a>


                        <a href="{{ route('pegawai.laporan_saya') }}"
                           class="text-sm transition
                           {{ request()->routeIs('pegawai.laporan_saya') ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">

                            Laporan Saya

                        </a>

                    </div>

                </div>



                {{-- RIGHT SIDE --}}
                <div class="flex items-center gap-4">


                    {{-- USER NAME (DESKTOP) --}}
                    <span class="hidden md:block text-sm text-gray-600">

                        {{ auth()->user()->name }}

                    </span>


                    {{-- LOGOUT (DESKTOP) --}}
                    <form method="POST"
                          action="{{ route('logout') }}"
                          class="hidden md:block">

                        @csrf

                        <button class="text-sm text-red-500 hover:text-red-700 transition">

                            Logout

                        </button>

                    </form>


                    {{-- HAMBURGER MENU (MOBILE) --}}
                    <button id="menuToggle"
                            class="md:hidden text-gray-700">

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

                </div>


            </div>

        </div>



        {{-- ================= MOBILE MENU ================= --}}
        <div id="mobileMenu"
             class="hidden md:hidden border-t border-gray-200 bg-white">

            <div class="px-6 py-4 space-y-3">


                <div class="text-sm text-gray-500">

                    {{ auth()->user()->name }}

                </div>


                <a href="{{ route('pegawai.lapor.create') }}"
                   class="block text-gray-700 hover:text-indigo-600">

                    Buat Laporan

                </a>


                <a href="{{ route('pegawai.laporan_saya') }}"
                   class="block text-gray-700 hover:text-indigo-600">

                    Laporan Saya

                </a>


                <form method="POST"
                      action="{{ route('logout') }}">

                    @csrf

                    <button class="text-red-500 hover:text-red-700 text-sm">

                        Logout

                    </button>

                </form>


            </div>

        </div>

    </nav>



    {{-- ================= CONTENT ================= --}}
    <main class="max-w-7xl mx-auto px-6 py-10">

        @yield('content')

    </main>



    {{-- ================= SCRIPT MOBILE MENU ================= --}}
    <script>

        document.addEventListener("DOMContentLoaded", function(){

            const toggle = document.getElementById("menuToggle");
            const menu   = document.getElementById("mobileMenu");

            if(toggle){

                toggle.addEventListener("click", function(){

                    menu.classList.toggle("hidden");

                });

            }

        });

    </script>


</body>
</html>