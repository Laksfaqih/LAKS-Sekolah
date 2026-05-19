<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Laporan Presensi Guru</h2>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" class="grid gap-3 md:grid-cols-5">
                <input type="date" name="start_date" value="{{ $filters['start_date'] }}" class="rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                <input type="date" name="end_date" value="{{ $filters['end_date'] }}" class="rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">

                <select name="guru_id" class="rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                    <option value="">Semua guru</option>
                    @foreach ($guruOptions as $guru)
                        <option value="{{ $guru->id }}" @selected($filters['guru_id'] === (string) $guru->id)>{{ $guru->nama }}</option>
                    @endforeach
                </select>

                <select name="status" class="rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                    <option value="">Semua status</option>
                    @foreach ($statusOptions as $status)
                        <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>

                <div class="flex gap-3">
                    <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Filter</button>
                    <a href="{{ route('admin.reports.presensi') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Reset</a>
                </div>
            </form>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.reports.presensi.print', request()->query()) }}" target="_blank" class="rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Cetak</a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Tanggal</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Guru</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Status</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Kelas</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-500">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($presensis as $presensi)
                            <tr>
                                <td class="px-6 py-4 text-slate-700">{{ $presensi->tanggal->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $presensi->guru->nama }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ ucfirst($presensi->status) }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $presensi->jadwalPelajaran?->mataPelajaran?->nama ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $presensi->jadwalPelajaran?->kelas?->nama ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $presensi->catatan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada data presensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $presensis->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
