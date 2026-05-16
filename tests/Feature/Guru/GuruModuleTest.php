<?php

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
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

test('guru can access sprint 3 pages', function () {
    $user = User::factory()->create(['role' => User::ROLE_GURU]);
    $guru = Guru::query()->create([
        'user_id' => $user->id,
        'nama' => 'Budi Santoso',
        'email' => 'budi@example.com',
    ]);
    $mataPelajaran = MataPelajaran::query()->create(['nama' => 'Matematika']);
    $kelas = Kelas::query()->create(['nama' => 'X RPL 1']);
    $jam = JamPelajaran::query()->create([
        'nama' => 'Jam 1',
        'urutan' => 1,
        'jam_mulai' => '07:00',
        'jam_selesai' => '08:00',
    ]);

    JadwalPelajaran::query()->create([
        'hari' => JadwalPelajaran::hariIni(),
        'guru_id' => $guru->id,
        'mata_pelajaran_id' => $mataPelajaran->id,
        'kelas_id' => $kelas->id,
        'jam_pelajaran_id' => $jam->id,
    ]);

    $this->actingAs($user)->get(route('guru.dashboard'))
        ->assertOk()
        ->assertSee('Dashboard Guru')
        ->assertSee('Matematika');

    $this->actingAs($user)->get(route('guru.jadwal.index'))
        ->assertOk()
        ->assertSee('Jadwal Mengajar');

    $this->actingAs($user)->get(route('guru.presensi.index'))
        ->assertOk()
        ->assertSee('Presensi Guru')
        ->assertSee('Simpan Presensi');

    $this->actingAs($user)->get(route('guru.profil.edit'))
        ->assertOk()
        ->assertSee('Profil Guru')
        ->assertSee('Budi Santoso');
});

test('guru can submit presensi on active schedule', function () {
    $user = User::factory()->create(['role' => User::ROLE_GURU]);
    $guru = Guru::query()->create([
        'user_id' => $user->id,
        'nama' => 'Siti Aminah',
        'email' => 'siti@example.com',
    ]);
    $mataPelajaran = MataPelajaran::query()->create(['nama' => 'Bahasa Indonesia']);
    $kelas = Kelas::query()->create(['nama' => 'XI AKL 1']);
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

    $this->actingAs($user)
        ->post(route('guru.presensi.store'), [
            'status' => 'hadir',
            'catatan' => 'Masuk tepat waktu',
        ])
        ->assertRedirect(route('guru.presensi.index'));

    $this->assertDatabaseHas('presensi_gurus', [
        'guru_id' => $guru->id,
        'jadwal_pelajaran_id' => $jadwal->id,
        'status' => 'hadir',
        'catatan' => 'Masuk tepat waktu',
    ]);
});

test('non guru can not access guru module', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)
        ->get(route('guru.dashboard'))
        ->assertForbidden();
});
