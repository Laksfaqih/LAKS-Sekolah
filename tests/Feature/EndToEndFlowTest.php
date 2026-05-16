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

test('guru end to end flow works', function () {
    $user = User::factory()->create(['role' => User::ROLE_GURU]);
    $guru = Guru::query()->create([
        'user_id' => $user->id,
        'nama' => 'Guru E2E',
        'email' => 'guru-e2e@example.com',
    ]);
    $mapel = MataPelajaran::query()->create(['nama' => 'Fisika']);
    $kelas = Kelas::query()->create(['nama' => 'X MIPA 1']);
    $jam = JamPelajaran::query()->create([
        'nama' => 'Jam 1',
        'urutan' => 1,
        'jam_mulai' => '07:00',
        'jam_selesai' => '08:00',
    ]);
    JadwalPelajaran::query()->create([
        'hari' => JadwalPelajaran::hariIni(),
        'guru_id' => $guru->id,
        'mata_pelajaran_id' => $mapel->id,
        'kelas_id' => $kelas->id,
        'jam_pelajaran_id' => $jam->id,
    ]);

    $this->actingAs($user)->get(route('guru.dashboard'))->assertOk()->assertSee('Fisika');
    $this->actingAs($user)->get(route('guru.jadwal.index', ['hari' => JadwalPelajaran::hariIni()]))->assertOk()->assertSee('X MIPA 1');
    $this->actingAs($user)->post(route('guru.presensi.store'), ['status' => 'hadir', 'catatan' => 'E2E'])->assertRedirect(route('guru.presensi.index'));
    $this->actingAs($user)->put(route('guru.profil.update'), [
        'nama' => 'Guru E2E Update',
        'nip' => 'E2E-001',
        'email' => 'guru-e2e@example.com',
        'no_hp' => '08123',
        'alamat' => 'Alamat E2E',
    ])->assertRedirect(route('guru.profil.edit'));

    $this->assertDatabaseHas('presensi_gurus', ['guru_id' => $guru->id, 'status' => 'hadir']);
    $this->assertDatabaseHas('gurus', ['id' => $guru->id, 'nama' => 'Guru E2E Update']);
});

test('kepsek end to end flow works', function () {
    $kepsek = User::factory()->create(['role' => User::ROLE_KEPSEK]);
    $guru = Guru::query()->create(['nama' => 'Guru Kepsek', 'email' => 'kepsek-guru@example.com']);
    $mapel = MataPelajaran::query()->create(['nama' => 'Akuntansi']);
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
        'mata_pelajaran_id' => $mapel->id,
        'kelas_id' => $kelas->id,
        'jam_pelajaran_id' => $jam->id,
    ]);
    PengaturanBel::query()->create([
        'nama' => 'Bel Kepsek',
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

    $this->actingAs($kepsek)->get(route('kepsek.dashboard'))->assertOk()->assertSee('Guru Kepsek');
    $this->actingAs($kepsek)->get(route('kepsek.monitoring.index', ['hari' => JadwalPelajaran::hariIni()]))->assertOk()->assertSee('Akuntansi');
    $this->actingAs($kepsek)->get(route('kepsek.gurus.index'))->assertOk()->assertSee('Akuntansi');
    $this->actingAs($kepsek)->get(route('kepsek.reports.jadwal'))->assertOk();
    $this->actingAs($kepsek)->get(route('kepsek.reports.presensi.print'))->assertOk();
    $this->actingAs($kepsek)->get(route('kepsek.reports.presensi.pdf'))->assertOk()->assertHeader('content-type', 'application/pdf');
});
