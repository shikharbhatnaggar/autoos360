@extends('layouts.app')

@section('page-title', 'Edit Sale — ' . $vehicle->sr_no)

@section('content')

<div class="space-y-6">

    {{-- ============================================================
        PAGE HEADER
    ============================================================= --}}

    <div>
        <h1 class="text-2xl font-semibold text-gray-900">
            Edit Sale — {{ $vehicle->sr_no }}
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            {{ $vehicle->vehicle_no }}
        </p>
    </div>


    {{-- ============================================================
        PURCHASE INFORMATION
    ============================================================= --}}

    <div class="bg-blue-50 text-blue-800 text-sm rounded-lg px-4 py-3">

        Purchase Net Rate:
        <span class="font-semibold">
            ₹{{ number_format($vehicle->purchase->net_rate ?? 0, 2) }}
        </span>

        — this is the cost basis used for Profit/Loss.

    </div>


    {{-- ============================================================
        EDIT SALE FORM
    ============================================================= --}}

    <form
        method="POST"
        action="{{ route('vehicles.sale.update', $vehicle) }}"
        x-data="saleForm(
            {{ (float) ($vehicle->purchase->net_rate ?? 0) }},
            {{ (float) ($vehicle->sale->sale_rate ?? 0) }},
            {{ (float) ($vehicle->sale->commission ?? 0) }}
        )"
        class="bg-white rounded-lg shadow p-4 space-y-6 sm:p-6"
    >

        @csrf
        @method('PUT')


        {{-- ========================================================
            PURCHASER DETAILS
        ========================================================= --}}

        <div>

            <h2 class="text-base font-semibold text-gray-900 mb-4">
                Purchaser Details
            </h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                {{-- Purchaser Name --}}
                <div>

                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Purchaser Name
                    </label>

                    <input
                        type="text"
                        name="purchaser_name"
                        value="{{ old('purchaser_name', $vehicle->sale->purchaser_name ?? '') }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >

                    @error('purchaser_name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                </div>


                {{-- Address --}}
                <div>

                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Address
                    </label>

                    <input
                        type="text"
                        name="purchaser_address"
                        value="{{ old('purchaser_address', $vehicle->sale->purchaser_address ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >

                    @error('purchaser_address')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                </div>


                {{-- Mobile --}}
                <div>

                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Mobile
                    </label>

                    <input
                        type="text"
                        name="purchaser_mobile"
                        value="{{ old('purchaser_mobile', $vehicle->sale->purchaser_mobile ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                    >

                    @error('purchaser_mobile')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                </div>


                {{-- Reference --}}
                <div>

                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Reference
                        <span class="font-normal text-gray-400">
                            (Branch Name / Medium of Sale)
                        </span>
                    </label>

                    <input
                        type="text"
                        name="reference_medium"
                        value="{{ old('reference_medium', $vehicle->sale->reference_medium ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                    >

                    @error('reference_medium')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                </div>


                {{-- Date of Sale --}}
                <div>

                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        D.O.S (Date of Sale)
                    </label>

                    <input
                        type="date"
                        name="sale_date"
                        value="{{ old('sale_date', optional($vehicle->sale->sale_date)->format('Y-m-d')) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                    >

                    @error('sale_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                </div>

            </div>

        </div>


        {{-- ========================================================
            SALE DETAILS
        ========================================================= --}}

        <div class="border-t pt-6">

            <h2 class="text-base font-semibold text-gray-900 mb-4">
                Sale Details
            </h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                {{-- Sale Rate --}}
                <div>

                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Sale Rate
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="sale_rate"
                        x-model.number="saleRate"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                    >

                    @error('sale_rate')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                </div>


                {{-- Commission --}}
                <div>

                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        − Commission
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="commission"
                        x-model.number="commission"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                    >

                    @error('commission')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                </div>

            </div>

        </div>


        {{-- ========================================================
            PROFIT / LOSS
        ========================================================= --}}

        <div class="border-t pt-5 text-right space-y-1">

            <p class="text-sm text-gray-500">

                Net Rate:

                <span
                    class="font-semibold text-slate-800"
                    x-text="'₹' + netRate.toFixed(2)"
                ></span>

            </p>


            <p
                class="text-lg font-bold"
                :class="profitLoss >= 0
                    ? 'text-green-600'
                    : 'text-red-600'"
            >

                Profit/Loss:

                <span
                    x-text="'₹' + profitLoss.toFixed(2)"
                ></span>

            </p>

        </div>


        {{-- ========================================================
            ACTIONS
        ========================================================= --}}

        <div class="flex justify-end gap-3 border-t pt-5">

            <a
                href="{{ route('vehicles.show', $vehicle) }}"
                class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="bg-slate-800 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-slate-900"
            >
                Update Sale
            </button>

        </div>

    </form>

</div>


{{-- ================================================================
    ALPINE SALE CALCULATION
================================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>

function saleForm(purchaseNetRate, initialSaleRate, initialCommission) {

    return {

        saleRate: Number(initialSaleRate) || 0,

        commission: Number(initialCommission) || 0,

        get netRate() {
            return (
                (Number(this.saleRate) || 0) -
                (Number(this.commission) || 0)
            );
        },

        get profitLoss() {
            return this.netRate - purchaseNetRate;
        }

    }

}

</script>

@endsection