<x-app-layout>
    <style>
        /* Subtle micro-animations for the premium feel */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes pulse-line {
            0%, 100% { opacity: 0.15; }
            50% { opacity: 0.6; }
        }
        .animate-pulse-line {
            animation: pulse-line 3s ease-in-out infinite;
        }
    </style>

    <div class="max-w-6xl mx-auto py-12 px-6 lg:px-8 min-h-[80vh] flex items-center" x-data="{ isSubmitting: false }">
        
        <div class="w-full grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">
            
            <!-- LEFT PANEL: Form & Content -->
            <div class="max-w-md w-full mx-auto lg:mx-0">
                
                <!-- Header -->
                <div class="mb-10">
                    <h1 class="text-3xl font-semibold text-gray-900 tracking-tight mb-2">
                        Create Workspace
                    </h1>
                    <p class="text-[14px] text-gray-500 leading-relaxed">
                        Set up a secure, isolated environment for your organization's AI knowledge, documents, and team collaboration.
                    </p>
                </div>

                <!-- Form -->
                <form 
                    action="{{ route('workspaces.store') }}" 
                    method="POST"
                    @submit="isSubmitting = true"
                    class="space-y-8"
                >
                    @csrf

                    <!-- Input Group -->
                    <div class="relative group">
                        <label for="name" class="block text-[13px] font-medium text-gray-900 mb-2">
                            Workspace Name
                        </label>
                        
                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name') }}"
                            class="block w-full px-4 py-2.5 text-[14px] bg-white border rounded-lg outline-none transition-all duration-300 shadow-sm
                                {{ $errors->has('name') 
                                    ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-4 focus:ring-red-500/10 focus:border-red-500' 
                                    : 'border-gray-200 text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 hover:border-gray-300' }}"
                            placeholder="e.g. Acme Corp, Engineering Team"
                            required
                            autofocus
                            autocomplete="off"
                        >

                        @error('name')
                            <p class="text-red-500 text-[13px] mt-2 font-medium">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Minimal Information List -->
                    <div class="space-y-3">
                        <h3 class="text-[12px] font-semibold text-gray-400 uppercase tracking-wider mb-4">
                            Workspace Defaults
                        </h3>
                        <div class="flex items-center text-[13px] text-gray-600">
                            <svg class="w-4 h-4 mr-3 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            You will be assigned as the Administrator
                        </div>
                        <div class="flex items-center text-[13px] text-gray-600">
                            <svg class="w-4 h-4 mr-3 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            The AI Knowledge Base will start empty
                        </div>
                        <div class="flex items-center text-[13px] text-gray-600">
                            <svg class="w-4 h-4 mr-3 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Team members can be invited after creation
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 flex items-center gap-4">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center px-5 py-2.5 text-[13px] font-medium text-white bg-gray-900 rounded-lg hover:bg-black focus:outline-none focus:ring-4 focus:ring-gray-900/10 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-[1px] disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none min-w-[140px]"
                            x-bind:disabled="isSubmitting"
                        >
                            <!-- Loading Spinner -->
                            <svg x-show="isSubmitting" style="display: none;" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            
                            <span x-text="isSubmitting ? 'Creating...' : 'Create Workspace'"></span>
                        </button>

                        <a href="{{ route('dashboard') }}" class="text-[13px] font-medium text-gray-500 hover:text-gray-900 transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <!-- RIGHT PANEL: Abstract Illustration -->
            <div class="hidden lg:flex items-center justify-center relative w-full h-full pointer-events-none select-none">
                
                <div class="relative w-full aspect-square max-w-[380px] animate-float">
                    
                    <!-- SVG Connection Lines -->
                    <svg class="absolute inset-0 w-full h-full text-gray-300" viewBox="0 0 400 400" fill="none" stroke="currentColor">
                        <!-- Top node to Document (Left) -->
                        <path d="M200 160 L100 260" stroke-width="1.2" stroke-dasharray="4 4" class="animate-pulse-line" style="animation-delay: 0ms;" />
                        <!-- Top node to Calendar (Center) -->
                        <path d="M200 160 L200 260" stroke-width="1.2" stroke-dasharray="4 4" class="animate-pulse-line" style="animation-delay: 400ms;" />
                        <!-- Top node to Team (Right) -->
                        <path d="M200 160 L300 260" stroke-width="1.2" stroke-dasharray="4 4" class="animate-pulse-line" style="animation-delay: 800ms;" />
                        
                        <!-- Document to Brain -->
                        <path d="M100 260 L200 360" stroke-width="1.2" stroke-dasharray="4 4" class="animate-pulse-line" style="animation-delay: 200ms;" />
                        <!-- Calendar to Brain -->
                        <path d="M200 260 L200 360" stroke-width="1.2" stroke-dasharray="4 4" class="animate-pulse-line" style="animation-delay: 600ms;" />
                        <!-- Team to Brain -->
                        <path d="M300 260 L200 360" stroke-width="1.2" stroke-dasharray="4 4" class="animate-pulse-line" style="animation-delay: 1000ms;" />
                    </svg>

                    <!-- HTML Nodes Layer -->
                    
                    <!-- 1. Top Badge (AI Workspace) -->
                    <div class="absolute top-[60px] left-1/2 -translate-x-1/2 flex items-center gap-2 bg-white border border-gray-200 rounded-full px-3.5 py-1.5 shadow-sm">
                        <svg class="w-3 h-3 text-indigo-500 animate-[pulse_4s_ease-in-out_infinite]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0l2.5 8.5L23 11l-8.5 2.5L12 22l-2.5-8.5L1 11l8.5-2.5L12 0z"/>
                        </svg>
                        <span class="text-[12px] font-medium text-gray-800 tracking-wide">AI Workspace</span>
                    </div>

                    <!-- 2. Center Core (○) -->
                    <div class="absolute top-[160px] left-1/2 -translate-x-1/2 -translate-y-1/2 w-10 h-10 bg-white border border-gray-200 rounded-full shadow-sm flex items-center justify-center transition-transform duration-500">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-indigo-50 to-blue-50 border border-indigo-100 flex items-center justify-center">
                            <div class="w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.5)]"></div>
                        </div>
                    </div>

                    <!-- 3. Left Node (Document 📄) -->
                    <div class="absolute top-[260px] left-[100px] -translate-x-1/2 -translate-y-1/2 w-12 h-12 bg-white border border-gray-200 rounded-xl shadow-sm flex items-center justify-center transition-all duration-300 pointer-events-auto hover:scale-110 hover:-translate-y-1 hover:shadow-md hover:border-blue-200 group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>

                    <!-- 4. Center Node (Calendar 📅) -->
                    <div class="absolute top-[260px] left-[200px] -translate-x-1/2 -translate-y-1/2 w-12 h-12 bg-white border border-gray-200 rounded-xl shadow-sm flex items-center justify-center transition-all duration-300 pointer-events-auto hover:scale-110 hover:-translate-y-1 hover:shadow-md hover:border-indigo-200 group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                        </svg>
                    </div>

                    <!-- 5. Right Node (Team 👥) -->
                    <div class="absolute top-[260px] left-[300px] -translate-x-1/2 -translate-y-1/2 w-12 h-12 bg-white border border-gray-200 rounded-xl shadow-sm flex items-center justify-center transition-all duration-300 pointer-events-auto hover:scale-110 hover:-translate-y-1 hover:shadow-md hover:border-purple-200 group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>

                    <!-- 6. Bottom Node (Brain / AI 🧠) -->
                    <div class="absolute top-[360px] left-[200px] -translate-x-1/2 -translate-y-1/2 w-14 h-14 bg-gray-900 border border-gray-800 rounded-2xl shadow-lg flex items-center justify-center transition-all duration-300 pointer-events-auto hover:scale-110 hover:-translate-y-1 hover:shadow-xl group overflow-hidden">
                        <!-- Subtle inner glow on hover -->
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 to-purple-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <!-- Abstract Brain/AI Chip Icon -->
                        <svg class="w-6 h-6 text-white relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>