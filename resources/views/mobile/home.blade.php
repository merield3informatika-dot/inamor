@extends('layouts.mobile')

@section('title', 'Beranda')

@section('content')

<div class="mx-auto w-full max-w-lg px-4 pb-8 pt-5">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <header class="flex items-start justify-between gap-4">

        <div class="min-w-0">

            <p class="text-[11px] font-medium text-gray-400">
                {{ $workspace->name }}
            </p>

            <h1 class="mt-1 truncate text-[23px] font-bold tracking-tight text-gray-900">
                Halo, {{ $user->name }}
            </h1>

        </div>


        {{-- Notification --}}

        <a
            href="{{ route('notifications.index') }}"
            class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 shadow-sm transition active:scale-95"
            aria-label="Notifikasi"
        >

            <svg
                class="h-[18px] w-[18px]"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 00-12 0v.75c0 2.27-.89 4.24-2.31 6.022a23.848 23.848 0 005.454 1.31m5.713 0a24.255 24.255 0 01-5.713 0m5.713 0a3 3 0 11-5.713 0"
                />
            </svg>


            @if($unreadNotificationCount > 0)

                <span
                    class="absolute -right-0.5 -top-0.5 flex h-[16px] min-w-[16px] items-center justify-center rounded-full bg-red-500 px-1 text-[8px] font-bold leading-none text-white ring-2 ring-gray-50"
                >
                    {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                </span>

            @endif

        </a>

    </header>


    {{-- =========================================================
         ANNOUNCEMENT
         Hanya tampilkan 1 terbaru
    ========================================================== --}}

    <section class="mt-8">

        <div class="mb-3 flex items-center justify-between">

            <h2 class="text-[15px] font-bold text-gray-900">
                Pengumuman
            </h2>

            @if($announcements->count() > 0)

                <a
                    href="{{ route('announcements.index') }}"
                    class="text-[11px] font-semibold text-blue-600"
                >
                    Lihat semua
                </a>

            @endif

        </div>


        @php
            $latestAnnouncement = $announcements->first();
        @endphp


        @if($latestAnnouncement)

            <article class="rounded-[20px] border border-gray-100 bg-white p-4 shadow-[0_3px_16px_rgba(0,0,0,0.025)]">

                <div class="flex gap-3.5">

                    {{-- Icon --}}

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[12px] bg-blue-50 text-blue-600">

                        <svg
                            class="h-[18px] w-[18px]"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"
                            />
                        </svg>

                    </div>


                    {{-- Content --}}

                    <div class="min-w-0 flex-1">

                        <div class="flex items-start justify-between gap-3">

                            <h3 class="line-clamp-2 text-[13px] font-bold leading-snug text-gray-900">
                                {{ $latestAnnouncement->title }}
                            </h3>

                            <span class="shrink-0 text-[9px] text-gray-400">
                                {{
                                    ($latestAnnouncement->published_at ?? $latestAnnouncement->created_at)
                                        ?->diffForHumans()
                                }}
                            </span>

                        </div>


                        <p class="mt-1.5 line-clamp-3 text-[11px] leading-relaxed text-gray-500">
                            {{ $latestAnnouncement->content }}
                        </p>

                    </div>

                </div>

            </article>

        @else

            <div class="rounded-[20px] border border-dashed border-gray-200 bg-white px-5 py-7 text-center">

                <p class="text-[11px] font-medium text-gray-400">
                    Belum ada pengumuman terbaru.
                </p>

            </div>

        @endif

    </section>


    {{-- =========================================================
         NEXT EVENT
         Hanya tampilkan 1 agenda terdekat
    ========================================================== --}}

    <section class="mt-8">

        <div class="mb-3 flex items-center justify-between">

            <h2 class="text-[15px] font-bold text-gray-900">
                Agenda Berikutnya
            </h2>

            @if($upcomingEvents->count() > 0)

                <a
                    href="{{ route('calendar.index') }}"
                    class="text-[11px] font-semibold text-violet-600"
                >
                    Lihat kalender
                </a>

            @endif

        </div>


        @php
            $nextEvent = $upcomingEvents->first();
        @endphp


        @if($nextEvent)

            <article class="rounded-[20px] border border-gray-100 bg-white p-4 shadow-[0_3px_16px_rgba(0,0,0,0.025)]">

                <div class="flex items-center gap-4">

                    {{-- Date --}}

                    <div class="flex h-[58px] w-[58px] shrink-0 flex-col items-center justify-center rounded-[15px] bg-violet-50">

                        <span class="text-[9px] font-bold uppercase tracking-wide text-violet-500">
                            {{ $nextEvent->start_at->translatedFormat('M') }}
                        </span>

                        <span class="mt-0.5 text-[21px] font-bold leading-none text-violet-700">
                            {{ $nextEvent->start_at->format('d') }}
                        </span>

                    </div>


                    {{-- Event --}}

                    <div class="min-w-0 flex-1">

                        <h3 class="line-clamp-2 text-[13px] font-bold leading-snug text-gray-900">
                            {{ $nextEvent->title }}
                        </h3>


                        <div class="mt-1.5 flex items-center gap-2">

                            <span class="text-[10px] text-gray-500">
                                {{ $nextEvent->date_label }}
                            </span>

                            <span class="h-1 w-1 rounded-full bg-gray-300"></span>

                            <span class="text-[10px] text-gray-500">
                                {{ $nextEvent->time_range_label }}
                            </span>

                        </div>


                        @if($nextEvent->location)

                            <div class="mt-1.5 flex items-center gap-1.5">

                                <svg
                                    class="h-3 w-3 shrink-0 text-gray-400"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 10.5-7.5 10.5S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"
                                    />
                                </svg>

                                <span class="truncate text-[10px] text-gray-400">
                                    {{ $nextEvent->location }}
                                </span>

                            </div>

                        @endif

                    </div>

                </div>

            </article>

        @else

            <div class="rounded-[20px] border border-dashed border-gray-200 bg-white px-5 py-7 text-center">

                <p class="text-[11px] font-medium text-gray-400">
                    Belum ada agenda mendatang.
                </p>

            </div>

        @endif

    </section>


    {{-- =========================================================
         AI ASSISTANT
    ========================================================== --}}

    <section class="mt-8">

        <a
            href="{{ route('chat') }}"
            class="group block rounded-[20px] border border-violet-100 bg-violet-50/70 p-4 transition active:scale-[0.99]"
        >

            <div class="flex items-center gap-3.5">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[13px] bg-white text-violet-600 shadow-sm">

                    <svg
                        class="h-[19px] w-[19px]"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 003.09 3.09L9 5.25"
                        />
                    </svg>

                </div>


                <div class="min-w-0 flex-1">

                    <p class="text-[13px] font-bold text-gray-900">
                        Tanya AI Assistant
                    </p>

                    <p class="mt-0.5 text-[10px] text-gray-500">
                        Cari informasi dari knowledge workspace.
                    </p>

                </div>


                <svg
                    class="h-4 w-4 shrink-0 text-violet-300"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 5l7 7-7 7"
                    />
                </svg>

            </div>

        </a>

    </section>

</div>

@endsection