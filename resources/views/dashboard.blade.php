<x-app-layout>
    
    <!-- Welcome Heading -->
    <div class="mb-6">
        <h2 class="text-[28px] md:text-[32px] font-bold text-gray-900 tracking-tight">
            Selamat datang, <span class="text-blue-600">{{ explode(' ', auth()->user()->name ?? 'Pengguna')[0] }}!</span> <span class="inline-block origin-bottom-right hover:animate-wave cursor-default">👋</span>
        </h2>
        <p class="text-[14px] md:text-[15px] text-gray-500 mt-1">Inamor siap membantu pekerjaan Anda hari ini dengan AI yang cerdas dan aman.</p>
    </div>

    <!-- Dashboard Layout Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Left Main Column (Hero, Quick Actions, Docs & Activity) -->
        <div class="xl:col-span-2 flex flex-col gap-6">
            
            <x-dashboard.hero
                :workspace="$workspace"
                :stats="$stats"
            />

            <x-dashboard.quick-actions />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-dashboard.recent-documents :documents="$recentDocuments ?? []" />
                
                <x-dashboard.recent-activities :activities="$recentActivities ?? []" />
            </div>

        </div>

        <!-- Right Sidebar Column (Calendar, Announcements, Stats) -->
        <div class="xl:col-span-1 flex flex-col gap-6">
            <x-right-sidebar 
                :events="$upcomingEvents ?? []" 
                :announcements="$announcements ?? []" 
                :stats="$serviceStats ?? []" 
            />
        </div>

    </div>

    <!-- Footer -->
    <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-[13px] text-gray-500">© {{ now()->year }} Inamor. All rights reserved.</p>
        <div class="flex items-center gap-6 text-[13px] text-gray-500 font-medium">
            <a href="#" class="hover:text-gray-900 transition-colors">Bantuan</a>
            <span class="text-gray-300">•</span>
            <a href="#" class="hover:text-gray-900 transition-colors">Kebijakan Privasi</a>
            <span class="text-gray-300">•</span>
            <a href="#" class="hover:text-gray-900 transition-colors">Syarat & Ketentuan</a>
        </div>
    </div>

</x-app-layout>