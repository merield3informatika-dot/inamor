<x-layouts.onboarding 
    x-data="{ 
        files: [],
        handleFiles(e) {
            const uploadedFiles = e.target.files ? e.target.files : e.dataTransfer.files;
            this.files = Array.from(uploadedFiles);
            if(e.type === 'drop') this.$refs.fileInput.files = uploadedFiles;
        }
    }">

    <!-- LEFT: GUIDED SETUP -->
    <form id="knowledge-form" action="{{ route('onboarding.knowledge.store') }}" method="POST" enctype="multipart/form-data" class="w-full max-w-[400px] flex flex-col h-full">
        @csrf
        
        <div class="mb-10">
            <h1 class="text-[32px] font-semibold tracking-tight text-gray-900 mb-2">Seed Knowledge</h1>
            <p class="text-[15px] text-gray-500">Upload internal documents to build your AI's context. Or skip and do this later.</p>
        </div>

        <div class="flex-1 flex flex-col justify-center pb-12">
            
            <!-- Immersive Drag & Drop Zone -->
            <div class="group relative flex w-full flex-col items-center justify-center rounded-3xl border-2 border-dashed border-gray-300 bg-gray-50/50 py-16 transition-all duration-300 hover:border-indigo-400 hover:bg-indigo-50/50"
                 @dragover.prevent="$el.classList.add('border-indigo-500', 'bg-indigo-50')"
                 @dragleave.prevent="$el.classList.remove('border-indigo-500', 'bg-indigo-50')"
                 @drop.prevent="$el.classList.remove('border-indigo-500', 'bg-indigo-50'); handleFiles($event)">
                 
                <input x-ref="fileInput" id="documents" name="documents[]" type="file" multiple accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.csv,.txt" @change="handleFiles" class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0" title="">
                
                <div class="mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-gray-900/5 transition-transform duration-300 group-hover:scale-110 group-hover:shadow-md">
                    <svg class="h-7 w-7 text-gray-400 transition-colors group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                </div>
                <h3 class="text-[16px] font-semibold text-gray-900">Drop documents here</h3>
                <p class="mt-2 text-[13px] text-gray-500">PDF, DOCX, CSV, TXT up to 50MB</p>
            </div>
            
            @error('documents') <p class="mt-4 text-[13px] text-red-500 text-center">{{ $message }}</p> @enderror

            <!-- Selected Files List (Micro-interaction) -->
            <div class="mt-6 space-y-2" x-show="files.length > 0" x-cloak
                 x-transition:enter="transition-all ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <template x-for="(file, index) in files" :key="index">
                    <div class="flex items-center gap-3 rounded-xl border border-gray-100 bg-white p-3 shadow-sm">
                        <div class="h-8 w-8 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                            <svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="truncate text-[13px] font-medium text-gray-900 flex-1" x-text="file.name"></span>
                        <span class="text-[11px] font-mono text-gray-400" x-text="(file.size / (1024*1024)).toFixed(1) + ' MB'"></span>
                    </div>
                </template>
            </div>
        </div>
    </form>

    <!-- FOOTER INJECTION -->
    <x-slot:footer>
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('onboarding.privacy') }}" class="text-[14px] font-medium text-gray-500 transition-colors hover:text-gray-900 px-2 py-2">
                &larr; Back
            </a>
            <button onclick="document.getElementById('knowledge-form').submit()" 
                    class="flex items-center justify-center rounded-xl bg-indigo-600 px-8 py-4 text-[15px] font-semibold text-white shadow-[0_4px_20px_0_rgba(79,70,229,0.25)] transition-all hover:bg-indigo-700 hover:shadow-[0_6px_25px_rgba(79,70,229,0.3)] active:scale-[0.98]">
                Complete Setup
            </button>
        </div>
    </x-slot:footer>

    <!-- RIGHT: VECTOR ENGINE VISUALIZER -->
    <x-slot:preview>
        <!-- Terminal / Engine Window -->
        <div class="w-full h-[540px] rounded-2xl bg-[#0F0F12] border border-[#27272A] shadow-[0_40px_100px_-20px_rgba(0,0,0,0.6)] flex flex-col overflow-hidden font-['IBM_Plex_Mono',monospace]"
             x-transition:enter="transition-all ease-out-expo duration-1000 delay-100"
             x-transition:enter-start="opacity-0 translate-y-12 scale-[0.95] rotateX(-5deg)">
            
            <div class="flex h-10 items-center border-b border-[#27272A] px-4 gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span class="text-[10px] text-gray-400 tracking-widest uppercase">INAMOR_VECTOR_ENGINE_v1.0</span>
            </div>

            <div class="flex-1 relative p-8 flex flex-col justify-center items-center text-center">
                
                <!-- AWAITING STATE -->
                <div x-show="files.length === 0" 
                     x-transition:enter="transition-opacity duration-300" 
                     x-transition:leave="transition-opacity duration-200 absolute inset-0 flex flex-col items-center justify-center"
                     class="w-full max-w-sm">
                    <div class="h-16 w-16 rounded-full border border-gray-800 flex items-center justify-center mx-auto mb-6">
                        <div class="h-2 w-2 rounded-full bg-gray-600 animate-ping"></div>
                    </div>
                    <p class="text-[13px] text-gray-400">System idle. Awaiting document ingestion.</p>
                </div>

                <!-- PROCESSING STATE (Activates instantly on file drop) -->
                <div x-show="files.length > 0" x-cloak
                     x-transition:enter="transition-all ease-out-expo duration-700 delay-300"
                     x-transition:enter-start="opacity-0 translate-y-8"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="w-full max-w-md text-left">
                     
                    <div class="mb-8 flex items-center justify-between">
                        <span class="text-[11px] text-indigo-400">INITIATING PIPELINE...</span>
                        <span class="text-[11px] text-gray-500" x-text="files.length + ' Object(s)'"></span>
                    </div>

                    <!-- Fake Progress Steps -->
                    <div class="space-y-6">
                        <!-- Step 1 -->
                        <div>
                            <div class="flex justify-between text-[11px] mb-2">
                                <span class="text-white">1. Extracting text & OCR</span>
                                <span class="text-green-400">DONE</span>
                            </div>
                            <div class="h-[2px] w-full bg-[#27272A]"><div class="h-full w-full bg-green-500"></div></div>
                        </div>

                        <!-- Step 2 -->
                        <div>
                            <div class="flex justify-between text-[11px] mb-2">
                                <span class="text-white">2. Generating 1536-D Embeddings</span>
                                <span class="text-indigo-400 animate-pulse">PROCESSING</span>
                            </div>
                            <div class="h-[2px] w-full bg-[#27272A] relative overflow-hidden">
                                <!-- Infinite slide animation -->
                                <div class="absolute h-full w-1/3 bg-indigo-500 animate-[slideRight_1.5s_ease-in-out_infinite]"></div>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div>
                            <div class="flex justify-between text-[11px] mb-2">
                                <span class="text-gray-500">3. Building Vector Index</span>
                                <span class="text-gray-600">WAITING</span>
                            </div>
                            <div class="h-[2px] w-full bg-[#27272A]"></div>
                        </div>
                    </div>
                    
                    <!-- Animated Data Grid -->
                    <div class="mt-10 grid grid-cols-8 gap-1 opacity-20">
                        <!-- Generates 32 fake processing dots -->
                        <template x-for="i in 32" :key="i">
                            <div class="h-2 w-full rounded-[1px] bg-indigo-500" :class="Math.random() > 0.5 ? 'animate-pulse' : ''" :style="`animation-delay: ${Math.random() * 2}s`"></div>
                        </template>
                    </div>

                </div>

            </div>
        </div>

        <style>
            @keyframes slideRight {
                0% { left: -100%; }
                100% { left: 100%; }
            }
        </style>
    </x-slot:preview>
</x-layouts.onboarding>