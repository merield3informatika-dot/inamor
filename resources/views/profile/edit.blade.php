<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <style>
        .field-input {
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }
        .action-btn {
            transition: background-color 150ms ease, transform 150ms ease;
        }
        .action-btn:active { transform: scale(0.97); }
    </style>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            @if (session('status') === 'profile-updated')
                <div class="rounded-lg border border-gray-200 bg-gray-900 text-white px-4 py-2.5 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <svg class="h-4 w-4 text-emerald-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-xs font-medium">Profile updated successfully.</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-gray-400 hover:text-white">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            <!-- ================= SINGLE PROFILE FORM (avatar + all fields) ================= -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 sm:p-6">

                <div id="send-verification-wrapper">
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>
                </div>

                <form
                    method="post"
                    action="{{ route('profile.update') }}"
                    enctype="multipart/form-data"
                    class="space-y-5"
                    onsubmit="setButtonLoading(this)"
                >
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                        <!-- LEFT: Avatar -->
                        <div class="lg:col-span-1">
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Avatar</h3>

                            <div class="flex flex-col items-center text-center border border-gray-200 rounded-xl p-4">
                                <div class="relative w-20 h-20 rounded-full bg-gray-100 mb-3">
                                    <img
                                        id="avatarPreview"
                                        src="{{ ($user->avatar ?? null) ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&background=EEF2FF&color=4338CA&bold=true' }}"
                                        alt="Avatar Preview"
                                        class="w-full h-full rounded-full object-cover border border-gray-200"
                                    >
                                </div>

                                <p class="text-[11px] text-gray-400 mb-3">PNG, JPG, WEBP · Max 5MB</p>

                                <input
                                    type="file"
                                    id="avatarInput"
                                    name="avatar"
                                    accept="image/png, image/jpeg, image/webp"
                                    class="hidden"
                                    onchange="previewAvatarImage(this)"
                                >

                                <div class="flex items-center gap-2 w-full">
                                    <button type="button" onclick="document.getElementById('avatarInput').click()" class="action-btn w-full inline-flex items-center justify-center px-3 py-2 text-[11px] font-semibold text-white bg-gray-900 rounded-lg hover:bg-gray-800">
                                        Change
                                    </button>
                                    <button type="button" onclick="resetAvatarImage()" class="action-btn w-full inline-flex items-center justify-center px-3 py-2 text-[11px] font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                                        Reset
                                    </button>
                                </div>

                                @error('avatar')
                                    <p class="mt-2 text-[11px] font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- RIGHT: Fields -->
                        <div class="lg:col-span-2 space-y-3.5">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label for="name" class="block text-[11px] font-semibold text-gray-500 mb-1">Full Name</label>
                                    <input
                                        id="name"
                                        name="name"
                                        type="text"
                                        class="field-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                        value="{{ old('name', $user->name) }}"
                                        required
                                        autofocus
                                        autocomplete="name"
                                    >
                                    @error('name')
                                        <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="username" class="block text-[11px] font-semibold text-gray-500 mb-1">Username</label>
                                    <input
                                        id="username"
                                        name="username"
                                        type="text"
                                        class="field-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                        value="{{ old('username', $user->username ?? '') }}"
                                        placeholder="johndoe"
                                    >
                                    @error('username')
                                        <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-[11px] font-semibold text-gray-500 mb-1">Email Address</label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    class="field-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                    value="{{ old('email', $user->email) }}"
                                    required
                                    autocomplete="username"
                                >
                                @error('email')
                                    <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $message }}</p>
                                @enderror

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="mt-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg flex items-center justify-between gap-2">
                                        <p class="text-[11px] text-amber-800 font-medium">Email unverified.</p>
                                        <button form="send-verification" class="text-[11px] font-bold text-amber-900 underline shrink-0">
                                            Resend
                                        </button>
                                    </div>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-1 text-[11px] font-medium text-emerald-600">New verification link sent.</p>
                                    @endif
                                @endif
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label for="phone" class="block text-[11px] font-semibold text-gray-500 mb-1">Phone Number</label>
                                    <input
                                        id="phone"
                                        name="phone"
                                        type="text"
                                        class="field-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                        value="{{ old('phone', $user->phone ?? '') }}"
                                        placeholder="+1 (555) 019-2834"
                                    >
                                    @error('phone')
                                        <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="department" class="block text-[11px] font-semibold text-gray-500 mb-1">Department</label>
                                    <input
                                        id="department"
                                        name="department"
                                        type="text"
                                        class="field-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                        value="{{ old('department', $user->department ?? '') }}"
                                        placeholder="Engineering & AI"
                                    >
                                    @error('department')
                                        <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="job_title" class="block text-[11px] font-semibold text-gray-500 mb-1">Job Title</label>
                                    <input
                                        id="job_title"
                                        name="job_title"
                                        type="text"
                                        class="field-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                        value="{{ old('job_title', $user->job_title ?? '') }}"
                                        placeholder="Senior Software Engineer"
                                    >
                                    @error('job_title')
                                        <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="location" class="block text-[11px] font-semibold text-gray-500 mb-1">Location</label>
                                    <input
                                        id="location"
                                        name="location"
                                        type="text"
                                        class="field-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                        value="{{ old('location', $user->location ?? '') }}"
                                        placeholder="San Francisco, CA"
                                    >
                                    @error('location')
                                        <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label for="bio" class="block text-[11px] font-semibold text-gray-500">Bio</label>
                                    <span id="charCount" class="text-[10px] text-gray-400">0 / 500</span>
                                </div>
                                <textarea
                                    id="bio"
                                    name="bio"
                                    rows="3"
                                    maxlength="500"
                                    oninput="updateCharCount(this)"
                                    class="field-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                                    placeholder="A short bio..."
                                >{{ old('bio', $user->bio ?? '') }}</textarea>
                                @error('bio')
                                    <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="action-btn inline-flex items-center justify-center px-5 py-2.5 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 disabled:opacity-50">
                            <span class="btn-text">Save Changes</span>
                            <span class="btn-spinner hidden ml-2 animate-spin">⏳</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- ================= PASSWORD ================= -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 sm:p-6">
                <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Password</h2>

                <form method="post" action="{{ route('password.update') }}" class="space-y-3.5" onsubmit="setButtonLoading(this)">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label for="update_password_current_password" class="block text-[11px] font-semibold text-gray-500 mb-1">Current</label>
                            <input id="update_password_current_password" name="current_password" type="password" class="field-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" autocomplete="current-password">
                            @if($errors->updatePassword->has('current_password'))
                                <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $errors->updatePassword->first('current_password') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="update_password_password" class="block text-[11px] font-semibold text-gray-500 mb-1">New Password</label>
                            <input id="update_password_password" name="password" type="password" class="field-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" autocomplete="new-password">
                            @if($errors->updatePassword->has('password'))
                                <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $errors->updatePassword->first('password') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="update_password_password_confirmation" class="block text-[11px] font-semibold text-gray-500 mb-1">Confirm</label>
                            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="field-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" autocomplete="new-password">
                            @if($errors->updatePassword->has('password_confirmation'))
                                <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                        @if (session('status') === 'password-updated')
                            <p class="text-[11px] font-semibold text-emerald-600">Saved.</p>
                        @endif
                        <button type="submit" class="action-btn inline-flex items-center justify-center px-5 py-2.5 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 disabled:opacity-50">
                            <span class="btn-text">Update Password</span>
                            <span class="btn-spinner hidden ml-2 animate-spin">⏳</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- ================= DANGER ZONE ================= -->
            <div class="bg-white rounded-xl border border-rose-200 shadow-sm p-4 sm:p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xs font-bold text-rose-600 uppercase tracking-wider">Danger Zone</h2>
                        <p class="text-[11px] text-gray-500 mt-0.5">Permanently delete your account and all data.</p>
                    </div>
                    <button type="button" onclick="openDeleteModal()" class="action-btn shrink-0 inline-flex items-center justify-center px-3.5 py-2 text-[11px] font-semibold text-white bg-rose-600 rounded-lg hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500">
                        Delete Account
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="confirmUserDeletionModal" class="fixed inset-0 z-[100] hidden items-center justify-center">
        <div class="absolute inset-0 bg-gray-900/50" onclick="closeDeleteModal()"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-sm mx-4 border border-gray-200 p-5 z-10">
            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf
                @method('delete')

                <h2 class="text-sm font-bold text-gray-900">Delete your account?</h2>
                <p class="text-xs text-gray-500 leading-relaxed">This action is permanent. Enter your password to confirm.</p>

                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" class="field-input w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-1 focus:ring-rose-500 focus:border-rose-500" placeholder="Password">
                    @if($errors->userDeletion->has('password'))
                        <p class="mt-1 text-[11px] font-medium text-rose-600">{{ $errors->userDeletion->first('password') }}</p>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-2 pt-1">
                    <button type="button" onclick="closeDeleteModal()" class="action-btn px-3.5 py-2 text-[11px] font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="action-btn px-3.5 py-2 text-[11px] font-semibold text-white bg-rose-600 rounded-lg hover:bg-rose-700">Delete Account</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bioEl = document.getElementById('bio');
            if (bioEl) {
                updateCharCount(bioEl);
            }
        });

        function updateCharCount(textarea) {
            const charCount = document.getElementById('charCount');
            if (charCount) {
                charCount.innerText = `${textarea.value.length} / 500`;
            }
        }

        function previewAvatarImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function resetAvatarImage() {
            document.getElementById('avatarInput').value = '';
            const defaultAvatar = "{{ ($user->avatar ?? null) ? Storage::url($user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&background=EEF2FF&color=4338CA&bold=true' }}";
            document.getElementById('avatarPreview').src = defaultAvatar;
        }

        function setButtonLoading(form) {
            const btn = form.querySelector('.action-btn');
            if (btn) {
                btn.disabled = true;
                const textEl = btn.querySelector('.btn-text');
                const spinnerEl = btn.querySelector('.btn-spinner');
                if (textEl) textEl.style.opacity = '0.7';
                if (spinnerEl) spinnerEl.classList.remove('hidden');
            }
        }

        function openDeleteModal() {
            const modal = document.getElementById('confirmUserDeletionModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('confirmUserDeletionModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    </script>
</x-app-layout>