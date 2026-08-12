<x-app-layout>
    @php
        $user = auth()->user();

        $avatarFallbackUrl = 'https://ui-avatars.com/api/?name='
            . urlencode($user->name ?? 'User')
            . '&background=F1F5F9&color=475569&font-size=0.35&bold=true';

        $avatarUrl = $user && $user->avatar
            ? Storage::url($user->avatar)
            : $avatarFallbackUrl;
    @endphp

    <style>
        .hide-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .hide-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .hide-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .hide-scroll:hover::-webkit-scrollbar-thumb {
            background: #94a3b8;
        }

        .ai-content ul {
            list-style: disc;
            margin: .65rem 0 .65rem 1.25rem;
        }

        .ai-content ol {
            list-style: decimal;
            margin: .65rem 0 .65rem 1.25rem;
        }

        .ai-content li {
            margin-bottom: .35rem;
            padding-left: .1rem;
        }

        .ai-content p {
            margin-bottom: .8rem;
        }

        .ai-content p:last-child {
            margin-bottom: 0;
        }

        .ai-content strong {
            font-weight: 650;
            color: #0f172a;
        }

        .source-excerpt {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 4;
            overflow: hidden;
        }
    </style>

    <div class="flex h-[calc(100vh-4rem)] flex-col bg-white sm:bg-[#F8F9FA]">

        {{-- =========================================================
            CHAT AREA
        ========================================================== --}}
        <div
            id="chat-box"
            class="hide-scroll flex w-full flex-1 flex-col items-center overflow-y-auto scroll-smooth px-4 py-5 sm:px-6 sm:py-6 lg:px-8"
        >
            <div
                id="messages-container"
                class="relative flex min-h-full w-full max-w-3xl flex-col space-y-8 pb-4"
            >

                {{-- =================================================
                    EMPTY STATE
                ================================================== --}}
                <div
                    id="empty-state"
                    class="absolute inset-0 flex h-full flex-col items-center justify-center px-4 text-center"
                >
                    <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-100 bg-white shadow-sm">
                        <svg
                            class="h-8 w-8 text-indigo-600"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"
                            />
                        </svg>
                    </div>

                    <h2 class="mb-2 text-2xl font-bold tracking-tight text-slate-900">
                        INAMOR Knowledge Engine
                    </h2>

                    <p class="mx-auto mb-10 max-w-md text-[15px] leading-relaxed text-slate-500">
                        Tanyakan apa saja seputar basis pengetahuan, pedoman operasional,
                        dan data resmi organisasi Anda.
                    </p>

                    <div class="grid w-full max-w-xl grid-cols-1 gap-3 sm:grid-cols-2">

                        <button
                            type="button"
                            class="suggestion-btn group flex flex-col rounded-xl border border-slate-200 bg-white p-4 text-left transition-all hover:border-indigo-300 hover:shadow-sm"
                        >
                            <span class="mb-0.5 text-[14px] font-semibold text-slate-900 group-hover:text-indigo-700">
                                Apa syarat dokumen PKL?
                            </span>

                            <span class="text-[12px] text-slate-500">
                                Mencari di pedoman akademik
                            </span>
                        </button>

                        <button
                            type="button"
                            class="suggestion-btn group flex flex-col rounded-xl border border-slate-200 bg-white p-4 text-left transition-all hover:border-indigo-300 hover:shadow-sm"
                        >
                            <span class="mb-0.5 text-[14px] font-semibold text-slate-900 group-hover:text-indigo-700">
                                Kapan jadwal pelaksanaan UAS?
                            </span>

                            <span class="text-[12px] text-slate-500">
                                Mencari di kalender akademik
                            </span>
                        </button>

                        <button
                            type="button"
                            class="suggestion-btn group flex flex-col rounded-xl border border-slate-200 bg-white p-4 text-left transition-all hover:border-indigo-300 hover:shadow-sm"
                        >
                            <span class="mb-0.5 text-[14px] font-semibold text-slate-900 group-hover:text-indigo-700">
                                Bagaimana SOP keterlambatan?
                            </span>

                            <span class="text-[12px] text-slate-500">
                                Mencari di peraturan organisasi
                            </span>
                        </button>

                        <button
                            type="button"
                            class="suggestion-btn group flex flex-col rounded-xl border border-slate-200 bg-white p-4 text-left transition-all hover:border-indigo-300 hover:shadow-sm"
                        >
                            <span class="mb-0.5 text-[14px] font-semibold text-slate-900 group-hover:text-indigo-700">
                                Apa panduan kelulusan wisuda?
                            </span>

                            <span class="text-[12px] text-slate-500">
                                Mencari di syarat wisuda
                            </span>
                        </button>

                    </div>
                </div>

            </div>
        </div>


        {{-- =========================================================
            INPUT
        ========================================================== --}}
        <div class="w-full border-t border-slate-100 bg-white px-4 pb-5 pt-4 sm:border-transparent sm:bg-gradient-to-t sm:from-[#F8F9FA] sm:via-[#F8F9FA] sm:to-transparent sm:px-8">

            <div class="relative mx-auto flex max-w-3xl flex-col rounded-2xl border border-slate-200 bg-white shadow-sm transition-all focus-within:border-indigo-400 focus-within:ring-4 focus-within:ring-indigo-500/10">

                <textarea
                    id="message"
                    rows="1"
                    class="min-h-[56px] max-h-40 w-full resize-none border-0 bg-transparent py-4 pl-5 pr-14 text-[15px] leading-relaxed text-slate-900 placeholder:text-slate-400 focus:ring-0"
                    placeholder="Tanyakan pada INAMOR..."
                ></textarea>

                <div class="absolute bottom-2 right-2">
                    <button
                        type="button"
                        id="send"
                        class="rounded-xl bg-slate-900 p-2 text-white transition-colors hover:bg-indigo-600 disabled:cursor-not-allowed disabled:bg-slate-200 disabled:text-slate-400"
                    >
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"
                            />
                        </svg>
                    </button>
                </div>

            </div>

            <div class="mt-3 flex items-center justify-center gap-1.5 text-center text-[11px] font-medium text-slate-400">
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
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                    />
                </svg>

                INAMOR Enterprise Knowledge Engine.
                AI dapat membuat kesalahan, verifikasi referensi jika ragu.
            </div>

        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const chatBox = document.getElementById('chat-box');
            const messagesContainer = document.getElementById('messages-container');
            const input = document.getElementById('message');
            const button = document.getElementById('send');
            const emptyState = document.getElementById('empty-state');

            const USER_AVATAR = @json($avatarUrl);

            let isProcessing = false;


            /* =========================================================
               SECURITY
            ========================================================== */

            const escapeHTML = (value = '') => {
                if (value === null || value === undefined) {
                    return '';
                }

                return String(value).replace(/[&<>'"]/g, tag => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    "'": '&#39;',
                    '"': '&quot;',
                }[tag] || tag));
            };


            const encodeData = value => {
                return escapeHTML(
                    JSON.stringify(value ?? [])
                );
            };


            const parseJSON = (value, fallback = []) => {
                if (!value) {
                    return fallback;
                }

                try {
                    return JSON.parse(value);
                } catch (error) {
                    return fallback;
                }
            };


            /* =========================================================
               AI RESPONSE
            ========================================================== */

            const formatAIResponse = text => {

                if (!text) {
                    return '';
                }

                let safeText = escapeHTML(text);

                safeText = safeText.replace(
                    /\*\*(.*?)\*\*/g,
                    '<strong>$1</strong>'
                );

                const lines = safeText.split('\n');
                const output = [];

                let listItems = [];
                let orderedItems = [];

                const flushLists = () => {

                    if (listItems.length > 0) {
                        output.push(
                            `<ul>${listItems.join('')}</ul>`
                        );

                        listItems = [];
                    }

                    if (orderedItems.length > 0) {
                        output.push(
                            `<ol>${orderedItems.join('')}</ol>`
                        );

                        orderedItems = [];
                    }
                };

                lines.forEach(line => {

                    const trimmed = line.trim();

                    if (!trimmed) {
                        flushLists();
                        return;
                    }

                    const bulletMatch =
                        trimmed.match(/^[-*]\s+(.+)$/);

                    if (bulletMatch) {
                        if (orderedItems.length > 0) {
                            flushLists();
                        }

                        listItems.push(
                            `<li>${bulletMatch[1]}</li>`
                        );

                        return;
                    }

                    const orderedMatch =
                        trimmed.match(/^\d+\.\s+(.+)$/);

                    if (orderedMatch) {
                        if (listItems.length > 0) {
                            flushLists();
                        }

                        orderedItems.push(
                            `<li>${orderedMatch[1]}</li>`
                        );

                        return;
                    }

                    flushLists();

                    output.push(
                        `<p>${trimmed}</p>`
                    );
                });

                flushLists();

                return output.join('');
            };


            /* =========================================================
               HELPERS
            ========================================================== */

            const scrollToBottom = () => {
                setTimeout(() => {
                    chatBox.scrollTo({
                        top: chatBox.scrollHeight,
                        behavior: 'smooth',
                    });
                }, 50);
            };


            const autoResizeInput = () => {

                input.style.height = 'auto';

                input.style.height =
                    `${input.scrollHeight}px`;

                if (input.value === '') {
                    input.style.height = 'auto';
                }
            };

            input.addEventListener(
                'input',
                autoResizeInput
            );


            /* =========================================================
               DOCUMENT URL
            ========================================================== */

            const buildDocumentUrl = (
                documentId,
                highlights = []
            ) => {

                if (!documentId) {
                    return null;
                }

                const params =
                    new URLSearchParams();

                highlights.forEach(highlight => {

                    if (!highlight) {
                        return;
                    }

                    if (typeof highlight === 'string') {

                        const text =
                            highlight.trim();

                        if (text) {
                            params.append(
                                'highlight[]',
                                text
                            );
                        }

                        return;
                    }

                    if (typeof highlight === 'object') {

                        const text =
                            highlight.text ??
                            highlight.highlight ??
                            '';

                        if (text) {
                            params.append(
                                'highlight[]',
                                String(text)
                            );
                        }

                        if (
                            highlight.page !== null &&
                            highlight.page !== undefined &&
                            highlight.page !== ''
                        ) {
                            params.append(
                                'page[]',
                                String(highlight.page)
                            );
                        }
                    }
                });

                const query =
                    params.toString();

                return `/documents/${encodeURIComponent(documentId)}${query ? `?${query}` : ''}`;
            };


            /* =========================================================
               SOURCE INTERACTION
            ========================================================== */

            messagesContainer.addEventListener(
                'click',
                event => {

                    const toggleBtn =
                        event.target.closest(
                            '.source-toggle'
                        );

                    if (toggleBtn) {

                        const targetId =
                            toggleBtn.dataset.target;

                        const body =
                            document.getElementById(targetId);

                        const icon =
                            toggleBtn.querySelector(
                                '.toggle-icon'
                            );

                        if (body) {
                            body.classList.toggle('hidden');
                        }

                        if (icon) {
                            icon.classList.toggle(
                                'rotate-180'
                            );
                        }

                        return;
                    }


                    const openDocBtn =
                        event.target.closest(
                            '.open-document-btn'
                        );

                    if (!openDocBtn) {
                        return;
                    }

                    const documentId =
                        openDocBtn.dataset.id;

                    let highlights =
                        parseJSON(
                            openDocBtn.dataset.highlights,
                            []
                        );

                    if (
                        (!Array.isArray(highlights) ||
                            highlights.length === 0) &&
                        openDocBtn.dataset.highlight
                    ) {
                        highlights = [
                            {
                                text:
                                    openDocBtn.dataset.highlight,
                                page:
                                    openDocBtn.dataset.page ||
                                    null,
                            }
                        ];
                    }

                    if (
                        !documentId ||
                        documentId === 'undefined'
                    ) {
                        showToast(
                            'Dokumen tidak ditemukan di basis data.'
                        );

                        return;
                    }

                    const url =
                        buildDocumentUrl(
                            documentId,
                            Array.isArray(highlights)
                                ? highlights
                                : []
                        );

                    if (!url) {
                        showToast(
                            'Dokumen tidak ditemukan di basis data.'
                        );

                        return;
                    }

                    window.open(
                        url,
                        '_blank'
                    );
                }
            );


            /* =========================================================
               AI AVATAR
            ========================================================== */

            const buildAIAvatar = () => `
                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-[10px] bg-indigo-600 text-white shadow-sm">
                    <svg
                        class="h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"
                        />
                    </svg>
                </div>
            `;


            /* =========================================================
               CONFIDENCE
            ========================================================== */

            const buildConfidenceBadge = confidence => {

                if (
                    confidence === null ||
                    confidence === undefined
                ) {
                    return `
                        <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-[9px] font-bold uppercase tracking-wider text-slate-500">
                            AI Synthesized
                        </span>
                    `;
                }

                if (confidence >= 90) {
                    return `
                        <span class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-2 py-1 text-[9px] font-bold uppercase tracking-wider text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Verified
                        </span>
                    `;
                }

                if (confidence >= 70) {
                    return `
                        <span class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2 py-1 text-[9px] font-bold uppercase tracking-wider text-amber-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            Probable
                        </span>
                    `;
                }

                return `
                    <span class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-2 py-1 text-[9px] font-bold uppercase tracking-wider text-slate-600">
                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                        Partial
                    </span>
                `;
            };


            /* =========================================================
               HIGHLIGHTS
            ========================================================== */

            const normalizeHighlights = source => {

                if (Array.isArray(source?.highlights)) {

                    return source.highlights
                        .map(item => {

                            if (typeof item === 'string') {
                                return {
                                    text: item,
                                    page: null,
                                };
                            }

                            if (
                                !item ||
                                typeof item !== 'object'
                            ) {
                                return null;
                            }

                            const text = String(
                                item.text ??
                                item.highlight ??
                                ''
                            ).trim();

                            if (!text) {
                                return null;
                            }

                            return {
                                text,
                                page:
                                    item.page ??
                                    null,
                            };
                        })
                        .filter(Boolean);
                }


                if (source?.highlight) {

                    return [
                        {
                            text:
                                String(
                                    source.highlight
                                ).trim(),

                            page:
                                source.page ??
                                null,
                        },
                    ];
                }

                return [];
            };


            /* =========================================================
               SHORT EXCERPT
               ---------------------------------------------------------
               UI dipendekin, tapi data original tetap disimpan
               untuk document viewer.
            ========================================================== */

            const makeExcerpt = (
                text,
                maxLength = 260
            ) => {

                if (!text) {
                    return '';
                }

                const clean =
                    String(text)
                        .replace(/\s+/g, ' ')
                        .trim();

                if (
                    clean.length <= maxLength
                ) {
                    return clean;
                }

                const shortened =
                    clean.slice(
                        0,
                        maxLength
                    );

                const lastSpace =
                    shortened.lastIndexOf(' ');

                return (
                    shortened.slice(
                        0,
                        lastSpace > 120
                            ? lastSpace
                            : maxLength
                    ).trim() +
                    '...'
                );
            };


            /* =========================================================
               SOURCE CARD
            ========================================================== */

            const buildSourceCard = (
                source,
                index,
                messageId,
                autoExpand
            ) => {

                const targetId =
                    `source-${messageId}-${index}`;

                const highlights =
                    normalizeHighlights(source);

                const firstPage =
                    source?.page ??
                    highlights.find(
                        item => item.page
                    )?.page ??
                    null;

                const badgeHTML =
                    buildConfidenceBadge(
                        source?.confidence
                    );

                const encodedHighlights =
                    encodeData(highlights);


                /* -----------------------------------------
                   Evidence UI
                ----------------------------------------- */

                let evidenceHTML = '';

                if (highlights.length > 0) {

                    evidenceHTML = highlights
                        .map(
                            (item, highlightIndex) => {

                                const excerpt =
                                    makeExcerpt(
                                        item.text
                                    );

                                return `
                                    <div class="rounded-lg border border-slate-200 bg-white px-3.5 py-3">

                                        <div class="mb-2 flex items-center justify-between gap-3">

                                            <span class="text-[9px] font-bold uppercase tracking-[0.12em] text-slate-400">
                                                Evidence ${highlightIndex + 1}
                                            </span>

                                            ${
                                                item.page
                                                    ? `
                                                        <span class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-1.5 py-0.5 text-[9px] font-semibold text-slate-500">
                                                            Hal. ${escapeHTML(item.page)}
                                                        </span>
                                                    `
                                                    : ''
                                            }

                                        </div>

                                        <p class="source-excerpt border-l-2 border-indigo-400 pl-3 text-[12px] leading-6 text-slate-600">
                                            ${escapeHTML(excerpt)}
                                        </p>

                                    </div>
                                `;
                            }
                        )
                        .join('');

                    if (highlights.length > 1) {

                        evidenceHTML = `
                            <div class="space-y-2">
                                ${evidenceHTML}
                            </div>
                        `;
                    }

                } else {

                    evidenceHTML = `
                        <div class="rounded-lg border border-dashed border-slate-200 bg-white px-4 py-3">
                            <p class="text-[12px] italic text-slate-400">
                                Tidak ada kutipan spesifik yang tersedia.
                            </p>
                        </div>
                    `;
                }


                return `
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">

                        {{-- SOURCE HEADER --}}
                        <button
                            type="button"
                            data-target="${targetId}"
                            class="source-toggle flex w-full items-center justify-between gap-4 px-3.5 py-3 text-left transition-colors hover:bg-slate-50 focus:outline-none"
                        >

                            <div class="flex min-w-0 items-center gap-3">

                                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                    <svg
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A1.125 1.125 0 0012.375 4.5H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-7.5"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M14.25 18.75h6m0 0v-6m0 6-6-6"
                                        />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <div
                                        class="truncate text-[13px] font-semibold text-slate-800"
                                        title="${escapeHTML(source?.title || 'Dokumen Organisasi')}"
                                    >
                                        ${escapeHTML(source?.title || 'Dokumen Organisasi')}
                                    </div>

                                    <div class="mt-0.5 flex items-center gap-2 text-[10px] text-slate-400">
                                        ${
                                            firstPage
                                                ? `Hal. ${escapeHTML(firstPage)}`
                                                : 'Dokumen referensi'
                                        }

                                        ${
                                            highlights.length > 0
                                                ? `
                                                    <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                                    ${highlights.length} evidence
                                                `
                                                : ''
                                        }
                                    </div>
                                </div>

                            </div>


                            <div class="flex flex-shrink-0 items-center gap-2">

                                ${badgeHTML}

                                <svg
                                    class="toggle-icon h-4 w-4 text-slate-400 transition-transform duration-200 ${autoExpand ? 'rotate-180' : ''}"
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

                            </div>

                        </button>


                        {{-- SOURCE BODY --}}
                        <div
                            id="${targetId}"
                            class="${autoExpand ? '' : 'hidden'} border-t border-slate-100 bg-slate-50/50 p-3.5"
                        >

                            <div class="mb-2.5 flex items-center justify-between">

                                <span class="text-[9px] font-bold uppercase tracking-[0.14em] text-slate-400">
                                    Evidence Dokumen
                                </span>

                                ${
                                    firstPage
                                        ? `
                                            <span class="text-[9px] font-semibold text-slate-400">
                                                Hal. ${escapeHTML(firstPage)}
                                            </span>
                                        `
                                        : ''
                                }

                            </div>


                            ${evidenceHTML}


                            {{-- OPEN DOCUMENT --}}
                            <div class="mt-3">

                                <button
                                    type="button"
                                    data-id="${escapeHTML(source?.document_id ?? source?.id ?? '')}"
                                    data-highlights="${encodedHighlights}"
                                    data-highlight="${escapeHTML(source?.highlight ?? '')}"
                                    data-page="${escapeHTML(source?.page ?? '')}"
                                    class="open-document-btn inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[11px] font-semibold text-slate-700 shadow-sm transition-all hover:border-indigo-300 hover:text-indigo-700 hover:shadow"
                                >

                                    Lihat Dokumen

                                    <svg
                                        class="h-3 w-3"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2.5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"
                                        />
                                    </svg>

                                </button>

                            </div>

                        </div>

                    </div>
                `;
            };


            /* =========================================================
               USER MESSAGE
            ========================================================== */

            const renderUserMessage = message => `
                <div class="flex w-full flex-row-reverse items-end gap-3">

                    <img
                        src="${escapeHTML(USER_AVATAR)}"
                        class="hidden h-8 w-8 flex-shrink-0 rounded-full border border-slate-200 sm:block"
                        alt="User"
                    />

                    <div class="max-w-[90%] rounded-2xl rounded-br-sm bg-slate-900 px-5 py-3.5 text-[14px] leading-relaxed text-white shadow-sm sm:max-w-[75%]">
                        ${escapeHTML(message)}
                    </div>

                </div>
            `;


            /* =========================================================
               LOADING
            ========================================================== */

            const renderLoadingState = id => `
                <div
                    id="${id}"
                    class="flex w-full items-end gap-3"
                    aria-live="polite"
                >

                    ${buildAIAvatar()}

                    <div class="flex h-[52px] items-center gap-2 rounded-2xl rounded-bl-sm border border-slate-100 bg-white px-5 shadow-sm">

                        <div class="mr-2 text-[13px] font-medium text-slate-500">
                            Mencari referensi organisasi
                        </div>

                        <div class="flex gap-1">

                            <div class="h-1.5 w-1.5 animate-bounce rounded-full bg-indigo-400"></div>

                            <div
                                class="h-1.5 w-1.5 animate-bounce rounded-full bg-indigo-400"
                                style="animation-delay: .15s"
                            ></div>

                            <div
                                class="h-1.5 w-1.5 animate-bounce rounded-full bg-indigo-400"
                                style="animation-delay: .3s"
                            ></div>

                        </div>

                    </div>

                </div>
            `;


            /* =========================================================
               SYSTEM MESSAGE
            ========================================================== */

            const renderSystemMessage = (
                answer,
                sources,
                messageId
            ) => {

                let evidenceBlock = '';

                if (
                    Array.isArray(sources) &&
                    sources.length > 0
                ) {

                    const autoExpand =
                        sources.length === 1;

                    const cards =
                        sources
                            .map(
                                (source, index) =>
                                    buildSourceCard(
                                        source,
                                        index,
                                        messageId,
                                        autoExpand
                                    )
                            )
                            .join('');


                    evidenceBlock = `
                        <div class="mt-6 border-t border-slate-100 pt-5">

                            <div class="mb-3 flex items-center justify-between">

                                <div class="flex items-center gap-2">

                                    <svg
                                        class="h-3.5 w-3.5 text-slate-400"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A1.125 1.125 0 0012.375 4.5H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
                                        />
                                    </svg>

                                    <span class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">
                                        Referensi Ditemukan
                                    </span>

                                    <span class="rounded-md bg-slate-100 px-1.5 py-0.5 text-[9px] font-bold text-slate-500">
                                        ${sources.length}
                                    </span>

                                </div>

                            </div>


                            <div class="space-y-2">
                                ${cards}
                            </div>

                        </div>
                    `;
                }


                return `
                    <div class="flex w-full items-start gap-3">

                        ${buildAIAvatar()}

                        <div class="max-w-[95%] flex-1 sm:max-w-[85%]">

                            <div class="rounded-2xl rounded-tl-sm border border-slate-100 bg-white p-5 shadow-sm sm:p-6">

                                <div class="ai-content font-sans text-[14px] leading-relaxed text-slate-800">
                                    ${formatAIResponse(answer)}
                                </div>

                                ${evidenceBlock}

                            </div>

                        </div>

                    </div>
                `;
            };


            /* =========================================================
               TOAST
            ========================================================== */

            const showToast = message => {

                const toast =
                    document.createElement('div');

                toast.className =
                    'fixed right-4 top-4 z-50 rounded-xl bg-slate-900 px-4 py-3 text-[13px] font-medium text-white shadow-xl';

                toast.textContent =
                    message;

                document.body.appendChild(
                    toast
                );

                setTimeout(() => {
                    toast.remove();
                }, 3000);
            };


            /* =========================================================
               SUGGESTIONS
            ========================================================== */

            document
                .querySelectorAll('.suggestion-btn')
                .forEach(btn => {

                    btn.addEventListener(
                        'click',
                        () => {

                            const question =
                                btn
                                    .querySelector('span')
                                    ?.textContent
                                    ?.trim();

                            if (
                                !question ||
                                isProcessing
                            ) {
                                return;
                            }

                            input.value =
                                question;

                            autoResizeInput();

                            sendMessage();
                        }
                    );
                });


            /* =========================================================
               INPUT
            ========================================================== */

            input.addEventListener(
                'keydown',
                event => {

                    if (
                        event.key === 'Enter' &&
                        !event.shiftKey
                    ) {
                        event.preventDefault();

                        sendMessage();
                    }
                }
            );


            button.addEventListener(
                'click',
                sendMessage
            );


            /* =========================================================
               SEND MESSAGE
            ========================================================== */

            async function sendMessage() {

                if (isProcessing) {
                    return;
                }

                const rawMessage =
                    input.value.trim();

                if (!rawMessage) {
                    return;
                }

                isProcessing = true;


                /* -----------------------------------------
                   Hide empty state
                ----------------------------------------- */

                if (
                    emptyState &&
                    emptyState.style.display !== 'none'
                ) {

                    emptyState.style.display =
                        'none';

                    messagesContainer.classList.remove(
                        'min-h-full',
                        'pb-4'
                    );
                }


                const messageId =
                    Date.now();


                /* -----------------------------------------
                   User message
                ----------------------------------------- */

                messagesContainer.insertAdjacentHTML(
                    'beforeend',
                    renderUserMessage(
                        rawMessage
                    )
                );

                input.value = '';

                autoResizeInput();

                button.disabled = true;
                input.disabled = true;


                /* -----------------------------------------
                   Loading
                ----------------------------------------- */

                const loadingId =
                    `loading-${messageId}`;

                messagesContainer.insertAdjacentHTML(
                    'beforeend',
                    renderLoadingState(
                        loadingId
                    )
                );

                scrollToBottom();


                try {

                    const response =
                        await fetch(
                            '/ai/chat',
                            {
                                method: 'POST',

                                headers: {
                                    'Content-Type':
                                        'application/json',

                                    'X-CSRF-TOKEN':
                                        '{{ csrf_token() }}',

                                    'Accept':
                                        'application/json',
                                },

                                body:
                                    JSON.stringify({
                                        message:
                                            rawMessage,
                                    }),
                            }
                        );


                    const loadingElement =
                        document.getElementById(
                            loadingId
                        );

                    if (loadingElement) {
                        loadingElement.remove();
                    }


                    if (!response.ok) {

                        let errorMessage =
                            'Server error';

                        try {

                            const errorData =
                                await response.json();

                            errorMessage =
                                errorData.message ??
                                errorMessage;

                        } catch (_) {
                            // Ignore invalid JSON.
                        }

                        throw new Error(
                            errorMessage
                        );
                    }


                    const data =
                        await response.json();


                    const answer =
                        data.answer ??
                        data.reply ??
                        data.message ??
                        'Informasi tidak ditemukan pada dokumen resmi organisasi.';


                    const sources =
                        Array.isArray(
                            data.sources
                        )
                            ? data.sources
                            : [];


                    messagesContainer.insertAdjacentHTML(
                        'beforeend',
                        renderSystemMessage(
                            answer,
                            sources,
                            messageId
                        )
                    );


                    scrollToBottom();

                } catch (error) {

                    const loadingElement =
                        document.getElementById(
                            loadingId
                        );

                    if (loadingElement) {
                        loadingElement.remove();
                    }


                    messagesContainer.insertAdjacentHTML(
                        'beforeend',
                        `
                            <div class="flex w-full items-start gap-3">

                                ${buildAIAvatar()}

                                <div class="max-w-[85%] rounded-2xl rounded-tl-sm border border-red-100 bg-red-50 px-5 py-4 shadow-sm">

                                    <div class="text-[13px] font-medium leading-relaxed text-red-700">
                                        Gagal memproses pertanyaan.
                                        Silakan coba lagi.
                                    </div>

                                </div>

                            </div>
                        `
                    );


                    console.error(
                        'INAMOR AI error:',
                        error
                    );

                } finally {

                    isProcessing = false;

                    button.disabled = false;
                    input.disabled = false;

                    setTimeout(() => {

                        input.focus();

                        scrollToBottom();

                    }, 50);
                }
            }

        });
    </script>
</x-app-layout>