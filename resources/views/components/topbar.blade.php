<header class="sticky top-3 z-40 mx-4 md:ml-0 md:mr-6 bg-white/70 backdrop-blur-xl border border-gray-200/60 shadow-[0_2px_8px_-2px_rgba(0,0,0,0.03)] h-[48px] rounded-[16px] flex items-center justify-between px-4 transition-all">
    
    <!-- Left: Breadcrumbs -->
    <div class="flex items-center gap-2 text-[13px] font-medium">
        <span class="text-gray-400 hover:text-gray-600 transition-colors cursor-pointer hidden sm:block">Workspace</span>
        <svg class="w-3.5 h-3.5 text-gray-300 hidden sm:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
        <span class="text-gray-900">Home</span>
    </div>

    <!-- Right: Global Search & Actions -->
    <div class="flex items-center gap-3">
        
        <!-- Search Input (Linear/Notion style Cmd+K) -->
        <div class="hidden md:flex items-center relative group">
            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                <svg class="w-[14px] h-[14px] text-gray-400 group-focus-within:text-indigo-500 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input 
                type="text" 
                class="block w-[220px] lg:w-[260px] h-[32px] pl-8 pr-12 text-[13px] text-gray-900 bg-gray-100/80 border border-transparent rounded-[8px] placeholder-gray-400 focus:outline-none focus:bg-white focus:border-gray-200 focus:ring-4 focus:ring-gray-100/50 transition-all" 
                placeholder="Search knowledge..."
            >
            <div class="absolute inset-y-0 right-0 pr-1.5 flex items-center pointer-events-none">
                <kbd class="inline-flex items-center px-1.5 border border-gray-200 rounded-[4px] text-[10px] font-medium text-gray-400 bg-white shadow-sm">⌘K</kbd>
            </div>
        </div>

        <div class="h-4 w-px bg-gray-200 hidden md:block"></div>

        <!-- Notification Icon -->
        <button class="relative w-[32px] h-[32px] flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-[8px] transition-colors">
            <svg class="w-[18px] h-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
            <span class="absolute top-[7px] right-[7px] w-2 h-2 bg-indigo-500 border-2 border-white rounded-full"></span>
        </button>

    </div>
</header>