<x-app-layout>
<div class="p-6 max-w-5xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">
        Detail Laporan #{{ $laporan->id_laporan }}
    </h1>

    <div class="bg-white p-6 rounded shadow space-y-6">

        {{-- ================= DATA PELAPOR ================= --}}
        <div>
            <h2 class="font-semibold text-lg mb-2">Data Pelapor</h2>
            <p><b>Nama :</b> {{ $laporan->user->name }}</p>
            <p><b>Email :</b> {{ $laporan->user->email }}</p>
        </div>

        <hr>

        {{-- ================= DATA BARANG ================= --}}
        <div>
            <h2 class="font-semibold text-lg mb-2">Data Barang</h2>
            <p><b>NUP :</b> {{ $laporan->barang->nup }}</p>
            <p><b>Nama Barang :</b> {{ $laporan->barang->nama_barang }}</p>
            <p><b>Lokasi :</b> {{ $laporan->barang->lokasi_ruang }}</p>
            <p><b>Kondisi Saat Ini :</b> {{ $laporan->barang->kondisi }}</p>
        </div>

        <hr>

        {{-- ================= DETAIL LAPORAN ================= --}}
        <div>
            <h2 class="font-semibold text-lg mb-2">Detail Laporan</h2>
            <p><b>Jenis Kerusakan :</b> {{ $laporan->jenis_kerusakan }}</p>
            <p><b>Prioritas :</b> {{ $laporan->prioritas }}</p>
            <p><b>Tanggal Lapor :</b> {{ $laporan->tanggal_lapor }}</p>

            <div class="bg-gray-100 p-4 rounded mt-3">
                <b>Deskripsi:</b>
                <p class="mt-2">{{ $laporan->deskripsi_keluhan }}</p>
            </div>
        </div>

        <hr>

        {{-- ================= STATUS & TIMELINE ================= --}}
        <div>
            <h2 class="font-semibold text-lg mb-2">Status & Timeline</h2>

            <p><b>Status Saat Ini :</b>
                <span class="font-bold 
                    @if($laporan->status_laporan === 'SELESAI') text-green-600
                    @elseif($laporan->status_laporan === 'DITOLAK') text-red-600
                    @elseif($laporan->status_laporan === 'MENUNGGU_KEPUTUSAN_KASUBAG') text-blue-600
                    @else text-yellow-600
                    @endif">
                    {{ $laporan->status_laporan }}
                </span>
            </p>

            <p><b>Tanggal Verifikasi Admin :</b>
                {{ $laporan->tanggal_verifikasi_admin ?? '-' }}
            </p>

            <p><b>Rekomendasi Admin :</b>
                {{ $laporan->status_admin ?? '-' }}
            </p>

            <p><b>Catatan Admin :</b>
                {{ $laporan->keputusan_admin ?? '-' }}
            </p>

            <p><b>Tanggal Keputusan Kasubag :</b>
                {{ $laporan->tanggal_keputusan_kasubag ?? '-' }}
            </p>

            <p><b>Keputusan Kasubag :</b>
                {{ $laporan->keputusan_kasubag ?? '-' }}
            </p>

        </div>

    </div>
</div>
</x-app-layout>