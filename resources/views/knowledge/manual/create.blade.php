<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($feedback) ? 'Teach AI' : 'New Manual Knowledge' }}
        </h2>
    </x-slot>

    <div class="py-8">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">

                @isset($feedback)

                    <a
                        href="{{ route('knowledge.feedback.show', $feedback) }}"
                        class="text-sm text-blue-600 hover:text-blue-800"
                    >
                        ← Kembali ke Review Feedback
                    </a>

                @else

                    <a
                        href="{{ route('knowledge.manual.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-800"
                    >
                        ← Kembali ke Manual Knowledge
                    </a>

                @endisset

            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="border-b px-6 py-5">

                    <h1 class="text-2xl font-bold text-gray-900">

                        {{ isset($feedback)
                            ? 'Teach AI'
                            : 'New Manual Knowledge' }}

                    </h1>

                    <p class="mt-2 text-sm text-gray-500">

                        {{ isset($feedback)
                            ? 'Tambahkan pengetahuan baru agar AI dapat menjawab pertanyaan serupa di masa mendatang.'
                            : 'Tambahkan knowledge baru yang akan digunakan AI untuk menjawab pertanyaan pengguna.' }}

                    </p>

                </div>

                <div class="p-6">

                    @isset($feedback)

                        <div class="mb-8 rounded-lg border bg-gray-50 p-5">

                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Pertanyaan Pengguna
                            </p>

                            <p class="mt-2 text-lg font-medium text-gray-900">
                                {{ $feedback->question }}
                            </p>

                            @if($feedback->answer)

                                <div class="mt-6">

                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        Jawaban AI Sebelumnya
                                    </p>

                                    <div class="mt-2 rounded-lg border bg-white p-4 whitespace-pre-wrap text-gray-700">
                                        {{ $feedback->answer }}
                                    </div>

                                </div>

                            @endif

                        </div>

                    @endisset

                    <form
                        method="POST"
                        action="{{ isset($feedback)
                            ? route('knowledge.manual.feedback.store', $feedback)
                            : route('knowledge.manual.store') }}"
                        class="space-y-6"
                    >

                        @csrf

                        <div>

                            <label
                                for="title"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Judul Knowledge
                            </label>

                            <input
                                id="title"
                                name="title"
                                type="text"
                                value="{{ old('title') }}"
                                placeholder="Contoh: SOP Pengajuan Cuti"
                                class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            @error('title')

                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                        <div>

                            <label
                                for="content"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Knowledge
                            </label>

                            <textarea
                                id="content"
                                name="content"
                                rows="14"
                                placeholder="Tuliskan informasi resmi yang akan digunakan AI..."
                                class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('content') }}</textarea>

                            @error('content')

                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                        <div class="rounded-lg border border-blue-200 bg-blue-50 p-5">

                            <h3 class="font-semibold text-blue-900">
                                Tips Menulis Knowledge
                            </h3>

                            <ul class="mt-3 list-disc space-y-2 pl-5 text-sm text-blue-800">

                                <li>Gunakan informasi resmi dari organisasi.</li>

                                <li>Tulis dengan bahasa yang jelas dan mudah dipahami.</li>

                                <li>Hindari opini pribadi.</li>

                                <li>Pastikan informasi masih berlaku.</li>

                                <li>Knowledge ini akan digunakan AI sebagai sumber jawaban.</li>

                            </ul>

                        </div>

                        <div class="flex justify-end gap-3">

                            @isset($feedback)

                                <a
                                    href="{{ route('knowledge.feedback.show', $feedback) }}"
                                    class="rounded-md border border-gray-300 px-5 py-2 text-gray-700 hover:bg-gray-100"
                                >
                                    Batal
                                </a>

                            @else

                                <a
                                    href="{{ route('knowledge.manual.index') }}"
                                    class="rounded-md border border-gray-300 px-5 py-2 text-gray-700 hover:bg-gray-100"
                                >
                                    Batal
                                </a>

                            @endisset

                            <button
                                type="submit"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-white hover:bg-indigo-700"
                            >
                                Simpan Knowledge
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>