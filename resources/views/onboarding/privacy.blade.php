<x-layouts.onboarding 
    x-data="{ visibility: '{{ old('visibility', $privacy['visibility'] ?? 'private') }}' }">

    <!-- LEFT: GUIDED SETUP -->
    <form id="privacy-form" action="{{ route('onboarding.privacy.store') }}" method="POST" class="w-full max-w-[400px] space-y-10">
        @csrf
        
        <div>
            <h1 class="text-[32px] font-semibold tracking-tight text-gray-900 mb-2">Access Control</h1>
            <p class="text-[15px] text-gray-500">Determine who can query your AI models and view knowledge.</p>
        </div>

        <div class="space-y-4">
            <!-- Private Card -->
            <label class="group relative flex cursor-pointer rounded-2xl border p-5 transition-all duration-300"
                   :class="visibility === 'private' ? 'border-indigo-600 bg-indigo-50/30 shadow-[0_4px_20px_rgba(79,70,229,0.08)] ring-1 ring-indigo-600' : 'border-gray-200 bg-white hover:border-indigo-300 hover:shadow-sm'">
                <input type="radio" name="visibility" value="private" x-model="visibility" class="peer sr-only">
                
                <div class="flex items-start gap-4">
                    <div class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full border-2 transition-colors duration-300"
                         :class="visibility === 'private' ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300 group-hover:border-indigo-400'">
                         <svg class="h-3 w-3 text-white" :class="visibility === 'private' ? 'opacity-100' : 'opacity-0'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-[15px] font-semibold text-gray-900">Private Workspace</h3>
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-bold tracking-wide text-gray-600 uppercase">Recommended</span>
                        </div>
                        <p class="text-[14px] text-gray-500 leading-relaxed">Strictly invite-only. Content is siloed and requires authentication to query.</p>
                    </div>
                </div>
            </label>

            <!-- Public Card -->
            <label class="group relative flex cursor-pointer rounded-2xl border p-5 transition-all duration-300"
                   :class="visibility === 'public' ? 'border-indigo-600 bg-indigo-50/30 shadow-[0_4px_20px_rgba(79,70,229,0.08)] ring-1 ring-indigo-600' : 'border-gray-200 bg-white hover:border-indigo-300 hover:shadow-sm'">
                <input type="radio" name="visibility" value="public" x-model="visibility" class="peer sr-only">
                
                <div class="flex items-start gap-4">
                    <div class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full border-2 transition-colors duration-300"
                         :class="visibility === 'public' ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300 group-hover:border-indigo-400'">
                         <svg class="h-3 w-3 text-white" :class="visibility === 'public' ? 'opacity-100' : 'opacity-0'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[15px] font-semibold text-gray-900 mb-1">Public Link</h3>
                        <p class="text-[14px] text-gray-500 leading-relaxed">Anyone with the unique link can join the workspace and search the index.</p>
                    </div>
                </div>
            </label>
        </div>
        
        @error('visibility') <p class="text-[13px] text-red-500">{{ $message }}</p> @enderror
    </form>

    <!-- FOOTER INJECTION -->
    <x-slot:footer>
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('onboarding.identity') }}" class="text-[14px] font-medium text-gray-500 transition-colors hover:text-gray-900 px-2 py-2">
                &larr; Back
            </a>
            <button onclick="document.getElementById('privacy-form').submit()" 
                    class="flex items-center justify-center rounded-xl bg-[#16161A] px-8 py-4 text-[15px] font-semibold text-white shadow-[0_4px_14px_0_rgba(0,0,0,0.1)] transition-all hover:bg-black hover:shadow-[0_6px_20px_rgba(0,0,0,0.15)] active:scale-[0.98]">
                Continue
            </button>
        </div>
    </x-slot:footer>

    <!-- RIGHT: SECURITY VISUALIZER -->
    <x-slot:preview>
        <div class="relative w-full h-[500px] rounded-[30px] bg-white border border-gray-200/60 shadow-[0_30px_100px_-20px_rgba(0,0,0,0.1)] overflow-hidden flex items-center justify-center">
            
            <!-- Grid Background Overlay -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>

            <!-- PRIVATE STATE ANIMATION -->
            <div x-show="visibility === 'private'" 
                 x-transition:enter="transition-all ease-out-expo duration-700 delay-100"
                 x-transition:enter-start="opacity-0 scale-[0.8] translate-y-8"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition-all ease-in duration-300 absolute"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-[0.9]"
                 class="relative z-10 flex flex-col items-center">
                 
                <!-- Glowing Shield Container -->
                <div class="relative flex h-32 w-32 items-center justify-center rounded-full bg-white shadow-[0_0_40px_rgba(79,70,229,0.15)] ring-1 ring-gray-100">
                    <div class="absolute inset-0 rounded-full border border-indigo-500 animate-[spin_8s_linear_infinite] border-dashed opacity-30"></div>
                    <svg class="h-12 w-12 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                
                <div class="mt-8 rounded-xl border border-gray-100 bg-white/80 backdrop-blur px-6 py-4 text-center shadow-sm">
                    <h4 class="text-[14px] font-bold text-gray-900 tracking-wide uppercase">End-to-End Secure</h4>
                    <p class="mt-1 text-[13px] text-gray-500">Access limited to authenticated team members.</p>
                </div>
            </div>

            <!-- PUBLIC STATE ANIMATION -->
            <div x-show="visibility === 'public'" x-cloak
                 x-transition:enter="transition-all ease-out-expo duration-700 delay-100"
                 x-transition:enter-start="opacity-0 scale-[0.8] translate-y-8"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition-all ease-in duration-300 absolute"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-[0.9]"
                 class="relative z-10 flex flex-col items-center">
                 
                <!-- Glowing Network Container -->
                <div class="relative flex h-32 w-32 items-center justify-center rounded-full bg-white shadow-[0_0_40px_rgba(34,197,94,0.15)] ring-1 ring-gray-100">
                    <!-- Fake network nodes radiating outward -->
                    <div class="absolute -left-12 top-4 h-2 w-16 rounded-full bg-gradient-to-r from-transparent to-green-300 opacity-50"></div>
                    <div class="absolute -right-12 bottom-4 h-2 w-16 rounded-full bg-gradient-to-l from-transparent to-green-300 opacity-50"></div>
                    <div class="absolute -top-12 left-1/2 h-16 w-2 -translate-x-1/2 rounded-full bg-gradient-to-b from-transparent to-green-300 opacity-50"></div>
                    <svg class="h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </div>
                
                <div class="mt-8 rounded-xl border border-gray-100 bg-white/80 backdrop-blur px-6 py-4 text-center shadow-sm w-72">
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-widest mb-2">Invite Link Generated</p>
                    <div class="h-8 w-full bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-center px-3 text-[12px] font-mono text-gray-500 truncate">
                        inamor.ai/join/v9x...
                    </div>
                </div>
            </div>

        </div>
    </x-slot:preview>
</x-layouts.onboarding>