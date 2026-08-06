@props([
    'analytics' => [],
])

@php
    $health = $analytics['health'] ?? [
        'status' => 'healthy',
        'label' => 'Healthy',
        'color' => 'green',
    ];

    $todayRequests = $analytics['today_requests'] ?? 0;

    $knowledgeMemory = $analytics['knowledge_memory']['percentage'] ?? 0;
    $gemini = $analytics['gemini']['percentage'] ?? 0;
    $latency = $analytics['latency']['average'] ?? 0;

    $hasData = $todayRequests > 0;

    $statusColors = [
        'green' => [
            'badge' => 'bg-green-100 text-green-700',
            'dot' => 'bg-green-500',
        ],
        'yellow' => [
            'badge' => 'bg-yellow-100 text-yellow-700',
            'dot' => 'bg-yellow-500',
        ],
        'orange' => [
            'badge' => 'bg-orange-100 text-orange-700',
            'dot' => 'bg-orange-500',
        ],
        'red' => [
            'badge' => 'bg-red-100 text-red-700',
            'dot' => 'bg-red-500',
        ],
    ];

    $style = $statusColors[$health['color']] ?? $statusColors['green'];
@endphp

<div class="bg-white rounded-[24px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-6 flex flex-col h-full">

    <div class="flex items-center justify-between mb-6 shrink-0">

        <div>

            <h3 class="text-[16px] font-bold text-gray-900 tracking-tight">
                AI Engine
            </h3>

            <p class="text-[12px] text-gray-500 mt-1">
                Knowledge Analytics
            </p>

        </div>

        <a
            href="{{ route('ai.analytics') }}"
            class="group flex items-center gap-1 text-[13px] font-semibold text-blue-600 hover:text-blue-700 transition-colors">

            Lihat semua

            <svg
                class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2.5">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 5l7 7-7 7" />

            </svg>

        </a>

    </div>

    @if($hasData)

        <div class="flex flex-col flex-1">

            <div class="flex items-center justify-between mb-6">

                <div class="flex items-center gap-3">

                    <div class="w-11 h-11 rounded-[14px] bg-gradient-to-br from-violet-50 to-indigo-100 border border-violet-100 flex items-center justify-center shadow-sm">

                        🤖

                    </div>

                    <div>

                        <div class="text-[14px] font-semibold text-gray-900">
                            AI Engine
                        </div>

                        <div class="text-[12px] text-gray-500">
                            {{ $todayRequests }} request hari ini
                        </div>

                    </div>

                </div>

                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full {{ $style['badge'] }}">

                    <span class="w-2 h-2 rounded-full {{ $style['dot'] }}"></span>

                    <span class="text-[11px] font-semibold">

                        {{ $health['label'] }}

                    </span>

                </div>

            </div>

            <div class="space-y-4">

                <div class="flex items-center justify-between">

                    <span class="text-[13px] text-gray-600">
                        Knowledge Memory
                    </span>

                    <span class="font-bold text-[14px] text-gray-900">
                        {{ $knowledgeMemory }}%
                    </span>

                </div>

                <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">

                    <div
                        class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-green-500"
                        style="width: {{ $knowledgeMemory }}%">

                    </div>

                </div>

                <div class="flex items-center justify-between">

                    <span class="text-[13px] text-gray-600">
                        Gemini
                    </span>

                    <span class="font-bold text-[14px] text-gray-900">
                        {{ $gemini }}%
                    </span>

                </div>

                <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">

                    <div
                        class="h-full rounded-full bg-gradient-to-r from-violet-500 to-indigo-600"
                        style="width: {{ $gemini }}%">

                    </div>

                </div>

            </div>

            <div class="mt-6 pt-5 border-t border-gray-100 flex items-center justify-between">

                <div>

                    <div class="text-[12px] text-gray-500">
                        Average Response
                    </div>

                    <div class="mt-1 text-[20px] font-bold text-gray-900">

                        {{ $latency }}

                        <span class="text-[13px] font-medium text-gray-500">
                            ms
                        </span>

                    </div>

                </div>

                <div class="text-right">

                    <div class="text-[12px] text-gray-500">
                        AI Saved
                    </div>

                    <div class="mt-1 text-[20px] font-bold text-emerald-600">

                        {{ $knowledgeMemory }}%

                    </div>

                </div>

            </div>

        </div>

    @else

        <div class="flex flex-col items-center justify-center flex-1 py-10 px-4 text-center rounded-2xl border-2 border-dashed border-gray-100 bg-gray-50/50">

            <div class="w-16 h-16 rounded-[18px] bg-white border border-gray-100 shadow-sm flex items-center justify-center text-3xl mb-5">

                🤖

            </div>

            <h4 class="text-[14px] font-semibold text-gray-900 mb-1">

                Belum ada aktivitas AI

            </h4>

            <p class="text-[12.5px] text-gray-500 leading-relaxed max-w-[220px]">

                Statistik AI akan muncul setelah pengguna mulai menggunakan AI Assistant.

            </p>

        </div>

    @endif

</div>