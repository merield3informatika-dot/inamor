<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">
                Documents
            </h2>

            <a href="{{ route('documents.create') }}"
                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Upload Document
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-6">

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl bg-white shadow">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                Title
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                Status
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                Size
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                Uploaded
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">

                        @forelse ($documents as $document)

                            <tr>

                                <td class="px-6 py-4">
                                    {{ $document->title }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="rounded bg-yellow-100 px-2 py-1 text-xs text-yellow-700">
                                        {{ ucfirst($document->status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    {{ number_format($document->file_size / 1024, 2) }} KB
                                </td>

                                <td class="px-6 py-4">
                                    {{ $document->created_at->format('d M Y H:i') }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                    No documents found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>
</x-app-layout>