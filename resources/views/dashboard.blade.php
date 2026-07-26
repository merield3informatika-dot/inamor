<x-app-layout>
    @php
        $workspace = auth()->user()->workspaceMemberships->first()?->workspace;
        $firstName = explode(' ', auth()->user()->name)[0];
    @endphp

    <div class="pb-10 pt-4">
        
        <!-- Hero / Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-gray-900 mb-2">
                Good morning, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">{{ $firstName }}</span>.
            </h1>
            <p class="text-[15px] font-medium text-gray-500">
                Here is an overview of your organization's knowledge base.
            </p>
        </div>

        <!-- AI Jumpstart Banner -->
        <a href="{{ route('chat') }}" class="group relative block w-full rounded-[24px] bg-[#0F172A] p-6 md:p-8 overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.1)] mb-8 transition-all duration-300 hover:shadow-[0_8px_30px_rgba(79,70,229,0.2)] hover:-translate-y-1 active:scale-[0.99]">
            <!-- Decorative Glow -->
            <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-indigo-500/20 blur-3xl transition-all duration-500 group-hover:bg-indigo-500/30"></div>
            <div class="absolute -left-20 -bottom-20 h-64 w-64 rounded-full bg-purple-500/20 blur-3xl transition-all duration-500 group-hover:bg-purple-500/30"></div>
            
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-500/20 text-indigo-300">
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                            </svg>
                        </span>
                        <span class="text-[12px] font-bold uppercase tracking-wider text-indigo-300">KnowledgeOS AI</span>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Ask your Knowledge Base</h2>
                    <p class="text-gray-400 text-[15px] max-w-xl leading-relaxed">
                        Instantly find answers, summarize reports, and extract insights from all your uploaded organization documents.
                    </p>
                </div>
                <div class="shrink-0">
                    <div class="inline-flex h-12 items-center justify-center gap-2 rounded-[12px] bg-white px-6 text-[14px] font-semibold text-gray-900 transition-colors group-hover:bg-indigo-50">
                        Start New Chat
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </div>
            </div>
        </a>

        <!-- Stats & Overview Grid -->
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3 mb-8">
            
            <!-- Active Workspace -->
            <div class="rounded-[20px] border border-gray-200/80 bg-white p-6 shadow-[0_2px_8px_-2px_rgba(0,0,0,0.04)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-[10px] bg-indigo-50 text-indigo-600">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-600 ring-1 ring-inset ring-emerald-600/20">
                        Active
                    </span>
                </div>
                <p class="text-[13px] font-medium text-gray-500 mb-1">Current Workspace</p>
                <h3 class="text-xl font-bold text-gray-900 truncate" title="{{ $workspace?->name ?? 'No Workspace' }}">
                    {{ $workspace?->name ?? 'No Workspace' }}
                </h3>
            </div>

            <!-- Documents -->
            <div class="rounded-[20px] border border-gray-200/80 bg-white p-6 shadow-[0_2px_8px_-2px_rgba(0,0,0,0.04)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-[10px] bg-sky-50 text-sky-600">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                </div>
                <p class="text-[13px] font-medium text-gray-500 mb-1">Total Documents</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-gray-900">0</h3>
                    <span class="text-[13px] text-gray-400">Indexed</span>
                </div>
            </div>

            <!-- Members -->
            <div class="rounded-[20px] border border-gray-200/80 bg-white p-6 shadow-[0_2px_8px_-2px_rgba(0,0,0,0.04)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-[10px] bg-purple-50 text-purple-600">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-[13px] font-medium text-gray-500 mb-1">Workspace Members</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-gray-900">{{ $workspace?->members()->count() ?? 0 }}</h3>
                    <span class="text-[13px] text-gray-400">Users</span>
                </div>
            </div>
            
        </div>

        <!-- Quick Actions -->
        <div>
            <h3 class="text-[14px] font-bold uppercase tracking-wider text-gray-400 mb-4 px-1">Quick Actions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                
                <!-- Upload Document -->
                <a href="{{ route('documents.create') }}" class="group flex items-center gap-4 rounded-[16px] border border-gray-200/80 bg-white p-4 shadow-sm transition-all hover:border-indigo-200 hover:shadow-md">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gray-50 text-gray-500 transition-colors group-hover:bg-indigo-50 group-hover:text-indigo-600">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-[14px] font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">Upload Document</h4>
                        <p class="text-[12px] text-gray-500">Add files to knowledge base</p>
                    </div>
                </a>

                <!-- AI Chat -->
                <a href="{{ route('chat') }}" class="group flex items-center gap-4 rounded-[16px] border border-gray-200/80 bg-white p-4 shadow-sm transition-all hover:border-indigo-200 hover:shadow-md">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gray-50 text-gray-500 transition-colors group-hover:bg-indigo-50 group-hover:text-indigo-600">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.333c-1.183.39-2.61.8-4.06.8.3-1.42.3-2.88 0-3.92C3.21 14.88 2 13.56 2 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-[14px] font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">New Chat</h4>
                        <p class="text-[12px] text-gray-500">Talk to the AI Assistant</p>
                    </div>
                </a>

                <!-- Manage Members (Disabled) -->
                <div class="flex items-center gap-4 rounded-[16px] border border-gray-100 bg-gray-50/50 p-4 opacity-70 cursor-not-allowed">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-[14px] font-semibold text-gray-500">Manage Members</h4>
                        <p class="text-[12px] text-gray-400">Coming soon</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>