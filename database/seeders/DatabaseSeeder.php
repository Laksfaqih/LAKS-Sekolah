<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SchoolIdentitySeeder::class,
            UserAndGuruSeeder::class,
            AcademicReferenceSeeder::class,
            ScheduleSeeder::class,
            AttendanceSeeder::class,
            BellSettingSeeder::class,
            NotificationSettingSeeder::class,
        ]);
    }
}
