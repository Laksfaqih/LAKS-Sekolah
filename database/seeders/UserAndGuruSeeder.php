<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAndGuruSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin LAKS-Bel',
                'email' => 'admin@gmail.com',
                'role' => User::ROLE_ADMIN,
            ],
            [
                'name' => 'Kepala Sekolah LAKS-Bel',
                'email' => 'kepsek@gmail.com',
                'role' => User::ROLE_KEPSEK,
            ],
            [
                'name' => 'Ahmad Fadli',
                'email' => 'guru@gmail.com',
                'role' => User::ROLE_GURU,
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'guru1@gmail.com',
                'role' => User::ROLE_GURU,
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'guru2@gmail.com',
                'role' => User::ROLE_GURU,
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'guru3@gmail.com',
                'role' => User::ROLE_GURU,
            ],
            [
                'name' => 'Rizky Pratama',
                'email' => 'guru4@gmail.com',
                'role' => User::ROLE_GURU,
            ],
        ];

        foreach ($users as $payload) {
            User::query()->updateOrCreate(
                ['email' => $payload['email']],
                [
                    'name' => $payload['name'],
                    'password' => Hash::make('asdasd'),
                    'role' => $payload['role'],
                    'email_verified_at' => now(),
                ],
            );
        }

        $gurus = [
            [
                'user_email' => 'guru@gmail.com',
                'nama' => 'Ahmad Fadli',
                'nip' => '198705102010011001',
                'email' => 'guru@gmail.com',
                'no_hp' => '081200000001',
                'alamat' => 'Jl. Kenanga No. 1, Bandung',
                'is_active' => true,
            ],
            [
                'user_email' => 'guru1@gmail.com',
                'nama' => 'Siti Rahma',
                'nip' => '198802142011012002',
                'email' => 'guru1@gmail.com',
                'no_hp' => '081200000002',
                'alamat' => 'Jl. Melati No. 2, Bandung',
                'is_active' => true,
            ],
            [
                'user_email' => 'guru2@gmail.com',
                'nama' => 'Budi Santoso',
                'nip' => '198501032009011003',
                'email' => 'guru2@gmail.com',
                'no_hp' => '081200000003',
                'alamat' => 'Jl. Mawar No. 3, Bandung',
                'is_active' => true,
            ],
            [
                'user_email' => 'guru3@gmail.com',
                'nama' => 'Dewi Lestari',
                'nip' => '199001212015022004',
                'email' => 'guru3@gmail.com',
                'no_hp' => '081200000004',
                'alamat' => 'Jl. Flamboyan No. 4, Bandung',
                'is_active' => true,
            ],
            [
                'user_email' => 'guru4@gmail.com',
                'nama' => 'Rizky Pratama',
                'nip' => '198909302014021005',
                'email' => 'guru4@gmail.com',
                'no_hp' => '081200000005',
                'alamat' => 'Jl. Anggrek No. 5, Bandung',
                'is_active' => true,
            ],
            [
                'user_email' => null,
                'nama' => 'Nina Kartika',
                'nip' => '198611112008012006',
                'email' => 'nina.kartika@smklaksbel.test',
                'no_hp' => '081200000006',
                'alamat' => 'Jl. Cendana No. 6, Bandung',
                'is_active' => true,
            ],
            [
                'user_email' => null,
                'nama' => 'Yusuf Hidayat',
                'nip' => '198304042007011007',
                'email' => 'yusuf.hidayat@smklaksbel.test',
                'no_hp' => '081200000007',
                'alamat' => 'Jl. Cemara No. 7, Bandung',
                'is_active' => false,
            ],
        ];

        foreach ($gurus as $payload) {
            $userId = null;

            if ($payload['user_email'] !== null) {
                $userId = User::query()->where('email', $payload['user_email'])->value('id');
            }

            Guru::query()->updateOrCreate(
                ['nip' => $payload['nip']],
                [
                    'user_id' => $userId,
                    'nama' => $payload['nama'],
                    'email' => $payload['email'],
                    'no_hp' => $payload['no_hp'],
                    'alamat' => $payload['alamat'],
                    'is_active' => $payload['is_active'],
                ],
            );
        }
    }
}
