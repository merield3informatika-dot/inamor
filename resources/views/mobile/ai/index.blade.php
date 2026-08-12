@extends('layouts.mobile')

@section('title', 'AI Assistant')

@section('content')

<div
    class="mx-auto flex h-[calc(100dvh-64px)] w-full max-w-lg flex-col bg-white"
    x-data="mobileAI"
>

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <header class="flex h-[60px] shrink-0 items-center gap-3 border-b border-gray-100 px-4">

        <a
            href="{{ route('mobile.home') }}"
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-gray-200 text-gray-500 active:bg-gray-50"
            aria-label="Kembali"
        >
            <svg
                class="h-4 w-4"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 19l-7-7 7-7"
                />
            </svg>
        </a>


        <div class="flex h-9 w-9 items-center justify-center rounded-[11px] bg-purple-50 text-purple-600">

            <svg
                class="h-[18px] w-[18px]"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 003.09 3.09L9.813 15.904z"
                />
            </svg>

        </div>


        <div class="min-w-0">

            <h1 class="text-[14px] font-bold text-gray-900">
                AI Assistant
            </h1>

            <p class="text-[10px] text-gray-400">
                Knowledge Workspace
            </p>

        </div>

    </header>


    {{-- =========================================================
         MESSAGES
    ========================================================== --}}

    <main
        x-ref="messages"
        class="min-h-0 flex-1 overflow-y-auto px-4 py-5"
    >

        {{-- Empty state --}}

        <div
            x-show="messages.length === 0"
            class="flex h-full flex-col items-center justify-center px-6 text-center"
        >

            <div class="flex h-14 w-14 items-center justify-center rounded-[18px] bg-purple-50 text-purple-600">

                <svg
                    class="h-7 w-7"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 003.09 3.09L9.813 15.904z"
                    />
                </svg>

            </div>


            <h2 class="mt-4 text-[16px] font-bold text-gray-900">
                Ada yang ingin ditanyakan?
            </h2>


            <p class="mt-1.5 max-w-[260px] text-[11px] leading-relaxed text-gray-400">
                Tanyakan sesuatu tentang informasi yang tersedia di knowledge workspace.
            </p>

        </div>


        {{-- Messages --}}

        <div
            x-show="messages.length > 0"
            class="space-y-5"
        >

            <template
                x-for="(message, index) in messages"
                :key="index"
            >

                <div>

                    {{-- User --}}

                    <template x-if="message.role === 'user'">

                        <div class="flex justify-end">

                            <div class="max-w-[82%] rounded-[18px] rounded-br-[6px] bg-purple-600 px-4 py-3 text-white">

                                <p
                                    class="whitespace-pre-wrap break-words text-[12px] leading-relaxed"
                                    x-text="message.content"
                                ></p>

                            </div>

                        </div>

                    </template>


                    {{-- Assistant --}}

                    <template x-if="message.role === 'assistant'">

                        <div class="flex justify-start">

                            <div class="max-w-[86%] rounded-[18px] rounded-bl-[6px] bg-gray-50 px-4 py-3">

                                <p
                                    class="whitespace-pre-wrap break-words text-[12px] leading-relaxed text-gray-700"
                                    x-text="message.content"
                                ></p>

                            </div>

                        </div>

                    </template>

                </div>

            </template>


            {{-- Loading --}}

            <div
                x-show="loading"
                class="flex justify-start"
            >

                <div class="rounded-[18px] rounded-bl-[6px] bg-gray-50 px-4 py-3">

                    <div class="flex items-center gap-1">

                        <span class="h-1.5 w-1.5 rounded-full bg-gray-300"></span>

                        <span class="h-1.5 w-1.5 rounded-full bg-gray-300"></span>

                        <span class="h-1.5 w-1.5 rounded-full bg-gray-300"></span>

                    </div>

                </div>

            </div>


            {{-- Error --}}

            <div
                x-show="error"
                class="rounded-[12px] border border-red-100 bg-red-50 px-3 py-2"
            >

                <p
                    class="text-[10px] leading-relaxed text-red-600"
                    x-text="error"
                ></p>

            </div>

        </div>

    </main>


    {{-- =========================================================
         INPUT
    ========================================================== --}}

    <footer class="shrink-0 border-t border-gray-100 bg-white px-3 pt-3 pb-[max(12px,env(safe-area-inset-bottom))]">

        <form
            @submit.prevent="sendMessage"
            class="flex items-end gap-2"
        >

            @csrf

            <textarea
                x-model="input"
                x-ref="input"
                rows="1"
                maxlength="2000"
                placeholder="Tanyakan sesuatu..."
                class="min-h-[44px] min-w-0 flex-1 resize-none rounded-[15px] border border-gray-200 bg-gray-50 px-4 py-3 text-[12px] text-gray-900 outline-none placeholder:text-gray-400 focus:border-purple-300 focus:bg-white focus:ring-2 focus:ring-purple-100"
            ></textarea>


            <button
                type="submit"
                :disabled="loading || !input.trim()"
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[14px] bg-purple-600 text-white active:scale-95 disabled:opacity-40"
                aria-label="Kirim"
            >

                <svg
                    class="h-[18px] w-[18px]"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4.5 12l15-7-4.5 14-3-6-7.5-1z"
                    />
                </svg>

            </button>

        </form>

    </footer>

</div>


@push('scripts')

<script>
    document.addEventListener('alpine:init', () => {

        Alpine.data('mobileAI', () => ({

            input: '',
            loading: false,
            error: '',
            messages: [],


            async sendMessage() {

                const message = this.input.trim();

                if (!message || this.loading) {
                    return;
                }


                this.error = '';


                this.messages.push({
                    role: 'user',
                    content: message,
                });


                this.input = '';

                await this.scrollToBottom();


                this.loading = true;


                try {

                    const response = await fetch('/ai/chat', {

                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',

                            'X-CSRF-TOKEN':
                                document
                                    .querySelector('meta[name="csrf-token"]')
                                    ?.getAttribute('content') ?? '',
                        },

                        body: JSON.stringify({
                            message: message,
                        }),

                    });


                    const data = await response.json();


                    if (!response.ok) {

                        throw new Error(
                            data.message ??
                            data.answer ??
                            'Terjadi kesalahan saat memproses pertanyaan.'
                        );

                    }


                    this.messages.push({

                        role: 'assistant',

                        content:
                            data.answer ??
                            'Tidak ada jawaban.',

                    });


                } catch (error) {

                    console.error(error);

                    this.error =
                        error.message ??
                        'Tidak dapat terhubung ke AI. Silakan coba lagi.';

                } finally {

                    this.loading = false;

                    await this.scrollToBottom();

                }

            },


            async scrollToBottom() {

                await this.$nextTick();

                const container = this.$refs.messages;

                if (!container) {
                    return;
                }

                container.scrollTop =
                    container.scrollHeight;

            },

        }));

    });
</script>

@endpush

@endsection