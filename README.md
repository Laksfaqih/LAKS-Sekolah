# LAKS-Bel

LAKS-Bel adalah aplikasi Laravel untuk manajemen jadwal pelajaran, presensi guru, monitoring kepala sekolah, dan pengaturan bel otomatis berbasis scheduler.

## Fitur Utama

- Authentication berbasis Laravel Breeze Blade.
- Role `admin`, `guru`, dan `kepsek`.
- CRUD master data: guru, mata pelajaran, kelas, jam pelajaran.
- Manajemen jadwal pelajaran dengan validasi bentrok guru dan kelas.
- Dashboard terpisah per role.
- Presensi mengajar guru berdasarkan jadwal aktif.
- Monitoring jadwal dan data guru untuk kepala sekolah.
- Pengaturan bel otomatis dengan upload audio.
- Laporan jadwal dan presensi untuk admin dan kepsek.
- Backup dan restore database untuk MySQL dan SQLite.

## Stack Teknis

- PHP 8.2+
- Laravel 12
- Laravel Breeze Blade
- MySQL untuk development default
- Vite + Tailwind CSS
- Laporan jadwal dan presensi dapat ditampilkan dan dicetak langsung dari aplikasi

## Instalasi Lokal

1. Install dependency PHP dan Node.

```bash
composer install
npm install
```

2. Salin file environment lalu generate key.

```bash
cp .env.example .env
php artisan key:generate
```

3. Pastikan konfigurasi database di `.env` mengarah ke MySQL.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laks_bel
DB_USERNAME=root
DB_PASSWORD=
```

4. Buat database MySQL terlebih dahulu.

```bash
mysql -u root -p -e "CREATE DATABASE laks_bel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

5. Jalankan migration dan seeder.

```bash
php artisan migrate --seed
```

6. Jalankan asset build atau mode development.

```bash
npm run dev
```

7. Jalankan server Laravel.

```bash
php artisan serve
```

## Setup Database

- Default development menggunakan MySQL.
- Modul backup/restore bawaan mendukung:
- MySQL/MariaDB dengan file dump `.sql`
- SQLite dengan file `.sqlite` atau `.db`
- Implementasi MySQL memakai dump SQL internal aplikasi, bukan `mysqldump`.

## Akun Dummy Seeder

Seeder default membuat akun berikut:

- `admin@gmail.com` / `asdasd`
- `guru@gmail.com` / `asdasd`
- `kepsek@gmail.com` / `asdasd`

 Seeder juga membuat relasi profil guru untuk akun `guru@gmail.com`.

## Scheduler Bel Otomatis

Command yang dipakai:

```bash
php artisan bells:check
```

Untuk menjalankan scheduler Laravel:

```bash
php artisan schedule:work
```

Atau pada cron server:

```bash
* * * * * cd /path/ke/laravel_app && php artisan schedule:run >> /dev/null 2>&1
```

## Struktur Role

- `admin`: kelola master data, user, sistem, laporan, pengaturan bel, backup/restore.
- `guru`: lihat jadwal, isi presensi, kelola profil.
- `kepsek`: monitoring jadwal, lihat data guru, akses laporan.

## Testing

Jalankan seluruh test:

```bash
php artisan test
```

Build frontend:

```bash
npm run build
```

## Catatan Implementasi

- Format identitas sekolah pada laporan memakai `nama sekolah`, `NPSN`, `alamat`, `telepon`, `email`, `website`, dan `nama kepala sekolah`.
- Laporan disiapkan untuk tampilan web dan cetak langsung tanpa dependency package PDF tambahan.
- Audio bel disimpan di disk `public` pada folder `storage/app/public/bells`.
