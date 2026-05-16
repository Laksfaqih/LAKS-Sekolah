<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Data Guru</h2>
    </x-slot>

    <div class="space-y-6">
        <x-page-header title="Kelola Guru" description="Tambah, ubah, hapus, dan cari data guru." actionLabel="Tambah Guru" :actionHref="route('admin.gurus.create')" />
        <x-flash-message />

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" class="flex flex-col gap-3 md:flex-row">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIP, atau email" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Cari</button>
            </form>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Nama</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">NIP</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Email</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Status</th>
                            <th class="px-6 py-3 text-right font-medium text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($gurus as $guru)
                            <tr>
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $guru->nama }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $guru->nip ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $guru->email ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-medium {{ $guru->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                                        {{ $guru->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.gurus.edit', $guru) }}" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">Edit</a>
                                        <form method="POST" action="{{ route('admin.gurus.destroy', $guru) }}" onsubmit="return confirm('Hapus data guru ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-rose-600 px-3 py-2 text-xs font-medium text-white hover:bg-rose-700">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada data guru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $gurus->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
