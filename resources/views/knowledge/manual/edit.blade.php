<x-app-layout>

    <div class="max-w-5xl mx-auto py-6">

        <div class="flex items-center justify-between mb-6">

            <div>

                <a
                    href="{{ route('knowledge.manual.show', $knowledge) }}"
                    class="text-sm text-gray-500 hover:text-indigo-600"
                >
                    ← Back
                </a>

                <h1 class="text-3xl font-bold mt-2">
                    Edit Manual Knowledge
                </h1>

                <p class="text-gray-500 mt-1">
                    Perbarui knowledge yang digunakan oleh AI.
                </p>

            </div>

        </div>

        <form
            action="{{ route('knowledge.manual.update', $knowledge) }}"
            method="POST"
            class="space-y-6"
        >

            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow">

                <div class="p-6 space-y-6">

                    <div>

                        <label class="block text-sm font-medium mb-2">
                            Title
                        </label>

                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', $knowledge->title) }}"
                            class="w-full rounded-lg border-gray-300"
                            required
                        >

                        @error('title')

                            <p class="text-red-500 text-sm mt-2">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                    <div>

                        <label class="block text-sm font-medium mb-2">
                            Content
                        </label>

                        <textarea
                            name="content"
                            rows="18"
                            class="w-full rounded-lg border-gray-300"
                            required
                        >{{ old('content', $knowledge->content) }}</textarea>

                        @error('content')

                            <p class="text-red-500 text-sm mt-2">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                    <div>

                        <label class="block text-sm font-medium mb-2">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-lg border-gray-300"
                        >

                            <option
                                value="draft"
                                @selected(old('status', $knowledge->status) === 'draft')
                            >
                                Draft
                            </option>

                            <option
                                value="published"
                                @selected(old('status', $knowledge->status) === 'published')
                            >
                                Published
                            </option>

                        </select>

                        @error('status')

                            <p class="text-red-500 text-sm mt-2">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>

            </div>

            <div class="flex items-center justify-between">

                <a
                    href="{{ route('knowledge.manual.show', $knowledge) }}"
                    class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="px-5 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700"
                >
                    Update Knowledge
                </button>

            </div>

        </form>

    </div>

</x-app-layout>