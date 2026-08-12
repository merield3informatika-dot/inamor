<x-app-layout>

    @php
        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $overview = $data['overview'] ?? [];
        $providers = $data['providers'] ?? [];
        $router = $data['router'] ?? [];
        $memory = $data['knowledge_memory'] ?? [];
        $cache = $data['cache'] ?? [];
        $retrieval = $data['retrieval'] ?? [];
        $errors = $data['errors'] ?? [];
        $cost = $data['cost'] ?? [];
        $questions = $data['top_questions'] ?? [];
        $timeline = $data['timeline'] ?? [];
        $systemHealth = $data['system_health'] ?? [];
        $trend = $data['performance']['requests'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | HELPERS
        |--------------------------------------------------------------------------
        */

        $number = fn ($value, $decimals = 0) =>
            number_format(
                (float) ($value ?? 0),
                $decimals,
                '.',
                ','
            );

        $percentage = fn ($value) =>
            max(0, min(100, (float) ($value ?? 0)));

        /*
        |--------------------------------------------------------------------------
        | HEALTH
        |--------------------------------------------------------------------------
        */

        $health = $overview['health'] ?? [
            'status' => 'healthy',
            'label' => 'Healthy',
            'color' => 'green',
        ];

        $healthColor = $health['color'] ?? 'green';

        $healthBadge = match ($healthColor) {
            'red' => 'bg-red-50 text-red-600 border-red-100',
            'orange' => 'bg-orange-50 text-orange-600 border-orange-100',
            'yellow' => 'bg-amber-50 text-amber-600 border-amber-100',
            default => 'bg-emerald-50 text-emerald-600 border-emerald-100',
        };

        $healthDot = match ($healthColor) {
            'red' => 'bg-red-500',
            'orange' => 'bg-orange-500',
            'yellow' => 'bg-amber-500',
            default => 'bg-emerald-500',
        };

        /*
        |--------------------------------------------------------------------------
        | SUCCESS RATE
        |--------------------------------------------------------------------------
        */

        $todayRequests = (int) ($overview['requests_today'] ?? 0);

        $todayFailed = 0;

        if (isset($overview['failed_today'])) {
            $todayFailed = (int) $overview['failed_today'];
        } elseif (isset($overview['failures_today'])) {
            $todayFailed = (int) $overview['failures_today'];
        } elseif (is_array($trend) && !empty($trend)) {
            $lastTrend = collect($trend)->last();

            if (is_array($lastTrend)) {
                $todayFailed = (int) (
                    $lastTrend['failed']
                    ?? $lastTrend['failures']
                    ?? $lastTrend['errors']
                    ?? 0
                );
            }
        }

        $successRate = $todayRequests > 0
            ? (($todayRequests - $todayFailed) / $todayRequests) * 100
            : 0;

        $successRate = max(0, min(100, round($successRate)));

        /*
        |--------------------------------------------------------------------------
        | REAL REQUEST TREND NORMALIZATION
        |
        | Backend boleh mengirim:
        | requests / count / total
        | failed / failures / errors
        | date / label / day
        |--------------------------------------------------------------------------
        */

        $chartData = collect($trend)
            ->map(function ($item) {
                if (!is_array($item)) {
                    return null;
                }

                $requests = (int) (
                    $item['requests']
                    ?? $item['request']
                    ?? $item['count']
                    ?? $item['total']
                    ?? 0
                );

                $failed = (int) (
                    $item['failed']
                    ?? $item['failures']
                    ?? $item['errors']
                    ?? 0
                );

                $date = $item['date']
                    ?? $item['day']
                    ?? $item['label']
                    ?? $item['created_at']
                    ?? '';

                $label = $item['label']
                    ?? $item['day']
                    ?? $date;

                return [
                    'date' => (string) $date,
                    'label' => (string) $label,
                    'requests' => $requests,
                    'failed' => $failed,
                ];
            })
            ->filter()
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | PROVIDER COUNT
        |--------------------------------------------------------------------------
        */

        $providerCount = is_countable($providers)
            ? count($providers)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | MEMORY / CACHE VALUES
        |--------------------------------------------------------------------------
        */

        $memoryCoverage = $percentage(
            $memory['coverage_pct']
            ?? $memory['percentage']
            ?? 0
        );

        $cacheHitRate = $percentage(
            $cache['hit_rate_pct']
            ?? $cache['percentage']
            ?? 0
        );

        $aiSaved = $percentage(
            $overview['ai_saved_pct']
            ?? $overview['saved_pct']
            ?? $memory['percentage']
            ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | HEALTH FALLBACK
        |--------------------------------------------------------------------------
        */

        $healthItems = collect($systemHealth);

        if ($healthItems->isEmpty()) {
            $healthItems = collect([
                [
                    'name' => 'Knowledge Memory',
                    'status' => 'healthy',
                    'label' => 'Healthy',
                ],
                [
                    'name' => 'AI Cache',
                    'status' => 'healthy',
                    'label' => 'Healthy',
                ],
                [
                    'name' => 'Retrieval',
                    'status' => 'healthy',
                    'label' => 'Healthy',
                ],
                [
                    'name' => 'Analytics',
                    'status' => 'healthy',
                    'label' => 'Healthy',
                ],
            ]);
        }
    @endphp


    <div class="min-h-screen bg-[#f8f9fb]">


        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="border-b border-gray-200/80 bg-white">

            <div class="mx-auto max-w-[1500px] px-6 py-5">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-center gap-3">

                        <a
                            href="{{ route('dashboard') }}"
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-500 transition hover:bg-gray-50 hover:text-gray-900"
                        >
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 19l-7-7 7-7"
                                />
                            </svg>
                        </a>

                        <div>

                            <div class="flex flex-wrap items-center gap-2">

                                <h1 class="text-[20px] font-bold tracking-tight text-gray-900">
                                    AI Analytics
                                </h1>

                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[9px] font-semibold {{ $healthBadge }}">

                                    <span class="h-1.5 w-1.5 rounded-full {{ $healthDot }}"></span>

                                    {{ $health['label'] ?? 'Unknown' }}

                                </span>

                            </div>

                            <p class="mt-1 text-[12px] text-gray-500">
                                Monitoring performa AI Engine dan Knowledge Engine workspace.
                            </p>

                        </div>

                    </div>


                    <button
                        type="button"
                        onclick="window.location.reload()"
                        class="inline-flex w-fit items-center gap-2 rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-[11px] font-semibold text-gray-600 transition hover:bg-gray-50 hover:text-gray-900"
                    >

                        <svg
                            class="h-3.5 w-3.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 4v5h5M20 20v-5h-5M5.5 15a7 7 0 0011.95 2M18.5 9a7 7 0 00-11.95-2"
                            />
                        </svg>

                        Refresh

                    </button>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- CONTENT --}}
        {{-- ========================================================= --}}

        <main class="mx-auto max-w-[1500px] px-6 py-6">


            {{-- ========================================================= --}}
            {{-- KPI --}}
            {{-- ========================================================= --}}

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">


                {{-- REQUESTS --}}

                <div class="rounded-[18px] border border-gray-200 bg-white p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-50 text-violet-600">

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                />
                            </svg>

                        </div>

                        <span class="text-[10px] text-gray-400">
                            Hari ini
                        </span>

                    </div>

                    <div class="mt-5">

                        <div class="text-[28px] font-bold tracking-tight text-gray-900">
                            {{ $number($todayRequests) }}
                        </div>

                        <p class="mt-1 text-[11px] text-gray-500">
                            AI Requests
                        </p>

                    </div>

                </div>


                {{-- LATENCY --}}

                <div class="rounded-[18px] border border-gray-200 bg-white p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 8v4l3 2"
                                />

                                <circle cx="12" cy="12" r="9"/>
                            </svg>

                        </div>

                        <span class="text-[10px] text-gray-400">
                            Average
                        </span>

                    </div>

                    <div class="mt-5">

                        <div class="text-[28px] font-bold tracking-tight text-gray-900">

                            {{ $number($overview['avg_response_ms'] ?? 0) }}

                            <span class="text-[12px] font-medium text-gray-400">
                                ms
                            </span>

                        </div>

                        <p class="mt-1 text-[11px] text-gray-500">
                            Response latency
                        </p>

                    </div>

                </div>


                {{-- SUCCESS --}}

                <div class="rounded-[18px] border border-gray-200 bg-white p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2.5"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>

                        </div>

                        <span class="text-[10px] text-gray-400">
                            Success
                        </span>

                    </div>

                    <div class="mt-5">

                        <div class="text-[28px] font-bold tracking-tight text-gray-900">
                            {{ $successRate }}%
                        </div>

                        <p class="mt-1 text-[11px] text-gray-500">
                            Request success rate
                        </p>

                    </div>

                </div>


                {{-- SAVED --}}

                <div class="rounded-[18px] border border-gray-200 bg-white p-5 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">

                            <svg
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13 7h8m0 0v8m0-8l-9 9-4-4-6 6"
                                />
                            </svg>

                        </div>

                        <span class="text-[10px] text-gray-400">
                            Efficiency
                        </span>

                    </div>

                    <div class="mt-5">

                        <div class="text-[28px] font-bold tracking-tight text-gray-900">
                            {{ $number($aiSaved) }}%
                        </div>

                        <p class="mt-1 text-[11px] text-gray-500">
                            AI Saved
                        </p>

                    </div>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- REQUEST TREND + ENGINE HEALTH --}}
            {{-- ========================================================= --}}

            <div class="mt-5 grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_340px]">


                {{-- REQUEST TREND --}}

                <section class="rounded-[20px] border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="flex items-start justify-between">

                        <div>

                            <h2 class="text-[15px] font-bold text-gray-900">
                                AI Requests
                            </h2>

                            <p class="mt-1 text-[11px] text-gray-500">
                                Aktivitas request AI berdasarkan data database.
                            </p>

                        </div>


                        <div class="flex items-center gap-4">

                            <div class="flex items-center gap-1.5">

                                <span class="h-2 w-2 rounded-full bg-violet-500"></span>

                                <span class="text-[9px] text-gray-500">
                                    Requests
                                </span>

                            </div>

                            <div class="flex items-center gap-1.5">

                                <span class="h-2 w-2 rounded-full bg-red-400"></span>

                                <span class="text-[9px] text-gray-500">
                                    Failed
                                </span>

                            </div>

                        </div>

                    </div>


                    @if(count($chartData) > 0)

                        <div class="relative mt-8 h-[300px]">

                            {{-- GRID --}}

                            <div class="pointer-events-none absolute inset-0 flex flex-col justify-between">

                                @for($i = 0; $i < 5; $i++)

                                    <div class="border-t border-dashed border-gray-100"></div>

                                @endfor

                            </div>


                            {{-- SVG --}}

                            <svg
                                id="aiRequestChart"
                                class="absolute inset-0 h-full w-full overflow-visible"
                                viewBox="0 0 900 300"
                                preserveAspectRatio="none"
                            >

                                <defs>

                                    <linearGradient
                                        id="requestAreaGradient"
                                        x1="0"
                                        y1="0"
                                        x2="0"
                                        y2="1"
                                    >

                                        <stop
                                            offset="0%"
                                            stop-color="#7c3aed"
                                            stop-opacity="0.16"
                                        />

                                        <stop
                                            offset="100%"
                                            stop-color="#7c3aed"
                                            stop-opacity="0"
                                        />

                                    </linearGradient>

                                </defs>


                                <path
                                    id="requestArea"
                                    fill="url(#requestAreaGradient)"
                                />


                                <path
                                    id="requestLine"
                                    fill="none"
                                    stroke="#7c3aed"
                                    stroke-width="3"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />


                                <path
                                    id="failedLine"
                                    fill="none"
                                    stroke="#f87171"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-dasharray="5 5"
                                />


                                <g id="requestDots"></g>
                                <g id="failedDots"></g>

                            </svg>


                            {{-- TOOLTIP --}}

                            <div
                                id="chartTooltip"
                                class="pointer-events-none absolute z-20 hidden min-w-[130px] rounded-xl border border-gray-200 bg-white px-3 py-2.5 shadow-xl"
                            >

                                <div
                                    id="tooltipDate"
                                    class="text-[9px] font-medium text-gray-400"
                                ></div>

                                <div class="mt-1.5 flex items-center justify-between gap-5">

                                    <span class="text-[10px] text-gray-500">
                                        Requests
                                    </span>

                                    <span
                                        id="tooltipRequests"
                                        class="text-[11px] font-bold text-violet-600"
                                    ></span>

                                </div>

                                <div class="mt-1 flex items-center justify-between gap-5">

                                    <span class="text-[10px] text-gray-500">
                                        Failed
                                    </span>

                                    <span
                                        id="tooltipFailed"
                                        class="text-[11px] font-bold text-red-500"
                                    ></span>

                                </div>

                            </div>


                            {{-- LABELS --}}

                            <div
                                id="chartLabels"
                                class="absolute inset-x-0 -bottom-6 flex justify-between"
                            ></div>

                        </div>

                    @else

                        <div class="mt-6 flex h-[300px] items-center justify-center rounded-2xl border border-dashed border-gray-200 bg-gray-50/50">

                            <div class="text-center">

                                <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-white text-gray-400 shadow-sm">

                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M4 19V5m0 14h16M7 15l3-4 3 2 4-6"
                                        />
                                    </svg>

                                </div>

                                <p class="mt-3 text-[12px] font-semibold text-gray-700">
                                    Trend belum tersedia
                                </p>

                                <p class="mt-1 max-w-[250px] text-[10px] leading-relaxed text-gray-400">
                                    Belum ada data request trend yang dikirim oleh analytics service.
                                </p>

                            </div>

                        </div>

                    @endif

                </section>


                {{-- ENGINE HEALTH --}}

                <section class="rounded-[20px] border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="flex items-start justify-between">

                        <div>

                            <h2 class="text-[15px] font-bold text-gray-900">
                                AI Engine
                            </h2>

                            <p class="mt-1 text-[11px] text-gray-500">
                                System health
                            </p>

                        </div>

                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[9px] font-semibold {{ $healthBadge }}">

                            <span class="h-1.5 w-1.5 rounded-full {{ $healthDot }}"></span>

                            {{ $health['label'] ?? 'Unknown' }}

                        </span>

                    </div>


                    <div class="mt-6 space-y-2">

                        @foreach($healthItems as $system)

                            @php
                                $systemStatus = $system['status'] ?? 'unknown';

                                $isHealthy = in_array(
                                    $systemStatus,
                                    ['healthy', 'ok', 'success'],
                                    true
                                );
                            @endphp

                            <div class="flex items-center justify-between rounded-xl border border-gray-100 px-3.5 py-3">

                                <span class="text-[10.5px] font-medium text-gray-700">
                                    {{ $system['name'] ?? 'System' }}
                                </span>

                                <span class="inline-flex items-center gap-1.5 text-[9px] font-semibold {{ $isHealthy ? 'text-emerald-600' : 'text-red-600' }}">

                                    <span class="h-1.5 w-1.5 rounded-full {{ $isHealthy ? 'bg-emerald-500' : 'bg-red-500' }}"></span>

                                    {{ $system['label'] ?? ucfirst($systemStatus) }}

                                </span>

                            </div>

                        @endforeach

                    </div>

                </section>

            </div>


            {{-- ========================================================= --}}
            {{-- PROVIDER PERFORMANCE --}}
            {{-- ========================================================= --}}

            <section class="mt-5 rounded-[20px] border border-gray-200 bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-[15px] font-bold text-gray-900">
                            Provider Performance
                        </h2>

                        <p class="mt-1 text-[11px] text-gray-500">
                            Performa provider AI yang digunakan workspace.
                        </p>

                    </div>

                    <span class="text-[10px] text-gray-400">
                        {{ $providerCount }} providers
                    </span>

                </div>


                @if($providerCount > 0)

                    <div class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">

                        @foreach($providers as $name => $provider)

                            @php
                                $providerRequests = (int) (
                                    $provider['requests']
                                    ?? $provider['request']
                                    ?? $provider['total']
                                    ?? 0
                                );

                                $providerFailed = (int) (
                                    $provider['failed']
                                    ?? $provider['failures']
                                    ?? 0
                                );

                                $providerSuccess = $provider['success_rate']
                                    ?? $provider['success_percentage']
                                    ?? null;

                                if ($providerSuccess === null) {
                                    $providerSuccess = $providerRequests > 0
                                        ? (($providerRequests - $providerFailed) / $providerRequests) * 100
                                        : 0;
                                }

                                $providerSuccess = $percentage($providerSuccess);

                                $providerStatus = strtolower(
                                    (string) ($provider['status'] ?? 'healthy')
                                );

                                $providerStatusClass = match ($providerStatus) {
                                    'failed',
                                    'down',
                                    'error' => 'bg-red-50 text-red-600',

                                    'warning',
                                    'quota',
                                    'degraded' => 'bg-amber-50 text-amber-600',

                                    default => 'bg-emerald-50 text-emerald-600',
                                };
                            @endphp


                            <div class="rounded-[16px] border border-gray-100 p-5 transition hover:border-gray-200 hover:shadow-sm">

                                <div class="flex items-center justify-between">

                                    <div class="flex min-w-0 items-center gap-3">

                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gray-50 text-[10px] font-bold uppercase text-gray-500">
                                            {{ strtoupper(substr((string) $name, 0, 2)) }}
                                        </div>

                                        <div class="min-w-0">

                                            <p class="truncate text-[12px] font-bold capitalize text-gray-900">
                                                {{ $name }}
                                            </p>

                                            <p class="mt-0.5 truncate text-[9px] text-gray-400">
                                                {{ $provider['model'] ?? 'Model tidak tersedia' }}
                                            </p>

                                        </div>

                                    </div>


                                    <span class="shrink-0 rounded-full px-2 py-1 text-[8px] font-semibold {{ $providerStatusClass }}">
                                        {{ ucfirst($providerStatus) }}
                                    </span>

                                </div>


                                <div class="mt-6 grid grid-cols-3 gap-3">

                                    <div>

                                        <p class="text-[9px] text-gray-400">
                                            Requests
                                        </p>

                                        <p class="mt-1 text-[16px] font-bold text-gray-900">
                                            {{ $number($providerRequests) }}
                                        </p>

                                    </div>


                                    <div>

                                        <p class="text-[9px] text-gray-400">
                                            Success
                                        </p>

                                        <p class="mt-1 text-[16px] font-bold text-gray-900">
                                            {{ $number($providerSuccess) }}%
                                        </p>

                                    </div>


                                    <div>

                                        <p class="text-[9px] text-gray-400">
                                            Failed
                                        </p>

                                        <p class="mt-1 text-[16px] font-bold {{ $providerFailed > 0 ? 'text-red-600' : 'text-gray-900' }}">
                                            {{ $number($providerFailed) }}
                                        </p>

                                    </div>

                                </div>


                                <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-gray-100">

                                    <div
                                        class="h-full rounded-full bg-emerald-500 transition-all"
                                        style="width: {{ $providerSuccess }}%"
                                    ></div>

                                </div>


                                <div class="mt-3 flex items-center justify-between text-[9px]">

                                    <span class="text-gray-400">
                                        Avg response
                                    </span>

                                    <span class="font-semibold text-gray-600">

                                        {{ $number(
                                            $provider['avg_response_ms']
                                            ?? $provider['latency']
                                            ?? 0
                                        ) }}

                                        ms

                                    </span>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="mt-5 rounded-2xl border border-dashed border-gray-200 bg-gray-50/50 px-5 py-10 text-center">

                        <p class="text-[12px] font-semibold text-gray-700">
                            Belum ada provider activity
                        </p>

                        <p class="mt-1 text-[10px] text-gray-400">
                            Data provider akan muncul setelah AI request tercatat.
                        </p>

                    </div>

                @endif

            </section>


            {{-- ========================================================= --}}
            {{-- KNOWLEDGE / CACHE / RETRIEVAL --}}
            {{-- ========================================================= --}}

            <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">


                {{-- KNOWLEDGE MEMORY --}}

                <section class="rounded-[20px] border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="flex items-start justify-between">

                        <div>

                            <h2 class="text-[15px] font-bold text-gray-900">
                                Knowledge Memory
                            </h2>

                            <p class="mt-1 text-[11px] text-gray-500">
                                Pemanfaatan knowledge internal.
                            </p>

                        </div>

                        <span class="text-[13px] font-bold text-violet-600">
                            {{ $number($memoryCoverage) }}%
                        </span>

                    </div>


                    <div class="mt-6 h-2 overflow-hidden rounded-full bg-gray-100">

                        <div
                            class="h-full rounded-full bg-violet-500 transition-all"
                            style="width: {{ $memoryCoverage }}%"
                        ></div>

                    </div>


                    <div class="mt-6 grid grid-cols-2 gap-3">

                        <div class="rounded-xl bg-gray-50 p-3.5">

                            <p class="text-[9px] text-gray-400">
                                Hits
                            </p>

                            <p class="mt-1 text-[17px] font-bold text-gray-900">
                                {{ $number($memory['hit'] ?? $memory['hits'] ?? 0) }}
                            </p>

                        </div>


                        <div class="rounded-xl bg-gray-50 p-3.5">

                            <p class="text-[9px] text-gray-400">
                                Misses
                            </p>

                            <p class="mt-1 text-[17px] font-bold text-gray-900">
                                {{ $number($memory['miss'] ?? $memory['misses'] ?? 0) }}
                            </p>

                        </div>

                    </div>

                </section>


                {{-- CACHE --}}

                <section class="rounded-[20px] border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="flex items-start justify-between">

                        <div>

                            <h2 class="text-[15px] font-bold text-gray-900">
                                AI Cache
                            </h2>

                            <p class="mt-1 text-[11px] text-gray-500">
                                Efisiensi cache terhadap request.
                            </p>

                        </div>

                        <span class="text-[13px] font-bold text-blue-600">
                            {{ $number($cacheHitRate) }}%
                        </span>

                    </div>


                    <div class="mt-6 h-2 overflow-hidden rounded-full bg-gray-100">

                        <div
                            class="h-full rounded-full bg-blue-500 transition-all"
                            style="width: {{ $cacheHitRate }}%"
                        ></div>

                    </div>


                    <div class="mt-6 grid grid-cols-2 gap-3">

                        <div class="rounded-xl bg-gray-50 p-3.5">

                            <p class="text-[9px] text-gray-400">
                                Cache Hits
                            </p>

                            <p class="mt-1 text-[17px] font-bold text-gray-900">
                                {{ $number($cache['hit'] ?? $cache['hits'] ?? 0) }}
                            </p>

                        </div>


                        <div class="rounded-xl bg-gray-50 p-3.5">

                            <p class="text-[9px] text-gray-400">
                                Cache Size
                            </p>

                            <p class="mt-1 text-[17px] font-bold text-gray-900">
                                {{ $number($cache['size'] ?? 0) }}
                            </p>

                        </div>

                    </div>

                </section>


                {{-- RETRIEVAL --}}

                <section class="rounded-[20px] border border-gray-200 bg-white p-6 shadow-sm">

                    <div>

                        <h2 class="text-[15px] font-bold text-gray-900">
                            Retrieval
                        </h2>

                        <p class="mt-1 text-[11px] text-gray-500">
                            Performa pencarian knowledge.
                        </p>

                    </div>


                    <div class="mt-6 grid grid-cols-2 gap-3">

                        <div class="rounded-xl border border-gray-100 p-3.5">

                            <p class="text-[9px] text-gray-400">
                                Avg Score
                            </p>

                            <p class="mt-1 text-[18px] font-bold text-gray-900">
                                {{ $retrieval['avg_score'] ?? 0 }}
                            </p>

                        </div>


                        <div class="rounded-xl border border-gray-100 p-3.5">

                            <p class="text-[9px] text-gray-400">
                                Search
                            </p>

                            <p class="mt-1 text-[18px] font-bold text-gray-900">

                                {{ $number(
                                    $retrieval['avg_search_ms']
                                    ?? $retrieval['search_ms']
                                    ?? 0
                                ) }}

                                <span class="text-[9px] font-medium text-gray-400">
                                    ms
                                </span>

                            </p>

                        </div>


                        <div class="rounded-xl border border-gray-100 p-3.5">

                            <p class="text-[9px] text-gray-400">
                                Retrieved
                            </p>

                            <p class="mt-1 text-[18px] font-bold text-gray-900">
                                {{ $number($retrieval['retrieved'] ?? 0) }}
                            </p>

                        </div>


                        <div class="rounded-xl border border-gray-100 p-3.5">

                            <p class="text-[9px] text-gray-400">
                                No Match
                            </p>

                            <p class="mt-1 text-[18px] font-bold text-gray-900">
                                {{ $number($retrieval['no_match'] ?? 0) }}
                            </p>

                        </div>

                    </div>

                </section>

            </div>


            {{-- ========================================================= --}}
            {{-- QUESTIONS + ERRORS --}}
            {{-- ========================================================= --}}

            <div class="mt-5 grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_340px]">


                {{-- TOP QUESTIONS --}}

                <section class="rounded-[20px] border border-gray-200 bg-white p-6 shadow-sm">

                    <div>

                        <h2 class="text-[15px] font-bold text-gray-900">
                            Top Questions
                        </h2>

                        <p class="mt-1 text-[11px] text-gray-500">
                            Pertanyaan yang paling sering diproses AI.
                        </p>

                    </div>


                    <div class="mt-5 overflow-hidden rounded-xl border border-gray-100">

                        @forelse($questions as $index => $question)

                            <div class="flex items-center gap-4 border-b border-gray-100 px-4 py-3.5 last:border-0">

                                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-gray-50 text-[9px] font-bold text-gray-500">
                                    {{ $index + 1 }}
                                </div>


                                <div class="min-w-0 flex-1">

                                    <p class="truncate text-[11px] font-semibold text-gray-800">
                                        {{ $question['question'] ?? $question['normalized_question'] ?? '—' }}
                                    </p>

                                    <p class="mt-1 text-[9px] text-gray-400">

                                        {{ $question['provider'] ?? 'Internal' }}

                                        @if(isset($question['date']))
                                            • {{ $question['date'] }}
                                        @endif

                                    </p>

                                </div>


                                <div class="shrink-0 text-right">

                                    <p class="text-[13px] font-bold text-gray-900">
                                        {{ $number($question['count'] ?? $question['requests'] ?? 0) }}
                                    </p>

                                    <p class="text-[8px] text-gray-400">
                                        requests
                                    </p>

                                </div>

                            </div>

                        @empty

                            <div class="px-5 py-10 text-center">

                                <p class="text-[11px] font-medium text-gray-500">
                                    Belum ada pertanyaan.
                                </p>

                                <p class="mt-1 text-[9px] text-gray-400">
                                    Pertanyaan AI akan muncul di sini setelah ada aktivitas.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </section>


                {{-- ERRORS --}}

                <section class="rounded-[20px] border border-gray-200 bg-white p-6 shadow-sm">

                    <div>

                        <h2 class="text-[15px] font-bold text-gray-900">
                            Error Overview
                        </h2>

                        <p class="mt-1 text-[11px] text-gray-500">
                            Error yang tercatat dari AI Engine.
                        </p>

                    </div>


                    <div class="mt-5 space-y-2">

                        @php
                            $errorRows = [
                                'Quota' => $errors['quota'] ?? 0,
                                'Timeout' => $errors['timeout'] ?? 0,
                                'Failed' => $errors['failed'] ?? 0,
                                'Unauthorized' => $errors['unauthorized'] ?? 0,
                                'Server Error' => $errors['server_error'] ?? 0,
                                'Provider Down' => $errors['provider_down'] ?? 0,
                            ];
                        @endphp


                        @foreach($errorRows as $label => $value)

                            <div class="flex items-center justify-between rounded-xl bg-gray-50 px-3.5 py-3">

                                <span class="text-[10px] font-medium text-gray-600">
                                    {{ $label }}
                                </span>

                                <span class="text-[12px] font-bold {{ $value > 0 ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $number($value) }}
                                </span>

                            </div>

                        @endforeach

                    </div>

                </section>

            </div>


            {{-- ========================================================= --}}
            {{-- RECENT AI ACTIVITY --}}
            {{-- ========================================================= --}}

            <section class="mt-5 rounded-[20px] border border-gray-200 bg-white p-6 shadow-sm">

                <div>

                    <h2 class="text-[15px] font-bold text-gray-900">
                        Recent AI Activity
                    </h2>

                    <p class="mt-1 text-[11px] text-gray-500">
                        Aktivitas AI terbaru pada workspace ini.
                    </p>

                </div>


                <div class="mt-5 divide-y divide-gray-100">

                    @forelse($timeline as $event)

                        @php
                            $eventType = $event['type'] ?? '';

                            $eventDot = match ($eventType) {
                                'provider_down',
                                'failed',
                                'error' => 'bg-red-500',

                                'provider_success',
                                'success' => 'bg-emerald-500',

                                default => 'bg-violet-500',
                            };
                        @endphp


                        <div class="flex items-center gap-4 py-3.5">

                            <span class="h-2 w-2 shrink-0 rounded-full {{ $eventDot }}"></span>


                            <div class="min-w-0 flex-1">

                                <p class="text-[11px] font-semibold text-gray-800">
                                    {{ $event['label'] ?? 'AI Request' }}
                                </p>

                                <p class="mt-0.5 truncate text-[10px] text-gray-500">
                                    {{ $event['detail'] ?? $event['description'] ?? '—' }}
                                </p>

                            </div>


                            <span class="shrink-0 text-[9px] text-gray-400">
                                {{ $event['time'] ?? $event['created_at'] ?? '—' }}
                            </span>

                        </div>

                    @empty

                        <div class="py-12 text-center">

                            <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-gray-50 text-gray-400">

                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                >
                                    <circle cx="12" cy="12" r="9"/>
                                    <path
                                        stroke-linecap="round"
                                        d="M12 7v5l3 2"
                                    />
                                </svg>

                            </div>

                            <p class="mt-3 text-[11px] font-semibold text-gray-600">
                                Belum ada aktivitas AI
                            </p>

                            <p class="mt-1 text-[9px] text-gray-400">
                                Aktivitas request akan muncul setelah AI digunakan.
                            </p>

                        </div>

                    @endforelse

                </div>

            </section>


            {{-- FOOTER --}}

            <div class="py-8 text-center">

                <p class="text-[10px] text-gray-400">
                    INAMOR AI Analytics
                    <span class="mx-1">•</span>
                    Workspace isolated
                    <span class="mx-1">•</span>
                    Live database metrics
                </p>

            </div>

        </main>

    </div>


    {{-- ========================================================= --}}
    {{-- REAL SVG CHART --}}
    {{-- ========================================================= --}}

    @if(count($chartData) > 0)

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const data = @json($chartData);

                const svg = document.getElementById('aiRequestChart');

                if (!svg || !Array.isArray(data) || data.length === 0) {
                    return;
                }


                const requestLine = document.getElementById('requestLine');
                const failedLine = document.getElementById('failedLine');
                const requestArea = document.getElementById('requestArea');

                const requestDots = document.getElementById('requestDots');
                const failedDots = document.getElementById('failedDots');

                const labels = document.getElementById('chartLabels');

                const tooltip = document.getElementById('chartTooltip');
                const tooltipDate = document.getElementById('tooltipDate');
                const tooltipRequests = document.getElementById('tooltipRequests');
                const tooltipFailed = document.getElementById('tooltipFailed');


                const width = 900;
                const height = 300;

                const paddingX = 18;
                const paddingY = 24;


                const requests = data.map(item => Number(item.requests || 0));
                const failed = data.map(item => Number(item.failed || 0));


                const maxValue = Math.max(
                    ...requests,
                    ...failed,
                    1
                );


                const chartHeight = height - (paddingY * 2);
                const chartWidth = width - (paddingX * 2);


                const step = data.length > 1
                    ? chartWidth / (data.length - 1)
                    : 0;


                function getX(index) {

                    if (data.length === 1) {
                        return width / 2;
                    }

                    return paddingX + (index * step);
                }


                function getY(value) {

                    const safeValue = Math.max(
                        0,
                        Number(value || 0)
                    );

                    return (
                        height -
                        paddingY -
                        ((safeValue / maxValue) * chartHeight)
                    );
                }


                function buildPoints(values) {

                    return values.map(function (value, index) {

                        return {
                            x: getX(index),
                            y: getY(value),
                            value: value
                        };

                    });

                }


                const requestPoints = buildPoints(requests);
                const failedPoints = buildPoints(failed);


                function pathFromPoints(points) {

                    if (!points.length) {
                        return '';
                    }

                    return points
                        .map(function (point, index) {

                            return (
                                index === 0 ? 'M ' : 'L '
                            ) + point.x + ' ' + point.y;

                        })
                        .join(' ');

                }


                requestLine.setAttribute(
                    'd',
                    pathFromPoints(requestPoints)
                );


                failedLine.setAttribute(
                    'd',
                    pathFromPoints(failedPoints)
                );


                if (requestPoints.length > 0) {

                    const first = requestPoints[0];
                    const last = requestPoints[requestPoints.length - 1];

                    const areaParts = [
                        'M ' + first.x + ' ' + height,
                    ];


                    requestPoints.forEach(function (point) {

                        areaParts.push(
                            'L ' + point.x + ' ' + point.y
                        );

                    });


                    areaParts.push(
                        'L ' + last.x + ' ' + height
                    );

                    areaParts.push('Z');


                    requestArea.setAttribute(
                        'd',
                        areaParts.join(' ')
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | REQUEST DOTS
                |--------------------------------------------------------------------------
                */

                requestPoints.forEach(function (point, index) {

                    const circle = document.createElementNS(
                        'http://www.w3.org/2000/svg',
                        'circle'
                    );


                    circle.setAttribute(
                        'cx',
                        point.x
                    );

                    circle.setAttribute(
                        'cy',
                        point.y
                    );

                    circle.setAttribute(
                        'r',
                        '4'
                    );

                    circle.setAttribute(
                        'fill',
                        '#ffffff'
                    );

                    circle.setAttribute(
                        'stroke',
                        '#7c3aed'
                    );

                    circle.setAttribute(
                        'stroke-width',
                        '2'
                    );


                    circle.style.cursor = 'pointer';


                    circle.addEventListener(
                        'mouseenter',
                        function (event) {

                            tooltipDate.textContent =
                                data[index].label ||
                                data[index].date ||
                                '—';


                            tooltipRequests.textContent =
                                String(requests[index]);


                            tooltipFailed.textContent =
                                String(failed[index]);


                            tooltip.classList.remove('hidden');


                            const chartRect =
                                svg.parentElement.getBoundingClientRect();


                            let left =
                                event.clientX -
                                chartRect.left +
                                12;


                            let top =
                                event.clientY -
                                chartRect.top -
                                65;


                            const tooltipWidth =
                                tooltip.offsetWidth;


                            if (
                                left + tooltipWidth >
                                chartRect.width
                            ) {
                                left =
                                    left -
                                    tooltipWidth -
                                    24;
                            }


                            if (top < 0) {
                                top = 10;
                            }


                            tooltip.style.left =
                                left + 'px';

                            tooltip.style.top =
                                top + 'px';

                        }
                    );


                    circle.addEventListener(
                        'mouseleave',
                        function () {

                            tooltip.classList.add('hidden');

                        }
                    );


                    requestDots.appendChild(circle);

                });


                /*
                |--------------------------------------------------------------------------
                | FAILED DOTS
                |--------------------------------------------------------------------------
                */

                failedPoints.forEach(function (point, index) {

                    if (failed[index] <= 0) {
                        return;
                    }


                    const circle = document.createElementNS(
                        'http://www.w3.org/2000/svg',
                        'circle'
                    );


                    circle.setAttribute(
                        'cx',
                        point.x
                    );

                    circle.setAttribute(
                        'cy',
                        point.y
                    );

                    circle.setAttribute(
                        'r',
                        '3'
                    );

                    circle.setAttribute(
                        'fill',
                        '#ffffff'
                    );

                    circle.setAttribute(
                        'stroke',
                        '#f87171'
                    );

                    circle.setAttribute(
                        'stroke-width',
                        '2'
                    );


                    failedDots.appendChild(circle);

                });


                /*
                |--------------------------------------------------------------------------
                | LABELS
                |--------------------------------------------------------------------------
                */

                data.forEach(function (item) {

                    const label =
                        document.createElement('span');


                    label.className =
                        'max-w-[80px] truncate text-[9px] text-gray-400';


                    label.textContent =
                        item.label ||
                        item.date ||
                        '—';


                    labels.appendChild(label);

                });

            });
        </script>

    @endif

</x-app-layout>