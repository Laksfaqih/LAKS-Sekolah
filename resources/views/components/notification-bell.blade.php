<div
    x-data="{
        open: false,
        unreadCount: 0,
        notifications: [],
        lastChecked: null,
        toasts: [],
        toastIdCounter: 0,
        pollingInterval: null,
        pollUrl: @js(route('guru.notifications.poll')),
        recentUrl: @js(route('guru.notifications.recent')),
        readUrlBase: @js(url('/guru/notifications')),
        markAllReadUrl: @js(route('guru.notifications.mark-all-read')),
        init() {
            this.fetchRecent();
            this.startPolling();
        },
        async fetchRecent() {
            try {
                const response = await fetch(this.recentUrl, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                this.unreadCount = data.unread_count;
                this.notifications = data.notifications;
            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
        },
        startPolling() {
            this.pollingInterval = setInterval(() => this.poll(), 30000);
        },
        async poll() {
            try {
                let url = this.pollUrl;
                if (this.lastChecked) {
                    url += '?last_checked=' + encodeURIComponent(this.lastChecked);
                }

                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();

                this.unreadCount = data.unread_count;
                this.lastChecked = data.last_checked;

                // Show toast for new notifications
                data.notifications.forEach(notification => {
                    this.showToast(notification);
                    // Add to dropdown list if not already there
                    if (!this.notifications.find(n => n.id === notification.id)) {
                        this.notifications.unshift(notification);
                        if (this.notifications.length > 10) {
                            this.notifications.pop();
                        }
                    }
                });
            } catch (error) {
                console.error('Error polling notifications:', error);
            }
        },
        showToast(notification) {
            const toastId = ++this.toastIdCounter;
            this.toasts.push({
                id: toastId,
                notification: notification
            });

            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                this.dismissToast(toastId);
            }, 5000);
        },
        dismissToast(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        },
        async markAsRead(notificationId) {
            try {
                const response = await fetch(`${this.readUrlBase}/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                });
                const data = await response.json();

                if (data.success) {
                    this.unreadCount = data.unread_count;
                    const notification = this.notifications.find(n => n.id === notificationId);
                    if (notification) {
                        notification.read_at = new Date().toISOString();
                    }
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },
        async markAllAsRead() {
            try {
                const response = await fetch(this.markAllReadUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                });
                const data = await response.json();

                if (data.success) {
                    this.unreadCount = 0;
                    this.notifications.forEach(n => {
                        n.read_at = new Date().toISOString();
                    });
                }
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
            }
        }
    }"
    @click.outside="open = false"
    class="relative"
>
    <!-- Bell Icon Button -->
    <button
        type="button"
        @click="open = !open"
        class="relative inline-flex items-center rounded-2xl border border-slate-200 bg-white p-2.5 text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-900"
    >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        <!-- Badge -->
        <span
            x-show="unreadCount > 0"
            x-text="unreadCount > 99 ? '99+' : unreadCount"
            x-cloak
            class="absolute -right-1 -top-1 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1.5 text-xs font-semibold text-white"
        ></span>
    </button>

    <!-- Dropdown -->
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 z-50 mt-2 w-80 origin-top-right rounded-2xl border border-slate-200 bg-white shadow-xl sm:w-96"
    >
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
            <h3 class="text-sm font-semibold text-slate-900">Notifikasi</h3>
            <button
                type="button"
                @click="markAllAsRead()"
                x-show="unreadCount > 0"
                class="text-xs font-medium text-blue-600 hover:text-blue-700"
            >
                Tandai semua dibaca
            </button>
        </div>

        <!-- Notifications List -->
        <div class="max-h-80 overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="px-4 py-8 text-center text-sm text-slate-500">
                    Tidak ada notifikasi
                </div>
            </template>

            <template x-for="notification in notifications" :key="notification.id">
                <div
                    @click="markAsRead(notification.id)"
                    class="cursor-pointer border-b border-slate-100 px-4 py-3 transition hover:bg-slate-50 last:border-b-0"
                    :class="{ 'bg-blue-50/50': !notification.read_at }"
                >
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex-shrink-0">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full"
                                :class="notification.read_at ? 'bg-slate-100 text-slate-600' : 'bg-blue-100 text-blue-600'"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm text-slate-900" x-text="notification.message"></p>
                            <p class="mt-1 text-xs text-slate-500" x-text="notification.created_at_human"></p>
                        </div>
                        <div
                            x-show="!notification.read_at"
                            class="mt-2 h-2 w-2 flex-shrink-0 rounded-full bg-blue-500"
                        ></div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="border-t border-slate-200 px-4 py-3">
            <a
                href="{{ route('guru.notifications.index') }}"
                class="block text-center text-sm font-medium text-blue-600 hover:text-blue-700"
            >
                Lihat semua notifikasi
            </a>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="fixed right-4 top-20 z-50 space-y-2">
        <template x-for="toast in toasts" :key="toast.id">
            <div
                x-show="true"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-full"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 translate-x-full"
                class="w-80 rounded-xl border border-slate-200 bg-white p-4 shadow-lg sm:w-96"
            >
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-slate-900">Pengingat Jadwal</p>
                        <p class="mt-1 text-sm text-slate-600" x-text="toast.notification.message"></p>
                    </div>
                    <button
                        type="button"
                        @click="dismissToast(toast.id)"
                        class="flex-shrink-0 text-slate-400 hover:text-slate-600"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>
