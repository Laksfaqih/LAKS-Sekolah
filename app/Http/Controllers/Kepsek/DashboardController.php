<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\PengaturanBel;
use App\Models\PresensiGuru;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = JadwalPelajaran::hariIni();
        $currentTime = now()->format('H:i');

        $jadwalHariIni = JadwalPelajaran::query()
            ->with(['guru', 'kelas', 'mataPelajaran', 'jamPelajaran'])
            ->where('hari', $today)
            ->orderBy('jam_pelajaran_id')
            ->get();

        $jadwalAktif = $jadwalHariIni->first(function ($jadwal) use ($currentTime) {
            return $jadwal->jamPelajaran->jam_mulai <= $currentTime
                && $jadwal->jamPelajaran->jam_selesai >= $currentTime;
        });

        $guruHadirCount = PresensiGuru::query()
            ->whereDate('tanggal', today())
            ->where('status', 'hadir')
            ->distinct('guru_id')
            ->count('guru_id');

        return view('kepsek.dashboard', [
            'today' => $today,
            'jadwalHariIni' => $jadwalHariIni,
            'jadwalAktif' => $jadwalAktif,
            'belAktif' => PengaturanBel::query()->where('is_active', true)->exists(),
            'guruHadirCount' => $guruHadirCount,
            'guruCount' => Guru::query()->count(),
        ]);
    }
}
