@extends('layouts.app')

@section('content')

<div class="min-h-screen flex flex-col lg:flex-row bg-white">

    {{-- ========================================================= --}}
    {{-- Left: Brand / Background --}}
    {{-- ========================================================= --}}

    <div
        class="relative w-full lg:w-1/2 min-h-[280px] lg:min-h-screen
               overflow-hidden bg-slate-900"
    >

        {{-- Background Image --}}
        <div
            class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('{{ asset('images/login-bg.jpg') }}');"
        ></div>

        {{-- Dark Overlay --}}
        <div class="absolute inset-0 bg-slate-950/70"></div>


        {{-- Brand Content --}}
        <div
            class="relative z-10 flex h-full min-h-[280px] lg:min-h-screen
                   flex-col justify-between p-8 sm:p-10 lg:p-14"
        >

            {{-- Logo / Brand --}}
            <div>

                <div class="flex items-center gap-3">

                    {{-- Replace with your actual logo --}}
                    <div
                        class="flex h-12 w-12 items-center justify-center
                               rounded-xl bg-white shadow-lg"
                    >
                        <span class="text-xl font-bold text-slate-900">
                            A
                        </span>
                    </div>

                    <div>

                        <div class="text-2xl font-bold tracking-tight text-white">
                            Autoos360
                        </div>

                        <div class="text-xs text-slate-300">
                            Vehicle Inventory Management
                        </div>

                    </div>

                </div>

            </div>


            {{-- Brand Message --}}
            <div class="hidden max-w-lg lg:block">

                <h2
                    class="text-4xl font-bold leading-tight tracking-tight text-white xl:text-5xl"
                >
                    Manage your vehicle inventory
                    <span class="text-slate-300">
                        smarter.
                    </span>
                </h2>

                <p class="mt-5 max-w-md text-base leading-7 text-slate-300">
                    Keep your vehicles, branches, brokers, sales and profitability
                    organized in one simple platform.
                </p>

            </div>


            {{-- Footer --}}
            <div class="hidden lg:block">

                <p class="text-xs text-slate-400">
                    © {{ date('Y') }} Autoos360. All rights reserved.
                </p>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- Right: Login --}}
    {{-- ========================================================= --}}

    <div
        class="flex w-full flex-1 items-center justify-center
               bg-white px-5 py-10 sm:px-8 lg:w-1/2 lg:px-12"
    >

        <div class="w-full max-w-md">

            {{-- Mobile Brand --}}
            <div class="mb-8 text-center lg:hidden">

                <div class="flex items-center justify-center gap-3">

                    <div
                        class="flex h-10 w-10 items-center justify-center
                               rounded-lg bg-slate-900 text-white"
                    >
                        <span class="font-bold">
                            A
                        </span>
                    </div>

                    <div class="text-left">

                        <div class="text-xl font-bold text-slate-900">
                            Autoos360
                        </div>

                        <div class="text-[11px] text-gray-500">
                            Vehicle Inventory Management
                        </div>

                    </div>

                </div>

            </div>


            {{-- Login Heading --}}
            <div class="mb-8">

                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">
                    Welcome back
                </h1>

                <p class="mt-2 text-sm text-gray-500">
                    Sign in to continue to your inventory dashboard.
                </p>

            </div>


            {{-- Login Form --}}
            <form
                method="POST"
                action="{{ route('login') }}"
                class="space-y-5"
            >

                @csrf


                {{-- Email --}}
                <div>

                    <label
                        for="email"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Email
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="Enter your email"
                        class="w-full rounded-lg border border-gray-300 bg-white
                               px-4 py-3 text-sm text-gray-900
                               placeholder-gray-400
                               transition
                               focus:border-blue-500
                               focus:outline-none
                               focus:ring-2 focus:ring-blue-500/20"
                    >

                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Password --}}
                <div>

                    <label
                        for="password"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Password
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                        class="w-full rounded-lg border border-gray-300 bg-white
                               px-4 py-3 text-sm text-gray-900
                               placeholder-gray-400
                               transition
                               focus:border-blue-500
                               focus:outline-none
                               focus:ring-2 focus:ring-blue-500/20"
                    >

                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Remember Me --}}
                <label class="flex cursor-pointer items-center gap-2.5 text-sm text-gray-600">

                    <input
                        type="checkbox"
                        name="remember"
                        class="h-4 w-4 rounded border-gray-300 text-blue-600
                               focus:ring-2 focus:ring-blue-500"
                    >

                    <span>
                        Remember me
                    </span>

                </label>


                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full rounded-lg bg-slate-900 px-4 py-3
                           text-sm font-medium text-white
                           transition hover:bg-slate-800
                           focus:outline-none focus:ring-2
                           focus:ring-slate-500 focus:ring-offset-2"
                >
                    Sign in
                </button>

            </form>


            {{-- Demo Credentials --}}
            <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">

                <p class="text-center text-xs text-gray-500">
                    <span class="font-medium text-gray-600">
                        Demo credentials
                    </span>

                    <span class="mx-1">
                        •
                    </span>

                    admin@maharajah.local / password
                </p>

            </div>

        </div>

    </div>

</div>

@endsection
