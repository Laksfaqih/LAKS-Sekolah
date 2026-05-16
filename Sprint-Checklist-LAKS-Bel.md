# Sprint Checklist LAKS-Bel

## Ringkasan

Dokumen ini adalah checklist implementasi untuk project LAKS-Bel yang diturunkan dari PRD dan breakdown task. Checklist dibagi menjadi 4 sprint agar mudah dipakai untuk tracking progres pengerjaan Laravel dari tahap fondasi sampai finishing.

## Cara Pakai

- Gunakan checkbox `- [ ]` untuk task yang belum selesai.
- Ubah menjadi `- [x]` jika task sudah selesai.
- Tambahkan catatan singkat di bawah task bila ada blocker atau keputusan teknis penting.

## Sprint 1 - Fondasi Project

Target sprint ini adalah menyiapkan fondasi aplikasi Laravel yang sudah bisa login, mengenali role pengguna, dan memiliki struktur data utama untuk pengembangan fitur berikutnya.

### Setup Project

- [x] Inisialisasi project Laravel.
- [x] Setup file environment `.env`.
- [x] Konfigurasi koneksi database.
- [x] Konfigurasi timezone aplikasi.
- [x] Setup asset build bila diperlukan.
- [x] Rapikan struktur folder dasar project.

### UI Dasar

- [x] Buat layout utama aplikasi.
- [x] Buat halaman login.
- [x] Buat struktur sidebar berdasarkan role.
- [x] Buat struktur topbar dasar.
- [x] Buat komponen alert dasar.
- [x] Buat komponen form dasar.
- [x] Buat komponen table dasar.
- [x] Buat komponen modal dasar bila diperlukan.
- [x] Buat placeholder dashboard admin.
- [x] Buat placeholder dashboard guru.
- [x] Buat placeholder dashboard kepsek.

### Authentication dan Authorization

- [x] Implementasi login.
- [x] Implementasi logout.
- [x] Tambahkan middleware auth.
- [x] Tambahkan middleware role-based access.
- [x] Proteksi route berdasarkan role.
- [x] Validasi redirect dashboard sesuai role setelah login.

### Database dan Model Dasar

- [x] Rancang tabel `users`.
- [x] Rancang tabel `gurus`.
- [x] Rancang tabel `mata_pelajarans`.
- [x] Rancang tabel `kelas`.
- [x] Rancang tabel `jam_pelajarans`.
- [x] Rancang tabel `jadwal_pelajarans`.
- [x] Rancang tabel `presensi_gurus`.
- [x] Rancang tabel `pengaturan_bels`.
- [x] Rancang tabel `identitas_sekolah`.
- [x] Buat migration untuk seluruh tabel inti.
- [x] Buat model Eloquent untuk seluruh entitas inti.
- [x] Definisikan relasi antar model.
- [x] Tambahkan foreign key dan constraint dasar.
- [x] Tentukan aturan penghapusan data yang aman.
- [x] Buat seeder role.
- [x] Buat seeder akun admin default.

### Master Data Dasar Admin

- [x] Buat modul daftar guru.
- [x] Buat fitur tambah guru.
- [x] Buat fitur edit guru.
- [x] Buat fitur hapus guru.
- [x] Buat fitur pencarian guru.
- [x] Tambahkan validasi form guru.
- [x] Buat modul daftar mata pelajaran.
- [x] Buat fitur tambah mata pelajaran.
- [x] Buat fitur edit mata pelajaran.
- [x] Buat fitur hapus mata pelajaran.
- [x] Buat fitur pencarian mata pelajaran.
- [x] Tambahkan validasi form mata pelajaran.
- [x] Buat modul daftar kelas.
- [x] Buat fitur tambah kelas.
- [x] Buat fitur edit kelas.
- [x] Buat fitur hapus kelas.
- [x] Tambahkan validasi form kelas.
- [x] Buat modul daftar jam pelajaran.
- [x] Buat fitur tambah jam pelajaran.
- [x] Buat fitur edit jam pelajaran.
- [x] Buat fitur hapus jam pelajaran.
- [x] Tambahkan atribut jam mulai, jam selesai, dan urutan jam.

### Definition of Done Sprint 1

- [x] Project Laravel bisa dijalankan lokal.
- [x] Login dan role dasar sudah bekerja.
- [x] Tabel inti tersedia dan bisa dipakai.
- [x] Master data dasar admin sudah bisa dikelola.

## Sprint 2 - Core Admin

Target sprint ini adalah menyelesaikan fitur inti admin untuk mengelola jadwal pelajaran, akun pengguna, dashboard, dan pengaturan sistem dasar.

