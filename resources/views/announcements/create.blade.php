<x-app-layout>

    <div class="mb-6">
        <h2 class="text-[26px] font-bold tracking-tight text-gray-900">
            Buat Pengumuman
        </h2>

        <p class="mt-1 text-[14px] text-gray-500">
            Pengumuman disimpan sebagai draft terlebih dahulu
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('announcements.store') }}"
        enctype="multipart/form-data"
        class="max-w-2xl rounded-[20px] border border-gray-100 bg-white p-6 shadow-[0_4px_24px_rgba(0,0,0,0.02)]"
    >

        @csrf

        {{-- Judul --}}
        <div class="mb-5">

            <label class="mb-1.5 block text-[13px] font-semibold text-gray-700">
                Judul
            </label>

            <input
                type="text"
                name="title"
                value="{{ old('title') }}"
                placeholder="Masukkan judul pengumuman..."
                class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-[13.5px] transition focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30"
            >

            @error('title')
                <p class="mt-1 text-[12px] text-red-500">
                    {{ $message }}
                </p>
            @enderror

        </div>

        {{-- Thumbnail --}}
        <div class="mb-5">

            <label class="mb-1.5 block text-[13px] font-semibold text-gray-700">
                Thumbnail
                <span class="font-normal text-gray-400">
                    (Opsional)
                </span>
            </label>

            <label
                class="group relative flex cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50/70 px-6 py-8 transition hover:border-blue-300 hover:bg-blue-50/30"
            >

                <input
                    type="file"
                    name="thumbnail"
                    accept="image/jpeg,image/png,image/webp"
                    class="sr-only"
                    onchange="previewAnnouncementThumbnail(event)"
                >

                <div
                    id="thumbnail-placeholder"
                    class="text-center"
                >
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-xl text-blue-600">
                        🖼️
                    </div>

                    <p class="text-[13px] font-semibold text-gray-700">
                        Tambahkan thumbnail
                    </p>

                    <p class="mt-1 text-[11px] text-gray-400">
                        JPG, PNG, atau WEBP · Maksimal 2 MB
                    </p>
                </div>

                <img
                    id="thumbnail-preview"
                    src=""
                    alt="Preview thumbnail"
                    class="hidden max-h-52 w-full rounded-xl object-cover"
                >

            </label>

            @error('thumbnail')
                <p class="mt-1 text-[12px] text-red-500">
                    {{ $message }}
                </p>
            @enderror

        </div>

        {{-- Content --}}
        <div class="mb-6">

            <label class="mb-1.5 block text-[13px] font-semibold text-gray-700">
                Isi Pengumuman
            </label>

            <textarea
                name="content"
                rows="8"
                placeholder="Tulis isi pengumuman..."
                class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-[13.5px] transition focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30"
            >{{ old('content') }}</textarea>

            @error('content')
                <p class="mt-1 text-[12px] text-red-500">
                    {{ $message }}
                </p>
            @enderror

        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">

            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-5 py-2.5 text-[13px] font-semibold text-white transition hover:bg-blue-700 active:scale-[0.98]"
            >
                Simpan Draft
            </button>

            <a
                href="{{ route('announcements.index') }}"
                class="rounded-xl border border-gray-100 bg-gray-50 px-5 py-2.5 text-[13px] font-semibold text-gray-600 transition hover:bg-gray-100"
            >
                Batal
            </a>

        </div>

    </form>

    <script>
        function previewAnnouncementThumbnail(event) {
            const file = event.target.files?.[0];

            const preview = document.getElementById(
                'thumbnail-preview'
            );

            const placeholder = document.getElementById(
                'thumbnail-placeholder'
            );

            if (!file) {
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
                preview.src = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;

                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };

            reader.readAsDataURL(file);
        }
    </script>

</x-app-layout>