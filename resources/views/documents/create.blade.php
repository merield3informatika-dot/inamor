<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Upload Document
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-6">

            <form action="{{ route('documents.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="bg-white rounded-xl shadow p-6 space-y-5">

                @csrf

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        class="w-full rounded-lg border-gray-300"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">
                        PDF File
                    </label>

                    <input
                        type="file"
                        name="document"
                        accept=".pdf"
                        required>
                </div>

                <button
                    class="px-5 py-2 bg-indigo-600 text-white rounded-lg">
                    Upload
                </button>

            </form>

        </div>
    </div>
</x-app-layout>