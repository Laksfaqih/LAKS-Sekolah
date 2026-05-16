<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuruProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function edit(): View
    {
        return view('guru.profil.edit', [
            'guru' => auth()->user()?->guru,
        ]);
    }

    public function update(GuruProfileRequest $request): RedirectResponse
    {
        $guru = $request->user()?->guru;

        abort_if($guru === null, 404);

        $guru->update($request->validated());

        return redirect()->route('guru.profil.edit')
            ->with('success', 'Profil guru berhasil diperbarui.');
    }
}
