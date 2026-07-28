@props([
    'events' => [],
    'announcements' => [],
    'stats' => []
])

<!-- Agenda Mendatang -->
<div class="bg-white rounded-[24px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-[15px] font-bold text-gray-900">Agenda Mendatang</h3>
        <a href="{{ route('calendar.index') }}" class="text-[12px] text-gray-500 hover:text-blue-600 font-medium transition-colors">Lihat kalender</a>
    </div>
    
    <div class="flex gap-6">
        <!-- Tanggal hari ini (orientasi waktu, statis, tidak mengikuti event) -->
        <div class="text-center w-14 shrink-0">
            <div class="text-[36px] font-bold text-blue-600 leading-none mb-1">{{ now()->format('d') }}</div>
            <div class="text-[12px] font-medium text-gray-600">{{ now()->translatedFormat('F Y') }}</div>
            <div class="text-[12px] text-gray-500">{{ now()->translatedFormat('l') }}</div>
        </div>
        
        <!-- Timeline -->
        <div class="flex-1 relative border-l border-gray-100 pl-5 py-1 space-y-6">
            @forelse($events as $event)
                <div class="relative">
                    <!-- Timeline Dot -->
                    <div class="absolute -left-[25px] top-1.5 w-2 h-2 rounded-full {{ $event->color ?? 'bg-blue-600' }} ring-4 ring-white"></div>
                    
                    <!-- Event Content (Redesigned for Vertical Stack) -->
                    <div class="flex flex-col">
                        <!-- Label (Besok / Tanggal) -->
                        <p class="text-[11px] font-semibold text-blue-600 uppercase tracking-wide mb-1">{{ $event->date_label }}</p>
                        
                        <!-- Judul -->
                        <h4 class="text-[13px] font-bold text-gray-900 leading-snug mb-2">{{ $event->title }}</h4>
                        
                        <!-- Details Stack -->
                        <div class="flex flex-col gap-1.5">
                            <!-- Waktu -->
                            <div class="flex items-start text-[12px] font-medium text-gray-600">
                                <svg class="w-3.5 h-3.5 mr-1.5 mt-[2px] shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="leading-tight">{{ $event->time_range_label }}</span>
                            </div>
                            
                            <!-- Lokasi -->
                            <div class="flex items-start text-[12px] text-gray-500">
                                <svg class="w-3.5 h-3.5 mr-1.5 mt-[2px] shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                <span class="leading-tight break-words">{{ $event->location ?? 'Online' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-4 opacity-50">
                    <svg class="w-8 h-8 text-gray-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    <p class="text-[12px] font-medium text-gray-500">Belum ada agenda mendatang</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Pengumuman Terbaru -->
<div class="bg-white rounded-[24px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-6 mt-6">
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-[15px] font-bold text-gray-900">Pengumuman Terbaru</h3>
        <a href="#" class="text-[12px] text-gray-500 hover:text-orange-600 font-medium transition-colors">Lihat semua</a>
    </div>
    
    <div class="space-y-4">
        @forelse($announcements as $announcement)
            @if(isset($announcement->is_important) && $announcement->is_important)
                <div class="bg-[#FFFDF5] border border-orange-100 rounded-[16px] p-4 flex gap-4">
                    <div class="w-10 h-10 shrink-0 flex items-center justify-center">
                        <svg class="w-8 h-8 text-orange-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <h4 class="text-[13px] font-bold text-gray-900">{{ $announcement->title }}</h4>
                            <span class="bg-orange-100 text-orange-700 text-[10px] font-bold px-2 py-0.5 rounded-full">Penting</span>
                        </div>
                        <p class="text-[12px] text-gray-600 leading-relaxed">{{ $announcement->content }}</p>
                    </div>
                </div>
            @else
                <div class="flex gap-4 p-2">
                    <div class="w-8 h-8 rounded-full bg-blue-50 shrink-0 flex items-center justify-center">
                        <svg class="w-4 h-4 {{ $announcement->icon_color ?? 'text-blue-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="text-[13px] font-bold text-gray-900 pr-2">{{ $announcement->title }}</h4>
                            <span class="text-[11px] text-gray-400 shrink-0">{{ $announcement->created_at?->diffForHumans() ?? '' }}</span>
                        </div>
                        <p class="text-[12px] text-gray-500 leading-relaxed">{{ $announcement->content }}</p>
                    </div>
                </div>
            @endif
        @empty
            <div class="flex flex-col items-center justify-center py-6 opacity-50">
                <svg class="w-8 h-8 text-gray-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p class="text-[12px] font-medium text-gray-500">Belum ada pengumuman</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Statistik Layanan -->
<div class="bg-white rounded-[24px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-6 mt-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-[15px] font-bold text-gray-900">Statistik Layanan</h3>
        <a href="#" class="text-[12px] text-gray-500 hover:text-blue-600 font-medium transition-colors">Lihat laporan</a>
    </div>
    
    @if(empty($stats))
        <div class="flex flex-col items-center justify-center py-6 opacity-50">
            <svg class="w-8 h-8 text-gray-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
            <p class="text-[12px] font-medium text-gray-500">Statistik belum tersedia</p>
        </div>
    @else
        <div class="grid grid-cols-4 gap-3">
            @foreach($stats as $stat)
                <div>
                    <p class="text-[10px] font-medium text-gray-500 mb-1 leading-tight">{{ $stat->label }}</p>
                    <h4 class="text-[20px] font-bold text-gray-900 mb-1 leading-none">{{ $stat->value }}</h4>
                    <div class="flex items-center gap-1">
                        <span class="text-[11px] font-bold flex items-center {{ $stat->trend_color ?? 'text-gray-500' }}">
                            <svg class="w-3 h-3 mr-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            {{ $stat->percentage }}
                        </span>
                    </div>
                    <p class="text-[9px] text-gray-400 mt-0.5">dari bulan lalu</p>
                </div>
            @endforeach
        </div>
    @endif
</div>