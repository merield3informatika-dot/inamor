<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify your identity — {{ $workspace->name }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body { height: 100%; overflow: hidden; overscroll-behavior: none; }
        body { background: #FFFFFF; }

        /* ---------- Step transitions (content only) ---------- */
        .step-panel {
            display: none;
            opacity: 0;
            transform: translateY(10px);
        }
        .step-panel.active {
            display: flex;
            animation: stepIn .45s cubic-bezier(.16,1,.3,1) forwards;
        }
        @keyframes stepIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* footer action groups swap instantly, no vertical shift */
        .footer-panel.hidden { display: none; }

        /* ---------- Camera ---------- */
        #videoStream_3, #videoStream_4 { transform: scaleX(1); }
        #videoStream_3.mirror, #videoStream_4.mirror { transform: scaleX(-1); }

        @keyframes scanline {
            0%   { top: 6%;  opacity: .95; }
            50%  { top: 92%; opacity: .55; }
            100% { top: 6%;  opacity: .95; }
        }
        .scanline {
            animation: scanline 2.6s ease-in-out infinite;
            box-shadow: 0 0 14px 1px rgba(79, 70, 229, .55);
        }

        @keyframes fadeInImg {
            from { opacity: 0; transform: scale(1.02); }
            to   { opacity: 1; transform: scale(1); }
        }
        .fade-in-img { animation: fadeInImg .5s cubic-bezier(.16,1,.3,1) forwards; }

        @keyframes seal-pop {
            0%   { opacity: 0; transform: scale(.6); }
            55%  { opacity: 1; transform: scale(1.08); }
            100% { opacity: 1; transform: scale(1); }
        }
        .seal-pop { animation: seal-pop .45s cubic-bezier(.16,1,.3,1) forwards; }

        .btn-press:active { transform: scale(.985); }

        .card-hover { transition: box-shadow .3s ease, border-color .3s ease; }
        .card-hover:hover { box-shadow: 0 8px 22px -10px rgba(17,24,39,.10); border-color: #E5E7EB; }

        .img-zoom { overflow: hidden; }
        .img-zoom img { transition: transform .5s cubic-bezier(.16,1,.3,1); }
        .img-zoom:hover img { transform: scale(1.05); }

        ::selection { background: #E0E7FF; }

        *:focus-visible {
            outline: 2px solid #4F46E5;
            outline-offset: 2px;
            border-radius: 8px;
        }

        /* Only the content column may scroll, and only if a step genuinely
           overflows (small viewports, the step-2 tutorial, etc). */
        .content-scroll { overflow-y: auto; }

        @media (max-height: 700px) {
            .compact-hide { display: none; }
        }

        @media (prefers-reduced-motion: reduce) {
            .step-panel, .fade-in-img, .seal-pop, .scanline { animation: none !important; }
        }
    </style>
</head>
<body class="h-screen overflow-hidden bg-white font-sans text-gray-900 antialiased">

   @php
    $logoUrl = $workspace->logo
        ? Storage::url($workspace->logo)
        : null;

    $memberCount = $workspace->members()->count();
@endphp

    <div
        x-data="joinRequest()"
        x-init="init()"
        class="flex h-screen w-full flex-col overflow-hidden"
    >

        {{-- ============================================================ --}}
        {{-- HEADER — fixed height, never grows                            --}}
        {{-- ============================================================ --}}
        <header class="flex-none border-b border-gray-100 bg-white">
            <div class="mx-auto max-w-2xl px-6 py-3.5 sm:px-10">
                <div class="mb-3 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="" class="h-6 w-6 rounded-md object-cover ring-1 ring-gray-200">
                        @else
                            <span class="flex h-6 w-6 items-center justify-center rounded-md bg-gray-900 text-[10px] font-semibold text-white">
                                {{ strtoupper(substr($workspace->name, 0, 1)) }}
                            </span>
                        @endif
                        <span class="text-sm font-medium text-gray-900">{{ $workspace->name }}</span>
                        <span class="hidden text-sm text-gray-300 sm:inline">/</span>
                        <span class="hidden text-sm text-gray-400 sm:inline">Identity verification</span>
                    </div>

                    <span class="hidden items-center gap-1.5 rounded-full bg-gray-50 px-3 py-1 text-[11px] font-medium text-gray-500 ring-1 ring-inset ring-gray-200 sm:inline-flex">
                        <svg class="h-3 w-3 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1.5a4.5 4.5 0 00-4.5 4.5v2.25H5a1.5 1.5 0 00-1.5 1.5v7.75A1.5 1.5 0 005 19h10a1.5 1.5 0 001.5-1.5v-7.75A1.5 1.5 0 0015 8.25h-.5V6A4.5 4.5 0 0010 1.5zm3 6.75V6a3 3 0 10-6 0v2.25h6z" clip-rule="evenodd"/></svg>
                        Encrypted &amp; confidential
                    </span>
                </div>

                <div class="flex items-center gap-1.5">
                    <template x-for="n in totalSteps" :key="n">
                        <div
                            class="h-[3px] flex-1 rounded-full transition-colors duration-500"
                            :class="n <= step ? 'bg-gray-900' : 'bg-gray-100'"
                        ></div>
                    </template>
                </div>
                <div class="mt-1.5 flex items-center justify-between">
                    <p class="text-[11px] font-medium uppercase tracking-wider text-gray-400" x-text="stageLabels[step - 1]"></p>
                    <p class="text-[11px] font-medium text-gray-400">Step <span x-text="step"></span> of <span x-text="totalSteps"></span></p>
                </div>
            </div>
        </header>

        <form
            id="joinRequestForm"
            action="{{ route('workspace.join-request.store', $workspace) }}"
            method="POST"
            enctype="multipart/form-data"
            class="flex min-h-0 flex-1 flex-col"
        >
            @csrf

            {{-- hidden fields required by the backend validation --}}
            <input type="hidden" name="full_name" id="full_name_hidden" value="{{ old('full_name', auth()->user()->name) }}">
            <input type="hidden" name="email" id="email_hidden" value="{{ old('email', auth()->user()->email) }}">

            {{-- real file inputs the backend expects, kept hidden and populated via JS --}}
            <input type="file" name="identity_card" id="identity_card_input" accept="image/*" class="hidden">
            <input type="file" name="selfie_with_identity_card" id="selfie_input" accept="image/*" class="hidden">

            @if ($errors->any())
                <div class="mx-auto mt-3 flex w-full max-w-2xl flex-none items-start gap-2.5 rounded-xl border border-red-100 bg-red-50/60 px-4 py-2.5 sm:px-10">
                    <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-red-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 6a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                    <div class="space-y-0.5">
                        @error('full_name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                        @error('email') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                        @error('identity_card') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                        @error('selfie_with_identity_card') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

            {{-- ============================================================ --}}
            {{-- CONTENT — flex-1, only this column may scroll                 --}}
            {{-- ============================================================ --}}
            <div class="content-scroll min-h-0 flex-1">
                <div class="mx-auto flex min-h-full w-full max-w-2xl flex-col justify-center px-6 py-5 sm:px-10">

                    {{-- ================= STEP 1 : WELCOME ================= --}}
                    <section class="step-panel active w-full flex-col items-center text-center" data-panel="1">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $workspace->name }}" class="h-16 w-16 rounded-2xl object-cover shadow-[0_8px_24px_-10px_rgba(17,24,39,0.2)] ring-1 ring-gray-100">
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-900 text-2xl font-semibold text-white shadow-[0_8px_24px_-10px_rgba(17,24,39,0.35)]">
                                {{ strtoupper(substr($workspace->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="mt-3.5 inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-[11px] font-semibold text-amber-700 ring-1 ring-inset ring-amber-200">
                            <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1.5a4.5 4.5 0 00-4.5 4.5v2.25H5a1.5 1.5 0 00-1.5 1.5v7.75A1.5 1.5 0 005 19h10a1.5 1.5 0 001.5-1.5v-7.75A1.5 1.5 0 0015 8.25h-.5V6A4.5 4.5 0 0010 1.5zm3 6.75V6a3 3 0 10-6 0v2.25h6z" clip-rule="evenodd"/></svg>
                            Verification required
                        </div>

                        <h1 class="mt-3 text-[22px] font-semibold leading-tight tracking-tight text-gray-900">
                            You're requesting access to {{ $workspace->name }}
                        </h1>

                        @if(!empty($workspace->description))
                            <p class="compact-hide mx-auto mt-2 max-w-sm text-sm leading-relaxed text-gray-500">
                                {{ $workspace->description }}
                            </p>
                        @endif

                        <div class="mt-3 flex items-center gap-3 text-xs font-medium text-gray-400">
                            <span>{{ $memberCount ? number_format($memberCount).' members' : 'Growing team' }}</span>
                            <span class="h-1 w-1 rounded-full bg-gray-300"></span>
                            <span class="inline-flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="10" cy="10" r="7.25"/><path d="M10 6v4l2.5 1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                ~1 minute
                            </span>
                        </div>

                        <div class="card-hover mt-5 w-full space-y-3.5 rounded-2xl border border-gray-200 bg-white p-4 text-left shadow-sm sm:p-5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Confirm your details</p>

                            <div>
                                <label for="full_name_display" class="mb-1.5 block text-xs font-medium text-gray-500">Full name</label>
                                <input
                                    type="text"
                                    id="full_name_display"
                                    value="{{ old('full_name', auth()->user()->name) }}"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 py-2.5 text-sm text-gray-900 transition duration-200 focus:border-gray-900 focus:bg-white focus:ring-1 focus:ring-gray-900"
                                >
                            </div>

                            <div>
                                <label for="email_display" class="mb-1.5 block text-xs font-medium text-gray-500">Email</label>
                                <input
                                    type="email"
                                    id="email_display"
                                    value="{{ old('email', auth()->user()->email) }}"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 py-2.5 text-sm text-gray-900 transition duration-200 focus:border-gray-900 focus:bg-white focus:ring-1 focus:ring-gray-900"
                                >
                            </div>
                        </div>
                    </section>

                    {{-- ================= STEP 2 : PREPARE / TUTORIAL ================= --}}
                    <section class="step-panel w-full flex-col" data-panel="2">
                        <h2 class="text-lg font-semibold tracking-tight text-gray-900">Before you start</h2>
                        <p class="mt-1 text-sm text-gray-500">Two quick photos. Here's what a good capture looks like.</p>

                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            {{-- Identity card tutorial --}}
                            <div class="card-hover rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-50 text-gray-700 ring-1 ring-gray-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><circle cx="8" cy="12" r="2"/><path d="M14 10h4M14 14h4"/></svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900">Identity card</p>
                                </div>

                                <div class="mt-3 grid grid-cols-2 gap-2.5">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <div class="relative flex h-14 w-full items-center justify-center rounded-lg border border-gray-200 bg-gray-50">
                                            <div class="h-8 w-14 rounded-md border border-gray-400 bg-white"></div>
                                            <span class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-emerald-600 text-[8px] text-white">✓</span>
                                        </div>
                                        <p class="text-[10px] font-medium text-gray-500">Clear &amp; well-lit</p>
                                    </div>
                                    <div class="flex flex-col items-center gap-1.5">
                                        <div class="relative flex h-14 w-full items-center justify-center rounded-lg border border-gray-200 bg-gray-50">
                                            <div class="h-8 w-14 -rotate-12 rounded-md border border-gray-300 bg-white opacity-60"></div>
                                            <span class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-gray-400 text-[8px] text-white">✕</span>
                                        </div>
                                        <p class="text-[10px] font-medium text-gray-500">Blurry / cropped</p>
                                    </div>
                                </div>

                                <p class="mt-3 text-[11px] leading-relaxed text-gray-500">Fit all four corners in frame, avoid glare and shadows.</p>
                            </div>

                            {{-- Selfie tutorial --}}
                            <div class="card-hover rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-50 text-gray-700 ring-1 ring-gray-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-3.5 3.5-6 8-6s8 2.5 8 6"/></svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900">Selfie with card</p>
                                </div>

                                <div class="mt-3 grid grid-cols-2 gap-2.5">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <div class="relative flex h-14 w-full items-center justify-center rounded-lg border border-gray-200 bg-gray-50">
                                            <div class="h-9 w-9 rounded-full border border-gray-400 bg-white"></div>
                                            <span class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-emerald-600 text-[8px] text-white">✓</span>
                                        </div>
                                        <p class="text-[10px] font-medium text-gray-500">Face &amp; card visible</p>
                                    </div>
                                    <div class="flex flex-col items-center gap-1.5">
                                        <div class="relative flex h-14 w-full items-center justify-center rounded-lg border border-gray-200 bg-gray-50">
                                            <div class="h-9 w-9 rounded-full border border-gray-300 bg-white opacity-40"></div>
                                            <span class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-gray-400 text-[8px] text-white">✕</span>
                                        </div>
                                        <p class="text-[10px] font-medium text-gray-500">Face covered / dark</p>
                                    </div>
                                </div>

                                <p class="mt-3 text-[11px] leading-relaxed text-gray-500">Hold the card next to your face and look straight at the camera.</p>
                            </div>
                        </div>
                    </section>

                    {{-- ================= STEP 3 : CAMERA - IDENTITY CARD ================= --}}
                    <section class="step-panel w-full flex-col" data-panel="3">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold tracking-tight text-gray-900">Capture identity card</h2>
                                <p class="mt-0.5 text-sm text-gray-500">Fit the card inside the frame.</p>
                            </div>
                            <span class="flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-700 ring-1 ring-inset ring-emerald-100">
                                <span class="relative flex h-2 w-2">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                </span>
                                Active
                            </span>
                        </div>

                        <div class="relative mx-auto mt-4 h-[38vh] max-h-[400px] min-h-[220px] w-full max-w-sm overflow-hidden rounded-3xl bg-gray-950 shadow-[0_14px_36px_-16px_rgba(17,24,39,0.4)] ring-1 ring-gray-900/5">
                            <video id="videoStream_3" autoplay playsinline muted class="h-full w-full object-cover"></video>
                            <canvas id="canvas_3" class="hidden"></canvas>
                            <img id="preview_3" class="fade-in-img hidden absolute inset-0 h-full w-full object-cover" alt="Identity card preview">

                            <div id="successSeal_3" class="pointer-events-none absolute inset-0 hidden items-center justify-center bg-gray-950/40">
                                <div class="seal-pop flex h-14 w-14 items-center justify-center rounded-full bg-white">
                                    <svg class="h-7 w-7 text-emerald-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.4 7.4a1 1 0 01-1.4 0L3.3 9.5a1 1 0 111.4-1.4l3.6 3.6 6.7-6.7a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                                </div>
                            </div>

                            <div class="pointer-events-none absolute inset-0 flex items-center justify-center p-6">
                                <div class="relative aspect-[16/10] w-full rounded-2xl border-2 border-dashed border-white/70">
                                    <span class="absolute -left-0.5 -top-0.5 h-5 w-5 rounded-tl-2xl border-l-4 border-t-4 border-indigo-400"></span>
                                    <span class="absolute -right-0.5 -top-0.5 h-5 w-5 rounded-tr-2xl border-r-4 border-t-4 border-indigo-400"></span>
                                    <span class="absolute -bottom-0.5 -left-0.5 h-5 w-5 rounded-bl-2xl border-b-4 border-l-4 border-indigo-400"></span>
                                    <span class="absolute -bottom-0.5 -right-0.5 h-5 w-5 rounded-br-2xl border-b-4 border-r-4 border-indigo-400"></span>
                                    <div class="scanline absolute left-0 h-0.5 w-full bg-indigo-400"></div>
                                </div>
                            </div>

                            <div class="pointer-events-none absolute bottom-3 left-1/2 -translate-x-1/2 rounded-full bg-black/45 px-3.5 py-1 text-[11px] font-medium text-white backdrop-blur-sm">
                                Align the card within the frame
                            </div>
                        </div>
                    </section>

                    {{-- ================= STEP 4 : CAMERA - SELFIE ================= --}}
                    <section class="step-panel w-full flex-col" data-panel="4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold tracking-tight text-gray-900">Take a selfie</h2>
                                <p class="mt-0.5 text-sm text-gray-500">Hold your identity card next to your face.</p>
                            </div>
                            <span class="flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-700 ring-1 ring-inset ring-emerald-100">
                                <span class="relative flex h-2 w-2">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                </span>
                                Active
                            </span>
                        </div>

                        <div class="relative mx-auto mt-4 h-[38vh] max-h-[400px] min-h-[220px] w-full max-w-sm overflow-hidden rounded-3xl bg-gray-950 shadow-[0_14px_36px_-16px_rgba(17,24,39,0.4)] ring-1 ring-gray-900/5">
                            <video id="videoStream_4" autoplay playsinline muted class="mirror h-full w-full object-cover"></video>
                            <canvas id="canvas_4" class="hidden"></canvas>
                            <img id="preview_4" class="fade-in-img hidden absolute inset-0 h-full w-full object-cover" alt="Selfie preview">

                            <div id="successSeal_4" class="pointer-events-none absolute inset-0 hidden items-center justify-center bg-gray-950/40">
                                <div class="seal-pop flex h-14 w-14 items-center justify-center rounded-full bg-white">
                                    <svg class="h-7 w-7 text-emerald-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.4 7.4a1 1 0 01-1.4 0L3.3 9.5a1 1 0 111.4-1.4l3.6 3.6 6.7-6.7a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                                </div>
                            </div>

                            <div class="pointer-events-none absolute inset-0 flex items-center justify-center p-6">
                                <div class="relative aspect-[3/4] h-full max-h-64 rounded-full border-2 border-dashed border-white/70">
                                    <div class="scanline absolute left-0 h-0.5 w-full bg-indigo-400"></div>
                                </div>
                            </div>

                            <div class="pointer-events-none absolute bottom-3 left-1/2 -translate-x-1/2 rounded-full bg-black/45 px-3.5 py-1 text-[11px] font-medium text-white backdrop-blur-sm">
                                Center your face inside the outline
                            </div>
                        </div>
                    </section>

                    {{-- ================= STEP 5 : REVIEW ================= --}}
                    <section class="step-panel w-full flex-col" data-panel="5">
                        <div class="flex flex-col items-center text-center">
                            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-50 ring-1 ring-gray-100">
                                <svg class="h-5 w-5 text-gray-700" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M10 2 4 4.5v4.4c0 4 2.6 6.6 6 7.6 3.4-1 6-3.6 6-7.6V4.5L10 2z" stroke-linejoin="round"/><path d="M7.3 10 9.3 12l3.4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <h2 class="mt-2.5 text-lg font-semibold tracking-tight text-gray-900">Review &amp; submit</h2>
                            <p class="mt-1 max-w-xs text-sm text-gray-500">Double-check both photos are clear before sending your request.</p>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="card-hover overflow-hidden rounded-2xl border border-gray-200 bg-white p-2.5 shadow-sm">
                                <p class="mb-1.5 px-0.5 text-[11px] font-semibold text-gray-500">Identity card</p>
                                <div class="img-zoom h-[22vh] max-h-[220px] min-h-[140px] w-full overflow-hidden rounded-xl bg-gray-100">
                                    <img id="reviewIdentity" class="fade-in-img h-full w-full object-cover" alt="Identity card">
                                </div>
                            </div>
                            <div class="card-hover overflow-hidden rounded-2xl border border-gray-200 bg-white p-2.5 shadow-sm">
                                <p class="mb-1.5 px-0.5 text-[11px] font-semibold text-gray-500">Selfie</p>
                                <div class="img-zoom h-[22vh] max-h-[220px] min-h-[140px] w-full overflow-hidden rounded-xl bg-gray-100">
                                    <img id="reviewSelfie" class="fade-in-img h-full w-full object-cover" alt="Selfie">
                                </div>
                            </div>
                        </div>

                        <div class="card-hover compact-hide mt-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">Checklist</p>
                            <ul class="mt-2 space-y-1.5">
                                <li class="flex items-center gap-2 text-[13px] text-gray-700">
                                    <svg class="h-3.5 w-3.5 flex-shrink-0 text-emerald-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.4 7.4a1 1 0 01-1.4 0L3.3 9.5a1 1 0 111.4-1.4l3.6 3.6 6.7-6.7a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                                    Full name and email confirmed
                                </li>
                                <li class="flex items-center gap-2 text-[13px] text-gray-700">
                                    <svg class="h-3.5 w-3.5 flex-shrink-0 text-emerald-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.4 7.4a1 1 0 01-1.4 0L3.3 9.5a1 1 0 111.4-1.4l3.6 3.6 6.7-6.7a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                                    Identity card and selfie captured
                                </li>
                            </ul>
                        </div>

                        <div class="mt-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                            <label class="flex cursor-pointer items-start gap-2.5">
                                <input type="checkbox" id="confirmCheckbox" required class="mt-0.5 h-4 w-4 rounded border-gray-300 text-gray-900 transition focus:ring-gray-900">
                                <span class="text-[13px] leading-relaxed text-gray-600">
                                    I confirm this information is correct and matches my official identity document.
                                </span>
                            </label>
                        </div>
                    </section>

                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- FOOTER — fixed height, always visible, never pushed down      --}}
            {{-- ============================================================ --}}
            <footer class="flex-none border-t border-gray-100 bg-white">
                <div class="mx-auto max-w-2xl px-6 py-3.5 sm:px-10">

                    <div data-footer="1" class="footer-panel">
                        <button
                            type="button"
                            onclick="goFromStep1()"
                            class="btn-press w-full rounded-2xl bg-gray-900 py-3.5 text-sm font-semibold text-white shadow-[0_8px_20px_-8px_rgba(17,24,39,0.45)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-gray-800"
                        >
                            Begin verification
                        </button>
                    </div>

                    <div data-footer="2" class="footer-panel hidden flex gap-3">
                        <button type="button" onclick="goToStep(1)" class="btn-press w-1/3 rounded-2xl border border-gray-200 bg-white py-3.5 text-sm font-semibold text-gray-600 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-gray-300">
                            Back
                        </button>
                        <button type="button" onclick="startCameraStep(3)" class="btn-press w-2/3 rounded-2xl bg-gray-900 py-3.5 text-sm font-semibold text-white shadow-[0_8px_20px_-8px_rgba(17,24,39,0.45)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-gray-800">
                            Open camera
                        </button>
                    </div>

                    <div data-footer="3" class="footer-panel hidden">
                        <div class="flex gap-3">
                            <button type="button" onclick="goToStep(2)" class="btn-press w-1/4 rounded-2xl border border-gray-200 bg-white py-3.5 text-sm font-semibold text-gray-600 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-gray-300">
                                Back
                            </button>
                            <button type="button" id="captureBtn_3" onclick="captureFrame(3)" class="btn-press w-3/4 rounded-2xl bg-gray-900 py-3.5 text-sm font-semibold text-white shadow-[0_8px_20px_-8px_rgba(17,24,39,0.45)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-gray-800">
                                Capture
                            </button>
                        </div>
                        <div id="afterCapture_3" class="mt-3 hidden flex gap-3">
                            <button type="button" onclick="retake(3)" class="btn-press w-1/2 rounded-2xl border border-gray-200 bg-white py-3.5 text-sm font-semibold text-gray-600 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-gray-300">
                                Retake
                            </button>
                            <button type="button" onclick="startCameraStep(4)" class="btn-press w-1/2 rounded-2xl bg-emerald-600 py-3.5 text-sm font-semibold text-white shadow-[0_8px_20px_-8px_rgba(5,150,105,0.4)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-emerald-500">
                                Continue
                            </button>
                        </div>
                    </div>

                    <div data-footer="4" class="footer-panel hidden">
                        <div class="flex gap-3">
                            <button type="button" onclick="goToStep(3)" class="btn-press w-1/4 rounded-2xl border border-gray-200 bg-white py-3.5 text-sm font-semibold text-gray-600 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-gray-300">
                                Back
                            </button>
                            <button type="button" id="captureBtn_4" onclick="captureFrame(4)" class="btn-press w-3/4 rounded-2xl bg-gray-900 py-3.5 text-sm font-semibold text-white shadow-[0_8px_20px_-8px_rgba(17,24,39,0.45)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-gray-800">
                                Capture
                            </button>
                        </div>
                        <div id="afterCapture_4" class="mt-3 hidden flex gap-3">
                            <button type="button" onclick="retake(4)" class="btn-press w-1/2 rounded-2xl border border-gray-200 bg-white py-3.5 text-sm font-semibold text-gray-600 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-gray-300">
                                Retake
                            </button>
                            <button type="button" onclick="goToStep(5)" class="btn-press w-1/2 rounded-2xl bg-emerald-600 py-3.5 text-sm font-semibold text-white shadow-[0_8px_20px_-8px_rgba(5,150,105,0.4)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-emerald-500">
                                Continue
                            </button>
                        </div>
                    </div>

                    <div data-footer="5" class="footer-panel hidden flex gap-3">
                        <button type="button" onclick="goToStep(4)" class="btn-press w-1/3 rounded-2xl border border-gray-200 bg-white py-3.5 text-sm font-semibold text-gray-600 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-gray-300">
                            Back
                        </button>
                        <button type="submit" id="submitBtn" class="btn-press w-2/3 rounded-2xl bg-gray-900 py-3.5 text-sm font-semibold text-white shadow-[0_8px_20px_-8px_rgba(17,24,39,0.45)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-gray-800">
                            Submit join request
                        </button>
                    </div>

                </div>
            </footer>

        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.14.1/cdn.min.js" defer></script>

    <script>
        // ---------------------------------------------------------------
        // Alpine: purely presentational state (progress bar, stage label).
        // Camera / capture / submit logic is kept as plain, unmodified JS —
        // same element ids, same functions, same File/MediaDevices calls
        // the backend and existing tests rely on. Only two lines were
        // added to goToStep() so the new fixed footer swaps its button
        // group in sync with the content panel.
        // ---------------------------------------------------------------
        function joinRequest() {
            return {
                step: 1,
                totalSteps: 5,
                stageLabels: ['Welcome', 'Prepare', 'Identity card', 'Selfie', 'Review'],
                init() {
                    document.addEventListener('step-changed', (e) => {
                        this.step = e.detail.step;
                    });
                },
            };
        }

        let currentStream = null;

        function emitStepChange(step) {
            document.dispatchEvent(new CustomEvent('step-changed', { detail: { step } }));
        }

        function goToStep(step) {
            document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
            document.querySelector('[data-panel="' + step + '"]').classList.add('active');

            document.querySelectorAll('.footer-panel').forEach(p => p.classList.add('hidden'));
            document.querySelector('[data-footer="' + step + '"]').classList.remove('hidden');

            emitStepChange(step);

            if (step !== 3 && step !== 4) {
                stopCamera();
            }
        }

        function goFromStep1() {
            const name = document.getElementById('full_name_display').value.trim();
            const email = document.getElementById('email_display').value.trim();

            document.getElementById('full_name_hidden').value = name;
            document.getElementById('email_hidden').value = email;

            if (!name || !email) {
                alert('Please fill in your full name and email.');
                return;
            }

            goToStep(2);
        }

        function stopCamera() {
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
                currentStream = null;
            }
        }

        async function startCameraStep(step) {
            goToStep(step);

            const video = document.getElementById('videoStream_' + step);
            const preview = document.getElementById('preview_' + step);
            const captureBtn = document.getElementById('captureBtn_' + step);
            const afterCapture = document.getElementById('afterCapture_' + step);
            const seal = document.getElementById('successSeal_' + step);

            preview.classList.add('hidden');
            seal.classList.add('hidden');
            seal.classList.remove('flex');
            video.classList.remove('hidden');
            captureBtn.classList.remove('hidden');
            afterCapture.classList.add('hidden');

            stopCamera();

            try {
                const facingMode = step === 4 ? 'user' : 'environment';
                currentStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: facingMode },
                    audio: false,
                });
                video.srcObject = currentStream;
            } catch (err) {
                alert('Unable to access the camera. Please check your permissions.');
                console.error(err);
            }
        }

        function captureFrame(step) {
            const video = document.getElementById('videoStream_' + step);
            const canvas = document.getElementById('canvas_' + step);
            const preview = document.getElementById('preview_' + step);
            const captureBtn = document.getElementById('captureBtn_' + step);
            const afterCapture = document.getElementById('afterCapture_' + step);
            const seal = document.getElementById('successSeal_' + step);

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            const ctx = canvas.getContext('2d');

            if (step === 4) {
                ctx.translate(canvas.width, 0);
                ctx.scale(-1, 1);
            }

            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            canvas.toBlob(function (blob) {
                const url = URL.createObjectURL(blob);
                preview.src = url;
                preview.classList.remove('hidden');
                video.classList.add('hidden');
                captureBtn.classList.add('hidden');

                seal.classList.remove('hidden');
                seal.classList.add('flex');
                setTimeout(() => {
                    seal.classList.add('hidden');
                    seal.classList.remove('flex');
                    afterCapture.classList.remove('hidden');
                }, 500);

                stopCamera();
                assignFileToInput(step, blob);

                if (step === 3) {
                    document.getElementById('reviewIdentity').src = url;
                } else if (step === 4) {
                    document.getElementById('reviewSelfie').src = url;
                }
            }, 'image/jpeg', 0.92);
        }

        function assignFileToInput(step, blob) {
            const fileName = step === 3 ? 'identity_card.jpg' : 'selfie_with_identity_card.jpg';
            const inputId = step === 3 ? 'identity_card_input' : 'selfie_input';
            const input = document.getElementById(inputId);

            const file = new File([blob], fileName, { type: 'image/jpeg' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
        }

        function retake(step) {
            startCameraStep(step);
        }

        document.getElementById('joinRequestForm').addEventListener('submit', function (e) {
            const identityInput = document.getElementById('identity_card_input');
            const selfieInput = document.getElementById('selfie_input');
            const confirmCheckbox = document.getElementById('confirmCheckbox');

            if (!identityInput.files.length || !selfieInput.files.length) {
                e.preventDefault();
                alert('Please capture both your identity card and selfie before submitting.');
                return;
            }

            if (!confirmCheckbox.checked) {
                e.preventDefault();
                alert('Please confirm that the information is correct.');
                return;
            }
        });

        window.addEventListener('beforeunload', stopCamera);
    </script>

</body>
</html>