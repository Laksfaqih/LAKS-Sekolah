@php
    $user = auth()->user();
    $adminDashboardLink = ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'];
    $adminGroups = [
        [
            'key' => 'akademik',
            'label' => 'Akademik',
            'items' => [
                ['label' => 'Data Guru', 'route' => 'admin.gurus.index', 'active' => 'admin.gurus.*'],
                ['label' => 'Mata Pelajaran', 'route' => 'admin.mata-pelajaran.index', 'active' => 'admin.mata-pelajaran.*'],
                ['label' => 'Data Kelas', 'route' => 'admin.kelas.index', 'active' => 'admin.kelas.*'],
                ['label' => 'Jam Pelajaran', 'route' => 'admin.jam-pelajaran.index', 'active' => 'admin.jam-pelajaran.*'],
                ['label' => 'Jadwal', 'route' => 'admin.jadwal-pelajaran.index', 'active' => 'admin.jadwal-pelajaran.*'],
                ['label' => 'Pengaturan Bel', 'route' => 'admin.pengaturan-bel.index', 'active' => 'admin.pengaturan-bel.*'],
            ],
        ],
        [
            'key' => 'laporan',
            'label' => 'Laporan',
            'items' => [
                ['label' => 'Laporan Jadwal', 'route' => 'admin.reports.jadwal', 'active' => 'admin.reports.jadwal*'],
                ['label' => 'Laporan Presensi', 'route' => 'admin.reports.presensi', 'active' => 'admin.reports.presensi*'],
            ],
        ],
        [
            'key' => 'sistem',
            'label' => 'Sistem',
            'items' => [
                ['label' => 'Backup Restore', 'route' => 'admin.backup-restore.edit', 'active' => 'admin.backup-restore.*'],
                ['label' => 'Manajemen User', 'route' => 'admin.users.index', 'active' => 'admin.users.*'],
                ['label' => 'Pengaturan', 'route' => 'admin.system-settings.edit', 'active' => 'admin.system-settings.*'],
            ],
        ],
    ];
    $flatLinks = match ($user?->role) {
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
    $isAdminSidebar = $user?->role === \App\Models\User::ROLE_ADMIN;

    $roleLabel = match ($user?->role) {
        \App\Models\User::ROLE_ADMIN => 'Administrator',
        \App\Models\User::ROLE_GURU => 'Guru',
        \App\Models\User::ROLE_KEPSEK => 'Kepala Sekolah',
        default => 'Pengguna',
    };
@endphp

<div
    x-cloak
    x-show="sidebarOpen"
    x-transition.opacity
    @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-slate-950/45 lg:hidden"
></div>

<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-200 bg-white shadow-2xl transition-transform duration-300 lg:static lg:z-auto lg:h-screen lg:w-72 lg:translate-x-0 lg:shadow-none"
>
    <div class="border-b border-slate-200 px-5 py-5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-2xl transition hover:bg-slate-50">
            <span class="flex items-center justify-center rounded-2xl border border-slate-200 bg-white shadow-sm" style="width: 56px; height: 56px; flex: 0 0 56px;">
                <img
                    src="{{ asset('logo-sekolah.png') }}"
                    alt="Logo Sekolah"
                    class="object-contain"
                    style="width: 44px; height: 44px; clip-path: inset(0 2px 0 0);"
                >
            </span>

            <span class="min-w-0">
                <span class="block truncate text-lg font-semibold tracking-tight text-slate-900">{{ $schoolName ?? 'LAKS-Bel' }}</span>
                <span class="mt-1 block text-[9px] font-semibold uppercase leading-4 tracking-[0.12em] text-amber-600">
                    {{ $schoolTagline ?? 'Sistem Monitoring Sekolah' }}
                </span>
            </span>
        </a>
    </div>

    <div class="flex-1 overflow-y-auto px-4 py-4">
        <div class="mb-4 rounded-2xl bg-slate-900 px-4 py-3 text-white shadow-sm">
            <p class="truncate text-sm font-semibold">{{ $user?->name }}</p>
            <p class="mt-1 text-[11px] uppercase tracking-[0.24em] text-slate-300">{{ $roleLabel }}</p>
        </div>

        @if ($isAdminSidebar)
            @php
                $activeAdminGroup = collect($adminGroups)->first(function (array $group) {
                    return collect($group['items'])->contains(fn (array $item) => request()->routeIs($item['active']));
                });
                $initialOpenGroup = $activeAdminGroup['key'] ?? null;
            @endphp

            <nav x-data="{ openGroup: @js($initialOpenGroup) }" class="space-y-1.5">
                <a
                    href="{{ route($adminDashboardLink['route']) }}"
                    @class([
                        'flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition',
                        'bg-slate-900 text-white shadow-sm' => request()->routeIs($adminDashboardLink['active']),
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->routeIs($adminDashboardLink['active']),
                    ])
                >
                    {{ $adminDashboardLink['label'] }}
                </a>

                @foreach ($adminGroups as $group)
                    @php
                        $groupIsActive = collect($group['items'])->contains(fn (array $item) => request()->routeIs($item['active']));
                    @endphp

                    <div class="rounded-2xl border border-slate-200 bg-white">
                        <button
                            type="button"
                            @click="openGroup = openGroup === '{{ $group['key'] }}' ? null : '{{ $group['key'] }}'"
                            @class([
                                'flex w-full items-center justify-between rounded-2xl px-4 py-3 text-left text-sm font-semibold transition',
                                'bg-slate-900 text-white' => $groupIsActive,
                                'text-slate-700 hover:bg-slate-50' => ! $groupIsActive,
                            ])
                        >
                            <span>{{ $group['label'] }}</span>
                            <svg
                                class="h-4 w-4 transition-transform"
                                :class="openGroup === '{{ $group['key'] }}' ? 'rotate-180' : ''"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div
                            x-cloak
                            x-show="openGroup === '{{ $group['key'] }}'"
                            x-transition.origin.top
                            class="space-y-1 px-2 pb-2 pt-1"
                        >
                            @foreach ($group['items'] as $item)
                                <a
                                    href="{{ route($item['route']) }}"
                                    @class([
                                        'flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition',
                                        'bg-slate-100 text-slate-900' => request()->routeIs($item['active']),
                                        'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! request()->routeIs($item['active']),
                                    ])
                                >
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>
        @else
            <nav class="space-y-1.5">
                @foreach ($flatLinks as $link)
                    <a
                        href="{{ route($link['route']) }}"
                        @class([
                            'flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition',
                            'bg-slate-900 text-white shadow-sm' => request()->routeIs($link['active']),
                            'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->routeIs($link['active']),
                        ])
                    >
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>
        @endif
    </div>

    <div class="border-t border-slate-200 px-4 py-4">
        <div class="grid gap-2">
            <a
                href="{{ route('profile.edit') }}"
                class="rounded-2xl border border-slate-200 px-4 py-3 text-center text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:text-slate-900"
            >
                Profil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full rounded-2xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700"
                >
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>
