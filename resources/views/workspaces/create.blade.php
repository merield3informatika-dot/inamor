<x-app-layout>
    <div class="max-w-xl mx-auto py-10">

        <h1 class="text-2xl font-bold mb-6">
            Create Workspace
        </h1>

        <form action="{{ route('workspaces.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block mb-2">
                    Workspace Name
                </label>

                <input
                    type="text"
                    name="name"
                    class="w-full border rounded px-3 py-2"
                    placeholder="My Organization"
                    required
                >

                @error('name')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button
                type="submit"
                class="px-4 py-2 bg-black text-white rounded"
            >
                Create Workspace
            </button>
        </form>

    </div>
</x-app-layout>