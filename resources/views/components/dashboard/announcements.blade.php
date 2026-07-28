@props(['announcements' => []])

<div class="bg-white rounded-[24px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-6">
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-[15px] font-bold text-gray-900">Pengumuman Terbaru</h3>
        <a href="#" class="text-[12px] text-gray-500 hover:text-orange-600 font-medium transition-colors">Lihat semua</a>
    </div>
    
    <div class="space-y-4">
        @forelse($announcements as $announcement)
            @if(isset($announcement->is_important) && $announcement->is_important)
                <!-- Important Announcement -->
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
                <!-- Regular Announcement -->
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
            <div class="flex flex-col items-center justify-center py-8 text-center">
                <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 mb-2">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <p class="text-[13px] font-medium text-gray-900">Belum ada pengumuman</p>
                <p class="text-[12px] text-gray-500 mt-0.5">Pengumuman terbaru instansi akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</div>