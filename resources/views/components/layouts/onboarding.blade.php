@props([
    'title' => 'Onboarding',
])

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — INAMOR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @keyframes onb-pop {
            0% { transform: scale(0.6); opacity: 0; }
            60% { transform: scale(1.06); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes onb-draw {
            from { stroke-dashoffset: 48; }
            to { stroke-dashoffset: 0; }
        }
        .onb-pop { animation: onb-pop .5s cubic-bezier(.2,.8,.2,1) both; }
        .onb-draw { stroke-dasharray: 48; stroke-dashoffset: 48; animation: onb-draw .5s .2s ease-out forwards; }
        @media (prefers-reduced-motion: reduce) {
            .onb-pop, .onb-draw { animation: none !important; }
        }
    </style>
</head>
<body class="relative min-h-screen overflow-x-hidden bg-[#FAFAF9] font-['Inter',sans-serif] text-[#16161A] antialiased">

    {{-- decorative background: soft gradient blobs + faint dot grid --}}
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -left-32 -top-40 h-[420px] w-[420px] rounded-full bg-[#6E56CF] opacity-[0.10] blur-[110px]"></div>
        <div class="absolute -bottom-40 -right-24 h-[380px] w-[380px] rounded-full bg-[#F5A667] opacity-[0.12] blur-[110px]"></div>
        <div
            class="absolute inset-0 opacity-40"
            style="background-image:radial-gradient(#E4E4E7 1px, transparent 1px); background-size:28px 28px; mask-image:radial-gradient(ellipse 80% 55% at 50% 0%, black 35%, transparent 100%); -webkit-mask-image:radial-gradient(ellipse 80% 55% at 50% 0%, black 35%, transparent 100%);"
        ></div>
    </div>

    <div class="relative flex min-h-screen flex-col">

        {{-- logo, top center — no dashboard nav/search/switcher --}}
        <header class="flex items-center justify-center pt-10 md:pt-14">
            <a href="/" class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-[10px] bg-gradient-to-br from-[#6E56CF] to-[#8B7CE8] font-['IBM_Plex_Mono',monospace] text-[13px] font-semibold text-white shadow-[0_2px_6px_rgba(110,86,207,0.35)]">IN</span>
                <span class="text-[15px] font-semibold tracking-tight text-[#16161A]">INAMOR</span>
            </a>
        </header>

        {{-- centered container — page content goes here via the default slot --}}
        <main class="flex flex-1 items-center justify-center px-4 py-10 md:py-14">
            <div class="w-full">
                {{ $slot }}
            </div>
        </main>

      
    </div>
</body>
</html>