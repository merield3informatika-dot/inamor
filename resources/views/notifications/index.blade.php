<x-app-layout>

    <div class="mx-auto w-full max-w-5xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- HEADER --}}

        <div class="mb-6 flex items-start justify-between gap-4">

            <div>

                <div class="flex items-center gap-2">

                    <h1 class="text-xl font-bold text-gray-900">
                        Notifikasi
                    </h1>

                    @if($unreadCount > 0)

                        <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-600">
                            {{ $unreadCount }} belum dibaca
                        </span>

                    @endif

                </div>

                <p class="mt-1 text-sm text-gray-500">
                    Informasi terbaru dari workspace Anda.
                </p>

            </div>

        </div>


        {{-- NOTIFICATION LIST --}}

        <div class="overflow-hidden rounded-[20px] border border-gray-100 bg-white shadow-[0_4px_24px_rgba(0,0,0,0.02)]">

            @forelse($notifications as $notification)

                @php
                    $isUnread = is_null($notification->read_at);

                    $type = $notification->type;

                    $icon = match ($type) {
                        'announcement' => '📢',
                        'calendar_created' => '📅',
                        'calendar_reminder_10h',
                        'calendar_reminder_6h' => '⏰',
                        default => '🔔',
                    };

                    $iconBackground = match ($type) {
                        'announcement' => 'bg-yellow-50',
                        'calendar_created' => 'bg-blue-50',
                        'calendar_reminder_10h',
                        'calendar_reminder_6h' => 'bg-purple-50',
                        default => 'bg-gray-50',
                    };

                    $iconColor = match ($type) {
                        'announcement' => 'text-yellow-600',
                        'calendar_created' => 'text-blue-600',
                        'calendar_reminder_10h',
                        'calendar_reminder_6h' => 'text-purple-600',
                        default => 'text-gray-500',
                    };

                    $url = data_get($notification->data, 'url');
                @endphp


                <div
                    class="
                        relative flex gap-4 border-b border-gray-50 px-5 py-5
                        last:border-b-0
                        {{ $isUnread ? 'bg-blue-50/30' : 'bg-white' }}
                    "
                >

                    @if($isUnread)

                        <div class="absolute left-0 top-0 h-full w-1 bg-blue-500"></div>

                    @endif


                    {{-- ICON --}}

                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[12px] {{ $iconBackground }} {{ $iconColor }}"
                    >
                        <span class="text-base">
                            {{ $icon }}
                        </span>
                    </div>


                    {{-- CONTENT --}}

                    <div class="min-w-0 flex-1">

                        <div class="flex items-start justify-between gap-3">

                            <h3
                                class="
                                    truncate text-[13px]
                                    {{ $isUnread
                                        ? 'font-bold text-gray-900'
                                        : 'font-semibold text-gray-700'
                                    }}
                                "
                            >
                                {{ $notification->title }}
                            </h3>

                            <span class="shrink-0 text-[10px] text-gray-400">
                                {{ $notification->created_at?->diffForHumans() }}
                            </span>

                        </div>


                        <p class="mt-1.5 text-[12px] leading-relaxed text-gray-500">
                            {{ $notification->message }}
                        </p>


                        {{-- TYPE --}}

                        <div class="mt-2">

                            @if($type === 'announcement')

                                <span class="rounded-full bg-yellow-50 px-2 py-1 text-[9px] font-semibold text-yellow-600">
                                    Pengumuman
                                </span>

                            @elseif($type === 'calendar_created')

                                <span class="rounded-full bg-blue-50 px-2 py-1 text-[9px] font-semibold text-blue-600">
                                    Kalender
                                </span>

                            @elseif(
                                $type === 'calendar_reminder_10h' ||
                                $type === 'calendar_reminder_6h'
                            )

                                <span class="rounded-full bg-purple-50 px-2 py-1 text-[9px] font-semibold text-purple-600">
                                    Pengingat Kalender
                                </span>

                            @else

                                <span class="rounded-full bg-gray-50 px-2 py-1 text-[9px] font-semibold text-gray-500">
                                    Notifikasi
                                </span>

                            @endif

                        </div>


                        {{-- URL --}}

                        @if($url)

                            <a
                                href="{{ $url }}"
                                class="mt-3 inline-flex items-center gap-1 text-[10px] font-semibold text-blue-600 hover:text-blue-700"
                            >
                                Lihat detail

                                <svg
                                    class="h-3 w-3"
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

                        @endif

                    </div>

                </div>

            @empty

                <div class="flex flex-col items-center justify-center px-6 py-16 text-center">

                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-50">

                        <svg
                            class="h-6 w-6 text-gray-300"
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

                    <h3 class="text-sm font-semibold text-gray-700">
                        Belum ada notifikasi
                    </h3>

                    <p class="mt-1 max-w-sm text-[11px] leading-relaxed text-gray-400">
                        Semua informasi terbaru dari workspace akan muncul di sini.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</x-app-layout>