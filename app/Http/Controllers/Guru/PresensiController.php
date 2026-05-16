<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\PresensiGuruRequest;
use App\Models\JadwalPelajaran;
use App\Models\PresensiGuru;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PresensiController extends Controller
{
    public function index(Request $request): View
    {
        $guru = $request->user()?->guru;
        $today = JadwalPelajaran::hariIni();
        $currentTime = now()->format('H:i');

        $jadwalAktif = null;
        $riwayat = collect();
        $presensiAktif = null;

        if ($guru !== null) {
            $jadwalAktif = $guru->jadwalPelajarans()
                ->with(['kelas', 'mataPelajaran', 'jamPelajaran'])
                ->where('hari', $today)
                ->get()
                ->first(function ($jadwal) use ($currentTime) {
                    return $jadwal->jamPelajaran->jam_mulai <= $currentTime
                        && $jadwal->jamPelajaran->jam_selesai >= $currentTime;
                });

            $riwayat = $guru->presensiGurus()
                ->with(['jadwalPelajaran.kelas', 'jadwalPelajaran.mataPelajaran'])
                ->latest('tanggal')
                ->latest()
                ->limit(20)
                ->get();

            if ($jadwalAktif !== null) {
                $presensiAktif = PresensiGuru::query()
                    ->where('guru_id', $guru->id)
                    ->where('jadwal_pelajaran_id', $jadwalAktif->id)
                    ->whereDate('tanggal', today())
                    ->first();
            }
        }

        $statusOptions = PresensiGuru::statusOptions();

        return view('guru.presensi.index', compact('guru', 'today', 'jadwalAktif', 'riwayat', 'presensiAktif', 'statusOptions'));
    }

    public function store(PresensiGuruRequest $request): RedirectResponse
    {
        $guru = $request->user()?->guru;

        abort_if($guru === null, 404);

        $jadwalAktif = $guru->jadwalPelajarans()
            ->with('jamPelajaran')
            ->where('hari', JadwalPelajaran::hariIni())
            ->get()
            ->first(function ($jadwal) {
                $currentTime = now()->format('H:i');

                return $jadwal->jamPelajaran->jam_mulai <= $currentTime
                    && $jadwal->jamPelajaran->jam_selesai >= $currentTime;
            });

        if ($jadwalAktif === null) {
            return redirect()->route('guru.presensi.index')
                ->with('error', 'Tidak ada jadwal aktif untuk melakukan presensi.');
        }

        PresensiGuru::query()->updateOrCreate(
            [
                'guru_id' => $guru->id,
                'jadwal_pelajaran_id' => $jadwalAktif->id,
                'tanggal' => today()->toDateString(),
            ],
            [
                'status' => $request->string('status')->toString(),
                'catatan' => $request->input('catatan'),
            ],
        );

        return redirect()->route('guru.presensi.index')
            ->with('success', 'Presensi mengajar berhasil disimpan.');
    }
}
