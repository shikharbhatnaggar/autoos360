@extends('layouts.app')
@section('page-title','Brokers')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Our Agents</h1>
    <a href="{{ route('brokers.create') }}" class="bg-slate-800 text-white px-4 py-2 rounded text-sm">+ Add Broker</a>
</div>

@if(session('success'))
    <div class="bg-green-50 text-green-700 text-sm px-4 py-2 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Mobile</th>
                <th class="px-4 py-2">Address</th>
                <th class="px-4 py-2 text-right">Purchases</th>
                <th class="px-4 py-2 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($brokers as $broker)
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-2">
                    <a href="{{ route('brokers.edit', $broker) }}" class="text-blue-600">{{ $broker->name }}</a>
                </td>
                <td class="px-4 py-2">{{ $broker->mobile ?? '—' }}</td>
                <td class="px-4 py-2">{{ $broker->address ?? '—' }}</td>
                <td class="px-4 py-2 text-right">{{ $broker->purchases_count }}</td>
                <td class="px-4 py-2 text-right whitespace-nowrap">
                    <a href="{{ route('brokers.edit', $broker) }}" class="text-blue-600 text-xs mr-3">Edit</a>
                    <form method="POST" action="{{ route('brokers.destroy', $broker) }}" class="inline"
                          onsubmit="return confirm('Remove {{ $broker->name }}? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 text-xs">Remove</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">No brokers yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $brokers->links() }}</div>
@endsection