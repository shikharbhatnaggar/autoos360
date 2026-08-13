@extends('layouts.app')

@section('page-title', 'Vehicle Details')

@section('content')

{{-- ========================================================= --}}
{{-- Page Header --}}
{{-- ========================================================= --}}

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>
        <div class="flex flex-wrap items-center gap-3">

            <h1 class="text-2xl font-bold text-gray-800">
                {{ $vehicle->sr_no }}
            </h1>

            <x-badge
                :variant="$vehicle->isSold() ? 'success' : 'warning'"
            >
                {{ $vehicle->isSold() ? 'Sold' : 'In Stock' }}
            </x-badge>

        </div>

        <p class="mt-1 text-sm text-gray-500">
            {{ $vehicle->vehicle_no }}
            <span class="mx-1 text-gray-300">•</span>
            {{ $vehicle->model }}
        </p>
    </div>

    <div class="flex items-center gap-2">

        <a
            href="{{ route('vehicles.edit', $vehicle) }}"
            class="inline-flex items-center justify-center rounded-lg border border-gray-300
                   bg-white px-4 py-2 text-sm font-medium text-gray-700
                   transition hover:bg-gray-50"
        >
            Edit Vehicle
        </a>

        <a
            href="{{ route('vehicles.index') }}"
            class="inline-flex items-center justify-center rounded-lg bg-blue-600
                   px-4 py-2 text-sm font-medium text-white
                   transition hover:bg-blue-700"
        >
            Back to Vehicles
        </a>

    </div>

</div>


{{-- ========================================================= --}}
{{-- Vehicle Summary --}}
{{-- ========================================================= --}}

<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">

    {{-- Vehicle Number --}}
    <x-card>

        <div class="text-xs font-medium uppercase tracking-wide text-gray-400">
            Vehicle No.
        </div>

        <div class="mt-2 text-lg font-semibold text-gray-800">
            {{ $vehicle->vehicle_no }}
        </div>

    </x-card>


    {{-- Model --}}
    <x-card>

        <div class="text-xs font-medium uppercase tracking-wide text-gray-400">
            Model
        </div>

        <div class="mt-2 text-lg font-semibold text-gray-800">
            {{ $vehicle->model }}
        </div>

    </x-card>


    {{-- Branch --}}
    <x-card>

        <div class="text-xs font-medium uppercase tracking-wide text-gray-400">
            Branch
        </div>

        <div class="mt-2 text-lg font-semibold text-gray-800">
            {{ $vehicle->branch->name }}
        </div>

    </x-card>

</div>


{{-- ========================================================= --}}
{{-- Purchase / Sale --}}
{{-- ========================================================= --}}

