<?php

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PengaturanBel;
use App\Models\PresensiGuru;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    Carbon::setTestNow(Carbon::parse('2026-05-11 07:30:00', 'Asia/Jakarta'));
});

afterEach(function () {
    Carbon::setTestNow();
});

test('kepsek can access dashboard and monitoring modules', function () {
    $kepsek = User::factory()->create(['role' => User::ROLE_KEPSEK]);
    $guru = Guru::query()->create([
        'nama' => 'Guru Monitoring',
        'email' => 'monitoring@example.com',
    ]);
    $mataPelajaran = MataPelajaran::query()->create(['nama' => 'Produktif']);
    $kelas = Kelas::query()->create(['nama' => 'XII TKJ 1']);
    $jam = JamPelajaran::query()->create([
        'nama' => 'Jam 1',
        'urutan' => 1,
        'jam_mulai' => '07:00',
        'jam_selesai' => '08:00',
    ]);
    $jadwal = JadwalPelajaran::query()->create([
        'hari' => JadwalPelajaran::hariIni(),
        'guru_id' => $guru->id,
        'mata_pelajaran_id' => $mataPelajaran->id,
        'kelas_id' => $kelas->id,
        'jam_pelajaran_id' => $jam->id,
    ]);
    PengaturanBel::query()->create([
        'nama' => 'Bel Masuk',
        'tipe_bel' => 'masuk',
        'jam_bunyi' => '07:00',
        'is_active' => true,
    ]);
    PresensiGuru::query()->create([
        'guru_id' => $guru->id,
        'jadwal_pelajaran_id' => $jadwal->id,
        'tanggal' => today()->toDateString(),
        'status' => 'hadir',
    ]);

    $this->actingAs($kepsek)
        ->get(route('kepsek.dashboard'))
        ->assertOk()
        ->assertSee('Dashboard Kepala Sekolah')
        ->assertSee('Aktif')
        ->assertSee('Guru Monitoring');

    $this->actingAs($kepsek)
        ->get(route('kepsek.monitoring.index', ['search' => 'Monitoring']))
        ->assertOk()
        ->assertSee('Monitoring Jadwal')
        ->assertSee('Produktif');

    $this->actingAs($kepsek)
        ->get(route('kepsek.gurus.index', ['search' => 'Guru']))
        ->assertOk()
        ->assertSee('Data Guru')
        ->assertSee('Guru Monitoring');
});

test('non kepsek can not access kepsek module', function () {
    $guru = User::factory()->create(['role' => User::ROLE_GURU]);

    $this->actingAs($guru)
        ->get(route('kepsek.dashboard'))
        ->assertForbidden();
});

test('kepsek can access report modules without export pdf', function () {
    $kepsek = User::factory()->create(['role' => User::ROLE_KEPSEK]);
    $guru = Guru::query()->create(['nama' => 'Guru Report']);
    $mataPelajaran = MataPelajaran::query()->create(['nama' => 'Ekonomi']);
    $kelas = Kelas::query()->create(['nama' => 'XII IPS 2']);
    $jam = JamPelajaran::query()->create([
        'nama' => 'Jam 2',
        'urutan' => 2,
        'jam_mulai' => '08:00',
        'jam_selesai' => '09:00',
    ]);
    $jadwal = JadwalPelajaran::query()->create([
        'hari' => 'Senin',
        'guru_id' => $guru->id,
        'mata_pelajaran_id' => $mataPelajaran->id,
        'kelas_id' => $kelas->id,
        'jam_pelajaran_id' => $jam->id,
    ]);

    PresensiGuru::query()->create([
        'guru_id' => $guru->id,
        'jadwal_pelajaran_id' => $jadwal->id,
        'tanggal' => today()->toDateString(),
        'status' => 'hadir',
    ]);

    $this->actingAs($kepsek)
        ->get(route('kepsek.reports.jadwal'))
        ->assertOk()
        ->assertSee('Laporan Jadwal Pelajaran')
        ->assertSee('Cetak')
        ->assertDontSee('Export PDF');

    $this->actingAs($kepsek)
        ->get(route('kepsek.reports.presensi'))
        ->assertOk()
        ->assertSee('Laporan Presensi Guru')
        ->assertSee('Cetak')
        ->assertDontSee('Export PDF');

    $this->actingAs($kepsek)
        ->get(route('kepsek.reports.presensi.print'))
        ->assertOk()
        ->assertSee('Logo Sekolah')
        ->assertSee('logo-sekolah.png')
        ->assertSee('Laporan Presensi Guru Kepala Sekolah');

    $this->actingAs($kepsek)
        ->get('/kepsek/reports/presensi/pdf')
        ->assertNotFound();
});
