@props(['activities' => []])

<div class="bg-white rounded-[24px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-6 flex flex-col h-full">
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-[15px] font-bold text-gray-900">Aktivitas Terbaru</h3>
        <a href="#" class="text-[13px] text-gray-500 hover:text-blue-600 font-medium transition-colors">Lihat semua</a>
    </div>
    
    <div class="space-y-5 flex-1">
        @forelse($activities as $activity)
            <div class="flex items-start gap-3.5">
                <div class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-[13px] font-medium text-gray-900 mb-0.5 truncate">{{ $activity->title ?? '' }}</h4>
                    <p class="text-[13px] {{ isset($activity->is_bold) && $activity->is_bold ? 'font-semibold text-gray-900' : 'text-gray-500' }} leading-relaxed">{{ $activity->description ?? '' }}</p>
                </div>
                <span class="text-[12px] text-gray-400 shrink-0 pt-0.5 flex items-center gap-1.5">
                    {{ $activity->created_at?->format('H:i') ?? '' }}
                    @if(isset($activity->is_unread) && $activity->is_unread)
                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    @endif
                </span>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-12 text-center my-auto">
                <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 mb-3">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-[13px] font-medium text-gray-900">Belum ada aktivitas</p>
                <p class="text-[12px] text-gray-500 mt-0.5">Aktivitas sistem terbaru akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</div>