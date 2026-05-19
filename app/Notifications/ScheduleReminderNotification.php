<?php

namespace App\Notifications;

use App\Models\JadwalPelajaran;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ScheduleReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected JadwalPelajaran $jadwal,
        protected int $minutesBefore
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $mataPelajaran = $this->jadwal->mataPelajaran->nama;
        $kelas = $this->jadwal->kelas->nama;
        $jamMulai = substr($this->jadwal->jamPelajaran->jam_mulai, 0, 5);
        $jamSelesai = substr($this->jadwal->jamPelajaran->jam_selesai, 0, 5);
        $hari = $this->jadwal->hari;

        return [
            'jadwal_pelajaran_id' => $this->jadwal->id,
            'mata_pelajaran' => $mataPelajaran,
            'kelas' => $kelas,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'hari' => $hari,
            'minutes_before' => $this->minutesBefore,
            'message' => "Jadwal mengajar {$mataPelajaran} di kelas {$kelas} akan dimulai dalam {$this->minutesBefore} menit (pukul {$jamMulai}).",
        ];
    }
}
