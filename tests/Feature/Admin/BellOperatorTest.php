<?php

use App\Models\BellTrigger;
use App\Models\PengaturanBel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

test('bells check command creates trigger records and skips duplicate runs', function () {
    Carbon::setTestNow('2026-05-17 07:45:00');

    $playableBell = PengaturanBel::query()->create([
        'nama' => 'Bel Masuk',
        'tipe_bel' => 'masuk',
        'jam_bunyi' => '07:45',
        'audio_path' => 'bells/bel-masuk.mp3',
        'is_active' => true,
    ]);

    $silentBell = PengaturanBel::query()->create([
        'nama' => 'Bel Istirahat',
        'tipe_bel' => 'istirahat',
        'jam_bunyi' => '07:45',
        'audio_path' => null,
        'is_active' => true,
    ]);

    Artisan::call('bells:check');
    Artisan::call('bells:check');

    $this->assertDatabaseCount('bell_triggers', 2);
    $this->assertDatabaseHas('bell_triggers', [
        'pengaturan_bel_id' => $playableBell->id,
        'status' => BellTrigger::STATUS_PENDING,
    ]);
    $this->assertDatabaseHas('bell_triggers', [
        'pengaturan_bel_id' => $silentBell->id,
        'status' => BellTrigger::STATUS_SKIPPED_NO_AUDIO,
    ]);

    Carbon::setTestNow();
});

test('admin browser operator can activate poll pending triggers and acknowledge playback', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $browserToken = 'browser-operator-1';

    $bell = PengaturanBel::query()->create([
        'nama' => 'Bel Ujian',
        'tipe_bel' => 'masuk',
        'jam_bunyi' => '08:00',
        'audio_path' => 'bells/bel-ujian.mp3',
        'is_active' => true,
    ]);

    $trigger = BellTrigger::query()->create([
        'pengaturan_bel_id' => $bell->id,
        'nama' => $bell->nama,
        'tipe_bel' => $bell->tipe_bel,
        'audio_path' => $bell->audio_path,
        'triggered_at' => now(),
        'status' => BellTrigger::STATUS_PENDING,
    ]);

    $this->actingAs($admin)
        ->postJson(route('admin.bell-operator.activate'), ['browser_token' => $browserToken])
        ->assertOk()
        ->assertJson([
            'status' => 'active',
            'is_current_operator' => true,
        ]);

    $this->actingAs($admin)
        ->getJson(route('admin.bell-operator.pending', ['browser_token' => $browserToken]))
        ->assertOk()
        ->assertJsonPath('trigger.id', $trigger->id)
        ->assertJsonPath('trigger.audio_url', url('/storage/'.$bell->audio_path));

    $this->actingAs($admin)
        ->postJson(route('admin.bell-operator.acknowledge', $trigger), [
            'browser_token' => $browserToken,
            'result' => 'played',
        ])
        ->assertOk()
        ->assertJson([
            'updated' => true,
        ]);

    $this->assertDatabaseHas('bell_triggers', [
        'id' => $trigger->id,
        'status' => BellTrigger::STATUS_PLAYED,
        'played_by_browser' => $browserToken,
    ]);
});

test('second admin browser cannot take over active bell operator lease', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)
        ->postJson(route('admin.bell-operator.activate'), ['browser_token' => 'browser-a'])
        ->assertOk()
        ->assertJsonPath('status', 'active');

    $this->actingAs($admin)
        ->postJson(route('admin.bell-operator.activate'), ['browser_token' => 'browser-b'])
        ->assertOk()
        ->assertJsonPath('status', 'active_elsewhere')
        ->assertJsonPath('is_current_operator', false);
});
