<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <style>
    [x-cloak] {
        display: none !important;
    }
</style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'INAMOR') }}</title>

    <!-- Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Compact premium custom scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #D1D5DB; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-gray-900 flex h-screen overflow-hidden selection:bg-blue-100 selection:text-blue-900 text-[13px]">

    <!-- Sidebar -->
    <x-sidebar />

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col h-screen transition-all md:pl-[240px]">
        
        <!-- Topbar -->
        <x-topbar />

        <!-- Dashboard Canvas (Dense & Compact Spacing) -->
        <main class="flex-1 overflow-y-auto px-5 md:px-6 pb-8 pt-4">
            <div class="max-w-[1560px] mx-auto w-full">
                {{ $slot }}
            </div>
        </main>

    </div>

</body>
</html>