### Modul Jadwal Pelajaran

- [x] Buat halaman daftar jadwal pelajaran.
- [x] Buat fitur tambah jadwal pelajaran.
- [x] Buat fitur edit jadwal pelajaran.
- [x] Buat fitur hapus jadwal pelajaran.
- [x] Tampilkan relasi guru, kelas, mata pelajaran, hari, dan jam pelajaran.
- [x] Tambahkan validasi input wajib pada form jadwal.
- [x] Cegah jadwal tanpa guru.
- [x] Cegah jadwal tanpa kelas.
- [x] Cegah jadwal tanpa mata pelajaran.
- [x] Cegah jadwal tanpa jam pelajaran.
- [x] Cegah bentrok jadwal guru pada hari dan jam yang sama.
- [x] Cegah bentrok jadwal kelas pada hari dan jam yang sama.
- [x] Tambahkan filter jadwal per hari.
- [x] Tambahkan filter jadwal per kelas.
- [x] Tambahkan filter jadwal per guru.
- [x] Tambahkan pencarian cepat pada daftar jadwal.

### Dashboard Admin

- [x] Tampilkan jumlah guru.
- [x] Tampilkan jumlah jadwal pelajaran.
- [x] Tampilkan status bel otomatis.
- [x] Tampilkan jam realtime.
- [x] Tampilkan daftar jadwal pelajaran hari ini.
- [x] Tampilkan guru, kelas, mata pelajaran, dan jam pada jadwal hari ini.
- [x] Tampilkan state kosong bila tidak ada jadwal.

### Manajemen User

- [x] Buat halaman daftar user.
- [x] Buat fitur tambah akun.
- [x] Buat fitur edit akun.
- [x] Buat fitur hapus akun.
- [x] Hubungkan akun dengan role yang sesuai.
- [x] Tambahkan validasi email atau username unik.

### Pengaturan Sistem Dasar

- [x] Buat halaman pengaturan sistem.
- [x] Buat fitur ganti password.
- [x] Buat fitur pengaturan identitas sekolah.
- [x] Buat form nama sekolah.
- [x] Buat form alamat sekolah.
- [x] Buat form kontak sekolah.
- [x] Tambahkan atribut identitas dasar lain bila diperlukan.

### Definition of Done Sprint 2

- [x] Admin dapat mengelola jadwal pelajaran end-to-end.
- [x] Validasi bentrok jadwal sudah berjalan.
- [x] Dashboard admin menampilkan ringkasan utama.
- [x] Manajemen user dan pengaturan sistem dasar sudah dapat dipakai.

## Sprint 3 - Modul Guru dan Kepsek

Target sprint ini adalah menyelesaikan seluruh alur pengguna untuk guru dan kepala sekolah agar sistem bisa dipakai selain oleh admin.

### Modul Guru

- [x] Tampilkan jadwal mengajar hari ini pada dashboard guru.
- [x] Tampilkan jam pelajaran aktif pada dashboard guru.
- [x] Tampilkan informasi kelas yang sedang diajar.
- [x] Tampilkan notifikasi pergantian jam di aplikasi.
- [x] Buat halaman jadwal pribadi guru.
- [x] Tampilkan jadwal guru per hari.
- [x] Tampilkan mata pelajaran, kelas, dan jam pada jadwal guru.
- [x] Tambahkan filter atau navigasi hari pada jadwal guru bila diperlukan.
- [x] Buat halaman presensi mengajar.
- [x] Buat aksi presensi pada jadwal aktif.
- [x] Simpan riwayat presensi guru.
- [x] Buat halaman riwayat presensi guru.
- [x] Tambahkan validasi agar presensi sesuai jadwal mengajar.
- [x] Buat halaman profil guru.
- [x] Tampilkan data pribadi guru.
- [x] Buat fitur edit profil guru.
- [x] Buat fitur ganti password guru.

### Modul Kepala Sekolah

- [x] Tampilkan jadwal pelajaran hari ini pada dashboard kepsek.
- [x] Tampilkan jam pelajaran yang sedang berlangsung pada dashboard kepsek.
- [x] Tampilkan status bel otomatis pada dashboard kepsek.
- [x] Tampilkan jumlah guru hadir pada dashboard kepsek.
- [x] Tampilkan informasi keterlambatan bila data tersedia.
- [x] Buat halaman monitoring jadwal.
- [x] Tambahkan filter monitoring per hari.
- [x] Tambahkan filter monitoring per kelas.
- [x] Tambahkan filter monitoring per guru.
- [x] Buat fitur pencarian jadwal pada monitoring.
- [x] Buat halaman data guru untuk kepsek.
- [x] Tampilkan daftar guru pada halaman kepsek.
- [x] Tampilkan mata pelajaran yang diajar guru.
- [x] Tambahkan fitur pencarian data guru.
- [x] Pastikan modul data guru untuk kepsek bersifat read-only.

