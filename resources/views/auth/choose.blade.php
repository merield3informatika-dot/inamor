<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to INAMOR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'enterprise': '0 20px 40px -15px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(0, 0, 0, 0.03)',
                        'enterprise-hover': '0 30px 60px -15px rgba(79, 70, 229, 0.1), 0 0 0 1px rgba(79, 70, 229, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.9)',
                        'primary-glow': '0 0 50px -10px rgba(79, 70, 229, 0.25)',
                        'logo-shadow': '0 4px 16px -4px rgba(79, 70, 229, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.6)',
                    }
                }
            }
        }
    </script>
    <style>
        /* Cinematic & Luxury Keyframes */
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-8px) rotate(1deg); }
        }
        @keyframes floatSlowReverse {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(8px) rotate(-1deg); }
        }
        @keyframes pulseGlow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.05); }
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .animate-float { animation: floatSlow 6s ease-in-out infinite; }
        .animate-float-delayed { animation: floatSlowReverse 7s ease-in-out infinite 1s; }
        .animate-pulse-glow { animation: pulseGlow 8s ease-in-out infinite; }
        
        .shimmer-effect::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transform: translateX(-100%);
            animation: shimmer 3s infinite;
        }

        .text-gradient {
            background: linear-gradient(135deg, #111827 0%, #374151 50%, #4F46E5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-white text-gray-900 selection:bg-indigo-100 selection:text-indigo-900 relative overflow-x-hidden font-sans">

    <!-- Ambient Enterprise Background (80% White, 15% Brand, 5% Ambient) -->
    <div class="absolute inset-0 pointer-events-none flex justify-center z-0 overflow-hidden">
        <!-- Floating Colorful Blobs & Ambient Lights -->
        <div class="absolute top-[-10%] left-[20%] w-[500px] h-[500px] bg-gradient-to-tr from-blue-400/10 via-indigo-400/10 to-purple-400/10 blur-[120px] rounded-full animate-pulse-glow"></div>
        <div class="absolute top-[30%] right-[10%] w-[400px] h-[400px] bg-gradient-to-br from-cyan-400/10 via-sky-400/15 to-blue-500/10 blur-[100px] rounded-full animate-pulse-glow" style="animation-delay: 3s;"></div>
        <div class="absolute bottom-[10%] left-[15%] w-[450px] h-[450px] bg-gradient-to-tr from-purple-400/10 via-pink-400/5 to-indigo-400/10 blur-[130px] rounded-full animate-pulse-glow" style="animation-delay: 5s;"></div>
        
        <!-- Subtle mesh light grid -->
        <div class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:32px_32px] opacity-40"></div>
    </div>

    <!-- Floating Decorative Elements (Nodes/Dots) -->
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute top-24 left-[12%] w-2 h-2 rounded-full bg-blue-500/40 animate-float"></div>
        <div class="absolute top-40 right-[15%] w-3 h-3 rounded-full bg-purple-500/30 animate-float-delayed"></div>
        <div class="absolute bottom-32 left-[18%] w-2.5 h-2.5 rounded-full bg-cyan-500/40 animate-float"></div>
        <div class="absolute bottom-20 right-[20%] w-2 h-2 rounded-full bg-indigo-500/40 animate-float-delayed"></div>
    </div>

    <!-- Main Container -->
    <main class="relative z-10 flex-1 flex flex-col items-center justify-center w-full px-6 py-16">
        
        <!-- Header -->
        <header class="flex flex-col items-center text-center w-full max-w-xl mx-auto">
            
            <!-- Premium Enterprise Logo -->
            <div class="relative w-14 h-14 rounded-[16px] bg-gradient-to-br from-indigo-600 via-blue-600 to-purple-600 p-[1px] shadow-logo mb-6 group animate-float">
                <div class="w-full h-full bg-white rounded-[15px] flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-blue-50/50 opacity-50"></div>
                    <svg class="w-6 h-6 text-indigo-600 relative z-10 transform transition-transform duration-500 group-hover:rotate-12" viewBox="0 0 24 24" fill="currentColor">
                        <polygon points="12 2 22 8 22 16 12 22 2 16 2 8" opacity="0.2"/>
                        <polygon points="12 4 19 8.2 19 15.8 12 20 5 15.8 5 8.2" fill="currentColor"/>
                        <circle cx="12" cy="12" r="3" fill="white"/>
                    </svg>
                </div>
            </div>

            <!-- Glowing Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/80 border border-indigo-100 shadow-sm backdrop-blur-md text-[11px] font-semibold text-indigo-600 tracking-wide uppercase mb-6">
                <span class="w-2 h-2 rounded-full bg-indigo-600 animate-ping"></span>
                Enterprise AI Workspace
            </div>

            <h1 class="text-3xl md:text-5xl font-bold tracking-tight text-gray-900 mb-4">
                Welcome to <span class="text-gradient">INAMOR</span>
            </h1>
            
            <p class="text-[15px] md:text-base text-gray-500 leading-relaxed font-medium">
                Start a new organization or join one you've been invited to.
            </p>

            <!-- Subtle Visual Separator with Accent Glow -->
            <div class="relative w-full max-w-xs flex items-center justify-center mt-8 mb-10">
                <div class="w-full h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                <div class="absolute w-12 h-px bg-gradient-to-r from-transparent via-indigo-500 to-transparent"></div>
            </div>
        </header>

        <!-- Cards Container -->
        <div class="w-full max-w-[820px] grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- CARD 1: Start Organization (Primary with Ambient Glow & Border Sweep) -->
            <a href="{{ route('register', ['intent' => 'create']) }}" class="group relative flex flex-col p-8 rounded-[24px] bg-white border border-indigo-200/80 shadow-enterprise hover:shadow-enterprise-hover hover:-translate-y-1 transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-indigo-500/50 overflow-hidden">
                
                <!-- Ambient Card Glow Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 via-blue-50/20 to-transparent opacity-70 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-400/20 blur-3xl rounded-full group-hover:scale-150 transition-transform duration-500"></div>

                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-start justify-between mb-6">
                        <!-- Animated Icon Container -->
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-600 to-blue-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/30 transition-transform duration-300 ease-out group-hover:scale-110 group-hover:rotate-3">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 21V7.5A1.5 1.5 0 016 6h12a1.5 1.5 0 011.5 1.5V21M9 9h.008v.008H9V9zm0 3h.008v.008H9V12zm0 3h.008v.008H9V15zm6-6h.008v.008H15V9zm0 3h.008v.008H15V12zm0 3h.008v.008H15V15z" />
                            </svg>
                        </div>
                        
                        <!-- Floating Badge -->
                        <span class="px-2.5 py-1 rounded-full bg-indigo-50 border border-indigo-200/60 text-[10px] font-bold text-indigo-700 tracking-wider uppercase shadow-xs">
                            Recommended
                        </span>
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 mb-2.5 tracking-tight group-hover:text-indigo-600 transition-colors">
                        Start a New Organization
                    </h2>
                    
                    <p class="text-[14px] text-gray-500 leading-relaxed mb-8 flex-1">
                        Create a new organization, set up your AI Workspace, and invite your team members.
                    </p>

                    <!-- Linear-style Inline Action with Glow Arrow -->
                    <div class="mt-auto flex items-center text-[14px] font-semibold text-indigo-600 group-hover:text-indigo-700 transition-colors">
                        Start Organization
                        <svg class="w-4 h-4 ml-2 transition-transform duration-300 ease-out group-hover:translate-x-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                </div>
            </a>

            <!-- CARD 2: Join Organization (Neutral Glass Elegance) -->
            <a href="{{ route('login') }}" class="group relative flex flex-col p-8 rounded-[24px] bg-white/90 backdrop-blur-md border border-gray-200/80 shadow-enterprise hover:shadow-enterprise-hover hover:-translate-y-1 transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-gray-400/50 overflow-hidden">
                
                <div class="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-start justify-between mb-6">
                        <!-- Icon Container -->
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200/80 text-gray-700 flex items-center justify-center shadow-xs transition-transform duration-300 ease-out group-hover:scale-110 group-hover:-rotate-3">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 mb-2.5 tracking-tight group-hover:text-gray-700 transition-colors">
                        Join an Organization
                    </h2>
                    
                    <p class="text-[14px] text-gray-500 leading-relaxed mb-8 flex-1">
                        Use your invitation link, QR Code, or invitation code to securely join an existing organization.
                    </p>

                    <!-- Linear-style Inline Action -->
                    <div class="mt-auto flex items-center text-[14px] font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                        Join Organization
                        <svg class="w-4 h-4 ml-2 text-gray-400 transition-transform duration-300 ease-out group-hover:translate-x-1.5 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4-4m4-4H3" />
                        </svg>
                    </div>
                </div>
            </a>

        </div>

        <!-- Footer -->
        <footer class="w-full max-w-[820px] mt-16 pt-6 border-t border-gray-200/60 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm">
            <a href="{{ route('landing') }}" class="group flex items-center font-medium text-gray-400 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4 mr-1.5 transition-transform duration-300 ease-out group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Home
            </a>

            <div class="text-gray-500 font-medium">
                Already have an account? 
                <a href="{{ route('login') }}" class="ml-1 font-semibold text-gray-900 hover:text-indigo-600 transition-colors focus:outline-none focus:underline">
                    Sign In
                </a>
            </div>
        </footer>

    </main>
</body>
</html>