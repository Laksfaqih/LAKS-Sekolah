<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $guruByNip = Guru::query()->get()->keyBy('nip');
        $mapelByKode = MataPelajaran::query()->get()->keyBy('kode');
        $kelasByNama = Kelas::query()->get()->keyBy('nama');
        $jamByUrutan = JamPelajaran::query()->get()->keyBy('urutan');

        $jadwals = [
            ['hari' => 'Senin', 'nip' => '198705102010011001', 'kode' => 'RPL', 'kelas' => 'X RPL 1', 'jam' => 1],
            ['hari' => 'Senin', 'nip' => '198802142011012002', 'kode' => 'BIN', 'kelas' => 'X RPL 2', 'jam' => 1],
            ['hari' => 'Senin', 'nip' => '198501032009011003', 'kode' => 'MTK', 'kelas' => 'XI AKL 1', 'jam' => 1],
            ['hari' => 'Senin', 'nip' => '199001212015022004', 'kode' => 'JK', 'kelas' => 'XII TKJ 1', 'jam' => 1],
            ['hari' => 'Senin', 'nip' => '198909302014021005', 'kode' => 'BIG', 'kelas' => 'XI RPL 1', 'jam' => 2],
            ['hari' => 'Senin', 'nip' => '198611112008012006', 'kode' => 'BD', 'kelas' => 'XII RPL 1', 'jam' => 2],
            ['hari' => 'Selasa', 'nip' => '198705102010011001', 'kode' => 'BD', 'kelas' => 'XI RPL 1', 'jam' => 3],
            ['hari' => 'Selasa', 'nip' => '198802142011012002', 'kode' => 'PAI', 'kelas' => 'XI AKL 1', 'jam' => 3],
            ['hari' => 'Selasa', 'nip' => '198501032009011003', 'kode' => 'PKK', 'kelas' => 'XII RPL 1', 'jam' => 3],
            ['hari' => 'Selasa', 'nip' => '199001212015022004', 'kode' => 'JK', 'kelas' => 'XII TKJ 1', 'jam' => 4],
            ['hari' => 'Rabu', 'nip' => '198705102010011001', 'kode' => 'RPL', 'kelas' => 'X RPL 2', 'jam' => 4],
            ['hari' => 'Rabu', 'nip' => '198802142011012002', 'kode' => 'BIN', 'kelas' => 'XI RPL 1', 'jam' => 5],
            ['hari' => 'Rabu', 'nip' => '198501032009011003', 'kode' => 'MTK', 'kelas' => 'X RPL 1', 'jam' => 5],
            ['hari' => 'Rabu', 'nip' => '198909302014021005', 'kode' => 'BIG', 'kelas' => 'XI AKL 1', 'jam' => 6],
            ['hari' => 'Kamis', 'nip' => '198611112008012006', 'kode' => 'BD', 'kelas' => 'XII RPL 1', 'jam' => 6],
            ['hari' => 'Kamis', 'nip' => '199001212015022004', 'kode' => 'JK', 'kelas' => 'XII TKJ 1', 'jam' => 7],
            ['hari' => 'Kamis', 'nip' => '198802142011012002', 'kode' => 'PAI', 'kelas' => 'X RPL 1', 'jam' => 7],
            ['hari' => 'Kamis', 'nip' => '198501032009011003', 'kode' => 'MTK', 'kelas' => 'X RPL 2', 'jam' => 8],
            ['hari' => 'Jumat', 'nip' => '198705102010011001', 'kode' => 'PKK', 'kelas' => 'XI AKL 1', 'jam' => 1],
            ['hari' => 'Jumat', 'nip' => '198909302014021005', 'kode' => 'BIG', 'kelas' => 'XII TKJ 1', 'jam' => 2],
            ['hari' => 'Jumat', 'nip' => '198611112008012006', 'kode' => 'RPL', 'kelas' => 'XI RPL 1', 'jam' => 2],
            ['hari' => 'Sabtu', 'nip' => '198802142011012002', 'kode' => 'BIN', 'kelas' => 'XII RPL 1', 'jam' => 3],
            ['hari' => 'Sabtu', 'nip' => '198501032009011003', 'kode' => 'MTK', 'kelas' => 'XI AKL 1', 'jam' => 4],
            ['hari' => 'Sabtu', 'nip' => '199001212015022004', 'kode' => 'JK', 'kelas' => 'XII TKJ 1', 'jam' => 5],
            ['hari' => JadwalPelajaran::hariIni(), 'nip' => '198705102010011001', 'kode' => 'RPL', 'kelas' => 'XII RPL 1', 'jam' => 9],
        ];

        foreach ($jadwals as $payload) {
            $guru = $guruByNip->get($payload['nip']);
            $mapel = $mapelByKode->get($payload['kode']);
            $kelas = $kelasByNama->get($payload['kelas']);
            $jam = $jamByUrutan->get($payload['jam']);

            if ($guru === null || $mapel === null || $kelas === null || $jam === null) {
                continue;
            }

            JadwalPelajaran::query()->updateOrCreate(
                [
                    'hari' => $payload['hari'],
                    'guru_id' => $guru->id,
                    'mata_pelajaran_id' => $mapel->id,
                    'kelas_id' => $kelas->id,
                    'jam_pelajaran_id' => $jam->id,
                ],
            );
        }
    }
}
