<x-app-layout>
<div class="p-8">
    <h1 class="text-2xl font-bold mb-6">Activity Log</h1>

    <div class="bg-white shadow rounded p-6">

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="text-left p-2">User</th>
                    <th class="text-left p-2">Aksi</th>
                    <th class="text-left p-2">Model</th>
                    <th class="text-left p-2">Deskripsi</th>
                    <th class="text-left p-2">Waktu</th>
                </tr>
            </thead>

            <tbody>
                @foreach($logs as $log)
                <tr class="border-b">
                    <td class="p-2">{{  $log->user?->name ?? 'System' }}</td>
                    <td class="p-2">{{ $log->aksi }}</td>
                    <td class="p-2">{{ $log->model }} #{{ $log->model_id }}</td>
                    <td class="p-2">{{ $log->deskripsi }}</td>
                    <td class="p-2">{{ $log->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>

    </div>
</div>
</x-app-layout>