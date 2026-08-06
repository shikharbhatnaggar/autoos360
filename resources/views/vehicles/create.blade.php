@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-semibold mb-6">New Vehicle Purchase</h1>

<form method="POST" action="{{ route('vehicles.store') }}" x-data="purchaseForm()" class="space-y-6">
    @csrf

    {{-- Vehicle identity --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-semibold mb-4 text-slate-700">Vehicle Details</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Branch</label>
                <select name="branch_id" required class="w-full border rounded px-3 py-2 text-sm">
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">SR No.</label>
                <input name="sr_no" required class="w-full border rounded px-3 py-2 text-sm" placeholder="SR-020">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Memo No.</label>
                <input name="memo_no" class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Vehicle No.</label>
                <input name="vehicle_no" required class="w-full border rounded px-3 py-2 text-sm" placeholder="TS09AB1234">
            </div>
            <div class="col-span-2">
                <label class="block text-xs text-gray-500 mb-1">Model</label>
                <input name="model" required class="w-full border rounded px-3 py-2 text-sm">
            </div>
        </div>
    </div>

    {{-- Seller block (from memo) --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-semibold mb-4 text-slate-700">Seller</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Seller Name</label>
                <input name="seller_name" required class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Address</label>
                <input name="seller_address" class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Mobile</label>
                <input name="seller_mobile" class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Reference</label>
                <select name="reference_type" x-model="referenceType" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="direct">Direct</option>
                    <option value="broker">Broker</option>
                </select>
            </div>
            <div x-show="referenceType === 'broker'">
                <label class="block text-xs text-gray-500 mb-1">Broker</label>
                <select name="broker_id" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">— select —</option>
                    @foreach($brokers as $broker)
                        <option value="{{ $broker->id }}">{{ $broker->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">D.O.P (Date of Purchase)</label>
                <input type="date" name="purchase_date" required class="w-full border rounded px-3 py-2 text-sm">
            </div>
        </div>
    </div>

    {{-- Purchase Rate / Commission (from memo) --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-semibold mb-4 text-slate-700">Purchase Rate</h2>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Purchase Rate</label>
                <input type="number" step="0.01" name="purchase_rate" x-model.number="purchaseRate" required
                       class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">+ Commission</label>
                <input type="number" step="0.01" name="commission" x-model.number="commission"
                       class="w-full border rounded px-3 py-2 text-sm" value="0">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Expenses (from below)</label>
                <input type="text" disabled x-bind:value="'₹' + expensesTotal.toFixed(2)"
                       class="w-full border rounded px-3 py-2 text-sm bg-gray-50 text-gray-600">
            </div>
        </div>
        <div class="mt-4 text-right border-t pt-3">
            <span class="text-sm text-gray-500">Net Rate:</span>
            <span class="text-lg font-bold text-slate-800" x-text="'₹' + netRate.toFixed(2)"></span>
        </div>
    </div>

    {{-- Expense Detail (from memo Image 2) --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-semibold mb-4 text-slate-700">Expense Detail</h2>
        <template x-for="(row, i) in expenses" :key="i">
            <div class="grid grid-cols-12 gap-3 mb-3 items-end">
                <div class="col-span-4">
                    <label class="block text-xs text-gray-500 mb-1">Category</label>
                    <select :name="'expenses['+i+'][category]'" x-model="row.category" class="w-full border rounded px-3 py-2 text-sm">
                        <option value="engine">Engine</option>
                        <option value="denting_painting">Denting / Painting</option>
                        <option value="accessories">Accessories</option>
                        <option value="tyre">Tyre</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-span-3">
                    <label class="block text-xs text-gray-500 mb-1">Amount</label>
                    <input type="number" step="0.01" :name="'expenses['+i+'][amount]'" x-model.number="row.amount"
                           @input="recalc()" class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div class="col-span-3">
                    <label class="block text-xs text-gray-500 mb-1">% (optional)</label>
                    <input type="number" step="0.01" :name="'expenses['+i+'][percentage]'" x-model.number="row.percentage"
                           class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <div class="col-span-2">
                    <button type="button" @click="removeExpense(i)" class="text-red-600 text-xs">Remove</button>
                </div>
            </div>
        </template>
        <button type="button" @click="addExpense()" class="text-sm text-blue-600">+ Add expense line</button>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('vehicles.index') }}" class="px-4 py-2 text-sm text-gray-600">Cancel</a>
        <button class="bg-slate-800 text-white px-5 py-2 rounded text-sm">Save Purchase</button>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function purchaseForm() {
    return {
        referenceType: 'direct',
        purchaseRate: 0,
        commission: 0,
        expenses: [{ category: 'engine', amount: 0, percentage: null }],
        get expensesTotal() {
            return this.expenses.reduce((sum, r) => sum + (Number(r.amount) || 0), 0);
        },
        get netRate() {
            return (Number(this.purchaseRate) || 0) + (Number(this.commission) || 0) + this.expensesTotal;
        },
        addExpense() {
            this.expenses.push({ category: 'other', amount: 0, percentage: null });
        },
        removeExpense(i) {
            this.expenses.splice(i, 1);
        },
        recalc() {},
    }
}
</script>
@endsection
