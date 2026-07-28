<x-app-layout>

    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('calendar.index') }}"
           class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h2 class="text-[22px] md:text-[24px] font-bold text-gray-900 tracking-tight">Tambah Kegiatan</h2>
            <p class="text-[13px] text-gray-500">Buat agenda baru untuk workspace Anda.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-[20px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-6 md:p-7 max-w-3xl">
        <form action="{{ route('calendar.store') }}" method="POST">
            @csrf

            @include('calendar._partials.form')

            <div class="flex items-center justify-end gap-3 mt-7 pt-5 border-t border-gray-100">
                <a href="{{ route('calendar.index') }}"
                   class="h-10 px-5 flex items-center rounded-lg text-[13px] font-semibold text-gray-600 hover:bg-gray-50 transition-colors duration-200">
                    Batal
                </a>
                <button type="submit"
                        class="h-10 px-5 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-[13px] font-semibold transition-colors duration-200">
                    Simpan Kegiatan
                </button>
            </div>
        </form>
    </div>

</x-app-layout>