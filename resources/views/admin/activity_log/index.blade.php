<x-app-layout>

<x-slot name="header">

    <div class="flex items-center justify-between w-full">

        {{-- JUDUL --}}
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ACTIVITY LOG
        </h2>

        {{-- AREA KANAN --}}
        <div class="flex items-center">

</x-slot>


    {{-- ================= TABLE ================= --}}
    <div class="bg-white shadow rounded-xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">User</th>
                        <th class="p-3">Aksi</th>
                        <th class="p-3">Model</th>
                        <th class="p-3">Deskripsi</th>
                        <th class="p-3">Waktu</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($logs as $log)
                        <tr class="border-b hover:bg-gray-50">

                            <td class="p-3">
                                {{ $log->user?->name ?? 'System' }}
                            </td>

                            <td class="p-3 font-semibold text-indigo-600">
                                {{ $log->aksi }}
                            </td>

                            <td class="p-3">
                                {{ $log->model ?? '-' }}
                                @if($log->model_id)
                                    #{{ $log->model_id }}
                                @endif
                            </td>

                            <td class="p-3">
                                {{ $log->deskripsi ?? '-' }}
                            </td>

                            <td class="p-3 text-gray-500">
                                {{ $log->created_at?->format('d M Y H:i') }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="text-center p-6 text-gray-500">
                                Tidak ada aktivitas tercatat
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ================= PAGINATION ================= --}}
        <div class="p-4">
            {{ $logs->links() }}
        </div>

    </div>

</div>

</x-app-layout>