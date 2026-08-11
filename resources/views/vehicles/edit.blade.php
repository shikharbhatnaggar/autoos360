@extends('layouts.app')

@section('page-title', 'Edit Vehicle')

@section('content')

<x-section-header
    title="Edit Vehicle Purchase"
    :subtitle="'Update purchase details for '.$vehicle->sr_no"
/>

<form
    method="POST"
    action="{{ route('vehicles.update', $vehicle) }}"
    x-data="purchaseForm(
        {{ Illuminate\Support\Js::from($vehicle->expenses->map(fn($e) => [
            'category' => $e->category,
            'amount' => (float) $e->amount,
            'percentage' => $e->percentage !== null ? (float) $e->percentage : null,
        ])->values()) }},
        {{ (float) $vehicle->purchase->purchase_rate }},
        {{ (float) $vehicle->purchase->commission }}
    )"
    class="space-y-6"
>
    @csrf
    @method('PUT')


    {{-- ========================================================= --}}
    {{-- Vehicle Details --}}
    {{-- ========================================================= --}}

    <x-card>

        <x-slot:header>

            <div>
                <h2 class="text-base font-semibold text-gray-800">
                    Vehicle Details
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Basic vehicle and inventory information
                </p>
            </div>

        </x-slot:header>


        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-4">

            {{-- Branch --}}
            <div>

                <label
                    for="branch_id"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Branch
                </label>

                @if(isset($branches))

                    <select
                        id="branch_id"
                        name="branch_id"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm
                               text-gray-700 transition
                               focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >

                        @foreach($branches as $branch)

                            <option
                                value="{{ $branch->id }}"
                                @selected($branch->id === $vehicle->branch_id)
                            >
                                {{ $branch->name }}
                            </option>

                        @endforeach

                    </select>

                @else

                    {{-- Preserve existing read-only branch behaviour --}}
                    <input
                        type="hidden"
                        name="branch_id"
                        value="{{ $vehicle->branch_id }}"
                    >

                    <input
                        type="text"
                        disabled
                        value="{{ $vehicle->branch->name }}"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-600"
                    >

                @endif

            </div>


            {{-- SR No --}}
            <x-input
                name="sr_no"
                label="SR No."
                :value="old('sr_no', $vehicle->sr_no)"
                placeholder="SR-020"
                required
            />


            {{-- Memo No --}}
            <x-input
                name="memo_no"
                label="Memo No."
                :value="old('memo_no', $vehicle->memo_no)"
            />


            {{-- Vehicle No --}}
            <x-input
                name="vehicle_no"
                label="Vehicle No."
                :value="old('vehicle_no', $vehicle->vehicle_no)"
                placeholder="TS09AB1234"
                required
            />


            {{-- Model --}}
            <div class="sm:col-span-2">

                <x-input
                    name="model"
                    label="Model"
                    :value="old('model', $vehicle->model)"
                    required
                />

            </div>

        </div>

    </x-card>


    {{-- ========================================================= --}}
    {{-- Seller Information --}}
    {{-- ========================================================= --}}

    <x-card>

        <x-slot:header>

            <div>
                <h2 class="text-base font-semibold text-gray-800">
                    Seller Information
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Seller and purchase reference details
                </p>
            </div>

        </x-slot:header>


        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

            {{-- Seller Name --}}
            <x-input
                name="seller_name"
                label="Seller Name"
                :value="old('seller_name', $vehicle->purchase->seller_name)"
                required
            />


            {{-- Address --}}
            <x-input
                name="seller_address"
                label="Address"
                :value="old('seller_address', $vehicle->purchase->seller_address)"
            />


            {{-- Mobile --}}
            <x-input
                name="seller_mobile"
                label="Mobile"
                :value="old('seller_mobile', $vehicle->purchase->seller_mobile)"
            />


            {{-- Reference --}}
            <div>

                <label
                    for="reference_type"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Reference
                </label>

                <select
                    id="reference_type"
                    name="reference_type"
                    x-model="referenceType"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm
                           text-gray-700 transition
                           focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

                    <option value="direct">
                        Direct
                    </option>

                    <option value="broker">
                        Broker
                    </option>

                </select>

            </div>


            {{-- Broker --}}
            <div x-show="referenceType === 'broker'">

                <label
                    for="broker_id"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Broker
                </label>

                <select
                    id="broker_id"
                    name="broker_id"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm
                           text-gray-700 transition
                           focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

                    <option value="">
                        — select —
                    </option>

                    @foreach($brokers as $broker)

                        <option
                            value="{{ $broker->id }}"
                            @selected($broker->id === $vehicle->purchase->broker_id)
                        >
                            {{ $broker->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Purchase Date --}}
            <div>

                <label
                    for="purchase_date"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    D.O.P
                    <span class="font-normal text-gray-400">
                        (Date of Purchase)
                    </span>
                </label>

                <input
                    id="purchase_date"
                    type="date"
                    name="purchase_date"
                    required
                    value="{{ old('purchase_date', optional($vehicle->purchase->purchase_date)->format('Y-m-d')) }}"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm
                           text-gray-700 transition
                           focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

            </div>

        </div>

    </x-card>


    {{-- ========================================================= --}}
    {{-- Purchase Rate --}}
    {{-- ========================================================= --}}

    <x-card>

        <x-slot:header>

            <div>
                <h2 class="text-base font-semibold text-gray-800">
                    Purchase Rate
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Purchase value, commission and calculated net rate
                </p>
            </div>

        </x-slot:header>


        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

            {{-- Purchase Rate --}}
            <x-input
                name="purchase_rate"
                label="Purchase Rate"
                type="number"
                step="0.01"
                x-model.number="purchaseRate"
                required
            />


            {{-- Commission --}}
            <x-input
                name="commission"
                label="+ Commission"
                type="number"
                step="0.01"
                x-model.number="commission"
            />


            {{-- Expenses --}}
            <div>

                <label
                    for="expenses_total"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Expenses
                </label>

                <input
                    id="expenses_total"
                    type="text"
                    disabled
                    x-bind:value="'₹' + expensesTotal.toFixed(2)"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-600"
                >

            </div>

        </div>


        {{-- Net Rate --}}
        <div class="mt-6 flex items-center justify-between rounded-xl border border-gray-200 bg-gray-50 px-5 py-4">

            <div>

                <p class="text-sm font-medium text-gray-600">
                    Net Rate
                </p>

                <p class="mt-0.5 text-xs text-gray-400">
                    Purchase rate + commission + expenses
                </p>

            </div>

            <span
                class="text-xl font-bold text-gray-900"
                x-text="'₹' + netRate.toFixed(2)"
            ></span>

        </div>

    </x-card>


    {{-- ========================================================= --}}
    {{-- Expense Details --}}
    {{-- ========================================================= --}}

    <x-card>

        <x-slot:header>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h2 class="text-base font-semibold text-gray-800">
                        Expense Detail
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Update expenses associated with this vehicle
                    </p>

                </div>


                <button
                    type="button"
                    @click="addExpense()"
                    class="inline-flex items-center justify-center gap-1.5 rounded-lg px-3 py-2
                           text-sm font-medium text-blue-600
                           transition hover:bg-blue-50 hover:text-blue-700"
                >
                    <span class="text-lg leading-none">+</span>
                    Add Expense
                </button>

            </div>

        </x-slot:header>


        {{-- Desktop heading --}}
        <div class="mb-2 hidden grid-cols-12 gap-3 border-b border-gray-100 pb-3 md:grid">

            <div class="col-span-4 text-xs font-semibold uppercase tracking-wide text-gray-400">
                Category
            </div>

            <div class="col-span-3 text-xs font-semibold uppercase tracking-wide text-gray-400">
                Amount
            </div>

            <div class="col-span-3 text-xs font-semibold uppercase tracking-wide text-gray-400">
                Percentage
            </div>

            <div class="col-span-2 text-xs font-semibold uppercase tracking-wide text-gray-400">
                Action
            </div>

        </div>


        {{-- Existing Alpine expense functionality preserved --}}
        <template
            x-for="(row, i) in expenses"
            :key="i"
        >

            <div class="grid grid-cols-1 gap-4 border-b border-gray-100 py-4 md:grid-cols-12 md:items-end">

                {{-- Category --}}
                <div class="md:col-span-4">

                    <label class="mb-2 block text-sm font-medium text-gray-700 md:hidden">
                        Category
                    </label>

                    <select
                        :name="'expenses['+i+'][category]'"
                        x-model="row.category"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm
                               text-gray-700 transition
                               focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >

                        <option value="engine">
                            Engine
                        </option>

                        <option value="denting_painting">
                            Denting / Painting
                        </option>

                        <option value="accessories">
                            Accessories
                        </option>

                        <option value="tyre">
                            Tyre
                        </option>

                        <option value="other">
                            Other
                        </option>

                    </select>

                </div>


                {{-- Amount --}}
                <div class="md:col-span-3">

                    <label class="mb-2 block text-sm font-medium text-gray-700 md:hidden">
                        Amount
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        :name="'expenses['+i+'][amount]'"
                        x-model.number="row.amount"
                        @input="recalc()"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm
                               text-gray-700 transition
                               focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >

                </div>


                {{-- Percentage --}}
                <div class="md:col-span-3">

                    <label class="mb-2 block text-sm font-medium text-gray-700 md:hidden">
                        % (optional)
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        :name="'expenses['+i+'][percentage]'"
                        x-model.number="row.percentage"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm
                               text-gray-700 transition
                               focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >

                </div>


                {{-- Remove --}}
                <div class="md:col-span-2">

                    <button
                        type="button"
                        @click="removeExpense(i)"
                        class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium
                               text-red-600 transition hover:bg-red-50 hover:text-red-700"
                    >
                        Remove
                    </button>

                </div>

            </div>

        </template>


        <div class="pt-4">

            <button
                type="button"
                @click="addExpense()"
                class="text-sm font-medium text-blue-600 transition hover:text-blue-700"
            >
                + Add expense line
            </button>

        </div>

    </x-card>


    {{-- ========================================================= --}}
    {{-- Form Actions --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

        <a
            href="{{ route('vehicles.show', $vehicle) }}"
            class="inline-flex items-center justify-center rounded-lg border border-gray-300
                   bg-white px-5 py-2.5 text-sm font-medium text-gray-700
                   transition hover:bg-gray-50"
        >
            Cancel
        </a>


        <x-button
            type="submit"
            variant="primary"
            size="md"
        >
            Update Purchase
        </x-button>

    </div>

</form>


{{-- ========================================================= --}}
{{-- Existing Alpine Logic — DO NOT CHANGE --}}
{{-- ========================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
function purchaseForm(initialExpenses, initialPurchaseRate, initialCommission) {
    return {
        referenceType: '{{ $vehicle->purchase->reference_type ?? 'direct' }}',

        purchaseRate: initialPurchaseRate,

        commission: initialCommission,

        expenses: (initialExpenses && initialExpenses.length)
            ? initialExpenses
            : [
                {
                    category: 'engine',
                    amount: 0,
                    percentage: null
                }
            ],

        get expensesTotal() {
            return this.expenses.reduce(
                (sum, r) => sum + (Number(r.amount) || 0),
                0
            );
        },

        get netRate() {
            return (Number(this.purchaseRate) || 0)
                + (Number(this.commission) || 0)
                + this.expensesTotal;
        },

        addExpense() {
            this.expenses.push({
                category: 'other',
                amount: 0,
                percentage: null
            });
        },

        removeExpense(i) {
            this.expenses.splice(i, 1);
        },

        recalc() {},
    }
}
</script>

@endsection