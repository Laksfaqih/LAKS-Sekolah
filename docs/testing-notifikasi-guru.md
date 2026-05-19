# Testing Notifikasi Jadwal Guru

Dokumentasi untuk menguji fitur notifikasi pengingat jadwal mengajar untuk guru.

## Gambaran Umum

Sistem notifikasi akan mengirimkan pengingat ke guru X menit sebelum jadwal mengajar dimulai (default: 10 menit). Notifikasi ditampilkan sebagai:
- **Badge** di ikon lonceng (jumlah notifikasi belum dibaca)
- **Dropdown** daftar notifikasi terbaru
- **Toast popup** yang muncul otomatis saat ada notifikasi baru
- **Halaman full list** semua notifikasi

## Akun Test

| Role | Email | Password |
|------|-------|----------|
| Guru | guru@gmail.com | asdasd |

## Cara Kerja

```
[Scheduler (tiap menit)]
    -> php artisan schedules:check-upcoming
    -> Query jadwal yang dimulai dalam 10 menit
    -> Kirim notifikasi ke guru terkait
    -> Simpan di tabel notifications

[Frontend (tiap 30 detik)]
    -> Polling ke /guru/notifications/poll
    -> Tampilkan toast untuk notifikasi baru
    -> Update badge count
```

## Langkah Testing Manual

### 1. Persiapan - Buat Jadwal Test

Buka terminal dan jalankan command berikut untuk membuat jadwal yang dimulai 10 menit dari sekarang:

```bash
php artisan tinker
```

Lalu paste kode berikut:

```php
use App\Models\JamPelajaran;
use App\Models\JadwalPelajaran;
use App\Models\User;

$now = now();
$targetTime = $now->copy()->addMinutes(10);
$today = JadwalPelajaran::hariIni();

echo "Waktu sekarang: " . $now->format('H:i') . "\n";
echo "Target waktu (10 menit lagi): " . $targetTime->format('H:i') . "\n";
echo "Hari: $today\n\n";

// Buat jam pelajaran test
$jamTest = JamPelajaran::updateOrCreate(
    ['nama' => 'Test Notifikasi'],
    [
        'urutan' => 99,
        'jam_mulai' => $targetTime->format('H:i:00'),
        'jam_selesai' => $targetTime->copy()->addMinutes(45)->format('H:i:00'),
    ]
);

// Ambil data yang diperlukan
$guru = User::where('role', 'guru')->first()->guru;
$mapel = \App\Models\MataPelajaran::first();
$kelas = \App\Models\Kelas::first();

// Buat jadwal test
$jadwalTest = JadwalPelajaran::updateOrCreate(
    [
        'guru_id' => $guru->id,
        'jam_pelajaran_id' => $jamTest->id,
        'hari' => $today,
    ],
    [
        'mata_pelajaran_id' => $mapel->id,
        'kelas_id' => $kelas->id,
    ]
);

echo "Jadwal Test dibuat!\n";
echo "  - Guru: {$guru->nama}\n";
echo "  - Mapel: {$mapel->nama}\n";
echo "  - Kelas: {$kelas->nama}\n";
echo "  - Jam: {$jamTest->jam_mulai}\n";
```

Ketik `exit` untuk keluar dari tinker.

### 2. Trigger Notifikasi

Jalankan command scheduler untuk mengirim notifikasi:

```bash
php artisan schedules:check-upcoming
```

Output yang diharapkan:
```
Notifikasi dikirim ke [Nama Guru] untuk jadwal [Mapel] di kelas [Kelas].
Total 1 notifikasi dikirim.
```

### 3. Verifikasi di Database (Opsional)

```bash
php artisan tinker --execute="
use App\Models\User;
\$guru = User::where('role', 'guru')->first();
echo 'Unread: ' . \$guru->unreadNotifications->count();
"
```

### 4. Test di Browser

1. **Buka browser** dan akses: `http://127.0.0.1:8000/login`

2. **Login sebagai Guru**
   - Email: `guru@gmail.com`
   - Password: `asdasd`

3. **Cek Ikon Lonceng**
   - Lokasi: Header sebelah kanan, sebelum foto profil
   - Harus ada badge merah dengan angka (jumlah notifikasi unread)

4. **Klik Ikon Lonceng**
   - Dropdown muncul dengan daftar notifikasi
   - Notifikasi unread memiliki background biru muda dan dot biru

