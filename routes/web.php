<?php

use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BellOperatorController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\JadwalPelajaranController;
use App\Http\Controllers\Admin\JamPelajaranController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Admin\PengaturanBelController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\JadwalMengajarController;
use App\Http\Controllers\Guru\NotificationController as GuruNotificationController;
use App\Http\Controllers\Guru\PresensiController;
use App\Http\Controllers\Guru\ProfilController as GuruProfilController;
use App\Http\Controllers\Kepsek\DashboardController as KepsekDashboardController;
use App\Http\Controllers\Kepsek\GuruController as KepsekGuruController;
use App\Http\Controllers\Kepsek\MonitoringJadwalController;
use App\Http\Controllers\Kepsek\ReportController as KepsekReportController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = request()->user();

        return redirect()->route(match ($user->role) {
            User::ROLE_ADMIN => 'admin.dashboard',
            User::ROLE_GURU => 'guru.dashboard',
            User::ROLE_KEPSEK => 'kepsek.dashboard',
            default => 'login',
        });
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/photo', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')
        ->as('admin.')
        ->middleware('role:admin')
        ->group(function () {
            Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
            Route::prefix('bell-operator')
                ->as('bell-operator.')
                ->group(function () {
                    Route::get('status', [BellOperatorController::class, 'status'])->name('status');
                    Route::post('activate', [BellOperatorController::class, 'activate'])->name('activate');
                    Route::post('heartbeat', [BellOperatorController::class, 'heartbeat'])->name('heartbeat');
                    Route::post('deactivate', [BellOperatorController::class, 'deactivate'])->name('deactivate');
                    Route::get('pending', [BellOperatorController::class, 'pending'])->name('pending');
                    Route::post('triggers/{bellTrigger}/acknowledge', [BellOperatorController::class, 'acknowledge'])->name('acknowledge');
                });
            Route::resource('gurus', GuruController::class)->except('show');
            Route::resource('mata-pelajaran', MataPelajaranController::class)->except('show');
            Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas'])->except('show');
            Route::resource('jam-pelajaran', JamPelajaranController::class)->except('show');
            Route::resource('jadwal-pelajaran', JadwalPelajaranController::class)->except('show');
            Route::resource('pengaturan-bel', PengaturanBelController::class)->except('show');
            Route::resource('users', UserController::class)->except('show');
            Route::get('system-settings', [SystemSettingController::class, 'edit'])->name('system-settings.edit');
            Route::put('system-settings', [SystemSettingController::class, 'update'])->name('system-settings.update');
            Route::get('reports/jadwal', [AdminReportController::class, 'jadwal'])->name('reports.jadwal');
            Route::get('reports/jadwal/print', [AdminReportController::class, 'jadwalPrint'])->name('reports.jadwal.print');
            Route::get('reports/presensi', [AdminReportController::class, 'presensi'])->name('reports.presensi');
            Route::get('reports/presensi/print', [AdminReportController::class, 'presensiPrint'])->name('reports.presensi.print');
            Route::get('backup-restore', [BackupController::class, 'edit'])->name('backup-restore.edit');
            Route::post('backup-restore/backup', [BackupController::class, 'backup'])->name('backup-restore.backup');
            Route::get('backup-restore/download', [BackupController::class, 'download'])->name('backup-restore.download');
            Route::post('backup-restore/restore', [BackupController::class, 'restore'])->name('backup-restore.restore');
        });

    Route::prefix('guru')
        ->as('guru.')
        ->middleware('role:guru')
        ->group(function () {
            Route::get('/dashboard', GuruDashboardController::class)->name('dashboard');
            Route::get('/jadwal', [JadwalMengajarController::class, 'index'])->name('jadwal.index');
            Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
            Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
            Route::get('/profil', [GuruProfilController::class, 'edit'])->name('profil.edit');
            Route::put('/profil', [GuruProfilController::class, 'update'])->name('profil.update');

            Route::prefix('notifications')->as('notifications.')->group(function () {
                Route::get('/', [GuruNotificationController::class, 'index'])->name('index');
                Route::get('/poll', [GuruNotificationController::class, 'poll'])->name('poll');
                Route::get('/recent', [GuruNotificationController::class, 'recent'])->name('recent');
                Route::post('/{id}/read', [GuruNotificationController::class, 'markAsRead'])->name('read');
                Route::post('/mark-all-read', [GuruNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
            });
        });

    Route::prefix('kepsek')
        ->as('kepsek.')
        ->middleware('role:kepsek')
        ->group(function () {
            Route::get('/dashboard', KepsekDashboardController::class)->name('dashboard');
            Route::get('/monitoring', [MonitoringJadwalController::class, 'index'])->name('monitoring.index');
            Route::get('/gurus', [KepsekGuruController::class, 'index'])->name('gurus.index');
            Route::get('/reports/jadwal', [KepsekReportController::class, 'jadwal'])->name('reports.jadwal');
            Route::get('/reports/jadwal/print', [KepsekReportController::class, 'jadwalPrint'])->name('reports.jadwal.print');
            Route::get('/reports/presensi', [KepsekReportController::class, 'presensi'])->name('reports.presensi');
            Route::get('/reports/presensi/print', [KepsekReportController::class, 'presensiPrint'])->name('reports.presensi.print');
        });
});

require __DIR__.'/auth.php';
