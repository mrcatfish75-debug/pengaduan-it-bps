@extends('layouts.pegawai')

@section('content')

<div class="space-y-8">

    {{-- ================= STATISTIK DASHBOARD ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        {{-- TOTAL --}}
        <div class="bg-blue-100 border border-blue-200 p-6 rounded-2xl shadow-sm">
            <p class="text-sm text-gray-600">Total Laporan</p>
            <h2 class="text-3xl font-bold text-blue-700 mt-2">
                {{ $total }}
            </h2>
        </div>

        {{-- PROSES --}}
        <div class="bg-yellow-100 border border-yellow-200 p-6 rounded-2xl shadow-sm">
            <p class="text-sm text-gray-600">Sedang Diproses</p>
            <h2 class="text-3xl font-bold text-yellow-700 mt-2">
                {{ $proses }}
            </h2>
        </div>

        {{-- SELESAI --}}
        <div class="bg-green-100 border border-green-200 p-6 rounded-2xl shadow-sm">
            <p class="text-sm text-gray-600">Selesai</p>
            <h2 class="text-3xl font-bold text-green-700 mt-2">
                {{ $selesai }}
            </h2>
        </div>

        {{-- DITOLAK --}}
        <div class="bg-red-100 border border-red-200 p-6 rounded-2xl shadow-sm">
            <p class="text-sm text-gray-600">Ditolak</p>
            <h2 class="text-3xl font-bold text-red-700 mt-2">
                {{ $ditolak }}
            </h2>
        </div>

    </div>



    {{-- ================= LAPORAN TERBARU ================= --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200">

        <div class="p-6 border-b border-gray-200">

            <h2 class="text-lg font-semibold text-gray-800">
                Laporan Terbaru
            </h2>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-100 text-gray-700">

                    <tr>

                        <th class="p-4 text-center whitespace-nowrap">ID</th>
                        <th class="p-4 text-center whitespace-nowrap">Pelapor</th>
                        <th class="p-4 text-center whitespace-nowrap">NUP</th>
                        <th class="p-4 text-center whitespace-nowrap">Barang</th>
                        <th class="p-4 text-center whitespace-nowrap">Kerusakan</th>
                        <th class="p-4 text-center whitespace-nowrap">Status</th>
                        <th class="p-4 text-center whitespace-nowrap">Laporan Dibuat</th>
                        <th class="p-4 text-center whitespace-nowrap">Laporan Selesai</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($laporan as $item)

                        <tr class="border-b hover:bg-gray-50">

                            <td class="p-4 text-center whitespace-nowrap">
                                {{ $item->id_laporan }}
                            </td>

                            <td class="p-4 whitespace-nowrap">
                                <div class="font-semibold">
                                    {{ $item->user->name ?? '-' }}
                                </div>
                            </td>

                            <td class="p-4 text-center whitespace-nowrap">
                                <div class="font-semibold">
                                    {{ $item->barang?->nup ?? '-' }}
                                </div>
                            </td>

                            <td class="p-4 whitespace-nowrap">
                                <div class="font-semibold">
                                    {{ $item->barang->nama_barang ?? '-' }}
                                </div>
                            </td>

                            <td class="p-4 text-center whitespace-nowrap">
                                {{ $item->jenis_kerusakan }}
                            </td>

                            <td class="p-4 text-center font-semibold whitespace-nowrap">

                                @switch($item->status_laporan)

                                    @case('MENUNGGU_REVIEW_ADMIN')
                                        <span class="text-yellow-600">Menunggu Admin</span>
                                    @break

                                    @case('MENUNGGU_KEPUTUSAN_KASUBAG')
                                        <span class="text-blue-600">Menunggu Kasubag</span>
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

                            <td class="p-4 text-center whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}
                            </td>

                            <td class="p-4 text-center whitespace-nowrap">

                                @if($item->tanggal_selesai)
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y H:i') }}
                                @else
                                    -
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center p-8 text-gray-500">
                                Belum ada laporan
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection