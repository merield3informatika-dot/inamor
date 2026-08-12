<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Inamor')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])

</head>


<body class="min-h-screen bg-[#F8FAFC] text-gray-900 antialiased">


    {{-- =========================================================
         PAGE CONTENT
    ========================================================== --}}

    <main class="min-h-[calc(100vh-128px)] pb-20">

        @yield('content')

    </main>


    {{-- =========================================================
         MOBILE BOTTOM NAVIGATION
    ========================================================== --}}

    <nav class="fixed inset-x-0 bottom-0 z-40 border-t border-gray-100 bg-white/95 backdrop-blur">

        <div class="grid h-16 grid-cols-5 px-2">


            {{-- HOME --}}

            <a
                href="{{ route('mobile.home') }}"
                class="flex flex-col items-center justify-center gap-1 text-blue-600"
            >

                <span class="text-lg">
                    ⌂
                </span>

                <span class="text-[10px] font-semibold">
                    Home
                </span>

            </a>


            {{-- CALENDAR --}}

            <a
                href="{{ route('mobile.calendar') }}"
                class="flex flex-col items-center justify-center gap-1 text-gray-400"
            >

                <span class="text-lg">
                    ◫
                </span>

                <span class="text-[10px] font-semibold">
                    Calendar
                </span>

            </a>


            {{-- AI --}}

            <a
                href="{{ route('mobile.ai') }}"
                class="flex flex-col items-center justify-center gap-1 text-gray-400"
            >

                <span class="text-lg">
                    ✦
                </span>

                <span class="text-[10px] font-semibold">
                    AI
                </span>

            </a>


            {{-- WORKSPACE CHAT --}}

            <a
                href="{{ route('mobile.chat') }}"
                class="flex flex-col items-center justify-center gap-1 text-gray-400"
            >

                <span class="text-lg">
                    ◌
                </span>

                <span class="text-[10px] font-medium">
                    Chat
                </span>

            </a>


            {{-- ACCOUNT --}}

           <a
    href="{{ route('mobile.account') }}"
    class="flex flex-col items-center justify-center gap-1 text-gray-400"
>
    <span class="text-lg">
        ♙
    </span>

    <span class="text-[10px] font-medium">
        Account
    </span>
</a>

        </div>

    </nav>


    {{-- =========================================================
         PAGE SCRIPTS
    ========================================================== --}}

    @stack('scripts')


</body>

</html>