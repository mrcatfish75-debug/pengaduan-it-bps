<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between w-full">

            <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                LAPORAN
            </h2>

        </div>

    </x-slot>


    <div class="p-6">


        {{-- ================= FILTER ================= --}}
        <form method="GET"
              class="flex flex-col md:flex-row md:items-center gap-3 mb-6">

            <select name="status"
                    class="px-3 py-2 text-sm border rounded w-full md:w-auto">

                <option value="">Semua Status</option>

                <option value="MENUNGGU_REVIEW_ADMIN"
                    {{ request('status') === 'MENUNGGU_REVIEW_ADMIN' ? 'selected' : '' }}>
                    Menunggu Review Admin
                </option>

                <option value="MENUNGGU_KEPUTUSAN_KASUBAG"
                    {{ request('status') === 'MENUNGGU_KEPUTUSAN_KASUBAG' ? 'selected' : '' }}>
                    Menunggu Keputusan Kasubag
                </option>

                <option value="DIKIRIM_VENDOR"
                    {{ request('status') === 'DIKIRIM_VENDOR' ? 'selected' : '' }}>
                    Dikirim ke Vendor
                </option>

                <option value="MENUNGGU_PENGADAAN"
                    {{ request('status') === 'MENUNGGU_PENGADAAN' ? 'selected' : '' }}>
                    Menunggu Pengadaan
                </option>

                <option value="SELESAI"
                    {{ request('status') === 'SELESAI' ? 'selected' : '' }}>
                    Selesai
                </option>

                <option value="DITOLAK"
                    {{ request('status') === 'DITOLAK' ? 'selected' : '' }}>
                    Ditolak
                </option>

            </select>


            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari NUP / Nama Barang..."
                   class="px-3 py-2 text-sm border rounded w-full md:w-64">


            <button type="submit"
                    class="px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                Filter
            </button>


            <a href="{{ route('admin.laporan') }}"
               class="px-4 py-2 text-sm text-white bg-gray-500 rounded hover:bg-gray-600">
                Reset
            </a>

        </form>



        {{-- ================= TABEL ================= --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">

            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    <thead class="bg-gray-200 text-gray-800 sticky top-0">

                        <tr>

                            <th class="p-3 text-center whitespace-nowrap">ID</th>
                            <th class="p-3 text-center whitespace-nowrap">Pelapor</th>
                            <th class="p-3 text-center whitespace-nowrap">Barang</th>
                            <th class="p-3 text-center whitespace-nowrap">Jenis</th>
                            <th class="p-3 text-center whitespace-nowrap">Deskripsi</th>
                            <th class="p-3 text-center whitespace-nowrap">Prioritas</th>
                            <th class="p-3 text-center whitespace-nowrap">Laporan Dibuat</th>
                            <th class="p-3 text-center whitespace-nowrap">Laporan Selesai</th>
                            <th class="p-3 text-center whitespace-nowrap">Status</th>
                            <th class="p-3 text-center whitespace-nowrap">Aksi</th>

                        </tr>

                    </thead>


                    <tbody class="text-gray-800">

                        @forelse ($laporan as $item)

                            <tr class="border-b hover:bg-gray-50 align-top">

                                <td class="p-3 text-center whitespace-nowrap">
                                    {{ $item->id_laporan }}
                                </td>

                                <td class="p-3 whitespace-nowrap">
                                    {{ $item->user?->name ?? '-' }}
                                </td>

                                <td class="p-3">

                                    <div class="font-semibold">
                                        {{ $item->barang?->nup ?? '-' }}
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        {{ $item->barang?->nama_barang ?? '' }}
                                    </div>

                                    <div class="text-xs text-gray-400">
                                        {{ $item->barang?->lokasi_ruang ?? '' }}
                                    </div>

                                </td>

                                <td class="p-3 text-center whitespace-nowrap">
                                    {{ $item->jenis_kerusakan }}
                                </td>

                                <td class="p-3 max-w-xs break-words">
                                    {{ $item->deskripsi_keluhan }}
                                </td>

                                <td class="p-3 text-center">

                                    <span class="
                                        px-3 py-1 rounded-full text-xs font-semibold
                                        @if($item->prioritas === 'TINGGI') bg-red-100 text-red-700
                                        @elseif($item->prioritas === 'SEDANG') bg-yellow-100 text-yellow-700
                                        @else bg-green-100 text-green-700
                                        @endif">

                                        {{ $item->prioritas }}

                                    </span>

                                </td>

                                <td class="p-3 text-center whitespace-nowrap">
                                    {{ $item->created_at?->format('d M Y H:i') }}
                                </td>

                                <td class="p-3 text-center whitespace-nowrap">

                                    @if($item->tanggal_selesai)

                                        {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y H:i') }}

                                    @else

                                        -

                                    @endif

                                </td>

                                <td class="p-3 font-semibold text-center whitespace-nowrap">

                                    @switch($item->status_laporan)

                                        @case('MENUNGGU_REVIEW_ADMIN')
                                            <span class="text-yellow-600">Menunggu Review Admin</span>
                                        @break

                                        @case('MENUNGGU_KEPUTUSAN_KASUBAG')
                                            <span class="text-blue-600">Menunggu Keputusan Kasubag</span>
                                        @break

                                        @case('DIKIRIM_VENDOR')
                                            <span class="text-purple-600">Sedang Diservis</span>
                                        @break

                                        @case('MENUNGGU_PENGADAAN')
                                            <span class="text-indigo-600">Menunggu Pengadaan</span>
                                        @break

                                        @case('SELESAI')
                                            <span class="text-green-600">Selesai</span>
                                        @break

                                        @case('DITOLAK')
                                            <span class="text-red-600">Ditolak</span>
                                        @break

                                    @endswitch

                                </td>


                                <td class="p-3 space-y-2 text-center">

                                    <a href="{{ route('admin.laporan.show', $item->id_laporan) }}"
                                    class="block px-3 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                                        Detail
                                    </a>

                                    <a href="{{ route('admin.laporan.edit', $item->id_laporan) }}"
                                    class="block px-3 py-1 text-sm text-white bg-indigo-500 rounded hover:bg-indigo-600">
                                        Edit
                                    </a>

                                    {{-- TOMBOL SELESAIKAN --}}
                                    @if($item->status_laporan === 'DIKIRIM_VENDOR' || $item->status_laporan === 'MENUNGGU_PENGADAAN')

                                        <form method="POST"
                                            action="{{ route('admin.laporan.selesai', $item->id_laporan) }}">

                                            @csrf

                                            <button type="submit"
                                                    class="w-full px-3 py-1 text-sm text-white bg-green-600 rounded hover:bg-green-700">

                                                Selesaikan

                                            </button>

                                        </form>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="10"
                                    class="p-6 text-center text-gray-500">

                                    Belum ada laporan masuk

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="mt-6">

            {{ $laporan->links() }}

        </div>

    </div>

</x-app-layout>