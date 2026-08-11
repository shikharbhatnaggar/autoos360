<!DOCTYPE html>
<!-- <html lang="en" class="h-full"> -->
<html lang="en" x-data="{ darkMode: false, sidebarOpen: false }"
      x-init="
        darkMode = localStorage.getItem('theme') === 'dark';
        document.documentElement.classList.toggle('dark', darkMode);
      ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Vehicle Inventory' }} | Maharajah Enterprises</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="h-full bg-gray-100 text-gray-800" :class="{ 'overflow-hidden': sidebarOpen }">

@if(auth()->check())

<div class="flex h-screen overflow-hidden" @keydown.escape.window="sidebarOpen = false">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    <div class="flex min-w-0 flex-1 flex-col overflow-hidden">

        {{-- Navbar --}}
        @include('partials.navbar')

        {{-- Main Content --}}
        <main class="min-w-0 flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">

            @include('partials.alerts')

            @yield('content')

        </main>

        {{-- Footer --}}
        @include('partials.footer')

    </div>

</div>

@else

<main class="flex min-h-screen items-center justify-center bg-gray-100 px-4">

    @yield('content')

</main>

@endif

</body>
</html>
