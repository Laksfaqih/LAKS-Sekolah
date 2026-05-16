@php
    $user = auth()->user();
    $links = match ($user?->role) {
        \App\Models\User::ROLE_ADMIN => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
            ['label' => 'Data Guru', 'route' => 'admin.gurus.index', 'active' => 'admin.gurus.*'],
            ['label' => 'Mata Pelajaran', 'route' => 'admin.mata-pelajaran.index', 'active' => 'admin.mata-pelajaran.*'],
            ['label' => 'Data Kelas', 'route' => 'admin.kelas.index', 'active' => 'admin.kelas.*'],
            ['label' => 'Jam Pelajaran', 'route' => 'admin.jam-pelajaran.index', 'active' => 'admin.jam-pelajaran.*'],
            ['label' => 'Jadwal', 'route' => 'admin.jadwal-pelajaran.index', 'active' => 'admin.jadwal-pelajaran.*'],
            ['label' => 'Pengaturan Bel', 'route' => 'admin.pengaturan-bel.index', 'active' => 'admin.pengaturan-bel.*'],
            ['label' => 'Laporan Jadwal', 'route' => 'admin.reports.jadwal', 'active' => 'admin.reports.jadwal*'],
            ['label' => 'Laporan Presensi', 'route' => 'admin.reports.presensi', 'active' => 'admin.reports.presensi*'],
            ['label' => 'Backup Restore', 'route' => 'admin.backup-restore.edit', 'active' => 'admin.backup-restore.*'],
            ['label' => 'Manajemen User', 'route' => 'admin.users.index', 'active' => 'admin.users.*'],
            ['label' => 'Pengaturan', 'route' => 'admin.system-settings.edit', 'active' => 'admin.system-settings.*'],
        ],
        \App\Models\User::ROLE_GURU => [
            ['label' => 'Dashboard Guru', 'route' => 'guru.dashboard', 'active' => 'guru.dashboard'],
            ['label' => 'Jadwal Mengajar', 'route' => 'guru.jadwal.index', 'active' => 'guru.jadwal.*'],
            ['label' => 'Presensi', 'route' => 'guru.presensi.index', 'active' => 'guru.presensi.*'],
            ['label' => 'Profil Guru', 'route' => 'guru.profil.edit', 'active' => 'guru.profil.*'],
        ],
        \App\Models\User::ROLE_KEPSEK => [
            ['label' => 'Dashboard Kepsek', 'route' => 'kepsek.dashboard', 'active' => 'kepsek.dashboard'],
            ['label' => 'Monitoring Jadwal', 'route' => 'kepsek.monitoring.index', 'active' => 'kepsek.monitoring.*'],
            ['label' => 'Data Guru', 'route' => 'kepsek.gurus.index', 'active' => 'kepsek.gurus.*'],
            ['label' => 'Laporan Jadwal', 'route' => 'kepsek.reports.jadwal', 'active' => 'kepsek.reports.jadwal*'],
            ['label' => 'Laporan Presensi', 'route' => 'kepsek.reports.presensi', 'active' => 'kepsek.reports.presensi*'],
        ],
        default => [],
    };
@endphp

<nav x-data="{ open: false }" class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-6">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-slate-900">
                    LAKS-Bel
                </a>

                <div class="hidden gap-2 sm:flex">
                    @foreach ($links as $link)
                        <a
                            href="{{ route($link['route']) }}"
                            @class([
                                'rounded-lg px-3 py-2 text-sm font-medium transition',
                                'bg-slate-900 text-white' => request()->routeIs($link['active']),
                                'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->routeIs($link['active']),
                            ])
                        >
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="hidden items-center gap-4 sm:flex">
                <div class="text-right">
                    <p class="text-sm font-medium text-slate-900">{{ $user?->name }}</p>
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ $user?->role }}</p>
                </div>

                <a href="{{ route('profile.edit') }}" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-600 hover:bg-slate-50">
                    Profil
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg bg-rose-600 px-3 py-2 text-sm font-medium text-white hover:bg-rose-700">
                        Logout
                    </button>
                </form>
            </div>

            <button @click="open = ! open" class="rounded-lg border border-slate-200 p-2 text-slate-500 sm:hidden">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open" class="border-t border-slate-200 bg-white sm:hidden">
        <div class="space-y-1 px-4 py-3">
            @foreach ($links as $link)
                <a
                    href="{{ route($link['route']) }}"
                    @class([
                        'block rounded-lg px-3 py-2 text-sm font-medium',
                        'bg-slate-900 text-white' => request()->routeIs($link['active']),
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->routeIs($link['active']),
                    ])
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</nav>
