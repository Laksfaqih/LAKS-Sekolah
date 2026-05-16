<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Data Guru</h2>
    </x-slot>

    <div class="space-y-6">
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
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Jumlah Jadwal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($gurus as $guru)
                            <tr>
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $guru->nama }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $guru->nip ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $guru->email ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $guru->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $guru->jadwalPelajarans->pluck('mataPelajaran.nama')->filter()->unique()->implode(', ') ?: '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $guru->jadwal_pelajarans_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada data guru.</td>
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
