<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-900">Notifikasi</h2>
            @if ($notifications->where('read_at', null)->count() > 0)
                <form action="{{ route('guru.notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="space-y-4">
        @forelse ($notifications as $notification)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-slate-300 {{ $notification->read_at ? '' : 'border-l-4 border-l-blue-500' }}">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $notification->read_at ? 'bg-slate-100 text-slate-600' : 'bg-blue-100 text-blue-600' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-slate-900">Pengingat Jadwal Mengajar</p>
                        <p class="mt-1 text-sm text-slate-700">{{ $notification->data['message'] ?? '' }}</p>
                        <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                            <span>{{ $notification->data['hari'] ?? '' }}</span>
                            <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                            <span>{{ $notification->data['jam_mulai'] ?? '' }} - {{ $notification->data['jam_selesai'] ?? '' }}</span>
                            <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @if (! $notification->read_at)
                        <form action="{{ route('guru.notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex-shrink-0 text-xs font-medium text-blue-600 hover:text-blue-700">
                                Tandai dibaca
                            </button>
                        </form>
                    @else
                        <span class="flex-shrink-0 text-xs text-slate-400">Dibaca</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">
                    <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <p class="text-sm text-slate-500">Belum ada notifikasi</p>
            </div>
        @endforelse

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</x-app-layout>
