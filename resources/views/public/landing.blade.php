<!DOCTYPE html>
<html lang="en" class="scroll-smooth bg-[#FAFAFA]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INAMOR | Enterprise AI Knowledge Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#eff2ff', 100: '#e1e6ff', 200: '#c8d3ff', 300: '#a5b4ff',
                            400: '#7f8eff', 500: '#4F67F8', 600: '#3a4be0', 700: '#303bb4',
                            800: '#283393', 900: '#252e75',
                        },
                        ui: {
                            bg: '#FFFFFF', sec: '#F8FAFC', text: '#111827', muted: '#6B7280', border: '#E5E7EB',
                        }
                    },
                    boxShadow: {
                        'premium': '0 20px 40px -15px rgba(0,0,0,0.05), 0 0 0 1px rgba(0,0,0,0.02)',
                        'premium-hover': '0 30px 60px -20px rgba(0,0,0,0.12), 0 0 0 1px rgba(79,103,248,0.2)',
                        'glass': 'inset 0 1px 0 0 rgba(255,255,255,0.8), 0 4px 6px -1px rgba(0,0,0,0.02)',
                    },
                    transitionTimingFunction: {
                        'apple': 'cubic-bezier(0.16, 1, 0.3, 1)',
                        'smooth': 'cubic-bezier(0.25, 1, 0.5, 1)',
                    }
                }
            }
        }
    </script>
    <style>
        /* Premium Motion Design System (CSS-Only) */
        
        /* 1. Staggered Reveals */
        @keyframes revealUp {
            0% { opacity: 0; transform: translateY(30px) scale(0.98); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        
        @keyframes revealLine {
            0% { opacity: 0; transform: translateY(100%) rotate(2deg); transform-origin: left bottom; }
            100% { opacity: 1; transform: translateY(0) rotate(0deg); }
        }

        @keyframes scaleIn {
            0% { opacity: 0; transform: scale(0.95) translateY(20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* 2. Continuous Parallax & Floating */
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(0.5deg); }
        }

        @keyframes blobParallax {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }

        /* 3. Utility Classes */
        .animate-reveal-up { opacity: 0; animation: revealUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-reveal-line { opacity: 0; animation: revealLine 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-scale-in { opacity: 0; animation: scaleIn 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        .delay-500 { animation-delay: 500ms; }
        .delay-600 { animation-delay: 600ms; }
        .delay-700 { animation-delay: 700ms; }
        .delay-800 { animation-delay: 800ms; }

        
        .perspective-container {
            perspective: 2000px;
            transform-style: preserve-3d;
        }
        
        .dashboard-mockup {
            transform: rotateX(8deg) rotateY(0deg) translateZ(0);
            transition: all 1.2s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, box-shadow;
        }
        
        .perspective-container:hover .dashboard-mockup {
            transform: rotateX(2deg) rotateY(0deg) translateZ(20px);
            box-shadow: 0 40px 80px -20px rgba(0,0,0,0.15), 0 0 0 1px rgba(79,103,248,0.3);
        }

        /* Image Mask Reveal */
        .mask-reveal {
            clip-path: inset(100% 0 0 0);
            animation: maskRevealAnim 1.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes maskRevealAnim {
            to { clip-path: inset(0 0 0 0); }
        }

        /* Smooth scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #FAFAFA; }
        ::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #D1D5DB; }

        /* Sticky sections for scroll-jacking feel */
        .sticky-section {
            position: sticky;
            top: 6rem;
            height: calc(100vh - 8rem);
        }
    </style>
</head>
<body class="font-sans text-ui-text antialiased selection:bg-brand-500 selection:text-white relative">

    <!-- Ambient Parallax Backgrounds -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-brand-50/60 blur-[100px] animate-[blobParallax_15s_ease-in-out_infinite]"></div>
        <div class="absolute top-[20%] right-[-10%] w-[40%] h-[60%] rounded-full bg-blue-50/50 blur-[120px] animate-[blobParallax_18s_ease-in-out_infinite_reverse]"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[60%] h-[40%] rounded-full bg-indigo-50/40 blur-[100px] animate-[blobParallax_20s_ease-in-out_infinite]"></div>
    </div>

    <!-- Sticky Navbar -->
    <header class="fixed top-0 inset-x-0 z-50 bg-white/70 backdrop-blur-xl border-b border-ui-border/50 transition-all duration-500 ease-apple hover:bg-white/95">
        <div class="max-w-[1280px] mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-8 h-8 rounded-lg bg-brand-500 flex items-center justify-center text-white font-bold text-lg shadow-sm group-hover:scale-105 group-hover:shadow-md transition-all duration-apple ease-apple">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <span class="font-bold text-lg tracking-tight">INAMOR</span>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-ui-muted">
                <a href="#product" class="hover:text-ui-text transition-colors duration-300">Product</a>
                <a href="#workflow" class="hover:text-ui-text transition-colors duration-300">Workflow</a>
                <a href="#security" class="hover:text-ui-text transition-colors duration-300">Security</a>
                <a href="#pricing" class="hover:text-ui-text transition-colors duration-300">Pricing</a>
            </nav>

            <div class="flex items-center gap-4">
    <a href="{{ route('login') }}"
        class="hidden sm:block text-sm font-medium text-ui-text hover:text-brand-500 transition-colors">
        Log in
    </a>

    <a href="{{ route('choose') }}"
        class="relative group overflow-hidden rounded-full p-[1px]">

        <span
            class="absolute inset-0 rounded-full bg-gradient-to-r from-brand-400 via-brand-600 to-brand-400 opacity-70 group-hover:opacity-100 transition-opacity duration-500">
        </span>

        <div
            class="relative rounded-full bg-white/90 backdrop-blur-sm px-4 py-1.5 text-sm font-semibold text-ui-text transition-all duration-300 group-hover:bg-transparent group-hover:text-white">
            Get Started
        </div>
    </a>
</div>
    </header>

    <main class="pt-24 overflow-hidden">
        
        <!-- Hero Section -->
        <section class="relative pt-16 pb-32 px-6 max-w-[1280px] mx-auto text-center flex flex-col items-center">
            
            <!-- Staggered Entrance Elements -->
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-ui-border text-brand-600 text-[11px] font-bold uppercase tracking-widest shadow-sm mb-8 animate-reveal-up delay-100">
                <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                The Standard for Organizations
            </div>
            
            <div class="overflow-hidden mb-6">
                <h1 class="text-[52px] md:text-[76px] font-extrabold text-ui-text leading-[1.05] tracking-tight animate-reveal-line delay-200">
                    Your institutional knowledge. <br class="hidden md:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 via-brand-500 to-indigo-400">Instantly accessible.</span>
                </h1>
            </div>
            
            <p class="text-lg md:text-xl text-ui-muted max-w-2xl mx-auto leading-relaxed mb-10 animate-reveal-up delay-400 font-medium">
                Unify documents, SOPs, and announcements into a single workspace. Give your team an AI assistant that actually knows your organization's official guidelines.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-reveal-up delay-500 w-full sm:w-auto z-20 relative">
                <a href="/register" class="group relative flex h-12 w-full sm:w-48 items-center justify-center gap-2 rounded-full bg-ui-text text-white font-semibold transition-all duration-apple hover:bg-black hover:shadow-premium-hover hover:scale-[1.02]">
                    Start Workspace
                    <svg class="w-4 h-4 transition-transform duration-apple group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
                <a href="#demo" class="group flex h-12 w-full sm:w-48 items-center justify-center gap-2 rounded-full bg-white border border-ui-border text-ui-text font-semibold shadow-sm transition-all duration-apple hover:border-gray-300 hover:bg-gray-50 hover:shadow-md">
                    Request Demo
                </a>
            </div>

            <!-- The Real Dashboard Product Mockup -->
            <div class="mt-24 relative w-full max-w-[1200px] perspective-container animate-scale-in delay-700 z-10">
                
                <!-- Atmospheric glow behind the mockup -->
                <div class="absolute inset-x-20 top-1/2 -translate-y-1/2 h-[60%] bg-gradient-to-r from-brand-300/30 via-brand-400/20 to-indigo-300/30 blur-[80px] -z-10 rounded-full mix-blend-multiply opacity-80"></div>
                
                <!-- Browser Window Frame -->
                <div class="dashboard-mockup relative bg-[#FAFAFA] rounded-2xl md:rounded-[24px] border border-gray-200/60 shadow-[0_20px_40px_-15px_rgba(0,0,0,0.05),0_0_0_1px_rgba(0,0,0,0.02)] overflow-hidden">
                    
                    <!-- MacOS Window Control Bar -->
                    <div class="h-10 md:h-12 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center px-4 md:px-6 gap-2 sticky top-0 z-20">
                        <div class="flex gap-1.5 md:gap-2">
                            <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-red-400/90 border border-red-500/20"></div>
                            <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-yellow-400/90 border border-yellow-500/20"></div>
                            <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-green-400/90 border border-green-500/20"></div>
                        </div>
                        <div class="mx-auto flex-1 flex justify-center max-w-sm">
                            <div class="w-full h-6 md:h-7 bg-gray-100/80 rounded-md border border-gray-200/50 flex items-center justify-center gap-2 text-[10px] md:text-xs text-gray-500 font-medium">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                inamor.workspace
                            </div>
                        </div>
                    </div>
                    
                    <!-- ACTUAL SCREENSHOT 1: Main Dashboard -->
                   <img
    src="{{ asset('images/dashboard.jpg') }}"
    alt="INAMOR Dashboard"
    class="w-full h-auto object-cover"
/>
                </div>
            </div>

            <!-- Trusted Logos -->
            <div class="mt-20 pt-10 animate-reveal-up delay-800">
                <p class="text-[13px] text-ui-muted font-semibold uppercase tracking-widest mb-8">Trusted by academic & government bodies</p>
                <div class="flex flex-wrap justify-center items-center gap-10 md:gap-20 opacity-50 grayscale hover:grayscale-0 transition-all duration-700">
                    <div class="font-bold text-xl tracking-tighter flex items-center gap-2"><div class="w-6 h-6 bg-current rounded-sm"></div>EduGov</div>
                    <div class="font-bold text-xl tracking-tighter flex items-center gap-2"><div class="w-6 h-6 border-4 border-current rounded-full"></div>Politeknik Negeri</div>
                    <div class="font-bold text-xl tracking-tighter flex items-center gap-2"><div class="w-6 h-6 bg-current rotate-45"></div>TechInst</div>
                    <div class="font-bold text-xl tracking-tighter flex items-center gap-2"><div class="w-8 h-4 bg-current rounded-full"></div>GlobalAcademy</div>
                </div>
            </div>
        </section>

        <!-- Feature Showcase with Parallax / Sticky Scroll -->
        <section id="product" class="relative py-32 bg-white border-y border-ui-border">
            <div class="max-w-[1280px] mx-auto px-6">
                
                <div class="text-center mb-24 max-w-3xl mx-auto">
                    <h2 class="text-3xl md:text-5xl font-bold text-ui-text mb-6 tracking-tight">Everything in context.</h2>
                    <p class="text-lg text-ui-muted leading-relaxed">The INAMOR workspace is built to connect the dots. Documents, AI conversations, and calendars seamlessly share the same context.</p>
                </div>

                <!-- Sticky Layout for premium scroll feel -->
                <div class="flex flex-col lg:flex-row gap-16 relative">
                    
                    <!-- Text Content (Sticky) -->
                    <div class="lg:w-5/12">
                        <div class="lg:sticky lg:top-32 space-y-12">
                            
                            <!-- Feature Block 1 -->
                            <div class="group cursor-pointer">
                                <div class="w-12 h-12 rounded-2xl bg-white border border-ui-border shadow-sm flex items-center justify-center mb-6 text-brand-500 group-hover:scale-110 group-hover:shadow-md transition-all duration-apple">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                </div>
                                <h3 class="text-2xl font-bold text-ui-text mb-3 group-hover:text-brand-600 transition-colors">Unified Knowledge Base</h3>
                                <p class="text-ui-muted leading-relaxed text-[15px]">Centralize your SOPs, digital services, and archives. The clean interface ensures your team focuses on work, not on finding files.</p>
                            </div>

                            <hr class="border-gray-100">

                            <!-- Feature Block 2 -->
                            <div class="group cursor-pointer">
                                <div class="w-12 h-12 rounded-2xl bg-white border border-ui-border shadow-sm flex items-center justify-center mb-6 text-brand-500 group-hover:scale-110 group-hover:shadow-md transition-all duration-apple">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                </div>
                                <h3 class="text-2xl font-bold text-ui-text mb-3 group-hover:text-brand-600 transition-colors">AI that understands</h3>
                                <p class="text-ui-muted leading-relaxed text-[15px]">Every document uploaded is instantly indexed. Ask the AI assistant, and it responds with exact citations from your official policies.</p>
                            </div>
                            
                            <hr class="border-gray-100">

                            <!-- Feature Block 3 -->
                            <div class="group cursor-pointer">
                                <div class="w-12 h-12 rounded-2xl bg-white border border-ui-border shadow-sm flex items-center justify-center mb-6 text-brand-500 group-hover:scale-110 group-hover:shadow-md transition-all duration-apple">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                                <h3 class="text-2xl font-bold text-ui-text mb-3 group-hover:text-brand-600 transition-colors">Activity & Monitoring</h3>
                                <p class="text-ui-muted leading-relaxed text-[15px]">Track recent documents, monitor AI feedback, and view upcoming agendas in beautifully designed widget panels.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Visual Content (Scrolls naturally) -->
                    <div class="lg:w-7/12 perspective-container">
                        <!-- ACTUAL SCREENSHOT 2: Secondary Dashboard View -->
                        <div class="dashboard-mockup relative bg-white rounded-2xl border border-gray-200/60 shadow-premium overflow-hidden sticky top-32">
                            <div class="h-8 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center px-4 gap-2 absolute top-0 w-full z-10">
                                <div class="w-2.5 h-2.5 rounded-full bg-gray-200"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-gray-200"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-gray-200"></div>
                            </div>
                            <!-- Image container with mask -->
                            <img
    src="{{ asset('images/document.jpg') }}"
    alt="INAMOR analysis"
    class="w-full h-auto object-cover"
/>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>

        <!-- Interactions & Micro-animations Section (Workflow) -->
        <section id="workflow" class="py-32 bg-[#FAFAFA]">
            <div class="max-w-[1280px] mx-auto px-6">
                <div class="text-center mb-20">
                    <h2 class="text-3xl md:text-5xl font-bold text-ui-text mb-6 tracking-tight">The flow of knowledge.</h2>
                    <p class="text-lg text-ui-muted">Designed for minimal friction. Powerful processing happens entirely behind the scenes.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                    <!-- Connecting line -->
                    <div class="hidden md:block absolute top-[50%] left-[10%] right-[10%] h-px bg-gradient-to-r from-transparent via-brand-200 to-transparent -z-10"></div>

                    <!-- Step 1 -->
                    <div class="group relative bg-white border border-ui-border rounded-3xl p-8 transition-all duration-apple hover:-translate-y-2 hover:shadow-premium hover:border-brand-200">
                        <div class="w-14 h-14 bg-ui-bg border border-ui-border shadow-sm text-ui-text rounded-2xl flex items-center justify-center font-bold text-xl mb-8 group-hover:bg-ui-text group-hover:text-white transition-colors duration-300">1</div>
                        <h3 class="text-xl font-bold text-ui-text mb-3">Upload Data</h3>
                        <p class="text-sm text-ui-muted mb-8">Drag and drop institutional SOPs, policies, and records into secure, isolated repositories.</p>
                        
                        <!-- Mini animated UI -->
                        <div class="h-32 bg-[#F8FAFC] rounded-xl border border-ui-border border-dashed flex flex-col items-center justify-center gap-3 group-hover:border-brand-400 transition-colors duration-300">
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-brand-500 group-hover:-translate-y-2 group-hover:scale-110 transition-all duration-apple">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="group relative bg-white border border-ui-border rounded-3xl p-8 transition-all duration-apple hover:-translate-y-2 hover:shadow-premium hover:border-brand-200 delay-100">
                        <div class="w-14 h-14 bg-ui-bg border border-ui-border shadow-sm text-ui-text rounded-2xl flex items-center justify-center font-bold text-xl mb-8 group-hover:bg-brand-500 group-hover:text-white group-hover:border-brand-500 transition-colors duration-300">2</div>
                        <h3 class="text-xl font-bold text-ui-text mb-3">AI Processing</h3>
                        <p class="text-sm text-ui-muted mb-8">Our engine securely reads, indexes, and understands the context of your specific organization.</p>
                        
                        <!-- Mini animated UI -->
                        <div class="h-32 bg-[#F8FAFC] rounded-xl border border-ui-border p-4 flex flex-col justify-end gap-2 overflow-hidden relative">
                            <!-- Scanning line -->
                            <div class="absolute inset-x-0 h-px bg-brand-500 shadow-[0_0_8px_rgba(79,103,248,0.8)] -top-2 group-hover:translate-y-32 transition-transform duration-[2s] ease-linear infinite"></div>
                            <div class="w-3/4 h-3 bg-gray-200 rounded-full"></div>
                            <div class="w-full h-3 bg-gray-200 rounded-full"></div>
                            <div class="w-1/2 h-3 bg-brand-200 rounded-full"></div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="group relative bg-white border border-ui-border rounded-3xl p-8 transition-all duration-apple hover:-translate-y-2 hover:shadow-premium hover:border-brand-200 delay-200">
                        <div class="w-14 h-14 bg-ui-bg border border-ui-border shadow-sm text-ui-text rounded-2xl flex items-center justify-center font-bold text-xl mb-8 group-hover:bg-ui-text group-hover:text-white transition-colors duration-300">3</div>
                        <h3 class="text-xl font-bold text-ui-text mb-3">Ask & Act</h3>
                        <p class="text-sm text-ui-muted mb-8">Staff can query the assistant and get instant answers, backed by concrete citations.</p>
                        
                        <!-- Mini animated UI -->
                        <div class="h-32 bg-[#F8FAFC] rounded-xl border border-ui-border p-3 flex flex-col justify-end gap-2">
                            <div class="self-end bg-white border border-gray-200 px-3 py-1.5 rounded-full rounded-tr-sm text-[10px] text-gray-500 shadow-sm w-3/4 group-hover:-translate-x-1 transition-transform duration-apple">What is the SOP?</div>
                            <div class="self-start bg-brand-50 border border-brand-100 px-3 py-1.5 rounded-full rounded-tl-sm text-[10px] text-brand-700 shadow-sm w-4/5 group-hover:translate-x-1 transition-transform duration-apple delay-100">According to doc...</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Security / Enterprise Section -->
        <section id="security" class="py-32 bg-white">
            <div class="max-w-[1280px] mx-auto px-6">
                <div class="flex flex-col md:flex-row gap-16 items-center">
                    
                    <div class="md:w-1/2 perspective-container">
                        <!-- Abstract Security Visual -->
                        <div class="dashboard-mockup relative w-full aspect-square max-w-md mx-auto">
                            <!-- Floating cards representing secure layers -->
                            <div class="absolute inset-0 bg-ui-sec rounded-[2rem] border border-ui-border shadow-sm transform -rotate-6 transition-transform duration-apple hover:rotate-0"></div>
                            <div class="absolute inset-0 bg-brand-50 rounded-[2rem] border border-brand-100 shadow-md transform rotate-3 transition-transform duration-apple hover:rotate-0"></div>
                            
                            <div class="absolute inset-4 bg-white rounded-[1.5rem] border border-ui-border shadow-premium p-8 flex flex-col justify-center">
                                <div class="w-16 h-16 rounded-full bg-brand-100 flex items-center justify-center mb-6 text-brand-500 shadow-glass">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                </div>
                                <h4 class="text-xl font-bold text-ui-text mb-2">Isolated Instances</h4>
                                <p class="text-sm text-ui-muted mb-6">Your data stays strictly within your workspace. It is never used to train global public models.</p>
                                
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 bg-gray-50 group hover:border-brand-200 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                            <span class="text-xs font-semibold text-gray-600">End-to-End Encryption</span>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-brand-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 bg-gray-50 group hover:border-brand-200 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                            <span class="text-xs font-semibold text-gray-600">Role-Based Access (RBAC)</span>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-brand-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="md:w-1/2 space-y-8">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-ui-sec border border-ui-border text-ui-text text-xs font-bold uppercase tracking-wider">
                            Enterprise Security
                        </div>
                        <h2 class="text-3xl md:text-5xl font-bold text-ui-text tracking-tight">Institutional-grade <br> data protection.</h2>
                        <p class="text-lg text-ui-muted leading-relaxed">
                            Designed to meet the strict compliance requirements of educational institutions and government agencies. You maintain absolute control over who views, edits, and queries your documents.
                        </p>
                        
                        <a href="#" class="group inline-flex items-center gap-2 text-brand-600 font-semibold text-[15px] hover:text-brand-700 transition-colors">
                            Read our security whitepaper 
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-apple" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </a>
                    </div>

                </div>
            </div>
        </section>

        <!-- Premium CTA Section -->
        <section class="py-32 px-6 relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute inset-0 bg-ui-text z-0"></div>
            <div class="absolute -top-[50%] -left-[10%] w-[70%] h-[150%] bg-gradient-to-br from-brand-900/50 to-transparent rounded-full blur-3xl z-0 transform rotate-12"></div>
            <div class="absolute -bottom-[50%] -right-[10%] w-[70%] h-[150%] bg-gradient-to-tl from-brand-800/40 to-transparent rounded-full blur-3xl z-0 transform -rotate-12"></div>

            <div class="max-w-[800px] mx-auto text-center relative z-10 perspective-container">
                <div class="dashboard-mockup">
                    <h2 class="text-4xl md:text-6xl font-bold text-white mb-8 tracking-tight">Ready to modernize your workspace?</h2>
                    <p class="text-gray-300 text-lg md:text-xl mb-12 max-w-2xl mx-auto font-medium">
                        Join leading organizations centralizing their knowledge and empowering their teams with AI.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="/register" class="w-full sm:w-auto inline-flex items-center justify-center h-14 px-10 bg-white text-ui-text font-bold rounded-full transition-all duration-apple hover:scale-105 hover:shadow-premium-hover">
                            Create Organization
                        </a>
                        <a href="#demo" class="w-full sm:w-auto inline-flex items-center justify-center h-14 px-10 bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold rounded-full transition-all duration-apple hover:bg-white/20 hover:border-white/30">
                            Talk to Sales
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer (Premium & Minimal) -->
    <footer class="bg-ui-text text-gray-400 py-20 border-t border-gray-800 relative z-10">
        <div class="max-w-[1280px] mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-10 mb-16">
                <div class="col-span-2 lg:col-span-2">
                    <a href="/" class="flex items-center gap-2 mb-6 group">
                        <div class="w-8 h-8 rounded-lg bg-brand-500 flex items-center justify-center text-white font-bold text-lg shadow-sm group-hover:scale-105 transition-transform duration-apple">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <span class="font-bold text-xl text-white tracking-tight">INAMOR</span>
                    </a>
                    <p class="text-sm text-gray-500 max-w-xs leading-relaxed mb-6">
                        The Enterprise AI Knowledge Platform built for universities, government agencies, and modern organizations.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-6">Product</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Knowledge Base</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">AI Assistant</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Integrations</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Changelog</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-6">Solutions</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors duration-300">For Universities</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">For Government</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">For Enterprises</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-6">Company</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors duration-300">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Security</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors duration-300">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">© 2026 INAMOR. Built with passion by ERIEL.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-gray-500 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>