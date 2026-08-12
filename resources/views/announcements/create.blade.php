<x-app-layout>

    <div class="mb-6">
        <h2 class="text-[26px] font-bold text-gray-900 tracking-tight">Buat Pengumuman</h2>
        <p class="text-[14px] text-gray-500 mt-1">Pengumuman disimpan sebagai draft terlebih dahulu</p>
    </div>

    <form method="POST" action="{{ route('announcements.store') }}"
          class="bg-white rounded-[20px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-6 max-w-2xl">
        @csrf

        <div class="mb-5">
            <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Judul</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-[13.5px] focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">
            @error('title')
                <p class="mt-1 text-[12px] text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Isi Pengumuman</label>
            <textarea name="content" rows="8"
                      class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-[13.5px] focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">{{ old('content') }}</textarea>
            @error('content')
                <p class="mt-1 text-[12px] text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 text-white text-[13px] font-semibold hover:bg-blue-700 transition-colors">
                Simpan Draft
            </button>
            <a href="{{ route('announcements.index') }}" class="px-5 py-2.5 rounded-xl bg-gray-50 border border-gray-100 text-gray-600 text-[13px] font-semibold hover:bg-gray-100 transition-colors">
                Batal
            </a>
        </div>

    </form>

</x-app-layout>