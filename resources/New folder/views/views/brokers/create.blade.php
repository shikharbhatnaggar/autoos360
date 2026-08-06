@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-semibold mb-6">Add Broker</h1>

<form method="POST" action="{{ route('brokers.store') }}" class="space-y-6">
    @csrf

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-semibold mb-4 text-slate-700">Broker Details</h2>
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-xs text-gray-500 mb-1">Name</label>
                <input name="name" required value="{{ old('name') }}" class="w-full border rounded px-3 py-2 text-sm">
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Mobile</label>
                <input name="mobile" value="{{ old('mobile') }}" class="w-full border rounded px-3 py-2 text-sm">
                @error('mobile') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Address</label>
                <input name="address" value="{{ old('address') }}" class="w-full border rounded px-3 py-2 text-sm">
                @error('address') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('brokers.index') }}" class="px-4 py-2 text-sm text-gray-600">Cancel</a>
        <button class="bg-slate-800 text-white px-5 py-2 rounded text-sm">Save Broker</button>
    </div>
</form>
@endsection 