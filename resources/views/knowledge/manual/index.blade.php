<x-app-layout>

    <div class="max-w-7xl mx-auto py-6">

        <div class="flex items-center justify-between mb-6">

            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Manual Knowledge
                </h1>

                <p class="mt-1 text-gray-500">
                    Kelola seluruh knowledge yang digunakan AI.
                </p>
            </div>

            <a
                href="{{ route('knowledge.manual.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-700 transition"
            >
                <span>+</span>
                <span>New Knowledge</span>
            </a>

        </div>

        @if(session('success'))

            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>

        @endif

        <div class="overflow-hidden rounded-xl bg-white shadow">

            <table class="min-w-full">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-sm font-semibold">
                            Title
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold">
                            Status
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold">
                            Updated
                        </th>

                        <th class="px-6 py-4 text-right text-sm font-semibold">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($knowledges as $knowledge)

                        <tr class="border-t hover:bg-gray-50">

                            <td class="px-6 py-5">

                                <div class="font-semibold text-gray-900">
                                    {{ $knowledge->title }}
                                </div>

                            </td>

                            <td class="px-6 py-5">

                                @if($knowledge->status === 'published')

                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Published
                                    </span>

                                @else

                                    <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                        Draft
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-5 text-sm text-gray-500">
                                {{ $knowledge->updated_at->diffForHumans() }}
                            </td>

                            <td class="px-6 py-5">

                                <div class="flex justify-end gap-2">

                                    <a
                                        href="{{ route('knowledge.manual.show', $knowledge) }}"
                                        class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 transition"
                                    >
                                        👁 View
                                    </a>

                                    <a
                                        href="{{ route('knowledge.manual.edit', $knowledge) }}"
                                        class="rounded-lg bg-amber-500 px-3 py-2 text-sm font-medium text-white hover:bg-amber-600 transition"
                                    >
                                        ✏ Edit
                                    </a>

                                    <form
                                        action="{{ route('knowledge.manual.destroy', $knowledge) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus knowledge ini?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700 transition"
                                        >
                                            🗑 Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="px-6 py-12 text-center text-gray-500"
                            >
                                Belum ada Manual Knowledge.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>