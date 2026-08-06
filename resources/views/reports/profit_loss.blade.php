@extends('layouts.app')
@section('page-title','Profit & Loss')
@section('content')
<h1 class="text-2xl font-semibold mb-6">PL Statistics</h1>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4"><p class="text-xs text-gray-500">Vehicles Sold</p><p class="text-2xl font-bold">{{ $count }}</p></div>
    <div class="bg-white rounded-lg shadow p-4"><p class="text-xs text-gray-500">Total Sales Value</p><p class="text-2xl font-bold">₹{{ number_format($total_sales_value,0) }}</p></div>
    <div class="bg-white rounded-lg shadow p-4"><p class="text-xs text-gray-500">Total Profit</p><p class="text-2xl font-bold {{ $total_profit>=0?'text-green-600':'text-red-600' }}">₹{{ number_format($total_profit,0) }}</p></div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-left text-gray-500">
            <tr><th class="px-4 py-2">SR No.</th><th class="px-4 py-2">Vehicle</th><th class="px-4 py-2">Branch</th>
                <th class="px-4 py-2 text-right">Purchase Net</th><th class="px-4 py-2 text-right">Sale Net</th><th class="px-4 py-2 text-right">P&amp;L</th></tr>
        </thead>
        <tbody>
        @foreach($vehicles as $v)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $v->sr_no }}</td>
                <td class="px-4 py-2">{{ $v->vehicle_no }} — {{ $v->model }}</td>
                <td class="px-4 py-2">{{ $v->branch->name }}</td>
                <td class="px-4 py-2 text-right">₹{{ number_format($v->purchase->net_rate,0) }}</td>
                <td class="px-4 py-2 text-right">₹{{ number_format($v->sale->net_rate,0) }}</td>
                <td class="px-4 py-2 text-right font-semibold {{ $v->sale->profit_loss>=0?'text-green-600':'text-red-600' }}">₹{{ number_format($v->sale->profit_loss,0) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
