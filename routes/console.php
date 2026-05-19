<?php

use App\Models\BellTrigger;
use App\Models\PengaturanBel;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('bells:check', function () {
    $now = now()->seconds(0);
    $formattedNow = $now->format('H:i:00');

    $bells = PengaturanBel::query()
        ->where('is_active', true)
        ->whereTime('jam_bunyi', $formattedNow)
        ->get();

    if ($bells->isEmpty()) {
        $this->info('Tidak ada jadwal bel aktif pada waktu ini.');

        return;
    }

    foreach ($bells as $bell) {
        $trigger = BellTrigger::query()->firstOrCreate(
            [
                'pengaturan_bel_id' => $bell->id,
                'triggered_at' => $now,
            ],
            [
                'nama' => $bell->nama,
                'tipe_bel' => $bell->tipe_bel,
                'audio_path' => $bell->audio_path,
                'status' => $bell->audio_path
                    ? BellTrigger::STATUS_PENDING
                    : BellTrigger::STATUS_SKIPPED_NO_AUDIO,
                'failure_reason' => $bell->audio_path ? null : 'File audio belum tersedia.',
            ],
        );

        if (! $trigger->wasRecentlyCreated) {
            $this->info("Bel {$bell->nama} ({$bell->tipe_bel}) sudah diproses untuk menit ini.");

            continue;
        }

        Log::info('Bel otomatis terpicu.', [
            'pengaturan_bel_id' => $bell->id,
            'nama' => $bell->nama,
            'tipe_bel' => $bell->tipe_bel,
            'jam_bunyi' => $bell->jam_bunyi,
            'audio_path' => $bell->audio_path,
            'trigger_id' => $trigger->id,
            'status' => $trigger->status,
        ]);

        if ($trigger->status === BellTrigger::STATUS_SKIPPED_NO_AUDIO) {
            $this->warn("Bel {$bell->nama} ({$bell->tipe_bel}) dilewati karena file audio belum ada.");

            continue;
        }

        $this->info("Bel {$bell->nama} ({$bell->tipe_bel}) siap diputar.");
    }
})->purpose('Memeriksa dan memicu jadwal bel aktif');

Schedule::command('bells:check')->everyMinute();
Schedule::command('schedules:check-upcoming')->everyMinute();
