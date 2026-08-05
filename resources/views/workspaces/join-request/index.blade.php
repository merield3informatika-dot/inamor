<x-app-layout>

<style>
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        opacity: 0;
        animation: fadeSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div class="max-w-2xl">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Join Requests</h1>
            <p class="text-sm sm:text-base text-gray-500 mt-1.5 leading-relaxed">
                Review and verify identity submissions from users requesting access to your workspace.
            </p>
        </div>
        
        <!-- Controls & Archived Link -->
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <!-- Archived Requests Navigation Button -->
            <a href="{{ route('workspace.join-request.archived') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-[0_1px_2px_rgb(0,0,0,0.02)] shrink-0 active:scale-95">
                <svg class="w-4 h-4 mr-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
                Archived Requests
            </a>

            <!-- Search Bar -->
            <div class="relative w-full sm:w-60 group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-gray-900 transition-colors">
                    <svg class="h-4.5 w-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    id="searchInput"
                    placeholder="Search name or email..." 
                    class="w-full pl-10 pr-9 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent shadow-[0_1px_2px_rgb(0,0,0,0.02)] hover:border-gray-300 transition-all duration-200 placeholder:text-gray-400"
                >
                <!-- Clear Search Icon Button -->
                <button type="button" id="clearSearchBtn" onclick="document.getElementById('searchInput').value=''; document.getElementById('searchInput').dispatchEvent(new Event('input'));" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-300 hover:text-gray-600 transition-colors hidden">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <!-- Status Filter -->
            <select id="statusFilter" class="w-full sm:w-auto py-2.5 pl-4 pr-10 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent shadow-[0_1px_2px_rgb(0,0,0,0.02)] cursor-pointer hover:border-gray-300 transition-all duration-200 text-gray-700 font-medium">
                <option value="all">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
        <!-- Pending -->
        <div class="bg-white border border-gray-200/70 rounded-2xl p-5 shadow-[0_2px_8px_rgb(0,0,0,0.02)] relative overflow-hidden group hover:shadow-[0_8px_20px_rgb(0,0,0,0.04)] hover:border-gray-300 transition-all duration-300">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[12px] font-semibold text-gray-500 uppercase tracking-wide">Pending</p>
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700">+12%</span>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-gray-900 tracking-tight stat-counter" data-target="{{ collect($joinRequests)->where('status', 'pending')->count() }}">0</h3>
                <span class="text-xs text-gray-400 font-medium">Requiring review</span>
            </div>
        </div>
        
        <!-- Approved -->
        <div class="bg-white border border-gray-200/70 rounded-2xl p-5 shadow-[0_2px_8px_rgb(0,0,0,0.02)] relative overflow-hidden group hover:shadow-[0_8px_20px_rgb(0,0,0,0.04)] hover:border-gray-300 transition-all duration-300">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[12px] font-semibold text-gray-500 uppercase tracking-wide">Approved</p>
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700">Stable</span>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-gray-900 tracking-tight stat-counter" data-target="{{ collect($joinRequests)->where('status', 'approved')->count() }}">0</h3>
                <span class="text-xs text-gray-400 font-medium">Onboarded members</span>
            </div>
        </div>
        
        <!-- Rejected -->
        <div class="bg-white border border-gray-200/70 rounded-2xl p-5 shadow-[0_2px_8px_rgb(0,0,0,0.02)] relative overflow-hidden group hover:shadow-[0_8px_20px_rgb(0,0,0,0.04)] hover:border-gray-300 transition-all duration-300">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[12px] font-semibold text-gray-500 uppercase tracking-wide">Rejected</p>
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700">Filtered</span>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-gray-900 tracking-tight stat-counter" data-target="{{ collect($joinRequests)->where('status', 'rejected')->count() }}">0</h3>
                <span class="text-xs text-gray-400 font-medium">Denied access</span>
            </div>
        </div>
        
        <!-- Total -->
        <div class="bg-white border border-gray-200/70 rounded-2xl p-5 shadow-[0_2px_8px_rgb(0,0,0,0.02)] relative overflow-hidden group hover:shadow-[0_8px_20px_rgb(0,0,0,0.04)] hover:border-gray-300 transition-all duration-300">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[12px] font-semibold text-gray-500 uppercase tracking-wide">Total Requests</p>
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600">All time</span>
            </div>
            <div class="flex items-baseline justify-between">
                <h3 class="text-3xl font-bold text-gray-900 tracking-tight stat-counter" data-target="{{ count($joinRequests) }}">0</h3>
                <span class="text-xs text-gray-400 font-medium">Submissions</span>
            </div>
        </div>
    </div>

    <!-- Success Message (Stripe / GitHub Style Alert) -->
    @if(session('status'))
        <div class="mb-8 rounded-xl border border-gray-900/10 bg-gray-900 text-white p-4 flex items-center justify-between shadow-xl animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                    <svg class="h-4 w-4 text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="text-sm font-medium tracking-tight">
                    {{ session('status') }}
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-gray-400 hover:text-white p-1 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <!-- Request List (Compact Horizontal Cards) -->
    <div class="space-y-3" id="requestsContainer">
        @forelse($joinRequests as $request)
            <div class="request-card animate-fade-in bg-white border border-gray-200/70 rounded-2xl px-5 py-4 flex flex-col xl:flex-row gap-6 xl:items-center justify-between shadow-[0_1px_4px_rgb(0,0,0,0.02)] hover:shadow-[0_6px_20px_rgb(0,0,0,0.05)] hover:border-gray-300 transition-all duration-200" 
                 style="animation-delay: {{ $loop->index * 0.04 }}s;"
                 data-name="{{ strtolower($request->full_name) }}" 
                 data-email="{{ strtolower($request->email) }}" 
                 data-status="{{ strtolower($request->status) }}">
                
                <!-- Left: User Identity & Metadata -->
                <div class="flex items-center gap-4 xl:w-[32%] min-w-0">
                    <div class="relative shrink-0">
                       @php
    $avatar = optional($request->user)->avatar;
