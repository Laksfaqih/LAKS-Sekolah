<?php

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('admin can perform crud for master data and schedule', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)
        ->post(route('admin.gurus.store'), [
            'nama' => 'Guru CRUD',
            'nip' => 'GR-CRUD-1',
            'email' => 'guru-crud@example.com',
            'no_hp' => '08123',
            'alamat' => 'Alamat Guru',
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.gurus.index'));

    $guru = Guru::query()->where('email', 'guru-crud@example.com')->firstOrFail();

    $this->actingAs($admin)
        ->put(route('admin.gurus.update', $guru), [
            'nama' => 'Guru CRUD Update',
            'nip' => 'GR-CRUD-1',
            'email' => 'guru-crud@example.com',
            'no_hp' => '08124',
            'alamat' => 'Alamat Baru',
            'is_active' => '1',
        ])
        ->assertRedirect(route('admin.gurus.index'));

    $this->actingAs($admin)
        ->post(route('admin.mata-pelajaran.store'), [
            'kode' => 'MP01',
            'nama' => 'Kimia',
            'deskripsi' => 'Mapel Kimia',
        ])
        ->assertRedirect(route('admin.mata-pelajaran.index'));

    $mataPelajaran = MataPelajaran::query()->where('kode', 'MP01')->firstOrFail();

    $this->actingAs($admin)
        ->put(route('admin.mata-pelajaran.update', $mataPelajaran), [
            'kode' => 'MP01',
            'nama' => 'Kimia Industri',
            'deskripsi' => 'Mapel Kimia Update',
        ])
        ->assertRedirect(route('admin.mata-pelajaran.index'));

    $this->actingAs($admin)
        ->post(route('admin.kelas.store'), [
            'nama' => 'X TKJ 1',
            'tingkat' => 'X',
            'jurusan' => 'TKJ',
            'keterangan' => 'Kelas CRUD',
        ])
        ->assertRedirect(route('admin.kelas.index'));

    $kelas = Kelas::query()->where('nama', 'X TKJ 1')->firstOrFail();

    $this->actingAs($admin)
        ->put(route('admin.kelas.update', $kelas), [
            'nama' => 'X TKJ 1A',
            'tingkat' => 'X',
            'jurusan' => 'TKJ',
            'keterangan' => 'Kelas Update',
        ])
        ->assertRedirect(route('admin.kelas.index'));

    $this->actingAs($admin)
        ->post(route('admin.jam-pelajaran.store'), [
            'nama' => 'Jam CRUD',
            'urutan' => 1,
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ])
        ->assertRedirect(route('admin.jam-pelajaran.index'));

    $jam = JamPelajaran::query()->where('nama', 'Jam CRUD')->firstOrFail();

    $this->actingAs($admin)
        ->put(route('admin.jam-pelajaran.update', $jam), [
            'nama' => 'Jam CRUD Update',
            'urutan' => 2,
            'jam_mulai' => '08:00',
            'jam_selesai' => '09:00',
        ])
        ->assertRedirect(route('admin.jam-pelajaran.index'));

    $this->actingAs($admin)
        ->post(route('admin.jadwal-pelajaran.store'), [
            'hari' => 'Senin',
            'guru_id' => $guru->id,
            'mata_pelajaran_id' => $mataPelajaran->id,
            'kelas_id' => $kelas->id,
            'jam_pelajaran_id' => $jam->id,
        ])
        ->assertRedirect(route('admin.jadwal-pelajaran.index'));

    $jadwal = JadwalPelajaran::query()->firstOrFail();

    $this->actingAs($admin)
        ->put(route('admin.jadwal-pelajaran.update', $jadwal), [
            'hari' => 'Selasa',
            'guru_id' => $guru->id,
            'mata_pelajaran_id' => $mataPelajaran->id,
            'kelas_id' => $kelas->id,
            'jam_pelajaran_id' => $jam->id,
        ])
        ->assertRedirect(route('admin.jadwal-pelajaran.index'));

    $this->assertDatabaseHas('jadwal_pelajarans', ['hari' => 'Selasa']);

    $this->actingAs($admin)->delete(route('admin.jadwal-pelajaran.destroy', $jadwal))->assertRedirect(route('admin.jadwal-pelajaran.index'));
    $this->actingAs($admin)->delete(route('admin.jam-pelajaran.destroy', $jam))->assertRedirect(route('admin.jam-pelajaran.index'));
    $this->actingAs($admin)->delete(route('admin.kelas.destroy', $kelas))->assertRedirect(route('admin.kelas.index'));
    $this->actingAs($admin)->delete(route('admin.mata-pelajaran.destroy', $mataPelajaran))->assertRedirect(route('admin.mata-pelajaran.index'));
    $this->actingAs($admin)->delete(route('admin.gurus.destroy', $guru))->assertRedirect(route('admin.gurus.index'));
});

