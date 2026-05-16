<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\IdentitasSekolah;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\PresensiGuru;
use App\Support\SimplePdfBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function jadwal(Request $request): View
    {
        [$jadwals, $filters] = $this->scheduleData($request);

        return view('admin.reports.jadwal', [
            'jadwals' => $jadwals,
            'filters' => $filters,
            'guruOptions' => Guru::query()->orderBy('nama')->get(),
            'kelasOptions' => Kelas::query()->orderBy('nama')->get(),
            'hariOptions' => JadwalPelajaran::hariOptions(),
            'school' => $this->schoolProfile(),
        ]);
    }

    public function jadwalPrint(Request $request): View
    {
        [$jadwals, $filters] = $this->scheduleData($request, false);

        return view('reports.print', [
            'title' => 'Laporan Jadwal Pelajaran',
            'subtitle' => $this->reportSubtitle($filters),
            'school' => $this->schoolProfile(),
            'columns' => ['Hari', 'Jam', 'Guru', 'Mata Pelajaran', 'Kelas'],
            'rows' => $jadwals->map(fn ($jadwal) => [
                $jadwal->hari,
                "{$jadwal->jamPelajaran->jam_mulai} - {$jadwal->jamPelajaran->jam_selesai}",
                $jadwal->guru->nama,
                $jadwal->mataPelajaran->nama,
                $jadwal->kelas->nama,
            ])->all(),
        ]);
    }

    public function jadwalPdf(Request $request): Response
    {
        [$jadwals, $filters] = $this->scheduleData($request, false);

        return $this->pdfResponse(
            'laporan-jadwal-pelajaran.pdf',
            'Laporan Jadwal Pelajaran',
            ['Hari', 'Jam', 'Guru', 'Mata Pelajaran', 'Kelas'],
            $jadwals->map(fn ($jadwal) => [
                $jadwal->hari,
                "{$jadwal->jamPelajaran->jam_mulai} - {$jadwal->jamPelajaran->jam_selesai}",
                $jadwal->guru->nama,
                $jadwal->mataPelajaran->nama,
                $jadwal->kelas->nama,
            ])->all(),
            $this->reportSubtitle($filters),
        );
    }

    public function presensi(Request $request): View
    {
        [$presensis, $filters] = $this->attendanceData($request);

        return view('admin.reports.presensi', [
            'presensis' => $presensis,
            'filters' => $filters,
            'guruOptions' => Guru::query()->orderBy('nama')->get(),
            'statusOptions' => PresensiGuru::statusOptions(),
            'school' => $this->schoolProfile(),
        ]);
    }

    public function presensiPrint(Request $request): View
    {
        [$presensis, $filters] = $this->attendanceData($request, false);

        return view('reports.print', [
            'title' => 'Laporan Presensi Guru',
            'subtitle' => $this->reportSubtitle($filters),
            'school' => $this->schoolProfile(),
            'columns' => ['Tanggal', 'Guru', 'Status', 'Mata Pelajaran', 'Kelas', 'Catatan'],
            'rows' => $presensis->map(fn ($presensi) => [
                $presensi->tanggal->format('Y-m-d'),
                $presensi->guru->nama,
                ucfirst($presensi->status),
                $presensi->jadwalPelajaran?->mataPelajaran?->nama ?? '-',
                $presensi->jadwalPelajaran?->kelas?->nama ?? '-',
                $presensi->catatan ?? '-',
            ])->all(),
        ]);
    }

    public function presensiPdf(Request $request): Response
    {
        [$presensis, $filters] = $this->attendanceData($request, false);

        return $this->pdfResponse(
            'laporan-presensi-guru.pdf',
            'Laporan Presensi Guru',
            ['Tanggal', 'Guru', 'Status', 'Mata Pelajaran', 'Kelas', 'Catatan'],
            $presensis->map(fn ($presensi) => [
                $presensi->tanggal->format('Y-m-d'),
                $presensi->guru->nama,
                ucfirst($presensi->status),
                $presensi->jadwalPelajaran?->mataPelajaran?->nama ?? '-',
                $presensi->jadwalPelajaran?->kelas?->nama ?? '-',
                $presensi->catatan ?? '-',
            ])->all(),
            $this->reportSubtitle($filters),
        );
    }

    /**
     * @return array{0: LengthAwarePaginator|Collection, 1: array<string, string>}
     */
    private function scheduleData(Request $request, bool $paginate = true): array
    {
        $filters = [
            'hari' => $request->string('hari')->toString(),
            'guru_id' => $request->string('guru_id')->toString(),
            'kelas_id' => $request->string('kelas_id')->toString(),
        ];

        $query = JadwalPelajaran::query()
            ->with(['guru', 'mataPelajaran', 'kelas', 'jamPelajaran'])
            ->when($filters['hari'] !== '', fn ($builder) => $builder->where('hari', $filters['hari']))
            ->when($filters['guru_id'] !== '', fn ($builder) => $builder->where('guru_id', (int) $filters['guru_id']))
            ->when($filters['kelas_id'] !== '', fn ($builder) => $builder->where('kelas_id', (int) $filters['kelas_id']))
            ->orderBy('hari')
            ->orderBy('jam_pelajaran_id');

        $data = $paginate ? $query->paginate(15)->withQueryString() : $query->get();

        return [$data, $filters];
    }

    /**
     * @return array{0: LengthAwarePaginator|Collection, 1: array<string, string>}
     */
    private function attendanceData(Request $request, bool $paginate = true): array
    {
        $filters = [
            'start_date' => $request->string('start_date')->toString(),
            'end_date' => $request->string('end_date')->toString(),
            'guru_id' => $request->string('guru_id')->toString(),
            'status' => $request->string('status')->toString(),
        ];

        $query = PresensiGuru::query()
            ->with(['guru', 'jadwalPelajaran.mataPelajaran', 'jadwalPelajaran.kelas'])
            ->when($filters['start_date'] !== '', fn ($builder) => $builder->whereDate('tanggal', '>=', $filters['start_date']))
            ->when($filters['end_date'] !== '', fn ($builder) => $builder->whereDate('tanggal', '<=', $filters['end_date']))
            ->when($filters['guru_id'] !== '', fn ($builder) => $builder->where('guru_id', (int) $filters['guru_id']))
            ->when($filters['status'] !== '', fn ($builder) => $builder->where('status', $filters['status']))
            ->latest('tanggal')
            ->latest();

        $data = $paginate ? $query->paginate(15)->withQueryString() : $query->get();

        return [$data, $filters];
    }

    private function pdfResponse(
        string $filename,
        string $title,
        array $headers,
        array $rows,
        string $subtitle,
    ): Response {
        $school = $this->schoolProfile();
        $metaLines = array_values(array_filter([$school['name'], $school['meta']]));

        return response(SimplePdfBuilder::makeTable($title, $metaLines, $subtitle, $headers, $rows), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    private function reportSubtitle(array $filters): string
    {
        $active = collect($filters)
            ->filter(fn (string $value) => $value !== '')
            ->map(fn (string $value, string $key) => str($key)->replace('_', ' ')->title().": {$value}")
            ->values();

        return $active->isEmpty()
            ? 'Filter: Semua data'
            : 'Filter: '.$active->implode(' | ');
    }

    private function schoolProfile(): array
    {
        $school = IdentitasSekolah::query()->first();

        return [
            'name' => $school?->nama_sekolah ?? 'LAKS-Bel',
            'meta' => collect([
                $school?->npsn ? 'NPSN: '.$school->npsn : null,
                $school?->alamat,
                $school?->telepon ? 'Telp: '.$school->telepon : null,
                $school?->email ? 'Email: '.$school->email : null,
                $school?->website ? 'Web: '.$school->website : null,
                $school?->nama_kepala_sekolah ? 'Kepala Sekolah: '.$school->nama_kepala_sekolah : null,
            ])->filter()->implode(' | '),
        ];
    }
}
