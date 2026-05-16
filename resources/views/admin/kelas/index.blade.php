<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Data Kelas</h2>
    </x-slot>

    <div class="space-y-6">
        <x-page-header title="Kelola Kelas" description="Master data kelas untuk kebutuhan jadwal pelajaran." actionLabel="Tambah Kelas" :actionHref="route('admin.kelas.create')" />
        <x-flash-message />

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" class="flex flex-col gap-3 md:flex-row">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, tingkat, atau jurusan" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Cari</button>
            </form>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Nama</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Tingkat</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Jurusan</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Keterangan</th>
                            <th class="px-6 py-3 text-right font-medium text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($kelas as $item)
                            <tr>
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $item->nama }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $item->tingkat ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $item->jurusan ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $item->keterangan ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.kelas.edit', $item) }}" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">Edit</a>
                                        <form method="POST" action="{{ route('admin.kelas.destroy', $item) }}" onsubmit="return confirm('Hapus data kelas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-rose-600 px-3 py-2 text-xs font-medium text-white hover:bg-rose-700">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada data kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $kelas->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
