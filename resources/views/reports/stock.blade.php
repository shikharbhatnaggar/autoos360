@extends('layouts.app')
@section('page-title','Stock')
@section('content')
<div class="flex items-center justify-between mb-6 print:hidden">
    <h1 class="text-2xl font-semibold">Our Stock</h1>
    <div class="flex gap-3">
        <button onclick="window.print()" class="bg-slate-800 text-white px-4 py-2 rounded text-sm">Print</button>
        <a href="{{ route('vehicles.index') }}" class="px-4 py-2 text-sm text-gray-600">Back to Vehicles</a>
    </div>
</div>

{{-- ReportService::stockInHand() doesn't currently return a branch list, so this is a
     plain filter (branch_id) rather than a populated dropdown. If you want a proper
     dropdown here, add $branches (scoped to the user, same as forUser()) to the
     'stockInHand' return array and swap the input below for a @foreach select. --}}
<form method="GET" class="bg-white rounded-lg shadow p-4 mb-4 flex gap-3 items-end flex-wrap print:hidden">
    <div>
        <label class="block text-xs text-gray-500 mb-1">Branch ID</label>
        <input type="text" name="branch_id" value="{{ request('branch_id') }}" placeholder="All branches" class="border rounded px-3 py-2 text-sm w-40">
    </div>
    <button class="bg-slate-800 text-white px-4 py-2 rounded text-sm">Filter</button>
    @if(request('branch_id'))
        <a href="{{ route('reports.stock') }}" class="text-sm text-gray-500">Clear</a>
    @endif
</form>

<div class="hidden print:block mb-6">
    <h1 class="text-xl font-bold">Stock Report</h1>
    <p class="text-sm text-gray-500">As of {{ now()->format('d-m-Y') }}</p>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-xs text-gray-500 mb-1">Vehicles in Stock</div>
        <div class="text-2xl font-bold text-slate-800">{{ $count }}</div>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="text-xs text-gray-500 mb-1">Capital Locked</div>
        <div class="text-2xl font-bold text-slate-800">₹{{ number_format($capital_locked, 2) }}</div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr>
                <th class="px-4 py-2">SR No.</th>
                <th class="px-4 py-2">Vehicle No.</th>
                <th class="px-4 py-2">Model</th>
                <th class="px-4 py-2">Branch</th>
                <th class="px-4 py-2">Seller</th>
                <th class="px-4 py-2">D.O.P</th>
                <th class="px-4 py-2 text-right">Days in Stock</th>
                <th class="px-4 py-2 text-right">Net Rate</th>
            </tr>
        </thead>
        <tbody>
        @forelse($vehicles as $v)
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-2">
                    <a href="{{ route('vehicles.show', $v) }}" class="text-blue-600 print:text-black print:no-underline">{{ $v->sr_no }}</a>
                </td>
                <td class="px-4 py-2">{{ $v->vehicle_no }}</td>
                <td class="px-4 py-2">{{ $v->model }}</td>
                <td class="px-4 py-2">{{ $v->branch->name }}</td>
                <td class="px-4 py-2">{{ $v->purchase->seller_name }}</td>
                <td class="px-4 py-2">{{ $v->purchase->purchase_date->format('d-m-Y') }}</td>
                <td class="px-4 py-2 text-right">{{ $v->purchase->purchase_date->diffInDays(now()) }}</td>
                <td class="px-4 py-2 text-right">₹{{ number_format($v->purchase->net_rate ?? 0, 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="8" class="px-4 py-6 text-center text-gray-400">No vehicles in stock.</td></tr>
        @endforelse
        </tbody>
        @if($count)
        <tfoot>
            <tr class="border-t bg-gray-50 font-semibold">
                <td class="px-4 py-2" colspan="7">Total ({{ $count }} vehicles)</td>
                <td class="px-4 py-2 text-right">₹{{ number_format($capital_locked, 2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

<style>
@media print {
    a { color: inherit !important; text-decoration: none !important; }
}
</style>
@endsection
