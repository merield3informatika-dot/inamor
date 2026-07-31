<x-layouts.onboarding>

<div class="mx-auto w-full max-w-2xl">

    <div class="mb-8">
        <p class="text-sm font-medium text-indigo-600">
            Step 3 of 3
        </p>

        <h1 class="mt-2 text-3xl font-bold text-gray-900">
            Upload Your Knowledge
        </h1>

        <p class="mt-2 text-gray-500">
            Upload your first documents now, or skip this step and do it later.
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm">

        <form
            action="{{ route('onboarding.knowledge.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            {{-- Knowledge Files --}}
            <div class="mb-6">

                <label
                    for="documents"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Knowledge Documents
                </label>

                <input
                    id="documents"
                    name="documents[]"
                    type="file"
                    multiple
                    accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.csv,.txt"
                    class="block w-full rounded-lg border border-gray-300 p-2 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-white hover:file:bg-indigo-700"
                >

                <p class="mt-2 text-sm text-gray-500">
                    Supported:
                    PDF, DOCX, PPTX, XLSX, CSV, TXT
                </p>

                @error('documents')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            <div class="mt-8 flex items-center justify-between">

                <a
                    href="{{ route('onboarding.privacy') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2 text-gray-700 transition hover:bg-gray-100"
                >
                    ← Back
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-6 py-3 font-medium text-white transition hover:bg-indigo-700"
                >
                    Create Workspace →
                </button>

            </div>

        </form>

    </div>

</div>

</x-layouts.onboarding>