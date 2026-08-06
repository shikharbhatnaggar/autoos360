@extends('layouts.app')
@section('page-title','Branches')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Our Locations</h1>
    @can('create', App\Models\Branch::class)
        <a href="{{ route('branches.create') }}" class="bg-slate-800 text-white px-4 py-2 rounded text-sm">+ Add Branch</a>
    @endcan
</div>

@if(session('success'))
    <div class="bg-green-50 text-green-700 text-sm px-4 py-2 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Code</th>
                <th class="px-4 py-2">Phone</th>
                <th class="px-4 py-2">Address</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2 text-right">Vehicles</th>
                <th class="px-4 py-2 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($branches as $branch)
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-2">
                    <a href="{{ route('branches.edit', $branch) }}" class="text-blue-600">{{ $branch->name }}</a>
                </td>
                <td class="px-4 py-2 text-gray-500">{{ $branch->code }}</td>
                <td class="px-4 py-2">{{ $branch->phone ?? '—' }}</td>
                <td class="px-4 py-2">{{ $branch->address ?? '—' }}</td>
                <td class="px-4 py-2">
                    <span class="px-2 py-0.5 rounded text-xs {{ $branch->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $branch->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-4 py-2 text-right">{{ $branch->vehicles_count }}</td>
                <td class="px-4 py-2 text-right whitespace-nowrap">
                    <a href="{{ route('branches.edit', $branch) }}" class="text-blue-600 text-xs">Edit</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-400">No branches yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
