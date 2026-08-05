<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Workspace Members</h1>
                <p class="text-sm text-gray-500 mt-1">Manage everyone with access to this workspace.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        placeholder="Search members..." 
                        class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-shadow shadow-sm"
                    >
                </div>
                
                <a href="{{ route('workspace.invitation.show') }}"
                   class="inline-flex w-full sm:w-auto items-center justify-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 hover:shadow-md transition-all duration-200 whitespace-nowrap">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Invite Member
                </a>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Members</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ $members->total() }}</h3>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Admins</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ collect($members->items())->where('role', 'admin')->count() }}</h3>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Viewers</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ collect($members->items())->where('role', 'viewer')->count() }}</h3>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Pending Requests</p>
                <h3 class="text-2xl font-bold text-gray-900">0</h3>
            </div>
        </div>

        <!-- Members List -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm" x-data>
            <!-- Header Row (Desktop Only) -->
            <div class="hidden lg:grid grid-cols-12 gap-4 px-6 py-3 border-b border-gray-100 bg-gray-50/50 rounded-t-xl text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <div class="col-span-4">Member</div>
                <div class="col-span-3">Role / Department</div>
                <div class="col-span-2">Joined</div>
                <div class="col-span-3 text-right">Access</div>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($members as $member)
                    @php
                        // Universal Avatar Resolver Logic
                        $avatarUrl = $member->user->avatar 
                            ? (str_starts_with($member->user->avatar, 'http') ? $member->user->avatar : asset('storage/' . $member->user->avatar))
                            : 'https://ui-avatars.com/api/?name='.urlencode($member->user->name).'&background=F3F4F6&color=111827&bold=true';
                    @endphp

                    <!-- Member Row -->
                    <div class="group flex flex-col lg:grid lg:grid-cols-12 lg:items-center gap-4 px-6 py-4 hover:bg-gray-50/80 transition-all duration-200">
                        
                        <!-- Col 1: Identity & Trigger -->
                        <div class="col-span-4 flex items-center gap-3">
                            <!-- Quick Card Trigger Scope -->
                            <div class="flex items-center gap-3 cursor-pointer group/trigger"
                                 @click="$dispatch('open-quick-card', {
                                     user: {
                                         id: {{ $member->user->id }},
                                         name: @js($member->user->name),
                                         username: @js($member->user->username),
                                         email: @js($member->user->email),
                                         job_title: @js($member->user->job_title),
                                         department: @js($member->user->department),
                                         bio: @js($member->user->bio),
                                         phone: @js($member->user->phone),
                                         location: @js($member->user->location),
                                         avatar_url: @js($avatarUrl),
profile_url: @js(
    $member->user->username
        ? route('people.show', $member->user->username)
        : null
)
                                     },
                                     workspace: {
                                         name: @js($workspace->name ?? 'Workspace'),
                                         logo: @js(($workspace->logo ?? false) ? Storage::url($workspace->logo) : null),
                                         role: @js($member->role)
                                     },
                                     joined: @js($member->created_at->format('M j, Y')),
                                     trigger: $el
                                 })">
                                 
                                <div class="relative shrink-0">
                                    <img src="{{ $avatarUrl }}" 
                                         alt="{{ $member->user->name }}" 
                                         class="w-10 h-10 rounded-full object-cover ring-1 ring-gray-900/10 group-hover/trigger:ring-gray-300 transition-all shadow-sm">
                                    <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-bold text-gray-900 truncate group-hover/trigger:text-indigo-600 transition-colors">
                                        {{ $member->user->name }}
                                    </h4>
                                    <p class="text-xs text-gray-500 truncate">{{ $member->user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Col 2: Job Data -->
                        <div class="col-span-3 hidden lg:block min-w-0">
                            <p class="text-sm text-gray-900 font-medium truncate">{{ $member->user->job_title ?? 'Team Member' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $member->user->department ?? 'General' }}</p>
                        </div>

                        <!-- Col 3: Dates -->
                        <div class="col-span-2 hidden lg:block">
                            <p class="text-sm text-gray-900">{{ $member->created_at->format('M j, Y') }}</p>
                            <p class="text-xs text-gray-400">Active recently</p>
                        </div>

                        <!-- Col 4: Actions & Role -->
                        <div class="col-span-3 flex items-center justify-between lg:justify-end gap-3 mt-2 lg:mt-0 pt-2 lg:pt-0 border-t border-gray-100 lg:border-t-0">
                            
                            <!-- Badges for mobile view -->
                            <div class="lg:hidden">
                                @if($member->role === 'admin')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold tracking-wide bg-purple-50 text-purple-700 border border-purple-200">ADMIN</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold tracking-wide bg-gray-100 text-gray-700 border border-gray-200">{{ strtoupper($member->role) }}</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2" @click.stop>
                                @if(!$member->isOwner())
                                    <form action="{{ route('workspace.members.update-role', $member->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <select name="role" 
                                                onchange="this.form.submit()" 
                                                class="block w-[100px] py-1.5 pl-2.5 pr-7 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-md hover:border-gray-300 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors cursor-pointer shadow-sm">
                                            <option value="admin" @selected($member->role == 'admin')>Admin</option>
                                            <option value="member" @selected($member->role == 'member')>Member</option>
                                            <option value="viewer" @selected($member->role == 'viewer')>Viewer</option>
                                        </select>
                                    </form>

                                    <form action="{{ route('workspace.members.destroy', $member->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 bg-white border border-transparent rounded-md hover:bg-red-50 hover:border-red-100 transition-all focus:outline-none"
                                                title="Remove Member">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <div class="px-2 py-1 flex items-center gap-1.5 bg-gray-50 rounded-md border border-gray-100">
                                        <svg class="w-3.5 h-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-[11px] font-semibold text-gray-600">OWNER</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="p-12 text-center flex flex-col items-center justify-center min-h-[300px]">
                        <div class="w-12 h-12 bg-gray-50 border border-gray-100 rounded-xl flex items-center justify-center mb-4 shadow-sm">
                            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">No members found</h3>
                        <p class="text-sm text-gray-500 mb-5">Start collaborating by inviting your team.</p>
                        <a href="{{ route('workspace.invitation.show') }}" 
                           class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                            Invite Member
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($members->hasPages())
            <div class="mt-8">
                {{ $members->links() }}
            </div>
        @endif

    </div>

    <!-- Mount Single Global Quick Card Component -->
    <x-member-quick-card />
    
</x-app-layout>