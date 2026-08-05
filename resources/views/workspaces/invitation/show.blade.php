<x-app-layout>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Invitation Center</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and share your workspace invitation link.</p>
        </div>
        
        <!-- Primary Action / Regenerate Link in Header -->
        <form action="{{ route('workspace.invitation.regenerate') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-red-600 bg-white border border-gray-200 rounded-lg hover:bg-red-50 hover:border-red-200 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Regenerate Link
            </button>
        </form>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Primary Content (Span 2) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Workspace Card -->
            <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex items-start gap-5">
                <!-- Logo -->
                <div class="w-16 h-16 rounded-xl bg-gray-900 text-white flex items-center justify-center text-xl font-bold shrink-0 shadow-sm overflow-hidden">
                    @if($workspace->logo ?? false)
                        <img src="{{ Storage::url($workspace->logo) }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($workspace->name, 0, 1)) }}
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="text-lg font-bold text-gray-900 truncate">{{ $workspace->name }}</h2>
                    <div class="flex flex-wrap items-center gap-2 mt-1.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-gray-100 text-gray-600 uppercase tracking-wide border border-gray-200">
                            {{ $workspace->visibility }} Workspace
                        </span>
                        @if(isset($workspace->members_count))
                            <span class="text-[13px] text-gray-500 font-medium flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ $workspace->members_count }} Members
                            </span>
                        @endif
                    </div>
                    @if(isset($workspace->description) && !empty($workspace->description))
                        <p class="text-sm text-gray-500 mt-3 line-clamp-2">{{ $workspace->description }}</p>
                    @endif
                </div>
            </div>

            <!-- Invitation Link Card -->
            <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300">
                <h3 class="text-base font-semibold text-gray-900">Invitation Link</h3>
                <p class="text-sm text-gray-500 mt-1">Anyone with this link can request access to this workspace.</p>
                
                <div class="mt-5 flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <input 
                            id="invite-link" 
                            type="text" 
                            readonly 
                            value="{{ route('workspace.invitation.accept', $invitation->token) }}" 
                            class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-gray-900 focus:bg-white transition-all shadow-sm"
                            onclick="this.select()"
                        >
                    </div>
                    
                    <div class="flex gap-2 shrink-0">
                        <button 
                            type="button"
                            onclick="copyInvitation()" 
                            class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-1"
                        >
                            Copy Link
                        </button>
                        <a 
                            href="{{ route('workspace.invitation.accept', $invitation->token) }}" 
                            target="_blank" 
                            class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-1"
                        >
                            Open Link
                        </a>
                    </div>
                </div>
            </div>

            <!-- QR Code Card -->
            <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col sm:flex-row items-center sm:items-start gap-6">
                <!-- QR Container -->
                <div class="shrink-0 bg-white p-2 border border-gray-100 rounded-xl shadow-sm">
                    <div id="qrcode-container" class="w-[128px] h-[128px]"></div>
                </div>
                
                <div class="flex-1 text-center sm:text-left">
                    <h3 class="text-base font-semibold text-gray-900">QR Invitation</h3>
                    <p class="text-sm text-gray-500 mt-1 max-w-sm">Users can scan this QR code with their mobile device to instantly open the join request page.</p>
                    
                    <div class="mt-4 flex flex-wrap items-center justify-center sm:justify-start gap-3">
                        <button 
                            onclick="downloadQR()" 
                            class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-200"
                        >
                            <svg class="w-4 h-4 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download QR
                        </button>
                        <button 
                            onclick="copyInvitation()" 
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors focus:outline-none"
                        >
                            Copy Link
                        </button>
                    </div>
                </div>
            </div>

            <!-- Security Section -->
            <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Security Overview</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="text-[11px] text-gray-500 font-semibold uppercase tracking-wider">Current Status</div>
                        <div class="mt-1.5 text-sm font-semibold text-green-600 flex items-center gap-1.5">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                            </span>
                            Active
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="text-[11px] text-gray-500 font-semibold uppercase tracking-wider">Expiration</div>
                        <div class="mt-1.5 text-sm font-medium text-gray-900">No Expiration</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="text-[11px] text-gray-500 font-semibold uppercase tracking-wider">Token Length</div>
                        <div class="mt-1.5 text-sm font-medium text-gray-900">Generated Automatically</div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white border border-red-200 rounded-xl p-6 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-red-500"></div>
                <h3 class="text-base font-semibold text-gray-900">Regenerate Link</h3>
                <p class="text-sm text-gray-500 mt-1 mb-5">Generate a new invitation token. Any previous links will immediately become invalid and users will no longer be able to use them to request access.</p>
                
                <form action="{{ route('workspace.invitation.regenerate') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition-all shadow-sm">
                        Regenerate Invitation Token
                    </button>
                </form>
            </div>

        </div>

        <!-- Right Column: Secondary Content (Span 1) -->
        <div class="space-y-6">
            
            <!-- Statistics -->
            <div class="grid grid-cols-2 gap-4">
                <!-- Usage Count -->
                <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm text-center hover:shadow-md transition-all duration-300">
                    <div class="text-2xl font-bold text-gray-900">{{ $invitation->usage_count }}</div>
                    <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mt-1">Invitation Used</div>
                </div>
                
                <!-- Visibility -->
                <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm text-center hover:shadow-md transition-all duration-300">
                    <div class="text-lg font-bold text-gray-900 mt-1 capitalize">{{ $workspace->visibility }}</div>
                    <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mt-1.5">Visibility</div>
                </div>

                <!-- Created At -->
                <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm text-center col-span-2 hover:shadow-md transition-all duration-300">
                    <div class="text-sm font-bold text-gray-900">{{ $invitation->created_at->format('M d, Y') }}</div>
                    <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mt-1">Created</div>
                </div>

                <!-- Last Used -->
                <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm text-center col-span-2 hover:shadow-md transition-all duration-300">
                    <div class="text-sm font-bold text-gray-900">
                        {{ $invitation->last_used_at ? $invitation->last_used_at->format('M d, Y H:i') : 'Never Used' }}
                    </div>
                    <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mt-1">Last Used</div>
                </div>
            </div>

            <!-- How It Works (Timeline) -->
            <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300">
                <h3 class="text-base font-semibold text-gray-900 mb-6">How it works</h3>
                
                <div class="relative border-l-2 border-gray-100 ml-3 space-y-6">
                    <!-- Step 1 -->
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 bg-gray-200 rounded-full border-4 border-white"></div>
                        <p class="text-sm font-medium text-gray-900">Share Invitation Link</p>
                        <p class="text-xs text-gray-500 mt-0.5">Send the link or QR to your team.</p>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 bg-gray-200 rounded-full border-4 border-white"></div>
                        <p class="text-sm font-medium text-gray-900">User Opens Link</p>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 bg-gray-200 rounded-full border-4 border-white"></div>
                        <p class="text-sm font-medium text-gray-900">User Submits Join Request</p>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 bg-gray-200 rounded-full border-4 border-white"></div>
                        <p class="text-sm font-medium text-gray-900">Administrator Reviews</p>
                        <p class="text-xs text-gray-500 mt-0.5">Approve or reject the request.</p>
                    </div>

                    <!-- Step 5 -->
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 bg-gray-900 rounded-full border-4 border-white ring-2 ring-gray-100"></div>
                        <p class="text-sm font-bold text-gray-900">Become Workspace Member</p>
                    </div>
                </div>
            </div>

            <!-- Warning Card -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 shadow-sm flex items-start gap-3">
                <svg class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="text-sm text-yellow-800">
                    <span class="font-semibold block mb-1">Important Note</span>
                    Changing the invitation link will invalidate the previous link immediately.
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Custom Toast Notification -->
<div id="toast" class="fixed bottom-6 right-6 transform translate-y-10 opacity-0 transition-all duration-300 z-50 pointer-events-none">
    <div class="bg-gray-900 text-white px-4 py-3 rounded-lg shadow-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span class="text-sm font-medium">Invitation copied.</span>
    </div>
