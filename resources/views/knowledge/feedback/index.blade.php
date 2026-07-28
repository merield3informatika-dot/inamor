<x-app-layout>

    <div class="max-w-7xl mx-auto">

        <div class="flex items-center justify-between mb-8">

            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Knowledge Feedback
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    AI belum dapat menjawab beberapa pertanyaan. Review dan tambahkan knowledge agar AI semakin pintar.
                </p>
            </div>

            <div class="text-right">

                <div class="text-3xl font-bold text-indigo-600">
                    {{ $feedbacks->total() }}
                </div>

                <div class="text-sm text-gray-500">
                    Total Feedback
                </div>

            </div>

        </div>

        <div class="overflow-hidden bg-white border border-gray-200 shadow rounded-xl">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Question
                        </th>

                        <th class="w-28 px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Asked
                        </th>

                        <th class="w-36 px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Status
                        </th>

                        <th class="w-48 px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Created
                        </th>

                        <th class="w-40 px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody class="bg-white divide-y divide-gray-100">

                    @forelse ($feedbacks as $feedback)

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-6 py-5">

                                <div class="font-semibold text-gray-900">
                                    {{ $feedback->question }}
                                </div>

                                <div class="mt-1 text-xs text-gray-500">
                                    Workspace #{{ $feedback->workspace_id }}
                                </div>

                            </td>

                            <td class="px-6 py-5 text-center">

                                <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-sm font-semibold text-indigo-700">
                                    {{ $feedback->asked_count }}x
                                </span>

                            </td>

                            <td class="px-6 py-5 text-center">

                                @if ($feedback->status === 'pending')

                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                                        🟡 Pending
                                    </span>

                                @else

                                    <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        🟢 Resolved
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-5 text-center text-sm text-gray-500">

                                {{ $feedback->created_at->format('d M Y H:i') }}

                            </td>

                            <td class="px-6 py-5 text-center">

                                <a
                                    href="{{ route('knowledge.feedback.show', $feedback) }}"
                                    class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700">

                                    Review

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="py-20 text-center">

                                <div class="text-lg font-semibold text-gray-400">
                                    🎉 Belum ada feedback
                                </div>

                                <p class="mt-2 text-sm text-gray-500">
                                    Semua pertanyaan berhasil dijawab oleh AI.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-6">

            {{ $feedbacks->links() }}

        </div>

    </div>

</x-app-layout>