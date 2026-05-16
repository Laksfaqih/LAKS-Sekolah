<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Presensi Guru</h2>
    </x-slot>

    <div class="space-y-6">
        <x-flash-message />

        @if (! $guru)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm text-amber-800">
                Akun ini belum terhubung ke data guru.
            </div>
        @else
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Presensi Mengajar Hari Ini</h3>
                <p class="mt-1 text-sm text-slate-600">Hari aktif: {{ $today }}</p>

                @if ($jadwalAktif)
                    <div class="mt-4 space-y-2 text-sm text-slate-700">
                        <p><span class="font-medium">Mata Pelajaran:</span> {{ $jadwalAktif->mataPelajaran->nama }}</p>
                        <p><span class="font-medium">Kelas:</span> {{ $jadwalAktif->kelas->nama }}</p>
                        <p><span class="font-medium">Jam:</span> {{ $jadwalAktif->jamPelajaran->jam_mulai }} - {{ $jadwalAktif->jamPelajaran->jam_selesai }}</p>
                    </div>

                    <form method="POST" action="{{ route('guru.presensi.store') }}" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="status" value="Status Presensi" />
                            <select id="status" name="status" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                                @foreach ($statusOptions as $status)
                                    <option value="{{ $status }}" @selected(old('status', $presensiAktif?->status ?? 'hadir') === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="catatan" value="Catatan" />
                            <textarea id="catatan" name="catatan" rows="3" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('catatan', $presensiAktif?->catatan) }}</textarea>
                            <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                        </div>

                        <x-primary-button>{{ $presensiAktif ? 'Perbarui Presensi' : 'Simpan Presensi' }}</x-primary-button>
                    </form>
                @else
                    <p class="mt-4 text-sm text-slate-500">Tidak ada jadwal aktif saat ini, sehingga presensi belum bisa dilakukan.</p>
                @endif
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-slate-900">Riwayat Presensi</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-slate-500">Tanggal</th>
                                <th class="px-6 py-3 text-left font-medium text-slate-500">Status</th>
                                <th class="px-6 py-3 text-left font-medium text-slate-500">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left font-medium text-slate-500">Kelas</th>
                                <th class="px-6 py-3 text-left font-medium text-slate-500">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($riwayat as $item)
                                <tr>
                                    <td class="px-6 py-4 text-slate-700">{{ $item->tanggal->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 text-slate-700">{{ ucfirst($item->status) }}</td>
                                    <td class="px-6 py-4 text-slate-700">{{ $item->jadwalPelajaran?->mataPelajaran?->nama ?? '-' }}</td>
                                    <td class="px-6 py-4 text-slate-700">{{ $item->jadwalPelajaran?->kelas?->nama ?? '-' }}</td>
                                    <td class="px-6 py-4 text-slate-700">{{ $item->catatan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada riwayat presensi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
