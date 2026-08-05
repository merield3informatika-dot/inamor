<x-app-layout>

<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Workspace Settings
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Manage your workspace preferences and configurations.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm max-w-3xl overflow-hidden">
        
        <!-- Navigation Tabs -->
        <div class="border-b border-gray-100 px-6 pt-4 flex gap-6">
            <a href="{{ route('workspace.settings.edit') }}" 
               class="pb-3 border-b-2 text-sm font-medium transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                General
            </a>
            <a href="{{ route('workspace.settings.danger') }}" 
               class="pb-3 border-b-2 text-sm font-medium transition-colors border-red-500 text-red-600">
                Danger Zone
            </a>
        </div>

        <div class="border-b border-gray-100 px-6 py-4 bg-red-50/30">
            <h2 class="font-semibold text-red-700">
                Danger Zone
            </h2>
        </div>

        <div class="p-6 space-y-6">
            
            <!-- Workspace Details Summary Box -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex items-center justify-between">
                <div>
                    <span class="text-xs uppercase tracking-wider font-semibold text-gray-400">Selected Workspace</span>
                    <h3 class="text-base font-bold text-gray-900 mt-0.5">{{ $workspace->name }}</h3>
                </div>
                <div class="flex items-center gap-4 text-right">
                    <div>
                        <span class="text-[10px] uppercase tracking-wider font-semibold text-gray-400 block">Visibility</span>
                        <span class="text-xs font-semibold text-gray-700 capitalize">{{ $workspace->visibility }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase tracking-wider font-semibold text-gray-400 block">Owner</span>
                        <span class="text-xs font-semibold text-gray-700">{{ $workspace->owner->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                <div class="flex items-start gap-4">
                    <div class="shrink-0">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-red-800">
                            Warning: Permanent Deletion
                        </h3>
                        <p class="mt-2 text-sm text-red-700">
                            Deleting this workspace will remove all its data. <strong>This action cannot be undone.</strong>
                        </p>
                        
                        <div class="mt-4">
                            <p class="text-sm font-semibold text-red-800 mb-2">The following associated data will be removed:</p>
                            <ul class="list-disc pl-5 text-sm text-red-700 space-y-1">
                                <li>Documents</li>
                                <li>Manual Knowledge</li>
                                <li>Knowledge Feedback</li>
                                <li>Calendar Events</li>
                                <li>Workspace Members</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('workspace.destroy') }}" method="POST" class="space-y-6 pt-2">
                @csrf
                @method('DELETE')

                <div class="max-w-md">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        To verify, type your account password
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500 placeholder-gray-400"
                        placeholder="Enter your personal password"
                    >
                    @error('password')
                        <p class="mt-2 text-sm font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <button 
                        type="submit" 
                        class="px-5 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                    >
                        Delete Workspace
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

</x-app-layout>