<x-app-layout>

    <div class="max-w-5xl mx-auto px-6 py-10">

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="h-36 bg-gradient-to-r from-indigo-500 to-indigo-600"></div>

            <div class="px-8 pb-8">

                {{-- Avatar --}}
                <div class="-mt-16">

                    @if($user->avatar)

                        <img
                            src="{{ Storage::url($user->avatar) }}"
                            alt="{{ $user->name }}"
                            class="w-28 h-28 rounded-full border-4 border-white object-cover shadow"
                        >

                    @else

                        <img
                            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=EEF2FF&color=4338CA&size=256"
                            alt="{{ $user->name }}"
                            class="w-28 h-28 rounded-full border-4 border-white shadow"
                        >

                    @endif

                </div>

                {{-- Name --}}
                <div class="mt-5">

                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ $user->name }}
                    </h1>

                    @if($user->username)
                        <p class="mt-1 text-gray-500">
                            {{ '@' . $user->username }}
                        </p>
                    @endif

                    @if($user->job_title)
                        <p class="mt-3 font-medium text-indigo-600">
                            {{ $user->job_title }}
                        </p>
                    @endif

                    @if($user->department)
                        <p class="text-gray-500">
                            {{ $user->department }}
                        </p>
                    @endif

                </div>

                {{-- Bio --}}
                @if($user->bio)
                    <div class="mt-8">

                        <h2 class="text-lg font-semibold">
                            About
                        </h2>

                        <p class="mt-2 text-gray-600 leading-relaxed">
                            {{ $user->bio }}
                        </p>

                    </div>
                @endif

                {{-- Information --}}
                <div class="mt-10">

                    <h2 class="text-lg font-semibold mb-4">
                        Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>

                            <p class="text-sm text-gray-400">
                                Email
                            </p>

                            <p class="font-medium">
                                {{ $user->email }}
                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-gray-400">
                                Phone
                            </p>

                            <p class="font-medium">
                                {{ $user->phone ?: '-' }}
                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-gray-400">
                                Location
                            </p>

                            <p class="font-medium">
                                {{ $user->location ?: '-' }}
                            </p>

                        </div>

                        <div>

                            <p class="text-sm text-gray-400">
                                Joined
                            </p>

                            <p class="font-medium">
                                {{ $user->created_at->format('d M Y') }}
                            </p>

                        </div>

                    </div>

                </div>

                {{-- Workspace --}}
                @if($user->currentWorkspace)

                    <div class="mt-10">

                        <h2 class="text-lg font-semibold mb-4">
                            Workspace
                        </h2>

                        <div class="rounded-xl border border-gray-200 p-5">

                            <p class="font-semibold">
                                {{ $user->currentWorkspace->name }}
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                {{ ucfirst($user->currentWorkspace->visibility) }} Workspace
                            </p>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>