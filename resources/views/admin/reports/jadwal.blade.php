<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Laporan Jadwal Pelajaran</h2>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" class="grid gap-3 md:grid-cols-4">
                <select name="hari" class="rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                    <option value="">Semua hari</option>
                    @foreach ($hariOptions as $hari)
                        <option value="{{ $hari }}" @selected($filters['hari'] === $hari)>{{ $hari }}</option>
                    @endforeach
                </select>

                <select name="guru_id" class="rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                    <option value="">Semua guru</option>
                    @foreach ($guruOptions as $guru)
                        <option value="{{ $guru->id }}" @selected($filters['guru_id'] === (string) $guru->id)>{{ $guru->nama }}</option>
                    @endforeach
                </select>

                <select name="kelas_id" class="rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                    <option value="">Semua kelas</option>
                    @foreach ($kelasOptions as $kelas)
                        <option value="{{ $kelas->id }}" @selected($filters['kelas_id'] === (string) $kelas->id)>{{ $kelas->nama }}</option>
                    @endforeach
                </select>

                <div class="flex gap-3">
                    <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Filter</button>
                    <a href="{{ route('admin.reports.jadwal') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Reset</a>
                </div>
            </form>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.reports.jadwal.print', request()->query()) }}" target="_blank" class="rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Cetak</a>
            <a href="{{ route('admin.reports.jadwal.pdf', request()->query()) }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Export PDF</a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Hari</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Jam</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Guru</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Kelas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($jadwals as $jadwal)
                            <tr>
                                <td class="px-6 py-4 text-slate-700">{{ $jadwal->hari }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $jadwal->jamPelajaran->jam_mulai }} - {{ $jadwal->jamPelajaran->jam_selesai }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $jadwal->guru->nama }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $jadwal->mataPelajaran->nama }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $jadwal->kelas->nama }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada data jadwal.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $jadwals->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
