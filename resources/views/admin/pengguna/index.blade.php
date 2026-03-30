<x-app-layout>

<x-slot name="header">
<div class="flex items-center justify-between w-full">
    <h2 class="text-xl font-semibold text-gray-800 leading-tight">
        PENGGUNA
    </h2>


</div>
</x-slot>

<div class="p-6">

    <form method="GET" class="flex flex-wrap items-center gap-3 mb-6">

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama / email..."
            class="px-3 py-2 text-sm border rounded"
        />

        <button
            type="submit"
            class="px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
            Filter
        </button>

        <a
            href="{{ route('admin.pengguna') }}"
            class="px-4 py-2 text-sm text-white bg-gray-500 rounded hover:bg-gray-600">
            Reset
        </a>

        <a href="{{ route('admin.pengguna.create') }}"
       class="px-4 py-2 text-sm text-white bg-yellow-600 rounded hover:bg-yellow-700">
        + Tambah Pengguna
        </a>

    </form>

    <div class="overflow-x-auto bg-white rounded shadow">

        <table class="w-full text-sm table-auto">

            <thead class="text-gray-800 bg-gray-200">

                <tr>

                    <th class="p-3 text-center"><a href="?sort=nama_barang&direction={{ $direction=='asc'?'desc':'asc' }}">
                        ID</th>
                    </a>
                    <th class="p-3 text-center"><a href="?sort=nama_barang&direction={{ $direction=='asc'?'desc':'asc' }}">
                        Nama</th>
                    </a>
                    <th class="p-3 text-center"><a href="?sort=email&direction={{ $direction=='asc'?'desc':'asc' }}">
                        Email</th>
                    </a>
                    <th class="p-3 text-center"><a href="?sort=role&direction={{ $direction=='asc'?'desc':'asc' }}">
                        Role</th>
                    </a>
                    <th class="p-3 text-center"><a href="?sort=created_at&direction={{ $direction=='asc'?'desc':'asc' }}">
                        Dibuat</th>
                    </a>
                    <th class="p-3 text-center">Aksi</th>

                </tr>

            </thead>

            <tbody class="text-gray-800">

                @forelse ($users as $user)

                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-3 font-semibold text-center">
                            {{ $user->id }}
                        </td>

                        <td class="p-3 font-semibold">
                            {{ $user->name }}
                        </td>

                        <td class="p-3 font-semibold">
                            {{ $user->email }}
                        </td>

                        <td class="p-3 font-semibold text-center">

                            <span class="px-2 py-1 text-xs font-semibold text-white rounded
                                @if($user->role == 'admin') bg-blue-600
                                @elseif($user->role == 'kasubag') bg-purple-600
                                @else bg-gray-600
                                @endif
                            ">

                                {{ ucfirst($user->role) }}

                            </span>

                        </td>

                        <td class="p-3 font-semibold text-center">
                            {{ $user->created_at->format('d M Y H:i') }}
                        </td>

                        <td class="p-3 space-y-2">

                            <a
                                href="{{ route('admin.pengguna.edit',$user->id) }}"
                                class="block px-3 py-1 text-sm text-center text-white bg-indigo-500 rounded hover:bg-indigo-600">
                                Edit
                            </a>

                            <form
                                method="POST"
                                action="{{ route('admin.pengguna.destroy',$user->id) }}">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="w-full px-3 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700"
                                    onclick="return confirm('Yakin hapus user?')">

                                    Hapus

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="p-6 text-center text-gray-500">

                            Belum ada pengguna

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-6">

        {{ $users->links() }}

    </div>

</div>

</x-app-layout>