<x-app-layout>

    <div class="mb-6">
        <h2 class="text-[26px] font-bold text-gray-900 tracking-tight">Workspace Chat</h2>
        <p class="text-[14px] text-gray-500 mt-1">Diskusi bersama anggota workspace</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 h-[70vh]">

        <div class="md:col-span-1 bg-white rounded-[20px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-4 overflow-y-auto">

            <div class="text-[11.5px] font-bold text-gray-400 uppercase tracking-wide mb-3 px-1">
                Percakapan
            </div>

            <div class="flex flex-col gap-1">

                @foreach($conversations as $conversation)

                    <a href="{{route('workspace.chat.show', $conversation->id) }}"
                       class="px-3 py-2.5 rounded-xl text-[13px] font-medium transition-colors
                              {{ $activeConversation && $activeConversation->id === $conversation->id
                                    ? 'bg-blue-50 text-blue-700'
                                    : 'text-gray-600 hover:bg-gray-50' }}">
                        {{ $conversation->title }}
                    </a>

                @endforeach

            </div>

        </div>

        <div class="md:col-span-3 bg-white rounded-[20px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] flex flex-col overflow-hidden">

            @if($activeConversation)

                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-[14px] font-bold text-gray-900">{{ $activeConversation->title }}</h3>
                </div>

                <div class="flex-1 overflow-y-auto px-5 py-4 flex flex-col gap-3">

                    @forelse($messages as $message)

                        @php $isMine = $message->user_id === auth()->id(); @endphp

                        <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }}">

                            <span class="text-[10.5px] text-gray-400 mb-1 px-1">
                                {{ $isMine ? 'Anda' : ($message->user->name ?? 'Pengguna') }}
                                · {{ $message->created_at->format('H:i') }}
                            </span>

                            <div class="max-w-[75%] px-3.5 py-2.5 rounded-2xl text-[13px] leading-relaxed
                                        {{ $isMine ? 'bg-blue-600 text-white rounded-tr-sm' : 'bg-gray-50 text-gray-700 rounded-tl-sm' }}">
                                {{ $message->message }}
                            </div>

                        </div>

                    @empty

                        <div class="flex-1 flex flex-col items-center justify-center text-center py-10">
                            <div class="text-3xl mb-3">💬</div>
                            <p class="text-[12.5px] text-gray-400">Belum ada pesan. Mulai percakapan!</p>
                        </div>

                    @endforelse

                </div>

                <form method="POST" action="{{route(
    'workspace.chat.messages.store',
    $activeConversation->id
) }}"
                      class="px-5 py-4 border-t border-gray-100 flex items-center gap-3">
                    @csrf

                    <input type="text" name="message" required autocomplete="off"
                           placeholder="Tulis pesan..."
                           class="flex-1 px-3.5 py-2.5 rounded-xl border border-gray-200 text-[13px] focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400">

                    <button type="submit"
                            class="px-4 py-2.5 rounded-xl bg-blue-600 text-white text-[13px] font-semibold hover:bg-blue-700 transition-colors">
                        Kirim
                    </button>

                </form>

            @else

                <div class="flex-1 flex flex-col items-center justify-center text-center py-10">
                    <div class="text-3xl mb-3">💬</div>
                    <p class="text-[12.5px] text-gray-400">Pilih percakapan untuk memulai</p>
                </div>

            @endif

        </div>

    </div>

</x-app-layout>