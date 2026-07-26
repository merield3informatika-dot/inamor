<x-app-layout>

    <x-slot name="header">
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto flex flex-col h-[75vh] min-h-[600px]">

            <div id="chat-box"
                class="flex-1 bg-white rounded-xl shadow-sm border border-gray-200 overflow-y-auto p-6 space-y-6 relative scroll-smooth flex flex-col">

                <div id="empty-state"
                    class="absolute inset-0 flex flex-col items-center justify-center text-center p-8 bg-white rounded-xl z-10">

                    <div
                        class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mb-6 border border-indigo-100 shadow-sm">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-8 h-8 text-indigo-600">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>

                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        AI Assistant
                    </h3>

                    <p class="text-gray-600 mb-4">
                        Ask questions about your organization's documents.
                    </p>

                </div>

            </div>

            <div
                class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-2">

                <div class="flex items-end gap-3">

                    <textarea id="message"
                        rows="1"
                        class="flex-1 resize-none border-0 focus:ring-0 py-3 px-4"
                        placeholder="Message AI Assistant..."></textarea>

                    <button id="send"
                        class="p-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">

                        Send

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

    const escapeHTML = (str = '') => {

        return String(str).replace(/[&<>'"]/g, tag => ({
            '&':'&amp;',
            '<':'&lt;',
            '>':'&gt;',
            "'":'&#39;',
            '"':'&quot;'
        }[tag]));

    };

    input.addEventListener('keydown', e => {

        if (e.key === 'Enter' && !e.shiftKey) {

            e.preventDefault();
            sendMessage();

        }

    });

    button.addEventListener('click', sendMessage);

    async function sendMessage() {

        const rawMessage = input.value.trim();

        if (!rawMessage) return;

        if (emptyState) {
            emptyState.style.display = 'none';
        }

        chatBox.insertAdjacentHTML('beforeend', `
            <div class="flex justify-end">
                <div class="bg-indigo-600 text-white rounded-xl px-4 py-3 max-w-[80%] whitespace-pre-wrap">
                    ${escapeHTML(rawMessage)}
                </div>
            </div>
        `);

        input.value = '';

        button.disabled = true;
        input.disabled = true;

        const loadingId = 'loading-' + Date.now();

        chatBox.insertAdjacentHTML('beforeend', `
            <div id="${loadingId}" class="flex justify-start">
                <div class="bg-gray-100 rounded-xl px-4 py-3">
                    AI is thinking...
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

                body: JSON.stringify({
                    message: rawMessage
                })

            });

            if (document.getElementById(loadingId)) {
                document.getElementById(loadingId).remove();
            }

            if (!response.ok) {
                throw new Error(await response.text());
            }

            const data = await response.json();

            console.log('AI Response =>', data);

            const answer =
                data.answer ??
                data.reply ??
                data.message ??
                'Informasi tidak ditemukan.';

            chatBox.insertAdjacentHTML('beforeend', `
                <div class="flex justify-start">
                    <div class="bg-gray-100 rounded-xl px-4 py-3 max-w-[80%] whitespace-pre-wrap">
                        ${escapeHTML(answer)}
                    </div>
                </div>
            `);

        } catch (err) {

            console.error(err);

            if (document.getElementById(loadingId)) {
                document.getElementById(loadingId).remove();
            }

            chatBox.insertAdjacentHTML('beforeend', `
                <div class="flex justify-start">
                    <div class="bg-red-100 text-red-700 rounded-xl px-4 py-3 max-w-[80%] whitespace-pre-wrap">
                        ${escapeHTML(err.message)}
                    </div>
                </div>
            `);

        } finally {

            button.disabled = false;
            input.disabled = false;
            input.focus();

            chatBox.scrollTop = chatBox.scrollHeight;

        }

    }

});
</script>

</x-app-layout>