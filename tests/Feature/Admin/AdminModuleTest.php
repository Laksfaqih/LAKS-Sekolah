<?php

use App\Models\Guru;
use App\Models\IdentitasSekolah;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PengaturanBel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('admin can access dashboard and system settings', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk();

    $this->actingAs($admin)
        ->get(route('admin.system-settings.edit'))
        ->assertOk();
});

test('non admin can not access admin dashboard', function () {
    $guruUser = User::factory()->create(['role' => User::ROLE_GURU]);

    $this->actingAs($guruUser)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('admin can create jadwal pelajaran and conflict is rejected', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $guru = Guru::query()->create(['nama' => 'Guru A']);
    $guruB = Guru::query()->create(['nama' => 'Guru B']);
    $mataPelajaran = MataPelajaran::query()->create(['nama' => 'Matematika']);
    $kelas = Kelas::query()->create(['nama' => 'X RPL 1']);
    $kelasLain = Kelas::query()->create(['nama' => 'X RPL 2']);
    $jam = JamPelajaran::query()->create([
        'urutan' => 1,
        'jam_mulai' => '07:00',
        'jam_selesai' => '08:00',
    ]);

    $this->actingAs($admin)
        ->post(route('admin.jadwal-pelajaran.store'), [
            'hari' => 'Senin',
            'guru_id' => $guru->id,
            'mata_pelajaran_id' => $mataPelajaran->id,
            'kelas_id' => $kelas->id,
            'jam_pelajaran_id' => $jam->id,
        ])
        ->assertRedirect(route('admin.jadwal-pelajaran.index'));

    $this->assertDatabaseHas('jadwal_pelajarans', [
        'hari' => 'Senin',
        'guru_id' => $guru->id,
        'kelas_id' => $kelas->id,
    ]);

    $response = $this->actingAs($admin)
        ->from(route('admin.jadwal-pelajaran.create'))
        ->post(route('admin.jadwal-pelajaran.store'), [
            'hari' => 'Senin',
            'guru_id' => $guru->id,
            'mata_pelajaran_id' => $mataPelajaran->id,
            'kelas_id' => $kelasLain->id,
            'jam_pelajaran_id' => $jam->id,
        ]);

    $response->assertRedirect(route('admin.jadwal-pelajaran.create'));
    $response->assertSessionHasErrors('guru_id');

    $response = $this->actingAs($admin)
        ->from(route('admin.jadwal-pelajaran.create'))
        ->post(route('admin.jadwal-pelajaran.store'), [
            'hari' => 'Senin',
            'guru_id' => $guruB->id,
            'mata_pelajaran_id' => $mataPelajaran->id,
            'kelas_id' => $kelas->id,
            'jam_pelajaran_id' => $jam->id,
        ]);

    $response->assertRedirect(route('admin.jadwal-pelajaran.create'));
    $response->assertSessionHasErrors('kelas_id');
});

test('admin can update system setting', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)
        ->put(route('admin.system-settings.update'), [
            'nama_sekolah' => 'SMK Contoh',
            'alamat' => 'Jl. Testing 123',
            'telepon' => '08123456789',
            'email' => 'sekolah@example.com',
        ])
        ->assertRedirect(route('admin.system-settings.edit'));

    expect(IdentitasSekolah::query()->first())->not->toBeNull();
    $this->assertDatabaseHas('identitas_sekolah', [
        'nama_sekolah' => 'SMK Contoh',
    ]);
});

test('school name from system settings is shown in app title and sidebar brand', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    IdentitasSekolah::query()->create(['nama_sekolah' => 'MTs Badrul Arifin']);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertSee('<title>MTs Badrul Arifin</title>', false);
    $response->assertSee('MTs Badrul Arifin');
    $response->assertSee('Sistem Monitoring Sekolah');
});

test('admin can create bell setting with audio upload', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)
        ->post(route('admin.pengaturan-bel.store'), [
            'nama' => 'Bel Masuk',
            'tipe_bel' => 'masuk',
            'jam_bunyi' => '07:00',
            'is_active' => '1',
            'audio_file' => UploadedFile::fake()->create('bel-masuk.mp3', 128, 'audio/mpeg'),
        ])
        ->assertRedirect(route('admin.pengaturan-bel.index'));

    $bell = PengaturanBel::query()->first();

    expect($bell)->not->toBeNull();
    expect($bell->audio_path)->not->toBeNull();

    Storage::disk('public')->assertExists($bell->audio_path);
    $this->assertDatabaseHas('pengaturan_bels', [
        'nama' => 'Bel Masuk',
        'tipe_bel' => 'masuk',
        'is_active' => true,
    ]);
});

test('admin can access report and backup modules', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $guru = Guru::query()->create(['nama' => 'Guru Laporan']);
    $mataPelajaran = MataPelajaran::query()->create(['nama' => 'Sejarah']);
    $kelas = Kelas::query()->create(['nama' => 'XI IPS 1']);
    $jam = JamPelajaran::query()->create([
        'urutan' => 1,
        'jam_mulai' => '07:00',
        'jam_selesai' => '08:00',
    ]);

    JadwalPelajaran::query()->create([
        'hari' => 'Senin',
        'guru_id' => $guru->id,
        'mata_pelajaran_id' => $mataPelajaran->id,
        'kelas_id' => $kelas->id,
        'jam_pelajaran_id' => $jam->id,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.reports.jadwal'))
        ->assertOk()
        ->assertSee('Laporan Jadwal Pelajaran')
        ->assertSee('Cetak')
        ->assertDontSee('Export PDF');

    $this->actingAs($admin)
        ->get(route('admin.reports.jadwal.print'))
        ->assertOk()
        ->assertSee('Logo Sekolah')
        ->assertSee('logo-sekolah.png')
        ->assertSee('Laporan Jadwal Pelajaran');

    $this->actingAs($admin)
        ->get('/admin/reports/jadwal/pdf')
        ->assertNotFound();

    $this->actingAs($admin)
        ->get(route('admin.backup-restore.edit'))
        ->assertOk()
        ->assertSee('Backup dan Restore');

    $this->actingAs($admin)
        ->post(route('admin.backup-restore.backup'))
        ->assertOk()
        ->assertDownload();
});
