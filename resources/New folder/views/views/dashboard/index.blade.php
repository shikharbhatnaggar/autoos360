@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-semibold mb-6">Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">In Stock</p>
        <p class="text-3xl font-bold text-slate-800">{{ $inStockCount }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Sold This Month</p>
        <p class="text-3xl font-bold text-slate-800">{{ $soldThisMonthCount }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Profit This Month</p>
        <p class="text-3xl font-bold {{ $monthProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
            ₹{{ number_format($monthProfit, 0) }}
        </p>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="px-5 py-3 border-b flex items-center justify-between">
        <h2 class="font-semibold">Recent Vehicles</h2>
        <a href="{{ route('vehicles.create') }}" class="text-sm bg-slate-800 text-white px-3 py-1.5 rounded">+ Add Purchase</a>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr>
                <th class="px-4 py-2">SR No.</th>
                <th class="px-4 py-2">Vehicle No.</th>
                <th class="px-4 py-2">Model</th>
                <th class="px-4 py-2">Branch</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Net Rate</th>
            </tr>
        </thead>
        <tbody>
        @foreach($recent as $v)
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
                <td class="px-4 py-2">₹{{ number_format($v->purchase->net_rate ?? 0, 0) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
