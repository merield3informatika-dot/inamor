<x-app-layout>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div class="max-w-2xl">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('workspace.join-request.index') }}" class="inline-flex items-center text-xs font-semibold text-gray-500 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-2.5 py-1 rounded-lg transition-all">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Join Requests
                </a>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Archived Join Requests</h1>
            <p class="text-sm sm:text-base text-gray-500 mt-1 leading-relaxed">
                Archived join requests can be restored at any time.
            </p>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('status'))
        <div class="mb-8 rounded-xl border border-gray-900/10 bg-gray-900 text-white p-4 flex items-center justify-between shadow-xl">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                    <svg class="h-4 w-4 text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="text-sm font-medium tracking-tight">
                    {{ session('status') }}
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-gray-400 hover:text-white p-1 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <!-- Enterprise Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200/70 shadow-[0_2px_8px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/70">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Archived At</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($joinRequests as $request)
                        <tr class="hover:bg-gray-50/60 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3.5">
                                    <img 
                                        src="https://ui-avatars.com/api/?name={{ urlencode($request->full_name) }}&background=F3F4F6&color=111827&bold=true" 
                                        alt="{{ $request->full_name }}" 
                                        class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-xs"
                                    >
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 group-hover:text-black">
                                            {{ $request->full_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $request->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($request->status == 'approved')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        Approved
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200/60 tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                        Rejected
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs font-medium text-gray-900">
                                    {{ optional($request->deleted_at)->diffForHumans() }}
                                </div>
                                <div class="text-[11px] text-gray-400 mt-0.5">
                                    {{ optional($request->deleted_at)->format('d M Y H:i') }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('workspace.join-request.restore', $request) }}" method="POST" class="m-0">
                                        @csrf
                                        <button class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold text-white bg-gray-900 rounded-xl hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-1 transition-all shadow-xs active:scale-95">
                                            Restore
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4 shadow-xs">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-gray-900 mb-1 tracking-tight">No archived join requests</h3>
                                    <p class="text-xs text-gray-500 text-center leading-relaxed">There are currently no archived records stored in the archive vault.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $joinRequests->links() }}
    </div>

</div>

</x-app-layout>