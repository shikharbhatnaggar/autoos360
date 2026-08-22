<!DOCTYPE html>
<!-- <html lang="en" class="h-full"> -->
<html lang="en" x-data="{ darkMode: false }"
      x-init="
        darkMode = localStorage.getItem('theme') === 'dark';
        document.documentElement.classList.toggle('dark', darkMode);
      ">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Vehicle Inventory' }} | Maharajah Enterprises</title>
    
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="h-full bg-gray-100 text-gray-800">

@if(auth()->check())

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    <div class="flex flex-1 flex-col overflow-hidden">

        {{-- Navbar --}}
        @include('partials.navbar')

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto p-6">

            @include('partials.alerts')

            @yield('content')

        </main>

        {{-- Footer --}}
        @include('partials.footer')

    </div>

</div>

@else

<main class="min-h-screen flex items-center justify-center bg-gray-100">

    @yield('content')

</main>

@endif

</body>
</html>