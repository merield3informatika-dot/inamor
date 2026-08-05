<x-app-layout>

<div class="max-w-5xl mx-auto px-6 py-8">

   

    <div class="bg-white rounded-xl border shadow-sm mt-6">

        <div class="p-6 border-b">

            <h2 class="text-xl font-semibold">
                General Settings
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Manage your workspace information.
            </p>

        </div>

        <form
            action="{{ route('workspace.settings.update') }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-6 space-y-6"
        >
            @csrf
            @method('PUT')

            {{-- Logo --}}
            <div>

                <label class="block mb-2 font-medium">
                    Workspace Logo
                </label>

                @if($workspace->logo)

                    <img
                        src="{{ Storage::url($workspace->logo) }}"
                        class="w-20 h-20 rounded-xl object-cover mb-4 border"
                    >

                @endif

                <input
                    type="file"
                    name="logo"
                    class="block w-full border rounded-lg p-3"
                >

                @error('logo')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Name --}}
            <div>

                <label class="block mb-2 font-medium">
                    Workspace Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name',$workspace->name) }}"
                    class="w-full border rounded-lg px-4 py-3"
                >

                @error('name')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Description --}}
            <div>

                <label class="block mb-2 font-medium">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="w-full border rounded-lg px-4 py-3"
                >{{ old('description',$workspace->description) }}</textarea>

                @error('description')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            {{-- Visibility --}}
            <div>

                <label class="block mb-2 font-medium">
                    Visibility
                </label>

                <select
                    name="visibility"
                    class="w-full border rounded-lg px-4 py-3"
                >

                    <option
                        value="public"
                        @selected($workspace->visibility == 'public')
                    >
                        Public
                    </option>

                    <option
                        value="private"
                        @selected($workspace->visibility == 'private')
                    >
                        Private
                    </option>

                </select>

                @error('visibility')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror

            </div>

            <div class="pt-2">

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-6 py-3 text-white hover:bg-indigo-500"
                >
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>

</x-app-layout>