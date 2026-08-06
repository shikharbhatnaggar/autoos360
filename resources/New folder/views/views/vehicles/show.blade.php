@extends('layouts.app')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">{{ $vehicle->sr_no }} — {{ $vehicle->vehicle_no }}</h1>
    <span class="px-3 py-1 rounded text-sm {{ $vehicle->isSold() ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
        {{ $vehicle->isSold() ? 'Sold' : 'In Stock' }}
    </span>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Purchase / Seller --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-semibold mb-4 text-slate-700">Purchase (Seller)</h2>
        <dl class="text-sm space-y-2">
            <div class="flex justify-between"><dt class="text-gray-500">Model</dt><dd>{{ $vehicle->model }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Seller</dt><dd>{{ $vehicle->purchase->seller_name }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Mobile</dt><dd>{{ $vehicle->purchase->seller_mobile }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Reference</dt>
                <dd>{{ ucfirst($vehicle->purchase->reference_type) }}{{ $vehicle->purchase->broker ? ' — '.$vehicle->purchase->broker->name : '' }}</dd>
            </div>
            <div class="flex justify-between"><dt class="text-gray-500">D.O.P</dt><dd>{{ $vehicle->purchase->purchase_date->format('d-m-Y') }}</dd></div>
            <div class="flex justify-between border-t pt-2"><dt class="text-gray-500">Purchase Rate</dt><dd>₹{{ number_format($vehicle->purchase->purchase_rate, 2) }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">+ Commission</dt><dd>₹{{ number_format($vehicle->purchase->commission, 2) }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">+ Expenses</dt><dd>₹{{ number_format($vehicle->purchase->expenses_total, 2) }}</dd></div>
            <div class="flex justify-between font-semibold border-t pt-2"><dt>Net Rate</dt><dd>₹{{ number_format($vehicle->purchase->net_rate, 2) }}</dd></div>
        </dl>

        <h3 class="text-sm font-semibold mt-6 mb-2 text-slate-600">Expense Detail</h3>
        <table class="w-full text-sm">
            <tbody>
            @foreach($vehicle->expenses as $expense)
                <tr class="border-t">
                    <td class="py-1.5">{{ \App\Models\VehicleExpense::CATEGORIES[$expense->category] ?? $expense->category }}</td>
                    <td class="py-1.5 text-right">₹{{ number_format($expense->amount, 2) }}</td>
                    <td class="py-1.5 text-right text-gray-400">{{ $expense->percentage ? $expense->percentage.'%' : '' }}</td>
                    <td class="py-1.5 text-right">
                        <form method="POST" action="{{ route('vehicles.expenses.destroy', [$vehicle, $expense]) }}">
                            @csrf @method('DELETE')
                            <button class="text-red-500 text-xs">remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <a href="{{ route('vehicles.edit', $vehicle) }}" class="inline-block mt-4 text-sm text-blue-600">Edit purchase</a>
    </div>

    {{-- Sale / Purchaser --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-semibold mb-4 text-slate-700">Sale (Purchaser)</h2>
        @if($vehicle->sale)
            <dl class="text-sm space-y-2">
                <div class="flex justify-between"><dt class="text-gray-500">Purchaser</dt><dd>{{ $vehicle->sale->purchaser_name }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Mobile</dt><dd>{{ $vehicle->sale->purchaser_mobile }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Reference / Medium</dt><dd>{{ $vehicle->sale->reference_medium }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">D.O.S</dt><dd>{{ $vehicle->sale->sale_date->format('d-m-Y') }}</dd></div>
                <div class="flex justify-between border-t pt-2"><dt class="text-gray-500">Sale Rate</dt><dd>₹{{ number_format($vehicle->sale->sale_rate, 2) }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">− Commission</dt><dd>₹{{ number_format($vehicle->sale->commission, 2) }}</dd></div>
                <div class="flex justify-between font-semibold"><dt>Net Rate</dt><dd>₹{{ number_format($vehicle->sale->net_rate, 2) }}</dd></div>
                <div class="flex justify-between font-bold text-lg border-t pt-2 {{ $vehicle->sale->profit_loss >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    <dt>Profit / Loss</dt><dd>₹{{ number_format($vehicle->sale->profit_loss, 2) }}</dd>
                </div>
            </dl>
            <a href="{{ route('vehicles.sale.edit', $vehicle) }}" class="inline-block mt-4 text-sm text-blue-600">Edit sale</a>
        @else
            <p class="text-sm text-gray-500 mb-4">Not sold yet.</p>
            <a href="{{ route('vehicles.sale.create', $vehicle) }}" class="bg-slate-800 text-white px-4 py-2 rounded text-sm">Record Sale</a>
        @endif
    </div>
</div>
@endsection
