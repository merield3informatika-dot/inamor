<x-layouts.onboarding>

<div class="mx-auto w-full max-w-2xl">

    <div class="mb-8">
        <p class="text-sm font-medium text-indigo-600">
            Step 2 of 3
        </p>

        <h1 class="mt-2 text-3xl font-bold text-gray-900">
            Workspace Privacy
        </h1>

        <p class="mt-2 text-gray-500">
            Choose who can access your workspace.
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm">

        <form
            action="{{ route('onboarding.privacy.store') }}"
            method="POST"
        >
            @csrf

            <div class="space-y-4">

                {{-- Private --}}
                <label class="flex cursor-pointer items-start gap-4 rounded-lg border border-gray-300 p-4 hover:border-indigo-500">

                    <input
                        type="radio"
                        name="visibility"
                        value="private"
                        {{ old('visibility', $privacy['visibility'] ?? 'private') == 'private' ? 'checked' : '' }}
                        class="mt-1"
                    >

                    <div>
                        <h3 class="font-semibold text-gray-900">
                            Private
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Only invited members can join this workspace.
                        </p>
                    </div>

                </label>

                {{-- Public --}}
                <label class="flex cursor-pointer items-start gap-4 rounded-lg border border-gray-300 p-4 hover:border-indigo-500">

                    <input
                        type="radio"
                        name="visibility"
                        value="public"
                        {{ old('visibility', $privacy['visibility'] ?? '') == 'public' ? 'checked' : '' }}
                        class="mt-1"
                    >

                    <div>
                        <h3 class="font-semibold text-gray-900">
                            Public
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Anyone with the invitation link can join this workspace.
                        </p>
                    </div>

                </label>

            </div>

            @error('visibility')
                <p class="mt-3 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror

            <div class="mt-8 flex items-center justify-between">

                <a
                    href="{{ route('onboarding.identity') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2 text-gray-700 transition hover:bg-gray-100"
                >
                    ← Back
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-6 py-3 font-medium text-white transition hover:bg-indigo-700"
                >
                    Continue →
                </button>

            </div>

        </form>

    </div>

</div>

</x-layouts.onboarding>