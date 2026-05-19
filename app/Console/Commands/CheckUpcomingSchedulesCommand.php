<?php

namespace App\Console\Commands;

use App\Models\JadwalPelajaran;
use App\Models\NotificationSetting;
use App\Notifications\ScheduleReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckUpcomingSchedulesCommand extends Command
{
    protected $signature = 'schedules:check-upcoming';

    protected $description = 'Memeriksa jadwal yang akan dimulai dan mengirim notifikasi ke guru';

    public function handle(): int
    {
        $reminderMinutes = NotificationSetting::getScheduleReminderMinutes();
        $targetTime = now()->addMinutes($reminderMinutes)->format('H:i');
        $today = JadwalPelajaran::hariIni();

        $jadwals = JadwalPelajaran::query()
            ->with(['guru.user', 'mataPelajaran', 'kelas', 'jamPelajaran'])
            ->where('hari', $today)
            ->whereHas('jamPelajaran', function ($query) use ($targetTime) {
                $query->whereRaw("TIME_FORMAT(jam_mulai, '%H:%i') = ?", [$targetTime]);
            })
            ->get();

        if ($jadwals->isEmpty()) {
            $this->info("Tidak ada jadwal yang dimulai dalam {$reminderMinutes} menit.");

            return self::SUCCESS;
        }

        $notificationsSent = 0;

        foreach ($jadwals as $jadwal) {
            $user = $jadwal->guru?->user;

            if ($user === null) {
                $this->warn("Guru untuk jadwal ID {$jadwal->id} tidak memiliki user terkait.");

                continue;
            }

            // Check for duplicate notification in the same minute
            $existingNotification = $user->notifications()
                ->where('type', ScheduleReminderNotification::class)
                ->whereRaw("JSON_EXTRACT(data, '$.jadwal_pelajaran_id') = ?", [$jadwal->id])
                ->where('created_at', '>=', now()->startOfMinute())
                ->where('created_at', '<', now()->addMinute()->startOfMinute())
                ->exists();

            if ($existingNotification) {
                $this->info("Notifikasi untuk jadwal ID {$jadwal->id} sudah dikirim pada menit ini.");

                continue;
            }

            $user->notify(new ScheduleReminderNotification($jadwal, $reminderMinutes));

            Log::info('Notifikasi jadwal dikirim.', [
                'user_id' => $user->id,
                'jadwal_pelajaran_id' => $jadwal->id,
                'mata_pelajaran' => $jadwal->mataPelajaran->nama,
                'kelas' => $jadwal->kelas->nama,
                'jam_mulai' => $jadwal->jamPelajaran->jam_mulai,
                'reminder_minutes' => $reminderMinutes,
            ]);

            $this->info("Notifikasi dikirim ke {$user->name} untuk jadwal {$jadwal->mataPelajaran->nama} di kelas {$jadwal->kelas->nama}.");

            $notificationsSent++;
        }

        $this->info("Total {$notificationsSent} notifikasi dikirim.");

        return self::SUCCESS;
    }
}
