<header
    class="sticky top-0 z-40 bg-[#F8FAFC]/90 px-5 py-3 backdrop-blur-md md:px-6"
>

    {{-- =========================================================
         TOPBAR
    ========================================================== --}}

    <div
        class="flex h-[64px] items-center justify-end px-5 md:px-7"
        x-data="notificationDropdown()"
        @click.outside="close()"
    >

        <div class="flex items-center gap-2">

            {{-- =================================================
                 WORKSPACE CHAT
            ================================================== --}}

            <a
                href="{{ route('workspace.chat') }}"
                class="group relative flex h-[38px] items-center gap-2 rounded-[10px] border border-gray-200/80 bg-white px-3.5 text-gray-600 shadow-[0_2px_8px_rgba(0,0,0,0.02)] transition-all duration-200 hover:border-purple-200 hover:bg-purple-50/50 hover:text-purple-700"
            >

                <svg
                    class="h-4 w-4 text-gray-500 transition-colors group-hover:text-purple-600"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8.625 9.75h6.75m-6.75 3h4.5m-8.25 5.25l1.5-3.75A8.25 8.25 0 0112 3.75a8.25 8.25 0 018.25 8.25A8.25 8.25 0 0112 20.25a8.2 8.2 0 01-3.75-.9l-3.375.9z"
                    />
                </svg>

                <span class="hidden text-[12px] font-semibold sm:inline">
                    Workspace Chat
                </span>

                {{-- Chat indicator --}}

                <span
                    class="absolute -right-0.5 -top-1 h-2 w-2 rounded-full bg-purple-500 ring-2 ring-[#F8FAFC]"
                ></span>

            </a>


            {{-- =================================================
                 DIVIDER
            ================================================== --}}

            <div class="mx-1 h-5 w-px bg-gray-200"></div>


            {{-- =================================================
                 NOTIFICATION
            ================================================== --}}

            <div class="relative">

                {{-- Notification button --}}

                <button
                    type="button"
                    @click="toggle()"
                    class="group relative flex h-[38px] w-[38px] items-center justify-center rounded-[10px] text-gray-500 transition-all duration-200 hover:bg-gray-100 hover:text-gray-900"
                    aria-label="Notifikasi"
                    :aria-expanded="open.toString()"
                >

                    <svg
                        class="h-[18px] w-[18px] transition-transform duration-200 group-hover:-rotate-3"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 00-12 0v.75a8.967 8.967 0 01-2.31 6.022 23.848 23.848 0 005.455 1.31m5.712 0a24.255 24.255 0 01-5.712 0m5.712 0a3 3 0 11-5.712 0"
                        />
                    </svg>


                    {{-- Unread badge --}}

                    <template x-if="unreadCount > 0">

                        <span
                            class="absolute right-[4px] top-[3px] flex min-h-[15px] min-w-[15px] items-center justify-center rounded-full border-2 border-white bg-purple-600 px-1 text-[7px] font-bold leading-none text-white"
                            x-text="unreadCount > 99 ? '99+' : unreadCount"
                        ></span>

                    </template>

                </button>


                {{-- =================================================
                     NOTIFICATION CARD
                ================================================== --}}

                <div
                    x-cloak
                    x-show="open"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1 scale-[0.98]"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute right-0 top-[46px] z-50 w-[350px] overflow-hidden rounded-[16px] border border-gray-200 bg-white shadow-[0_18px_50px_rgba(15,23,42,0.12)]"
                >

                    {{-- =================================================
                         HEADER
                    ================================================== --}}

                    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">

                        <div>

                            <h3 class="text-[13px] font-bold text-gray-900">
                                Notifikasi
                            </h3>

                            <p
                                class="mt-0.5 text-[10px] text-gray-400"
                                x-text="
                                    unreadCount > 0
                                        ? `${unreadCount} belum dibaca`
                                        : 'Tidak ada notifikasi baru'
                                "
                            ></p>

                        </div>

                    </div>


                    {{-- =================================================
                         LOADING
                    ================================================== --}}

                    <div
                        x-show="loading"
                        class="flex items-center justify-center px-4 py-8"
                    >

                        <div class="h-5 w-5 animate-spin rounded-full border-2 border-gray-200 border-t-purple-500">
                        </div>

                    </div>


                    {{-- =================================================
                         EMPTY
                    ================================================== --}}

                    <div
                        x-show="!loading && notifications.length === 0"
                        class="px-5 py-8 text-center"
                    >

                        <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-[11px] bg-gray-50">

                            <svg
                                class="h-5 w-5 text-gray-300"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 00-12 0v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
                                />
                            </svg>

                        </div>

                        <p class="mt-3 text-[11px] font-semibold text-gray-700">
                            Belum ada notifikasi
                        </p>

                        <p class="mt-1 text-[9px] leading-relaxed text-gray-400">
                            Pengumuman dan aktivitas penting akan muncul di sini.
                        </p>

                    </div>


                    {{-- =================================================
                         NOTIFICATION LIST
                    ================================================== --}}

                    <div
                        x-show="!loading && notifications.length > 0"
                        class="max-h-[300px] overflow-y-auto"
                    >

                        <template
                            x-for="notification in notifications.slice(0, 5)"
                            :key="notification.id"
                        >

                            <a
                                :href="notification.data?.url ?? '#'"
                                class="flex items-start gap-3 border-b border-gray-50 px-4 py-3 transition-colors hover:bg-gray-50"
                            >

                                {{-- Icon --}}

                                <div
                                    class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-[10px]"
                                    :class="notificationType(notification.type).bg"
                                >

                                    <svg
                                        class="h-4 w-4"
                                        :class="notificationType(notification.type).text"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        x-html="notificationType(notification.type).icon"
                                    ></svg>

                                </div>


                                {{-- Content --}}

                                <div class="min-w-0 flex-1">

                                    <div class="flex items-start justify-between gap-2">

                                        <p
                                            class="truncate text-[11px] font-semibold"
                                            :class="
                                                notification.read_at
                                                    ? 'text-gray-700'
                                                    : 'text-gray-900'
                                            "
                                            x-text="notification.title"
                                        ></p>

                                        <span
                                            class="shrink-0 text-[8px] text-gray-400"
                                            x-text="formatTime(notification.created_at)"
                                        ></span>

                                    </div>


                                    <p
                                        class="mt-1 line-clamp-2 text-[9.5px] leading-relaxed text-gray-500"
                                        x-text="notification.message"
                                    ></p>

                                </div>


                                {{-- Unread indicator --}}

                                <span
                                    x-show="!notification.read_at"
                                    class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-purple-500"
                                ></span>

                            </a>

                        </template>

                    </div>


                    {{-- =================================================
                         FOOTER
                    ================================================== --}}

                    <div class="border-t border-gray-100 bg-gray-50/60">

                        <a
                            href="{{ route('notifications.index') }}"
                            class="flex items-center justify-center px-4 py-3 text-[10px] font-semibold text-gray-500 transition-colors hover:bg-gray-100 hover:text-purple-600"
                        >
                            Lihat semua notifikasi

                            <svg
                                class="ml-1 h-3 w-3"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ALPINE
         Cuma untuk toggle + fetch data.
         Tidak ada polling / mark-read / logic berat.
    ========================================================== --}}

    <script>

        function notificationDropdown() {

            return {

                open: false,

                loading: false,

                notifications: [],

                unreadCount: 0,


                async init() {

                    await this.fetchUnreadCount();

                },


             async toggle() {
    this.open = !this.open;

    if (!this.open) {
        return;
    }

    await this.fetchNotifications();

    if (this.unreadCount > 0) {
        await this.markAllAsRead();
    }
},
async markAllAsRead() {
    try {
        const response = await fetch(
            '{{ route('notifications.read-all') }}',
            {
                method: 'PATCH',

                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content'),
                },

                credentials: 'same-origin',
            }
        );

        if (!response.ok) {
            throw new Error(
                'Failed to mark notifications as read.'
            );
        }

        // Update UI langsung tanpa reload.
        this.notifications = this.notifications.map(
            notification => ({
                ...notification,
                read_at: new Date().toISOString(),
            })
        );

        this.unreadCount = 0;

    } catch (error) {

        console.error(
            'Mark notifications as read error:',
            error
        );

    }
},


                close() {

                    this.open = false;

                },


                async fetchUnreadCount() {

                    try {

                        const response = await fetch(
                            '{{ route('notifications.unread-count') }}',
                            {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                },

                                credentials: 'same-origin',
                            }
                        );


                        if (!response.ok) {

                            return;

                        }


                        const data = await response.json();


                        this.unreadCount = Number(
                            data.count ?? 0
                        );

                    } catch (error) {

                        console.error(
                            'Notification count error:',
                            error
                        );

                    }

                },


                async fetchNotifications() {

                    this.loading = true;


                    try {

                        const response = await fetch(
                            '{{ route('notifications.index') }}?limit=20',
                            {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                },

                                credentials: 'same-origin',
                            }
                        );


                        if (!response.ok) {

                            throw new Error(
                                'Failed to fetch notifications.'
                            );

                        }


                        const data = await response.json();


                        this.notifications = Array.isArray(data.data)
                            ? data.data
                            : [];


                        await this.fetchUnreadCount();

                    } catch (error) {

                        console.error(
                            'Notification fetch error:',
                            error
                        );

                        this.notifications = [];

                    } finally {

                        this.loading = false;

                    }

                },


                notificationType(type) {

                    const types = {

                        announcement: {

                            bg: 'bg-blue-50',

                            text: 'text-blue-600',

                            icon: `
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.5 10.5v3a1.5 1.5 0 001.5 1.5h1.5l2.5 4v-12l-2.5 4H6a1.5 1.5 0 00-1.5 1.5z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 9.5a3 3 0 010 5"
                                />
                            `,

                        },


                        calendar_created: {

                            bg: 'bg-violet-50',

                            text: 'text-violet-600',

                            icon: `
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3.75 9h16.5M5.25 5.25h13.5A1.5 1.5 0 0120.25 6.75v12A1.5 1.5 0 0118.75 20.25H5.25a1.5 1.5 0 01-1.5-1.5v-12a1.5 1.5 0 011.5-1.5z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 12v3m0 0h2.5"
                                />
                            `,

                        },


                        calendar_reminder_10h: {

                            bg: 'bg-amber-50',

                            text: 'text-amber-600',

                            icon: `
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 00-12 0v.75a8.967 8.967 0 01-2.31 6.022 23.848 23.848 0 005.455 1.31m5.712 0a24.255 24.255 0 01-5.712 0m5.712 0a3 3 0 11-5.712 0"
                                />
                            `,

                        },


                        calendar_reminder_6h: {

                            bg: 'bg-amber-50',

                            text: 'text-amber-600',

                            icon: `
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 00-12 0v.75a8.967 8.967 0 01-2.31 6.022 23.848 23.848 0 005.455 1.31m5.712 0a24.255 24.255 0 01-5.712 0m5.712 0a3 3 0 11-5.712 0"
                                />
                            `,

                        },

                    };


                    return types[type] ?? {

                        bg: 'bg-gray-50',

                        text: 'text-gray-500',

                        icon: `
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 00-12 0v.75a8.967 8.967 0 01-2.31 6.022 23.848 23.848 0 005.455 1.31m5.712 0a24.255 24.255 0 01-5.712 0"
                            />
                        `,

                    };

                },


                formatTime(timestamp) {

                    if (!timestamp) {

                        return '';

                    }


                    const date = new Date(timestamp);

                    const now = new Date();

                    const diff = Math.floor(
                        (now - date) / 1000
                    );


                    if (diff < 60) {

                        return 'baru saja';

                    }


                    if (diff < 3600) {

                        return `${Math.floor(diff / 60)}m`;

                    }


                    if (diff < 86400) {

                        return `${Math.floor(diff / 3600)}j`;

                    }


                    if (diff < 604800) {

                        return `${Math.floor(diff / 86400)}h`;

                    }


                    return date.toLocaleDateString(
                        'id-ID',
                        {
                            day: 'numeric',
                            month: 'short',
                        }
                    );

                },

            };

        }

    </script>

</header>