<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pengaduan IT BPS') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="bg-gray-100 font-sans antialiased overflow-x-hidden">


<div class="min-h-screen flex">


    {{-- ================= MOBILE SIDEBAR OVERLAY ================= --}}
    <div id="sidebarOverlay"
         class="fixed inset-0 bg-black bg-opacity-40 hidden z-40 lg:hidden"></div>



    {{-- ================= SIDEBAR ================= --}}
    <aside id="sidebar"
           class="fixed top-0 left-0 h-full w-72 bg-gray-800 text-white flex flex-col shadow-lg
           transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-50">


        {{-- LOGO --}}
        <div class="p-5 border-b border-gray-600 text-center">

            <img src="/images/logo.png"
                alt="Logo BPS"
                class="h-24 w-24 mx-auto mb-2">

        </div>


        {{-- MENU --}}
        <nav class="flex-1 px-4 py-6 space-y-2">

            @auth

                @if(auth()->user()->role === 'admin')

                    <x-sidebar-link route="admin.dashboard" label="Dashboard" />
                    <x-sidebar-link route="admin.laporan" label="Laporan" />
                    <x-sidebar-link route="admin.pengguna" label="Pengguna" />
                    <x-sidebar-link route="admin.activity-log" label="Activity Log" />

                @elseif(auth()->user()->role === 'kasubag')

                    <x-sidebar-link route="kasubag.dashboard" label="Dashboard" />
                    <x-sidebar-link route="kasubag.hasil" label="Laporan" />
                    <x-sidebar-link route="kasubag.barang" label="Barang" />

                @endif

            @endauth

        </nav>



        {{-- USER PROFILE --}}
        @auth

        <div class="border-t border-gray-700 p-5 bg-gray-900">

            <div class="flex items-center gap-3 mb-4">

                <div class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center text-lg font-bold">

                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}

                </div>

                <div>

                    <p class="font-semibold text-sm">

                        {{ auth()->user()->name }}

                    </p>

                    <span class="text-xs bg-gray-700 px-2 py-1 rounded">

                        {{ auth()->user()->role }}

                    </span>

                </div>

            </div>


            <form method="POST" action="{{ route('logout') }}">

                @csrf

                <button type="submit"
                        class="w-full flex items-center justify-center gap-2
                               bg-red-600 hover:bg-red-500 transition
                               text-sm py-2 rounded">

                    LOGOUT

                </button>

            </form>

        </div>

        @endauth

    </aside>



    {{-- ================= MAIN CONTENT ================= --}}
    <div class="flex-1 flex flex-col lg:pl-72 min-h-screen">


        {{-- HEADER --}}
        <header class="bg-white shadow px-6 py-4 flex items-center justify-between sticky top-0 z-30">


            {{-- LEFT HEADER --}}
            <div class="flex items-center gap-4">


                {{-- MOBILE SIDEBAR BUTTON --}}
                <button id="menuToggle"
                        class="text-gray-600 hover:text-gray-900 lg:hidden focus:outline-none">

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


                <h2 class="text-xl font-semibold text-gray-700">

                    {{ $header ?? '' }}

                </h2>

            </div>



            {{-- RIGHT HEADER --}}
            <div class="flex items-center gap-4">


                {{-- NOTIFICATION --}}
                <div class="relative">

                    <button id="notifButton"
                            class="relative text-gray-500 hover:text-gray-700 focus:outline-none">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M15 17h5l-1.405-1.405C18.79 15.21 18 13.11 18 11V8a6 6 0 10-12 0v3c0 2.11-.79 4.21-1.595 5.595L3 17h5m4 0a3 3 0 11-6 0"/>

                        </svg>


                        @if(isset($notifikasi) && $notifikasi->count() > 0)

                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1">

                                {{ $notifikasi->count() }}

                            </span>

                        @endif

                    </button>


                    <div id="notifDropdown"
                         class="hidden absolute right-0 mt-2 w-72 bg-white border rounded-lg shadow-lg z-50 max-h-96 overflow-y-auto">


                        <div class="p-3 font-semibold border-b">

                            Notifikasi

                        </div>


                        @forelse($notifikasi ?? [] as $n)

                            @php
                                $routeDetail = '#';

                                if(auth()->check()){
                                    if(auth()->user()->role === 'admin'){
                                        $routeDetail = route('admin.laporan.show',$n->id_laporan);
                                    }
                                    elseif(auth()->user()->role === 'kasubag'){
                                        $routeDetail = route('kasubag.laporan.show',$n->id_laporan);
                                    }
                                }
                            @endphp


                            <a href="{{ $routeDetail }}"
                               class="block px-4 py-2 hover:bg-gray-100 text-sm">

                                <b>Laporan #{{ $n->id_laporan }}</b>

                                <div class="text-xs text-gray-500">

                                    {{ $n->status_laporan }}

                                </div>

                            </a>

                        @empty

                            <div class="p-4 text-sm text-gray-500">

                                Tidak ada notifikasi

                            </div>

                        @endforelse

                    </div>

                </div>


                <span class="text-sm text-gray-500">

                    {{ now()->format('d F Y') }}

                </span>

            </div>

        </header>



        {{-- PAGE CONTENT --}}
        <main class="p-8 flex-1">

            {{ $slot }}

        </main>


    </div>

</div>



{{-- ================= SCRIPT ================= --}}
<script>

document.addEventListener("DOMContentLoaded", function(){

    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");
    const toggle  = document.getElementById("menuToggle");

    if(toggle){

        toggle.addEventListener("click", function(){

            sidebar.classList.toggle("-translate-x-full");
            overlay.classList.toggle("hidden");

        });

        overlay.addEventListener("click", function(){

            sidebar.classList.add("-translate-x-full");
            overlay.classList.add("hidden");

        });

    }

});


document.addEventListener("DOMContentLoaded", function(){

    const notifButton  = document.getElementById("notifButton");
    const notifDropdown = document.getElementById("notifDropdown");

    if(notifButton){

        notifButton.addEventListener("click", function(e){

            e.stopPropagation();
            notifDropdown.classList.toggle("hidden");

        });

        document.addEventListener("click", function(event){

            if(!notifDropdown.contains(event.target) &&
               !notifButton.contains(event.target)){

                notifDropdown.classList.add("hidden");

            }

        });

    }

});

</script>


</body>
</html>