<x-app-layout>

<x-slot name="header">

<div class="flex items-center justify-between w-full">

<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Dashboard
</h2>

</div>

</x-slot>

<div class="p-6 max-w-7xl mx-auto">

{{-- SUMMARY --}}
<div class="grid grid-cols-3 gap-6 mb-6">

<div class="bg-yellow-100 p-4 rounded shadow">
<h3 class="text-sm text-gray-600">Menunggu Keputusan</h3>
<p class="text-2xl font-bold">{{ $menunggu }}</p>
</div>

<div class="bg-green-100 p-4 rounded shadow">
<h3 class="text-sm text-gray-600">Selesai</h3>
<p class="text-2xl font-bold">{{ $selesai }}</p>
</div>

<div class="bg-red-100 p-4 rounded shadow">
<h3 class="text-sm text-gray-600">Ditolak</h3>
<p class="text-2xl font-bold">{{ $ditolak }}</p>
</div>

</div>


{{-- LAPORAN TERBARU --}}

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">

            <h2 class="text-lg font-semibold text-gray-700 text-center">
                Laporan Terbaru
            </h2>

        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm table-auto bg-white rounded shadow">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="p-3 text-center">ID</th>
                        <th class="p-3 text-center">Pelapor</th>
                        <th class="p-3 text-center">NUP</th>
                        <th class="p-3 text-center">Barang</th>
                        <th class="p-3 text-center">Jenis</th>
                        <th class="p-3 text-center">Deskripsi</th>
                        <th class="p-3 text-center">Prioritas</th>
                        <th class="p-3 text-center">Laporan Dibuat</th>
                        <th class="p-3 text-center">Laporan Selesai</th>
                        <th class="p-3 text-center">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                @forelse($laporan as $item)

                    <tr class="hover:bg-gray-50 transition">

                        {{-- ID --}}
                        <td class="px-6 py-4 font-semibold text-gray-700">
                            {{ $item->id_laporan }}
                        </td>

                        {{-- PELAPOR --}}
                        <td class="px-6 py-4 font-semibold text-gray-700">
                            {{ $item->user->name ?? '-' }}
                        </td>

                        {{-- NUP --}}
                        <td class="px-6 py-4 font-semibold text-gray-700 text-center">
                            {{ $item->barang->nup ?? '-' }}
                        </td>

                        {{-- BARANG --}}
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">
                                {{ $item->barang->nama_barang ?? '-' }}
                            </div>  
                            <div class="text-xs text-gray-500">
                                {{ $item->barang->lokasi_ruang ?? '-' }}

                        </td>

                        {{-- JENIS --}}
                        <td class="px-6 py-4 text-gray-700 text-center">
                            {{ $item->jenis_kerusakan }}
                        </td>

                        {{-- DESKRIPSI --}}
                        <td class="p-3 max-w-xs break-words text-justify">
                            {{ $item->deskripsi_keluhan }}
                        </td>


                        {{-- PRIORITAS --}}
                        <td class="px-6 py-4 text-center">
                            <span class="
                                px-3 py-1 rounded-full text-xs font-semibold
                                @if($item->prioritas === 'TINGGI') bg-red-100 text-red-700
                                @elseif($item->prioritas === 'SEDANG') bg-yellow-100 text-yellow-700
                                @else bg-green-100 text-green-700
                                @endif">
                                {{ $item->prioritas }}
                            </span>
                        </td>

                        {{-- LAPORAN DIBUAT --}}
                        <td class="p-3 text-center">
                        {{ $item->created_at ? $item->created_at->format('d M Y H:i') : '-' }}
                        </td>

                        {{-- LAPORAN SELESAI --}}
                        <td class="p-3 text-center">

                        @if($item->tanggal_selesai)
                        {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y H:i') }}
                        @else
                        -
                        @endif

                        </td>

                        {{-- STATUS --}}
                        <td class="p-3 font-semibold text-center">

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

                       
                    </tr>

                @empty
                    <tr>
                        <td colspan="7"
                            class="px-6 py-10 text-center text-gray-500">
                            Belum ada laporan dibuat.
                        </td>
                    </tr>
                @endforelse

                </tbody>
</table>

</div>

</div>

</x-app-layout>