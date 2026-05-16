<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Dashboard Guru</h2>
    </x-slot>

    <div class="space-y-6">
        <x-flash-message />

        @if (! $guru)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm text-amber-800">
                Akun ini belum terhubung ke data guru. Hubungi admin untuk mengaitkan akun user dengan profil guru.
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-3">
                <x-data-card label="Hari Ini" :value="$today" />
                <x-data-card label="Jumlah Jadwal Hari Ini" :value="$jadwalHariIni->count()" />
                <x-data-card label="Presensi Hari Ini" :value="$presensiHariIni?->status ? ucfirst($presensiHariIni->status) : 'Belum Presensi'" />
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Jadwal Aktif</h3>
                    @if ($jadwalAktif)
                        <div class="mt-4 space-y-2 text-sm text-slate-700">
                            <p><span class="font-medium">Mata Pelajaran:</span> {{ $jadwalAktif->mataPelajaran->nama }}</p>
                            <p><span class="font-medium">Kelas:</span> {{ $jadwalAktif->kelas->nama }}</p>
                            <p><span class="font-medium">Jam:</span> {{ $jadwalAktif->jamPelajaran->jam_mulai }} - {{ $jadwalAktif->jamPelajaran->jam_selesai }}</p>
                        </div>
                    @else
                        <p class="mt-4 text-sm text-slate-500">Saat ini tidak ada jam pelajaran aktif.</p>
                    @endif
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Notifikasi Pergantian Jam</h3>
                    <div class="mt-4 text-sm text-slate-700">
                        @if ($jadwalAktif)
                            Jam sedang berlangsung untuk kelas <span class="font-medium">{{ $jadwalAktif->kelas->nama }}</span>.
                        @elseif ($jadwalHariIni->isNotEmpty())
                            Tidak ada jadwal aktif saat ini. Silakan cek jadwal hari ini untuk jam berikutnya.
                        @else
                            Tidak ada jadwal mengajar pada hari ini.
                        @endif
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-slate-900">Jadwal Mengajar Hari Ini</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-slate-500">Jam</th>
                                <th class="px-6 py-3 text-left font-medium text-slate-500">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left font-medium text-slate-500">Kelas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($jadwalHariIni as $jadwal)
                                <tr>
                                    <td class="px-6 py-4 text-slate-700">{{ $jadwal->jamPelajaran->jam_mulai }} - {{ $jadwal->jamPelajaran->jam_selesai }}</td>
                                    <td class="px-6 py-4 text-slate-700">{{ $jadwal->mataPelajaran->nama }}</td>
                                    <td class="px-6 py-4 text-slate-700">{{ $jadwal->kelas->nama }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-slate-500">Belum ada jadwal hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
