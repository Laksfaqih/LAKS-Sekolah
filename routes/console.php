<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use App\Models\PengaturanBel;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('bells:check', function () {
    $now = now()->format('H:i:00');

    $bells = PengaturanBel::query()
        ->where('is_active', true)
        ->whereTime('jam_bunyi', $now)
        ->get();

    if ($bells->isEmpty()) {
        $this->info('Tidak ada jadwal bel aktif pada waktu ini.');

        return;
    }

    foreach ($bells as $bell) {
        Log::info('Bel otomatis terpicu.', [
            'pengaturan_bel_id' => $bell->id,
            'nama' => $bell->nama,
            'tipe_bel' => $bell->tipe_bel,
            'jam_bunyi' => $bell->jam_bunyi,
            'audio_path' => $bell->audio_path,
        ]);

        $this->info("Bel {$bell->nama} ({$bell->tipe_bel}) terpicu.");
    }
})->purpose('Memeriksa dan memicu jadwal bel aktif');

Schedule::command('bells:check')->everyMinute();
