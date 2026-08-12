@extends('layouts.mobile')

@section('title', 'Kalender')

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

            <h1 class="mt-1 text-[23px] font-bold tracking-tight text-gray-900">
                Kalender
            </h1>

            <p class="mt-1 text-[11px] leading-relaxed text-gray-500">
                Lihat kegiatan dan agenda workspace.
            </p>

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

        </a>

    </header>


    {{-- =========================================================
         TODAY
    ========================================================== --}}

    @php
        $todayEvents = $events->filter(
            fn ($event) => $event->start_at->isToday()
        );

        $upcomingEvents = $events->filter(
            fn ($event) => ! $event->start_at->isToday()
        );
    @endphp


    <section class="mt-7">

        <div class="flex items-end justify-between">

            <div>

                <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-violet-600">
                    Hari ini
                </p>

                <h2 class="mt-0.5 text-[16px] font-bold text-gray-900">
                    {{ now()->translatedFormat('l, d F Y') }}
                </h2>

            </div>


            <span class="text-[10px] font-medium text-gray-400">
                {{ $todayEvents->count() }} kegiatan
            </span>

        </div>


        <div class="mt-3 space-y-3">

            @forelse($todayEvents as $event)

                <article class="rounded-[20px] border border-gray-100 bg-white p-4 shadow-[0_3px_16px_rgba(0,0,0,0.025)]">

                    <div class="flex gap-3.5">

                        {{-- Time --}}

                        <div class="flex w-[55px] shrink-0 flex-col items-center justify-center rounded-[14px] bg-violet-50 px-2 py-2">

                            <span class="text-[13px] font-bold text-violet-700">
                                {{ $event->start_at->format('H:i') }}
                            </span>

                            @if($event->end_at)

                                <span class="mt-0.5 text-[9px] text-violet-400">
                                    {{ $event->end_at->format('H:i') }}
                                </span>

                            @endif

                        </div>


                        {{-- Content --}}

                        <div class="min-w-0 flex-1">

                            <div class="flex items-start justify-between gap-3">

                                <h3 class="text-[13px] font-bold leading-snug text-gray-900">
                                    {{ $event->title }}
                                </h3>

                                @if($event->category)

                                    <span class="shrink-0 rounded-full bg-gray-50 px-2 py-1 text-[8px] font-semibold text-gray-500">
                                        {{ $event->category }}
                                    </span>

                                @endif

                            </div>


                            @if($event->description)

                                <p class="mt-1.5 line-clamp-2 text-[10px] leading-relaxed text-gray-500">
                                    {{ $event->description }}
                                </p>

                            @endif


                            @if($event->location)

                                <div class="mt-2 flex items-center gap-1.5">

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
                                        {{ $event->location }}
                                    </span>

                                </div>

                            @endif

                        </div>

                    </div>

                </article>

            @empty

                <div class="rounded-[20px] border border-dashed border-gray-200 bg-white px-5 py-8 text-center">

                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-gray-50">

                        <svg
                            class="h-5 w-5 text-gray-300"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6.75 3.75v2.25m10.5-2.25v2.25M3.75 9h16.5M5.25 5.25h13.5a1.5 1.5 0 011.5 1.5v12a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-12a1.5 1.5 0 011.5-1.5z"
                            />
                        </svg>

                    </div>

                    <p class="mt-3 text-[11px] font-medium text-gray-400">
                        Tidak ada kegiatan hari ini.
                    </p>

                </div>

            @endforelse

        </div>

    </section>


    {{-- =========================================================
         UPCOMING
    ========================================================== --}}

    <section class="mt-8">

        <div class="mb-3 flex items-center justify-between">

            <div>

                <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-gray-400">
                    Selanjutnya
                </p>

                <h2 class="mt-0.5 text-[16px] font-bold text-gray-900">
                    Agenda Mendatang
                </h2>

            </div>

            <span class="text-[10px] font-medium text-gray-400">
                {{ $upcomingEvents->count() }} kegiatan
            </span>

        </div>


        <div class="space-y-3">

            @forelse($upcomingEvents as $event)

                <article class="rounded-[20px] border border-gray-100 bg-white p-4 shadow-[0_3px_16px_rgba(0,0,0,0.025)]">

                    <div class="flex gap-3.5">

                        {{-- Date --}}

                        <div class="flex h-[58px] w-[58px] shrink-0 flex-col items-center justify-center rounded-[15px] bg-violet-50">

                            <span class="text-[9px] font-bold uppercase tracking-wide text-violet-500">
                                {{ $event->start_at->translatedFormat('M') }}
                            </span>

                            <span class="mt-0.5 text-[21px] font-bold leading-none text-violet-700">
                                {{ $event->start_at->format('d') }}
                            </span>

                        </div>


                        {{-- Event --}}

                        <div class="min-w-0 flex-1">

                            <div class="flex items-start justify-between gap-3">

                                <h3 class="text-[13px] font-bold leading-snug text-gray-900">
                                    {{ $event->title }}
                                </h3>

                                @if($event->category)

                                    <span class="shrink-0 rounded-full bg-gray-50 px-2 py-1 text-[8px] font-semibold text-gray-500">
                                        {{ $event->category }}
                                    </span>

                                @endif

                            </div>


                            <div class="mt-1.5 flex flex-wrap items-center gap-x-2 gap-y-1">

                                <span class="text-[10px] text-gray-500">
                                    {{ $event->date_label }}
                                </span>

                                <span class="h-1 w-1 rounded-full bg-gray-300"></span>

                                <span class="text-[10px] text-gray-500">
                                    {{ $event->time_range_label }}
                                </span>

                            </div>


                            @if($event->location)

                                <div class="mt-2 flex items-center gap-1.5">

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
                                        {{ $event->location }}
                                    </span>

                                </div>

                            @endif


                            @if($event->description)

                                <p class="mt-2 line-clamp-2 text-[10px] leading-relaxed text-gray-400">
                                    {{ $event->description }}
                                </p>

                            @endif

                        </div>

                    </div>

                </article>

            @empty

                <div class="rounded-[20px] border border-dashed border-gray-200 bg-white px-5 py-8 text-center">

                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-gray-50">

                        <svg
                            class="h-5 w-5 text-gray-300"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6.75 3.75v2.25m10.5-2.25v2.25M3.75 9h16.5M5.25 5.25h13.5a1.5 1.5 0 011.5 1.5v12a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-12a1.5 1.5 0 011.5-1.5z"
                            />
                        </svg>

                    </div>

                    <p class="mt-3 text-[11px] font-medium text-gray-400">
                        Belum ada agenda mendatang.
                    </p>

                </div>

            @endforelse

        </div>

    </section>


    {{-- =========================================================
         DATA CHECK
         Hapus setelah testing
    ========================================================== --}}

    <div class="mt-8 rounded-xl border border-dashed border-gray-200 bg-white p-3">

        <p class="text-[9px] font-semibold uppercase tracking-wider text-gray-400">
            Mobile Calendar Data
        </p>

        <div class="mt-2 grid grid-cols-2 gap-2 text-[10px]">

            <div>
                <span class="text-gray-400">Workspace:</span>
                <span class="font-semibold text-gray-700">
                    {{ $workspace->name }}
                </span>
            </div>

            <div>
                <span class="text-gray-400">Events:</span>
                <span class="font-semibold text-gray-700">
                    {{ $events->count() }}
                </span>
            </div>

        </div>

    </div>

</div>

@endsection