@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Vehicles</h1>
    <a href="{{ route('vehicles.create') }}" class="bg-slate-800 text-white px-4 py-2 rounded text-sm">+ Add Purchase</a>
</div>

<form method="GET" class="bg-white rounded-lg shadow p-4 mb-4 flex gap-3 items-end flex-wrap">
    <div>
        <label class="block text-xs text-gray-500 mb-1">Search</label>
        <input name="search" value="{{ request('search') }}" placeholder="SR No / Vehicle No / Model" class="border rounded px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1">Status</label>
        <select name="status" class="border rounded px-3 py-2 text-sm">
            <option value="">All</option>
            <option value="in_stock" @selected(request('status')=='in_stock')>In Stock</option>
            <option value="sold" @selected(request('status')=='sold')>Sold</option>
        </select>
    </div>
    @if($branches->count() > 1)
    <div>
        <label class="block text-xs text-gray-500 mb-1">Branch</label>
        <select name="branch_id" class="border rounded px-3 py-2 text-sm">
            <option value="">All</option>
            @foreach($branches as $b)
                <option value="{{ $b->id }}" @selected(request('branch_id')==$b->id)>{{ $b->name }}</option>
            @endforeach
        </select>
    </div>
    @endif
    <button class="bg-slate-800 text-white px-4 py-2 rounded text-sm">Filter</button>
</form>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr>
                <th class="px-4 py-2">SR No.</th>
                <th class="px-4 py-2">Vehicle No.</th>
                <th class="px-4 py-2">Model</th>
                <th class="px-4 py-2">Branch</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2 text-right">Net Rate</th>
                <th class="px-4 py-2 text-right">P&amp;L</th>
            </tr>
        </thead>
        <tbody>
        @forelse($vehicles as $v)
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-2"><a href="{{ route('vehicles.show', $v) }}" class="text-blue-600">{{ $v->sr_no }}</a></td>
                <td class="px-4 py-2">{{ $v->vehicle_no }}</td>
                <td class="px-4 py-2">{{ $v->model }}</td>
                <td class="px-4 py-2">{{ $v->branch->name }}</td>
                <td class="px-4 py-2">
                    <span class="px-2 py-0.5 rounded text-xs {{ $v->status === 'sold' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $v->status === 'sold' ? 'Sold' : 'In Stock' }}
                    </span>
                </td>
                <td class="px-4 py-2 text-right">₹{{ number_format($v->purchase->net_rate ?? 0, 0) }}</td>
                <td class="px-4 py-2 text-right {{ ($v->sale->profit_loss ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $v->sale ? '₹'.number_format($v->sale->profit_loss, 0) : '—' }}
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-400">No vehicles found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $vehicles->links() }}</div>
@endsection
