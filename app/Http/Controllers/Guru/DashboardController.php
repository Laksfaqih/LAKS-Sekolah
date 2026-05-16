<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\PresensiGuru;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $guru = auth()->user()?->guru;
        $today = JadwalPelajaran::hariIni();
        $currentTime = now()->format('H:i');

        $jadwalHariIni = collect();
        $jadwalAktif = null;
        $presensiHariIni = null;

        if ($guru !== null) {
            $jadwalHariIni = $guru->jadwalPelajarans()
                ->with(['kelas', 'mataPelajaran', 'jamPelajaran'])
                ->where('hari', $today)
                ->orderBy('jam_pelajaran_id')
                ->get();

            $jadwalAktif = $jadwalHariIni->first(function ($jadwal) use ($currentTime) {
                return $jadwal->jamPelajaran->jam_mulai <= $currentTime
                    && $jadwal->jamPelajaran->jam_selesai >= $currentTime;
            });

            $presensiHariIni = PresensiGuru::query()
                ->where('guru_id', $guru->id)
                ->whereDate('tanggal', today())
                ->latest()
                ->first();
        }

        return view('guru.dashboard', compact('guru', 'today', 'jadwalHariIni', 'jadwalAktif', 'presensiHariIni'));
    }
}