<div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

    {{-- ===================================================== --}}
    {{-- Purchase / Seller --}}
    {{-- ===================================================== --}}

    <x-card>

        <x-slot:header>

            <div class="flex items-center justify-between">

                <div>
                    <h2 class="text-base font-semibold text-gray-800">
                        Purchase Information
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Seller and purchase details
                    </p>
                </div>

                <a
                    href="{{ route('vehicles.edit', $vehicle) }}"
                    class="text-sm font-medium text-blue-600 hover:text-blue-700"
                >
                    Edit
                </a>

            </div>

        </x-slot:header>


        {{-- Purchase Details --}}
        <dl class="divide-y divide-gray-100 text-sm">

            <div class="flex items-center justify-between gap-4 py-3">
                <dt class="text-gray-500">
                    Model
                </dt>

                <dd class="font-medium text-gray-800 text-right">
                    {{ $vehicle->model }}
                </dd>
            </div>


            <div class="flex items-center justify-between gap-4 py-3">
                <dt class="text-gray-500">
                    Seller
                </dt>

                <dd class="font-medium text-gray-800 text-right">
                    {{ $vehicle->purchase->seller_name }}
                </dd>
            </div>


            <div class="flex items-center justify-between gap-4 py-3">
                <dt class="text-gray-500">
                    Mobile
                </dt>

                <dd class="font-medium text-gray-800 text-right">
                    {{ $vehicle->purchase->seller_mobile ?: '—' }}
                </dd>
            </div>


            <div class="flex items-center justify-between gap-4 py-3">

                <dt class="text-gray-500">
                    Reference
                </dt>

                <dd class="font-medium text-gray-800 text-right">

                    {{ ucfirst($vehicle->purchase->reference_type) }}

                    @if($vehicle->purchase->broker)
                        <span class="text-gray-400">
                            — {{ $vehicle->purchase->broker->name }}
                        </span>
                    @endif

                </dd>

            </div>


            <div class="flex items-center justify-between gap-4 py-3">

                <dt class="text-gray-500">
                    D.O.P
                </dt>

                <dd class="font-medium text-gray-800 text-right">
                    {{ $vehicle->purchase->purchase_date->format('d-m-Y') }}
                </dd>

            </div>


            {{-- Purchase Rate --}}
            <div class="flex items-center justify-between gap-4 border-t border-gray-200 py-3">

                <dt class="text-gray-500">
                    Purchase Rate
                </dt>

                <dd class="font-medium text-gray-800">
                    ₹{{ number_format($vehicle->purchase->purchase_rate, 2) }}
                </dd>

            </div>


            {{-- Commission --}}
            <div class="flex items-center justify-between gap-4 py-3">

                <dt class="text-gray-500">
                    + Commission
                </dt>

                <dd class="font-medium text-gray-800">
                    ₹{{ number_format($vehicle->purchase->commission, 2) }}
                </dd>

            </div>


            {{-- Expenses --}}
            <div class="flex items-center justify-between gap-4 py-3">

                <dt class="text-gray-500">
                    + Expenses
                </dt>

                <dd class="font-medium text-gray-800">
                    ₹{{ number_format($vehicle->purchase->expenses_total, 2) }}
                </dd>

            </div>


            {{-- Net Rate --}}
            <div class="flex items-center justify-between gap-4 border-t border-gray-200 py-4">

                <dt class="font-semibold text-gray-800">
                    Net Rate
                </dt>

                <dd class="text-lg font-bold text-gray-900">
                    ₹{{ number_format($vehicle->purchase->net_rate, 2) }}
                </dd>

            </div>

        </dl>


        {{-- Expense Detail --}}
        <div class="mt-6">

            <div class="mb-3 flex items-center justify-between">

                <div>
                    <h3 class="text-sm font-semibold text-gray-800">
                        Expense Detail
                    </h3>

                    <p class="mt-0.5 text-xs text-gray-400">
                        Additional costs associated with this vehicle
                    </p>
                </div>

            </div>


            <div class="overflow-hidden rounded-lg border border-gray-200">

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Category
                                </th>

                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Amount
                                </th>

                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    %
                                </th>

                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">

                            @forelse($vehicle->expenses as $expense)

                                <tr class="transition hover:bg-gray-50">

                                    <td class="px-4 py-3 font-medium text-gray-700">

                                        {{ \App\Models\VehicleExpense::CATEGORIES[$expense->category] ?? $expense->category }}

                                    </td>

                                    <td class="px-4 py-3 text-right font-medium text-gray-800">

                                        ₹{{ number_format($expense->amount, 2) }}

                                    </td>

                                    <td class="px-4 py-3 text-right text-gray-400">

                                        {{ $expense->percentage ? $expense->percentage.'%' : '—' }}

                                    </td>

                                    <td class="px-4 py-3 text-right">

                                        <form
                                            method="POST"
                                            action="{{ route('vehicles.expenses.destroy', [$vehicle, $expense]) }}"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-xs font-medium text-red-600 transition hover:text-red-700"
                                            >
                                                Remove
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="4"
                                        class="px-4 py-6 text-center text-sm text-gray-400"
                                    >
                                        No expenses recorded.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="mt-5">

            <a
                href="{{ route('vehicles.edit', $vehicle) }}"
                class="text-sm font-medium text-blue-600 hover:text-blue-700"
            >
                Edit purchase details →
            </a>

        </div>

    </x-card>


    {{-- ===================================================== --}}
    {{-- Sale / Purchaser --}}
    {{-- ===================================================== --}}

    <x-card>

        <x-slot:header>

            <div class="flex items-center justify-between">

                <div>
                    <h2 class="text-base font-semibold text-gray-800">
                        Sale Information
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Purchaser and sale details
                    </p>
                </div>

                @if($vehicle->sale)

                    <x-badge variant="success">
                        Sold
                    </x-badge>

                @else

                    <x-badge variant="warning">
                        Not Sold
                    </x-badge>

                @endif

            </div>

        </x-slot:header>


        @if($vehicle->sale)

            <dl class="divide-y divide-gray-100 text-sm">

                <div class="flex items-center justify-between gap-4 py-3">

                    <dt class="text-gray-500">
                        Purchaser
                    </dt>

                    <dd class="font-medium text-gray-800 text-right">
                        {{ $vehicle->sale->purchaser_name }}
                    </dd>

                </div>


                <div class="flex items-center justify-between gap-4 py-3">

                    <dt class="text-gray-500">
                        Mobile
                    </dt>

                    <dd class="font-medium text-gray-800 text-right">
                        {{ $vehicle->sale->purchaser_mobile ?: '—' }}
                    </dd>

                </div>


                <div class="flex items-center justify-between gap-4 py-3">

                    <dt class="text-gray-500">
                        Reference / Medium
                    </dt>

                    <dd class="font-medium text-gray-800 text-right">
                        {{ $vehicle->sale->reference_medium }}
                    </dd>

                </div>


                <div class="flex items-center justify-between gap-4 py-3">

                    <dt class="text-gray-500">
                        D.O.S
                    </dt>

                    <dd class="font-medium text-gray-800 text-right">
                        {{ $vehicle->sale->sale_date->format('d-m-Y') }}
                    </dd>

                </div>


                <div class="flex items-center justify-between gap-4 border-t border-gray-200 py-3">

                    <dt class="text-gray-500">
                        Sale Rate
                    </dt>

                    <dd class="font-medium text-gray-800">
                        ₹{{ number_format($vehicle->sale->sale_rate, 2) }}
                    </dd>

                </div>


                <div class="flex items-center justify-between gap-4 py-3">

                    <dt class="text-gray-500">
                        − Commission
                    </dt>

                    <dd class="font-medium text-gray-800">
                        ₹{{ number_format($vehicle->sale->commission, 2) }}
                    </dd>

                </div>


                <div class="flex items-center justify-between gap-4 py-3">

                    <dt class="font-semibold text-gray-800">
                        Net Rate
                    </dt>

                    <dd class="font-semibold text-gray-900">
                        ₹{{ number_format($vehicle->sale->net_rate, 2) }}
                    </dd>

                </div>


                {{-- Profit / Loss --}}
                <div class="mt-2 flex items-center justify-between gap-4 rounded-lg
                    {{ $vehicle->sale->profit_loss >= 0 ? 'bg-green-50' : 'bg-red-50' }}
                    px-4 py-4"
                >

                    <dt class="font-semibold
                        {{ $vehicle->sale->profit_loss >= 0 ? 'text-green-700' : 'text-red-700' }}"
                    >
                        Profit / Loss
                    </dt>

                    <dd class="text-xl font-bold
                        {{ $vehicle->sale->profit_loss >= 0 ? 'text-green-700' : 'text-red-700' }}"
                    >
                        ₹{{ number_format($vehicle->sale->profit_loss, 2) }}
                    </dd>

                </div>

            </dl>


            <div class="mt-6">

                <a
                    href="{{ route('vehicles.sale.edit', $vehicle) }}"
                    class="inline-flex items-center justify-center rounded-lg bg-blue-600
                           px-4 py-2 text-sm font-medium text-white
                           transition hover:bg-blue-700"
                >
                    Edit Sale
                </a>

            </div>

        @else

            <div class="flex flex-col items-center justify-center rounded-lg border border-dashed border-gray-300
                        bg-gray-50 px-6 py-12 text-center">

                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100">

                    <x-heroicon-o-shopping-cart class="h-6 w-6 text-yellow-600"/>

                </div>

                <h3 class="mt-4 text-sm font-semibold text-gray-800">
                    Vehicle not sold yet
                </h3>

                <p class="mt-1 max-w-sm text-sm text-gray-500">
                    Record the sale once this vehicle has been sold to a customer.
                </p>

                <a
                    href="{{ route('vehicles.sale.create', $vehicle) }}"
                    class="mt-5 inline-flex items-center justify-center rounded-lg bg-blue-600
                           px-4 py-2 text-sm font-medium text-white
                           transition hover:bg-blue-700"
                >
                    Record Sale
                </a>

            </div>

        @endif

    </x-card>

</div>

@endsection