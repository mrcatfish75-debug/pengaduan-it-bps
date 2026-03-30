<x-app-layout>

<x-slot name="header">
<h2 class="text-xl font-semibold text-gray-800 leading-tight">
    Tambah Pengguna
</h2>
</x-slot>

<div class="max-w-xl p-6 mx-auto bg-white rounded shadow">

    <form method="POST" action="{{ route('admin.pengguna.store') }}">

        @csrf

        <div class="mb-4">

            <label class="block text-sm font-medium">
                Nama
            </label>

            <input
                type="text"
                name="name"
                required
                class="w-full px-3 py-2 border rounded"
            />

        </div>

        <div class="mb-4">

            <label class="block text-sm font-medium">
                Email
            </label>

            <input
                type="email"
                name="email"
                required
                class="w-full px-3 py-2 border rounded"
            />

        </div>

        <div class="mb-4">

            <label class="block text-sm font-medium">
                Password
            </label>

            <input
                type="password"
                name="password"
                required
                class="w-full px-3 py-2 border rounded"
            />

        </div>

        <div class="mb-4">

            <label class="block text-sm font-medium">
                Role
            </label>

            <select
                name="role"
                class="w-full px-3 py-2 border rounded">

                <option value="pegawai">Pegawai</option>
                <option value="admin">Admin</option>
                <option value="kasubag">Kasubag</option>

            </select>

        </div>

        <button
            type="submit"
            class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">

            Simpan

        </button>

    </form>

</div>

</x-app-layout>