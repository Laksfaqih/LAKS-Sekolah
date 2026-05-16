<?php

namespace App\Providers;

use App\Models\IdentitasSekolah;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.app', 'layouts.navigation'], function ($view): void {
            $school = IdentitasSekolah::query()->first();

            $view->with('schoolName', $school?->nama_sekolah ?: 'LAKS-Bel');
            $view->with('schoolTagline', 'Sistem Monitoring Sekolah');
        });
    }
}
