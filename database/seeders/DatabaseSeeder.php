<?php

namespace Database\Seeders;

use App\Models\IdentitasSekolah;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin LAKS-Bel',
                'password' => Hash::make('asdasd'),
                'role' => User::ROLE_ADMIN,
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'guru@gmail.com'],
            [
                'name' => 'Guru LAKS-Bel',
                'password' => Hash::make('asdasd'),
                'role' => User::ROLE_GURU,
            ],
        );

        $guruUser = User::query()->where('email', 'guru@gmail.com')->first();

        if ($guruUser !== null) {
            Guru::query()->updateOrCreate(
                ['user_id' => $guruUser->id],
                [
                    'nama' => 'Guru LAKS-Bel',
                    'nip' => 'GR-001',
                    'email' => 'guru@gmail.com',
                    'no_hp' => '081234567890',
                    'alamat' => 'Alamat guru belum diatur',
                    'is_active' => true,
                ],
            );
        }

        User::query()->updateOrCreate(
            ['email' => 'kepsek@gmail.com'],
            [
                'name' => 'Kepala Sekolah LAKS-Bel',
                'password' => Hash::make('asdasd'),
                'role' => User::ROLE_KEPSEK,
            ],
        );

        IdentitasSekolah::query()->updateOrCreate(
            ['id' => 1],
            [
                'nama_sekolah' => 'SMK LAKS Bel',
                'npsn' => '12345678',
                'alamat' => 'Alamat sekolah belum diatur',
                'telepon' => null,
                'email' => null,
                'website' => null,
                'nama_kepala_sekolah' => 'Kepala Sekolah LAKS-Bel',
            ],
        );
    }
}
