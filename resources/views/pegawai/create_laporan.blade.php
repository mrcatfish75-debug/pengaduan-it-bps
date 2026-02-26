<x-app-layout>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Buat Laporan Kerusakan IT
    </h2>
</x-slot>

<div class="py-10">
    <div class="max-w-3xl mx-auto px-6">

        {{-- SUCCESS --}}
        @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800 border border-green-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR --}}
        @if($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 border border-red-300">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">

            <form method="POST" action="{{ route('lapor.store') }}" class="space-y-6">
                @csrf

                {{-- PILIH BARANG --}}
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Pilih Barang (NUP)
                    </label>

                    <select name="barang_id" required
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                               bg-white dark:bg-gray-700 
                               text-gray-800 dark:text-white
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Barang --</option>

                        @foreach($barang as $b)
                            <option value="{{ $b->id }}">
                                {{ $b->nup }} - {{ $b->nama_barang }} ({{ $b->lokasi_ruang }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- JENIS --}}
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Jenis Kerusakan
                    </label>

                    <select name="jenis_kerusakan" required
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                               bg-white dark:bg-gray-700 
                               text-gray-800 dark:text-white
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Hardware">Hardware</option>
                        <option value="Software">Software</option>
                        <option value="Jaringan">Jaringan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                {{-- DESKRIPSI --}}
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Deskripsi Keluhan
                    </label>

                    <textarea name="deskripsi_keluhan"
                              required
                              rows="4"
                              placeholder="Jelaskan detail kerusakan..."
                              class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                                     bg-white dark:bg-gray-700
                                     text-gray-800 dark:text-white
                                     focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                {{-- PRIORITAS --}}
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Prioritas
                    </label>

                    <select name="prioritas" required
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                               bg-white dark:bg-gray-700 
                               text-gray-800 dark:text-white
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Prioritas --</option>
                        <option value="Low">Low</option>
                        <option value="Normal">Normal</option>
                        <option value="High">High</option>
                    </select>
                </div>

                {{-- BUTTON --}}
                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 
                               text-white font-semibold py-3 rounded-lg 
                               transition duration-200 shadow-md">
                        Kirim Laporan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

</x-app-layout>