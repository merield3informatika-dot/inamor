<x-app-layout>

    <div class="max-w-5xl mx-auto">

        <div class="mb-8 flex items-center justify-between">

            <div>

                
                 <a  href="{{ route('knowledge.feedback.index') }}"
                    class="text-sm text-indigo-600 hover:text-indigo-700">

                    ← Kembali

                </a>

                <h1 class="mt-2 text-2xl font-bold text-gray-900">
                    Review Knowledge Feedback
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Ajarkan AI bagaimana menjawab pertanyaan ini.
                </p>

            </div>

            @if ($feedback->status === 'pending')

                <span class="inline-flex rounded-full bg-yellow-100 px-4 py-2 text-sm font-semibold text-yellow-800">
                    🟡 Pending
                </span>

            @else

                <span class="inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">
                    🟢 Resolved
                </span>

            @endif

        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow">

            <div class="border-b border-gray-100 px-8 py-6">

                <div class="text-sm font-medium uppercase tracking-wider text-gray-400">
                    Pertanyaan
                </div>

                <h2 class="mt-3 text-2xl font-semibold text-gray-900">
                    {{ $feedback->question }}
                </h2>

            </div>

            <div class="grid grid-cols-3 divide-x divide-gray-100">

                <div class="p-6 text-center">

                    <div class="text-sm text-gray-500">
                        Ditanyakan
                    </div>

                    <div class="mt-2 text-3xl font-bold text-indigo-600">
                        {{ $feedback->asked_count }}x
                    </div>

                </div>

                <div class="p-6 text-center">

                    <div class="text-sm text-gray-500">
                        Dibuat
                    </div>

                    <div class="mt-2 font-semibold text-gray-900">
                        {{ $feedback->created_at->format('d M Y') }}
                    </div>

                    <div class="text-sm text-gray-500">
                        {{ $feedback->created_at->format('H:i') }}
                    </div>

                </div>

                <div class="p-6 text-center">

                    <div class="text-sm text-gray-500">
                        Status
                    </div>

                    <div class="mt-2 font-semibold text-gray-900">
                        {{ ucfirst($feedback->status) }}
                    </div>

                </div>

            </div>

        </div>

        <div class="mt-8 rounded-xl border border-indigo-100 bg-indigo-50 p-6">

            <div class="flex items-start gap-4">

                <div class="text-4xl">

                    🤖

                </div>

                <div>

                    <h3 class="text-lg font-semibold text-indigo-900">
                        AI belum mengetahui jawaban untuk pertanyaan ini.
                    </h3>

                    <p class="mt-2 text-sm text-indigo-700 leading-relaxed">
                        Tambahkan knowledge baru melalui Manual Knowledge
                        atau unggah dokumen yang berisi informasi terkait.
                        Setelah diproses, AI akan dapat menjawab pertanyaan
                        serupa secara otomatis.
                    </p>

                </div>

            </div>

        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2">

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow">

                <div class="text-4xl">

                    📄

                </div>

                <h3 class="mt-4 text-lg font-semibold text-gray-900">
                    Upload Document
                </h3>

                <p class="mt-2 text-sm leading-relaxed text-gray-500">
                    Upload PDF yang berisi jawaban resmi.
                    AI akan melakukan indexing dan menggunakan dokumen
                    tersebut sebagai sumber jawaban.
                </p>

              <a
    href="{{ route('documents.create', ['feedback' => $feedback->id]) }}"
    class="mt-6 inline-flex rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700"
>
    Upload Dokumen
</a>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow">

                <div class="text-4xl">

                    ✍️

                </div>

                <h3 class="mt-4 text-lg font-semibold text-gray-900">
                    Manual Knowledge
                </h3>

                <p class="mt-2 text-sm leading-relaxed text-gray-500">
                    Tambahkan jawaban secara manual tanpa perlu
                    mengunggah dokumen.
                    Cocok untuk informasi singkat atau FAQ.
                </p>

   <a            
    href="{{ route('knowledge.manual.feedback.create', $feedback) }}"
    class="mt-6 inline-flex rounded-lg bg-gray-900 px-5 py-3 text-sm font-semibold text-white hover:bg-black">

    Teach AI

</a>

            </div>

        </div>

        <div class="mt-8 rounded-xl border border-dashed border-gray-300 bg-white p-6">

            <h3 class="font-semibold text-gray-900">
                Timeline
            </h3>

            <div class="mt-5 space-y-5">

                <div class="flex items-start gap-4">

                    <div class="mt-1 h-3 w-3 rounded-full bg-yellow-400"></div>

                    <div>

                        <div class="font-medium text-gray-900">
                            Feedback dibuat
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ $feedback->created_at->format('d M Y H:i') }}
                        </div>

                    </div>

                </div>

                @if ($feedback->status === 'resolved')

                    <div class="flex items-start gap-4">

                        <div class="mt-1 h-3 w-3 rounded-full bg-green-500"></div>

                        <div>

                            <div class="font-medium text-gray-900">
                                Feedback telah diselesaikan
                            </div>

                            <div class="text-sm text-gray-500">
                                AI telah memiliki knowledge untuk pertanyaan ini.
                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>