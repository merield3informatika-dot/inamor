<div class="mb-10">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-5 px-1">
        <h3 class="text-[15px] font-bold text-gray-900 tracking-tight flex items-center gap-2">
            Akses Cepat
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500/80 animate-pulse"></span>
        </h3>
    </div>
    
    <!-- 5-Column Compact Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        
        <!-- 1. Dokumen & Arsip (RED) -->
        <a href="{{ route('documents.index') }}" class="group relative flex flex-col h-[155px] bg-white rounded-[24px] p-5 border border-gray-200/80 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.02)] hover:shadow-[0_24px_48px_-12px_rgba(239,68,68,0.25)] hover:border-red-300/80 transition-all duration-500 ease-out hover:-translate-y-1.5 overflow-hidden focus:outline-none focus:ring-4 focus:ring-red-500/10">
            
            <!-- Shimmer Light Beam -->
            <div class="absolute inset-0 overflow-hidden z-0 pointer-events-none">
                <div class="absolute top-0 bottom-0 w-[150%] bg-gradient-to-r from-transparent via-white/80 to-transparent -translate-x-[150%] group-hover:translate-x-[150%] skew-x-[-20deg] transition-transform duration-[1.2s] ease-in-out z-10"></div>
            </div>

            <!-- Ambient Glow & Dot Matrix -->
            <div class="absolute inset-0 bg-gradient-to-br from-red-50/40 via-transparent to-red-100/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0"></div>
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none z-0 [mask-image:radial-gradient(ellipse_at_center,black_40%,transparent_70%)]" style="background-image: radial-gradient(circle at 1px 1px, rgba(239,68,68,0.25) 1px, transparent 0); background-size: 12px 12px;"></div>
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-red-400/15 blur-2xl rounded-full group-hover:scale-[1.8] group-hover:-translate-x-4 group-hover:translate-y-4 transition-all duration-700 ease-out opacity-0 group-hover:opacity-100 pointer-events-none z-0"></div>

            <!-- Top Inner Glass Reflection -->
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-red-400/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col h-full justify-between">
                <!-- Hover Dynamic Icon -->
                <div class="w-11 h-11 rounded-[14px] bg-red-50 border border-red-100/50 flex items-center justify-center text-red-500 shadow-sm transition-all duration-500 group-hover:bg-red-500 group-hover:text-white group-hover:border-red-400 group-hover:shadow-[0_8px_20px_-6px_rgba(239,68,68,0.7)] group-hover:scale-110 group-hover:-rotate-6 group-hover:-translate-y-1 group-hover:translate-x-1">
                    <svg class="w-5 h-5 transition-transform duration-500 group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>

                <!-- Text & Action Reveal -->
                <div class="flex items-end justify-between mt-auto">
                    <h4 class="text-[14px] font-bold text-gray-800 tracking-tight leading-[1.2] transition-all duration-500 group-hover:text-red-950 group-hover:translate-x-1 group-hover:-translate-y-0.5">
                        Dokumen &Arsip
                    </h4>
                    <!-- Slide-in Arrow -->
                    <div class="w-7 h-7 rounded-full bg-white border border-red-100 shadow-sm flex items-center justify-center text-red-500 opacity-0 -translate-x-6 rotate-[-45deg] group-hover:opacity-100 group-hover:translate-x-0 group-hover:rotate-0 transition-all duration-500 ease-out shrink-0">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </div>
            </div>
        </a>

        <!-- 2. Surat & Disposisi (ORANGE) -->
        <a href="#" class="group relative flex flex-col h-[155px] bg-white rounded-[24px] p-5 border border-gray-200/80 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.02)] hover:shadow-[0_24px_48px_-12px_rgba(249,115,22,0.25)] hover:border-orange-300/80 transition-all duration-500 ease-out hover:-translate-y-1.5 overflow-hidden focus:outline-none focus:ring-4 focus:ring-orange-500/10">
            <div class="absolute inset-0 overflow-hidden z-0 pointer-events-none"><div class="absolute top-0 bottom-0 w-[150%] bg-gradient-to-r from-transparent via-white/80 to-transparent -translate-x-[150%] group-hover:translate-x-[150%] skew-x-[-20deg] transition-transform duration-[1.2s] ease-in-out z-10"></div></div>
            <div class="absolute inset-0 bg-gradient-to-br from-orange-50/40 via-transparent to-orange-100/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0"></div>
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none z-0 [mask-image:radial-gradient(ellipse_at_center,black_40%,transparent_70%)]" style="background-image: radial-gradient(circle at 1px 1px, rgba(249,115,22,0.25) 1px, transparent 0); background-size: 12px 12px;"></div>
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-orange-400/15 blur-2xl rounded-full group-hover:scale-[1.8] group-hover:-translate-x-4 group-hover:translate-y-4 transition-all duration-700 ease-out opacity-0 group-hover:opacity-100 pointer-events-none z-0"></div>
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-orange-400/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>

            <div class="relative z-10 flex flex-col h-full justify-between">
                <div class="w-11 h-11 rounded-[14px] bg-orange-50 border border-orange-100/50 flex items-center justify-center text-orange-500 shadow-sm transition-all duration-500 group-hover:bg-orange-500 group-hover:text-white group-hover:border-orange-400 group-hover:shadow-[0_8px_20px_-6px_rgba(249,115,22,0.7)] group-hover:scale-110 group-hover:-rotate-6 group-hover:-translate-y-1 group-hover:translate-x-1">
                    <svg class="w-5 h-5 transition-transform duration-500 group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <div class="flex items-end justify-between mt-auto">
                    <h4 class="text-[14px] font-bold text-gray-800 tracking-tight leading-[1.2] transition-all duration-500 group-hover:text-orange-950 group-hover:translate-x-1 group-hover:-translate-y-0.5">
                        Surat &<br>Disposisi
                    </h4>
                    <div class="w-7 h-7 rounded-full bg-white border border-orange-100 shadow-sm flex items-center justify-center text-orange-500 opacity-0 -translate-x-6 rotate-[-45deg] group-hover:opacity-100 group-hover:translate-x-0 group-hover:rotate-0 transition-all duration-500 ease-out shrink-0">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </div>
            </div>
        </a>

        <!-- 3. AI Chat (PURPLE) -->
        <a href="{{ route('chat') }}" class="group relative flex flex-col h-[155px] bg-white rounded-[24px] p-5 border border-gray-200/80 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.02)] hover:shadow-[0_24px_48px_-12px_rgba(168,85,247,0.25)] hover:border-purple-300/80 transition-all duration-500 ease-out hover:-translate-y-1.5 overflow-hidden focus:outline-none focus:ring-4 focus:ring-purple-500/10">
            <div class="absolute inset-0 overflow-hidden z-0 pointer-events-none"><div class="absolute top-0 bottom-0 w-[150%] bg-gradient-to-r from-transparent via-white/80 to-transparent -translate-x-[150%] group-hover:translate-x-[150%] skew-x-[-20deg] transition-transform duration-[1.2s] ease-in-out z-10"></div></div>
            <div class="absolute inset-0 bg-gradient-to-br from-purple-50/40 via-transparent to-purple-100/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0"></div>
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none z-0 [mask-image:radial-gradient(ellipse_at_center,black_40%,transparent_70%)]" style="background-image: radial-gradient(circle at 1px 1px, rgba(168,85,247,0.25) 1px, transparent 0); background-size: 12px 12px;"></div>
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-purple-400/15 blur-2xl rounded-full group-hover:scale-[1.8] group-hover:-translate-x-4 group-hover:translate-y-4 transition-all duration-700 ease-out opacity-0 group-hover:opacity-100 pointer-events-none z-0"></div>
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-purple-400/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>

            <div class="relative z-10 flex flex-col h-full justify-between">
                <div class="w-11 h-11 rounded-[14px] bg-purple-50 border border-purple-100/50 flex items-center justify-center text-purple-600 shadow-sm transition-all duration-500 group-hover:bg-purple-500 group-hover:text-white group-hover:border-purple-400 group-hover:shadow-[0_8px_20px_-6px_rgba(168,85,247,0.7)] group-hover:scale-110 group-hover:-rotate-6 group-hover:-translate-y-1 group-hover:translate-x-1">
                    <svg class="w-5 h-5 transition-transform duration-500 group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <div class="flex items-end justify-between mt-auto">
                    <h4 class="text-[14px] font-bold text-gray-800 tracking-tight leading-[1.2] transition-all duration-500 group-hover:text-purple-950 group-hover:translate-x-1 group-hover:-translate-y-0.5">
                        Asisten<br>AI Chat
                    </h4>
                    <div class="w-7 h-7 rounded-full bg-white border border-purple-100 shadow-sm flex items-center justify-center text-purple-500 opacity-0 -translate-x-6 rotate-[-45deg] group-hover:opacity-100 group-hover:translate-x-0 group-hover:rotate-0 transition-all duration-500 ease-out shrink-0">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </div>
            </div>
        </a>

        <!-- 4. Kalender Kegiatan (BLUE) -->
        <a href="#" class="group relative flex flex-col h-[155px] bg-white rounded-[24px] p-5 border border-gray-200/80 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.02)] hover:shadow-[0_24px_48px_-12px_rgba(59,130,246,0.25)] hover:border-blue-300/80 transition-all duration-500 ease-out hover:-translate-y-1.5 overflow-hidden focus:outline-none focus:ring-4 focus:ring-blue-500/10">
            <div class="absolute inset-0 overflow-hidden z-0 pointer-events-none"><div class="absolute top-0 bottom-0 w-[150%] bg-gradient-to-r from-transparent via-white/80 to-transparent -translate-x-[150%] group-hover:translate-x-[150%] skew-x-[-20deg] transition-transform duration-[1.2s] ease-in-out z-10"></div></div>
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/40 via-transparent to-blue-100/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0"></div>
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none z-0 [mask-image:radial-gradient(ellipse_at_center,black_40%,transparent_70%)]" style="background-image: radial-gradient(circle at 1px 1px, rgba(59,130,246,0.25) 1px, transparent 0); background-size: 12px 12px;"></div>
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-400/15 blur-2xl rounded-full group-hover:scale-[1.8] group-hover:-translate-x-4 group-hover:translate-y-4 transition-all duration-700 ease-out opacity-0 group-hover:opacity-100 pointer-events-none z-0"></div>
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-blue-400/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>

            <div class="relative z-10 flex flex-col h-full justify-between">
                <div class="w-11 h-11 rounded-[14px] bg-blue-50 border border-blue-100/50 flex items-center justify-center text-blue-500 shadow-sm transition-all duration-500 group-hover:bg-blue-500 group-hover:text-white group-hover:border-blue-400 group-hover:shadow-[0_8px_20px_-6px_rgba(59,130,246,0.7)] group-hover:scale-110 group-hover:-rotate-6 group-hover:-translate-y-1 group-hover:translate-x-1">
                    <svg class="w-5 h-5 transition-transform duration-500 group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex items-end justify-between mt-auto">
                    <h4 class="text-[14px] font-bold text-gray-800 tracking-tight leading-[1.2] transition-all duration-500 group-hover:text-blue-950 group-hover:translate-x-1 group-hover:-translate-y-0.5">
                        Kalender<br>Kegiatan
                    </h4>
                    <div class="w-7 h-7 rounded-full bg-white border border-blue-100 shadow-sm flex items-center justify-center text-blue-500 opacity-0 -translate-x-6 rotate-[-45deg] group-hover:opacity-100 group-hover:translate-x-0 group-hover:rotate-0 transition-all duration-500 ease-out shrink-0">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </div>
            </div>
        </a>

        <!-- 5. Layanan Digital (GREEN) -->
        <a href="#" class="group relative flex flex-col h-[155px] bg-white rounded-[24px] p-5 border border-gray-200/80 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.02)] hover:shadow-[0_24px_48px_-12px_rgba(34,197,94,0.25)] hover:border-green-300/80 transition-all duration-500 ease-out hover:-translate-y-1.5 overflow-hidden focus:outline-none focus:ring-4 focus:ring-green-500/10">
            <div class="absolute inset-0 overflow-hidden z-0 pointer-events-none"><div class="absolute top-0 bottom-0 w-[150%] bg-gradient-to-r from-transparent via-white/80 to-transparent -translate-x-[150%] group-hover:translate-x-[150%] skew-x-[-20deg] transition-transform duration-[1.2s] ease-in-out z-10"></div></div>
            <div class="absolute inset-0 bg-gradient-to-br from-green-50/40 via-transparent to-green-100/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0"></div>
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none z-0 [mask-image:radial-gradient(ellipse_at_center,black_40%,transparent_70%)]" style="background-image: radial-gradient(circle at 1px 1px, rgba(34,197,94,0.25) 1px, transparent 0); background-size: 12px 12px;"></div>
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-green-400/15 blur-2xl rounded-full group-hover:scale-[1.8] group-hover:-translate-x-4 group-hover:translate-y-4 transition-all duration-700 ease-out opacity-0 group-hover:opacity-100 pointer-events-none z-0"></div>
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-green-400/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10"></div>

            <div class="relative z-10 flex flex-col h-full justify-between">
                <div class="w-11 h-11 rounded-[14px] bg-green-50 border border-green-100/50 flex items-center justify-center text-green-500 shadow-sm transition-all duration-500 group-hover:bg-green-500 group-hover:text-white group-hover:border-green-400 group-hover:shadow-[0_8px_20px_-6px_rgba(34,197,94,0.7)] group-hover:scale-110 group-hover:-rotate-6 group-hover:-translate-y-1 group-hover:translate-x-1">
                    <svg class="w-5 h-5 transition-transform duration-500 group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </div>
                <div class="flex items-end justify-between mt-auto">
                    <h4 class="text-[14px] font-bold text-gray-800 tracking-tight leading-[1.2] transition-all duration-500 group-hover:text-green-950 group-hover:translate-x-1 group-hover:-translate-y-0.5">
                        Layanan<br>Digital
                    </h4>
                    <div class="w-7 h-7 rounded-full bg-white border border-green-100 shadow-sm flex items-center justify-center text-green-500 opacity-0 -translate-x-6 rotate-[-45deg] group-hover:opacity-100 group-hover:translate-x-0 group-hover:rotate-0 transition-all duration-500 ease-out shrink-0">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </div>
            </div>
        </a>
        
    </div>
</div>