<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $schoolName ?? 'LAKS-Bel' }}</title>

        <script>
            (() => {
                const storedTheme = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const isDark = storedTheme ? storedTheme === 'dark' : prefersDark;

                document.documentElement.classList.toggle('dark', isDark);
            })();
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased lg:h-screen lg:overflow-hidden">
        <div
            x-data="{
                sidebarOpen: false,
                darkMode: document.documentElement.classList.contains('dark'),
                toggleDarkMode() {
                    this.darkMode = ! this.darkMode;
                    document.documentElement.classList.toggle('dark', this.darkMode);
                    localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                },
            }"
            class="min-h-screen bg-slate-100 transition-colors duration-300 lg:flex lg:h-screen"
        >
            @include('layouts.navigation')

            <div class="min-w-0 flex-1 lg:h-screen lg:overflow-y-auto">
                <div class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
                    <div class="flex items-center justify-between px-4 py-4 sm:px-6">
                        <div class="flex items-center gap-3">
                            <button
                                @click="sidebarOpen = true"
                                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white p-3 text-slate-600 shadow-sm transition hover:border-slate-300 hover:text-slate-900 lg:hidden"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>

                            <div class="hidden lg:block">
                                <p class="text-sm font-semibold text-slate-900">Panel Aplikasi</p>
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">{{ $schoolName ?? 'LAKS-Bel' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                @click="toggleDarkMode()"
                                class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-900"
                            >
                                <svg x-show="! darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z" />
                                </svg>
                                <svg x-cloak x-show="darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v1.5m0 15V21m9-9h-1.5M4.5 12H3m15.364 6.364l-1.06-1.06M6.696 6.696l-1.06-1.06m12.728 0l-1.06 1.06M6.696 17.304l-1.06 1.06M12 7.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9z" />
                                </svg>
                                <span x-text="darkMode ? 'Light' : 'Dark'"></span>
                            </button>

                            <div class="flex items-center gap-3">
                                @if (auth()->user()?->profile_photo_url)
                                    <img
                                        src="{{ auth()->user()->profile_photo_url }}"
                                        alt="{{ auth()->user()?->name }}"
                                        class="h-11 w-11 rounded-full object-cover ring-2 ring-slate-200"
                                    >
                                @else
                                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold uppercase text-white">
                                        {{ str(auth()->user()?->name)->substr(0, 1) }}
                                    </span>
                                @endif

                                <div class="text-right">
                                    <p class="text-sm font-semibold text-slate-900">{{ auth()->user()?->name }}</p>
                                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500">{{ auth()->user()?->role }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @isset($header)
                    <header class="border-b border-slate-200 bg-white">
                        <div class="px-4 py-5 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="px-4 py-6 sm:px-6 lg:px-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
