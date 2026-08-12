<x-app-layout>
    <div class="pb-12" x-data="documentManager()">
        
        <!-- Page Header & Actions -->
        <div class="mb-8 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Knowledge Base</h1>
                <p class="mt-1 text-[13.5px] text-gray-500">Manage and organize your organization's AI knowledge sources.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <!-- Status Filter -->
                <div class="relative w-full sm:w-auto">
                    <select x-model="filterStatus" class="block w-full sm:w-36 h-[42px] pl-3 pr-8 text-[13px] font-medium text-gray-700 border border-gray-200/80 rounded-[12px] bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 appearance-none cursor-pointer">
                        <option value="all">All Status</option>
                        <option value="ready">Ready</option>
                        <option value="pending">Processing</option>
                        <option value="archived">Archived</option>
                        <option value="failed">Failed</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- UI Search -->
                <div class="relative w-full sm:w-[240px] group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-[14px] w-[14px] text-gray-400 group-focus-within:text-indigo-500 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" x-model="searchQuery" class="block w-full h-[42px] pl-9 pr-3 text-[13px] border border-gray-200/80 rounded-[12px] bg-white shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="Search knowledge...">
                </div>

                <!-- Upload Button -->
                <a href="{{ route('documents.create') }}" class="flex w-full sm:w-auto h-[42px] shrink-0 items-center justify-center gap-2 rounded-[12px] bg-[#0F172A] px-5 text-[13px] font-medium text-white shadow-sm transition-all hover:bg-[#1E293B] active:scale-[0.98]">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Upload Document
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-8 flex items-center justify-between rounded-[12px] border border-emerald-200/60 bg-emerald-50/50 p-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <p class="text-[13.5px] font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-emerald-600 hover:text-emerald-800"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif

        <!-- Documents Grid -->
        @if($documents->count() > 0)
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($documents as $document)
                    @php
                        $status = strtolower($document->status);
                        $ext = strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION));
                        
                        $badgeClass = match($status) {
                            'ready' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                            'pending', 'processing' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                            'failed' => 'bg-red-50 text-red-700 border-red-200/60',
                            'archived' => 'bg-gray-100 text-gray-600 border-gray-200',
                            default => 'bg-gray-50 text-gray-700 border-gray-200/60',
                        };

                        $isProcessing = in_array($status, ['pending', 'processing']);
                    @endphp
                    
                    <div 
                        x-show="matchDocument('{{ strtolower($document->title) }}', '{{ $status }}', '{{ strtolower($ext) }}')"
                        class="group relative flex flex-col justify-between rounded-[20px] border border-gray-200/80 bg-white p-5 shadow-sm transition-all duration-300 {{ $status === 'archived' ? 'opacity-70 grayscale-[30%]' : 'hover:-translate-y-1 hover:border-indigo-200 hover:shadow-md' }}"
                    >
                        
                        <!-- Header: Icon, Badge, Actions -->
                        <div class="mb-4 flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[12px] {{ $status === 'archived' ? 'bg-gray-100 text-gray-500' : 'bg-indigo-50 text-indigo-600 group-hover:bg-indigo-100' }} transition-colors">
                                    <span class="text-[11px] font-bold tracking-wider">{{ $ext }}</span>
                                </div>
                                
                                <span class="inline-flex items-center gap-1.5 rounded-md border px-2 py-1 text-[9.5px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                                    @if($isProcessing)
                                        <svg class="h-2.5 w-2.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                    @endif
                                    {{ $document->status }}
                                </span>
                            </div>

                            <!-- 3-Dot Menu -->
                            <div class="relative" x-data="{ menuOpen: false }">
                                <button @click="menuOpen = !menuOpen" @click.away="menuOpen = false" class="p-1 text-gray-400 hover:text-gray-900 rounded-lg hover:bg-gray-50 transition-colors">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                </button>
                                
                                <div x-show="menuOpen" x-transition.opacity.duration.200ms class="absolute right-0 top-8 z-20 w-40 rounded-[12px] bg-white border border-gray-100 shadow-lg py-1" style="display: none;">
                                    @if(!$isProcessing && $status !== 'failed')
                                        <a href="{{ route('documents.show', $document) }}" class="block w-full text-left px-4 py-2 text-[13px] text-gray-700 hover:bg-gray-50">Preview Knowledge</a>
                                    @endif
                                    
                                    @if($status === 'ready')
                                        <form method="POST" action="{{ route('documents.archive', $document) }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-[13px] text-gray-700 hover:bg-gray-50">Archive</button>
                                        </form>
                                    @endif

                                    @if($status === 'archived')
                                        <form method="POST" action="{{ route('documents.restore', $document) }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-[13px] text-emerald-600 hover:bg-emerald-50 font-medium">Restore Access</button>
                                        </form>
                                    @endif
                                    
                                    @if(!$isProcessing)
                                        <button type="button" @click="confirmDelete('{{ route('documents.destroy', $document) }}')" class="block w-full text-left px-4 py-2 text-[13px] text-red-600 hover:bg-red-50">Delete Permanently</button>
                                    @else
                                        <div class="px-4 py-2 text-[12px] text-gray-400 italic">Processing...</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Body: Title -->
                        <div class="mb-4">
                            <h3 class="text-[14.5px] font-bold text-gray-900 line-clamp-2 leading-snug transition-colors group-hover:text-indigo-600" title="{{ $document->title }}">
                                {{ $document->title }}
                            </h3>
                        </div>
                        
                        <!-- Footer: Meta Details -->
                        <div class="mt-auto flex flex-col gap-2 border-t border-gray-50 pt-3">
                            <div class="flex items-center justify-between text-[11.5px] text-gray-500 font-medium">
                                <span>Size</span>
                                <span class="text-gray-900">{{ number_format($document->file_size / 1024, 2) }} KB</span>
                            </div>
                            
                            @if($document->content)
                                <div class="flex items-center justify-between text-[11.5px] text-gray-500 font-medium">
                                    <span>Method</span>
                                    <span class="text-indigo-600 uppercase">{{ $document->content->extraction_method }} {{ $document->content->ocr_used ? '(OCR)' : '' }}</span>
                                </div>
                                @if($document->content->confidence)
                                <div class="flex items-center justify-between text-[11.5px] text-gray-500 font-medium">
                                    <span>Confidence</span>
                                    <span class="text-gray-900">{{ number_format($document->content->confidence, 1) }}%</span>
                                </div>
                                @endif
                                @if($document->content->page_count)
                                <div class="flex items-center justify-between text-[11.5px] text-gray-500 font-medium">
                                    <span>Structure</span>
                                    <span class="text-gray-900">{{ $document->content->page_count }} items</span>
                                </div>
                                @endif
                            @endif

                            <div class="flex items-center justify-between text-[11.5px] text-gray-500 font-medium mt-1">
                                <span>Uploaded</span>
                                <span>{{ $document->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center rounded-[24px] border border-dashed border-gray-300/80 bg-white/50 px-6 py-24 text-center shadow-sm">
                <div class="relative mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-indigo-50 shadow-inner">
                    <div class="absolute -right-2 -top-2 h-6 w-6 animate-bounce rounded-full bg-indigo-100 flex items-center justify-center shadow-sm">
                        <svg class="h-3 w-3 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.6h8l-6.4 4.8 2.4 7.6-6.4-4.8-6.4 4.8 2.4-7.6-6.4-4.8h8z"/></svg>
                    </div>
                    <svg class="h-10 w-10 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-gray-900">No documents yet</h3>
                <p class="mb-8 max-w-sm text-[13.5px] text-gray-500">Upload your organization's manuals, guidelines, and reports to power up your AI assistant's knowledge base.</p>
                <a href="{{ route('documents.create') }}" class="flex h-[44px] items-center justify-center gap-2 rounded-[12px] bg-indigo-600 px-6 text-[14px] font-medium text-white shadow-[0_4px_12px_rgba(79,70,229,0.3)] transition-all hover:bg-indigo-700 hover:shadow-[0_6px_16px_rgba(79,70,229,0.4)] active:scale-[0.98]">
                    Upload Your First Document
                </a>
            </div>
        @endif

        <!-- Delete Modal -->
        <div x-show="deleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 px-4 backdrop-blur-sm" style="display: none;">
            <div @click.away="deleteModalOpen = false" class="w-full max-w-md rounded-[24px] bg-white p-6 shadow-2xl">
                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="mb-2 text-lg font-bold text-gray-900">Delete Permanently?</h3>
                <p class="mb-6 text-[13.5px] text-gray-500 leading-relaxed">This will permanently remove the document, its extracted content, and flush its knowledge vectors from the AI engine. This action cannot be undone.</p>
                
                <div class="flex gap-3 justify-end">
                    <button @click="deleteModalOpen = false" class="rounded-xl px-4 py-2.5 text-[13.5px] font-medium text-gray-700 bg-gray-50 hover:bg-gray-100">Cancel</button>
                    <form method="POST" :action="deleteActionUrl">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-xl px-4 py-2.5 text-[13.5px] font-medium text-white bg-red-600 hover:bg-red-700 shadow-sm">Delete Permanently</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('documentManager', () => ({
                searchQuery: '',
                filterStatus: 'all',
                deleteModalOpen: false,
                deleteActionUrl: '',

                matchDocument(title, status, ext) {
                    const matchesSearch = title.includes(this.searchQuery.toLowerCase()) || ext.includes(this.searchQuery.toLowerCase());
                    const matchesStatus = this.filterStatus === 'all' || this.filterStatus === status;
                    
                    return matchesSearch && matchesStatus;
                },

                confirmDelete(url) {
                    this.deleteActionUrl = url;
                    this.deleteModalOpen = true;
                }
            }))
        })
    </script>
</x-app-layout>