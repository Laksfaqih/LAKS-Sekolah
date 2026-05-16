<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Dashboard Kepala Sekolah</h2>
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-data-card label="Hari Ini" :value="$today" />
            <x-data-card label="Jadwal Hari Ini" :value="$jadwalHariIni->count()" />
            <x-data-card label="Guru Hadir" :value="$guruHadirCount . ' / ' . $guruCount" />
            <x-data-card label="Status Bel" :value="$belAktif ? 'Aktif' : 'Nonaktif'" />
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Jam Pelajaran Sedang Berlangsung</h3>
                @if ($jadwalAktif)
                    <div class="mt-4 space-y-2 text-sm text-slate-700">
                        <p><span class="font-medium">Guru:</span> {{ $jadwalAktif->guru->nama }}</p>
                        <p><span class="font-medium">Kelas:</span> {{ $jadwalAktif->kelas->nama }}</p>
                        <p><span class="font-medium">Mata Pelajaran:</span> {{ $jadwalAktif->mataPelajaran->nama }}</p>
                    </div>
                @else
                    <p class="mt-4 text-sm text-slate-500">Tidak ada jam pelajaran aktif saat ini.</p>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Informasi Keterlambatan</h3>
                <p class="mt-4 text-sm text-slate-500">
                    Belum ada modul keterlambatan terpisah. Indikasi kehadiran dapat dipantau dari data presensi guru pada sprint ini.
                </p>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-slate-900">Jadwal Hari Ini</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Jam</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Guru</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Kelas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($jadwalHariIni as $jadwal)
                            <tr>
                                <td class="px-6 py-4 text-slate-700">{{ $jadwal->jamPelajaran->jam_mulai }} - {{ $jadwal->jamPelajaran->jam_selesai }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $jadwal->guru->nama }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $jadwal->mataPelajaran->nama }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $jadwal->kelas->nama }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada jadwal hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
