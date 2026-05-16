<?php

namespace Database\Seeders;

use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class AcademicReferenceSeeder extends Seeder
{
    public function run(): void
    {
        $mataPelajarans = [
            ['kode' => 'MTK', 'nama' => 'Matematika', 'deskripsi' => 'Materi numerasi dan logika dasar.'],
            ['kode' => 'BIN', 'nama' => 'Bahasa Indonesia', 'deskripsi' => 'Kompetensi literasi, menulis, dan presentasi.'],
            ['kode' => 'BIG', 'nama' => 'Bahasa Inggris', 'deskripsi' => 'Reading, speaking, dan vocabulary untuk siswa SMK.'],
            ['kode' => 'RPL', 'nama' => 'Pemrograman Dasar', 'deskripsi' => 'Logika pemrograman dan praktik coding dasar.'],
            ['kode' => 'BD', 'nama' => 'Basis Data', 'deskripsi' => 'Konsep relational database dan SQL.'],
            ['kode' => 'JK', 'nama' => 'Jaringan Komputer', 'deskripsi' => 'Konfigurasi jaringan dasar dan troubleshooting.'],
            ['kode' => 'PAI', 'nama' => 'Pendidikan Agama', 'deskripsi' => 'Pembinaan karakter dan nilai keagamaan.'],
            ['kode' => 'PKK', 'nama' => 'Produk Kreatif dan Kewirausahaan', 'deskripsi' => 'Simulasi usaha dan pengembangan produk.'],
        ];

        foreach ($mataPelajarans as $payload) {
            MataPelajaran::query()->updateOrCreate(
                ['kode' => $payload['kode']],
                $payload,
            );
        }

        $kelasList = [
            ['nama' => 'X RPL 1', 'tingkat' => 'X', 'jurusan' => 'RPL', 'keterangan' => 'Kelas reguler pagi.'],
            ['nama' => 'X RPL 2', 'tingkat' => 'X', 'jurusan' => 'RPL', 'keterangan' => 'Kelas reguler pagi.'],
            ['nama' => 'XI RPL 1', 'tingkat' => 'XI', 'jurusan' => 'RPL', 'keterangan' => 'Fokus project web dan database.'],
            ['nama' => 'XI AKL 1', 'tingkat' => 'XI', 'jurusan' => 'AKL', 'keterangan' => 'Akuntansi dan keuangan lembaga.'],
            ['nama' => 'XII TKJ 1', 'tingkat' => 'XII', 'jurusan' => 'TKJ', 'keterangan' => 'Persiapan ujian dan praktik industri.'],
            ['nama' => 'XII RPL 1', 'tingkat' => 'XII', 'jurusan' => 'RPL', 'keterangan' => 'Kelas akhir dengan fokus project akhir.'],
        ];

        foreach ($kelasList as $payload) {
            Kelas::query()->updateOrCreate(
                ['nama' => $payload['nama']],
                $payload,
            );
        }

        $jamPelajarans = [
            ['urutan' => 1, 'nama' => 'Jam ke-1', 'jam_mulai' => '07:00', 'jam_selesai' => '07:45'],
            ['urutan' => 2, 'nama' => 'Jam ke-2', 'jam_mulai' => '07:45', 'jam_selesai' => '08:30'],
            ['urutan' => 3, 'nama' => 'Jam ke-3', 'jam_mulai' => '08:30', 'jam_selesai' => '09:15'],
            ['urutan' => 4, 'nama' => 'Jam ke-4', 'jam_mulai' => '09:30', 'jam_selesai' => '10:15'],
            ['urutan' => 5, 'nama' => 'Jam ke-5', 'jam_mulai' => '10:15', 'jam_selesai' => '11:00'],
            ['urutan' => 6, 'nama' => 'Jam ke-6', 'jam_mulai' => '11:00', 'jam_selesai' => '11:45'],
            ['urutan' => 7, 'nama' => 'Jam ke-7', 'jam_mulai' => '12:30', 'jam_selesai' => '13:15'],
            ['urutan' => 8, 'nama' => 'Jam ke-8', 'jam_mulai' => '13:15', 'jam_selesai' => '14:00'],
            ['urutan' => 9, 'nama' => 'Sesi Demo Presensi', 'jam_mulai' => '00:00', 'jam_selesai' => '23:59'],
        ];

        foreach ($jamPelajarans as $payload) {
            JamPelajaran::query()->updateOrCreate(
                ['urutan' => $payload['urutan']],
                $payload,
            );
        }
    }
}
