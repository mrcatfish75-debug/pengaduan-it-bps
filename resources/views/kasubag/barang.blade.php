<x-app-layout>

<x-slot name="header">
    BARANG
</x-slot>

<div class="max-w-7xl mx-auto">

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">


<div class="overflow-x-auto">
<table class="w-full text-sm">

<thead class="bg-gray-50 text-gray-600 uppercase text-xs">
<tr>
<th class="px-6 py-4 text-left">
    <a href="?sort=nup&direction={{ $direction=='asc'?'desc':'asc' }}">
        NUP
    </a>
</th>

<th class="px-6 py-4 text-left">
    <a href="?sort=nama_barang&direction={{ $direction=='asc'?'desc':'asc' }}">
        Nama Barang
    </a>
</th>

<th class="px-6 py-4 text-left">
    <a href="?sort=lokasi_ruang&direction={{ $direction=='asc'?'desc':'asc' }}">
        Lokasi
    </a>
</th>

<th class="px-6 py-4 text-left">
    <a href="?sort=kondisi&direction={{ $direction=='asc'?'desc':'asc' }}">
        Kondisi
    </a>
</th>

<th class="px-6 py-4 text-center">
    Aksi
</th>
</tr>
</thead>

<tbody class="divide-y divide-gray-100">

@foreach($barang as $item)
<tr class="hover:bg-gray-50 transition">

<td class="px-6 py-4">{{ $item->nup }}</td>
<td class="px-6 py-4">{{ $item->nama_barang }}</td>
<td class="px-6 py-4">{{ $item->lokasi_ruang }}</td>

<td class="px-6 py-4">
<form method="POST" 
action="{{ route('kasubag.barang.update',$item->id) }}"
class="flex gap-2 items-center">
@csrf
@method('PATCH')

<select name="kondisi"
class="rounded border-gray-300 text-sm">

<option value="Baik" {{ $item->kondisi=='Baik'?'selected':'' }}>Baik</option>
<option value="Rusak Ringan" {{ $item->kondisi=='Rusak Ringan'?'selected':'' }}>Rusak Ringan</option>
<option value="Rusak Berat" {{ $item->kondisi=='Rusak Berat'?'selected':'' }}>Rusak Berat</option>
<option value="Dalam Perbaikan (Internal)" {{ $item->kondisi=='Dalam Perbaikan (Internal)'?'selected':'' }}>
Dalam Perbaikan (Internal)
</option>

</select>

<button type="submit"
class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs">
Update
</button>

</form>
</td>

<td class="px-6 py-4 text-center">

<form method="POST"
action="{{ route('kasubag.barang.destroy',$item->id) }}"
onsubmit="return confirm('Yakin hapus barang?')">
@csrf
@method('DELETE')

<button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
Hapus
</button>

</form>

</td>

</tr>
@endforeach

</tbody>

</table>
</div>

<div class="px-6 py-4 border-t border-gray-200">
{{ $barang->links() }}
</div>

</div>

</div>

</x-app-layout>