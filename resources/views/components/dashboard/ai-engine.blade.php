@props([
    'analytics' => [],
])

@php
    /*
    |--------------------------------------------------------------------------
    | Analytics
    |--------------------------------------------------------------------------
    */

    $overview = $analytics['overview'] ?? [];

    $todayRequests = (int) (
        $overview['requests_today'] ?? 0
    );

    $latency = (int) (
        $overview['today_avg_response_ms']
        ?? $overview['avg_response_ms']
        ?? 0
    );

    $successRate = (int) (
        $overview['success_rate'] ?? 0
    );

    /*
    |--------------------------------------------------------------------------
    | Knowledge Memory
    |--------------------------------------------------------------------------
    */

    $knowledgeMemory = (int) (
        $analytics['knowledge_memory']['coverage_pct']
        ?? 0
    );

    $knowledgeUsedToday = (int) (
        $analytics['knowledge_memory']['used_today']
        ?? 0
    );

    $knowledgeSaved = (int) (
        $analytics['knowledge_memory']['saved_count']
        ?? 0
    );

    /*
    |--------------------------------------------------------------------------
    | Health
    |--------------------------------------------------------------------------
    */

    $health = $overview['health'] ?? [
        'status' => 'no_data',
        'label' => 'No Data',
        'color' => 'gray',
    ];

    $healthColor = $health['color'] ?? 'gray';
    $healthLabel = $health['label'] ?? 'No Data';

    $healthBadge = match ($healthColor) {
        'green' => 'border-emerald-100 bg-emerald-50 text-emerald-600',
        'yellow' => 'border-amber-100 bg-amber-50 text-amber-600',
        'orange' => 'border-orange-100 bg-orange-50 text-orange-600',
        'red' => 'border-red-100 bg-red-50 text-red-600',
        default => 'border-gray-100 bg-gray-50 text-gray-500',
    };

    $healthDot = match ($healthColor) {
        'green' => 'bg-emerald-500',
        'yellow' => 'bg-amber-500',
        'orange' => 'bg-orange-500',
        'red' => 'bg-red-500',
        default => 'bg-gray-400',
    };

    $successRate = min(100, max(0, $successRate));
    $knowledgeMemory = min(100, max(0, $knowledgeMemory));
@endphp


{{-- ============================================================
     AI ENGINE CARD
============================================================ --}}

<div class="rounded-[20px] border border-gray-100 bg-white p-5 shadow-[0_8px_30px_rgba(15,23,42,0.04)]">


    {{-- ========================================================
         HEADER
    ========================================================= --}}

    <div class="flex items-start justify-between">

        <div>
            <h3 class="text-[15px] font-bold tracking-tight text-gray-900">
                AI Engine
            </h3>

            <p class="mt-1 text-[11px] text-gray-500">
                Knowledge Analytics
            </p>
        </div>


        <a
            href="{{ route('ai.analytics') }}"
            class="group inline-flex items-center gap-1 text-[11px] font-semibold text-blue-600 transition-colors hover:text-blue-700"
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


    {{-- ========================================================
         AI STATUS
    ========================================================= --}}

    <div class="mt-5 flex items-center justify-between">

        <div class="flex min-w-0 items-center gap-3">

            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[11px] bg-violet-50"
            >
                <span class="text-[16px]">
                    🤖
                </span>
            </div>


            <div class="min-w-0">

                <p class="truncate text-[12.5px] font-semibold text-gray-900">
                    AI Assistant
                </p>

                <p class="mt-0.5 text-[10px] text-gray-500">
                    {{ number_format($todayRequests) }} request hari ini
                </p>

            </div>

        </div>


        {{-- Health --}}
        <span
            class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-2 py-1 {{ $healthBadge }}"
        >

            <span class="h-1.5 w-1.5 rounded-full {{ $healthDot }}"></span>

            <span class="text-[8.5px] font-semibold">
                {{ $healthLabel }}
            </span>

        </span>

    </div>


    {{-- ========================================================
         CORE METRICS
    ========================================================= --}}

    <div class="mt-5 grid grid-cols-2 divide-x divide-gray-100 border-y border-gray-100 py-4">

        {{-- Requests --}}
        <div class="pr-4">

            <p class="text-[9.5px] font-medium text-gray-400">
                Requests
            </p>

            <div class="mt-1 flex items-baseline gap-1">

                <span class="text-[19px] font-bold tracking-tight text-gray-900">
                    {{ number_format($todayRequests) }}
                </span>

            </div>

            <p class="mt-0.5 text-[9px] text-gray-400">
                hari ini
            </p>

        </div>


        {{-- Response --}}
        <div class="pl-4">

            <p class="text-[9.5px] font-medium text-gray-400">
                Response
            </p>

            <div class="mt-1 flex items-baseline gap-1">

                <span class="text-[19px] font-bold tracking-tight text-gray-900">
                    {{ number_format($latency) }}
                </span>

                <span class="text-[9px] font-medium text-gray-400">
                    ms
                </span>

            </div>

            <p class="mt-0.5 text-[9px] text-gray-400">
                rata-rata
            </p>

        </div>

    </div>


    {{-- ========================================================
         PERFORMANCE
    ========================================================= --}}

    <div class="mt-4 grid grid-cols-2 gap-4">


        {{-- Success Rate --}}
        <div>

            <div class="flex items-center justify-between">

                <p class="text-[10px] font-semibold text-gray-700">
                    Success Rate
                </p>

                <span class="text-[12px] font-bold text-gray-900">
                    {{ $successRate }}%
                </span>

            </div>

            <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-gray-100">

                <div
                    class="h-full rounded-full bg-emerald-500 transition-all duration-500"
                    style="width: {{ $successRate }}%"
                ></div>

            </div>

            <p class="mt-1 text-[8.5px] text-gray-400">
                Request berhasil
            </p>

        </div>


        {{-- Knowledge Memory --}}
        <div>

            <div class="flex items-center justify-between">

                <p class="text-[10px] font-semibold text-gray-700">
                    Knowledge
                </p>

                <span class="text-[12px] font-bold text-violet-600">
                    {{ $knowledgeMemory }}%
                </span>

            </div>

            <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-gray-100">

                <div
                    class="h-full rounded-full bg-violet-500 transition-all duration-500"
                    style="width: {{ $knowledgeMemory }}%"
                ></div>

            </div>

            <p class="mt-1 text-[8.5px] text-gray-400">
                {{ number_format($knowledgeUsedToday) }} used
            </p>

        </div>

    </div>


    {{-- ========================================================
         KNOWLEDGE FOOTNOTE
    ========================================================= --}}

    @if($knowledgeSaved > 0)

        <div class="mt-4 flex items-center justify-between rounded-[10px] bg-gray-50 px-3 py-2">

            <div class="flex items-center gap-2">

                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-50">

                    <svg
                        class="h-3 w-3 text-emerald-500"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                </span>

                <span class="text-[9px] text-gray-500">
                    Knowledge berhasil digunakan
                </span>

            </div>

            <span class="text-[10px] font-bold text-gray-700">
                {{ number_format($knowledgeSaved) }}
            </span>

        </div>

    @endif

</div>