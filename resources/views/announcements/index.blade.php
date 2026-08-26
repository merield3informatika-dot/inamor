<x-app-layout>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-[26px] font-bold tracking-tight text-gray-900 md:text-[30px]">
                Pengumuman
            </h2>

            <p class="mt-1 text-[14px] text-gray-500">
                Informasi resmi untuk seluruh anggota workspace
            </p>
        </div>

        @if($isAdmin)
            <a
                href="{{ route('announcements.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-[13px] font-semibold text-white transition-colors hover:bg-blue-700"
            >
                + Buat Pengumuman
            </a>
        @endif
    </div>

    @if(session('status'))
        <div class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-[13px] font-medium text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex flex-col gap-4">

        @forelse($announcements as $announcement)

            <div
                class="overflow-hidden rounded-[20px] border border-gray-100 bg-white shadow-[0_4px_24px_rgba(0,0,0,0.02)] transition duration-200 hover:border-gray-200 hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)]"
            >

                <div class="flex flex-col md:flex-row">

                    {{-- =====================================================
                        THUMBNAIL
                    ====================================================== --}}

                    <div class="relative h-[190px] w-full shrink-0 overflow-hidden bg-gradient-to-br from-blue-50 via-indigo-50 to-violet-50 md:h-auto md:w-[230px]">

                        @if($announcement->thumbnail)

                            <img
                                src="{{ asset('storage/' . $announcement->thumbnail) }}"
                                alt="{{ $announcement->title }}"
                                class="h-full w-full object-cover transition duration-500 hover:scale-[1.03]"
                            >

                            {{-- subtle overlay --}}
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-white/5"></div>

                        @else

                            {{-- =================================================
                                FALLBACK
                            ================================================== --}}

                            <div class="flex h-full min-h-[190px] items-center justify-center">

                                <div class="flex flex-col items-center text-center">

                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm">
                                        <svg
                                            class="h-7 w-7 text-blue-500"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M6.5 17A2.5 2.5 0 0 1 4 14.5v-8A2.5 2.5 0 0 1 6.5 4H18a2 2 0 0 1 2 2v11"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M8 8h7M8 11.5h5"
                                            />
                                        </svg>
                                    </div>

                                    <span class="mt-3 text-[11px] font-semibold text-blue-500">
                                        Pengumuman
                                    </span>

                                </div>

                            </div>

                        @endif

                    </div>


                    {{-- =====================================================
                        CONTENT
                    ====================================================== --}}

                    <div class="min-w-0 flex-1 p-5 md:p-6">

                        <div class="flex items-start justify-between gap-4">

                            <div class="min-w-0 flex-1">

                                {{-- Title + Status --}}
                                <div class="flex flex-wrap items-center gap-2">

                                    <h3 class="text-[16px] font-bold leading-snug text-gray-900">
                                        {{ $announcement->title }}
                                    </h3>

                                    @if($announcement->status === 'published')

                                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10.5px] font-bold uppercase text-emerald-700">
                                            Published
                                        </span>

                                    @else

                                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[10.5px] font-bold uppercase text-gray-600">
                                            Draft
                                        </span>

                                    @endif

                                </div>


                                {{-- Author + Date --}}
                                <p class="mt-1.5 text-[12px] text-gray-400">

                                    Oleh
                                    {{ $announcement->author->name ?? 'Admin' }}

                                    @if($announcement->published_at)

                                        · Dipublikasikan
                                        {{ $announcement->published_at->diffForHumans() }}

                                    @else

                                        · Dibuat
                                        {{ $announcement->created_at->diffForHumans() }}

                                    @endif

                                </p>


                                {{-- Content --}}
                                <p class="mt-4 line-clamp-4 whitespace-pre-line text-[13.5px] leading-relaxed text-gray-600">
                                    {{ $announcement->content }}
                                </p>

                            </div>


                            {{-- =================================================
                                ADMIN ACTIONS
                            ================================================== --}}

                            @if($isAdmin)

                                <div class="flex shrink-0 items-center gap-2">

                                    @if($announcement->status === 'draft')

                                        <form
                                            method="POST"
                                            action="{{ route('announcements.publish', $announcement) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="rounded-lg bg-blue-600 px-3 py-1.5 text-[12px] font-semibold text-white transition-colors hover:bg-blue-700"
                                            >
                                                Publish
                                            </button>
                                        </form>

                                    @endif


                                    <a
                                        href="{{ route('announcements.edit', $announcement) }}"
                                        class="rounded-lg border border-gray-100 bg-gray-50 px-3 py-1.5 text-[12px] font-semibold text-gray-600 transition-colors hover:bg-gray-100"
                                    >
                                        Edit
                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route('announcements.destroy', $announcement) }}"
                                        onsubmit="return confirm('Hapus pengumuman ini?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-red-50 px-3 py-1.5 text-[12px] font-semibold text-red-600 transition-colors hover:bg-red-100"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="flex flex-col items-center justify-center rounded-[20px] border-2 border-dashed border-gray-100 bg-gray-50/50 py-16 text-center">

                <div class="mb-3 text-3xl">
                    📢
                </div>

                <p class="text-[13px] text-gray-500">
                    Belum ada pengumuman
                </p>

            </div>

        @endforelse

    </div>

</x-app-layout>