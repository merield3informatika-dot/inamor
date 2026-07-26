<aside class="fixed top-3 bottom-3 left-3 z-50 w-[250px] flex flex-col bg-white rounded-[16px] border border-gray-200/80 shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05)] transition-transform duration-300 -translate-x-full md:translate-x-0 overflow-hidden">
    
    <!-- Workspace Switcher -->
    <div class="px-3 pt-3 pb-2">
        <button class="flex items-center justify-between w-full h-[44px] px-2 rounded-[10px] hover:bg-gray-50 transition-colors group">
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-[6px] bg-gradient-to-tr from-indigo-600 to-indigo-500 flex items-center justify-center text-white font-semibold text-[11px] shadow-sm">
                    {{ substr(auth()->user()->workspaceMemberships->first()?->workspace?->name ?? 'K', 0, 1) }}
                </div>
                <span class="text-[14px] font-medium text-gray-900 truncate">
                    {{ auth()->user()->workspaceMemberships->first()?->workspace?->name ?? 'KnowledgeOS' }}
                </span>
            </div>
            <svg class="w-[18px] h-[18px] text-gray-400 group-hover:text-gray-600 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
            </svg>
        </button>
    </div>

    <!-- New Chat Button -->
    <div class="px-3 mb-2">
        <a href="{{ route('chat') }}" class="flex items-center justify-center gap-2 w-full h-[42px] bg-[#0F172A] hover:bg-[#1E293B] text-white rounded-[10px] text-[14px] font-medium transition-colors shadow-[0_1px_2px_rgba(0,0,0,0.1)] active:scale-[0.98]">
            <svg class="w-[18px] h-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            New Chat
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-3 space-y-0.5">
        
        <div class="pt-2 pb-1 px-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
            Workspace
        </div>

        <a href="{{ route('dashboard') }}" class="flex items-center justify-between h-[42px] px-2 rounded-[10px] transition-colors group {{ request()->routeIs('dashboard') ? 'bg-gray-50 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="flex items-center gap-3">
                <svg class="w-[18px] h-[18px] transition-colors {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span class="text-[14px] font-medium">Home</span>
            </div>
        </a>

        <a href="{{ route('chat') }}" class="flex items-center justify-between h-[42px] px-2 rounded-[10px] transition-colors group {{ request()->routeIs('chat.*') ? 'bg-gray-50 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="flex items-center gap-3">
                <svg class="w-[18px] h-[18px] transition-colors {{ request()->routeIs('chat.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.221-1.15-2.136-2.389-2.097a48.045 48.045 0 00-11.212 0c-1.239.039-2.39 .955-2.39 2.176v5.25c0 1.22 1.15 2.136 2.389 2.097A48.24 48.24 0 0012 14.25c.32 0 .64.011.96.032m0 0l-3 3v-3m-3 0c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951" />
                </svg>
                <span class="text-[14px] font-medium">AI Chat</span>
            </div>
        </a>

        <div class="pt-4 pb-1 px-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
            Knowledge Base
        </div>

        <a href="{{ route('documents.index') }}" class="flex items-center justify-between h-[42px] px-2 rounded-[10px] transition-colors group {{ request()->routeIs('documents.*') ? 'bg-gray-50 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="flex items-center gap-3">
                <svg class="w-[18px] h-[18px] transition-colors {{ request()->routeIs('documents.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span class="text-[14px] font-medium">Documents</span>
            </div>
        </a>
    </nav>

    <!-- User Profile Footer -->
    <div class="p-3 border-t border-gray-100">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 p-2 rounded-[10px] hover:bg-gray-50 transition-colors">
            <div class="w-7 h-7 rounded-full bg-gray-900 text-white flex items-center justify-center font-medium text-[11px] shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[13px] font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-[11px] text-gray-500 truncate">{{ auth()->user()->email }}</p>
            </div>
        </a>
    </div>

</aside>