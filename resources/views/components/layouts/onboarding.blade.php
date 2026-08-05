<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>INAMOR — AI Workspace Setup</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        /* Ultra-smooth SaaS easings */
        .ease-out-expo { transition-timing-function: cubic-bezier(0.16, 1, 0.3, 1); }
        .ease-in-out-expo { transition-timing-function: cubic-bezier(0.87, 0, 0.13, 1); }
        
        /* Premium custom scrollbar for the left panel (if ever needed on tiny screens) */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
        .custom-scroll:hover::-webkit-scrollbar-thumb { background: #D1D5DB; }

        /* Floating animations for preview */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        
        /* Ambient gradient rotation */
        @keyframes ambient {
            0% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.1); }
            100% { transform: rotate(360deg) scale(1); }
        }
        .animate-ambient { animation: ambient 20s linear infinite; }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden bg-[#FAFAF9] font-['Inter',sans-serif] text-[#16161A] antialiased selection:bg-indigo-500/30">

    <div {{ $attributes->merge(['class' => 'grid h-full w-full grid-cols-1 lg:grid-cols-[540px_1fr] relative']) }}>
        
        <!-- LEFT PANEL (Form & Guided Setup) -->
        <!-- Removed overflow-y-auto from here. Made Header & Footer shrink-0 to pin them. -->
        <div class="relative z-20 flex h-full w-full flex-col bg-white px-8 py-8 lg:px-14 lg:py-10 shadow-[20px_0_60px_-15px_rgba(0,0,0,0.05)] border-r border-gray-200/60 overflow-hidden">
            
            <!-- Header (Logo & Step) pinned to top -->
            <header class="flex items-center justify-between animate-fade-in-down shrink-0">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-[8px] bg-indigo-600 text-[11px] font-bold text-white shadow-[0_2px_10px_rgba(79,70,229,0.3)]">IN</div>
                    <span class="text-[14px] font-bold tracking-tight text-gray-900">INAMOR</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="h-1.5 w-8 rounded-full {{ request()->routeIs('onboarding.identity*') ? 'bg-indigo-600' : 'bg-gray-100' }} transition-colors duration-500"></div>
                    <div class="h-1.5 w-8 rounded-full {{ request()->routeIs('onboarding.privacy*') ? 'bg-indigo-600' : 'bg-gray-100' }} transition-colors duration-500"></div>
                    <div class="h-1.5 w-8 rounded-full {{ request()->routeIs('onboarding.knowledge*') ? 'bg-indigo-600' : 'bg-gray-100' }} transition-colors duration-500"></div>
                </div>
            </header>

            <!-- Main Form Content -->
            <!-- flex-1 + min-h-0 forces this container to take exact remaining space without pushing footer out. -->
            <!-- pt-8 pushes content down aesthetically without using justify-center (which causes clipping). -->
            <main class="flex-1 flex flex-col min-h-0 overflow-y-auto custom-scroll pt-8 lg:pt-10 pb-4">
                {{ $slot }}
            </main>

            <!-- Footer pinned to bottom -->
            <footer class="mt-4 lg:mt-6 shrink-0">
                {{ $footer ?? '' }}
            </footer>
        </div>

        <!-- RIGHT PANEL (Live Interactive Preview) -->
        <div class="relative z-10 hidden lg:flex h-full w-full items-center justify-center overflow-hidden bg-[#FAFAF9]">
            
            <div class="pointer-events-none absolute inset-0 overflow-hidden mix-blend-multiply">
                <div class="absolute -left-[10%] top-[10%] h-[600px] w-[600px] rounded-full bg-indigo-400/10 blur-[120px] animate-ambient"></div>
                <div class="absolute -right-[10%] bottom-[10%] h-[500px] w-[500px] rounded-full bg-blue-400/10 blur-[100px] animate-ambient" style="animation-direction: reverse;"></div>
                <div class="absolute inset-0 opacity-[0.25]" style="background-image:radial-gradient(#94A3B8 1px, transparent 1px); background-size:32px 32px; mask-image:radial-gradient(ellipse 90% 90% at 50% 50%, black 20%, transparent 100%); -webkit-mask-image:radial-gradient(ellipse 90% 90% at 50% 50%, black 20%, transparent 100%);"></div>
            </div>

            <div class="relative z-10 w-full max-w-[700px] px-12 perspective-[1000px]">
                {{ $preview ?? '' }}
            </div>
            
        </div>
    </div>
</body>
</html>