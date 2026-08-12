@extends('layouts.mobile')

@section('title', 'Edit Profil')

@section('content')

<div class="mx-auto w-full max-w-lg">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <header class="flex items-center gap-3 border-b border-gray-100 bg-white px-4 py-4">

        <a
            href="{{ route('mobile.account') }}"
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-gray-200 text-gray-500 active:bg-gray-50"
            aria-label="Kembali"
        >
            <svg
                class="h-4 w-4"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 19l-7-7 7-7"
                />
            </svg>
        </a>


        <div class="min-w-0">

            <h1 class="text-[16px] font-bold text-gray-900">
                Edit Profil
            </h1>

            <p class="text-[10px] text-gray-400">
                Perbarui informasi profil Anda
            </p>

        </div>

    </header>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="mx-4 mt-3 rounded-[12px] border border-red-100 bg-red-50 px-3 py-3">

            <p class="mb-1 text-[10px] font-semibold text-red-600">
                Ada yang perlu diperbaiki
            </p>

            <ul class="space-y-0.5">

                @foreach($errors->all() as $error)

                    <li class="text-[9px] leading-relaxed text-red-500">
                        • {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         FORM
    ========================================================== --}}

    <form
        method="POST"
        action="{{ route('profile.update') }}"
        enctype="multipart/form-data"
        class="mt-3"
    >

        @csrf

        @method('PATCH')


        {{-- =====================================================
             AVATAR
        ====================================================== --}}

        <section class="bg-white px-4 py-5">

            <div class="flex items-center gap-4">

                <div class="h-16 w-16 shrink-0 overflow-hidden rounded-full border border-gray-100 bg-gray-100">

                    @if($user->avatar)

                        <img
                            src="{{ Storage::url($user->avatar) }}"
                            alt="{{ $user->name }}"
                            class="h-full w-full object-cover"
                        >

                    @else

                        <div class="flex h-full w-full items-center justify-center bg-blue-50 text-blue-600">

                            <span class="text-xl font-bold">
                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                            </span>

                        </div>

                    @endif

                </div>


                <div class="min-w-0 flex-1">

                    <label
                        for="avatar"
                        class="inline-flex cursor-pointer items-center rounded-[10px] border border-gray-200 bg-white px-3 py-2 text-[10px] font-semibold text-gray-600 active:bg-gray-50"
                    >
                        Ganti Foto
                    </label>

                    <input
                        id="avatar"
                        type="file"
                        name="avatar"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        class="hidden"
                    >

                    <p class="mt-1.5 text-[8px] leading-relaxed text-gray-400">
                        JPG, PNG, atau WEBP. Maksimal 5 MB.
                    </p>

                </div>

            </div>

        </section>


        {{-- =====================================================
             BASIC INFORMATION
        ====================================================== --}}

        <section class="mt-3 bg-white px-4 py-5">

            <div class="mb-5">

                <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
                    Informasi dasar
                </p>

            </div>


            {{-- Name --}}

            <div class="mb-5">

                <label
                    for="name"
                    class="mb-2 block text-[11px] font-semibold text-gray-700"
                >
                    Nama
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    maxlength="255"
                    autocomplete="name"
                    class="w-full rounded-[12px] border border-gray-200 bg-gray-50 px-3.5 py-3 text-[12px] text-gray-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >

                @error('name')

                    <p class="mt-1.5 text-[9px] text-red-500">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Username --}}

            <div class="mb-5">

                <label
                    for="username"
                    class="mb-2 block text-[11px] font-semibold text-gray-700"
                >
                    Username
                </label>

                <div class="flex items-center rounded-[12px] border border-gray-200 bg-gray-50 focus-within:border-blue-300 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-100">

                    <span class="pl-3.5 text-[12px] text-gray-400">
                        @
                    </span>

                    <input
                        id="username"
                        type="text"
                        name="username"
                        value="{{ old('username', $user->username) }}"
                        maxlength="30"
                        autocomplete="username"
                        class="min-w-0 flex-1 bg-transparent px-1.5 py-3 text-[12px] text-gray-900 outline-none"
                    >

                </div>

                <p class="mt-1.5 text-[8px] text-gray-400">
                    3–30 karakter, hanya huruf, angka, dash, dan underscore.
                </p>

                @error('username')

                    <p class="mt-1.5 text-[9px] text-red-500">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Email --}}

            <div>

                <label
                    for="email"
                    class="mb-2 block text-[11px] font-semibold text-gray-700"
                >
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    maxlength="255"
                    autocomplete="email"
                    class="w-full rounded-[12px] border border-gray-200 bg-gray-50 px-3.5 py-3 text-[12px] text-gray-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >

                <p class="mt-1.5 text-[8px] text-gray-400">
                    Mengubah email akan meminta verifikasi email kembali.
                </p>

                @error('email')

                    <p class="mt-1.5 text-[9px] text-red-500">
                        {{ $message }}
                    </p>

                @enderror

            </div>

        </section>


        {{-- =====================================================
             ABOUT
        ====================================================== --}}

        <section class="mt-3 bg-white px-4 py-5">

            <div class="mb-5">

                <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
                    Tentang Anda
                </p>

            </div>


            {{-- Bio --}}

            <div>

                <label
                    for="bio"
                    class="mb-2 block text-[11px] font-semibold text-gray-700"
                >
                    Bio
                </label>

                <textarea
                    id="bio"
                    name="bio"
                    rows="4"
                    maxlength="500"
                    placeholder="Ceritakan sedikit tentang diri Anda..."
                    class="w-full resize-none rounded-[12px] border border-gray-200 bg-gray-50 px-3.5 py-3 text-[12px] leading-relaxed text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-blue-300 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >{{ old('bio', $user->bio) }}</textarea>

                <p class="mt-1.5 text-right text-[8px] text-gray-400">
                    Maksimal 500 karakter
                </p>

                @error('bio')

                    <p class="mt-1.5 text-[9px] text-red-500">
                        {{ $message }}
                    </p>

                @enderror

            </div>

        </section>


        {{-- =====================================================
             WORK INFORMATION
        ====================================================== --}}

        <section class="mt-3 bg-white px-4 py-5">

            <div class="mb-5">

                <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
                    Informasi pekerjaan
                </p>

            </div>


            {{-- Job Title --}}

            <div class="mb-5">

                <label
                    for="job_title"
                    class="mb-2 block text-[11px] font-semibold text-gray-700"
                >
                    Jabatan
                </label>

                <input
                    id="job_title"
                    type="text"
                    name="job_title"
                    value="{{ old('job_title', $user->job_title) }}"
                    maxlength="100"
                    autocomplete="organization-title"
                    placeholder="Contoh: Software Engineer"
                    class="w-full rounded-[12px] border border-gray-200 bg-gray-50 px-3.5 py-3 text-[12px] text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-blue-300 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >

                @error('job_title')

                    <p class="mt-1.5 text-[9px] text-red-500">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Department --}}

            <div class="mb-5">

                <label
                    for="department"
                    class="mb-2 block text-[11px] font-semibold text-gray-700"
                >
                    Department
                </label>

                <input
                    id="department"
                    type="text"
                    name="department"
                    value="{{ old('department', $user->department) }}"
                    maxlength="100"
                    placeholder="Contoh: Engineering"
                    class="w-full rounded-[12px] border border-gray-200 bg-gray-50 px-3.5 py-3 text-[12px] text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-blue-300 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >

                @error('department')

                    <p class="mt-1.5 text-[9px] text-red-500">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Phone --}}

            <div class="mb-5">

                <label
                    for="phone"
                    class="mb-2 block text-[11px] font-semibold text-gray-700"
                >
                    Nomor Telepon
                </label>

                <input
                    id="phone"
                    type="tel"
                    name="phone"
                    value="{{ old('phone', $user->phone) }}"
                    maxlength="30"
                    autocomplete="tel"
                    placeholder="Contoh: 08123456789"
                    class="w-full rounded-[12px] border border-gray-200 bg-gray-50 px-3.5 py-3 text-[12px] text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-blue-300 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >

                @error('phone')

                    <p class="mt-1.5 text-[9px] text-red-500">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- Location --}}

            <div>

                <label
                    for="location"
                    class="mb-2 block text-[11px] font-semibold text-gray-700"
                >
                    Lokasi
                </label>

                <input
                    id="location"
                    type="text"
                    name="location"
                    value="{{ old('location', $user->location) }}"
                    maxlength="150"
                    autocomplete="address-level2"
                    placeholder="Contoh: Pontianak"
                    class="w-full rounded-[12px] border border-gray-200 bg-gray-50 px-3.5 py-3 text-[12px] text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-blue-300 focus:bg-white focus:ring-2 focus:ring-blue-100"
                >

                @error('location')

                    <p class="mt-1.5 text-[9px] text-red-500">
                        {{ $message }}
                    </p>

                @enderror

            </div>

        </section>


        {{-- =====================================================
             ACTIONS
        ====================================================== --}}

        <section class="mt-3 bg-white px-4 py-4">

            <button
                type="submit"
                class="w-full rounded-[12px] bg-blue-600 px-4 py-3 text-[11px] font-semibold text-white active:bg-blue-700"
            >
                Simpan Perubahan
            </button>


            <a
                href="{{ route('mobile.account') }}"
                class="mt-2 block w-full rounded-[12px] border border-gray-200 px-4 py-3 text-center text-[11px] font-semibold text-gray-500 active:bg-gray-50"
            >
                Batal
            </a>

        </section>

    </form>


    {{-- =========================================================
         FOOTER
    ========================================================== --}}

    <div class="px-4 py-6 text-center">

        <p class="text-[9px] text-gray-300">
            Inamor
        </p>

    </div>

</div>

@endsection