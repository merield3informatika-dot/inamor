<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Workspace Created - INAMOR</title>
    
    <!-- Fonts & Tailwind -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        /* =======================================================
           PREMIUM ANIMATION KEYFRAMES
           ======================================================= */
        
        /* 1. SVG Draw Animations */
        .path-circle {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: drawPath 0.7s cubic-bezier(0.65, 0, 0.35, 1) 0.3s forwards;
        }
        .path-check {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: drawPath 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.8s forwards;
        }
        @keyframes drawPath {
            to { stroke-dashoffset: 0; }
        }

        /* 2. Container Pop & Bounce */
        .animate-pop {
            transform: scale(0);
            opacity: 0;
            animation: popIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s forwards;
        }
        @keyframes popIn {
            0% { transform: scale(0.6); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* 3. Soft Glow & Fill Fade In */
        .glow-bg {
            background-color: transparent;
            border-color: transparent;
            box-shadow: none;
            animation: fadeInGlow 0.8s ease forwards 1.1s;
        }
        @keyframes fadeInGlow {
            to { 
                background-color: rgba(16, 185, 129, 0.08); 
                border-color: rgba(16, 185, 129, 0.2);
                box-shadow: 0 0 30px rgba(16, 185, 129, 0.15), inset 0 0 20px rgba(16, 185, 129, 0.05);
            }
        }

        /* 4. Infinite Smooth Pulse */
        .animate-pulse-glow {
            animation: pulseGlow 2.5s cubic-bezier(0.4, 0, 0.6, 1) 1.5s infinite;
        }
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.2); }
            70% { box-shadow: 0 0 0 25px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* 5. Staggered Text Reveal (Apple/Linear Feel) */
        .reveal-element {
            opacity: 0;
            transform: translateY(12px) scale(0.98);
            filter: blur(4px);
        }
        .reveal-delay-1 { animation: smoothReveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) 1.1s forwards; }
        .reveal-delay-2 { animation: smoothReveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) 1.25s forwards; }
        .reveal-delay-3 { animation: smoothReveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) 1.4s forwards; }
        .reveal-delay-4 { animation: smoothReveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) 1.55s forwards; }

        @keyframes smoothReveal {
            to { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
        }

        /* 6. Floating Background Orbs */
        .bg-orb-1 { animation: floatOrb1 15s ease-in-out infinite alternate; }
        .bg-orb-2 { animation: floatOrb2 18s ease-in-out infinite alternate; }
        
        @keyframes floatOrb1 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(40px, 80px) scale(1.1); }
        }
        @keyframes floatOrb2 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-50px, -60px) scale(1.05); }
        }
    </style>
</head>
<body 
    class="relative flex flex-col items-center justify-center min-h-screen bg-[#FCFCFC] overflow-hidden font-sans selection:bg-gray-200 text-slate-900"
    x-data="{ progress: 0 }"
    x-init="
        // Start progress bar animation after reveal
        setTimeout(() => progress = 100, 1500);
        // Execute smooth redirect once the progress bar completes
        setTimeout(() => window.location.href = '{{ route('dashboard') }}', 4300);
    "
>

    <!-- ========================================== -->
    <!-- BACKGROUND EFFECTS                         -->
    <!-- ========================================== -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <!-- Subtle Grid Pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#f0f0f0_1px,transparent_1px),linear-gradient(to_bottom,#f0f0f0_1px,transparent_1px)] bg-[size:3rem_3rem] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,#000_20%,transparent_100%)] opacity-40"></div>
        
        <!-- Floating Blur Orbs -->
        <div class="absolute top-[-20%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-emerald-100/40 mix-blend-multiply filter blur-[120px] bg-orb-1 opacity-60"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[50vw] h-[50vw] rounded-full bg-blue-50/50 mix-blend-multiply filter blur-[100px] bg-orb-2 opacity-50"></div>
    </div>

    <!-- ========================================== -->
    <!-- MAIN CONTENT                               -->
    <!-- ========================================== -->
    <main class="relative z-10 flex flex-col items-center w-full max-w-lg px-6">
        
        <!-- Animated Draw Checkmark -->
        <div class="relative flex items-center justify-center w-24 h-24 mb-8 rounded-full border-2 border-transparent animate-pop glow-bg animate-pulse-glow">
            <svg class="w-11 h-11 text-emerald-500 overflow-visible drop-shadow-sm" viewBox="0 0 64 64" fill="none" stroke="currentColor">
                <!-- Circle Outline -->
                <circle class="path-circle" cx="32" cy="32" r="30" stroke-width="4" stroke-linecap="round" pathLength="100" />
                <!-- Checkmark Path -->
                <path class="path-check" d="M 18 34 L 27 43 L 48 21" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" pathLength="100" />
            </svg>
        </div>

        <!-- Staggered Title -->
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 reveal-element reveal-delay-1 text-center">
            Workspace Created
        </h1>

        <!-- Premium Details Box -->
        <div class="w-full mt-8 reveal-element reveal-delay-2 group">
            <div class="relative overflow-hidden bg-white/70 backdrop-blur-xl border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] rounded-2xl p-6 transition-all duration-500 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:bg-white/90">
                <div class="absolute inset-0 bg-gradient-to-br from-white/40 to-transparent pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col items-center text-center">
                    <!-- Workspace Initial / Logo fallback -->
                    <div class="w-12 h-12 mb-4 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-md">
                        @if($workspace->logo)
                            <img src="{{ Storage::url($workspace->logo) }}" alt="{{ $workspace->name }}" class="w-full h-full object-cover rounded-xl">
                        @else
                            <span class="text-lg font-bold">{{ strtoupper(substr($workspace->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    
                    <h2 class="text-lg font-bold text-slate-900 leading-tight">
                        {{ $workspace->name }}
                    </h2>
                    
                    @if($workspace->description)
                        <p class="text-sm text-slate-500 mt-2 line-clamp-2 px-4 leading-relaxed font-medium">
                            {{ $workspace->description }}
                        </p>
                    @endif
                    
                    <div class="mt-4 flex items-center gap-1.5 px-3 py-1 bg-slate-50 border border-slate-100 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full {{ $workspace->visibility === 'private' ? 'bg-amber-400' : 'bg-emerald-400' }}"></span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">
                            {{ $workspace->visibility }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="w-full max-w-[240px] mt-14 flex flex-col items-center reveal-element reveal-delay-3">
            <div class="flex items-center justify-between w-full mb-3 text-[10px] font-bold tracking-[0.2em] text-slate-400 uppercase">
                <span>Preparing Engine</span>
            </div>
            
            <!-- Elegant Loading Line -->
            <div class="w-full h-[3px] bg-slate-200/60 rounded-full overflow-hidden">
                <div 
                    class="h-full bg-slate-900 rounded-full transition-all ease-[cubic-bezier(0.65,0,0.35,1)]"
                    style="width: 0%;"
                    :style="`width: ${progress}%; transition-duration: 2600ms;`"
                ></div>
            </div>
            
            <p class="text-[11px] font-medium text-slate-400 mt-4 tracking-wide">
                Redirecting automatically...
            </p>
        </div>

        <!-- Fallback Action -->
        <div class="mt-8 reveal-element reveal-delay-4">
            <a 
                href="{{ route('dashboard') }}" 
                class="group flex items-center gap-2 px-4 py-2 text-[11px] font-bold uppercase tracking-wider text-slate-400 hover:text-slate-800 transition-colors duration-300"
            >
                <span>Skip to Dashboard</span>
                <svg class="w-3.5 h-3.5 transform transition-transform duration-300 group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>

    </main>

</body>
</html>