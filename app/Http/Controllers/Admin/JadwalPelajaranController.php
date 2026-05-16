<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\JadwalPelajaranRequest;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        return view('admin.jadwal-pelajaran.index', [
            'jadwalPelajarans' => $jadwalPelajarans,
            'search' => $search,
            'hariOptions' => JadwalPelajaran::hariOptions(),
            'guruOptions' => Guru::query()->orderBy('nama')->get(),
            'kelasOptions' => Kelas::query()->orderBy('nama')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.jadwal-pelajaran.create', [
            'jadwalPelajaran' => new JadwalPelajaran(),
            ...$this->formOptions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JadwalPelajaranRequest $request): RedirectResponse
    {
        JadwalPelajaran::query()->create($request->validated());

        return redirect()->route('admin.jadwal-pelajaran.index')
            ->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalPelajaran $jadwalPelajaran): View
    {
        return view('admin.jadwal-pelajaran.edit', [
            'jadwalPelajaran' => $jadwalPelajaran,
            ...$this->formOptions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JadwalPelajaranRequest $request, JadwalPelajaran $jadwalPelajaran): RedirectResponse
    {
        $jadwalPelajaran->update($request->validated());

        return redirect()->route('admin.jadwal-pelajaran.index')
            ->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalPelajaran $jadwalPelajaran): RedirectResponse
    {
        $jadwalPelajaran->delete();

        return redirect()->route('admin.jadwal-pelajaran.index')
            ->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }

    private function formOptions(): array
    {
        return [
            'guruOptions' => Guru::query()->orderBy('nama')->get(),
            'mataPelajaranOptions' => MataPelajaran::query()->orderBy('nama')->get(),
            'kelasOptions' => Kelas::query()->orderBy('nama')->get(),
            'jamPelajaranOptions' => JamPelajaran::query()->orderBy('urutan')->get(),
            'hariOptions' => JadwalPelajaran::hariOptions(),
        ];
    }
}
