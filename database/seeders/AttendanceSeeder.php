<?php

namespace Database\Seeders;

use App\Models\JadwalPelajaran;
use App\Models\PresensiGuru;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = JadwalPelajaran::query()
            ->with('guru')
            ->get()
            ->groupBy('hari');

        $statusCycle = ['hadir', 'hadir', 'hadir', 'izin', 'hadir', 'sakit'];

        for ($offset = 0; $offset < 21; $offset++) {
            $date = today()->subDays($offset);

            if ($date->dayOfWeekIso > 6) {
                continue;
            }

            $hari = JadwalPelajaran::hariIni(Carbon::parse($date));

            $dailySchedules = $schedules->get($hari, collect())
                ->sortBy('jam_pelajaran_id')
                ->take(4)
                ->values();

            foreach ($dailySchedules as $index => $jadwal) {
                $status = $statusCycle[($offset + $index) % count($statusCycle)];

                PresensiGuru::query()->updateOrCreate(
                    [
                        'guru_id' => $jadwal->guru_id,
                        'jadwal_pelajaran_id' => $jadwal->id,
                        'tanggal' => $date->toDateString(),
                    ],
                    [
                        'status' => $status,
                        'catatan' => $this->catatanUntukStatus($status, $date),
                    ],
                );
            }
        }
    }

    private function catatanUntukStatus(string $status, Carbon $date): ?string
    {
        return match ($status) {
            'izin' => 'Izin keperluan keluarga pada '.$date->format('d-m-Y'),
            'sakit' => 'Sakit demam, melapor melalui admin piket',
            default => 'Mengajar sesuai jadwal.',
        };
    }
}
