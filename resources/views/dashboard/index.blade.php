@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')

<div class="space-y-6">

    {{-- ============================================================
        PRIMARY STATISTICS
    ============================================================= --}}

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

    {{-- Available Vehicles --}}
    <x-stat-card
        title="Available Vehicles"
        :value="number_format($inStockCount)"
        valueClass="text-xl"
    >
        <x-slot:icon>
            <x-heroicon-o-truck class="w-6 h-6"/>
        </x-slot:icon>
    </x-stat-card>


    {{-- Total Purchase --}}
    <x-stat-card
        title="Total Purchase"
        :value="'₹' . number_format($totalPurchase)"
        valueClass="text-xl"
    >
        <x-slot:icon>
            <x-heroicon-o-shopping-cart class="w-6 h-6"/>
        </x-slot:icon>
    </x-stat-card>


    {{-- Total Sale --}}
    <x-stat-card
        title="Total Sale"
        :value="'₹' . number_format($totalSale)"
        valueClass="text-xl"
    >
        <x-slot:icon>
            <x-heroicon-o-banknotes class="w-6 h-6"/>
        </x-slot:icon>
    </x-stat-card>


    {{-- Total Expenses --}}
    <x-stat-card
        title="Total Expenses"
        :value="'₹' . number_format($totalExpense)"
        valueClass="text-xl"
    >
        <x-slot:icon>
            <x-heroicon-o-receipt-percent class="w-6 h-6"/>
        </x-slot:icon>
    </x-stat-card>


    {{-- Profit 
    <x-stat-card
        title="Profit"
        :value="'₹' . number_format($monthProfit)"
        color="{{ $monthProfit >= 0 ? 'green' : 'red' }}"
        valueClass="text-xl"
    >
        <x-slot:icon>
            <x-heroicon-o-chart-bar class="w-6 h-6"/>
        </x-slot:icon>
    </x-stat-card> --}}


    {{-- RC Left --}}
    <x-stat-card
        title="RC Left"
        :value="number_format($rcLeft)"
        color="{{ $rcLeft > 0 ? 'red' : 'green' }}"
        valueClass="text-xl"
    >
        <x-slot:icon>
            <x-heroicon-o-document-text class="w-6 h-6"/>
        </x-slot:icon>
    </x-stat-card>


    {{-- Insurance Left --}}
    <x-stat-card
        title="Insurance Left"
        :value="number_format($insuranceLeft)"
        color="{{ $insuranceLeft > 0 ? 'red' : 'green' }}"
        valueClass="text-xl"
    >
        <x-slot:icon>
            <x-heroicon-o-shield-check class="w-6 h-6"/>
        </x-slot:icon>
    </x-stat-card>


    {{-- Net Loss 
    <x-stat-card
        title="Net Loss"
        :value="'₹' . number_format($netLoss)"
        color="{{ $netLoss > 0 ? 'red' : 'green' }}"
        valueClass="text-xl"
    >
        <x-slot:icon>
            <x-heroicon-o-arrow-trending-down class="w-6 h-6"/>
        </x-slot:icon>
    </x-stat-card> --}}

    {{-- Sold Vehicles --}}
    <x-stat-card
        title="Sold Vehicles"
        :value="number_format($soldVehiclesCount)"
        valueClass="text-xl"
    >
        <x-slot:icon>
            <x-heroicon-o-check-badge class="w-6 h-6"/>
        </x-slot:icon>
    </x-stat-card>

    {{-- Net Profit / Loss --}}
    <x-stat-card
        title="Net Profit / Loss"
        :value="'₹' . number_format($netProfitLoss)"
        color="{{ $netProfitLoss >= 0 ? 'green' : 'red' }}"
        valueClass="text-xl"
    >
        <x-slot:icon>

            @if($netProfitLoss >= 0)

                <x-heroicon-o-arrow-trending-up class="w-6 h-6"/>

            @else

                <x-heroicon-o-arrow-trending-down class="w-6 h-6"/>

            @endif

        </x-slot:icon>
    </x-stat-card>

