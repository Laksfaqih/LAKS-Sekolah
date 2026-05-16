<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\PengaturanBel;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $todayName = now()->locale('id')->dayName;

        return view('admin.dashboard', [
            'guruCount' => Guru::query()->count(),
            'jadwalCount' => JadwalPelajaran::query()->count(),
            'belAktif' => PengaturanBel::query()->where('is_active', true)->exists(),
            'jadwalHariIni' => JadwalPelajaran::query()
                ->with(['guru', 'mataPelajaran', 'kelas', 'jamPelajaran'])
                ->where('hari', $todayName)
                ->orderBy('jam_pelajaran_id')
                ->limit(10)
                ->get(),
            'currentTime' => now()->format('H:i:s'),
        ]);
    }
}
