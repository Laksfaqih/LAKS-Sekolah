<?php

use App\Models\JadwalPelajaran;
use Illuminate\Support\Carbon;

test('hari ini returns minggu on sunday', function () {
    $sunday = Carbon::parse('2026-05-17 09:00:00', 'Asia/Jakarta');

    expect(JadwalPelajaran::hariIni($sunday))->toBe('Minggu');
});

test('hari ini still returns school day names on weekdays', function () {
    $monday = Carbon::parse('2026-05-11 09:00:00', 'Asia/Jakarta');

    expect(JadwalPelajaran::hariIni($monday))->toBe('Senin');
});
