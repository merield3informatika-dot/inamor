<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Workspace Dashboard
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Welcome back, {{ auth()->user()->name }} 👋
                </p>
            </div>
        </div>
    </x-slot>

    @php
        $workspace = auth()->user()->workspaceMemberships->first()?->workspace;
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            {{-- Workspace --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    Active Workspace
                </h3>

                <div class="mt-4 flex items-center justify-between">
                    <div>
                        <p class="text-xl font-bold text-indigo-600">
                            {{ $workspace?->name ?? 'No Workspace' }}
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Everything you upload and manage belongs to this workspace.
                        </p>
                    </div>

                    <div>
                        <span
                            class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">
                            Active
                        </span>
                    </div>
                </div>
            </div>

            {{-- Overview --}}
            <div class="grid gap-6 md:grid-cols-3">

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="text-sm text-gray-500">
                        Documents
                    </div>

                    <div class="mt-2 text-3xl font-bold text-gray-900">
                        0
                    </div>

                    <p class="mt-2 text-sm text-gray-500">
                        Uploaded documents.
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="text-sm text-gray-500">
                        Members
                    </div>

                    <div class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $workspace?->members()->count() ?? 0 }}
                    </div>

                    <p class="mt-2 text-sm text-gray-500">
                        Workspace members.
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="text-sm text-gray-500">
                        AI Assistant
                    </div>

                    <div class="mt-2 text-xl font-semibold text-orange-500">
                        Coming Soon
                    </div>

                    <p class="mt-2 text-sm text-gray-500">
                        AI-powered document assistant.
                    </p>
                </div>

            </div>

            {{-- Quick Actions --}}
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    Quick Actions
                </h3>

                <div class="mt-5 flex flex-wrap gap-3">

                    <a href="#"
                        class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 transition">
                        Upload Document
                    </a>

                    <button
                        disabled
                        class="inline-flex items-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-500 cursor-not-allowed">
                        AI Chat
                    </button>

                    <button
                        disabled
                        class="inline-flex items-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-500 cursor-not-allowed">
                        Manage Members
                    </button>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>