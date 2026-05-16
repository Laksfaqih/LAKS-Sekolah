<?php

namespace Database\Seeders;

use App\Models\IdentitasSekolah;
use Illuminate\Database\Seeder;

class SchoolIdentitySeeder extends Seeder
{
    public function run(): void
    {
        IdentitasSekolah::query()->updateOrCreate(
            ['id' => 1],
            [
                'nama_sekolah' => 'SMK LAKS Bel',
                'npsn' => '12345678',
                'alamat' => 'Jl. Pendidikan No. 10, Kecamatan Sukamaju, Kota Bandung',
                'telepon' => '(022) 555-1200',
                'email' => 'info@smklaksbel.test',
                'website' => 'https://smklaksbel.test',
                'nama_kepala_sekolah' => 'Dra. Ratna Wulandari, M.Pd',
            ],
        );
    }
}
