<div x-data="memberQuickCard()"
     @open-quick-card.window="openCard($event.detail)"
     @keydown.escape.window="closeCard()"
     @resize.window="closeCard()"
     class="relative z-[100]"
     style="display: none;"
     x-show="isOpen">

    <!-- Mobile / Tablet Backdrop -->
    <div x-show="isOpen"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/10 backdrop-blur-sm lg:hidden"
         @click="closeCard()"></div>

    <!-- The Floating Card -->
    <div x-show="isOpen"
         x-ref="card"
         @click.outside="closeCard()"
         :style="positionStyle"
         class="fixed w-full bg-white flex flex-col shadow-[0_4px_40px_-8px_rgba(0,0,0,0.15)] ring-1 ring-gray-900/5
                bottom-0 inset-x-0 rounded-t-3xl border-t border-gray-100
                md:bottom-auto md:inset-0 md:m-auto md:w-[420px] md:rounded-2xl md:border md:h-fit
                lg:m-0 lg:w-[380px] lg:rounded-2xl"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-12 lg:translate-y-2 lg:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 lg:scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 lg:scale-100"
         x-transition:leave-end="opacity-0 translate-y-12 lg:translate-y-2 lg:scale-95">

        <!-- Mobile Drag Handle -->
        <div class="flex justify-center pt-4 pb-2 md:hidden">
            <div class="w-12 h-1.5 bg-gray-200 rounded-full"></div>
        </div>

        <template x-if="user">
            <div class="flex flex-col h-full max-h-[85vh] md:max-h-none overflow-y-auto">
                <!-- Header -->
                <div class="p-5 flex items-start gap-4">
                    <div class="relative shrink-0">
                        <img :src="user.avatar_url" :alt="user.name" class="w-16 h-16 rounded-full object-cover shadow-sm ring-1 ring-gray-900/10">
                        <div class="absolute bottom-0.5 right-0.5 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="flex-1 min-w-0 pt-1">
                        <h3 class="text-lg font-bold text-gray-900 leading-snug truncate" x-text="user.name"></h3>
                        <p class="text-sm text-gray-500 truncate" x-text="'@' + user.username"></p>
                        <template x-if="user.job_title || user.department">
                            <p class="mt-2 text-sm text-gray-700 font-medium truncate">
                                <span x-text="user.job_title"></span>
                                <template x-if="user.job_title && user.department"><span class="text-gray-400 font-normal px-1">at</span></template>
                                <span x-text="user.department"></span>
                            </p>
                        </template>
                    </div>
                </div>

                <!-- Bio -->
                <template x-if="user.bio">
                    <div class="px-5 pb-4">
                        <p class="text-sm text-gray-600 leading-relaxed line-clamp-2" x-text="user.bio"></p>
                    </div>
                </template>

                <div class="h-px bg-gray-100 w-full"></div>

                <!-- Contact Info -->
                <div class="p-5 space-y-4">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <p class="text-sm text-gray-900 truncate" x-text="user.email"></p>
                    </div>
                    <template x-if="user.phone">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <p class="text-sm text-gray-900" x-text="user.phone"></p>
                        </div>
                    </template>
                    <template x-if="user.location">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p class="text-sm text-gray-900" x-text="user.location"></p>
                        </div>
                    </template>
                </div>

                <div class="h-px bg-gray-100 w-full"></div>

                <!-- Workspace Context -->
                <div class="p-5 bg-gray-50/50">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <!-- Real Logo (Primary) -->
                            <template x-if="workspace.logo">
                                <img :src="workspace.logo" :alt="workspace.name" class="w-6 h-6 rounded-[6px] object-cover shadow-sm ring-1 ring-gray-900/10">
                            </template>
                            
                            <!-- Initials (Fallback) -->
                            <template x-if="!workspace.logo">
                                <div class="w-6 h-6 bg-gray-900 rounded-[6px] flex items-center justify-center shadow-sm">
                                    <span class="text-white text-[10px] font-bold" x-text="workspace.name.substring(0,1).toUpperCase()"></span>
                                </div>
                            </template>

                            <span class="text-sm font-medium text-gray-900" x-text="workspace.name"></span>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium tracking-wide border" 
                              :class="workspace.role === 'admin' ? 'bg-purple-50 text-purple-700 border-purple-200/60' : 'bg-gray-100 text-gray-700 border-gray-200/60'"
                              x-text="workspace.role.toUpperCase()">
                        </span>
                    </div>
                    <p class="text-xs text-gray-500">Member since <span class="font-medium text-gray-900" x-text="joined"></span></p>
                </div>

                <!-- Actions -->
                <div class="p-4 grid grid-cols-2 gap-2 bg-white rounded-b-3xl md:rounded-b-2xl">
                    <a :href="user.profile_url" 
                       class="col-span-2 flex items-center justify-center w-full py-2 px-4 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors shadow-sm ring-1 ring-gray-900">
                        <span x-text="user.id === authId ? 'Edit Profile' : 'View Full Profile'"></span>
                    </a>
                    <button @click="copyEmail" type="button" class="flex items-center justify-center w-full py-2 px-4 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors shadow-sm">
                        Copy Email
                    </button>
                    <button disabled type="button" class="flex items-center justify-center w-full py-2 px-4 bg-gray-50 text-gray-400 text-sm font-medium rounded-lg border border-gray-100 cursor-not-allowed">
                        Message
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('memberQuickCard', () => ({
        isOpen: false,
        user: null,
        workspace: null,
        joined: null,
        positionStyle: {},
        authId: {{ auth()->id() ?? 'null' }},

        openCard(detail) {
            this.user = detail.user;
            this.workspace = detail.workspace;
            this.joined = detail.joined;

            // Clear inline styles before rendering to avoid flash of old position
            this.positionStyle = {};
            
            // Set isOpen to render the DOM elements
            this.isOpen = true;

            // Wait for DOM to update so we can measure the precise height
            this.$nextTick(() => {
                if (window.innerWidth >= 1024) {
                    const trigger = detail.trigger.getBoundingClientRect();
                    
                    // Dynamically read dimensions
                    const cardWidth = this.$refs.card.offsetWidth || 380;
                    const cardHeight = this.$refs.card.offsetHeight; 
                    const spacing = 16;

                    // Default to right of the trigger
                    let left = trigger.right + spacing;
                    let top = trigger.top - 20;

                    // If overflow right, open to the left instead
                    if (left + cardWidth > window.innerWidth) {
                        left = trigger.left - cardWidth - spacing;
                    }

                    // If overflow bottom, slide it up
                    if (top + cardHeight > window.innerHeight) {
                        top = window.innerHeight - cardHeight - spacing;
                    }

                    // Guarantee it doesn't go above viewport
                    if (top < 16) top = 16;

                    this.positionStyle = {
                        top: `${top}px`,
                        left: `${left}px`
                    };
                }
            });
        },

        closeCard() {
            this.isOpen = false;
            setTimeout(() => { if(!this.isOpen) this.positionStyle = {}; }, 200);
        },

        copyEmail() {
            if (this.user && this.user.email) {
                navigator.clipboard.writeText(this.user.email);
            }
        }
    }));
});
</script>