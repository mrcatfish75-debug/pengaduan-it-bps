<x-app-layout>

<div class="max-w-7xl mx-auto p-6">

    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">
        Laporan Saya
    </h2>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">

        <table class="min-w-full text-sm">
            
            <thead class="bg-gray-100 dark:bg-gray-700 
                           text-gray-700 dark:text-gray-200 
                           uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-4 text-left">Jenis</th>
                    <th class="px-6 py-4 text-left">Prioritas</th>
                    <th class="px-6 py-4 text-left">Tanggal</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-left">Hasil Penanganan</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

            @forelse($laporan as $item)

                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                    {{-- JENIS --}}
                    <td class="px-6 py-4 font-medium text-gray-800 dark:text-gray-100">
                        {{ $item->jenis_kerusakan }}
                    </td>

                    {{-- PRIORITAS --}}
                    <td class="px-6 py-4">
                        <span class="
                            px-3 py-1 rounded-full text-xs font-semibold
                            @if($item->prioritas === 'High') bg-red-500 text-white
                            @elseif($item->prioritas === 'Normal') bg-yellow-400 text-black
                            @else bg-green-500 text-white
                            @endif">
                            {{ $item->prioritas }}
                        </span>
                    </td>

                    {{-- TANGGAL --}}
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($item->tanggal_lapor)->format('d M Y') }}
                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-4">

                        <span class="
                            px-3 py-1 rounded-full text-xs font-semibold
                            @if(in_array($item->status_laporan,
                                ['MENUNGGU_REVIEW_ADMIN','MENUNGGU_KEPUTUSAN_KASUBAG']))
                                bg-yellow-500 text-black
                            @elseif($item->status_laporan === 'SELESAI')
                                bg-green-600 text-white
                            @else
                                bg-red-600 text-white
                            @endif
                        ">

                        @if(in_array($item->status_laporan,
                            ['MENUNGGU_REVIEW_ADMIN','MENUNGGU_KEPUTUSAN_KASUBAG']))
                            Sedang Diproses
                        @elseif($item->status_laporan === 'SELESAI')
                            Selesai
                        @else
                            Ditolak
                        @endif

                        </span>

                    </td>

                    {{-- HASIL PENANGANAN --}}
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">

                        @if($item->status_laporan === 'MENUNGGU_REVIEW_ADMIN')
                            Menunggu pemeriksaan Tim IT

                        @elseif($item->status_laporan === 'MENUNGGU_KEPUTUSAN_KASUBAG')
                            Dalam persetujuan Kasubag

                        @elseif($item->status_laporan === 'DITOLAK')
                            Laporan ditolak

                        @elseif($item->status_laporan === 'SELESAI')

                            @switch($item->status_kasubag)

                                @case('DISETUJUI_SERVIS_INTERNAL')
                                    Sedang diperbaiki (Internal)
                                    @break

                                @case('DISETUJUI_SERVIS_EKSTERNAL')
                                    Service Eksternal
                                    @break

                                @case('DISETUJUI_GANTI_BARU')
                                    Diganti Baru
                                    @break

                                @default
                                    Selesai
                            @endswitch

                        @else
                            -
                        @endif

                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="5"
                        class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        Belum ada laporan yang dibuat.
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $laporan->links() }}
    </div>

</div>

</x-app-layout>