</div>

<!-- QR Code Generator CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const inviteUrl = "{{ route('workspace.invitation.accept', $invitation->token) }}";

    // Initialize QR Code on DOM Load
    window.addEventListener('DOMContentLoaded', () => {
        new QRCode(document.getElementById("qrcode-container"), {
            text: inviteUrl,
            width: 128,
            height: 128,
            colorDark : "#111827", // Tailwind gray-900
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    });

    // Copy to Clipboard logic using Clipboard API with fallback
    function copyInvitation() {
        if (navigator.clipboard && window.isSecureContext) {
            // Navigator clipboard api method'
            navigator.clipboard.writeText(inviteUrl).then(() => {
                showToast();
            });
        } else {
            // Textarea fallback
            let textArea = document.createElement("textarea");
            textArea.value = inviteUrl;
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                showToast();
            } catch (err) {
                console.error('Fallback: Oops, unable to copy', err);
            }
            textArea.remove();
        }
    }

    // Download generated QR canvas as image
    function downloadQR() {
        // qrcode.js generates a canvas element
        const canvas = document.querySelector('#qrcode-container canvas');
        if (canvas) {
            const link = document.createElement('a');
            link.download = 'workspace-qr-invite.png';
            link.href = canvas.toDataURL("image/png");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    // Custom Toast toggler
    function showToast() {
        const toast = document.getElementById('toast');
        // Show
        toast.classList.remove('translate-y-10', 'opacity-0');
        // Hide after 3s
        setTimeout(() => {
            toast.classList.add('translate-y-10', 'opacity-0');
        }, 3000);
    }
</script>

</x-app-layout>