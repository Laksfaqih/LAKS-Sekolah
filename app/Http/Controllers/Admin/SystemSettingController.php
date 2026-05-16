<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SystemSettingRequest;
use App\Models\IdentitasSekolah;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SystemSettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.system-settings.edit', [
            'setting' => IdentitasSekolah::query()->firstOrCreate(
                ['id' => 1],
                ['nama_sekolah' => 'SMK LAKS Bel'],
            ),
        ]);
    }

    public function update(SystemSettingRequest $request): RedirectResponse
    {
        $setting = IdentitasSekolah::query()->firstOrCreate(
            ['id' => 1],
            ['nama_sekolah' => 'SMK LAKS Bel'],
        );

        $setting->update($request->validated());

        return redirect()->route('admin.system-settings.edit')
            ->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
