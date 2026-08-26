<x-app-layout>

    <div class="mb-6">
        <h2 class="text-[26px] font-bold tracking-tight text-gray-900">
            Edit Pengumuman
        </h2>

        <p class="mt-1 text-[14px] text-gray-500">
            Perubahan tidak memicu notifikasi baru
        </p>
    </div>


    <form
        method="POST"
        action="{{ route('announcements.update', $announcement) }}"
        enctype="multipart/form-data"
        class="max-w-2xl rounded-[20px] border border-gray-100 bg-white p-6 shadow-[0_4px_24px_rgba(0,0,0,0.02)]"
    >

        @csrf
        @method('PUT')


        {{-- =====================================================
            JUDUL
        ====================================================== --}}

        <div class="mb-5">

            <label class="mb-1.5 block text-[13px] font-semibold text-gray-700">
                Judul
            </label>

            <input
                type="text"
                name="title"
                value="{{ old('title', $announcement->title) }}"
                placeholder="Masukkan judul pengumuman..."
                class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-[13.5px] transition focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30"
            >

            @error('title')
                <p class="mt-1 text-[12px] text-red-500">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- =====================================================
            THUMBNAIL
        ====================================================== --}}

        <div class="mb-5">

            <label class="mb-1.5 block text-[13px] font-semibold text-gray-700">
                Thumbnail

                <span class="font-normal text-gray-400">
                    (Opsional)
                </span>
            </label>


            {{-- Current / Preview --}}
            <div
                id="thumbnail-container"
                class="relative mb-3 overflow-hidden rounded-2xl border border-gray-200 bg-gray-50"
            >

                @if($announcement->thumbnail)

                    <img
                        id="thumbnail-preview"
                        src="{{ asset('storage/' . $announcement->thumbnail) }}"
                        alt="{{ $announcement->title }}"
                        class="h-[220px] w-full object-cover"
                    >

                @else

                    <div
                        id="thumbnail-empty"
                        class="flex h-[180px] flex-col items-center justify-center text-center"
                    >

                        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-500">
                            <svg
                                class="h-6 w-6"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                            >
                                <rect
                                    x="3"
                                    y="4"
                                    width="18"
                                    height="16"
                                    rx="2"
                                />

                                <circle
                                    cx="8.5"
                                    cy="9"
                                    r="1.5"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m4 17 4.5-4.5 3 3 2.5-2.5L20 18"
                                />
                            </svg>
                        </div>

                        <p class="text-[12px] font-medium text-gray-500">
                            Belum ada thumbnail
                        </p>

                    </div>

                    <img
                        id="thumbnail-preview"
                        src=""
                        alt="Preview thumbnail"
                        class="hidden h-[220px] w-full object-cover"
                    >

                @endif


                {{-- Remove button --}}

                @if($announcement->thumbnail)

                    <button
                        type="button"
                        id="remove-thumbnail-button"
                        onclick="removeThumbnail()"
                        class="absolute right-3 top-3 rounded-xl bg-black/60 px-3 py-2 text-[11px] font-semibold text-white backdrop-blur-md transition hover:bg-red-500"
                    >
                        Hapus thumbnail
                    </button>

                @endif

            </div>


            {{-- Upload --}}

            <label
                class="group flex cursor-pointer items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 transition hover:border-blue-300 hover:bg-blue-50/30"
            >

                <input
                    type="file"
                    name="thumbnail"
                    accept="image/jpeg,image/png,image/webp"
                    class="sr-only"
                    onchange="previewAnnouncementThumbnail(event)"
                >


                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-100">

                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 16V4"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m8 8 4-4 4 4"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 12v5a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-5"
                        />
                    </svg>

                </div>


                <div class="min-w-0">

                    <p class="text-[12.5px] font-semibold text-gray-700">
                        Ganti thumbnail
                    </p>

                    <p class="mt-0.5 text-[10.5px] text-gray-400">
                        JPG, PNG, WEBP · Maksimal 2 MB
                    </p>

                </div>

            </label>


            {{-- Remove state --}}
            <input
                type="hidden"
                name="remove_thumbnail"
                id="remove-thumbnail"
                value="0"
            >


            @error('thumbnail')
                <p class="mt-1 text-[12px] text-red-500">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- =====================================================
            CONTENT
        ====================================================== --}}

        <div class="mb-6">

            <label class="mb-1.5 block text-[13px] font-semibold text-gray-700">
                Isi Pengumuman
            </label>

            <textarea
                name="content"
                rows="8"
                placeholder="Tulis isi pengumuman..."
                class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-[13.5px] transition focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30"
            >{{ old('content', $announcement->content) }}</textarea>

            @error('content')
                <p class="mt-1 text-[12px] text-red-500">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- =====================================================
            ACTIONS
        ====================================================== --}}

        <div class="flex items-center gap-3">

            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-5 py-2.5 text-[13px] font-semibold text-white transition hover:bg-blue-700 active:scale-[0.98]"
            >
                Simpan Perubahan
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

            const file = event.target.files?.[0]

            const preview =
                document.getElementById('thumbnail-preview')

            const empty =
                document.getElementById('thumbnail-empty')

            const removeButton =
                document.getElementById('remove-thumbnail-button')

            const removeInput =
                document.getElementById('remove-thumbnail')


            if (!file) {
                return
            }


            const reader = new FileReader()


            reader.onload = function (e) {

                preview.src = e.target.result

                preview.classList.remove('hidden')

                if (empty) {
                    empty.classList.add('hidden')
                }

                if (removeButton) {
                    removeButton.classList.add('hidden')
                }

                removeInput.value = '0'

            }


            reader.readAsDataURL(file)
        }


        function removeThumbnail() {

            const preview =
                document.getElementById('thumbnail-preview')

            const empty =
                document.getElementById('thumbnail-empty')

            const removeButton =
                document.getElementById('remove-thumbnail-button')

            const removeInput =
                document.getElementById('remove-thumbnail')


            preview.src = ''

            preview.classList.add('hidden')


            if (empty) {
                empty.classList.remove('hidden')
            }


            if (removeButton) {
                removeButton.classList.add('hidden')
            }


            removeInput.value = '1'

        }

    </script>

</x-app-layout>