<header class="sticky top-0 z-40 bg-white/80 backdrop-blur-sm border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
        <a href="{{ route('landing') }}">
            <x-application-logo />
        </a>

        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
            <a href="#fitur" class="hover:text-gray-900 transition-colors">Produk</a>
            <a href="#cara-kerja" class="hover:text-gray-900 transition-colors">Cara Kerja</a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-gray-900 px-3 py-2 transition-colors">
                Masuk
            </a>
            <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-gray-900 hover:bg-gray-800 px-4 py-2 rounded-lg transition-colors">
                Daftar
            </a>
        </div>
    </div>
</header>