@endphp

@if($avatar)
    <img
        src="{{ Storage::url($avatar) }}"
        alt="{{ $request->full_name }}"
        class="w-11 h-11 rounded-full object-cover border border-gray-200 shadow-sm"
    >
@else
    <img
        src="https://ui-avatars.com/api/?name={{ urlencode($request->full_name) }}&background=F3F4F6&color=111827&bold=true"
        alt="{{ $request->full_name }}"
        class="w-11 h-11 rounded-full object-cover border border-gray-200 shadow-sm"
    >
@endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="text-sm font-bold text-gray-900 truncate tracking-tight">{{ $request->full_name }}</h4>
                        <p class="text-xs text-gray-500 truncate mt-0.5">{{ $request->email }}</p>
                        <div class="flex items-center gap-2 mt-1 text-[11px] text-gray-400 font-medium">
                            <span>Submitted {{ $request->created_at ? $request->created_at->diffForHumans() : 'recently' }}</span>
                            @if(isset($request->reviewed_at) && $request->reviewed_at)
                                <span>•</span>
                                <span>Reviewed by Admin</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Center: Verification Documents Preview (Larger & Sleeker) -->
                <div class="flex items-center gap-4 xl:w-[38%] xl:justify-center">
                    
                    <!-- Identity Card -->
                    <div class="flex items-center gap-2.5 cursor-pointer group/img" onclick="openPreviewModal('{{ Storage::url($request->identity_card_path) }}')">
                        <div class="w-24 h-14 rounded-lg overflow-hidden border border-gray-200 shadow-xs relative bg-gray-50">
                            <div class="absolute inset-0 bg-gray-100 animate-pulse"></div>
                            <img 
                                src="{{ Storage::url($request->identity_card_path) }}" 
                                alt="ID Card" 
                                class="relative z-10 w-full h-full object-cover transition-transform duration-300 group-hover/img:scale-105"
                                loading="lazy"
                                onload="this.previousElementSibling.remove()"
                            >
                            <div class="absolute inset-0 z-20 bg-gray-900/0 group-hover/img:bg-gray-900/15 backdrop-blur-[0.5px] transition-all flex items-center justify-center opacity-0 group-hover/img:opacity-100">
                                <svg class="w-4 h-4 text-white drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </div>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">ID Card</span>
                    </div>

                    <!-- Selfie -->
                    <div class="flex items-center gap-2.5 cursor-pointer group/img" onclick="openPreviewModal('{{ Storage::url($request->selfie_with_identity_card_path) }}')">
                        <div class="w-14 h-14 rounded-lg overflow-hidden border border-gray-200 shadow-xs relative bg-gray-50">
                            <div class="absolute inset-0 bg-gray-100 animate-pulse"></div>
                            <img 
                                src="{{ Storage::url($request->selfie_with_identity_card_path) }}" 
                                alt="Selfie" 
                                class="relative z-10 w-full h-full object-cover transition-transform duration-300 group-hover/img:scale-105"
                                loading="lazy"
                                onload="this.previousElementSibling.remove()"
                            >
                            <div class="absolute inset-0 z-20 bg-gray-900/0 group-hover/img:bg-gray-900/15 backdrop-blur-[0.5px] transition-all flex items-center justify-center opacity-0 group-hover/img:opacity-100">
                                <svg class="w-4 h-4 text-white drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </div>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Selfie</span>
                    </div>

                </div>

                <!-- Right: Status Badge & Interactive Action Buttons -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between xl:w-[30%] gap-4 xl:justify-end">
                    
                    <!-- Status Badge -->
                    <div class="shrink-0">
                        @if($request->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60 tracking-wide">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                                Pending
                            </span>
                        @elseif($request->status === 'approved')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 tracking-wide">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                Approved
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200/60 tracking-wide">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                Rejected
                            </span>
                        @endif
                    </div>

                    <!-- Action Buttons with Loading States -->
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        @if($request->status === 'pending')
                            <!-- Approve Form -->
                            <form action="{{ route('workspace.join-request.approve', $request->id) }}" method="POST" class="m-0 flex-1 sm:flex-none" onsubmit="setButtonLoading(this)">
                                @csrf
                                <button type="submit" class="action-btn w-full sm:w-auto inline-flex items-center justify-center px-3.5 py-2 text-xs font-semibold text-white bg-gray-900 rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-1 transition-all shadow-xs active:scale-95 disabled:opacity-50">
                                    <span class="btn-text">Approve</span>
                                    <span class="btn-spinner hidden ml-1.5 animate-spin">⏳</span>
                                </button>
                            </form>
                            
                            <!-- Reject Button -->
                            <button 
                                type="button" 
                                onclick="openRejectModal('{{ route('workspace.join-request.reject', $request->id) }}')"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-gray-900 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all shadow-xs active:scale-95"
                            >
                                Reject
                            </button>
                        @else
                            <!-- Archive Form (SoftDeletes archive route requested) -->
                            <form action="{{ route('workspace.join-request.archive', $request->id) }}" method="POST" class="m-0 flex-1 sm:flex-none" onsubmit="setButtonLoading(this)">
                                @csrf
                                <button type="submit" class="action-btn w-full sm:w-auto inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-gray-600 bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all active:scale-95 disabled:opacity-50">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                    <span class="btn-text">Archive</span>
                                    <span class="btn-spinner hidden ml-1.5 animate-spin">⏳</span>
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>
        @empty
            
            <!-- Blade Empty State -->
            <div class="bg-white border border-gray-200/70 rounded-2xl p-16 flex flex-col items-center justify-center shadow-[0_2px_8px_rgb(0,0,0,0.02)] min-h-[380px]">
                <div class="w-20 h-20 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-5 shadow-xs">
                    <svg class="w-9 h-9 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1 tracking-tight">No join requests found</h3>
                <p class="text-sm text-gray-500 text-center max-w-sm leading-relaxed">Everything looks pristine. There are currently no pending membership requests waiting for verification.</p>
            </div>
            
        @endforelse

        <!-- Frontend Filter JS Empty State -->
        <div id="jsEmptyState" class="hidden bg-white border border-gray-200/70 rounded-2xl p-16 flex-col items-center justify-center shadow-[0_2px_8px_rgb(0,0,0,0.02)] min-h-[380px]">
            <div class="w-20 h-20 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-5 shadow-xs">
                <svg class="w-9 h-9 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1 tracking-tight">No matching requests</h3>
            <p class="text-sm text-gray-500 text-center max-w-sm leading-relaxed">We couldn't find any submission matching your current filter parameter.</p>
            <button onclick="document.getElementById('searchInput').value=''; document.getElementById('statusFilter').value='all'; document.getElementById('searchInput').dispatchEvent(new Event('input'));" class="mt-5 px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors shadow-xs">
                Reset Filter
            </button>
        </div>
    </div>

</div>

<!-- Reject Reason Modal (Enterprise Redesign) -->
<div 
    id="rejectModal" 
    class="fixed inset-0 z-[100] hidden items-center justify-center"
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
>
    <!-- Dark Backdrop -->
    <div id="rejectModalBackdrop" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-300" onclick="closeRejectModal()"></div>
    
    <!-- Modal Panel -->
    <div id="rejectModalPanel" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden opacity-0 translate-y-3 sm:translate-y-0 scale-95 transition-all duration-300 border border-gray-100">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-gray-900 tracking-tight" id="modal-title">Reject Join Request</h3>
            <button type="button" onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-900 p-1 rounded-lg transition-colors">
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form id="rejectForm" method="POST" action="" class="m-0" onsubmit="setButtonLoading(this)">
            @csrf
            <div class="p-6">
                <p class="text-xs text-gray-500 mb-4 leading-relaxed">Please provide a specific reason for rejection. This feedback will be recorded securely.</p>
                
                <div>
                    <label for="reason" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Reason</label>
                    <textarea 
                        id="reason"
                        name="reason" 
                        required 
                        rows="3" 
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all shadow-inner placeholder:text-gray-400 resize-none"
                        placeholder="e.g. Blurry photo, mismatched identity card..."
                    ></textarea>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all shadow-xs">
                    Cancel
                </button>
                <button type="submit" class="action-btn px-4 py-2 text-xs font-semibold text-white bg-rose-600 rounded-xl hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 transition-all shadow-xs active:scale-95 disabled:opacity-50">
                    <span class="btn-text">Reject Request</span>
                    <span class="btn-spinner hidden ml-1.5 animate-spin">⏳</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Fullscreen Image Preview Modal (Enhanced) -->
<div 
    id="imagePreviewModal" 
    class="fixed inset-0 z-[110] hidden items-center justify-center"
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
>
    <!-- Dark Backdrop -->
    <div id="previewBackdrop" class="absolute inset-0 bg-gray-950/90 backdrop-blur-md opacity-0 transition-opacity duration-300 cursor-zoom-out" onclick="closePreviewModal()"></div>
    
    <!-- Close Button -->
    <button 
        type="button"
        onclick="closePreviewModal()" 
        class="absolute top-6 right-6 p-2.5 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-full transition-all z-20 focus:outline-none"
    >
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    <!-- Image Container -->
    <div class="relative z-10 w-full max-w-6xl p-4 sm:p-8 flex justify-center items-center pointer-events-none h-full">
        <img 
            id="previewImage" 
            src="" 
            alt="Document Preview" 
            class="max-h-full max-w-full rounded-2xl shadow-2xl object-contain pointer-events-auto scale-95 opacity-0 transition-all duration-300 ease-out border border-white/10"
        >
    </div>
</div>

<!-- JavaScript Logic -->
<script>
    // --- Stats Counter Animation ---
    document.addEventListener('DOMContentLoaded', () => {
        const counters = document.querySelectorAll('.stat-counter');
        const speed = 25;

        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            if (target === 0) return;

            const inc = target / speed;
            let current = 0;

            const updateCount = () => {
                current += inc;
                if (current < target) {
                    counter.innerText = Math.ceil(current);
                    requestAnimationFrame(updateCount);
                } else {
                    counter.innerText = target;
                }
            };
            updateCount();
        });
    });

    // --- Action Button Loading State Handler ---
    function setButtonLoading(form) {
        const btn = form.querySelector('.action-btn');
        if (btn) {
            btn.disabled = true;
            const textEl = btn.querySelector('.btn-text');
            const spinnerEl = btn.querySelector('.btn-spinner');
            if (textEl) textEl.style.opacity = '0.7';
            if (spinnerEl) spinnerEl.classList.remove('hidden');
        }
    }

    // --- Frontend Search & Filter Logic with Clear Icon Toggle ---
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearSearchBtn');
        const statusFilter = document.getElementById('statusFilter');
        const cards = document.querySelectorAll('.request-card');
        const jsEmptyState = document.getElementById('jsEmptyState');

        function filterRequests() {
            const searchTerm = searchInput.value.toLowerCase();
            const status = statusFilter.value;
            let visibleCount = 0;

            if (searchTerm.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                const email = card.getAttribute('data-email');
                const cardStatus = card.getAttribute('data-status');

                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                const matchesStatus = status === 'all' || cardStatus === status;

                if (matchesSearch && matchesStatus) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (cards.length > 0) {
                if (visibleCount === 0) {
                    jsEmptyState.classList.remove('hidden');
                    jsEmptyState.classList.add('flex');
                } else {
                    jsEmptyState.classList.add('hidden');
                    jsEmptyState.classList.remove('flex');
                }
            }
        }

        searchInput.addEventListener('input', filterRequests);
        statusFilter.addEventListener('change', filterRequests);
    });

    // --- Image Preview Modal Logic ---
    function openPreviewModal(url) {
        const modal = document.getElementById('imagePreviewModal');
        const backdrop = document.getElementById('previewBackdrop');
        const img = document.getElementById('previewImage');
        
        img.src = url;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        void modal.offsetWidth;
        
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
        img.classList.remove('scale-95', 'opacity-0');
        img.classList.add('scale-100', 'opacity-100');
        
        document.body.style.overflow = 'hidden';
    }

    function closePreviewModal() {
        const modal = document.getElementById('imagePreviewModal');
        const backdrop = document.getElementById('previewBackdrop');
        const img = document.getElementById('previewImage');
        
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        img.classList.remove('scale-100', 'opacity-100');
        img.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            img.src = '';
            document.body.style.overflow = '';
        }, 300);
    }

    // --- Reject Modal Logic ---
    function openRejectModal(actionUrl) {
        const modal = document.getElementById('rejectModal');
        const backdrop = document.getElementById('rejectModalBackdrop');
        const panel = document.getElementById('rejectModalPanel');
        const form = document.getElementById('rejectForm');
        
        form.action = actionUrl;
        form.reset();
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        void modal.offsetWidth;
        
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
        panel.classList.remove('opacity-0', 'translate-y-3', 'sm:translate-y-0', 'scale-95');
        panel.classList.add('opacity-100', 'translate-y-0', 'scale-100');
        
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            document.getElementById('reason').focus();
        }, 200);
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        const backdrop = document.getElementById('rejectModalBackdrop');
        const panel = document.getElementById('rejectModalPanel');
        
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        panel.classList.remove('opacity-100', 'translate-y-0', 'scale-100');
        panel.classList.add('opacity-0', 'translate-y-3', 'sm:translate-y-0', 'scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('rejectForm').action = '';
            
            if(document.getElementById('imagePreviewModal').classList.contains('hidden')) {
                document.body.style.overflow = '';
            }
        }, 300);
    }

    // Escape Key Shortcut
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const previewModal = document.getElementById('imagePreviewModal');
            const rejectModal = document.getElementById('rejectModal');
            
            if (!previewModal.classList.contains('hidden')) {
                closePreviewModal();
            } else if (!rejectModal.classList.contains('hidden')) {
                closeRejectModal();
            }
        }
    });
</script>

</x-app-layout>