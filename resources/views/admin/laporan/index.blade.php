<x-app-layout>
    <div class="p-6">

        <h1 class="text-2xl font-bold mb-6 text-gray-800">
            Daftar Laporan Kerusakan IT
        </h1>

        {{-- SUCCESS ALERT --}}
        @if(session('success'))
            <div class="bg-green-500 text-white p-3 mb-4 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR ALERT --}}
        @if(session('error'))
            <div class="bg-red-500 text-white p-3 mb-4 rounded shadow">
                {{ session('error') }}
            </div>
        @endif


        {{-- ================= FILTER + SEARCH ================= --}}
        <form method="GET" class="mb-6 flex flex-wrap gap-3 items-center">

            <select name="status"
                class="border rounded px-3 py-2 text-sm">
                <option value="">Semua Status</option>

                <option value="MENUNGGU_REVIEW_ADMIN"
                    {{ request('status') == 'MENUNGGU_REVIEW_ADMIN' ? 'selected' : '' }}>
                    Menunggu Review Admin
                </option>

                <option value="MENUNGGU_KEPUTUSAN_KASUBAG"
                    {{ request('status') == 'MENUNGGU_KEPUTUSAN_KASUBAG' ? 'selected' : '' }}>
                    Menunggu Keputusan Kasubag
                </option>

                <option value="SELESAI"
                    {{ request('status') == 'SELESAI' ? 'selected' : '' }}>
                    Selesai
                </option>

                <option value="DITOLAK"
                    {{ request('status') == 'DITOLAK' ? 'selected' : '' }}>
                    Ditolak
                </option>
            </select>

            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari NUP Barang..."
                class="border rounded px-3 py-2 text-sm">

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                Filter
            </button>

           <a href="{{ route('admin.laporan') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
                Reset
            </a>

        </form>
        {{-- ===================================================== --}}


        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="table-auto w-full text-sm">

                <thead class="bg-gray-200 text-gray-800">
                    <tr>
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Pelapor</th>
                        <th class="p-3 text-left">Barang (NUP)</th>
                        <th class="p-3 text-left">Jenis</th>
                        <th class="p-3 text-left">Deskripsi</th>
                        <th class="p-3 text-left">Prioritas</th>
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-gray-800">

                @forelse($laporan as $item)
                    <tr class="border-b hover:bg-gray-50 align-top">

                        <td class="p-3">{{ $item->id_laporan }}</td>

                        <td class="p-3">
                            {{ $item->user->name ?? '-' }}
                        </td>

                        <td class="p-3">
                            <div class="font-semibold">
                                {{ $item->barang->nup ?? '-' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $item->barang->nama_barang ?? '' }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $item->barang->lokasi_ruang ?? '' }}
                            </div>
                        </td>

                        <td class="p-3">
                            {{ $item->jenis_kerusakan }}
                        </td>

                        <td class="p-3 max-w-xs whitespace-normal break-words">
                            {{ $item->deskripsi_keluhan }}
                        </td>

                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($item->prioritas == 'High') bg-red-500 text-white
                                @elseif($item->prioritas == 'Normal') bg-yellow-500 text-white
                                @else bg-gray-400 text-white
                                @endif">
                                {{ $item->prioritas }}
                            </span>
                        </td>

                        <td class="p-3">
                            {{ \Carbon\Carbon::parse($item->tanggal_lapor)->format('d M Y') }}
                        </td>

                        <td class="p-3 font-semibold">
                            @if($item->status_laporan === 'MENUNGGU_REVIEW_ADMIN')
                                <span class="text-yellow-600">
                                    Menunggu Review Admin
                                </span>
                            @elseif($item->status_laporan === 'MENUNGGU_KEPUTUSAN_KASUBAG')
                                <span class="text-blue-600">
                                    Menunggu Keputusan Kasubag
                                </span>
                            @elseif($item->status_laporan === 'SELESAI')
                                <span class="text-green-600">
                                    Selesai
                                </span>
                            @elseif($item->status_laporan === 'DITOLAK')
                                <span class="text-red-600">
                                    Ditolak
                                </span>
                            @endif
                        </td>

                        {{-- ================= KOLOM AKSI UPDATED ================= --}}
                        <td class="p-3 space-y-2">

                            {{-- TOMBOL DETAIL --}}
                            <a href="{{ route('admin.laporan.show', $item->id_laporan) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm block text-center">
                                Detail
                            </a>

                            {{-- FORM VERIFIKASI --}}
                            @if($item->status_laporan === 'MENUNGGU_REVIEW_ADMIN')

                                <form method="POST"
                                      action="{{ route('admin.laporan.verifikasi',$item->id_laporan) }}"
                                      class="space-y-2">
                                    @csrf

                                    <select name="status_admin"
                                            required
                                            class="w-full border rounded p-1 text-black text-sm">
                                        <option value="">-- Pilih Rekomendasi --</option>
                                        <option value="REKOMENDASI_SERVIS_INTERNAL">
                                            Servis Internal
                                        </option>
                                        <option value="REKOMENDASI_SERVIS_EKSTERNAL">
                                            Servis Eksternal
                                        </option>
                                        <option value="REKOMENDASI_GANTI_BARU">
                                            Ganti Baru
                                        </option>
                                        <option value="DITOLAK_ADMIN">
                                            Tolak Laporan
                                        </option>
                                    </select>

                                    <textarea name="keputusan_admin"
                                              class="w-full border rounded p-1 text-black text-sm"
                                              placeholder="Catatan admin (opsional)"></textarea>

                                    <button type="submit"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm w-full">
                                        Kirim ke Kasubag
                                    </button>
                                </form>

                            @elseif($item->status_laporan === 'SELESAI')

                                <span class="text-green-600 font-bold text-sm block text-center">
                                    ✔ Sudah Ditangani
                                </span>

                            @elseif($item->status_laporan === 'DITOLAK')

                                <span class="text-red-600 font-bold text-sm block text-center">
                                    ✖ Ditolak
                                </span>

                            @endif

                        </td>
                        {{-- ====================================================== --}}

                    </tr>

                @empty
                    <tr>
                        <td colspan="9"
                            class="text-center p-6 text-gray-500">
                            Belum ada laporan masuk
                        </td>
                    </tr>
                @endforelse

                </tbody>

            </table>
        </div>


        {{-- ================= PAGINATION ================= --}}
        <div class="mt-6">
            {{ $laporan->links() }}
        </div>
        {{-- ============================================= --}}

    </div>
</x-app-layout>