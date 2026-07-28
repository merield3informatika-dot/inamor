<x-app-layout>

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('calendar.index') }}"
           class="w-9 h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h2 class="text-[22px] md:text-[24px] font-bold text-gray-900 tracking-tight">Edit Kegiatan</h2>
            <p class="text-[13px] text-gray-500">Perbarui detail agenda ini.</p>
        </div>
    </div>

    <div class="bg-white rounded-[20px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-6 md:p-7 max-w-3xl">

        <!-- FORM UPDATE — berdiri sendiri, tertutup rapat sebelum form lain dibuka -->
        <form action="{{ route('calendar.update', $event) }}" method="POST">
            @csrf
            @method('PUT')

            @include('calendar._partials.form', ['event' => $event])

            <div class="flex items-center justify-end gap-3 mt-7 pt-5 border-t border-gray-100">
                <a href="{{ route('calendar.index') }}"
                   class="h-10 px-5 flex items-center rounded-lg text-[13px] font-semibold text-gray-600 hover:bg-gray-50 transition-colors duration-200">
                    Batal
                </a>
                <button type="submit"
                        class="h-10 px-5 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-[13px] font-semibold transition-colors duration-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>

        <!-- FORM DESTROY — terpisah total, di luar form update, tidak bersarang -->
        <form action="{{ route('calendar.destroy', $event) }}" method="POST"
              class="mt-4 pt-4 border-t border-gray-100"
              onsubmit="return confirm('Hapus kegiatan ini? Tindakan tidak dapat dibatalkan.');">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="h-10 px-4 flex items-center gap-1.5 rounded-lg text-[13px] font-semibold text-red-600 hover:bg-red-50 transition-colors duration-200">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus Kegiatan
            </button>
        </form>
    </div>

</x-app-layout>