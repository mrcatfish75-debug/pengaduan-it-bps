<x-app-layout>

    <div class="p-6 max-w-xl mx-auto">

        <h1 class="text-2xl font-bold mb-6">
            Edit Laporan #{{ $laporan->id_laporan }}
        </h1>

        @if ($errors->any())

            <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
                {{ $errors->first() }}
            </div>

        @endif

        <form
            method="POST"
            action="{{ route('admin.laporan.update', $laporan->id_laporan) }}"
        >

            @csrf
            @method('PUT')

            <!-- PRIORITAS -->
            <div class="mb-4">

                <label class="block font-semibold">
                    Prioritas
                </label>

                <select
                    name="prioritas"
                    class="border rounded w-full p-2"
                >

                    <option
                        value="RENDAH"
                        {{ old('prioritas', $laporan->prioritas) == 'RENDAH' ? 'selected' : '' }}
                    >
                        RENDAH
                    </option>

                    <option
                        value="SEDANG"
                        {{ old('prioritas', $laporan->prioritas) == 'SEDANG' ? 'selected' : '' }}
                    >
                        SEDANG
                    </option>

                    <option
                        value="TINGGI"
                        {{ old('prioritas', $laporan->prioritas) == 'TINGGI' ? 'selected' : '' }}
                    >
                        TINGGI
                    </option>

                </select>

            </div>

            <!-- DESKRIPSI -->
            <div class="mb-4">

                <label class="block font-semibold">
                    Deskripsi Keluhan
                </label>

                <textarea
                    name="deskripsi_keluhan"
                    rows="5"
                    maxlength="2000"
                    class="border rounded w-full p-2"
                >{{ old('deskripsi_keluhan', $laporan->deskripsi_keluhan) }}</textarea>

            </div>

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
            >
                Update Laporan
            </button>

        </form>

    </div>

</x-app-layout>