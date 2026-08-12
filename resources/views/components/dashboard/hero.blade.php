@props([
    'workspace',
    'stats',
])

@php
    $pendingFeedback = (int) ($stats['pending_feedback'] ?? 0);

    $documentToday = (int) ($stats['document_count_today'] ?? 0);

    $knowledgeCount = (int) (
        $stats['knowledge_count']
        ?? $stats['manual_knowledge_count']
        ?? 0
    );

    $unreadAnnouncements = (int) (
        $stats['unread_announcements']
        ?? $stats['announcement_count']
        ?? 0
    );
@endphp

<div class="relative overflow-hidden rounded-[24px] border border-gray-100 bg-white shadow-[0_4px_24px_rgba(0,0,0,0.025)]">

    {{-- Subtle decorative glow --}}
    <div class="pointer-events-none absolute -right-20 -top-24 h-64 w-64 rounded-full bg-blue-50/50 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-24 left-1/3 h-52 w-52 rounded-full bg-violet-50/40 blur-3xl"></div>

    <div class="relative grid grid-cols-1 lg:grid-cols-[1.35fr_.65fr]">

        {{-- ================================================= --}}
        {{-- LEFT : MAIN HERO --}}
        {{-- ================================================= --}}
        <div class="p-6 sm:p-7 lg:p-8">

            {{-- Top context --}}
            <div class="flex items-center justify-between gap-4">

                <div class="flex min-w-0 items-center gap-2">

                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-[10px] bg-gray-50 border border-gray-100">
                        <svg
                            class="h-4 w-4 text-gray-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3.75 7.5h16.5M6 3.75h12a2.25 2.25 0 012.25 2.25v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75z"
                            />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="truncate text-[12px] font-semibold text-gray-800">
                            {{ $workspace->name }}
                        </p>

                        <p class="text-[10.5px] text-gray-400">
                            {{ now()->translatedFormat('l, d F Y') }}
                        </p>
                    </div>

                </div>

                <span class="hidden shrink-0 rounded-full bg-emerald-50 px-2.5 py-1 text-[9px] font-semibold text-emerald-600 sm:inline-flex">
                    Workspace aktif
                </span>

            </div>


            {{-- Main content --}}
            <div class="mt-7">

                @if($pendingFeedback > 0)

                    {{-- Attention state --}}
                    <div class="relative overflow-hidden rounded-[20px] border border-amber-200/70 bg-gradient-to-br from-amber-50/90 via-white to-white p-5 sm:p-6">

                        <div class="absolute right-0 top-0 h-28 w-28 rounded-full bg-amber-100/50 blur-2xl"></div>

                        <div class="relative flex items-start gap-4">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[13px] bg-amber-500 shadow-sm shadow-amber-200">
                                <svg
                                    class="h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 9v3.75m0 3.75h.008M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">

                                <div class="flex flex-wrap items-center gap-2">

                                    <h3 class="text-[16px] font-bold tracking-tight text-gray-900 sm:text-[17px]">
                                        AI membutuhkan perhatian
                                    </h3>

                                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[9px] font-bold text-amber-700">
                                        {{ $pendingFeedback }} pending
                                    </span>

                                </div>

                                <p class="mt-1.5 max-w-[540px] text-[12.5px] leading-relaxed text-gray-600">
                                    Ada
                                    <span class="font-semibold text-gray-900">
                                        {{ $pendingFeedback }} feedback AI
                                    </span>
                                    yang belum ditinjau. Review feedback untuk membantu meningkatkan kualitas jawaban AI.
                                </p>

                                <a
                                    href="{{ route('knowledge.feedback.index') }}"
                                    class="group mt-4 inline-flex h-9 items-center gap-2 rounded-[10px] bg-gray-900 px-4 text-[11.5px] font-semibold text-white transition-all duration-200 hover:bg-gray-800 hover:shadow-md"
                                >
                                    Tinjau feedback

                                    <svg
                                        class="h-3.5 w-3.5 transition-transform duration-200 group-hover:translate-x-0.5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2.5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M5 12h14m-6-6l6 6-6 6"
                                        />
                                    </svg>
                                </a>

                            </div>

                        </div>

                    </div>

                @else

                    {{-- Healthy state --}}
                    <div class="relative overflow-hidden rounded-[20px] border border-emerald-200/70 bg-gradient-to-br from-emerald-50/80 via-white to-white p-5 sm:p-6">

                        <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-emerald-100/50 blur-2xl"></div>

                        <div class="relative flex items-start gap-4">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[13px] bg-emerald-500 shadow-sm shadow-emerald-200">
                                <svg
                                    class="h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2.5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4.5 12.75l6 6 9-13.5"
                                    />
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">

                                <div class="flex flex-wrap items-center gap-2">

                                    <h3 class="text-[16px] font-bold tracking-tight text-gray-900 sm:text-[17px]">
                                        Workspace berjalan normal
                                    </h3>

                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[9px] font-bold text-emerald-700">
                                        Semua aman
                                    </span>

                                </div>

                                <p class="mt-1.5 max-w-[540px] text-[12.5px] leading-relaxed text-gray-600">
                                    Tidak ada feedback AI yang perlu ditinjau saat ini. Knowledge dan dokumen workspace siap digunakan.
                                </p>

                                <a
                                    href="{{ route('chat') }}"
                                    class="group mt-4 inline-flex h-9 items-center gap-2 rounded-[10px] border border-gray-200 bg-white px-4 text-[11.5px] font-semibold text-gray-800 transition-all duration-200 hover:border-gray-300 hover:shadow-sm"
                                >
                                    Buka AI Assistant

                                    <svg
                                        class="h-3.5 w-3.5 transition-transform duration-200 group-hover:translate-x-0.5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2.5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M5 12h14m-6-6l6 6-6 6"
                                        />
                                    </svg>
                                </a>

                            </div>

                        </div>

                    </div>

                @endif

            </div>


         

        </div>


        {{-- ================================================= --}}
        {{-- RIGHT : WORKSPACE SNAPSHOT --}}
        {{-- ================================================= --}}
        <div class="border-t border-gray-100 bg-gray-50/35 p-6 lg:border-l lg:border-t-0 lg:p-7">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-gray-400">
                        Workspace
                    </p>

                    <p class="mt-1 text-[13px] font-semibold text-gray-900">
                        Ringkasan hari ini
                    </p>
                </div>

                <div class="flex h-8 w-8 items-center justify-center rounded-[10px] bg-white border border-gray-100 shadow-sm">

                    <svg
                        class="h-4 w-4 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 13.5l4.5-4.5 4 4 5.5-7 4 4"
                        />
                    </svg>

                </div>

            </div>


            {{-- Main mini stat --}}
            <div class="mt-6">

                <p class="text-[11px] font-medium text-gray-400">
                    Dokumen ditambahkan
                </p>

                <div class="mt-1 flex items-end gap-2">

                    <span class="text-[32px] font-bold leading-none tracking-tight text-gray-900">
                        {{ number_format($documentToday) }}
                    </span>

                    <span class="pb-0.5 text-[10px] text-gray-400">
                        hari ini
                    </span>

                </div>

            </div>


            {{-- Stats --}}
            <div class="mt-6 divide-y divide-gray-200/70 border-y border-gray-200/70">

                {{-- Knowledge --}}
                <div class="flex items-center justify-between py-3.5">

                    <div class="flex items-center gap-2.5">

                        <div class="flex h-8 w-8 items-center justify-center rounded-[9px] bg-indigo-50">

                            <svg
                                class="h-4 w-4 text-indigo-500"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332 1.253-4.5 1.253"
                                />
                            </svg>

                        </div>

                        <div>
                            <p class="text-[11.5px] font-semibold text-gray-800">
                                Knowledge
                            </p>

                            <p class="text-[9.5px] text-gray-400">
                                Entri manual
                            </p>
                        </div>

                    </div>

                    <span class="text-[15px] font-bold text-gray-900">
                        {{ number_format($knowledgeCount) }}
                    </span>

                </div>


                {{-- Feedback --}}
                <div class="flex items-center justify-between py-3.5">

                    <div class="flex items-center gap-2.5">

                        <div class="{{ $pendingFeedback > 0 ? 'bg-amber-50' : 'bg-gray-50' }} flex h-8 w-8 items-center justify-center rounded-[9px]">

                            <svg
                                class="{{ $pendingFeedback > 0 ? 'text-amber-500' : 'text-gray-400' }} h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                />
                            </svg>

                        </div>

                        <div>
                            <p class="text-[11.5px] font-semibold text-gray-800">
                                Feedback AI
                            </p>

                            <p class="text-[9.5px] text-gray-400">
                                Menunggu tinjauan
                            </p>
                        </div>

                    </div>

                    <span class="{{ $pendingFeedback > 0 ? 'text-amber-600' : 'text-gray-900' }} text-[15px] font-bold">
                        {{ number_format($pendingFeedback) }}
                    </span>

                </div>


                {{-- Announcement --}}
                <div class="flex items-center justify-between py-3.5">

                    <div class="flex items-center gap-2.5">

                        <div class="flex h-8 w-8 items-center justify-center rounded-[9px] bg-rose-50">

                            <svg
                                class="h-4 w-4 text-rose-500"
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

                        <div>
                            <p class="text-[11.5px] font-semibold text-gray-800">
                                Pengumuman
                            </p>

                            <p class="text-[9.5px] text-gray-400">
                                Belum dibaca
                            </p>
                        </div>

                    </div>

                    <span class="text-[15px] font-bold text-gray-900">
                        {{ number_format($unreadAnnouncements) }}
                    </span>

                </div>

            </div>


            {{-- Footer hint --}}
            <div class="mt-5 flex items-center gap-2 text-[9.5px] text-gray-400">

                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>

                <span>
                    Data workspace diperbarui secara langsung
                </span>

            </div>

        </div>

    </div>

</div>