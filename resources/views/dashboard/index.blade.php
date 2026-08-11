@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')

<div class="space-y-6">

    {{-- ============================================================
        PRIMARY STATISTICS
    ============================================================= --}}

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

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
            valueClass="text-xl">
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


        {{-- Total Expense --}}
        <x-stat-card
            title="Total Expenses"
            :value="'₹' . number_format($totalExpense)"
            valueClass="text-xl"
        >
            <x-slot:icon>
                <x-heroicon-o-receipt-percent class="w-6 h-6"/>
            </x-slot:icon>
        </x-stat-card>

        <x-stat-card
            title="Profit"
            :value="'₹'.number_format($monthProfit)"
            color="{{ $monthProfit >= 0 ? 'green' : 'red' }}"
            valueClass="text-xl">

            <x-slot:icon>
                <x-heroicon-o-chart-bar class="w-6 h-6"/>
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


        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead>

                    <tr class="border-b border-gray-200">

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Branch
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Available
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Purchase
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Sale
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Expense
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($branchStats as $branch)

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $branch['name'] }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                {{ number_format($branch['stock']) }}
                            </td>

                            <td class="px-6 py-4 text-right font-medium">
                                ₹{{ number_format($branch['purchase']) }}
                            </td>

                            <td class="px-6 py-4 text-right font-medium">
                                ₹{{ number_format($branch['sale']) }}
                            </td>

                            <td class="px-6 py-4 text-right font-medium">
                                ₹{{ number_format($branch['expense']) }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-8 text-center text-sm text-gray-500">

                                No branch statistics available.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </x-card>


    {{-- ============================================================
        RECENT VEHICLES
    ============================================================= --}}

    <x-card>

        <x-slot:header>

            <div class="flex w-full flex-wrap items-center justify-between gap-3">

                <h2 class="font-semibold text-gray-900">
                    Recent Vehicles
                </h2>

                <a
                    href="{{ route('vehicles.create') }}"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">

                    Add Purchase

                </a>

            </div>

        </x-slot:header>


        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead>

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            SR No
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Vehicle No
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Model
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Branch
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Net Rate
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($recent as $vehicle)

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-6 py-4">

                                <a
                                    href="{{ route('vehicles.show', $vehicle) }}"
                                    class="font-medium text-blue-600 hover:text-blue-700">

                                    {{ $vehicle->sr_no }}

                                </a>

                            </td>


                            <td class="px-6 py-4 text-gray-700">
                                {{ $vehicle->vehicle_no }}
                            </td>


                            <td class="px-6 py-4 text-gray-700">
                                {{ $vehicle->model }}
                            </td>


                            <td class="px-6 py-4 text-gray-700">
                                {{ $vehicle->branch->name }}
                            </td>


                            <td class="px-6 py-4">

                                @if($vehicle->status == 'sold')

                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Sold
                                    </span>

                                @else

                                    <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                                        In Stock
                                    </span>

                                @endif

                            </td>


                            <td class="px-6 py-4 text-right font-semibold">

                                ₹{{ number_format($vehicle->purchase->net_rate ?? 0) }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-8 text-center text-sm text-gray-500">

                                No vehicles found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </x-card>

</div>

@endsection