<?php

namespace Database\Seeders;

use App\Models\PengaturanBel;
use Illuminate\Database\Seeder;

class BellSettingSeeder extends Seeder
{
    public function run(): void
    {
        $bells = [
            [
                'nama' => 'Bel Masuk Pagi',
                'tipe_bel' => 'masuk',
                'jam_bunyi' => '06:55',
                'audio_path' => null,
                'is_active' => true,
            ],
            [
                'nama' => 'Bel Pergantian Jam 1',
                'tipe_bel' => 'pergantian_jam',
                'jam_bunyi' => '07:45',
                'audio_path' => null,
                'is_active' => true,
            ],
            [
                'nama' => 'Bel Istirahat',
                'tipe_bel' => 'istirahat',
                'jam_bunyi' => '09:15',
                'audio_path' => null,
                'is_active' => true,
            ],
            [
                'nama' => 'Bel Masuk Setelah Istirahat',
                'tipe_bel' => 'masuk',
                'jam_bunyi' => '09:30',
                'audio_path' => null,
                'is_active' => true,
            ],
            [
                'nama' => 'Bel Pulang',
                'tipe_bel' => 'pulang',
                'jam_bunyi' => '14:00',
                'audio_path' => null,
                'is_active' => true,
            ],
            [
                'nama' => 'Bel Cadangan Sore',
                'tipe_bel' => 'pulang',
                'jam_bunyi' => '15:00',
                'audio_path' => null,
                'is_active' => false,
            ],
        ];

        foreach ($bells as $payload) {
            PengaturanBel::query()->updateOrCreate(
                ['nama' => $payload['nama']],
                $payload,
            );
        }
    }
}
