@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-semibold mb-6">Add Branch</h1>

<form method="POST" action="{{ route('branches.store') }}" class="space-y-6">
    @csrf

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-semibold mb-4 text-slate-700">Branch Details</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Name</label>
                <input name="name" required value="{{ old('name') }}" class="w-full border rounded px-3 py-2 text-sm">
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Code</label>
                <input name="code" required value="{{ old('code') }}" class="w-full border rounded px-3 py-2 text-sm" placeholder="e.g. HYD-01">
                @error('code') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Must be unique. Cannot be changed after the branch is created.</p>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Phone</label>
                <input name="phone" value="{{ old('phone') }}" class="w-full border rounded px-3 py-2 text-sm">
                @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Address</label>
                <input name="address" value="{{ old('address') }}" class="w-full border rounded px-3 py-2 text-sm">
                @error('address') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('branches.index') }}" class="px-4 py-2 text-sm text-gray-600">Cancel</a>
        <button class="bg-slate-800 text-white px-5 py-2 rounded text-sm">Save Branch</button>
    </div>
</form>
@endsection
