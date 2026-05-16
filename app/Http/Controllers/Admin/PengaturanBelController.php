<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PengaturanBelRequest;
use App\Models\PengaturanBel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PengaturanBelController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());

        $pengaturanBels = PengaturanBel::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nama', 'like', "%{$search}%")
                        ->orWhere('tipe_bel', 'like', "%{$search}%");
                });
            })
            ->orderBy('jam_bunyi')
            ->paginate(10)
            ->withQueryString();

        return view('admin.pengaturan-bel.index', [
            'pengaturanBels' => $pengaturanBels,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.pengaturan-bel.create', [
            'pengaturanBel' => new PengaturanBel(),
            'tipeOptions' => PengaturanBel::tipeOptions(),
        ]);
    }

    public function store(PengaturanBelRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('audio_file');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('audio_file')) {
            $data['audio_path'] = $request->file('audio_file')->store('bells', 'public');
        }

        PengaturanBel::query()->create($data);

        return redirect()->route('admin.pengaturan-bel.index')
            ->with('success', 'Pengaturan bel berhasil ditambahkan.');
    }

    public function edit(PengaturanBel $pengaturanBel): View
    {
        return view('admin.pengaturan-bel.edit', [
            'pengaturanBel' => $pengaturanBel,
            'tipeOptions' => PengaturanBel::tipeOptions(),
        ]);
    }

    public function update(PengaturanBelRequest $request, PengaturanBel $pengaturanBel): RedirectResponse
    {
        $data = $request->safe()->except('audio_file');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('audio_file')) {
            if ($pengaturanBel->audio_path) {
                Storage::disk('public')->delete($pengaturanBel->audio_path);
            }

            $data['audio_path'] = $request->file('audio_file')->store('bells', 'public');
        }

        $pengaturanBel->update($data);

        return redirect()->route('admin.pengaturan-bel.index')
            ->with('success', 'Pengaturan bel berhasil diperbarui.');
    }

    public function destroy(PengaturanBel $pengaturanBel): RedirectResponse
    {
        if ($pengaturanBel->audio_path) {
            Storage::disk('public')->delete($pengaturanBel->audio_path);
        }

        $pengaturanBel->delete();

        return redirect()->route('admin.pengaturan-bel.index')
            ->with('success', 'Pengaturan bel berhasil dihapus.');
    }
}
