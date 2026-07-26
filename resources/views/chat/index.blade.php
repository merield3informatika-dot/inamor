<x-app-layout>

    <x-slot name="header">
    
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto flex flex-col h-[75vh] min-h-[600px]">
        
        <!-- Chat Container -->
        <div id="chat-box" class="flex-1 bg-white rounded-xl shadow-sm border border-gray-200 overflow-y-auto p-6 space-y-6 relative scroll-smooth flex flex-col">
            
            <!-- Empty State -->
            <div id="empty-state" class="absolute inset-0 flex flex-col items-center justify-center text-center p-8 bg-white rounded-xl z-10">
                <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 border border-indigo-100 shadow-sm">
                    <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-indigo-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">AI Assistant</h3>
                <p class="text-gray-600 mb-4 max-w-sm text-base">Ask questions about your organization's documents.</p>
                <p class="text-sm text-gray-400 font-medium bg-gray-50 px-4 py-2 rounded-lg border border-gray-100">Upload documents first, then start asking questions.</p>
            </div>

        </div>

        <!-- Input Area (Sticky Bottom) -->
        <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 transition-all duration-200 ease-in-out p-2 relative">
            <div class="flex items-end gap-3">
                <textarea 
                    id="message" 
                    rows="1" 
                    class="flex-1 max-h-32 bg-transparent border-0 focus:ring-0 resize-none py-3 px-4 text-gray-900 placeholder-gray-500 text-base sm:text-sm leading-relaxed" 
                    placeholder="Message AI Assistant..."
                    aria-label="Message input"
                ></textarea>
                
                <button 
                    id="send" 
                    class="mb-1 mr-1 p-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
                    aria-label="Send message"
                >
                    <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path d="M3.478 2.404a.75.75 0 00-.926.941l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.404z" />
                    </svg>
                </button>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chatBox = document.getElementById('chat-box');
        const input = document.getElementById('message');
        const button = document.getElementById('send');
        const emptyState = document.getElementById('empty-state');

        // Escape HTML helper
        const escapeHTML = (str) => {
            return str.replace(/[&<>'"]/g, 
                tag => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    "'": '&#39;',
                    '"': '&quot;'
                }[tag] || tag)
            );
        };

        // Auto-resize textarea
        const resizeInput = () => {
            input.style.height = 'auto';
            input.style.height = (input.scrollHeight) + 'px';
            if (input.value === '') {
                input.style.height = 'auto';
            }
        };

        input.addEventListener('input', resizeInput);

        // Handle Enter key
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        button.addEventListener('click', sendMessage);

        async function sendMessage() {
            const rawMessage = input.value.trim();
            
            if (!rawMessage) return;

            const safeMessage = escapeHTML(rawMessage);

            // Hide empty state if it's there
            if (emptyState && emptyState.style.display !== 'none') {
                emptyState.style.display = 'none';
            }

            // Add User Message
            chatBox.insertAdjacentHTML('beforeend', `
                <div class="flex justify-end w-full">
                    <div class="bg-indigo-600 text-white px-5 py-3.5 rounded-2xl rounded-tr-sm max-w-[85%] md:max-w-[70%] shadow-sm text-[15px] leading-relaxed whitespace-pre-wrap">${safeMessage}</div>
                </div>
            `);

            // Reset input
            input.value = '';
            resizeInput();
            chatBox.scrollTop = chatBox.scrollHeight;

            // Disable UI
            input.disabled = true;
            button.disabled = true;

            // Add Loading State
            const loadingId = 'loading-' + Date.now();
            chatBox.insertAdjacentHTML('beforeend', `
                <div id="${loadingId}" class="flex justify-start w-full">
                    <div class="bg-gray-50 text-gray-700 px-5 py-3.5 rounded-2xl rounded-tl-sm border border-gray-200 shadow-sm flex items-center gap-2">
                        <span class="text-[14px] font-medium text-gray-500">AI is thinking</span>
                        <div class="flex space-x-1.5 ml-1">
                            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.15s;"></div>
                            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.3s;"></div>
                        </div>
                    </div>
                </div>
            `);
            
            chatBox.scrollTop = chatBox.scrollHeight;

            try {
                const response = await fetch('/ai/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: rawMessage })
                });

                document.getElementById(loadingId).remove();

                if (!response.ok) {
                    throw new Error('Server returned an error');
                }

                const data = await response.json();
                
                // Add AI Message
                chatBox.insertAdjacentHTML('beforeend', `
                    <div class="flex justify-start w-full">
                        <div class="bg-gray-100 text-gray-800 px-5 py-3.5 rounded-2xl rounded-tl-sm max-w-[85%] md:max-w-[70%] border border-gray-200 shadow-sm text-[15px] leading-relaxed whitespace-pre-wrap">${escapeHTML(data.reply || data.message || '...')}</div>
                    </div>
                `);

            } catch (error) {
                // Handle Error
                if (document.getElementById(loadingId)) {
                    document.getElementById(loadingId).remove();
                }
                chatBox.insertAdjacentHTML('beforeend', `
                    <div class="flex justify-start w-full">
                        <div class="bg-red-50 text-red-800 px-5 py-3.5 rounded-2xl rounded-tl-sm max-w-[85%] md:max-w-[70%] border border-red-200 shadow-sm text-[15px] leading-relaxed">
                            Sorry, something went wrong. Please try again.
                        </div>
                    </div>
                `);
            } finally {
                // Re-enable UI
                input.disabled = false;
                button.disabled = false;
                input.focus();
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }
    });
</script>



</x-app-layout>