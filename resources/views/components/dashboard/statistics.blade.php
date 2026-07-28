@props(['stats' => []])

<div class="bg-white rounded-[24px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-[15px] font-bold text-gray-900">Statistik Layanan</h3>
        <a href="#" class="text-[12px] text-gray-500 hover:text-blue-600 font-medium transition-colors">Lihat laporan</a>
    </div>
    
    @if(empty($stats))
        <div class="flex flex-col items-center justify-center py-6 text-center">
            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 mb-2">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
            </div>
            <p class="text-[13px] font-medium text-gray-900">Statistik belum tersedia</p>
            <p class="text-[12px] text-gray-500 mt-0.5">Data statistik layanan akan muncul di sini.</p>
        </div>
    @else
        <div class="grid grid-cols-4 gap-4">
            @foreach($stats as $stat)
                <div>
                    <p class="text-[11px] font-medium text-gray-500 mb-1 leading-tight">{{ $stat->label ?? '' }}</p>
                    <h4 class="text-[22px] font-bold text-gray-900 mb-1 leading-none">{{ $stat->value ?? '0' }}</h4>
                    <div class="flex items-center gap-1 text-[11px]">
                        <span class="font-bold flex items-center {{ $stat->trend_color ?? 'text-purple-600' }}">
                            <svg class="w-3 h-3 mr-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            {{ $stat->percentage ?? '0%' }}
                        </span>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">dari bulan lalu</p>
                </div>
            @endforeach
        </div>
    @endif
</div>