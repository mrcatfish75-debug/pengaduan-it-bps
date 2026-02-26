<x-app-layout>

<div class="max-w-7xl mx-auto p-6">

<h1 class="text-3xl font-bold mb-6
text-gray-800 dark:text-white">
Dashboard Kasubag
</h1>

{{-- SUCCESS --}}
@if(session('success'))
<div class="mb-5 p-4 rounded bg-green-600 text-white shadow">
    {{ session('success') }}
</div>
@endif

<div class="bg-white dark:bg-gray-800
shadow-xl rounded-xl overflow-hidden">

<div class="p-6 border-b
border-gray-200 dark:border-gray-700">

<h2 class="text-lg font-semibold
text-gray-800 dark:text-white">
Laporan Menunggu Keputusan
</h2>

</div>

@if($laporan->count())

<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-100 dark:bg-gray-700">

<tr class="text-gray-700 dark:text-gray-200">
<th class="p-4 text-left">No</th>
<th class="p-4 text-left">Pelapor</th>
<th class="p-4 text-left">Barang</th>
<th class="p-4 text-left">Keluhan</th>
<th class="p-4 text-left">Rekomendasi Admin</th>
<th class="p-4 text-center">Keputusan</th>
</tr>

</thead>

<tbody class="divide-y
divide-gray-200 dark:divide-gray-700">

@foreach($laporan as $i => $item)

<tr class="hover:bg-gray-50
dark:hover:bg-gray-700 transition">

<td class="p-4 text-gray-800 dark:text-gray-200">
{{ $laporan->firstItem() + $i }}
</td>

<td class="p-4 font-medium
text-gray-800 dark:text-white">
{{ $item->user->name ?? '-' }}
</td>

<td class="p-4 text-gray-700 dark:text-gray-300">
<b>{{ $item->barang->nup ?? '-' }}</b><br>
<span class="text-xs">
{{ $item->barang->nama_barang ?? '-' }}
</span>
</td>

<td class="p-4 max-w-xs
text-gray-700 dark:text-gray-300">
{{ $item->deskripsi_keluhan }}
</td>

<td class="p-4">
<span class="px-3 py-1 rounded-full
bg-blue-600 text-white text-xs">
{{ $item->status_admin }}
</span>
</td>

<td class="p-4 w-72">

<form method="POST"
action="{{ route('kasubag.putusan',$item->id_laporan) }}">
@csrf

<select name="status_kasubag"
required
class="w-full mb-2 rounded
border-gray-300
dark:bg-gray-700
dark:text-white">

<option value="">-- Pilih --</option>

<option value="DISETUJUI_SERVIS_INTERNAL">
Servis Internal
</option>

<option value="DISETUJUI_SERVIS_EKSTERNAL">
Servis Eksternal
</option>

<option value="DISETUJUI_GANTI_BARU">
Ganti Baru
</option>

<option value="DITOLAK_KASUBAG">
Tolak
</option>

</select>

<textarea name="keputusan_kasubag"
rows="2"
class="w-full rounded
border-gray-300
dark:bg-gray-700
dark:text-white mb-2"
placeholder="Catatan keputusan"></textarea>

<button type="submit"
class="w-full bg-indigo-600
hover:bg-indigo-700
text-white py-2 rounded">
Simpan
</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

<div class="p-4">
{{ $laporan->links() }}
</div>

@else

<div class="p-8 text-center
text-gray-500 dark:text-gray-400">
Tidak ada laporan menunggu keputusan.
</div>

@endif

</div>

</div>

</x-app-layout>