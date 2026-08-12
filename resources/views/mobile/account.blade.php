@extends('layouts.mobile')

@section('title', 'Account')

@section('content')

<div class="mx-auto w-full max-w-lg">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <header class="border-b border-gray-100 bg-white px-4 py-4">

        <h1 class="text-[17px] font-bold text-gray-900">
            Account
        </h1>

        <p class="mt-0.5 text-[10px] text-gray-400">
            Profil dan informasi akun
        </p>

    </header>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('status') === 'profile-updated')

        <div class="mx-4 mt-3 rounded-[12px] border border-emerald-100 bg-emerald-50 px-3 py-2.5">

            <p class="text-[10px] font-semibold text-emerald-600">
                Profil berhasil diperbarui.
            </p>

        </div>

    @endif


    {{-- =========================================================
         PROFILE CARD
    ========================================================== --}}

    <section class="bg-white px-4 py-5">

        <div class="flex items-center gap-4">

            {{-- Avatar --}}

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


            {{-- Main information --}}

            <div class="min-w-0 flex-1">

                <h2 class="truncate text-[16px] font-bold text-gray-900">
                    {{ $user->name }}
                </h2>

                @if($user->username)

                    <p class="mt-0.5 truncate text-[11px] text-gray-400">
                        @{{ $user->username }}
                    </p>

                @endif

                <p class="mt-1 truncate text-[10px] text-gray-400">
                    {{ $user->email }}
                </p>

            </div>

        </div>


        {{-- Bio --}}

        @if($user->bio)

            <p class="mt-4 text-[11px] leading-relaxed text-gray-500">
                {{ $user->bio }}
            </p>

        @endif


        {{-- Edit button --}}

        <a
            href="{{ route('mobile.account.edit') }}"
            class="mt-4 flex w-full items-center justify-center rounded-[12px] bg-blue-600 px-4 py-3 text-[11px] font-semibold text-white active:bg-blue-700"
        >
            Edit Profil
        </a>

    </section>


    {{-- =========================================================
         PERSONAL INFORMATION
    ========================================================== --}}

    <section class="mt-3 bg-white">

        <div class="border-b border-gray-100 px-4 py-3">

            <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
                Informasi pribadi
            </p>

        </div>


        {{-- Phone --}}

        <div class="flex items-center justify-between border-b border-gray-50 px-4 py-4">

            <div class="min-w-0">

                <p class="text-[10px] text-gray-400">
                    Nomor Telepon
                </p>

                <p class="mt-1 truncate text-[12px] font-semibold text-gray-800">
                    {{ $user->phone ?: 'Belum diatur' }}
                </p>

            </div>

        </div>


        {{-- Job Title --}}

        <div class="flex items-center justify-between border-b border-gray-50 px-4 py-4">

            <div class="min-w-0">

                <p class="text-[10px] text-gray-400">
                    Jabatan
                </p>

                <p class="mt-1 truncate text-[12px] font-semibold text-gray-800">
                    {{ $user->job_title ?: 'Belum diatur' }}
                </p>

            </div>

        </div>


        {{-- Department --}}

        <div class="flex items-center justify-between border-b border-gray-50 px-4 py-4">

            <div class="min-w-0">

                <p class="text-[10px] text-gray-400">
                    Department
                </p>

                <p class="mt-1 truncate text-[12px] font-semibold text-gray-800">
                    {{ $user->department ?: 'Belum diatur' }}
                </p>

            </div>

        </div>


        {{-- Location --}}

        <div class="flex items-center justify-between px-4 py-4">

            <div class="min-w-0">

                <p class="text-[10px] text-gray-400">
                    Lokasi
                </p>

                <p class="mt-1 truncate text-[12px] font-semibold text-gray-800">
                    {{ $user->location ?: 'Belum diatur' }}
                </p>

            </div>

        </div>

    </section>


    {{-- =========================================================
         ACCOUNT INFORMATION
    ========================================================== --}}

    <section class="mt-3 bg-white">

        <div class="border-b border-gray-100 px-4 py-3">

            <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
                Akun
            </p>

        </div>


        {{-- Email --}}

        <div class="flex items-center justify-between border-b border-gray-50 px-4 py-4">

            <div class="min-w-0">

                <p class="text-[10px] text-gray-400">
                    Email
                </p>

                <p class="mt-1 truncate text-[12px] font-semibold text-gray-800">
                    {{ $user->email }}
                </p>

            </div>


            @if($user->hasVerifiedEmail())

                <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-[8px] font-semibold text-emerald-600">
                    Verified
                </span>

            @else

                <span class="shrink-0 rounded-full bg-amber-50 px-2 py-1 text-[8px] font-semibold text-amber-600">
                    Unverified
                </span>

            @endif

        </div>


        {{-- Username --}}

        <div class="flex items-center justify-between px-4 py-4">

            <div class="min-w-0">

                <p class="text-[10px] text-gray-400">
                    Username
                </p>

                <p class="mt-1 truncate text-[12px] font-semibold text-gray-800">
                    {{ $user->username ? '@' . $user->username : 'Belum diatur' }}
                </p>

            </div>

        </div>

    </section>


    {{-- =========================================================
         CURRENT WORKSPACE
    ========================================================== --}}

    @if($user->currentWorkspace)

        <section class="mt-3 bg-white">

            <div class="border-b border-gray-100 px-4 py-3">

                <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
                    Workspace
                </p>

            </div>


            <div class="flex items-center gap-3 px-4 py-4">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-[11px] bg-blue-50 text-blue-600">

                    <svg
                        class="h-5 w-5"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.75 6.75A2.25 2.25 0 016 4.5h4.5l2.25 2.25H18a2.25 2.25 0 012.25 2.25v8.25A2.25 2.25 0 0118 19.5H6a2.25 2.25 0 01-2.25-2.25V6.75z"
                        />
                    </svg>

                </div>


                <div class="min-w-0">

                    <p class="truncate text-[12px] font-semibold text-gray-800">
                        {{ $user->currentWorkspace->name }}
                    </p>

                    <p class="mt-0.5 text-[9px] text-gray-400">
                        Workspace aktif
                    </p>

                </div>

            </div>

        </section>

    @endif


    {{-- =========================================================
         LOGOUT
    ========================================================== --}}

    <section class="mt-3 bg-white px-4 py-4">

        <form
            method="POST"
            action="{{ route('logout') }}"
        >

            @csrf

            <button
                type="submit"
                class="w-full rounded-[12px] border border-red-100 bg-red-50 px-4 py-3 text-[11px] font-semibold text-red-600 active:bg-red-100"
            >
                Keluar dari akun
            </button>

        </form>

    </section>


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