</div>


    {{-- ============================================================
        BROKER STATISTICS
    ============================================================= --}}

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

        {{-- Total Brokers --}}
        <x-stat-card
            title="Total Brokers"
            :value="number_format($totalBrokers)"
        >
            <x-slot:icon>
                <x-heroicon-o-user-group class="w-6 h-6"/>
            </x-slot:icon>
        </x-stat-card>


        {{-- Commission Paid --}}
        <x-stat-card
            title="Commission Paid"
            :value="'₹' . number_format($totalCommission)"
        >
            <x-slot:icon>
                <x-heroicon-o-currency-rupee class="w-6 h-6"/>
            </x-slot:icon>
        </x-stat-card>

    </div>


    {{-- ============================================================
        BRANCH STATISTICS
    ============================================================= --}}

    <x-card>

        <x-slot:header>

            <div>
                <h2 class="font-semibold text-gray-900">
                    Branch Statistics
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Inventory and financial summary by branch
                </p>
            </div>

        </x-slot:header>


        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">

            @forelse($branchStats as $branch)

                <div class="
                    rounded-xl
                    border border-gray-200
                    bg-white
                    p-5
                    shadow-sm
                    transition-all
                    duration-200
                    ease-out
                    hover:-translate-y-1
                    hover:border-gray-300
                    hover:shadow-md
                ">

                    {{-- Branch Header --}}
                    <div class="flex items-center justify-between">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                <x-heroicon-o-building-office-2 class="h-5 w-5"/>
                            </div>

                            <div>
                                <h3 class="font-semibold text-gray-900">
                                    {{ $branch['name'] }}
                                </h3>

                                <p class="text-xs text-gray-500">
                                    Branch Overview
                                </p>
                            </div>

                        </div>

                    </div>


                    {{-- Available Vehicles --}}
                    <div class="mt-5 flex items-center justify-between rounded-lg bg-gray-50 px-4 py-3">

                        <div class="flex items-center gap-2">

                            <x-heroicon-o-truck class="h-5 w-5 text-gray-500"/>

                            <span class="text-sm text-gray-600">
                                Available
                            </span>

                        </div>

                        <span class="text-lg font-semibold text-gray-900">
                            {{ number_format($branch['stock']) }}
                        </span>

                    </div>


                    {{-- Financial Statistics --}}
                    <div class="mt-4 grid grid-cols-3 gap-2">

                        {{-- Purchase --}}
                        <div class="rounded-lg bg-blue-50 p-3">

                            <p class="text-xs font-medium text-blue-600">
                                Purchase
                            </p>

                            <p class="mt-1 text-sm font-semibold text-blue-900">
                                ₹{{ number_format($branch['purchase']) }}
                            </p>

                        </div>


                        {{-- Sale --}}
                        <div class="rounded-lg bg-green-50 p-3">

                            <p class="text-xs font-medium text-green-600">
                                Sale
                            </p>

                            <p class="mt-1 text-sm font-semibold text-green-900">
                                ₹{{ number_format($branch['sale']) }}
                            </p>

                        </div>


                        {{-- Expense --}}
                        <div class="rounded-lg bg-red-50 p-3">

                            <p class="text-xs font-medium text-red-600">
                                Expense
                            </p>

                            <p class="mt-1 text-sm font-semibold text-red-900">
                                ₹{{ number_format($branch['expense']) }}
                            </p>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-span-full rounded-lg border border-dashed border-gray-300 py-10 text-center">

                    <x-heroicon-o-building-office-2 class="mx-auto h-8 w-8 text-gray-400"/>

                    <p class="mt-2 text-sm text-gray-500">
                        No branch statistics available.
                    </p>

                </div>

            @endforelse

        </div>

    </x-card>


    {{-- ============================================================
        RECENT VEHICLES
    ============================================================= --}}

    {{-- ============================================================
    RECENT VEHICLES
============================================================= --}}

