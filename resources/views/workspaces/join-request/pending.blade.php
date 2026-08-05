<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Join Request Submitted</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            color-scheme: light;
        }

        html, body {
            height: 100vh;
            height: 100dvh;
            width: 100vw;
            margin: 0;
            padding: 0;
            overflow: hidden;
            overscroll-behavior: none;
        }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* ===================== BACKGROUND ===================== */
        @keyframes bgFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        .bg-fade { animation: bgFadeIn 1.1s ease forwards; }

        @keyframes floatBlob {
            0%   { transform: translate(0, 0) scale(1); }
            33%  { transform: translate(2.5vw, -2vh) scale(1.06); }
            66%  { transform: translate(-2vw, 2vh) scale(0.96); }
            100% { transform: translate(0, 0) scale(1); }
        }
        .blob { animation: floatBlob 18s ease-in-out infinite; }
        .blob-slow { animation-duration: 24s; }
        .blob-slower { animation-duration: 30s; }

        @keyframes pulseGlowBg {
            0%, 100% { opacity: 0.45; }
            50% { opacity: 0.8; }
        }
        .pulse-bg { animation: pulseGlowBg 6s ease-in-out infinite; }

        @keyframes beamSweep {
            0%   { transform: translateX(-30%) rotate(8deg); opacity: 0; }
            15%  { opacity: 0.35; }
            50%  { opacity: 0.15; }
            100% { transform: translateX(30%) rotate(8deg); opacity: 0; }
        }
        .light-beam { animation: beamSweep 9s ease-in-out infinite; }

        @keyframes particleDrift {
            0%   { transform: translateY(0) translateX(0); opacity: 0; }
            10%  { opacity: 0.6; }
            90%  { opacity: 0.3; }
            100% { transform: translateY(-6vh) translateX(1vw); opacity: 0; }
        }
        .light-particle { animation: particleDrift 7s ease-in-out infinite; }

        /* ===================== ENTRANCE ===================== */
        @keyframes riseIn {
            from { opacity: 0; transform: translateY(1.1vh); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .rise-in { opacity: 0; animation: riseIn 0.65s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(1.4vh) scale(0.985); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .card-in { opacity: 0; animation: cardIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        /* ===================== CHECKMARK SEQUENCE ===================== */
        @keyframes circlePop {
            0%   { transform: scale(0); opacity: 0; }
            55%  { transform: scale(1.12); opacity: 1; }
            75%  { transform: scale(0.96); }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes ringDraw {
            from { stroke-dashoffset: 302; }
            to   { stroke-dashoffset: 0; }
        }
        @keyframes checkDraw {
            from { stroke-dashoffset: 48; }
            to   { stroke-dashoffset: 0; }
        }
        @keyframes glowExpand {
            0%   { opacity: 0; transform: scale(0.6); }
            45%  { opacity: 0.6; transform: scale(1.25); }
            100% { opacity: 0.28; transform: scale(1.05); }
        }
        @keyframes glowBreathe {
            0%, 100% { opacity: 0.22; transform: scale(1.02); }
            50%      { opacity: 0.4; transform: scale(1.1); }
        }
        @keyframes shadowPulse {
            0%, 100% { box-shadow: 0 0.6vh 2vh -0.4vh rgba(16, 185, 129, 0.25); }
            50%      { box-shadow: 0 0.8vh 3vh -0.2vh rgba(16, 185, 129, 0.4); }
        }
        @keyframes badgeGlow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.35); }
            50%      { box-shadow: 0 0 0 0.9vh rgba(16, 185, 129, 0); }
        }
        @keyframes particlePop {
            0%   { transform: translate(0, 0) scale(0); opacity: 1; }
            75%  { opacity: 0.9; }
            100% { transform: translate(var(--tx), var(--ty)) scale(1); opacity: 0; }
        }

        .success-circle-wrap {
            animation: circlePop 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards, shadowPulse 3s ease-in-out 0.9s infinite;
            opacity: 0;
            border-radius: 9999px;
        }
        .success-ring {
            stroke-dasharray: 302;
            stroke-dashoffset: 302;
            animation: ringDraw 0.85s cubic-bezier(0.65, 0, 0.35, 1) 0.15s forwards;
        }
        .success-check {
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: checkDraw 0.4s ease-out 0.85s forwards;
        }
        .success-glow-burst {
            animation: glowExpand 1s cubic-bezier(0.16, 1, 0.3, 1) 0.75s forwards, glowBreathe 3.2s ease-in-out 1.8s infinite;
            opacity: 0;
        }
        .success-badge-ring {
            animation: badgeGlow 2.6s ease-out 1.5s infinite;
        }
        .particle {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0.6vh;
            height: 0.6vh;
            min-width: 4px;
            min-height: 4px;
            border-radius: 9999px;
            opacity: 0;
            animation: particlePop 1s cubic-bezier(0.16, 1, 0.3, 1) 0.95s forwards;
        }

        /* ===================== TIMELINE ===================== */
        @keyframes lineGrow {
            from { height: 0%; }
            to   { height: 100%; }
        }
        .timeline-line-fill {
            height: 0%;
            animation: lineGrow 1.2s cubic-bezier(0.16, 1, 0.3, 1) 1.1s forwards;
        }
        @keyframes iconPop {
            0%   { transform: scale(0); opacity: 0; }
            65%  { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .icon-pop { opacity: 0; animation: iconPop 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes dotPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.45); }
            50%      { box-shadow: 0 0 0 0.7vh rgba(245, 158, 11, 0); }
        }
        .dot-pulse { animation: dotPulse 2s ease-out infinite; }

        /* ===================== MICRO INTERACTIONS ===================== */
        .btn-lift {
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, background-color 0.25s ease;
        }
        .btn-lift:hover { transform: translateY(-2px) scale(1.01); }
        .btn-lift:active { transform: translateY(0) scale(0.97); }

        .btn-ripple {
            position: absolute;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.55);
            transform: scale(0);
            animation: rippleOut 0.6s ease-out forwards;
            pointer-events: none;
        }
        @keyframes rippleOut {
            to { transform: scale(3.2); opacity: 0; }
        }

        .card-lift {
            transition: box-shadow 0.35s ease, transform 0.35s ease, border-color 0.35s ease;
        }
        .card-lift:hover {
            box-shadow: 0 1.2vh 3vh -1vh rgba(17, 24, 39, 0.14);
            transform: translateY(-2px);
            border-color: #e0e0f0;
        }

        *:focus-visible {
            outline: 2px solid #6366f1;
            outline-offset: 2px;
            border-radius: 10px;
        }

        /* ===================== FLUID SIZING (never scrolls) ===================== */
        .vh-shell {
            height: 100vh;
            height: 100dvh;
        }

        .success-card {
            width: min(760px, 92vw);
            max-height: 96vh;
            overflow: hidden;
            padding: clamp(16px, 3.4vh, 44px) clamp(20px, 4.4vw, 56px);
        }

        .icon-stage {
            width: clamp(56px, 12vh, 128px);
            height: clamp(56px, 12vh, 128px);
            margin-bottom: clamp(8px, 2vh, 28px);
        }

        .headline {
            font-size: clamp(1.15rem, 2.6vh, 2.1rem);
            line-height: 1.15;
        }

        .subtext {
            font-size: clamp(0.72rem, 1.5vh, 0.95rem);
        }

        .badge-text { font-size: clamp(0.62rem, 1.15vh, 0.78rem); }

        .info-cards { margin-top: clamp(8px, 2vh, 28px); margin-bottom: clamp(10px, 2.2vh, 28px); gap: clamp(6px, 1vh, 12px); }
        .info-card-pad { padding: clamp(8px, 1.6vh, 20px); }
        .info-label { font-size: clamp(0.55rem, 1vh, 0.68rem); }
        .info-value { font-size: clamp(0.85rem, 1.7vh, 1.1rem); margin-top: clamp(2px, 0.6vh, 8px); }

        .timeline-wrap { margin-top: clamp(6px, 1.4vh, 16px); }
        .timeline-eyebrow { font-size: clamp(0.55rem, 1vh, 0.68rem); margin-bottom: clamp(6px, 1.6vh, 20px); }
        .timeline-steps { gap: clamp(8px, 1.8vh, 26px); }
        .timeline-dot { width: clamp(20px, 3.4vh, 32px); height: clamp(20px, 3.4vh, 32px); }
        .timeline-title { font-size: clamp(0.72rem, 1.35vh, 0.875rem); }
        .timeline-desc { font-size: clamp(0.62rem, 1.15vh, 0.75rem); margin-top: clamp(1px, 0.3vh, 4px); }

        .actions-row { margin-top: clamp(12px, 2.6vh, 40px); gap: clamp(6px, 1.2vh, 12px); }
        .action-btn { padding: clamp(9px, 1.7vh, 16px) clamp(12px, 1.8vw, 16px); font-size: clamp(0.72rem, 1.35vh, 0.875rem); }

        .footer-note { margin-top: clamp(8px, 1.8vh, 28px); font-size: clamp(0.6rem, 1.05vh, 0.75rem); }
    </style>
</head>
<body class="bg-[#FAFAFA] text-gray-900 antialiased selection:bg-indigo-100 selection:text-indigo-900">

    <!-- Decorative animated background -->
    <div class="bg-fade fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="blob blob-slow absolute -top-[10vh] -left-[10vw] w-[42vh] h-[42vh] bg-indigo-200/30 rounded-full blur-3xl pulse-bg"></div>
        <div class="blob blob-slower absolute top-1/3 right-[-14vw] w-[48vh] h-[48vh] bg-emerald-200/25 rounded-full blur-3xl"></div>
        <div class="blob absolute bottom-[-14vh] left-1/3 w-[44vh] h-[44vh] bg-yellow-100/40 rounded-full blur-3xl pulse-bg"></div>

        <div class="light-beam absolute top-0 left-1/4 w-[18vw] h-[130vh] bg-gradient-to-b from-white/60 via-indigo-100/30 to-transparent blur-2xl"></div>

        <span class="light-particle absolute top-[60%] left-[20%] w-1 h-1 rounded-full bg-indigo-300" style="animation-delay: 0s;"></span>
        <span class="light-particle absolute top-[70%] left-[75%] w-1 h-1 rounded-full bg-emerald-300" style="animation-delay: 1.5s;"></span>
        <span class="light-particle absolute top-[50%] left-[50%] w-1 h-1 rounded-full bg-yellow-300" style="animation-delay: 3s;"></span>
        <span class="light-particle absolute top-[80%] left-[35%] w-1 h-1 rounded-full bg-indigo-200" style="animation-delay: 4.5s;"></span>

        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(255,255,255,0)_0%,#FAFAFA_78%)]"></div>
    </div>

    <!-- Viewport-locked centering shell -->
    <div class="vh-shell relative z-10 w-full flex items-center justify-center overflow-hidden">
        <div class="w-full flex flex-col items-center">

            <!-- Success Card -->
            <div class="success-card card-in bg-white/90 backdrop-blur-sm rounded-[1.6rem] sm:rounded-[2rem] shadow-[0_20px_60px_-15px_rgba(17,24,39,0.08)] border border-gray-100 flex flex-col">

                <!-- Animated SVG Checkmark -->
                <div class="relative flex items-center justify-center w-full">
                    <div class="icon-stage success-circle-wrap relative flex items-center justify-center">

                        <!-- glow burst -->
                        <div class="success-glow-burst absolute inset-0 rounded-full bg-emerald-300 blur-2xl"></div>

                        <!-- particles -->
                        <span class="particle bg-emerald-400" style="--tx: -5vh; --ty: -4vh;"></span>
                        <span class="particle bg-indigo-400" style="--tx: 4.6vh; --ty: -5vh;"></span>
                        <span class="particle bg-yellow-400" style="--tx: -5.6vh; --ty: 2.4vh;"></span>
                        <span class="particle bg-emerald-300" style="--tx: 5.2vh; --ty: 3.2vh;"></span>
                        <span class="particle bg-indigo-300" style="--tx: 0vh; --ty: -6vh;"></span>
                        <span class="particle bg-emerald-400" style="--tx: 0vh; --ty: 6vh;"></span>

                        <!-- checkmark svg -->
                        <svg viewBox="0 0 100 100" class="relative w-full h-full">
                            <circle cx="50" cy="50" r="46" fill="#ECFDF5" />
                            <circle
                                class="success-ring"
                                cx="50" cy="50" r="46"
                                fill="none"
                                stroke="#10B981"
                                stroke-width="4"
                                stroke-linecap="round"
                                transform="rotate(-90 50 50)"
                            />
                            <path
                                class="success-check"
                                d="M30 52 L44 66 L72 36"
                                fill="none"
                                stroke="#10B981"
                                stroke-width="6"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </div>
                </div>

                <!-- Header -->
                <div class="rise-in text-center" style="animation-delay: 1.0s;">
                    <span class="success-badge-ring badge-text inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold px-3 py-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                        Submitted Successfully
                    </span>
                </div>

                <div class="rise-in text-center mt-2" style="animation-delay: 1.1s;">
                    <h1 class="headline font-extrabold text-gray-900 tracking-tight">
                        Request Submitted
                    </h1>
                    <p class="subtext mt-1.5 text-gray-500 leading-snug max-w-md mx-auto">
                        Your join request has been sent to the workspace administrators for review.
                    </p>
                </div>

                <!-- Info cards -->
                <div class="info-cards rise-in grid grid-cols-2 w-full" style="animation-delay: 1.25s;">
                    <div class="card-lift info-card-pad rounded-2xl border border-gray-200 bg-white">
                        <p class="info-label font-semibold uppercase tracking-wider text-gray-400">Estimated Review</p>
                        <p class="info-value font-bold text-gray-900">~24 Hours</p>
                    </div>
                    <div class="card-lift info-card-pad rounded-2xl border border-gray-200 bg-white">
                        <p class="info-label font-semibold uppercase tracking-wider text-gray-400">Status</p>
                        <p class="info-value inline-flex items-center gap-1.5 font-bold text-yellow-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 dot-pulse shrink-0"></span>
                            Pending Review
                        </p>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="timeline-wrap rise-in" style="animation-delay: 1.4s;">
                    <h2 class="timeline-eyebrow font-bold tracking-widest text-gray-400 uppercase">
                        What happens next
                    </h2>

                    <div class="relative">
                        <!-- animated connecting line -->
                        <div class="absolute left-[calc(clamp(20px,3.4vh,32px)/2)] top-1 bottom-1 w-0.5 bg-gray-100 -translate-x-1/2">
                            <div class="timeline-line-fill w-full bg-gradient-to-b from-emerald-400 via-yellow-400 to-gray-200 rounded-full"></div>
                        </div>

                        <div class="timeline-steps flex flex-col">
                            <!-- Step 1: Submitted -->
                            <div class="relative flex items-center gap-3">
                                <div class="icon-pop timeline-dot relative z-10 flex items-center justify-center rounded-full bg-emerald-500 shrink-0 shadow-sm shadow-emerald-200" style="animation-delay: 1.55s;">
                                    <svg class="w-1/2 h-1/2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="rise-in" style="animation-delay: 1.6s;">
                                    <h3 class="timeline-title font-semibold text-gray-900">Submitted</h3>
                                    <p class="timeline-desc text-gray-500 leading-snug">Your information and documents were securely received.</p>
                                </div>
                            </div>

                            <!-- Step 2: Admin Review -->
                            <div class="relative flex items-center gap-3">
                                <div class="icon-pop timeline-dot relative z-10 flex items-center justify-center rounded-full bg-white border-2 border-yellow-400 shrink-0 shadow-sm dot-pulse" style="animation-delay: 1.75s;">
                                    <div class="w-1/3 h-1/3 bg-yellow-500 rounded-full"></div>
                                </div>
                                <div class="rise-in" style="animation-delay: 1.8s;">
                                    <h3 class="timeline-title font-semibold text-gray-900">Admin Review</h3>
                                    <p class="timeline-desc text-gray-500 leading-snug">Administrators are verifying your ID card and selfie.</p>
                                </div>
                            </div>

                            <!-- Step 3: Workspace Access -->
                            <div class="relative flex items-center gap-3">
                                <div class="icon-pop timeline-dot relative z-10 flex items-center justify-center rounded-full bg-gray-50 border border-gray-200 shrink-0 shadow-sm" style="animation-delay: 1.95s;">
                                    <svg class="w-1/2 h-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <div class="rise-in" style="animation-delay: 2.0s;">
                                    <h3 class="timeline-title font-semibold text-gray-400">Workspace Access</h3>
                                    <p class="timeline-desc text-gray-400 leading-snug">Full access will be granted once your request is approved.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="actions-row rise-in flex flex-row w-full" style="animation-delay: 2.1s;">
                    
                       <a href="{{ route('dashboard') }}"
                        onclick="createRipple(event)"
                        class="btn-lift action-btn group relative flex items-center justify-center w-2/3 font-semibold text-white bg-indigo-600 border border-transparent rounded-2xl shadow-md shadow-indigo-200 hover:bg-indigo-500 hover:shadow-lg hover:shadow-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 overflow-hidden"
                    >
                        <span class="relative z-10 flex items-center gap-2">
                            Back to Dashboard
                            <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>
                    </a>

                    <button
                        type="button"
                        id="copyStatusBtn"
                        onclick="createRipple(event); copyPageLink();"
                        class="btn-lift action-btn relative flex items-center justify-center w-1/3 font-semibold text-gray-600 bg-white border border-gray-200 rounded-2xl shadow-sm hover:border-gray-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 overflow-hidden"
                    >
                        <span id="copyStatusLabel" class="flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <span class="hidden sm:inline">Copy</span>
                        </span>
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <p class="footer-note rise-in text-center text-gray-400 font-medium" style="animation-delay: 2.25s;">
                You will be notified once a decision is made.
            </p>

        </div>
    </div>

    <script>
        function copyPageLink() {
            const label = document.getElementById('copyStatusLabel');
            const originalHTML = label.innerHTML;

            navigator.clipboard.writeText(window.location.href).then(function () {
                label.innerHTML = '<svg class="w-4 h-4 text-emerald-500 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg><span class="hidden sm:inline">Copied</span>';

                setTimeout(function () {
                    label.innerHTML = originalHTML;
                }, 2000);
            }).catch(function () {
                // silently ignore if clipboard access is unavailable
            });
        }

        function createRipple(event) {
            const button = event.currentTarget;
            const rect = button.getBoundingClientRect();
            const ripple = document.createElement('span');
            const size = Math.max(rect.width, rect.height);

            ripple.className = 'btn-ripple';
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = (event.clientX - rect.left - size / 2) + 'px';
            ripple.style.top = (event.clientY - rect.top - size / 2) + 'px';

            button.appendChild(ripple);
            setTimeout(function () { ripple.remove(); }, 650);
        }
    </script>

</body>
</html>