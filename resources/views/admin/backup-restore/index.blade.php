<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Backup dan Restore</h2>
    </x-slot>

    <div class="space-y-6">
        <x-flash-message />

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Informasi Database</h3>
            <div class="mt-4 space-y-2 text-sm text-slate-700">
                <p><span class="font-medium">Driver:</span> {{ $databaseDriver }}</p>
                <p><span class="font-medium">Database:</span> {{ $databaseName ?? '-' }}</p>
                <p><span class="font-medium">Host:</span> {{ $databaseHost ?? '-' }}</p>
                <p><span class="font-medium">Path:</span> {{ $databasePath ?? 'Tidak digunakan untuk driver ini' }}</p>
            </div>

            <form method="POST" action="{{ route('admin.backup-restore.backup') }}" class="mt-6">
                @csrf
                <x-primary-button>Buat Backup Database</x-primary-button>
            </form>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Restore Database</h3>
            <p class="mt-1 text-sm text-slate-600">Unggah file backup `.sql`, `.sqlite`, atau `.db` sesuai driver database yang sedang aktif.</p>

            <form method="POST" action="{{ route('admin.backup-restore.restore') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
                @csrf
                <div>
                    <x-input-label for="database_file" value="File Backup Database" />
                    <input id="database_file" name="database_file" type="file" accept=".sql,.sqlite,.db" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm">
                    <x-input-error :messages="$errors->get('database_file')" class="mt-2" />
                </div>

                <x-primary-button>Restore Database</x-primary-button>
            </form>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-slate-900">Riwayat Backup</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Nama File</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Ukuran</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Waktu</th>
                            <th class="px-6 py-3 text-right font-medium text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($backups as $backup)
                            <tr>
                                <td class="px-6 py-4 text-slate-700">{{ $backup['name'] }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ number_format($backup['size'] / 1024, 2) }} KB</td>
                                <td class="px-6 py-4 text-slate-700">{{ \Illuminate\Support\Carbon::createFromTimestamp($backup['updated_at'])->format('Y-m-d H:i:s') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.backup-restore.download', ['file' => $backup['path']]) }}" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">Download</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada file backup.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
