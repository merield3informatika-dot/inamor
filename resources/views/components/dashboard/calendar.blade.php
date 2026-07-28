@props(['events' => []])

<div class="bg-white rounded-[24px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-[15px] font-bold text-gray-900">Kalender Hari Ini</h3>
        <a href="#" class="text-[12px] text-gray-500 hover:text-blue-600 font-medium transition-colors">Lihat kalender</a>
    </div>
    
    <div class="flex gap-6">
        <!-- Current Date Display -->
        <div class="text-center w-14 shrink-0">
            <div class="text-[36px] font-bold text-blue-600 leading-none mb-1">{{ now()->format('d') }}</div>
            <div class="text-[12px] font-medium text-gray-600">{{ now()->translatedFormat('F Y') }}</div>
            <div class="text-[12px] text-gray-500">{{ now()->translatedFormat('l') }}</div>
        </div>
        
        <!-- Timeline Events List -->
        <div class="flex-1 relative border-l border-gray-100 pl-5 py-1 space-y-6">
            @forelse($events as $event)
                <div class="relative">
                    <div class="absolute -left-[25px] top-1.5 w-2 h-2 rounded-full {{ $event->color ?? 'bg-blue-600' }} ring-4 ring-white"></div>
                    <div class="flex gap-4">
                        <span class="text-[12px] font-semibold text-gray-500 shrink-0 w-10 mt-0.5">{{ $event->start_at->format('H:i') }}</span>
                        <div>
                            <h4 class="text-[13px] font-bold text-gray-900 leading-snug">{{ $event->title }}</h4>
                            <p class="text-[12px] text-gray-500 mt-0.5">{{ $event->location ?? 'Online' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-4 text-center">
                    <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 mb-2">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    <p class="text-[12px] font-medium text-gray-500">Tidak ada jadwal hari ini</p>
                </div>
            @endforelse
        </div>
    </div>
</div>