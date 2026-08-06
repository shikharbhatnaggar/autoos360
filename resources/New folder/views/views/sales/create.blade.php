@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-semibold mb-6">Record Sale — {{ $vehicle->sr_no }} ({{ $vehicle->vehicle_no }})</h1>

<div class="bg-blue-50 text-blue-800 text-sm rounded px-4 py-2 mb-6">
    Purchase Net Rate: ₹{{ number_format($vehicle->purchase->net_rate, 2) }} — this is the cost basis used for Profit/Loss.
</div>

<form method="POST" action="{{ route('vehicles.sale.store', $vehicle) }}" x-data="saleForm({{ $vehicle->purchase->net_rate }})" class="bg-white rounded-lg shadow p-6 space-y-4">
    @csrf
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Purchaser Name</label>
            <input name="purchaser_name" required class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Address</label>
            <input name="purchaser_address" class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Mobile</label>
            <input name="purchaser_mobile" class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Reference (Branch Name / Medium of Sale)</label>
            <input name="reference_medium" class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">D.O.S (Date of Sale)</label>
            <input type="date" name="sale_date" required class="w-full border rounded px-3 py-2 text-sm">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 border-t pt-4">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Sale Rate</label>
            <input type="number" step="0.01" name="sale_rate" x-model.number="saleRate" required class="w-full border rounded px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">− Commission</label>
            <input type="number" step="0.01" name="commission" x-model.number="commission" value="0" class="w-full border rounded px-3 py-2 text-sm">
        </div>
    </div>

    <div class="border-t pt-4 text-right space-y-1">
        <p class="text-sm text-gray-500">Net Rate: <span class="font-semibold text-slate-800" x-text="'₹' + netRate.toFixed(2)"></span></p>
        <p class="text-lg font-bold" :class="profitLoss >= 0 ? 'text-green-600' : 'text-red-600'">
            Profit/Loss: <span x-text="'₹' + profitLoss.toFixed(2)"></span>
        </p>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('vehicles.show', $vehicle) }}" class="px-4 py-2 text-sm text-gray-600">Cancel</a>
        <button class="bg-slate-800 text-white px-5 py-2 rounded text-sm">Save Sale</button>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function saleForm(purchaseNetRate) {
    return {
        saleRate: 0,
        commission: 0,
        get netRate() { return (Number(this.saleRate) || 0) - (Number(this.commission) || 0); },
        get profitLoss() { return this.netRate - purchaseNetRate; },
    }
}
</script>
@endsection
