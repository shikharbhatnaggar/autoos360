@extends('layouts.app')
@section('content')
<div class="max-w-sm mx-auto mt-16 bg-white p-8 rounded-lg shadow">
    <h1 class="text-xl font-semibold mb-6 text-center">Vehicle Inventory — Login</h1>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm text-gray-600 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Password</label>
            <input type="password" name="password" required class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <label class="flex items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" name="remember"> Remember me
        </label>
        <button class="w-full bg-slate-800 text-white rounded py-2 text-sm hover:bg-slate-700">
            Sign in
        </button>
    </form>
    <p class="text-xs text-gray-400 mt-4 text-center">
        Demo: admin@maharajah.local / password
    </p>
</div>
@endsection
