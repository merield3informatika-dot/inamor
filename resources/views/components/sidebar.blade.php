@php
    $user = auth()->user();
    $currentWorkspace = $user->currentWorkspace ?? null;
@endphp

<aside class="fixed top-0 bottom-0 left-0 z-50 w-[240px] bg-white border-r border-gray-100 flex flex-col transition-transform duration-300 -translate-x-full md:translate-x-0 shadow-[2px_0_16px_rgba(0,0,0,0.015)]">
    
    <!-- Header / Logo -->
    <div class="px-4 pt-5 pb-4 flex items-center gap-2.5 shrink-0">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20 shrink-0">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                <polygon points="12 2 22 8 22 16 12 22 2 16 2 8" opacity="0.2"/>
                <polygon points="12 4 19 8.2 19 15.8 12 20 5 15.8 5 8.2" fill="white"/>
                <circle cx="12" cy="12" r="3" fill="#4F46E5"/>
            </svg>
        </div>
        <div>
            <h1 class="text-[15px] font-bold tracking-tight text-gray-900 leading-tight">INAMOR</h1>
            <p class="text-[9px] text-gray-400 leading-tight">Pemerintah Berbasis AI</p>
        </div>
    </div>

    <!-- Workspace Selector -->
    <div class="px-3 mb-3 shrink-0 relative">
        <button type="button"
            id="workspace-switcher-btn"
            class="w-full flex items-center justify-between p-2 rounded-lg border border-gray-200/80 bg-gray-50/50 hover:bg-gray-50 transition-colors group">
            <div class="flex items-center gap-2.5 overflow-hidden">
                <!-- Workspace Logo / Initials -->
                <div class="w-8 h-8 rounded-md bg-[#0F172A] flex items-center justify-center shrink-0 overflow-hidden shadow-sm">
                    @if($currentWorkspace && $currentWorkspace->logo)
                        <img src="{{ Storage::url($currentWorkspace->logo) }}" alt="{{ $currentWorkspace->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-[13px] font-bold text-white">
                            {{ $currentWorkspace ? strtoupper(substr($currentWorkspace->name, 0, 1)) : '?' }}
                        </span>
                    @endif
                </div>
                <div class="text-left min-w-0">
                    <p class="text-[13px] font-semibold text-gray-900 leading-tight truncate">
                        {{ $currentWorkspace->name ?? 'Pilih Workspace' }}
                    </p>
                    
                    <!-- Visibility Status -->
                    @if($currentWorkspace)
                        @if($currentWorkspace->visibility === 'private')
                            <div class="flex items-center gap-1 mt-0.5 text-amber-600">
                                <svg class="w-2.5 h-2.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-[10px] font-medium leading-tight">Private Workspace</span>
                            </div>
                        @else
                            <div class="flex items-center gap-1 mt-0.5 text-emerald-600">
                                <svg class="w-2.5 h-2.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-[10px] font-medium leading-tight">Public Workspace</span>
                            </div>
                        @endif
                    @else
                        <span class="text-[10px] text-gray-500 leading-tight">Belum Ada Workspace</span>
                    @endif
                </div>
            </div>
            <svg id="workspace-switcher-chevron" class="w-3.5 h-3.5 text-gray-400 group-hover:text-gray-600 shrink-0 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
        </button>

        <!-- Workspace Dropdown List -->
        <div id="workspace-switcher-menu" class="hidden absolute left-0 right-0 mt-1.5 bg-white border border-gray-100 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] z-50 overflow-hidden transform opacity-0 scale-95 transition-all duration-200 ease-out">
            <div class="max-h-[240px] overflow-y-auto py-1">
                @foreach ($user->workspaceMemberships as $membership)
                    @php $ws = $membership->workspace; @endphp
                    <form action="{{ route('workspaces.switch', $ws) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 flex items-center justify-between {{ $ws->id === ($currentWorkspace->id ?? null) ? 'bg-blue-50/50' : 'hover:bg-gray-50' }} transition-colors">
                            <div class="flex items-center gap-2.5 overflow-hidden">
                                <!-- Initial/Logo per item -->
                                <div class="w-6 h-6 rounded bg-[#0F172A] text-white flex items-center justify-center shrink-0 overflow-hidden shadow-sm">
                                    @if($ws->logo)
                                        <img src="{{ Storage::url($ws->logo) }}" alt="{{ $ws->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-[10px] font-bold">{{ strtoupper(substr($ws->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[12px] truncate {{ $ws->id === ($currentWorkspace->id ?? null) ? 'text-blue-700 font-semibold' : 'text-gray-700 font-medium' }}">
                                        {{ $ws->name }}
                                    </p>
                                    <p class="text-[9px] font-medium mt-0.5 {{ $ws->visibility === 'private' ? 'text-amber-500' : 'text-emerald-500' }}">
                                        {{ ucfirst($ws->visibility) }}
                                    </p>
                                </div>
                            </div>
                            @if($ws->id === ($currentWorkspace->id ?? null))
                                <svg class="w-4 h-4 text-blue-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </button>
                    </form>
                @endforeach
            </div>

            <div class="border-t border-gray-100 bg-gray-50/50 p-1.5 flex flex-col gap-0.5">
                <a href="{{ route('workspace.settings.edit') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-[12px] text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors font-medium">
                    <svg class="w-3.5 h-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Workspace Settings
                </a>
                <a href="{{ route('workspaces.create') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-md text-[12px] text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors font-medium">
                    <svg class="w-3.5 h-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Buat Workspace Baru</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Scrollable Area -->
    <nav class="flex-1 px-2.5 space-y-0.5 overflow-y-auto pb-3 text-[13px] custom-scrollbar">
        
        <!-- ========================================== -->
        <!-- 1. FITUR AKTIF                               -->
        <!-- ========================================== -->

        <!-- Beranda -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-blue-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            <span>Beranda</span>
        </a>

        <!-- AI Assistant -->
        <a href="{{ route('chat') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg transition-colors {{ request()->routeIs('chat') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('chat') ? 'text-purple-600' : 'text-purple-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 00-2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" /></svg>
            <span>AI Assistant</span>
        </a>

        <!-- Dokumen & Arsip (Collapsible) -->
        @php
            $isDocsActive = request()->routeIs(['documents.*', 'knowledge.manual.*', 'knowledge.feedback.*']);
        @endphp
        <div>
            <button type="button" 
                onclick="
                    let menu = this.nextElementSibling;
                    let icon = this.querySelector('.arrow-icon');
                    icon.classList.toggle('rotate-90');
                    menu.classList.toggle('max-h-0');
                    menu.classList.toggle('max-h-[500px]');
                "
                class="w-full flex items-center justify-between px-2.5 py-2 rounded-lg transition-colors {{ $isDocsActive ? 'bg-red-50 text-red-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 {{ $isDocsActive ? 'text-red-600' : 'text-red-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    <span>Dokumen & Arsip</span>
                </div>
                <svg class="arrow-icon w-3.5 h-3.5 transition-transform duration-300 {{ $isDocsActive ? 'rotate-90' : '' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Submenu Container -->
            <div class="overflow-hidden transition-all duration-300 ease-in-out {{ $isDocsActive ? 'max-h-[500px]' : 'max-h-0' }}">
                <div class="pt-1.5 pb-1 pl-11 pr-2 flex flex-col gap-0.5 relative">
                    
                    <div class="absolute left-[22px] top-0 bottom-3 w-px bg-gray-200"></div>

                    <!-- Dokumen -->
                    <a href="{{ route('documents.index') }}" class="group flex items-center gap-2 py-1.5 px-2.5 rounded-md text-[12px] transition-colors relative {{ request()->routeIs('documents.*') ? 'text-gray-900 font-semibold bg-gray-100/80' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-3.5 h-3.5 {{ request()->routeIs('documents.*') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Dokumen</span>
                    </a>
                    
                    <!-- Manual Knowledge -->
                    <a href="{{ route('knowledge.manual.index') }}" class="group flex items-center gap-2 py-1.5 px-2.5 rounded-md text-[12px] transition-colors relative {{ request()->routeIs('knowledge.manual.*') ? 'text-gray-900 font-semibold bg-gray-100/80' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-3.5 h-3.5 {{ request()->routeIs('knowledge.manual.*') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span>Manual Knowledge</span>
                    </a>
                    
                    <!-- AI Feedback -->
                    <a href="{{ route('knowledge.feedback.index') }}" class="group flex items-center gap-2 py-1.5 px-2.5 rounded-md text-[12px] transition-colors relative {{ request()->routeIs('knowledge.feedback.*') ? 'text-gray-900 font-semibold bg-gray-100/80' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-3.5 h-3.5 {{ request()->routeIs('knowledge.feedback.*') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span>AI Feedback</span>
                    </a>

                    <!-- AI Training -->
                    <a href="javascript:void(0)" class="group flex items-center gap-2 py-1.5 px-2.5 rounded-md text-[12px] transition-colors text-gray-400 cursor-not-allowed">
                        <svg class="w-3.5 h-3.5 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        <span>AI Training</span>
                    </a>
                    
                </div>
            </div>
        </div>

        <!-- Kalender Kegiatan -->
        <a href="{{ route('calendar.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg transition-colors {{ request()->routeIs('calendar.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('calendar.*') ? 'text-blue-600' : 'text-blue-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Kalender Kegiatan</span>
        </a>

        <!-- Workspace (Collapsible) -->
        @php
            $isWorkspaceNavActive = request()->routeIs(['workspace.members.*', 'workspace.join-request.*', 'workspace.invitation.*', 'workspace.settings.*']);
        @endphp
        <div>
            <button type="button" 
                onclick="
                    let menu = this.nextElementSibling;
                    let icon = this.querySelector('.arrow-icon');
                    icon.classList.toggle('rotate-90');
                    menu.classList.toggle('max-h-0');
                    menu.classList.toggle('max-h-[500px]');
                "
                class="w-full flex items-center justify-between px-2.5 py-2 rounded-lg transition-colors {{ $isWorkspaceNavActive ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 {{ $isWorkspaceNavActive ? 'text-indigo-600' : 'text-indigo-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Workspace</span>
                </div>
                <svg class="arrow-icon w-3.5 h-3.5 transition-transform duration-300 {{ $isWorkspaceNavActive ? 'rotate-90' : '' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Submenu Container -->
            <div class="overflow-hidden transition-all duration-300 ease-in-out {{ $isWorkspaceNavActive ? 'max-h-[500px]' : 'max-h-0' }}">
                <div class="pt-1.5 pb-1 pl-11 pr-2 flex flex-col gap-0.5 relative">
                    
                    <div class="absolute left-[22px] top-0 bottom-3 w-px bg-gray-200"></div>

                    <!-- Members Section Group -->
                    <a href="{{ route('workspace.members.index') }}" class="group flex items-center gap-2 py-1.5 px-2.5 rounded-md text-[12px] transition-colors relative {{ request()->routeIs('workspace.members.*') ? 'text-gray-900 font-semibold bg-gray-100/80' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-3.5 h-3.5 {{ request()->routeIs('workspace.members.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Members</span>
                    </a>
                    
                    <a href="{{ route('workspace.join-request.index') }}" class="group flex items-center justify-between py-1.5 px-2.5 rounded-md text-[12px] transition-colors relative {{ request()->routeIs('workspace.join-request.*') ? 'text-gray-900 font-semibold bg-gray-100/80' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 {{ request()->routeIs('workspace.join-request.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Pending Requests</span>
                        </div>
                        @if(isset($pendingJoinRequestsCount) && $pendingJoinRequestsCount > 0)
                            <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendingJoinRequestsCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('workspace.invitation.show') }}" class="group flex items-center gap-2 py-1.5 px-2.5 rounded-md text-[12px] transition-colors relative {{ request()->routeIs('workspace.invitation.*') ? 'text-gray-900 font-semibold bg-gray-100/80' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-3.5 h-3.5 {{ request()->routeIs('workspace.invitation.*') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        <span>Invitation</span>
                    </a>

                    <!-- Settings Section Group -->
                    <a href="{{ route('workspace.settings.edit') }}" class="group flex items-center gap-2 py-1.5 px-2.5 rounded-md text-[12px] transition-colors relative {{ request()->routeIs('workspace.settings.edit', 'workspace.settings.update') ? 'text-gray-900 font-semibold bg-gray-100/80' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-3.5 h-3.5 {{ request()->routeIs('workspace.settings.edit', 'workspace.settings.update') ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>General Settings</span>
                    </a>

                    <a href="{{ route('workspace.settings.danger') }}" class="group flex items-center gap-2 py-1.5 px-2.5 rounded-md text-[12px] transition-colors relative {{ request()->routeIs('workspace.settings.danger', 'workspace.destroy') ? 'text-red-600 font-semibold bg-red-50/80' : 'text-gray-500 hover:text-red-600 hover:bg-gray-50' }}">
                        <svg class="w-3.5 h-3.5 {{ request()->routeIs('workspace.settings.danger', 'workspace.destroy') ? 'text-red-600' : 'text-gray-400 group-hover:text-red-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Danger Zone</span>
                    </a>

                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 2. FITUR MENDATANG (COMING SOON)           -->
        <!-- ========================================== -->
        
        @php
            $comingSoonWrapper = "flex items-center justify-between px-2.5 py-2 rounded-lg text-gray-500 opacity-60 cursor-not-allowed hover:bg-gray-50/50 transition-colors mt-4";
            $comingSoonWrapperNormal = "flex items-center justify-between px-2.5 py-2 rounded-lg text-gray-500 opacity-60 cursor-not-allowed hover:bg-gray-50/50 transition-colors";
            $comingSoonBadge = "text-[9px] font-bold bg-gray-200/60 text-gray-500 px-1.5 py-0.5 rounded-full tracking-wide";
        @endphp

        <!-- Layanan Digital -->
        <a href="javascript:void(0)" class="{{ $comingSoonWrapper }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                <span>Layanan Digital</span>
            </div>
            <span class="{{ $comingSoonBadge }}">Soon</span>
        </a>

        <!-- Surat & Disposisi -->
        <a href="javascript:void(0)" class="{{ $comingSoonWrapperNormal }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-orange-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                <span>Surat & Disposisi</span>
            </div>
            <div class="flex items-center gap-1.5">
                @if(isset($unreadDispositions) && $unreadDispositions > 0)
                    <span class="bg-red-100 text-red-600 text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $unreadDispositions }}</span>
                @endif
                <span class="{{ $comingSoonBadge }}">Soon</span>
            </div>
        </a>

        <!-- Pengumuman -->
        <a href="javascript:void(0)" class="{{ $comingSoonWrapperNormal }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                <span>Pengumuman</span>
            </div>
            <span class="{{ $comingSoonBadge }}">Soon</span>
        </a>

        <!-- Data & Laporan -->
        <a href="javascript:void(0)" class="{{ $comingSoonWrapperNormal }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-teal-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                <span>Data & Laporan</span>
            </div>
            <span class="{{ $comingSoonBadge }}">Soon</span>
        </a>

        <!-- Monitoring & Evaluasi -->
        <a href="javascript:void(0)" class="{{ $comingSoonWrapperNormal }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                <span>Monitoring & Evaluasi</span>
            </div>
            <span class="{{ $comingSoonBadge }}">Soon</span>
        </a>

        <!-- Pengaturan -->
        <a href="javascript:void(0)" class="{{ $comingSoonWrapperNormal }}">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                <span>Pengaturan</span>
            </div>
            <span class="{{ $comingSoonBadge }}">Soon</span>
        </a>
    </nav>

   <!-- Bottom User Profile (Pinned) -->
    <div class="p-3 border-t border-gray-100 shrink-0 relative bg-white">
        <button id="user-profile-btn" type="button" class="w-full flex items-center gap-2.5 px-2 py-2 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none group">
            <div class="relative shrink-0">
                @php
                    $avatarFallbackUrl = 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&background=E0E7FF&color=4F46E5';
                    $avatarUrl = $user->avatar ? Storage::url($user->avatar) : $avatarFallbackUrl;
                @endphp
                <img
                    src="{{ $avatarUrl }}"
                    alt="Profile"
                    class="w-8 h-8 rounded-full object-cover shadow-sm group-hover:shadow-md transition-shadow"
                    onerror="this.onerror=null; this.src='{{ $avatarFallbackUrl }}';"
                >
                <span class="absolute bottom-0 right-0 w-2 h-2 bg-green-500 border-2 border-white rounded-full"></span>
            </div>
            <div class="flex-1 min-w-0 text-left">
                <p class="text-[12px] font-bold text-gray-900 truncate">{{ $user->name ?? 'Pengguna' }}</p>
                <p class="text-[10px] text-gray-500 truncate">{{ $user->job_title ?: 'Member' }}</p>
            </div>
            <svg id="user-profile-chevron" class="w-4 h-4 text-gray-400 group-hover:text-gray-600 shrink-0 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Profile Dropdown Menu -->
        <div id="user-profile-menu" class="hidden absolute bottom-[100%] left-2 right-2 mb-1 bg-white border border-gray-100/80 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] z-50 p-1.5 opacity-0 scale-95 translate-y-2 transition-all duration-200 ease-out">
            
            <!-- My Profile -->
            <a href="{{ route('profile.edit') }}" class="w-full flex items-center justify-between px-2.5 py-2 rounded-lg text-[12px] text-gray-700 transition-colors duration-150 hover:bg-gray-50 mb-0.5">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium">My Profile</span>
                </div>
            </a>

            <!-- Workspace Settings (Active Route) -->
            <a href="{{ route('workspace.settings.edit') }}" class="w-full flex items-center justify-between px-2.5 py-2 rounded-lg text-[12px] text-gray-700 hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="font-medium">Workspace Settings</span>
                </div>
            </a>

            <!-- Divider -->
            <div class="h-px bg-gray-100 my-1.5 mx-1"></div>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[12px] text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Scripts untuk Dropdown -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- Script Profil ---
            const profileBtn = document.getElementById('user-profile-btn');
            const profileMenu = document.getElementById('user-profile-menu');
            const profileChevron = document.getElementById('user-profile-chevron');
            let isProfileOpen = false;

            function toggleProfileMenu(event) {
                if (event) event.stopPropagation();
                
                isProfileOpen = !isProfileOpen;
                if (isProfileOpen) {
                    profileMenu.classList.remove('hidden');
                    void profileMenu.offsetWidth; // Reflow
                    
                    profileMenu.classList.remove('opacity-0', 'scale-95', 'translate-y-2');
                    profileMenu.classList.add('opacity-100', 'scale-100', 'translate-y-0');
                    profileChevron.classList.add('rotate-180');
                    
                    if (isWorkspaceOpen) toggleWorkspaceMenu(); // Auto-close other menu
                } else {
                    profileMenu.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
                    profileMenu.classList.add('opacity-0', 'scale-95', 'translate-y-2');
                    profileChevron.classList.remove('rotate-180');
                    setTimeout(() => { if (!isProfileOpen) profileMenu.classList.add('hidden'); }, 200);
                }
            }
            profileBtn.addEventListener('click', toggleProfileMenu);


            // --- Script Workspace Switcher ---
            const workspaceBtn = document.getElementById('workspace-switcher-btn');
            const workspaceMenu = document.getElementById('workspace-switcher-menu');
            const workspaceChevron = document.getElementById('workspace-switcher-chevron');
            let isWorkspaceOpen = false;

            function toggleWorkspaceMenu(event) {
                if (event) event.stopPropagation();
                
                isWorkspaceOpen = !isWorkspaceOpen;
                if (isWorkspaceOpen) {
                    workspaceMenu.classList.remove('hidden');
                    void workspaceMenu.offsetWidth; // Reflow
                    
                    workspaceMenu.classList.remove('opacity-0', 'scale-95');
                    workspaceMenu.classList.add('opacity-100', 'scale-100');
                    workspaceChevron.classList.add('rotate-180');
                    
                    if (isProfileOpen) toggleProfileMenu(); // Auto-close other menu
                } else {
                    workspaceMenu.classList.remove('opacity-100', 'scale-100');
                    workspaceMenu.classList.add('opacity-0', 'scale-95');
                    workspaceChevron.classList.remove('rotate-180');
                    setTimeout(() => { if (!isWorkspaceOpen) workspaceMenu.classList.add('hidden'); }, 200);
                }
            }
            workspaceBtn.addEventListener('click', toggleWorkspaceMenu);

            // Close dropdowns if clicking outside
            document.addEventListener('click', function (event) {
                if (isProfileOpen && !profileBtn.contains(event.target) && !profileMenu.contains(event.target)) {
                    toggleProfileMenu();
                }
                if (isWorkspaceOpen && !workspaceBtn.contains(event.target) && !workspaceMenu.contains(event.target)) {
                    toggleWorkspaceMenu();
                }
            });

        });
    </script>
</aside>
