<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Pengaturan Bel</h2>
    </x-slot>

    <div class="space-y-6">
        <x-page-header title="Kelola Bel Otomatis" description="Atur jadwal bunyi bel, tipe, status aktif, dan file audio." actionLabel="Tambah Jadwal Bel" :actionHref="route('admin.pengaturan-bel.create')" />
        <x-flash-message />

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" class="flex flex-col gap-3 md:flex-row">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau tipe bel" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Cari</button>
            </form>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Nama</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Tipe</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Jam</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Audio</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Status</th>
                            <th class="px-6 py-3 text-right font-medium text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($pengaturanBels as $pengaturanBel)
                            <tr>
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $pengaturanBel->nama }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ str($pengaturanBel->tipe_bel)->replace('_', ' ')->title() }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ substr((string) $pengaturanBel->jam_bunyi, 0, 5) }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $pengaturanBel->audio_path ? basename($pengaturanBel->audio_path) : '-' }}</td>
                                <td class="px-6 py-4">
                                    <span @class([
                                        'rounded-full px-3 py-1 text-xs font-medium',
                                        'bg-emerald-100 text-emerald-700' => $pengaturanBel->is_active,
                                        'bg-slate-100 text-slate-600' => ! $pengaturanBel->is_active,
                                    ])>
                                        {{ $pengaturanBel->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.pengaturan-bel.edit', $pengaturanBel) }}" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">Edit</a>
                                        <form method="POST" action="{{ route('admin.pengaturan-bel.destroy', $pengaturanBel) }}" onsubmit="return confirm('Hapus pengaturan bel ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-rose-600 px-3 py-2 text-xs font-medium text-white hover:bg-rose-700">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada pengaturan bel.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $pengaturanBels->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
