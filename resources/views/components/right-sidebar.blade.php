@props([
    'events' => [],
    'announcements' => [],
    'stats' => [],
    'analytics' => [],
])

@php
    /*
    |--------------------------------------------------------------------------
    | AI ANALYTICS
    |--------------------------------------------------------------------------
    | Dibaca langsung dari DashboardController.
    |
    | Support dua kemungkinan struktur:
    | 1. overview.requests_today
    | 2. today_requests
    |--------------------------------------------------------------------------
    */

    $overview = $analytics['overview'] ?? [];

    $requestsToday = (int) (
        $overview['requests_today']
        ?? $analytics['today_requests']
        ?? 0
    );

    $avgResponse = (int) (
        $overview['avg_response_ms']
        ?? $overview['today_avg_response_ms']
        ?? $analytics['latency']['average']
        ?? 0
    );

    $successRate = (float) (
        $overview['success_rate']
        ?? $analytics['success_rate']
        ?? 0
    );

    /*
    |--------------------------------------------------------------------------
    | Request Trend
    |--------------------------------------------------------------------------
    */

    $trend = $analytics['performance']['requests']
        ?? $analytics['requests_trend']
        ?? [];

    $chartValues = collect($trend)
        ->map(function ($item) {
            if (is_array($item)) {
                return (int) (
                    $item['requests']
                    ?? $item['count']
                    ?? $item['value']
                    ?? 0
                );
            }

            return (int) $item;
        })
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Kalau service belum mengirim trend,
    | coba ambil dari beberapa format alternatif.
    |--------------------------------------------------------------------------
    */

    if ($chartValues->count() < 2) {
        $alternativeTrend = $analytics['trend']
            ?? $analytics['daily_requests']
            ?? [];

        $chartValues = collect($alternativeTrend)
            ->map(function ($item) {
                if (is_array($item)) {
                    return (int) (
                        $item['requests']
                        ?? $item['count']
                        ?? $item['value']
                        ?? 0
                    );
                }

                return (int) $item;
            })
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Chart geometry
    |--------------------------------------------------------------------------
    */

    $chartWidth = 320;
    $chartHeight = 88;

    $maxValue = max(
        1,
        (int) ($chartValues->max() ?? 0)
    );

    $points = [];

    if ($chartValues->count() >= 2) {
        $count = $chartValues->count() - 1;

        foreach ($chartValues as $index => $value) {
            $x = ($index / $count) * $chartWidth;

            $y = $chartHeight
                - (($value / $maxValue) * ($chartHeight - 14))
                - 7;

            $points[] = [
                'x' => round($x, 2),
                'y' => round($y, 2),
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Smooth curve menggunakan quadratic Bézier
    |--------------------------------------------------------------------------
    */

    $curvePath = '';

    if (count($points) >= 2) {
        $curvePath = 'M ' . $points[0]['x'] . ' ' . $points[0]['y'];

        for ($i = 1; $i < count($points); $i++) {
            $previous = $points[$i - 1];
            $current = $points[$i];

            $midX = ($previous['x'] + $current['x']) / 2;

            $curvePath .= sprintf(
                ' C %s %s, %s %s, %s %s',
                $midX,
                $previous['y'],
                $midX,
                $current['y'],
                $current['x'],
                $current['y']
            );
        }
    }

    $latestRequestCount = (int) ($chartValues->last() ?? 0);
@endphp


{{-- ================================================================
     AGENDA MENDATANG
================================================================= --}}

<div class="rounded-[24px] border border-gray-100 bg-white p-6 shadow-[0_4px_24px_rgba(0,0,0,0.02)]">

    <div class="mb-6 flex items-center justify-between">

        <h3 class="text-[15px] font-bold text-gray-900">
            Agenda Mendatang
        </h3>

        <a
            href="{{ route('calendar.index') }}"
            class="text-[12px] font-medium text-gray-500 transition hover:text-blue-600"
        >
            Lihat kalender
        </a>

    </div>


    <div class="flex gap-6">

        {{-- Today --}}

        <div class="w-14 shrink-0 text-center">

            <div class="mb-1 text-[36px] font-bold leading-none text-blue-600">
                {{ now()->format('d') }}
            </div>

            <div class="text-[12px] font-medium text-gray-600">
                {{ now()->translatedFormat('F Y') }}
            </div>

            <div class="text-[12px] text-gray-500">
                {{ now()->translatedFormat('l') }}
            </div>

        </div>


        {{-- Timeline --}}

        <div class="relative flex-1 border-l border-gray-100 py-1 pl-5">

            @forelse($events as $event)

                <div class="relative mb-6 last:mb-0">

                    <div
                        class="absolute -left-[25px] top-1.5 h-2 w-2 rounded-full ring-4 ring-white {{ $event->color ?? 'bg-blue-600' }}"
                    ></div>

                    <p class="mb-1 text-[11px] font-semibold uppercase tracking-wide text-blue-600">
                        {{ $event->date_label ?? $event->start_at?->translatedFormat('d F Y') }}
                    </p>

                    <h4 class="mb-2 text-[13px] font-bold leading-snug text-gray-900">
                        {{ $event->title }}
                    </h4>

                    <div class="flex flex-col gap-1.5">

                        <div class="flex items-start text-[12px] font-medium text-gray-600">

                            <svg
                                class="mr-1.5 mt-[2px] h-3.5 w-3.5 shrink-0 text-gray-400"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>

                            <span>
                                {{ $event->time_range_label ?? 'Waktu belum ditentukan' }}
                            </span>

                        </div>


                        <div class="flex items-start text-[12px] text-gray-500">

                            <svg
                                class="mr-1.5 mt-[2px] h-3.5 w-3.5 shrink-0 text-gray-400"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"
                                />
                            </svg>

                            <span class="break-words">
                                {{ $event->location ?? 'Online' }}
                            </span>

                        </div>

                    </div>

                </div>

            @empty

                <div class="flex flex-col items-center justify-center py-4 text-center">

                    <svg
                        class="mb-2 h-8 w-8 text-gray-300"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25"
                        />
                    </svg>

                    <p class="text-[12px] font-medium text-gray-500">
                        Belum ada agenda mendatang
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>


{{-- ================================================================
     PENGUMUMAN TERBARU
================================================================= --}}

<div class="mt-6 rounded-[24px] border border-gray-100 bg-white p-6 shadow-[0_4px_24px_rgba(0,0,0,0.02)]">

    {{-- Header --}}
    <div class="mb-5 flex items-center justify-between">

        <div>
            <h3 class="text-[15px] font-bold text-gray-900">
                Pengumuman Terbaru
            </h3>

            <p class="mt-0.5 text-[11px] text-gray-400">
                Informasi terbaru dari workspace
            </p>
        </div>

        <a
            href="{{ route('announcements.index') }}"
            class="group inline-flex items-center gap-1 text-[11px] font-semibold text-gray-500 transition hover:text-orange-600"
        >
            Lihat semua

            <svg
                class="h-3.5 w-3.5 transition-transform duration-200 group-hover:translate-x-0.5"
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


    {{-- Announcements --}}
    @forelse($announcements as $announcement)

     <a
    href="{{ route('announcements.index') }}"
            class="group flex gap-4 border-b border-gray-50 py-3.5 last:border-0 transition-colors"
        >

            {{-- Icon --}}
            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[11px] bg-orange-50 text-orange-500 transition-colors duration-200 group-hover:bg-orange-100"
            >
                <svg
                    class="h-4 w-4"
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

                <div class="mb-1 flex items-start justify-between gap-3">

                    <h4
                        class="truncate text-[13px] font-bold text-gray-900 transition-colors group-hover:text-orange-600"
                    >
                        {{ $announcement->title }}
                    </h4>

                    <span class="shrink-0 text-[10px] text-gray-400">
                        {{ ($announcement->published_at ?? $announcement->created_at)?->diffForHumans() }}
                    </span>

                </div>

                <p class="line-clamp-2 text-[12px] leading-relaxed text-gray-500">
                    {{ $announcement->content }}
                </p>

            </div>

        </a>

    @empty

        {{-- Empty State --}}
        <div class="flex flex-col items-center justify-center py-8 text-center">

            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-gray-50">

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
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31.826-2.37-2.37a1.724 1.724 0 001.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                </svg>

            </div>

            <p class="text-[12px] font-medium text-gray-400">
                Belum ada pengumuman
            </p>

            <p class="mt-0.5 text-[10px] text-gray-400">
                Pengumuman yang sudah dipublish akan muncul di sini.
            </p>

        </div>

    @endforelse

</div>+


{{-- ================================================================
     STATISTIK LAYANAN
================================================================= --}}

<div class="mt-6 rounded-[24px] border border-gray-100 bg-white p-5 shadow-[0_4px_24px_rgba(0,0,0,0.02)]">

    {{-- Header --}}

    <div class="flex items-start justify-between">

        <div>

            <h3 class="text-[15px] font-bold text-gray-900">
                Statistik Layanan
            </h3>

            <p class="mt-0.5 text-[11px] text-gray-400">
                Aktivitas AI minggu ini
            </p>

        </div>


        <a
            href="{{ route('ai.analytics') }}"
            class="group inline-flex items-center gap-1 text-[11px] font-semibold text-blue-600 transition hover:text-blue-700"
        >

            Lihat laporan

            <svg
                class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m9 5 7 7-7 7"
                />
            </svg>

        </a>

    </div>


    {{-- Main numbers --}}

    <div class="mt-5 flex items-end justify-between">

        <div>

            <div class="flex items-baseline gap-1">

                <span class="text-[27px] font-bold tracking-tight text-gray-900">
                    {{ number_format($requestsToday) }}
                </span>

                <span class="text-[11px] text-gray-400">
                    requests
                </span>

            </div>

            <p class="mt-1 text-[10px] text-gray-400">
                AI digunakan hari ini
            </p>

        </div>


        <div class="text-right">

            <div class="flex items-center justify-end gap-1.5">

                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                <span class="text-[12px] font-bold text-emerald-600">
                    {{ number_format($successRate, 0) }}%
                </span>

            </div>

            <p class="mt-0.5 text-[10px] text-gray-400">
                success rate
            </p>

        </div>

    </div>


    {{-- Chart --}}

    <div class="mt-5">

        @if(count($points) >= 2)

            <div class="relative h-[88px] w-full overflow-hidden rounded-[14px] bg-gray-50/70">

                {{-- Grid --}}

                <div class="absolute inset-x-0 top-1/4 border-t border-gray-100"></div>
                <div class="absolute inset-x-0 top-1/2 border-t border-gray-100"></div>
                <div class="absolute inset-x-0 top-3/4 border-t border-gray-100"></div>


                <svg
                    class="absolute inset-0 h-full w-full"
                    viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}"
                    preserveAspectRatio="none"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >

                    <defs>

                        <linearGradient
                            id="serviceChartGradient"
                            x1="0"
                            y1="0"
                            x2="0"
                            y2="1"
                        >

                            <stop
                                offset="0%"
                                stop-color="#6366F1"
                                stop-opacity="0.18"
                            />

                            <stop
                                offset="100%"
                                stop-color="#6366F1"
                                stop-opacity="0"
                            />

                        </linearGradient>

                    </defs>


                    {{-- Filled area --}}

                    @php
                        $areaPath = $curvePath
                            . ' L '
                            . $chartWidth
                            . ' '
                            . $chartHeight
                            . ' L 0 '
                            . $chartHeight
                            . ' Z';
                    @endphp

                    <path
                        d="{{ $areaPath }}"
                        fill="url(#serviceChartGradient)"
                    />


                    {{-- Curve --}}

                    <path
                        d="{{ $curvePath }}"
                        stroke="#6366F1"
                        stroke-width="2.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        vector-effect="non-scaling-stroke"
                    />

                </svg>


                {{-- Last point --}}

                @php
                    $lastPoint = $points[count($points) - 1];
                @endphp

                <div
                    class="absolute h-2.5 w-2.5 rounded-full border-2 border-indigo-500 bg-white shadow-sm"
                    style="
                        left: {{ ($lastPoint['x'] / $chartWidth) * 100 }}%;
                        top: {{ ($lastPoint['y'] / $chartHeight) * 100 }}%;
                        transform: translate(-50%, -50%);
                    "
                ></div>

            </div>


            <div class="mt-2 flex items-center justify-between">

                <span class="text-[9px] text-gray-400">
                    7 hari terakhir
                </span>

                <span class="text-[9px] font-medium text-gray-500">
                    {{ number_format($chartValues->sum()) }} total requests
                </span>

            </div>

        @else

            <div class="flex h-[82px] flex-col items-center justify-center rounded-[14px] border border-dashed border-gray-200 bg-gray-50/50">

                <svg
                    class="mb-1 h-5 w-5 text-gray-300"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 17l6-6 4 4 8-9"
                    />
                </svg>

                <span class="text-[10px] text-gray-400">
                    Belum cukup data untuk grafik
                </span>

            </div>

        @endif

    </div>


    {{-- Bottom metrics --}}

    <div class="mt-4 grid grid-cols-2 gap-3">

        <div class="rounded-xl bg-gray-50 px-3 py-2.5">

            <p class="text-[9px] text-gray-400">
                Response
            </p>

            <p class="mt-0.5 text-[13px] font-bold text-gray-900">

                {{ number_format($avgResponse) }}

                <span class="text-[9px] font-medium text-gray-400">
                    ms
                </span>

            </p>

        </div>


        <div class="rounded-xl bg-gray-50 px-3 py-2.5">

            <p class="text-[9px] text-gray-400">
                Terakhir
            </p>

            <p class="mt-0.5 text-[13px] font-bold text-gray-900">

                {{ number_format($latestRequestCount) }}

                <span class="text-[9px] font-medium text-gray-400">
                    req
                </span>

            </p>

        </div>

    </div>

</div>