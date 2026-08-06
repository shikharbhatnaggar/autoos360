<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Vehicle Inventory' }} — Maharajah Enterprises</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    @auth
    <nav class="bg-slate-800 text-white px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-6">
            <a href="{{ route('dashboard') }}" class="font-semibold">🚗 Vehicle Inventory</a>
            <a href="{{ route('vehicles.index') }}" class="text-sm text-slate-300 hover:text-white">Vehicles</a>
            <a href="{{ route('reports.profit_loss') }}" class="text-sm text-slate-300 hover:text-white">P&amp;L Report</a>
            <a href="{{ route('reports.stock') }}" class="text-sm text-slate-300 hover:text-white">Stock Report</a>
            @if(auth()->user()->hasRole('admin','branch_manager'))
                <a href="{{ route('brokers.index') }}" class="text-sm text-slate-300 hover:text-white">Brokers</a>
            @endif
            @if(auth()->user()->isAdmin())
                <a href="{{ route('branches.index') }}" class="text-sm text-slate-300 hover:text-white">Branches</a>
            @endif
        </div>
        <div class="flex items-center gap-4 text-sm">
            <span class="text-slate-300">{{ auth()->user()->name }} ({{ auth()->user()->role->label }})</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-slate-300 hover:text-white">Logout</button>
            </form>
        </div>
    </nav>
    @endauth

    <main class="max-w-6xl mx-auto py-8 px-4">
        @if(session('success'))
            <div class="mb-4 rounded bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-3 text-sm">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-3 text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>
</body>
</html>
