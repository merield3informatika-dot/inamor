<x-app-layout>

    <div class="max-w-5xl mx-auto py-6">

        <div class="flex items-center justify-between mb-6">

            <div>

                <a
                    href="{{ route('knowledge.manual.index') }}"
                    class="text-sm text-gray-500 hover:text-indigo-600"
                >
                    ← Back to Manual Knowledge
                </a>

                <h1 class="text-3xl font-bold mt-2">
                    {{ $knowledge->title }}
                </h1>

                <div class="flex items-center gap-3 mt-3">

                    @if($knowledge->status === 'published')

                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                            Published
                        </span>

                    @else

                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm">
                            Draft
                        </span>

                    @endif

                    <span class="text-sm text-gray-500">
                        Updated {{ $knowledge->updated_at->diffForHumans() }}
                    </span>

                </div>

            </div>

            <a
                href="{{ route('knowledge.manual.edit', $knowledge) }}"
                class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700"
            >
                Edit Knowledge
            </a>

        </div>

        <div class="bg-white rounded-xl shadow">

            <div class="border-b px-6 py-4">

                <h2 class="font-semibold text-lg">
                    Content
                </h2>

            </div>

            <div class="px-6 py-6">

                <div class="prose max-w-none whitespace-pre-line">

                    {{ $knowledge->content }}

                </div>

            </div>

        </div>

        <div class="bg-white rounded-xl shadow mt-6">

            <div class="px-6 py-5">

                <h2 class="font-semibold mb-4">
                    Information
                </h2>

                <div class="grid grid-cols-2 gap-6">

                    <div>

                        <p class="text-sm text-gray-500">
                            Created At
                        </p>

                        <p class="font-medium">
                            {{ $knowledge->created_at->format('d M Y H:i') }}
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Last Updated
                        </p>

                        <p class="font-medium">
                            {{ $knowledge->updated_at->format('d M Y H:i') }}
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Status
                        </p>

                        <p class="font-medium capitalize">
                            {{ $knowledge->status }}
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            Created By
                        </p>

                        <p class="font-medium">
                            {{ $knowledge->creator?->name ?? '-' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>