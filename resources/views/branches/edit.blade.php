@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-semibold mb-6">Edit Branch — {{ $branch->name }}</h1>

<form method="POST" action="{{ route('branches.update', $branch) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-semibold mb-4 text-slate-700">Branch Details</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Name</label>
                <input name="name" required value="{{ old('name', $branch->name) }}" class="w-full border rounded px-3 py-2 text-sm">
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Code</label>
                {{-- Code is immutable after creation — update() validation doesn't accept it. --}}
                <input type="text" disabled value="{{ $branch->code }}" class="w-full border rounded px-3 py-2 text-sm bg-gray-50 text-gray-500">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Phone</label>
                <input name="phone" value="{{ old('phone', $branch->phone) }}" class="w-full border rounded px-3 py-2 text-sm">
                @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Address</label>
                <input name="address" value="{{ old('address', $branch->address) }}" class="w-full border rounded px-3 py-2 text-sm">
                @error('address') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="col-span-2 flex items-center gap-2 pt-2">
                {{-- Hidden field ensures unchecking the box actually submits is_active=0,
                     since browsers omit unchecked checkboxes from the request entirely. --}}
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                       @checked(old('is_active', $branch->is_active)) class="rounded border-gray-300">
                <label for="is_active" class="text-sm text-gray-700">Branch is active</label>
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('branches.index') }}" class="px-4 py-2 text-sm text-gray-600">Cancel</a>
        <button class="bg-slate-800 text-white px-5 py-2 rounded text-sm">Update Branch</button>
    </div>
</form>
@endsection
