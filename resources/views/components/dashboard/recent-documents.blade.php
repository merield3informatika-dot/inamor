@props([
    'documents' => [],
    'showDummy' => true,
])

@php
    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */

    $items = collect($documents);

    /*
    |--------------------------------------------------------------------------
    | Dummy documents
    |--------------------------------------------------------------------------
    */

    if ($items->isEmpty() && $showDummy) {
        $items = collect([
            (object) [
                'id' => null,
                'title' => 'KETENTUAN PRAKTIK KERJA LAPANGAN',
                'file_name' => 'ketentuan-pkl-2026.pdf',
                'file_size' => 24576,
                'status' => 'ready',
                'category' => 'Dokumen',
                'created_at' => now()->subHour(),
            ],

            (object) [
                'id' => null,
                'title' => 'data mahasiswa',
                'file_name' => 'data-mahasiswa.xlsx',
                'file_size' => 8192,
                'status' => 'ready',
                'category' => 'Dokumen',
                'created_at' => now()->subHour(),
            ],

            (object) [
                'id' => null,
                'title' => 'kalender akademik 26-27',
                'file_name' => 'kalender-akademik.pdf',
                'file_size' => 2001988,
                'status' => 'ready',
                'category' => 'Dokumen',
                'created_at' => now()->subHours(2),
            ],

            (object) [
                'id' => null,
                'title' => 'PANDUAN v2',
                'file_name' => 'panduan-v2.pdf',
                'file_size' => 2048,
                'status' => 'archived',
                'category' => 'Dokumen',
                'created_at' => now()->subDays(3),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    $formatSize = function ($bytes) {
        $bytes = (int) $bytes;

        if ($bytes <= 0) {
            return '—';
        }

        if ($bytes >= 1024 * 1024) {
            return number_format($bytes / 1024 / 1024, 1) . ' MB';
        }

        return number_format($bytes / 1024, 0) . ' KB';
    };

    $getExtension = function ($document) {
        $fileName = $document->file_name ?? '';

        if ($fileName !== '') {
            return strtoupper(
                pathinfo($fileName, PATHINFO_EXTENSION)
            ) ?: 'DOC';
        }

        return strtoupper($document->extension ?? 'DOC');
    };

    $statusConfig = function ($status) {
        return match (strtolower((string) $status)) {
            'ready', 'success', 'completed' => [
                'label' => 'READY',
                'dot' => 'bg-emerald-500',
                'text' => 'text-emerald-700',
                'bg' => 'bg-emerald-50',
                'border' => 'border-emerald-100',
            ],

            'processing', 'pending' => [
                'label' => 'PROCESSING',
                'dot' => 'bg-amber-500',
                'text' => 'text-amber-700',
                'bg' => 'bg-amber-50',
                'border' => 'border-amber-100',
            ],

            'failed', 'error' => [
                'label' => 'FAILED',
                'dot' => 'bg-red-500',
                'text' => 'text-red-700',
                'bg' => 'bg-red-50',
                'border' => 'border-red-100',
            ],

            'archived' => [
                'label' => 'ARCHIVED',
                'dot' => 'bg-gray-400',
                'text' => 'text-gray-600',
                'bg' => 'bg-gray-100',
                'border' => 'border-gray-200',
            ],

            default => [
                'label' => strtoupper((string) $status ?: 'DOCUMENT'),
                'dot' => 'bg-gray-400',
                'text' => 'text-gray-600',
                'bg' => 'bg-gray-50',
                'border' => 'border-gray-200',
            ],
        };
    };
@endphp


{{-- ========================================================================= --}}
{{-- OUTER CARD                                                               --}}
{{-- ========================================================================= --}}

<div class="flex h-full min-w-0 flex-col rounded-[20px] border border-gray-200/80 bg-white p-4 shadow-sm">

    {{-- Header --}}
    <div class="mb-3 flex items-start justify-between gap-3">

        <div class="min-w-0">

            <h3 class="text-[13px] font-bold tracking-tight text-gray-900">
                Dokumen
            </h3>

            <p class="mt-0.5 text-[10.5px] font-medium text-gray-400">
                {{ $items->count() }} dokumen tersedia
            </p>

        </div>


        <a
            href="{{ route('documents.index') }}"
            class="group inline-flex shrink-0 items-center gap-1 pt-0.5 text-[10px] font-semibold text-gray-500 transition-colors hover:text-indigo-600"
        >

            <span>Lihat semua</span>

            <svg
                class="h-3 w-3 transition-transform duration-200 group-hover:translate-x-0.5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 5l7 7-7 7"
                />
            </svg>

        </a>

    </div>


    {{-- Documents --}}
    @if($items->isNotEmpty())

        <div class="flex flex-1 flex-col gap-2">

            @foreach($items as $document)

                @php
                    $status = $statusConfig(
                        $document->status ?? 'ready'
                    );

                    $extension = $getExtension($document);

                    $canOpen = !empty($document->id);

                    $href = $canOpen
                        ? route('documents.show', $document)
                        : null;
                @endphp


                {{-- Document Card --}}
                <div
                    class="group relative min-w-0 overflow-hidden rounded-[14px] border border-gray-200 bg-white transition-all duration-200 hover:border-gray-300 hover:shadow-sm"
                >

                    {{-- Real document clickable layer --}}
                    @if($canOpen)

                        <a
                            href="{{ $href }}"
                            class="absolute inset-0 z-10"
                            aria-label="Lihat {{ $document->title }}"
                        ></a>

                    @endif


                    <div class="flex min-w-0 items-center gap-2.5 px-2.5 py-2.5">

                        {{-- File icon --}}
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[10px] border border-gray-200 bg-white"
                        >

                            @if($extension === 'PDF')

                                <svg
                                    class="h-[16px] w-[16px] text-red-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19.5 14.25v-9A2.25 2.25 0 0017.25 3H8.25A2.25 2.25 0 006 5.25v13.5A2.25 2.25 0 008.25 21h9A2.25 2.25 0 0019.5 18.75v-4.5"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 15.75h6m-6-3h4.5"
                                    />
                                </svg>

                            @else

                                <span class="text-[7.5px] font-extrabold tracking-wide text-gray-500">
                                    {{ $extension }}
                                </span>

                            @endif

                        </div>


                        {{-- Main information --}}
                        <div class="min-w-0 flex-1">

                            {{-- Title --}}
                            <h4
                                class="truncate text-[11.5px] font-semibold leading-[15px] text-gray-900 transition-colors group-hover:text-indigo-600"
                                title="{{ $document->title }}"
                            >
                                {{ $document->title }}
                            </h4>


                            {{-- Metadata --}}
                            <div class="mt-1 flex min-w-0 items-center gap-1 text-[9px] font-medium leading-none text-gray-400">

                                <span class="shrink-0">
                                    {{ $document->category ?? 'Dokumen' }}
                                </span>

                                <span class="text-gray-300">
                                    •
                                </span>

                                <span class="shrink-0">
                                    {{ $formatSize($document->file_size ?? 0) }}
                                </span>

                                <span class="text-gray-300">
                                    •
                                </span>

                                <span class="flex min-w-0 items-center gap-0.5 truncate">

                                    <svg
                                        class="h-2.5 w-2.5 shrink-0"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>

                                    <span class="truncate">
                                        {{ $document->created_at?->diffForHumans() ?? 'Baru saja' }}
                                    </span>

                                </span>

                            </div>

                        </div>


                        {{-- Status --}}
                        <div class="relative z-20 shrink-0">

                            <span
                                class="inline-flex items-center gap-1 rounded-full border px-1.5 py-[3px] text-[7px] font-bold tracking-[0.06em] {{ $status['bg'] }} {{ $status['border'] }} {{ $status['text'] }}"
                            >

                                <span
                                    class="h-1.5 w-1.5 shrink-0 rounded-full {{ $status['dot'] }}"
                                ></span>

                                {{ $status['label'] }}

                            </span>

                        </div>


                        {{-- Open arrow --}}
                        @if($canOpen)

                            <div class="relative z-20 hidden shrink-0 sm:block">

                                <a
                                    href="{{ $href }}"
                                    class="flex h-6 w-6 items-center justify-center rounded-lg text-gray-300 opacity-0 transition-all duration-200 hover:bg-gray-50 hover:text-indigo-600 group-hover:opacity-100"
                                    aria-label="Buka dokumen"
                                >

                                    <svg
                                        class="h-3 w-3"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 5l7 7-7 7"
                                        />
                                    </svg>

                                </a>

                            </div>

                        @endif

                    </div>


                    {{-- Bottom hover accent --}}
                    <div
                        class="pointer-events-none absolute bottom-0 left-0 h-[1.5px] w-0 bg-indigo-500 transition-all duration-300 group-hover:w-full"
                    ></div>

                </div>

            @endforeach

        </div>


    @else

        {{-- Empty state --}}
        <div
            class="flex flex-1 flex-col items-center justify-center rounded-[16px] border border-dashed border-gray-200 bg-gray-50/40 px-4 py-8 text-center"
        >

            <div
                class="mb-3 flex h-11 w-11 items-center justify-center rounded-[13px] border border-gray-100 bg-white text-gray-400"
            >

                <svg
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
                    />
                </svg>

            </div>

            <h4 class="text-[12.5px] font-semibold text-gray-900">
                Belum ada dokumen
            </h4>

            <p class="mt-1 max-w-[210px] text-[10.5px] leading-relaxed text-gray-500">
                Dokumen yang baru diunggah atau dibagikan akan muncul di sini.
            </p>

            <a
                href="{{ route('documents.create') }}"
                class="mt-3 inline-flex items-center gap-1.5 rounded-[9px] bg-gray-900 px-3 py-1.5 text-[10px] font-semibold text-white transition-colors hover:bg-gray-800"
            >

                <svg
                    class="h-3 w-3"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 4v16m8-8H4"
                    />
                </svg>

                Tambah Dokumen

            </a>

        </div>

    @endif

</div>