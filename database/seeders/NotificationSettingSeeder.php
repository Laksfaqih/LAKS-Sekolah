<?php

namespace Database\Seeders;

use App\Models\NotificationSetting;
use Illuminate\Database\Seeder;

class NotificationSettingSeeder extends Seeder
{
    public function run(): void
    {
        NotificationSetting::set(
            NotificationSetting::KEY_SCHEDULE_REMINDER_MINUTES,
            NotificationSetting::DEFAULT_SCHEDULE_REMINDER_MINUTES
        );
    }
}
