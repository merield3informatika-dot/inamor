<x-app-layout>

<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Create Workspace
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Create a new workspace for your organization.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm max-w-3xl">

        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="font-semibold text-gray-900">
                Workspace Details
            </h2>
        </div>

        <form 
            action="{{ route('workspaces.store') }}" 
            method="POST" 
            enctype="multipart/form-data" 
            class="p-6 space-y-7"
        >
            @csrf

            {{-- Logo with AlpineJS Preview --}}
            <div x-data="{ photoName: null, photoPreview: null }">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Logo Workspace
                </label>

                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 rounded-xl border bg-gray-50 overflow-hidden flex items-center justify-center">
                        <template x-if="!photoPreview">
                            <svg class="w-8 h-8 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </template>
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-full h-full object-cover">
                        </template>
                    </div>

                    <div>
                        <input 
                            type="file" 
                            name="logo" 
                            accept="image/*" 
                            class="hidden" 
                            x-ref="logo" 
                            @change="
                                photoName = $refs.logo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => { photoPreview = e.target.result; };
                                reader.readAsDataURL($refs.logo.files[0]);
                            "
                        >

                        <button 
                            type="button" 
                            @click="$refs.logo.click()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-gray-900 transition-colors"
                        >
                            Select Image
                        </button>

                        <p class="mt-2 text-xs text-gray-500">
                            PNG atau JPG • Maksimal 2MB
                        </p>

                        @error('logo')
                            <p class="mt-2 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Workspace Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Workspace Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-gray-900 focus:border-gray-900"
                    placeholder="Masukkan nama workspace"
                >
                @error('name')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea 
                    name="description" 
                    rows="4" 
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-gray-900 focus:border-gray-900"
                    placeholder="Deskripsi singkat mengenai workspace Anda..."
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Visibility --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Visibility
                </label>

                <div class="space-y-3">
                    <label class="flex gap-3 border rounded-lg p-4 cursor-pointer transition-colors {{ old('visibility', 'private') == 'private' ? 'border-gray-900 bg-gray-50/50' : 'border-gray-200 hover:border-gray-400' }}">
                        <input 
                            type="radio" 
                            name="visibility" 
                            value="private" 
                            class="mt-0.5 text-gray-900 focus:ring-gray-900"
                            {{ old('visibility', 'private') == 'private' ? 'checked' : '' }}
                        >
                        <div>
                            <p class="font-medium text-gray-900">
                                Private
                            </p>
                            <p class="text-sm text-gray-500">
                                Only invited members can access this workspace.
                            </p>
                        </div>
                    </label>

                    <label class="flex gap-3 border rounded-lg p-4 cursor-pointer transition-colors {{ old('visibility') == 'public' ? 'border-gray-900 bg-gray-50/50' : 'border-gray-200 hover:border-gray-400' }}">
                        <input 
                            type="radio" 
                            name="visibility" 
                            value="public" 
                            class="mt-0.5 text-gray-900 focus:ring-gray-900"
                            {{ old('visibility') == 'public' ? 'checked' : '' }}
                        >
                        <div>
                            <p class="font-medium text-gray-900">
                                Public
                            </p>
                            <p class="text-sm text-gray-500">
                                Workspace dapat diakses melalui link atau QR Code, tidak bebas bergabung.
                            </p>
                        </div>
                    </label>
                </div>

                @error('visibility')
                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="border-t border-gray-100 pt-6 flex items-center justify-end gap-3">
                <a 
                    href="{{ route('dashboard') }}" 
                    class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </a>
                <button 
                    type="submit" 
                    class="px-5 py-2.5 rounded-lg bg-gray-900 hover:bg-black text-white font-medium transition-colors"
                >
                    Create Workspace
                </button>
            </div>
        </form>

    </div>

</div>

</x-app-layout>