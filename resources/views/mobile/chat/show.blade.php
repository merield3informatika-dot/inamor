@extends('layouts.mobile')

@section('title', 'Workspace Chat')

@section('content')

<div class="mx-auto flex min-h-[calc(100dvh-64px)] w-full max-w-lg flex-col bg-white">

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


        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[11px] bg-purple-50 text-purple-600">

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
                    d="M8.625 9.75h6.75m-6.75 3h4.5m-8.25 5.25l1.5-3.75A8.25 8.25 0 0112 3.75a8.25 8.25 0 018.25 8.25A8.25 8.25 0 0112 20.25a8.2 8.2 0 01-3.75-.9l-3.375.9z"
                />
            </svg>

        </div>


        <div class="min-w-0 flex-1">

            <h1 class="truncate text-[14px] font-bold text-gray-900">
                {{ $activeConversation->title ?? 'Workspace Chat' }}
            </h1>

            <p class="truncate text-[10px] text-gray-400">
                {{ $workspace->name }}
            </p>

        </div>

    </header>


    {{-- =========================================================
         MESSAGES
    ========================================================== --}}

    <main class="flex-1 overflow-y-auto px-4 py-5">

        @forelse($messages as $message)

            @php
                $isMine = (int) $message->user_id === (int) $user->id;
            @endphp


            <div class="mb-5 flex {{ $isMine ? 'justify-end' : 'justify-start' }}">

                <div class="max-w-[82%]">

                    {{-- Sender --}}

                    @if(! $isMine)

                        <p class="mb-1 px-1 text-[9px] font-semibold text-gray-400">
                            {{ $message->user?->name ?? 'Member' }}
                        </p>

                    @endif


                    {{-- Message --}}

                    <div
                        class="
                            rounded-[18px] px-4 py-3
                            {{
                                $isMine
                                    ? 'rounded-br-[6px] bg-purple-600 text-white'
                                    : 'rounded-bl-[6px] border border-gray-100 bg-gray-50 text-gray-700'
                            }}
                        "
                    >

                        <p class="whitespace-pre-wrap break-words text-[12px] leading-relaxed">
                            {{ $message->message }}
                        </p>

                    </div>


                    {{-- Time --}}

                    <p
                        class="
                            mt-1 px-1 text-[8px] text-gray-400
                            {{ $isMine ? 'text-right' : 'text-left' }}
                        "
                    >
                        {{ $message->created_at?->format('H:i') }}
                    </p>

                </div>

            </div>

        @empty

            <div class="flex h-full min-h-[400px] flex-col items-center justify-center px-6 text-center">

                <div class="flex h-14 w-14 items-center justify-center rounded-[18px] bg-purple-50 text-purple-500">

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
                            d="M8.625 9.75h6.75m-6.75 3h4.5m-8.25 5.25l1.5-3.75A8.25 8.25 0 0112 3.75a8.25 8.25 0 018.25 8.25A8.25 8.25 0 0112 20.25a8.2 8.2 0 01-3.75-.9l-3.375.9z"
                        />
                    </svg>

                </div>


                <h2 class="mt-4 text-[15px] font-bold text-gray-800">
                    Belum ada pesan
                </h2>


                <p class="mt-1 max-w-[240px] text-[10px] leading-relaxed text-gray-400">
                    Mulai percakapan dengan anggota workspace.
                </p>

            </div>

        @endforelse

    </main>


    {{-- =========================================================
         VALIDATION ERROR
    ========================================================== --}}

    @if($errors->any())

        <div class="shrink-0 border-t border-red-100 bg-red-50 px-4 py-2">

            <p class="text-[9px] leading-relaxed text-red-600">
                {{ $errors->first('message') }}
            </p>

        </div>

    @endif


    {{-- =========================================================
         MESSAGE INPUT
    ========================================================== --}}

    <footer class="shrink-0 border-t border-gray-100 bg-white px-3 pt-3 pb-[max(12px,env(safe-area-inset-bottom))]">

        <form
            method="POST"
            action="{{ route('mobile.chat.messages.store', $activeConversation->id) }}"
            class="flex items-end gap-2"
        >

            @csrf


            <textarea
                name="message"
                rows="1"
                maxlength="5000"
                required
                placeholder="Tulis pesan..."
                class="min-h-[44px] min-w-0 flex-1 resize-none rounded-[15px] border border-gray-200 bg-gray-50 px-4 py-3 text-[12px] text-gray-900 outline-none placeholder:text-gray-400 focus:border-purple-300 focus:bg-white focus:ring-2 focus:ring-purple-100"
            ></textarea>


            <button
                type="submit"
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[14px] bg-purple-600 text-white active:scale-95"
                aria-label="Kirim pesan"
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

@endsection