<x-card>

    <x-slot:header>

        <div class="flex w-full flex-wrap items-center justify-between gap-3">

            <div>

                <h2 class="font-semibold text-gray-900">
                    Recent Vehicles
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Latest available and sold vehicles
                </p>

            </div>

            <a
                href="{{ route('vehicles.create') }}"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">

                Add Purchase

            </a>

        </div>

    </x-slot:header>


    {{-- ========================================================
        TWO SECTIONS
    ========================================================= --}}

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">


        {{-- ====================================================
            AVAILABLE STOCK
        ===================================================== --}}

        <div class="rounded-xl border border-gray-200 overflow-hidden">

            <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-5 py-4">

                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-yellow-100 text-yellow-600">

                        <x-heroicon-o-truck class="h-5 w-5"/>

                    </div>

                    <div>

                        <h3 class="font-semibold text-gray-900">
                            Available Stock
                        </h3>

                        <p class="text-xs text-gray-500">
                            Latest vehicles in inventory
                        </p>

                    </div>

                </div>

                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                    {{ $recentAvailable->count() }}
                </span>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead>

                        <tr class="border-b border-gray-100">

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Vehicle No
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Model
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Net Rate
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse($recentAvailable as $vehicle)

                            <tr class="group">

                                <td class="px-5 py-3 transition-colors duration-150 group-hover:bg-gray-50">

                                    <a
                                        href="{{ route('vehicles.show', $vehicle) }}"
                                        class="font-medium text-blue-600 hover:text-blue-700">

                                        {{ $vehicle->vehicle_no }}

                                    </a>

                                </td>


                                <td class="px-5 py-3 text-sm text-gray-700 transition-colors duration-150 group-hover:bg-gray-50">

                                    {{ $vehicle->model }}

                                </td>


                                <td class="px-5 py-3 text-right font-semibold text-gray-900 transition-colors duration-150 group-hover:bg-gray-50">

                                    ₹{{ number_format($vehicle->purchase->net_rate ?? 0) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="3"
                                    class="px-5 py-8 text-center text-sm text-gray-500">

                                    No available vehicles found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>



        {{-- ====================================================
            SOLD STOCK
        ===================================================== --}}

        <div class="rounded-xl border border-gray-200 overflow-hidden">

            <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-5 py-4">

                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-100 text-green-600">

                        <x-heroicon-o-check-circle class="h-5 w-5"/>

                    </div>

                    <div>

                        <h3 class="font-semibold text-gray-900">
                            Sold Stock
                        </h3>

                        <p class="text-xs text-gray-500">
                            Latest completed sales
                        </p>

                    </div>

                </div>

                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                    {{ $recentSold->count() }}
                </span>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead>

                        <tr class="border-b border-gray-100">

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Vehicle No
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Model
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Net Rate
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Sale Price
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Profit/Loss
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse($recentSold as $vehicle)

                            <tr class="group">

                                <td class="px-5 py-3 transition-colors duration-150 group-hover:bg-gray-50">

                                    <a
                                        href="{{ route('vehicles.show', $vehicle) }}"
                                        class="font-medium text-blue-600 hover:text-blue-700">

                                        {{ $vehicle->vehicle_no }}

                                    </a>

                                </td>


                                <td class="px-5 py-3 text-sm text-gray-700 transition-colors duration-150 group-hover:bg-gray-50">

                                    {{ $vehicle->model }}

                                </td>


                                <td class="px-5 py-3 text-right font-semibold text-gray-900 transition-colors duration-150 group-hover:bg-gray-50">

                                    ₹{{ number_format($vehicle->purchase->net_rate ?? 0) }}

                                </td>


                                <td class="px-5 py-3 text-right font-semibold text-gray-900 transition-colors duration-150 group-hover:bg-gray-50">

                                    ₹{{ number_format($vehicle->sale->net_rate ?? 0) }}

                                </td>


                                <td class="px-5 py-3 text-right font-semibold transition-colors duration-150 group-hover:bg-gray-50">

                                    @php
                                        $profitLoss = (float) ($vehicle->sale->profit_loss ?? 0);
                                    @endphp

                                    <span class="{{ $profitLoss >= 0 ? 'text-green-600' : 'text-red-600' }}">

                                        {{ $profitLoss >= 0 ? '+' : '' }}₹{{ number_format($profitLoss) }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="px-5 py-8 text-center text-sm text-gray-500">

                                    No sold vehicles found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-card>

</div>

@endsection