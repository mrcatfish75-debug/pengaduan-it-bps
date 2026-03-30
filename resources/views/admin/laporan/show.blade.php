<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                LAPORAN
            </h2>
        </div>
    </x-slot>

    <div class="max-w-5xl p-6 mx-auto">

        <h1 class="mb-6 text-2xl font-bold">
            Detail Laporan #{{ $laporan->id_laporan }}
        </h1>

        <div class="p-6 space-y-6 bg-white rounded shadow">

            {{-- DATA PELAPOR --}}
            <div>
                <h2 class="mb-2 text-lg font-semibold">Data Pelapor</h2>

                <p><b>Nama :</b> {{ $laporan->user->name }}</p>
                <p><b>Email :</b> {{ $laporan->user->email }}</p>
            </div>

            <hr>

            {{-- DATA BARANG --}}
            <div>
                <h2 class="mb-2 text-lg font-semibold">Data Barang</h2>

                <p><b>NUP :</b> {{ $laporan->barang->nup }}</p>
                <p><b>Nama Barang :</b> {{ $laporan->barang->nama_barang }}</p>
                <p><b>Lokasi :</b> {{ $laporan->barang->lokasi_ruang }}</p>
                <p><b>Kondisi :</b> {{ $laporan->barang->kondisi }}</p>
            </div>

            <hr>

            {{-- DETAIL LAPORAN --}}
            <div>
                <h2 class="mb-2 text-lg font-semibold">Detail Laporan</h2>

                <p><b>Jenis Kerusakan :</b> {{ $laporan->jenis_kerusakan }}</p>
                <p><b>Prioritas :</b> {{ $laporan->prioritas }}</p>
                <p><b>Tanggal Lapor :</b> {{ $laporan->tanggal_lapor }}</p>

                <div class="p-4 mt-3 bg-gray-100 rounded">
                    <b>Deskripsi:</b>
                    <p class="mt-2">
                        {{ $laporan->deskripsi_keluhan }}
                    </p>
                </div>
            </div>

            <hr>

            {{-- STATUS --}}
            <div>
                <h2 class="mb-2 text-lg font-semibold">Status & Timeline</h2>

                <p><b>Status :</b> {{ $laporan->status_laporan }}</p>
                <p><b>Rekomendasi Admin :</b> {{ $laporan->status_admin ?? '-' }}</p>
                <p><b>Catatan Admin :</b> {{ $laporan->keputusan_admin ?? '-' }}</p>
                <p><b>Keputusan Kasubag :</b> {{ $laporan->keputusan_kasubag ?? '-' }}</p>
            </div>

            {{-- FORM VERIFIKASI ADMIN --}}
            @if ($laporan->status_laporan === 'MENUNGGU_REVIEW_ADMIN')

                <hr>

                <form
                    method="POST"
                    action="{{ route('admin.laporan.verifikasi', $laporan->id_laporan) }}"
                    class="space-y-4"
                >
                    @csrf

                    <div>

                        <label class="block text-sm font-medium">
                            Keputusan Admin
                        </label>

                        <select
                            name="status_admin"
                            class="w-full px-3 py-2 border rounded"
                        >

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

                    </div>

                    <div>

                        <label class="block text-sm font-medium">
                            Catatan Admin
                        </label>

                        <textarea
                            name="keputusan_admin"
                            rows="3"
                            class="w-full px-3 py-2 border rounded"
                        ></textarea>

                    </div>

                    <button
                        type="submit"
                        class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-500"
                    >
                        Proses Laporan
                    </button>

                </form>

            @endif

        </div>

    </div>

</x-app-layout>