<x-layouts.onboarding 
    x-data="{ 
        name: '{{ old('name', $identity['name'] ?? '') }}', 
        bio: '{{ old('bio', $identity['bio'] ?? '') }}', 
        logoPreview: null,
        handleFileDrop(e) {
            const file = e.target.files ? e.target.files[0] : e.dataTransfer.files[0];
            if (file) {
                this.logoPreview = URL.createObjectURL(file);
                if(e.type === 'drop') this.$refs.fileInput.files = e.dataTransfer.files;
            }
        }
    }">

    <!-- LEFT: GUIDED SETUP -->
    <form id="identity-form" action="{{ route('onboarding.identity.store') }}" method="POST" enctype="multipart/form-data" class="w-full max-w-[400px]">
        @csrf
        
        <!-- Tightened heading gap -->
        <div class="mb-8">
            <h1 class="text-[28px] font-semibold tracking-tight text-gray-900 mb-1.5">Create your workspace</h1>
            <p class="text-[14px] text-gray-500">Design the identity of your AI knowledge platform.</p>
        </div>

        <!-- Reduced gap from space-y-8 to space-y-6 -->
        <div class="space-y-6">
            <!-- Premium Logo Upload -->
            <div>
                <label class="block text-[13px] font-medium text-gray-700 mb-2.5">Workspace Logo</label>
                <div class="group relative flex cursor-pointer items-center gap-4 rounded-2xl border border-dashed border-gray-300 p-4 transition-all duration-300 hover:border-indigo-400 hover:bg-indigo-50/30"
                     @dragover.prevent="$el.classList.add('border-indigo-500', 'bg-indigo-50')"
                     @dragleave.prevent="$el.classList.remove('border-indigo-500', 'bg-indigo-50')"
                     @drop.prevent="$el.classList.remove('border-indigo-500', 'bg-indigo-50'); handleFileDrop($event)">
                    
                    <input x-ref="fileInput" type="file" name="logo" accept="image/*" @change="handleFileDrop" class="absolute inset-0 z-10 w-full h-full opacity-0 cursor-pointer">
                    
                    <div class="relative flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-[14px] bg-white border border-gray-100 shadow-sm transition-transform duration-300 group-hover:scale-105">
                        <template x-if="logoPreview">
                            <img :src="logoPreview" class="h-full w-full object-cover">
                        </template>
                        <template x-if="!logoPreview">
                            <svg class="h-6 w-6 text-gray-400 transition-colors group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        </template>
                    </div>
                    <div>
                        <p class="text-[13px] font-medium text-gray-900 group-hover:text-indigo-600 transition-colors" x-text="logoPreview ? 'Replace image' : 'Upload an image'"></p>
                        <p class="text-[12px] text-gray-500 mt-0.5">Drag & drop or click to browse</p>
                    </div>
                </div>
            </div>

            <!-- Workspace Name -->
            <div class="relative group">
                <label for="name" class="block text-[13px] font-medium text-gray-700 mb-2">Workspace Name</label>
                <!-- Reduced vertical padding -->
                <input id="name" name="name" type="text" x-model="name" placeholder="Acme Corporation" 
                       class="w-full bg-white rounded-xl border border-gray-200 px-4 py-2.5 text-[14px] text-gray-900 shadow-sm transition-all duration-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 placeholder-gray-400 outline-none">
                @error('name') <p class="mt-2 text-[13px] text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Workspace Bio -->
            <div class="relative group">
                <label for="bio" class="block text-[13px] font-medium text-gray-700 mb-2">Description</label>
                <textarea id="bio" name="bio" x-model="bio" rows="2" placeholder="Central brain for engineering docs..." 
                          class="w-full resize-none bg-white rounded-xl border border-gray-200 px-4 py-2.5 text-[14px] text-gray-900 shadow-sm transition-all duration-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 placeholder-gray-400 outline-none"></textarea>
                @error('bio') <p class="mt-2 text-[13px] text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>
    </form>

    <!-- FOOTER INJECTION -->
    <x-slot:footer>
        <button onclick="document.getElementById('identity-form').submit()" 
                class="flex w-full items-center justify-center rounded-xl bg-[#16161A] px-6 py-3.5 text-[15px] font-semibold text-white shadow-[0_4px_14px_0_rgba(0,0,0,0.1)] transition-all hover:bg-black hover:shadow-[0_6px_20px_rgba(0,0,0,0.15)] active:scale-[0.98]">
            Continue
            <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>
    </x-slot:footer>

    <!-- RIGHT: LIVE AI PREVIEW (Unchanged logic, just maintaining structure) -->
    <x-slot:preview>
        <div class="animate-float w-full overflow-hidden rounded-[20px] bg-white border border-gray-200/60 shadow-[0_20px_80px_-20px_rgba(0,0,0,0.15)] flex flex-col"
             x-transition:enter="transition-all ease-out-expo duration-1000 delay-100"
             x-transition:enter-start="opacity-0 translate-y-12 scale-[0.95] rotateX(10deg)">
            
            <div class="flex h-12 items-center gap-2 border-b border-gray-100 bg-gray-50/80 px-4">
                <div class="h-3 w-3 rounded-full bg-red-400/80"></div>
                <div class="h-3 w-3 rounded-full bg-amber-400/80"></div>
                <div class="h-3 w-3 rounded-full bg-green-400/80"></div>
            </div>

            <div class="flex h-[480px]">
                <div class="w-56 border-r border-gray-100 bg-[#FAFAF9]/50 p-4 flex flex-col gap-6">
                    <div class="flex items-center gap-3 px-1">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition-all duration-300">
                            <template x-if="logoPreview"><img :src="logoPreview" class="h-full w-full object-cover"></template>
                            <template x-if="!logoPreview"><div class="h-full w-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-[14px]" x-text="name ? name.charAt(0).toUpperCase() : 'W'"></div></template>
                        </div>
                        <div class="truncate text-[13px] font-semibold text-gray-900 transition-all duration-200" x-text="name || 'Untitled Workspace'"></div>
                    </div>
                    
                    <div class="space-y-1">
                        <div class="h-8 w-full rounded-lg bg-indigo-50/50 flex items-center px-3 gap-3"><div class="h-4 w-4 rounded bg-indigo-200"></div><div class="h-2 w-16 rounded bg-indigo-200"></div></div>
                        <div class="h-8 w-full rounded-lg flex items-center px-3 gap-3"><div class="h-4 w-4 rounded bg-gray-200"></div><div class="h-2 w-20 rounded bg-gray-200"></div></div>
                        <div class="h-8 w-full rounded-lg flex items-center px-3 gap-3"><div class="h-4 w-4 rounded bg-gray-200"></div><div class="h-2 w-12 rounded bg-gray-200"></div></div>
                    </div>
                </div>

                <div class="flex-1 p-8 bg-white relative">
                    <div class="mb-8">
                        <h2 class="text-[22px] font-bold tracking-tight text-gray-900 transition-all duration-200" x-text="'Welcome to ' + (name || 'Workspace')"></h2>
                        <p class="mt-2 text-[14px] text-gray-500 leading-relaxed max-w-sm transition-all duration-200" x-text="bio || 'Set up your AI knowledge base to get started.'"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-gray-100 bg-[#FAFAF9] p-5 shadow-sm">
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest mb-4">AI Readiness</p>
                            <div class="flex items-center gap-3">
                                <span class="relative flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span></span>
                                <span class="text-[14px] font-medium text-gray-900">Awaiting Knowledge</span>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-gray-100 bg-[#FAFAF9] p-5 shadow-sm flex flex-col justify-between">
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest mb-4">Knowledge Graph</p>
                            <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden"><div class="h-full w-[10%] bg-indigo-500 rounded-full"></div></div>
                        </div>
                    </div>
                    
                    <div class="absolute bottom-8 left-8 right-8">
                        <div class="h-12 w-full rounded-xl border border-gray-200 bg-white shadow-[0_4px_20px_-10px_rgba(0,0,0,0.1)] flex items-center px-4 gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <div class="h-2 w-32 bg-gray-100 rounded"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:preview>
</x-layouts.onboarding>