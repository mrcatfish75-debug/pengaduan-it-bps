@extends('layouts.pegawai')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-10">

        <h1 class="text-2xl font-bold text-gray-800 mb-8">
            Buat Laporan Kerusakan IT
        </h1>

        @if ($errors->any())

            <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-lg">
                {{ $errors->first() }}
            </div>

        @endif

        <form
            method="POST"
            action="{{ route('pegawai.lapor.store') }}"
            class="space-y-6"
        >

            @csrf

            <!-- BARANG -->
            <div>

                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Pilih Barang (Bisa Diketik NUP / Nama / Ruangan)
                </label>

                <input
                    type="text"
                    id="barangInput"
                    list="barangList"
                    placeholder="Ketik NUP atau Nama Barang..."
                    class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-indigo-500 p-3"
                    autocomplete="off"
                    required
                >

                <datalist id="barangList">

                    @foreach ($barang as $item)

                        <option
                            value="{{ $item->nup }} - {{ $item->nama_barang }} ({{ $item->lokasi_ruang }})"
                            data-id="{{ $item->id }}"
                        >
                        </option>

                    @endforeach

                </datalist>

                <input
                    type="hidden"
                    name="barang_id"
                    id="barang_id"
                >

            </div>

            <!-- JENIS -->
            <div>

                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Jenis Kerusakan
                </label>

                <select
                    name="jenis_kerusakan"
                    required
                    class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-indigo-500 p-3"
                >

                    <option value="">-- Pilih Jenis --</option>

                    <option
                        value="Hardware"
                        {{ old('jenis_kerusakan') == 'Hardware' ? 'selected' : '' }}
                    >
                        Kerusakan Perangkat (Hardware)
                    </option>

                    <option
                        value="Software"
                        {{ old('jenis_kerusakan') == 'Software' ? 'selected' : '' }}
                    >
                        Kerusakan Aplikasi / Sistem (Software)
                    </option>

                </select>

            </div>

            <!-- DESKRIPSI -->
            <div>

                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Deskripsi Keluhan
                </label>

                <textarea
                    name="deskripsi_keluhan"
                    rows="4"
                    maxlength="2000"
                    class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-indigo-500 p-3"
                    placeholder="Jelaskan detail kerusakan..."
                >{{ old('deskripsi_keluhan') }}</textarea>

            </div>

            <!-- PRIORITAS -->
            <div>

                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Tingkat Urgensi
                </label>

                <select
                    name="prioritas"
                    required
                    class="w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-indigo-500 p-3"
                >

                    <option value="">-- Pilih Tingkat Urgensi --</option>

                    <option value="RENDAH" {{ old('prioritas') == 'RENDAH' ? 'selected' : '' }}>
                        Rendah (Masih Bisa Digunakan)
                    </option>

                    <option value="SEDANG" {{ old('prioritas') == 'SEDANG' ? 'selected' : '' }}>
                        Sedang (Mengganggu Pekerjaan)
                    </option>

                    <option value="TINGGI" {{ old('prioritas') == 'TINGGI' ? 'selected' : '' }}>
                        Tinggi (Tidak Bisa Digunakan Sama Sekali)
                    </option>

                </select>

            </div>

            <button
                type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 transition text-white py-3 rounded-xl font-semibold shadow"
            >
                Kirim Laporan
            </button>

        </form>

    </div>

</div>

<script>

    document
        .getElementById('barangInput')
        .addEventListener('change', function () {

            const input   = this.value.trim();
            const options = document.querySelectorAll('#barangList option');

            let found = false;

            options.forEach(function (option) {

                if (option.value === input) {

                    document.getElementById('barang_id').value = option.dataset.id;
                    found = true;

                }

            });

            if (!found) {
                document.getElementById('barang_id').value = '';
            }

        });

</script>

@endsection