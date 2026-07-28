@props(['documents' => []])

<div class="bg-white rounded-[24px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-6 flex flex-col h-full">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6 shrink-0">
        <h3 class="text-[16px] font-bold text-gray-900 tracking-tight">Dokumen Terbaru</h3>
        <a href="{{ route('documents.index') }}" class="group flex items-center gap-1 text-[13px] font-semibold text-blue-600 hover:text-blue-700 transition-colors">
            Lihat semua
            <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>
    
    <!-- List Content -->
    <div class="flex flex-col gap-1 flex-1">
        @forelse($documents as $document)
            <div class="group relative flex items-center gap-4 p-3 -mx-3 rounded-2xl hover:bg-gray-50/80 hover:shadow-[inset_0_0_0_1px_rgba(0,0,0,0.03)] transition-all duration-200 cursor-pointer">
                
                <!-- File Icon/Badge -->
                <div class="w-11 h-11 rounded-[12px] bg-gradient-to-b from-gray-50 to-gray-100 border border-gray-200/80 shadow-sm flex items-center justify-center shrink-0 relative overflow-hidden">
                    <!-- Folded Corner Effect -->
                    <div class="absolute top-0 right-0 w-3.5 h-3.5 bg-white border-b border-l border-gray-200/80 rounded-bl-[6px]"></div>
                    <!-- Extension Text -->
                    <span class="text-[10px] font-bold text-gray-600 uppercase tracking-wider mt-1">
                        {{ $document->extension ?? 'DOC' }}
                    </span>
                </div>
                
                <!-- Document Info -->
                <div class="flex-1 min-w-0">
                    <h4 class="text-[14px] font-semibold text-gray-900 group-hover:text-blue-600 transition-colors truncate mb-0.5 leading-snug">
                        {{ $document->title }}
                    </h4>
                    <div class="flex items-center text-[12px] font-medium text-gray-500">
                        <span class="truncate max-w-[120px]">{{ $document->category ?? 'Dokumen' }}</span>
                        <span class="mx-2 text-gray-300">•</span>
                        <span class="shrink-0 flex items-center gap-1">
                            <svg class="w-3 h-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $document->created_at?->diffForHumans() ?? 'Baru saja' }}
                        </span>
                    </div>
                </div>
                
           
               
            </div>
        @empty
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center flex-1 py-10 px-4 mt-2 text-center rounded-2xl border-2 border-dashed border-gray-100 bg-gray-50/50">
                <div class="w-14 h-14 rounded-[16px] bg-white shadow-sm border border-gray-100 flex items-center justify-center text-gray-400 mb-4 relative">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <!-- Decorative plus badge -->
                    <div class="absolute -bottom-1.5 -right-1.5 w-6 h-6 bg-gray-100 border-2 border-white rounded-full flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-[14px] font-semibold text-gray-900 mb-1">Belum ada dokumen</h4>
                <p class="text-[12.5px] text-gray-500 max-w-[220px] leading-relaxed">Dokumen yang baru diunggah atau dibagikan akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</div>