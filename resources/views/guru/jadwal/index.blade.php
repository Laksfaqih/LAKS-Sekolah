<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Jadwal Mengajar</h2>
    </x-slot>

    <div class="space-y-6">
        @if (! $guru)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm text-amber-800">
                Akun ini belum terhubung ke data guru.
            </div>
        @else
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <form method="GET" class="flex flex-col gap-3 md:flex-row">
                    <select name="hari" class="rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                        <option value="">Semua hari</option>
                        @foreach ($hariOptions as $hari)
                            <option value="{{ $hari }}" @selected($selectedHari === $hari)>{{ $hari }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Filter</button>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-slate-500">Hari</th>
                                <th class="px-6 py-3 text-left font-medium text-slate-500">Jam</th>
                                <th class="px-6 py-3 text-left font-medium text-slate-500">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left font-medium text-slate-500">Kelas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($jadwalPelajarans as $jadwal)
                                <tr>
                                    <td class="px-6 py-4 text-slate-700">{{ $jadwal->hari }}</td>
                                    <td class="px-6 py-4 text-slate-700">{{ $jadwal->jamPelajaran->jam_mulai }} - {{ $jadwal->jamPelajaran->jam_selesai }}</td>
                                    <td class="px-6 py-4 text-slate-700">{{ $jadwal->mataPelajaran->nama }}</td>
                                    <td class="px-6 py-4 text-slate-700">{{ $jadwal->kelas->nama }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada jadwal mengajar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
