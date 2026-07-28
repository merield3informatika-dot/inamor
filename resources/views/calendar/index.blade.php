<x-app-layout>

    <!-- Header -->
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <div>
            <h2 class="text-[22px] md:text-[24px] font-bold text-gray-900 tracking-tight">Kalender Kegiatan</h2>
            <p class="text-[13px] text-gray-500 mt-0.5">Agenda dan kegiatan workspace Anda.</p>
        </div>
        <a href="{{ route('calendar.create') }}"
           class="inline-flex items-center gap-1.5 h-10 px-4 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-[13px] font-semibold transition-colors duration-200">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Kegiatan
        </a>
    </div>

    <!-- Flash message -->
    @if (session('status'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
            <p class="text-[13px] font-medium text-emerald-700">{{ session('status') }}</p>
        </div>
    @endif

    <!-- Event List -->
    <div class="bg-white rounded-[20px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] overflow-hidden">

        @if ($events->isEmpty())
            <div class="flex flex-col items-center justify-center text-center py-16 px-6">
                <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-[15px] font-semibold text-gray-900">Belum ada kegiatan</h3>
                <p class="text-[13px] text-gray-500 mt-1 max-w-sm">
                    Mulai jadwalkan agenda pertama untuk workspace ini.
                </p>
                <a href="{{ route('calendar.create') }}"
                   class="inline-flex items-center gap-1.5 mt-5 h-9 px-4 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-[12.5px] font-semibold transition-colors duration-200">
                    Tambah Kegiatan
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach ($events as $event)
                    <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50/60 transition-colors duration-200">

                        <!-- Color indicator -->
                        <div class="w-2 h-10 rounded-full shrink-0" style="background-color: {{ $event->color ?? '#6B7280' }}"></div>

                        <!-- Date block -->
                        <div class="w-14 shrink-0 text-center">
                            <p class="text-[11px] font-semibold text-gray-400 uppercase">
                                {{ $event->start_at->translatedFormat('M') }}
                            </p>
                            <p class="text-[20px] font-bold text-gray-900 leading-none">
                                {{ $event->start_at->format('d') }}
                            </p>
                        </div>

                        <!-- Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-[14px] font-semibold text-gray-900 truncate">{{ $event->title }}</p>
                                <span class="text-[10.5px] font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 shrink-0">
                                    {{ $event->category }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 mt-1 text-[12px] text-gray-500 flex-wrap">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                                        <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    {{ $event->start_at->format('H:i') }}
                                    @if ($event->end_at)
                                        &ndash; {{ $event->end_at->format('H:i') }}
                                    @endif
                                </span>
                                @if ($event->location)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $event->location }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <a href="{{ route('calendar.edit', $event) }}"
                           class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors duration-200 shrink-0">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</x-app-layout>