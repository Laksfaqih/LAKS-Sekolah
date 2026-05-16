<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonitoringJadwalController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());

        $jadwalPelajarans = JadwalPelajaran::query()
            ->with(['guru', 'mataPelajaran', 'kelas', 'jamPelajaran'])
            ->when($request->filled('hari'), fn ($query) => $query->where('hari', $request->string('hari')->toString()))
            ->when($request->filled('guru_id'), fn ($query) => $query->where('guru_id', $request->integer('guru_id')))
            ->when($request->filled('kelas_id'), fn ($query) => $query->where('kelas_id', $request->integer('kelas_id')))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('hari', 'like', "%{$search}%")
                        ->orWhereHas('guru', fn ($guruQuery) => $guruQuery->where('nama', 'like', "%{$search}%"))
                        ->orWhereHas('kelas', fn ($kelasQuery) => $kelasQuery->where('nama', 'like', "%{$search}%"))
                        ->orWhereHas('mataPelajaran', fn ($mapelQuery) => $mapelQuery->where('nama', 'like', "%{$search}%"));
                });
            })
            ->orderBy('hari')
            ->orderBy('jam_pelajaran_id')
            ->paginate(10)
            ->withQueryString();

        return view('kepsek.monitoring.index', [
            'jadwalPelajarans' => $jadwalPelajarans,
            'search' => $search,
            'hariOptions' => JadwalPelajaran::hariOptions(),
            'guruOptions' => Guru::query()->orderBy('nama')->get(),
            'kelasOptions' => Kelas::query()->orderBy('nama')->get(),
        ]);
    }
}
