<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Dashboard Admin</h2>
    </x-slot>

    <div class="space-y-6">
        <x-flash-message />

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-data-card label="Jumlah Guru" :value="$guruCount" />
            <x-data-card label="Jumlah Jadwal" :value="$jadwalCount" />
            <x-data-card label="Status Bel" :value="$belAktif ? 'Aktif' : 'Nonaktif'" />
            <x-data-card label="Jam Realtime" :value="$currentTime" />
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-slate-900">Jadwal Hari Ini</h3>
                <p class="text-sm text-slate-600">Ringkasan jadwal hari aktif yang tersimpan di sistem.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Guru</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Kelas</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Jam</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($jadwalHariIni as $jadwal)
                            <tr>
                                <td class="px-6 py-4 text-slate-700">{{ $jadwal->guru->nama }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $jadwal->mataPelajaran->nama }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $jadwal->kelas->nama }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $jadwal->jamPelajaran->jam_mulai }} - {{ $jadwal->jamPelajaran->jam_selesai }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                    Belum ada jadwal untuk hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
