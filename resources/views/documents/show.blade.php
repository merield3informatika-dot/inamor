<x-app-layout>

    @php
        /*
        |--------------------------------------------------------------------------
        | DOCUMENT DATA
        |--------------------------------------------------------------------------
        */

        $rawText = (string) ($document->content->raw_text ?? '');

        $status = strtolower((string) $document->status);
        $ext = strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION));

        $badgeClass = match ($status) {
            'ready' => 'bg-emerald-50 text-emerald-700 border-emerald-200/70',
            'archived' => 'bg-gray-100 text-gray-600 border-gray-200',
            'failed' => 'bg-red-50 text-red-700 border-red-200/70',
            default => 'bg-gray-50 text-gray-700 border-gray-200',
        };

        /*
        |--------------------------------------------------------------------------
        | AI HIGHLIGHTS
        |--------------------------------------------------------------------------
        |
        | Highlight datang dari query:
        | ?highlight[]=...
        |
        | Kita tetap escape seluruh document text sebelum render.
        |--------------------------------------------------------------------------
        */

        $highlightsQuery = request()->query('highlight', []);

        if (!is_array($highlightsQuery)) {
            $highlightsQuery = [$highlightsQuery];
        }

        $highlights = collect($highlightsQuery)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $hasHighlights = count($highlights) > 0;

        /*
        |--------------------------------------------------------------------------
        | TEXT FORMATTER
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | raw_text tidak diubah di database.
        |
        | Ini hanya membuat presentation layer:
        | - page divider
        | - paragraph spacing
        | - heading detection
        | - numbered list
        | - bullet list
        | - justified text
        |--------------------------------------------------------------------------
        */

        $normalizeWhitespace = function (string $text): string {
            $text = preg_replace('/[ \t]+/u', ' ', $text) ?? $text;
            return trim($text);
        };

        $isPageMarker = function (string $line): bool {
            return preg_match('/^Page\s+\d+\s*:?\s*$/i', trim($line)) === 1;
        };

        $getPageNumber = function (string $line): ?int {
            if (preg_match('/^Page\s+(\d+)\s*:?\s*$/i', trim($line), $matches)) {
                return (int) $matches[1];
            }

            return null;
        };

        $isNumberedList = function (string $line): bool {
            return preg_match(
                '/^\s*(\d{1,3})[\.\)]\s+/u',
                $line
            ) === 1;
        };

        $isBulletList = function (string $line): bool {
            return preg_match(
                '/^\s*(?:[-•●▪◦])\s+/u',
                $line
            ) === 1;
        };

        $isHeading = function (string $line): bool {
            $line = trim($line);

            if ($line === '') {
                return false;
            }

            if (mb_strlen($line) > 140) {
                return false;
            }

            if (preg_match('/^\d{1,3}[\.\)]\s+/', $line)) {
                return false;
            }

            if (preg_match('/^Page\s+\d+/i', $line)) {
                return false;
            }

            /*
             * Common document section markers.
             */
            $headingKeywords = [
                'BAB ',
                'PENDAHULUAN',
                'LATAR BELAKANG',
                'MAKSUD',
                'TUJUAN',
                'RUANG LINGKUP',
                'KETENTUAN',
                'PERSYARATAN',
                'PROSEDUR',
                'PELAKSANAAN',
                'PENUTUP',
                'KONTAK',
                'CATATAN',
                'MENGINGAT',
                'MENIMBANG',
                'MEMUTUSKAN',
                'LAMPIRAN',
                'DAFTAR ISI',
                'TENTANG',
            ];

            $upper = mb_strtoupper($line);

            foreach ($headingKeywords as $keyword) {
                if (str_starts_with($upper, $keyword)) {
                    return true;
                }
            }

            /*
             * Kalau mayoritas karakter alfabet adalah uppercase,
             * kemungkinan besar itu heading dari dokumen resmi.
             */
            $letters = preg_replace('/[^A-Za-zÀ-ÿ]/u', '', $line) ?? '';

            if (mb_strlen($letters) >= 8) {
                $upperLetters = preg_replace('/[^A-ZÀ-Ý]/u', '', $line) ?? '';

                if (
                    mb_strlen($upperLetters) / mb_strlen($letters) >= 0.78 &&
                    mb_strlen($line) <= 120
                ) {
                    return true;
                }
            }

            return false;
        };

        /*
        |--------------------------------------------------------------------------
        | HIGHLIGHT FUNCTION
        |--------------------------------------------------------------------------
        */

        $highlightLine = function (string $line) use ($highlights): string {
            $safeLine = e($line);

            foreach ($highlights as $highlight) {
                $safeHighlight = e($highlight);

                $words = preg_split(
                    '/\s+/u',
                    $safeHighlight,
                    -1,
                    PREG_SPLIT_NO_EMPTY
                );

                if (empty($words)) {
                    continue;
                }

                $quotedWords = array_map(
                    fn ($word) => preg_quote($word, '/'),
                    $words
                );

                /*
                 * OCR tolerant:
                 *
                 * "Ujian Akhir Semester"
                 *
                 * tetap bisa match meskipun text aslinya:
                 *
                 * "Ujian     Akhir
                 *  Semester"
                 */
                $pattern = '/' . implode('\s+', $quotedWords) . '/iu';

                $safeLine = preg_replace_callback(
                    $pattern,
                    function ($matches) {
                        return '<mark class="ai-highlight inline rounded-[4px] bg-amber-200 px-1 py-[1px] text-amber-950 shadow-[0_1px_2px_rgba(0,0,0,0.08)] transition-all duration-200">'
                            . $matches[0]
                            . '</mark>';
                    },
                    $safeLine
                ) ?? $safeLine;
            }

            return $safeLine;
        };

        /*
        |--------------------------------------------------------------------------
        | BUILD PAGE STRUCTURE
        |--------------------------------------------------------------------------
        */

        $normalizedRawText = str_replace(
            ["\r\n", "\r"],
            "\n",
            $rawText
        );

        $rawLines = explode("\n", $normalizedRawText);

        $pages = [];
        $currentPage = [
            'number' => null,
            'lines' => [],
        ];

        foreach ($rawLines as $line) {
            $trimmed = trim($line);

            if ($isPageMarker($trimmed)) {
                if (!empty($currentPage['lines'])) {
                    $pages[] = $currentPage;
                }

                $currentPage = [
                    'number' => $getPageNumber($trimmed),
                    'lines' => [],
                ];

                continue;
            }

            $currentPage['lines'][] = $line;
        }

        if (!empty($currentPage['lines'])) {
            $pages[] = $currentPage;
        }

        /*
        |--------------------------------------------------------------------------
        | If OCR does not provide explicit page markers,
        | treat the entire document as one page.
        |--------------------------------------------------------------------------
        */

        if (empty($pages)) {
            $pages = [[
                'number' => null,
                'lines' => [],
            ]];
        }

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $pageCount = $document->content?->page_count ?? count($pages);

        if ($pageCount < 1) {
            $pageCount = count($pages);
        }
    @endphp


    <div class="mx-auto w-full max-w-[1080px] px-4 pb-28 sm:px-6 lg:px-8">

        {{-- ================================================================
             HEADER
        ================================================================= --}}

        <div class="flex items-center gap-4 pb-7 pt-6">

            <a
                href="{{ route('documents.index') }}"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-400 shadow-sm transition-all hover:border-gray-300 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                aria-label="Back to documents"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"
                    />
                </svg>
            </a>

            <div>
                <h1 class="text-xl font-bold tracking-tight text-gray-900 sm:text-2xl">
                    Document Intelligence
                </h1>

                <p class="mt-1 text-[13px] text-gray-500">
                    Membaca dokumen asli yang diekstrak oleh sistem.
                </p>
            </div>

        </div>


        {{-- ================================================================
             DOCUMENT META
        ================================================================= --}}

        <section class="mb-6 overflow-hidden rounded-[22px] border border-gray-200/80 bg-white shadow-[0_4px_20px_-8px_rgba(0,0,0,0.08)]">

            <div class="p-5 sm:p-6">

                <div class="flex items-start gap-4">

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-[13px] bg-indigo-50 text-indigo-600">
                        <span class="text-[11px] font-bold tracking-wider">
                            {{ $ext ?: 'FILE' }}
                        </span>
                    </div>

                    <div class="min-w-0 flex-1">

                        <h2 class="truncate text-[16px] font-bold leading-tight text-gray-900 sm:text-[17px]">
                            {{ $document->title }}
                        </h2>

                        <div class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1.5 text-[11.5px] font-medium text-gray-500">

                            <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                                {{ $document->status }}
                            </span>

                            <span class="text-gray-300">•</span>

                            <span>
                                {{ number_format($document->file_size / 1024, 2) }} KB
                            </span>

                            <span class="text-gray-300">•</span>

                            <span>
                                {{ $document->created_at->format('M d, Y h:i A') }}
                            </span>

                        </div>

                    </div>

                </div>


                @if($document->content)

                    <div class="mt-5 grid grid-cols-2 gap-y-5 border-t border-gray-100 pt-5 sm:grid-cols-4 sm:gap-y-0">

                        <div>
                            <div class="mb-1 text-[9px] font-bold uppercase tracking-[0.14em] text-gray-400">
                                Method
                            </div>

                            <div class="text-[13px] font-semibold text-gray-900">
                                {{ strtoupper($document->content->extraction_method ?? 'N/A') }}
                            </div>
                        </div>


                        <div>
                            <div class="mb-1 text-[9px] font-bold uppercase tracking-[0.14em] text-gray-400">
                                OCR Used
                            </div>

                            <div class="text-[13px] font-semibold {{ $document->content->ocr_used ? 'text-indigo-600' : 'text-gray-900' }}">
                                {{ $document->content->ocr_used ? 'Yes' : 'No' }}
                            </div>
                        </div>


                        <div>
                            <div class="mb-1 text-[9px] font-bold uppercase tracking-[0.14em] text-gray-400">
                                Confidence
                            </div>

                            <div class="text-[13px] font-semibold text-gray-900">
                                {{ $document->content->confidence ? number_format($document->content->confidence, 1) . '%' : 'N/A' }}
                            </div>
                        </div>


                        <div>
                            <div class="mb-1 text-[9px] font-bold uppercase tracking-[0.14em] text-gray-400">
                                Structure
                            </div>

                            <div class="text-[13px] font-semibold text-gray-900">
                                {{ $pageCount }} {{ $pageCount === 1 ? 'page' : 'pages' }}
                            </div>
                        </div>

                    </div>

                @endif

            </div>

        </section>


        @if($document->content)


            {{-- ============================================================
                 AI REFERENCE PANEL
            ============================================================= --}}

            @if($hasHighlights)

                <section class="relative mb-6 overflow-hidden rounded-[20px] border border-indigo-100 bg-indigo-50/40 p-5 shadow-sm sm:p-6">

                    <div class="pointer-events-none absolute -right-16 -top-16 h-40 w-40 rounded-full bg-indigo-100/70 blur-3xl"></div>

                    <div class="relative">

                        <div class="flex items-center gap-2">

                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">

                                <svg
                                    class="h-3.5 w-3.5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25"
                                    />
                                </svg>

                            </div>

                            <div>

                                <h3 class="text-[11px] font-bold uppercase tracking-[0.12em] text-indigo-900">
                                    AI Used This Document
                                </h3>

                                <p class="mt-0.5 text-[12px] font-medium text-indigo-700/70">
                                    {{ count($highlights) }}
                                    {{ count($highlights) === 1 ? 'passage' : 'passages' }}
                                    digunakan untuk menjawab pertanyaan.
                                </p>

                            </div>

                        </div>


                        <div class="mt-4 space-y-2">

                            @foreach($highlights as $idx => $highlight)

                                <button
                                    type="button"
                                    onclick="scrollToHighlight({{ $idx + 1 }})"
                                    class="group block w-full text-left"
                                >

                                    <div class="flex gap-3 rounded-xl border border-indigo-100 bg-white px-4 py-3 shadow-sm transition-all hover:border-indigo-300 hover:shadow">

                                        <span class="shrink-0 pt-0.5 text-[11px] font-bold text-indigo-400">
                                            [{{ $idx + 1 }}]
                                        </span>

                                        <span class="text-[12.5px] font-medium leading-relaxed text-gray-700">
                                            "{{ \Illuminate\Support\Str::limit($highlight, 260) }}"
                                        </span>

                                    </div>

                                </button>

                            @endforeach

                        </div>

                    </div>

                </section>

            @endif


            {{-- ============================================================
                 DOCUMENT READER
            ============================================================= --}}

            <section class="overflow-hidden rounded-[22px] border border-gray-200/80 bg-white shadow-[0_4px_20px_-8px_rgba(0,0,0,0.08)]">

                {{-- Reader Header --}}

                <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/70 px-5 py-4 sm:px-7">

                    <div>

                        <h3 class="text-[11px] font-bold uppercase tracking-[0.12em] text-gray-800">
                            {{ $hasHighlights ? 'Document with AI References' : 'Document Content' }}
                        </h3>

                        @if($hasHighlights)

                            <p class="mt-1 text-[10px] text-gray-400">
                                Bagian yang digunakan AI ditandai dengan highlight.
                            </p>

                        @endif

                    </div>


                    <span class="inline-flex shrink-0 items-center gap-1.5 text-[9px] font-bold uppercase tracking-[0.12em] text-gray-400">

                        <svg
                            class="h-3.5 w-3.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                            />
                        </svg>

                        Read Only

                    </span>

                </div>


                {{-- ========================================================
                     ACTUAL DOCUMENT
                ========================================================= --}}

                <div class="bg-[#F5F6F8] px-3 py-5 sm:px-6 sm:py-8">

                    <div class="mx-auto max-w-[820px] space-y-6">

                        @foreach($pages as $pageIndex => $page)

                            <article
                                id="{{ $page['number'] ? 'document-page-' . $page['number'] : 'document-page-' . ($pageIndex + 1) }}"
                                class="document-page overflow-hidden rounded-[4px] border border-gray-200/80 bg-white shadow-[0_3px_14px_-5px_rgba(0,0,0,0.12)]"
                            >

                                {{-- Page top label --}}

                                <div class="flex items-center justify-between border-b border-gray-100 px-8 py-3 sm:px-12">

                                    <span class="text-[9px] font-bold uppercase tracking-[0.15em] text-gray-300">
                                        Document
                                    </span>

                                    <span class="text-[9px] font-semibold uppercase tracking-[0.12em] text-gray-400">
                                        Page {{ $page['number'] ?? ($pageIndex + 1) }}
                                    </span>

                                </div>


                                {{-- Page content --}}

                                <div class="document-paper px-8 py-10 sm:px-12 sm:py-14">

                                    @php
                                        $hasVisibleContent = false;
                                        $paragraphBuffer = '';

                                        $flushParagraph = function () use (&$paragraphBuffer, $highlightLine) {
                                            if (trim($paragraphBuffer) === '') {
                                                $paragraphBuffer = '';
                                                return;
                                            }

                                            $text = trim($paragraphBuffer);

                                            echo '<p class="document-paragraph">' .
                                                $highlightLine($text) .
                                                '</p>';

                                            $paragraphBuffer = '';
                                        };
                                    @endphp


                                    @foreach($page['lines'] as $line)

                                        @php
                                            $trimmed = trim($line);
                                        @endphp


                                        {{-- Empty line = paragraph boundary --}}

                                        @if($trimmed === '')

                                            @php
                                                $flushParagraph();
                                            @endphp

                                            @continue

                                        @endif


                                        {{-- Numbered list --}}

                                        @if($isNumberedList($trimmed))

                                            @php
                                                $flushParagraph();

                                                preg_match(
                                                    '/^\s*(\d{1,3})[\.\)]\s+(.*)$/us',
                                                    $trimmed,
                                                    $listMatch
                                                );

                                                $number = $listMatch[1] ?? '';
                                                $listText = $listMatch[2] ?? $trimmed;

                                                $hasVisibleContent = true;
                                            @endphp

                                            <div class="document-list-item">

                                                <div class="document-list-number">
                                                    {{ $number }}.
                                                </div>

                                                <div class="min-w-0 flex-1">
                                                    {!! $highlightLine($listText) !!}
                                                </div>

                                            </div>

                                            @continue

                                        @endif


                                        {{-- Bullet list --}}

                                        @if($isBulletList($trimmed))

                                            @php
                                                $flushParagraph();

                                                $listText = preg_replace(
                                                    '/^\s*(?:[-•●▪◦])\s+/u',
                                                    '',
                                                    $trimmed
                                                ) ?? $trimmed;

                                                $hasVisibleContent = true;
                                            @endphp

                                            <div class="document-list-item">

                                                <div class="document-list-bullet">
                                                    •
                                                </div>

                                                <div class="min-w-0 flex-1">
                                                    {!! $highlightLine($listText) !!}
                                                </div>

                                            </div>

                                            @continue

                                        @endif


                                        {{-- Heading --}}

                                        @if($isHeading($trimmed))

                                            @php
                                                $flushParagraph();
                                                $hasVisibleContent = true;
                                            @endphp

                                            <h4 class="document-heading">
                                                {!! $highlightLine($trimmed) !!}
                                            </h4>

                                            @continue

                                        @endif


                                        {{-- Normal text --}}

                                        @php
                                            $hasVisibleContent = true;

                                            if ($paragraphBuffer === '') {
                                                $paragraphBuffer = $trimmed;
                                            } else {
                                                $paragraphBuffer .= ' ' . $trimmed;
                                            }
                                        @endphp

                                    @endforeach


                                    @php
                                        $flushParagraph();
                                    @endphp


                                    @if(!$hasVisibleContent)

                                        <div class="py-10 text-center">

                                            <p class="text-[13px] text-gray-400">
                                                Tidak ada teks yang dapat ditampilkan.
                                            </p>

                                        </div>

                                    @endif

                                </div>

                            </article>

                        @endforeach

                    </div>

                </div>

            </section>


            {{-- ============================================================
                 FLOATING HIGHLIGHT NAVIGATION
            ============================================================= --}}

            @if($hasHighlights)

                <div
                    id="highlight-toolbar"
                    class="fixed bottom-6 left-1/2 z-50 flex -translate-x-1/2 translate-y-24 items-center gap-3 rounded-full bg-[#111827] px-4 py-2.5 text-white shadow-[0_12px_40px_rgba(0,0,0,0.25)] transition-transform duration-500 sm:bottom-8"
                >

                    <div class="flex items-center gap-2 text-[10px] font-bold tracking-wide">

                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-amber-400"></span>

                        <span id="current-hl">1</span>

                        <span class="text-gray-500">OF</span>

                        <span id="total-hl">0</span>

                    </div>


                    <div class="h-5 w-px bg-gray-700"></div>


                    <div class="flex items-center gap-1">

                        <button
                            type="button"
                            onclick="navigateHighlight(-1)"
                            class="rounded-lg p-1.5 text-gray-300 transition-colors hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-400"
                            aria-label="Previous highlight"
                        >
                            <svg
                                class="h-3.5 w-3.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 15l7-7 7 7"
                                />
                            </svg>
                        </button>


                        <button
                            type="button"
                            onclick="navigateHighlight(1)"
                            class="rounded-lg p-1.5 text-gray-300 transition-colors hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-400"
                            aria-label="Next highlight"
                        >
                            <svg
                                class="h-3.5 w-3.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </button>

                    </div>

                </div>

            @endif


        @else

            {{-- ============================================================
                 EMPTY / PROCESSING STATE
            ============================================================= --}}

            <section class="rounded-[22px] border border-gray-200/80 bg-white p-12 text-center shadow-sm">

                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-amber-50 text-amber-500">

                    <svg
                        class="h-7 w-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                </div>

                <h3 class="mb-2 text-lg font-bold text-gray-900">
                    No Extracted Content
                </h3>

                <p class="mx-auto max-w-sm text-[14px] leading-relaxed text-gray-500">
                    This document may still be processing or failed during extraction.
                    AI cannot read it yet.
                </p>

            </section>

        @endif

    </div>


    {{-- ================================================================
         DOCUMENT READER STYLES
    ================================================================= --}}

    <style>
        .document-paper {
            color: #1f2937;
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        .document-paragraph {
            margin: 0 0 1.15rem;
            font-size: 14px;
            line-height: 1.95;
            text-align: justify;
            text-justify: inter-word;
            color: #374151;
            letter-spacing: -0.005em;
            overflow-wrap: anywhere;
        }

        .document-paragraph:last-child {
            margin-bottom: 0;
        }

        .document-heading {
            margin: 1.75rem 0 0.9rem;
            font-size: 13px;
            line-height: 1.65;
            font-weight: 750;
            letter-spacing: 0.015em;
            color: #111827;
        }

        .document-heading:first-child {
            margin-top: 0;
        }

        .document-list-item {
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
            margin: 0 0 0.65rem;
            padding-left: 0.2rem;
            font-size: 14px;
            line-height: 1.85;
            color: #374151;
        }

        .document-list-number {
            width: 1.35rem;
            flex: 0 0 1.35rem;
            text-align: right;
            font-weight: 600;
            color: #4b5563;
        }

        .document-list-bullet {
            width: 1.1rem;
            flex: 0 0 1.1rem;
            text-align: center;
            font-size: 18px;
            line-height: 1.55;
            color: #9ca3af;
        }

        .ai-highlight {
            scroll-margin-top: 120px;
        }

        .ai-highlight.ai-highlight-active {
            background-color: rgb(253 186 116 / 0.95) !important;
            box-shadow:
                0 0 0 3px rgb(99 102 241 / 0.20),
                0 2px 8px rgb(0 0 0 / 0.10);
        }

        @media (max-width: 640px) {
            .document-paragraph,
            .document-list-item {
                font-size: 13px;
                line-height: 1.85;
            }

            .document-paper {
                padding-left: 1.35rem;
                padding-right: 1.35rem;
            }
        }
    </style>


    {{-- ================================================================
         HIGHLIGHT NAVIGATION
    ================================================================= --}}

    @if($hasHighlights)

        <script>
            document.addEventListener('DOMContentLoaded', () => {

                const highlights = Array.from(
                    document.querySelectorAll('.ai-highlight')
                );

                const toolbar = document.getElementById('highlight-toolbar');
                const currentCounter = document.getElementById('current-hl');
                const totalCounter = document.getElementById('total-hl');

                let currentHighlightIndex = 0;

                if (!highlights.length) {
                    return;
                }

                totalCounter.textContent = highlights.length;

                /*
                 * Make toolbar visible.
                 */
                setTimeout(() => {
                    if (toolbar) {
                        toolbar.classList.remove('translate-y-24');
                    }
                }, 350);


                window.scrollToHighlight = function (index) {

                    if (!highlights.length) {
                        return;
                    }

                    if (index < 1) {
                        index = highlights.length;
                    }

                    if (index > highlights.length) {
                        index = 1;
                    }

                    highlights.forEach((element) => {
                        element.classList.remove('ai-highlight-active');
                    });

                    const target = highlights[index - 1];

                    if (!target) {
                        return;
                    }

                    currentHighlightIndex = index;

                    currentCounter.textContent = index;

                    target.classList.add('ai-highlight-active');

                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                };


                window.navigateHighlight = function (direction) {

                    let nextIndex = currentHighlightIndex + direction;

                    if (nextIndex < 1) {
                        nextIndex = highlights.length;
                    }

                    if (nextIndex > highlights.length) {
                        nextIndex = 1;
                    }

                    window.scrollToHighlight(nextIndex);
                };


                /*
                 * Keyboard navigation.
                 */
                document.addEventListener('keydown', (event) => {

                    if (
                        event.key === 'ArrowDown' ||
                        event.key === 'ArrowRight'
                    ) {
                        event.preventDefault();
                        window.navigateHighlight(1);
                    }

                    if (
                        event.key === 'ArrowUp' ||
                        event.key === 'ArrowLeft'
                    ) {
                        event.preventDefault();
                        window.navigateHighlight(-1);
                    }

                });


                /*
                 * Focus first AI reference after the page is ready.
                 */
                setTimeout(() => {
                    window.scrollToHighlight(1);
                }, 650);

            });
        </script>

    @endif

</x-app-layout>