5. **Test Mark as Read**
   - Klik salah satu notifikasi untuk menandai sebagai dibaca
   - Badge count akan berkurang

6. **Test Mark All as Read**
   - Klik "Tandai semua dibaca" di header dropdown
   - Semua notifikasi akan ditandai dibaca

7. **Lihat Halaman Full List**
   - Klik "Lihat semua notifikasi" di footer dropdown
   - Atau akses langsung: `http://127.0.0.1:8000/guru/notifications`

## Test Toast Popup

Toast muncul otomatis saat ada notifikasi baru (polling setiap 30 detik).

### Cara Test:

1. **Login sebagai guru** dan biarkan halaman terbuka

2. **Di terminal baru**, buat jadwal baru dan trigger notifikasi:
   ```bash
   # Buat jadwal baru (ulangi langkah 1)
   php artisan tinker
   # ... paste kode di atas ...

   # Trigger notifikasi
   php artisan schedules:check-upcoming
   ```

3. **Tunggu maksimal 30 detik** (atau refresh halaman)

4. **Toast akan muncul** di pojok kanan atas dengan pesan notifikasi

5. Toast akan hilang otomatis setelah 5 detik, atau klik tombol X untuk menutup

## Test Scheduler Otomatis

Untuk menjalankan scheduler secara otomatis (production-like):

```bash
# Development mode - jalankan scheduler setiap menit
php artisan schedule:work
```

Dengan ini, command `schedules:check-upcoming` akan berjalan otomatis setiap menit.

## Konfigurasi

### Mengubah Waktu Reminder

Default: 10 menit sebelum jadwal. Untuk mengubah:

```bash
php artisan tinker --execute="
use App\Models\NotificationSetting;
NotificationSetting::set('schedule_reminder_minutes', 15); // Ubah ke 15 menit
"
```

### Cek Setting Saat Ini

```bash
php artisan tinker --execute="
use App\Models\NotificationSetting;
echo NotificationSetting::getScheduleReminderMinutes() . ' menit';
"
```

## Troubleshooting

### Notifikasi tidak muncul

1. **Cek apakah jadwal sudah dibuat dengan benar**
   ```bash
   php artisan tinker --execute="
   use App\Models\JadwalPelajaran;
   \$today = JadwalPelajaran::hariIni();
   echo JadwalPelajaran::where('hari', \$today)->count() . ' jadwal hari ini';
   "
   ```

2. **Cek apakah waktu jadwal sesuai**
   - Jadwal harus dimulai tepat 10 menit dari waktu command dijalankan
   - Gunakan format `H:i` (contoh: 08:30)

3. **Cek apakah guru memiliki user**
   ```bash
   php artisan tinker --execute="
   use App\Models\Guru;
   Guru::whereNull('user_id')->get()->each(fn(\$g) => echo \$g->nama . ' tidak punya user\n');
   "
   ```

### Badge tidak update

- Refresh halaman
- Cek console browser untuk error JavaScript
- Pastikan route `/guru/notifications/poll` bisa diakses

### Toast tidak muncul

- Toast hanya muncul untuk notifikasi BARU (setelah halaman dibuka)
- Pastikan polling berjalan (cek Network tab di DevTools)
- Tunggu hingga 30 detik untuk polling berikutnya

## Endpoint API

| Method | URL | Deskripsi |
|--------|-----|-----------|
| GET | /guru/notifications | Halaman list notifikasi |
| GET | /guru/notifications/poll | Polling notifikasi baru |
| GET | /guru/notifications/recent | 10 notifikasi terbaru |
| POST | /guru/notifications/{id}/read | Tandai satu notifikasi dibaca |
| POST | /guru/notifications/mark-all-read | Tandai semua dibaca |

## File Terkait

```
app/
├── Console/Commands/
│   └── CheckUpcomingSchedulesCommand.php    # Command scheduler
├── Http/Controllers/Guru/
│   └── NotificationController.php           # Controller notifikasi
├── Models/
│   └── NotificationSetting.php              # Model setting
├── Notifications/
│   └── ScheduleReminderNotification.php     # Notification class

resources/views/
├── components/
│   └── notification-bell.blade.php          # Component lonceng
├── guru/notifications/
│   └── index.blade.php                      # Halaman list

database/migrations/
├── 2026_05_18_000001_create_notifications_table.php
└── 2026_05_18_000002_create_notification_settings_table.php

routes/
├── console.php                              # Scheduler registration
└── web.php                                  # Route definitions
```
