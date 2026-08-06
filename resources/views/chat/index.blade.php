<x-app-layout>
    <x-slot name="header">
    </x-slot>

    @php
        $user = auth()->user();
        $avatarFallbackUrl = 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&background=E0E7FF&color=4F46E5';
        $avatarUrl = $user && $user->avatar
            ? Storage::url($user->avatar)
            : $avatarFallbackUrl;
    @endphp

    <style>
        /* Sleek scrollbar for enterprise feel */
        .hide-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .hide-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .hide-scroll::-webkit-scrollbar-thumb {
            background-color: #e5e7eb;
            border-radius: 20px;
        }
        .hide-scroll:hover::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
        }
    </style>

    <div class="flex flex-col h-[calc(100vh-4rem)] bg-[#F8F9FB]">
        <!-- Main Chat Area -->
        <div id="chat-box" class="flex-1 overflow-y-auto hide-scroll p-4 sm:p-8 scroll-smooth w-full flex flex-col items-center">
            
            <!-- Container for messages to keep them centered and bounded -->
            <div id="messages-container" class="w-full max-w-4xl space-y-8 flex flex-col relative min-h-full">
                
                <!-- Premium Empty State -->
                <div id="empty-state" class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 my-auto h-full">
                    <div class="w-20 h-20 bg-white border border-gray-100 rounded-3xl flex items-center justify-center mb-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                        <img src="{{ asset('logo.png') }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" class="w-12 h-12 object-contain" alt="INAMOR Logo" />
                        <svg style="display: none;" class="w-10 h-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight mb-3">
                        INAMOR Knowledge Engine
                    </h2>
                    <p class="text-gray-500 text-lg max-w-lg mx-auto mb-10 leading-relaxed">
                        Access your organization's verified documents, operational guidelines, and collective intelligence.
                    </p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full max-w-2xl">
                        <button class="suggestion-btn flex flex-col text-left p-4 rounded-2xl bg-white border border-gray-200 hover:border-indigo-300 hover:shadow-sm hover:ring-1 hover:ring-indigo-50 transition-all">
                            <span class="text-sm font-semibold text-gray-900 mb-1">Syarat & Ketentuan PKL</span>
                            <span class="text-xs text-gray-500">Retrieve formal internship requirements</span>
                        </button>
                        <button class="suggestion-btn flex flex-col text-left p-4 rounded-2xl bg-white border border-gray-200 hover:border-indigo-300 hover:shadow-sm hover:ring-1 hover:ring-indigo-50 transition-all">
                            <span class="text-sm font-semibold text-gray-900 mb-1">Jadwal Pelaksanaan UAS</span>
                            <span class="text-xs text-gray-500">Check academic calendar schedules</span>
                        </button>
                        <button class="suggestion-btn flex flex-col text-left p-4 rounded-2xl bg-white border border-gray-200 hover:border-indigo-300 hover:shadow-sm hover:ring-1 hover:ring-indigo-50 transition-all">
                            <span class="text-sm font-semibold text-gray-900 mb-1">SOP Keterlambatan</span>
                            <span class="text-xs text-gray-500">Read official tardiness protocols</span>
                        </button>
                        <button class="suggestion-btn flex flex-col text-left p-4 rounded-2xl bg-white border border-gray-200 hover:border-indigo-300 hover:shadow-sm hover:ring-1 hover:ring-indigo-50 transition-all">
                            <span class="text-sm font-semibold text-gray-900 mb-1">Panduan Wisuda</span>
                            <span class="text-xs text-gray-500">Graduation prerequisites and info</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Floating Input Area -->
        <div class="w-full bg-gradient-to-t from-[#F8F9FB] via-[#F8F9FB] text-center to-transparent pt-6 pb-6 px-4">
            <div class="max-w-3xl mx-auto relative bg-white border border-gray-200 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] focus-within:ring-4 focus-within:ring-indigo-500/10 focus-within:border-indigo-400 transition-all flex flex-col">
                <textarea id="message"
                    rows="1"
                    class="w-full bg-transparent border-0 resize-none py-4 pl-5 pr-14 text-[15px] text-gray-900 placeholder:text-gray-400 focus:ring-0 min-h-[56px] max-h-48 leading-relaxed"
                    placeholder="Ask INAMOR or search organizational knowledge..."></textarea>
                
                <div class="absolute right-2.5 bottom-2.5">
                    <button id="send"
                        class="p-2.5 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm"
                        aria-label="Send Request">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="mt-3 text-[11px] font-medium text-gray-400 flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                INAMOR Enterprise Knowledge Engine. Zero-token retrieval active.
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
            
            const USER_AVATAR = '{{ $avatarUrl }}';
            const AI_LOGO_PATH = '{{ asset("logo.png") }}';
            let isProcessing = false;

            // ==========================================
            // UTILITIES
            // ==========================================
            
            const escapeHTML = (str = '') => {
                if (str === null || str === undefined) return '';
                return String(str).replace(/[&<>'"]/g, tag => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;'
                }[tag] || tag));
            };

            const scrollToBottom = () => {
                chatBox.scrollTo({
                    top: chatBox.scrollHeight,
                    behavior: 'smooth'
                });
            };

            const autoResizeInput = () => {
                input.style.height = 'auto';
                input.style.height = (input.scrollHeight) + 'px';
                if (input.value === '') input.style.height = 'auto';
            };

            input.addEventListener('input', autoResizeInput);

            // ==========================================
            // EVENT DELEGATION
            // ==========================================
            
            messagesContainer.addEventListener('click', (e) => {
                const toggleBtn = e.target.closest('.source-toggle');
                if (toggleBtn) {
                    const targetId = toggleBtn.dataset.target;
                    const body = document.getElementById(targetId);
                    const icon = toggleBtn.querySelector('.toggle-icon');
                    
                    if (body && icon) {
                        body.classList.toggle('hidden');
                        icon.classList.toggle('rotate-180');
                    }
                }
            });

            document.querySelectorAll('.suggestion-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    input.value = btn.querySelector('span.font-semibold').textContent;
                    autoResizeInput();
                    input.focus();
                });
            });

            // ==========================================
            // TEMPLATE BUILDERS
            // ==========================================

            const buildAIAvatar = () => `
                <div class="w-8 h-8 rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center flex-shrink-0 overflow-hidden">
                    <img src="${AI_LOGO_PATH}" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" class="w-5 h-5 object-contain" alt="AI" />
                    <svg style="display: none;" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                </div>
            `;

            const buildConfidenceBadge = (confidence) => {
                if (confidence === null || confidence === undefined) {
                    return `<span class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-wider font-bold text-indigo-600 bg-indigo-50/50 px-2.5 py-1 rounded-md border border-indigo-100">AI Synthesized</span>`;
                }
                
                if (confidence >= 90) {
                    return `<span class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-wider font-bold text-emerald-700 bg-emerald-50/50 px-2.5 py-1 rounded-md border border-emerald-100"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Verified Match</span>`;
                }
                
                if (confidence >= 70) {
                    return `<span class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-wider font-bold text-amber-700 bg-amber-50/50 px-2.5 py-1 rounded-md border border-amber-100"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Probable Match</span>`;
                }

                return `<span class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-wider font-bold text-gray-600 bg-gray-50 px-2.5 py-1 rounded-md border border-gray-200"><span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Partial Match</span>`;
            };

            const buildSourceCard = (source, index, messageId, autoExpand) => {
                const targetId = `source-${messageId}-${index}`;
                const badgeHTML = buildConfidenceBadge(source.confidence);
                
                const pageHTML = source.page 
                    ? `<span class="text-xs font-semibold text-gray-500 bg-white border border-gray-200 px-2 py-0.5 rounded shadow-sm">Page ${escapeHTML(source.page)}</span>`
                    : '';
                    
                const highlightHTML = source.highlight 
                    ? `<p class="text-sm text-gray-700 italic border-l-[3px] border-indigo-300 pl-3.5 leading-relaxed mb-4">"${escapeHTML(source.highlight)}"</p>`
                    : '';

                return `
                    <div class="border border-gray-200 rounded-xl bg-white overflow-hidden shadow-sm hover:border-gray-300 hover:shadow transition-all duration-200">
                        <button data-target="${targetId}" class="source-toggle w-full flex items-center justify-between p-3.5 hover:bg-gray-50 transition-colors text-left focus:outline-none focus:bg-gray-50">
                            <div class="flex items-center gap-3 overflow-hidden pr-4">
                                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 truncate">
                                    ${escapeHTML(source.title || 'Knowledge Base Document')}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                ${badgeHTML}
                                <svg class="toggle-icon w-4 h-4 text-gray-400 transition-transform duration-200 ${autoExpand ? 'rotate-180' : ''}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        
                        <div id="${targetId}" class="${autoExpand ? '' : 'hidden'} border-t border-gray-100 bg-[#F9FAFC] p-5">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Document Excerpt</span>
                                ${pageHTML}
                            </div>
                            ${highlightHTML}
                            
                            <button onclick="alert('Document viewer integration pending.')" class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors bg-white border border-indigo-100 shadow-sm px-3 py-1.5 rounded-lg hover:bg-indigo-50">
                                Open Document
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
            };

            const renderUserMessage = (message) => `
                <div class="flex flex-row-reverse items-start gap-3 w-full self-end">
                    <img src="${USER_AVATAR}" class="w-8 h-8 rounded-full shadow-sm object-cover border border-gray-200" alt="User" />
                    <div class="max-w-[80%] sm:max-w-[70%] bg-indigo-600 text-white px-5 py-3.5 rounded-2xl rounded-tr-sm shadow-sm text-[15px] leading-relaxed whitespace-pre-wrap">
                        ${escapeHTML(message)}
                    </div>
                </div>
            `;

            const renderLoadingState = (id) => `
                <div id="${id}" class="flex items-start gap-3 w-full" aria-live="polite">
                    ${buildAIAvatar()}
                    <div class="bg-white border border-gray-100 px-5 py-4 rounded-2xl rounded-tl-sm shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex items-center gap-2 h-[52px]">
                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.15s"></div>
                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.3s"></div>
                    </div>
                </div>
            `;

            const renderSystemMessage = (answer, sources, messageId) => {
                let evidenceBlock = '';
                
                if (sources && sources.length > 0) {
                    const autoExpand = sources.length === 1;
                    const cards = sources.map((s, i) => buildSourceCard(s, i, messageId, autoExpand)).join('');
                    
                    evidenceBlock = `
                        <div class="mt-5 w-full">
                            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Evidence Retrieved (${sources.length})
                            </div>
                            <div class="space-y-3">
                                ${cards}
                            </div>
                        </div>
                    `;
                }

                return `
                    <div class="flex items-start gap-3 w-full">
                        ${buildAIAvatar()}
                        <div class="flex-1 max-w-[88%] sm:max-w-[80%]">
                            <div class="bg-white border border-gray-100 px-6 py-5 rounded-2xl rounded-tl-sm shadow-[0_2px_15px_rgb(0,0,0,0.03)]">
                                <div class="prose prose-gray prose-sm sm:prose-base max-w-none text-gray-800 leading-relaxed whitespace-pre-wrap font-sans">
                                    ${escapeHTML(answer)}
                                </div>
                                ${evidenceBlock}
                            </div>
                        </div>
                    </div>
                `;
            };

            const renderErrorMessage = (errorText) => `
                <div class="flex items-start gap-3 w-full">
                    ${buildAIAvatar()}
                    <div class="bg-red-50 border border-red-100 px-5 py-4 rounded-2xl rounded-tl-sm shadow-sm max-w-[80%]">
                        <div class="flex items-start gap-2 text-red-700">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="text-[15px] leading-relaxed">${escapeHTML(errorText)}</span>
                        </div>
                    </div>
                </div>
            `;

            // ==========================================
            // MAIN LOGIC
            // ==========================================

            input.addEventListener('keydown', e => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });

            button.addEventListener('click', sendMessage);

            async function sendMessage() {
                if (isProcessing) return;
                
                const rawMessage = input.value.trim();
                if (!rawMessage) return;

                isProcessing = true;
                
                if (emptyState && emptyState.style.display !== 'none') {
                    emptyState.style.display = 'none';
                    messagesContainer.classList.remove('min-h-full');
                }

                const messageId = Date.now();

                // 1. Render User Message
                messagesContainer.insertAdjacentHTML('beforeend', renderUserMessage(rawMessage));

                // Reset Input Area
                input.value = '';
                autoResizeInput();
                button.disabled = true;
                input.disabled = true;

                // 2. Render Loading State
                const loadingId = `loading-${messageId}`;
                messagesContainer.insertAdjacentHTML('beforeend', renderLoadingState(loadingId));
                scrollToBottom();

                try {
                    const response = await fetch('/ai/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: rawMessage })
                    });

                    const loadingElement = document.getElementById(loadingId);
                    if (loadingElement) loadingElement.remove();

                    if (!response.ok) {
                        throw new Error(await response.text());
                    }

                    const data = await response.json();
                    
                    const answer = data.answer ?? data.reply ?? data.message ?? 'Informasi tidak ditemukan pada dokumen resmi.';
                    const sources = data.sources ?? [];

                    // 3. Render System Response
                    messagesContainer.insertAdjacentHTML('beforeend', renderSystemMessage(answer, sources, messageId));

                } catch (err) {
                    console.error(err);
                    const loadingElement = document.getElementById(loadingId);
                    if (loadingElement) loadingElement.remove();

                    messagesContainer.insertAdjacentHTML('beforeend', renderErrorMessage(err.message));
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