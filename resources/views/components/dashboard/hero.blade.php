@props([
    'workspace',
    'stats',
])

<div class="bg-white rounded-[24px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] overflow-hidden">
    <div class="flex flex-col lg:flex-row">

        <!-- ================================================= -->
        <!-- LEFT: Context + Status Spotlight (Primary Focus)  -->
        <!-- ================================================= -->
        <div class="w-full lg:w-[62%] p-7 lg:p-8 lg:border-r lg:border-gray-100">

            <!-- Workspace + Date (kecil, hanya context anchor) -->
            <div class="flex items-center gap-2 mb-5">
                <span class="text-[13px] font-semibold text-gray-900">{{ $workspace->name }}</span>
                <span class="text-gray-300">•</span>
                <span class="text-[13px] text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>

            @php
                $pendingFeedback = $stats['pending_feedback'] ?? 0;
            @endphp

            <!-- Status Spotlight (signature element) -->
            @if($pendingFeedback > 0)
                <div class="rounded-2xl border border-amber-200/70 bg-amber-50/60 p-5 md:p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-amber-500 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-[18px] md:text-[19px] font-bold text-gray-900 leading-snug">
                                AI membutuhkan perhatian Anda
                            </h3>
                            <p class="text-[13.5px] text-gray-600 mt-1 leading-relaxed">
                                <span class="font-semibold text-gray-900">{{ $stats['pending_feedback'] }} feedback AI</span> belum ditinjau. Meninjau feedback membantu AI memberi jawaban yang lebih akurat.
                            </p>
                            <a href="{{ route('knowledge.feedback.index') }}"
                               class="inline-flex items-center gap-1.5 mt-4 h-10 px-5 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-[13px] font-semibold transition-colors duration-200">
                                Tinjau Sekarang
                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-2xl border border-emerald-200/70 bg-emerald-50/50 p-5 md:p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-emerald-500 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-[18px] md:text-[19px] font-bold text-gray-900 leading-snug">
                                Workspace berjalan normal
                            </h3>
                            <p class="text-[13.5px] text-gray-600 mt-1 leading-relaxed">
                                Tidak ada feedback AI yang perlu ditinjau saat ini. Semua data dan dokumen dalam kondisi baik.
                            </p>
                            <a href="{{ route('chat') }}"
                               class="inline-flex items-center gap-1.5 mt-4 h-10 px-5 bg-white border border-gray-200 hover:border-gray-300 text-gray-800 rounded-lg text-[13px] font-semibold transition-colors duration-200">
                                Buka AI Assistant
                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Universal Search (secondary, tetap ada sebagai akses cepat) -->
            <form action="{{ route('chat') }}" method="GET" class="relative mt-5">
                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                    <svg class="w-[16px] h-[16px] text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                </div>
                <input
                    type="text"
                    name="q"
                    class="w-full h-[46px] pl-11 pr-4 bg-gray-50/70 border border-gray-200/80 rounded-xl text-[13.5px] text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-gray-900/5 focus:border-gray-300 transition-all duration-200"
                    placeholder="Cari dokumen, SOP, layanan, atau tanya AI..."
                >
            </form>
        </div>

        <!-- ================================================= -->
        <!-- RIGHT: Summary Rail (bukan grid statistik)         -->
        <!-- ================================================= -->
        <div class="w-full lg:w-[38%] p-7 lg:p-8 bg-gray-50/40">
            <p class="text-[11px] font-bold text-gray-400 tracking-wider uppercase mb-4">Ringkasan Workspace</p>

            <div class="flex flex-col">

                <!-- Dokumen -->
                <div class="flex items-center justify-between py-3.5 border-b border-gray-200/70">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-semibold text-gray-900">Dokumen</p>
                            <p class="text-[11.5px] text-gray-500">Ditambahkan hari ini</p>
                        </div>
                    </div>
                    <span class="text-[16px] font-bold text-gray-900">{{ $stats['document_count_today'] }}</span>
                </div>

                <!-- Knowledge -->
                <div class="flex items-center justify-between py-3.5 border-b border-gray-200/70">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-semibold text-gray-900">Knowledge</p>
                            <p class="text-[11.5px] text-gray-500">Total entri manual</p>
                        </div>
                    </div>
                    <span class="text-[16px] font-bold text-gray-900">{{ $stats['knowledge_count'] ?? 0 }}</span>
                </div>

                <!-- Feedback -->
                <div class="flex items-center justify-between py-3.5 border-b border-gray-200/70">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $pendingFeedback > 0 ? 'bg-amber-50' : 'bg-gray-100' }} flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 {{ $pendingFeedback > 0 ? 'text-amber-500' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-semibold text-gray-900">Feedback AI</p>
                            <p class="text-[11.5px] text-gray-500">Menunggu tinjauan</p>
                        </div>
                    </div>
                    <span class="text-[16px] font-bold {{ $pendingFeedback > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ $pendingFeedback }}</span>
                </div>

                <!-- Pengumuman -->
                <div class="flex items-center justify-between py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-rose-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-semibold text-gray-900">Pengumuman</p>
                            <p class="text-[11.5px] text-gray-500">Belum dibaca</p>
                        </div>
                    </div>
                    <span class="text-[16px] font-bold text-gray-900">{{ $stats['unread_announcements'] ?? 0 }}</span>
                </div>

            </div>
        </div>
    </div>
</div>