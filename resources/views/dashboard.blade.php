<x-app-layout>
    
@php
    /*
    |--------------------------------------------------------------------------
    | Daily Dashboard Quote
    |--------------------------------------------------------------------------
    | 0 = Sunday
    | 1 = Monday
    | ...
    | 6 = Saturday
    */

    $dailyQuotes = [
        0 => [
            'category' => 'MINGGU · RESET',
            'quote' => 'Istirahat bukan berhenti. Besok kita lanjut lagi.',
        ],

        1 => [
            'category' => 'SENIN · START',
            'quote' => 'Tidak harus langsung jauh. Mulai saja dulu.',
        ],

        2 => [
            'category' => 'SELASA · BUILD',
            'quote' => 'Hal besar dibangun dari hal kecil yang terus dikerjakan.',
        ],

        3 => [
            'category' => 'RABU · FOCUS',
            'quote' => 'Tetap di jalur. Progress tidak selalu terlihat setiap hari.',
        ],

        4 => [
            'category' => 'KAMIS · PROGRESS',
            'quote' => 'Sedikit lebih baik dari kemarin sudah cukup.',
        ],

        5 => [
            'category' => 'JUMAT · FINISH',
            'quote' => 'Selesaikan yang penting. Sisanya bisa menunggu.',
        ],

        6 => [
            'category' => 'SABTU · EXPLORE',
            'quote' => 'Coba sesuatu yang baru. Tidak semua hal harus sempurna.',
        ],
    ];

    $todayQuote = $dailyQuotes[now()->dayOfWeek];

    /*
    |--------------------------------------------------------------------------
    | Display Name
    |--------------------------------------------------------------------------
    | Gunakan display_name jika nanti tersedia.
    | Fallback ke nama depan dari nama akun.
    */

    $accountName = auth()->user()->name ?? 'Pengguna';

    $userName = auth()->user()->display_name
        ?? explode(' ', trim($accountName))[0];

    $userName = trim($userName) !== ''
        ? $userName
        : 'Pengguna';
@endphp


{{-- ========================================================= --}}
{{-- WELCOME --}}
{{-- ========================================================= --}}

<div class="mb-7">

    <div class="flex items-center gap-2">

        <h2 class="text-[28px] font-bold tracking-tight text-gray-900 md:text-[32px]">
            Selamat datang,
            <span class="text-blue-600">
                {{ $userName }}
            </span>
        </h2>

        {{-- Subtle signature --}}
        <span
            class="mt-2 h-2 w-2 shrink-0 rounded-full bg-blue-500"
        ></span>

    </div>


    <div class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1">

        {{-- Day category --}}
        <span class="text-[10px] font-bold tracking-[0.12em] text-blue-600">
            {{ $todayQuote['category'] }}
        </span>

        <span class="hidden h-1 w-1 rounded-full bg-gray-300 sm:block"></span>

        {{-- Daily quote --}}
        <p class="text-[14px] leading-relaxed text-gray-500 md:text-[15px]">
            {{ $todayQuote['quote'] }}
        </p>

    </div>

</div>


{{-- ========================================================= --}}
{{-- DASHBOARD LAYOUT --}}
{{-- ========================================================= --}}

<div class="grid grid-cols-1 gap-6 xl:grid-cols-3">


    {{-- ===================================================== --}}
    {{-- MAIN COLUMN --}}
    {{-- ===================================================== --}}

    <div class="flex flex-col gap-6 xl:col-span-2">


        {{-- ================================================= --}}
        {{-- HERO --}}
        {{-- ================================================= --}}

        <x-dashboard.hero
            :workspace="$workspace"
            :stats="$stats"
        />


        {{-- ================================================= --}}
        {{-- QUICK ACTIONS --}}
        {{-- ================================================= --}}

        <x-dashboard.quick-actions />


        {{-- ================================================= --}}
        {{-- RECENT DOCUMENTS + ACTIVITIES --}}
        {{-- ================================================= --}}

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            <x-dashboard.recent-documents
                :documents="$recentDocuments ?? []"
            />

            <x-dashboard.recent-activities
                :activities="$recentActivities ?? []"
            />

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- RIGHT SIDEBAR --}}
    {{-- ===================================================== --}}

    <div class="flex flex-col gap-6 xl:col-span-1">


        {{-- ================================================= --}}
        {{-- RIGHT SIDEBAR --}}
        {{-- ================================================= --}}

        <x-right-sidebar
            :events="$upcomingEvents ?? []"
            :announcements="$announcements ?? []"
            :stats="$serviceStats ?? []"
            :analytics="$analytics ?? []"
        />


        {{-- ================================================= --}}
        {{-- AI ENGINE --}}
        {{-- ================================================= --}}

        <x-dashboard.ai-engine
            :analytics="$analytics ?? []"
        />

    </div>

</div>


{{-- ========================================================= --}}
{{-- FOOTER --}}
{{-- ========================================================= --}}

<div class="mt-8 flex flex-col gap-4 border-t border-gray-200 pt-6 sm:flex-row sm:items-center sm:justify-between">


    {{-- ===================================================== --}}
    {{-- BRAND --}}
    {{-- ===================================================== --}}

    <div class="flex items-center gap-2">

        <span class="text-[12px] font-semibold tracking-tight text-gray-700">
            INAMOR
        </span>

        <span class="h-1 w-1 rounded-full bg-gray-300"></span>

        <p class="text-[12px] text-gray-400">
            Pemerintahan Berbasis AI
        </p>

    </div>


    {{-- ===================================================== --}}
    {{-- FOOTER NAVIGATION --}}
    {{-- ===================================================== --}}

    <div class="flex items-center gap-5 text-[12px] font-medium text-gray-400">

        <a
            href="#"
            class="transition-colors duration-200 hover:text-gray-700"
        >
            Bantuan
        </a>

        <span class="h-1 w-1 rounded-full bg-gray-300"></span>

        <a
            href="#"
            class="transition-colors duration-200 hover:text-gray-700"
        >
            Kebijakan Privasi
        </a>

        <span class="h-1 w-1 rounded-full bg-gray-300"></span>

        <a
            href="#"
            class="transition-colors duration-200 hover:text-gray-700"
        >
            Syarat & Ketentuan
        </a>

    </div>


    {{-- ===================================================== --}}
    {{-- COPYRIGHT --}}
    {{-- ===================================================== --}}

    <p class="text-[11px] text-gray-400">
        © {{ now()->year }} Inamor
    </p>

</div>

</x-app-layout>