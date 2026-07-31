<x-layouts.onboarding>

<div class="mx-auto w-full max-w-2xl">

    <div class="mb-8">
        <p class="text-sm font-medium text-indigo-600">
            Step 1 of 3
        </p>

        <h1 class="mt-2 text-3xl font-bold text-gray-900">
            Create Your Workspace
        </h1>

        <p class="mt-2 text-gray-500">
            Give your workspace a name before continuing.
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm">

        <form
            action="{{ route('onboarding.identity.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            {{-- Workspace Name --}}
            <div class="mb-6">
                <label
                    for="name"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Workspace Name <span class="text-red-500">*</span>
                </label>

                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $identity['name'] ?? '') }}"
                    placeholder="Example Workspace"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >

                @error('name')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Workspace Logo --}}
            <div class="mb-6">
                <label
                    for="logo"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Workspace Logo
                </label>

                <input
                    id="logo"
                    name="logo"
                    type="file"
                    accept="image/*"
                    class="block w-full rounded-lg border border-gray-300 p-2 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-white hover:file:bg-indigo-700"
                >

                @error('logo')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Bio --}}
            <div class="mb-8">
                <label
                    for="bio"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Workspace Bio
                </label>

                <textarea
                    id="bio"
                    name="bio"
                    rows="4"
                    placeholder="Describe your workspace..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >{{ old('bio', $identity['bio'] ?? '') }}</textarea>

                @error('bio')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex justify-end">
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