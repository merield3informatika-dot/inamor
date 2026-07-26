<x-app-layout>
    <div class="pb-10">
        
        <!-- Page Header & Actions -->
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Knowledge Base</h1>
                <p class="mt-1 text-[14px] text-gray-500">Manage and organize your organization's documents for AI context.</p>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <!-- UI Only Search -->
                <div class="relative w-full sm:w-[240px] group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-[14px] w-[14px] text-gray-400 group-focus-within:text-indigo-500 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" class="block w-full h-[42px] pl-9 pr-3 text-[13px] border border-gray-200/80 rounded-[12px] bg-white shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05)] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="Search documents...">
                </div>

                <!-- Upload Button -->
                <a href="{{ route('documents.create') }}" class="flex h-[42px] shrink-0 items-center justify-center gap-2 rounded-[12px] bg-[#0F172A] px-4 text-[13px] font-medium text-white shadow-[0_1px_2px_rgba(0,0,0,0.1)] transition-colors hover:bg-[#1E293B] active:scale-[0.98]">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Upload Document
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-8 flex items-center gap-3 rounded-[12px] border border-emerald-200/60 bg-emerald-50/50 p-4 shadow-sm backdrop-blur-sm animate-in fade-in slide-in-from-top-2 duration-300">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-[14px] font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Documents Grid -->
        @if($documents->count() > 0)
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($documents as $document)
                    <div class="group relative flex flex-col justify-between rounded-[20px] border border-gray-200/80 bg-white p-5 shadow-[0_2px_8px_-2px_rgba(0,0,0,0.04)] transition-all duration-300 hover:-translate-y-1 hover:border-indigo-200 hover:shadow-[0_12px_24px_-4px_rgba(79,70,229,0.15)]">
                        
                        <!-- Card Header: Icon & Status -->
                        <div class="mb-4 flex items-start justify-between">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[12px] bg-indigo-50 text-indigo-600 transition-colors group-hover:bg-indigo-100">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>

                            @php
                                $status = strtolower($document->status);
                                $badgeClass = match($status) {
                                    'ready', 'success', 'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                    'pending', 'processing' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                    'failed', 'error' => 'bg-red-50 text-red-700 border-red-200/60',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200/60',
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-md border px-2 py-1 text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                                {{ $document->status }}
                            </span>
                        </div>

                        <!-- Card Body: Title & Meta -->
                        <div>
                            <h3 class="mb-3 text-[15px] font-semibold text-gray-900 line-clamp-2 transition-colors group-hover:text-indigo-600" title="{{ $document->title }}">
                                {{ $document->title }}
                            </h3>
                            
                            <div class="flex items-center justify-between text-[12px] font-medium text-gray-400">
                                <div class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                    </svg>
                                    {{ number_format($document->file_size / 1024, 2) }} KB
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $document->created_at->format('M d, Y') }}
                                </div>
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
                <p class="mb-8 max-w-sm text-[14px] text-gray-500">Upload your organization's manuals, guidelines, and reports to power up your AI assistant's knowledge base.</p>
                <a href="{{ route('documents.create') }}" class="flex h-[44px] items-center justify-center gap-2 rounded-[12px] bg-indigo-600 px-6 text-[14px] font-medium text-white shadow-[0_4px_12px_rgba(79,70,229,0.3)] transition-all hover:bg-indigo-700 hover:shadow-[0_6px_16px_rgba(79,70,229,0.4)] active:scale-[0.98]">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Upload Your First Document
                </a>
            </div>
        @endif

    </div>
</x-app-layout>