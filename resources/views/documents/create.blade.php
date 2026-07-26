<x-app-layout>
    <div class="pb-10 pt-4">
        
        <div class="mx-auto max-w-2xl">
            <!-- Header -->
            <div class="mb-8 flex items-center gap-4">
                <a href="{{ route('documents.index') }}" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-gray-200/80 bg-white text-gray-400 shadow-sm transition-colors hover:bg-gray-50 hover:text-gray-900">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Upload Document</h1>
                    <p class="mt-1 text-[14px] text-gray-500">Add a new document to your organization's AI knowledge base.</p>
                </div>
            </div>

            <!-- Upload Form -->
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="relative overflow-hidden rounded-[24px] border border-gray-200/80 bg-white shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
                
                @csrf

                <!-- Decorative Background Glow -->
                <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-indigo-50/50 blur-3xl"></div>

                <div class="relative p-6 sm:p-10 space-y-8">
                    
                    <!-- Title Input -->
                    <div>
                        <label for="title" class="mb-2.5 block text-[13px] font-semibold text-gray-900">
                            Document Title
                        </label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title"
                            class="block w-full h-[44px] rounded-[12px] border border-gray-200/80 bg-gray-50/50 px-4 text-[14px] text-gray-900 placeholder-gray-400 shadow-sm transition-all focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                            placeholder="e.g., Q3 Financial Report 2026"
                            value="{{ old('title') }}"
                            required
                        >
                        @error('title')
                            <p class="mt-2 text-[13px] text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Input -->
                    <div>
                        <label class="mb-2.5 block text-[13px] font-semibold text-gray-900">
                            PDF File
                        </label>
                        
                        <div class="rounded-[16px] border border-gray-200/80 bg-gray-50/50 p-5 transition-colors hover:border-indigo-200 hover:bg-indigo-50/30">
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[10px] bg-white border border-gray-200 text-gray-400 shadow-sm">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <input 
                                        type="file" 
                                        name="document" 
                                        id="document"
                                        accept=".pdf"
                                        required
                                        class="block w-full cursor-pointer text-[13px] text-gray-500 transition-all
                                               file:mr-4 file:cursor-pointer file:rounded-[8px] file:border-0 
                                               file:bg-white file:px-4 file:py-2.5 file:text-[13px] 
                                               file:font-semibold file:text-indigo-600 file:shadow-sm file:ring-1 file:ring-gray-200 
                                               hover:file:bg-gray-50 hover:file:text-indigo-700"
                                    >
                                    <p class="mt-2.5 text-[12px] text-gray-500">
                                        Upload a valid PDF document. This file will be processed and indexed by the AI.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @error('document')
                            <p class="mt-2 text-[13px] text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Form Actions -->
                <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-5 sm:flex sm:flex-row-reverse sm:px-10">
                    <button type="submit" class="flex h-[42px] w-full items-center justify-center gap-2 rounded-[12px] bg-[#0F172A] px-8 text-[14px] font-medium text-white shadow-[0_1px_2px_rgba(0,0,0,0.1)] transition-all hover:bg-[#1E293B] active:scale-[0.98] sm:w-auto sm:ml-3">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                        </svg>
                        Upload to Knowledge Base
                    </button>
                    <a href="{{ route('documents.index') }}" class="mt-3 flex h-[42px] w-full items-center justify-center rounded-[12px] bg-white px-6 text-[14px] font-medium text-gray-700 border border-gray-200/80 shadow-sm transition-colors hover:bg-gray-50 hover:text-gray-900 sm:mt-0 sm:w-auto">
                        Cancel
                    </a>
                </div>

            </form>
        </div>

    </div>
</x-app-layout>