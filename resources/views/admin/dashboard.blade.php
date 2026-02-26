<x-app-layout>

<div class="max-w-7xl mx-auto px-6 lg:px-10 py-8">

    {{-- ================= HEADER ================= --}}
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
            Dashboard Admin IT
        </h1>

        <p class="text-gray-500 dark:text-gray-400 mt-1">
            Monitoring laporan kerusakan dan manajemen aset BMN IT
        </p>
    </div>


    {{-- ================= SUMMARY ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-12">

        <div class="bg-blue-600 text-white p-6 rounded-xl shadow">
            <p class="text-sm opacity-80">Total Laporan</p>
            <h2 class="text-3xl font-bold mt-2">{{ $total ?? 0 }}</h2>
        </div>

        <div class="bg-yellow-500 text-white p-6 rounded-xl shadow">
            <p class="text-sm opacity-80">Menunggu Review Admin</p>
            <h2 class="text-3xl font-bold mt-2">{{ $menungguAdmin ?? 0 }}</h2>
        </div>

        <div class="bg-indigo-500 text-white p-6 rounded-xl shadow">
            <p class="text-sm opacity-80">Menunggu Kasubag</p>
            <h2 class="text-3xl font-bold mt-2">{{ $menungguKasubag ?? 0 }}</h2>
        </div>

        <div class="bg-green-600 text-white p-6 rounded-xl shadow">
            <p class="text-sm opacity-80">Selesai</p>
            <h2 class="text-3xl font-bold mt-2">{{ $selesai ?? 0 }}</h2>
        </div>

        <div class="bg-red-600 text-white p-6 rounded-xl shadow">
            <p class="text-sm opacity-80">Ditolak</p>
            <h2 class="text-3xl font-bold mt-2">{{ $ditolak ?? 0 }}</h2>
        </div>

    </div>


    {{-- ================= QUICK ACTION ================= --}}
    <div class="bg-gray-800 rounded-xl shadow p-6 mb-12">

        <h2 class="text-lg font-semibold text-white mb-4">
            Quick Action
        </h2>

        <div class="flex flex-wrap gap-4">

            <a href="{{ route('admin.laporan') }}"
               class="bg-blue-600 hover:bg-blue-700 transition text-white px-6 py-3 rounded-lg shadow">
                📋 Laporan Masuk
            </a>

            <a href="{{ route('admin.activity-log') }}"
               class="bg-gray-600 hover:bg-gray-700 transition text-white px-6 py-3 rounded-lg shadow">
                🧾 Activity Log
            </a>

        </div>

    </div>


    {{-- ================= IMPORT BARANG ================= --}}
    <div class="bg-gray-800 rounded-xl shadow p-6">

        <h2 class="text-xl font-semibold text-white mb-6">
            Import Master Barang (Excel BMN)
        </h2>

        {{-- SUCCESS --}}
        @if(session('success'))
            <div class="bg-green-600 text-white px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR --}}
        @if($errors->any())
            <div class="bg-red-600 text-white px-4 py-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form
            action="{{ route('import.barang') }}"
            method="POST"
            enctype="multipart/form-data"
            class="flex flex-col md:flex-row gap-4 items-start md:items-center"
        >
            @csrf

            <input
                type="file"
                name="file"
                required
                class="block w-full text-sm text-gray-300
                       file:mr-4
                       file:py-2
                       file:px-4
                       file:rounded-lg
                       file:border-0
                       file:text-sm
                       file:font-semibold
                       file:bg-indigo-600
                       file:text-white
                       hover:file:bg-indigo-700"
            >

            <button
                type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg shadow transition">
                Upload Excel
            </button>

        </form>

        <p class="text-gray-400 text-sm mt-4">
            Upload file Excel BMN aktif untuk memperbarui daftar barang pegawai.
        </p>

    </div>

</div>

</x-app-layout>