<header class="sticky top-0 z-40 bg-[#F8FAFC]/90 backdrop-blur-md py-3 px-5 md:px-6">
    <div class="flex items-center justify-between gap-4 max-w-[1560px] mx-auto">
        
        <!-- Search -->
        <div class="flex-1 max-w-[560px]">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    class="block w-full h-[40px] pl-10 pr-14 bg-white border border-gray-200/80 rounded-[12px] text-[13px] text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500/10 focus:border-purple-400 shadow-[0_2px_8px_rgba(0,0,0,0.01)] transition-all" 
                    placeholder="Cari layanan, dokumen, SOP, atau tanya AI..."
                >
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                    <kbd class="hidden sm:inline-flex items-center gap-1 px-1.5 py-0.5 border border-gray-200 rounded-[5px] text-[10px] font-medium text-gray-500 bg-gray-50">
                        ⌘ K
                    </kbd>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 shrink-0">
            
            <a href="{{ route('chat') }}" class="hidden sm:flex items-center gap-1.5 bg-purple-50 text-purple-700 px-3.5 h-[36px] rounded-[10px] font-semibold text-[12px] border border-purple-100 hover:bg-purple-100 transition-all shadow-sm">
                <svg class="w-3.5 h-3.5 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                </svg>
                AI Assistant
            </a>

            <div class="h-5 w-px bg-gray-200 hidden sm:block"></div>

            <button class="relative p-2 text-gray-500 hover:text-gray-900 hover:bg-white rounded-full transition-colors">
                <svg class="w-4.5 h-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute top-1.5 right-1.5 flex h-2 w-2 items-center justify-center rounded-full bg-purple-600 border border-[#F8FAFC]"></span>
            </button>

            <button class="p-2 text-gray-500 hover:text-gray-900 hover:bg-white rounded-full transition-colors">
                <svg class="w-4.5 h-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </button>

            <button class="flex items-center gap-1.5 pl-2 border-l border-gray-200 transition-colors">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=E0E7FF&color=4F46E5" alt="Profile" class="w-8 h-8 rounded-full object-cover">
                <svg class="w-3 h-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
            </button>
            
        </div>
    </div>
</header>