### Definition of Done Sprint 3

- [x] Guru dapat melihat jadwal, mengisi presensi, dan mengelola profil.
- [x] Kepsek dapat memonitor jadwal dan melihat data guru.
- [x] Hak akses guru dan kepsek sudah terpisah dengan benar dari admin.

## Sprint 4 - Bel, Laporan, dan Hardening

Target sprint ini adalah menyelesaikan fitur operasional lanjutan, laporan, scheduler bel otomatis, dan tahap penguatan kualitas aplikasi.

### Bel Otomatis

- [x] Buat halaman pengaturan bel otomatis.
- [x] Buat fitur tambah jadwal bunyi bel.
- [x] Buat fitur ubah jadwal bunyi bel.
- [x] Kelompokkan tipe bel seperti pergantian jam dan pulang.
- [x] Tambahkan status aktif atau nonaktif pada pengaturan bel.
- [x] Buat fitur upload file suara bel.
- [x] Validasi format file audio.
- [x] Simpan referensi file audio ke database.
- [x] Tampilkan file audio aktif pada pengaturan bel.
- [x] Tentukan scheduler Laravel untuk pengecekan jadwal bel.
- [x] Jalankan event bel berdasarkan jam aktif.
- [x] Pastikan hanya jadwal bel aktif yang diproses.
- [x] Tampilkan status bel otomatis pada dashboard terkait.

### Laporan

- [x] Buat laporan jadwal pelajaran untuk admin.
- [x] Tambahkan filter laporan jadwal bila diperlukan.
- [x] Tambahkan fitur cetak laporan jadwal.
- [x] Tambahkan export PDF laporan jadwal.
- [x] Buat laporan presensi guru untuk admin.
- [x] Tampilkan data presensi berdasarkan periode.
- [x] Tambahkan fitur cetak laporan presensi.
- [x] Tambahkan export PDF laporan presensi.
- [x] Buat halaman laporan jadwal pelajaran untuk kepsek.
- [x] Buat halaman laporan presensi guru untuk kepsek.
- [x] Tambahkan fitur cetak atau export PDF pada laporan kepsek.

### Backup dan Restore

- [x] Tentukan pendekatan backup database pada level aplikasi.
- [x] Buat tombol trigger backup database.
- [x] Buat alur restore database.
- [x] Tambahkan validasi file restore.
- [x] Batasi akses backup dan restore hanya untuk admin.

### Testing dan Hardening

- [x] Uji validasi semua form utama.
- [x] Uji proteksi route berdasarkan role.
- [x] Uji akses ilegal antar role.
- [x] Uji login dan logout.
- [x] Uji CRUD data guru.
- [x] Uji CRUD mata pelajaran.
- [x] Uji CRUD kelas.
- [x] Uji CRUD jam pelajaran.
- [x] Uji CRUD jadwal pelajaran.
- [x] Uji validasi bentrok jadwal.
- [x] Uji pengaturan bel otomatis.
- [x] Uji upload audio bel.
- [x] Uji presensi guru.
- [x] Uji laporan dan export PDF.
- [x] Uji alur admin end-to-end.
- [x] Uji alur guru end-to-end.
- [x] Uji alur kepsek end-to-end.
- [x] Rapikan tampilan halaman utama.
- [x] Samakan style form, table, dan tombol.
- [x] Tambahkan feedback sukses dan error yang konsisten.
- [x] Buat README project.
- [x] Tulis langkah instalasi lokal.
- [x] Tulis langkah setup database.
- [x] Tulis akun dummy atau seeder default bila ada.
- [x] Tulis perintah scheduler untuk bel otomatis.

### Definition of Done Sprint 4

- [x] Bel otomatis dasar berjalan sesuai konfigurasi.
- [x] Laporan admin dan kepsek dapat dicetak atau diexport.
- [x] Backup dan restore memiliki alur dasar yang bisa dipakai admin.
- [x] Fitur utama telah diuji dan dokumentasi setup lokal tersedia.

## Backlog / Catatan Teknis

- [x] Tentukan package authentication Laravel yang dipakai.
- [x] Tentukan package PDF yang dipakai.
- [x] Tentukan strategi penyimpanan file audio bel.
- [x] Tentukan cara trigger bel otomatis pada environment target.
- [x] Tentukan format identitas sekolah pada laporan.
