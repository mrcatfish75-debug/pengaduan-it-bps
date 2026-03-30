<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between w-full">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                DASHBOARD
            </h2>

        </div>

    </x-slot>


    <div class="max-w-7xl mx-auto p-6">


        {{-- ================= CARD SUMMARY ================= --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-10">

            @php

                $cards = [

                    ['label' => 'Total Laporan', 'value' => $total ?? 0, 'bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                    ['label' => 'Menunggu Admin', 'value' => $menungguAdmin ?? 0, 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                    ['label' => 'Menunggu Kasubag', 'value' => $menungguKasubag ?? 0, 'bg' => 'bg-indigo-100', 'text' => 'text-indigo-800'],
                    ['label' => 'Selesai', 'value' => $selesai ?? 0, 'bg' => 'bg-green-100', 'text' => 'text-green-800'],
                    ['label' => 'Ditolak', 'value' => $ditolak ?? 0, 'bg' => 'bg-red-100', 'text' => 'text-red-800'],

                ];

            @endphp


            @foreach ($cards as $card)

                <div class="{{ $card['bg'] }} p-6 rounded-xl shadow-sm hover:shadow-md transition">

                    <p class="text-sm opacity-70">
                        {{ $card['label'] }}
                    </p>

                    <h2 class="text-3xl font-bold mt-2 {{ $card['text'] }}">
                        {{ $card['value'] }}
                    </h2>

                </div>

            @endforeach

        </div>



        {{-- ================= TABEL LAPORAN ================= --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">


            <div class="px-6 py-4 border-b border-gray-200">

                <h2 class="text-lg font-semibold text-gray-700 text-center">
                    Laporan Terbaru
                </h2>

            </div>


            <div class="overflow-x-auto max-h-[500px]">


                <table class="min-w-full text-sm text-left">

                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs sticky top-0 z-10">

                        <tr>

                            <th class="p-3 text-center whitespace-nowrap">ID</th>
                            <th class="p-3 text-center whitespace-nowrap">Pelapor</th>
                            <th class="p-3 text-center whitespace-nowrap">NUP</th>
                            <th class="p-3 text-center whitespace-nowrap">Barang</th>
                            <th class="p-3 text-center whitespace-nowrap">Jenis</th>
                            <th class="p-3 text-center whitespace-nowrap">Deskripsi</th>
                            <th class="p-3 text-center whitespace-nowrap">Prioritas</th>
                            <th class="p-3 text-center whitespace-nowrap">Laporan Dibuat</th>
                            <th class="p-3 text-center whitespace-nowrap">Laporan Selesai</th>
                            <th class="p-3 text-center whitespace-nowrap">Status</th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse ($laporan as $item)

                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-4 py-3 font-semibold text-center whitespace-nowrap">
                                    {{ $item->id_laporan }}
                                </td>

                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $item->user->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    {{ $item->barang->nup ?? '-' }}
                                </td>

                                <td class="px-4 py-3">

                                    <div class="font-semibold">
                                        {{ $item->barang->nama_barang ?? '-' }}
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        {{ $item->barang->lokasi_ruang ?? '-' }}
                                    </div>

                                </td>

                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    {{ $item->jenis_kerusakan }}
                                </td>

                                <td class="px-4 py-3 max-w-xs break-words">
                                    {{ $item->deskripsi_keluhan }}
                                </td>

                                <td class="px-4 py-3 text-center whitespace-nowrap">

                                    <span class="
                                        px-3 py-1 rounded-full text-xs font-semibold
                                        @if($item->prioritas === 'TINGGI') bg-red-100 text-red-700
                                        @elseif($item->prioritas === 'SEDANG') bg-yellow-100 text-yellow-700
                                        @else bg-green-100 text-green-700
                                        @endif">

                                        {{ $item->prioritas }}

                                    </span>

                                </td>

                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    {{ $item->created_at?->format('d M Y H:i') }}
                                </td>

                                <td class="px-4 py-3 text-center whitespace-nowrap">

                                    @if ($item->tanggal_selesai)

                                        {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y H:i') }}

                                    @else

                                        -

                                    @endif

                                </td>

                                <td class="px-4 py-3 font-semibold whitespace-nowrap">

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

                                <td colspan="10" class="px-6 py-10 text-center text-gray-500">
                                    Belum ada laporan dibuat.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-app-layout>