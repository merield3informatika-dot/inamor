<x-app-layout>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-[26px] md:text-[30px] font-bold text-gray-900 tracking-tight">Pengumuman</h2>
            <p class="text-[14px] text-gray-500 mt-1">Informasi resmi untuk seluruh anggota workspace</p>
        </div>

        @if($isAdmin)
            <a href="{{ route('announcements.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 text-white text-[13px] font-semibold hover:bg-blue-700 transition-colors">
                + Buat Pengumuman
            </a>
        @endif
    </div>

    @if(session('status'))
        <div class="mb-5 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 text-[13px] font-medium">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex flex-col gap-4">

        @forelse($announcements as $announcement)

            <div class="bg-white rounded-[20px] border border-gray-100 shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-6">

                <div class="flex items-start justify-between gap-4 mb-3">

                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-[16px] font-bold text-gray-900">{{ $announcement->title }}</h3>

                            @if($announcement->status === 'published')
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10.5px] font-bold uppercase">Published</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-[10.5px] font-bold uppercase">Draft</span>
                            @endif
                        </div>

                        <p class="text-[12px] text-gray-400">
                            Oleh {{ $announcement->author->name ?? 'Admin' }}
                            @if($announcement->published_at)
                                · Dipublikasikan {{ $announcement->published_at->diffForHumans() }}
                            @else
                                · Dibuat {{ $announcement->created_at->diffForHumans() }}
                            @endif
                        </p>
                    </div>

                    @if($isAdmin)
                        <div class="flex items-center gap-2 shrink-0">

                            @if($announcement->status === 'draft')
                                <form method="POST" action="{{ route('announcements.publish', $announcement) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-[12px] font-semibold hover:bg-blue-700 transition-colors">
                                        Publish
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('announcements.edit', $announcement) }}"
                               class="px-3 py-1.5 rounded-lg bg-gray-50 border border-gray-100 text-gray-600 text-[12px] font-semibold hover:bg-gray-100 transition-colors">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('announcements.destroy', $announcement) }}"
                                  onsubmit="return confirm('Hapus pengumuman ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 text-[12px] font-semibold hover:bg-red-100 transition-colors">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    @endif

                </div>

                <p class="text-[13.5px] text-gray-600 leading-relaxed whitespace-pre-line">{{ $announcement->content }}</p>

            </div>

        @empty

            <div class="flex flex-col items-center justify-center py-16 text-center rounded-[20px] border-2 border-dashed border-gray-100 bg-gray-50/50">
                <div class="text-3xl mb-3">📢</div>
                <p class="text-[13px] text-gray-500">Belum ada pengumuman</p>
            </div>

        @endforelse

    </div>

</x-app-layout>