test('admin main forms validate invalid input', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)
        ->from(route('admin.gurus.create'))
        ->post(route('admin.gurus.store'), ['nama' => ''])
        ->assertRedirect(route('admin.gurus.create'))
        ->assertSessionHasErrors(['nama']);

    $this->actingAs($admin)
        ->from(route('admin.mata-pelajaran.create'))
        ->post(route('admin.mata-pelajaran.store'), ['nama' => ''])
        ->assertRedirect(route('admin.mata-pelajaran.create'))
        ->assertSessionHasErrors(['nama']);

    $this->actingAs($admin)
        ->from(route('admin.kelas.create'))
        ->post(route('admin.kelas.store'), ['nama' => ''])
        ->assertRedirect(route('admin.kelas.create'))
        ->assertSessionHasErrors(['nama']);

    $this->actingAs($admin)
        ->from(route('admin.jam-pelajaran.create'))
        ->post(route('admin.jam-pelajaran.store'), [
            'urutan' => 0,
            'jam_mulai' => '09:00',
            'jam_selesai' => '08:00',
        ])
        ->assertRedirect(route('admin.jam-pelajaran.create'))
        ->assertSessionHasErrors(['urutan', 'jam_selesai']);

    $this->actingAs($admin)
        ->from(route('admin.jadwal-pelajaran.create'))
        ->post(route('admin.jadwal-pelajaran.store'), [])
        ->assertRedirect(route('admin.jadwal-pelajaran.create'))
        ->assertSessionHasErrors(['guru_id', 'mata_pelajaran_id', 'kelas_id', 'jam_pelajaran_id', 'hari']);

    $this->actingAs($admin)
        ->from(route('admin.system-settings.edit'))
        ->put(route('admin.system-settings.update'), [
            'nama_sekolah' => '',
            'website' => 'invalid-url',
        ])
        ->assertRedirect(route('admin.system-settings.edit'))
        ->assertSessionHasErrors(['nama_sekolah', 'website']);

    $this->actingAs($admin)
        ->from(route('admin.pengaturan-bel.create'))
        ->post(route('admin.pengaturan-bel.store'), [
            'nama' => '',
            'tipe_bel' => 'invalid',
            'jam_bunyi' => '07',
            'audio_file' => UploadedFile::fake()->create('bad.txt', 1, 'text/plain'),
        ])
        ->assertRedirect(route('admin.pengaturan-bel.create'))
        ->assertSessionHasErrors(['nama', 'tipe_bel', 'jam_bunyi', 'audio_file']);
});

test('admin end to end flow works across core modules', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $guru = Guru::query()->create(['nama' => 'Guru End To End', 'email' => 'e2e-guru@example.com']);
    $mapel = MataPelajaran::query()->create(['kode' => 'BIO', 'nama' => 'Biologi']);
    $kelas = Kelas::query()->create(['nama' => 'XI MIPA 1']);
    $jam = JamPelajaran::query()->create([
        'nama' => 'Jam 1',
        'urutan' => 1,
        'jam_mulai' => '07:00',
        'jam_selesai' => '08:00',
    ]);

    $this->actingAs($admin)
        ->post(route('admin.users.store'), [
            'name' => 'Guru User',
            'email' => 'guru-user@example.com',
            'role' => User::ROLE_GURU,
            'guru_id' => $guru->id,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->assertRedirect(route('admin.users.index'));

    $this->actingAs($admin)
        ->post(route('admin.jadwal-pelajaran.store'), [
            'hari' => 'Senin',
            'guru_id' => $guru->id,
            'mata_pelajaran_id' => $mapel->id,
            'kelas_id' => $kelas->id,
            'jam_pelajaran_id' => $jam->id,
        ])
        ->assertRedirect(route('admin.jadwal-pelajaran.index'));

    $this->actingAs($admin)
        ->post(route('admin.pengaturan-bel.store'), [
            'nama' => 'Bel E2E',
            'tipe_bel' => 'masuk',
            'jam_bunyi' => '07:00',
            'audio_file' => UploadedFile::fake()->create('bel.mp3', 128, 'audio/mpeg'),
        ])
        ->assertRedirect(route('admin.pengaturan-bel.index'));

    $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    $this->actingAs($admin)->get(route('admin.reports.jadwal'))->assertOk()->assertSee('Biologi');
    $this->actingAs($admin)->get(route('admin.reports.jadwal.print'))->assertOk();
    $this->actingAs($admin)->get(route('admin.reports.jadwal.pdf'))->assertOk()->assertHeader('content-type', 'application/pdf');
    $this->actingAs($admin)->get(route('admin.backup-restore.edit'))->assertOk();
});
