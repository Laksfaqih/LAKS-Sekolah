<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalMengajarController extends Controller
{
    public function index(Request $request): View
    {
        $guru = $request->user()?->guru;
        $selectedHari = $request->string('hari')->toString();
        $hariOptions = JadwalPelajaran::hariOptions();

        $jadwalPelajarans = collect();

        if ($guru !== null) {
            $jadwalPelajarans = $guru->jadwalPelajarans()
                ->with(['kelas', 'mataPelajaran', 'jamPelajaran'])
                ->when($selectedHari !== '', fn ($query) => $query->where('hari', $selectedHari))
                ->orderBy('hari')
                ->orderBy('jam_pelajaran_id')
                ->get();
        }

        return view('guru.jadwal.index', compact('guru', 'selectedHari', 'hariOptions', 'jadwalPelajarans'));
    }
}
