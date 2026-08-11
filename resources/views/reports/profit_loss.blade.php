@extends('layouts.app')

@section('page-title', 'Profit & Loss')

@section('content')

<!-- <x-section-header
    title="Profit & Loss"
    subtitle="Analyze vehicle sales performance and profitability"
/> -->


{{-- ========================================================= --}}
{{-- Summary Statistics --}}
{{-- ========================================================= --}}

<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">

    {{-- Vehicles Sold --}}
    <x-card>

        <div class="flex items-center justify-between gap-3">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Vehicles Sold
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-900">
                    {{ $count }}
                </p>

            </div>

            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-50">
                <x-heroicon-o-truck class="h-6 w-6 text-blue-600"/>
            </div>

        </div>

    </x-card>


    {{-- Total Sales --}}
    <x-card>

        <div class="flex items-center justify-between gap-3">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Total Sales Value
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-900">
                    ₹{{ number_format($total_sales_value, 0) }}
                </p>

            </div>

            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-50">
                <x-heroicon-o-banknotes class="h-6 w-6 text-indigo-600"/>
            </div>

        </div>

    </x-card>


    {{-- Total Profit --}}
    <x-card>

        <div class="flex items-center justify-between gap-3">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Total Profit
                </p>

                <p
                    class="mt-2 text-2xl font-bold
                    {{ $total_profit >= 0 ? 'text-green-600' : 'text-red-600' }}"
                >
                    ₹{{ number_format($total_profit, 0) }}
                </p>

            </div>

            <div
                class="flex h-11 w-11 items-center justify-center rounded-lg
                {{ $total_profit >= 0 ? 'bg-green-50' : 'bg-red-50' }}"
            >

                @if($total_profit >= 0)

                    <x-heroicon-o-arrow-trending-up class="h-6 w-6 text-green-600"/>

                @else

                    <x-heroicon-o-arrow-trending-down class="h-6 w-6 text-red-600"/>

                @endif

            </div>

        </div>

    </x-card>

</div>


{{-- ========================================================= --}}
{{-- Profit & Loss Details --}}
{{-- ========================================================= --}}

<x-card>

    <x-slot:header>

        <div>

            <h2 class="text-base font-semibold text-gray-800">
                Vehicle Profit & Loss
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Purchase cost, sale value and resulting profit or loss
            </p>

        </div>

    </x-slot:header>


    <div class="overflow-x-auto">

        <table class="w-full min-w-[850px] text-sm">

            <thead>

                <tr class="border-b border-gray-200 bg-gray-50/70">

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        SR No.
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Vehicle
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Branch
                    </th>

                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Purchase Net
                    </th>

                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Sale Net
                    </th>

                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                        P&amp;L
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-gray-100">

            @forelse($vehicles as $v)

                <tr class="transition hover:bg-gray-50">

                    {{-- SR No --}}
                    <td class="px-5 py-4">

                        <span class="font-medium text-gray-800">
                            {{ $v->sr_no }}
                        </span>

                    </td>


                    {{-- Vehicle --}}
                    <td class="px-5 py-4">

                        <div class="font-medium text-gray-800">
                            {{ $v->vehicle_no }}
                        </div>

                        <div class="mt-0.5 text-xs text-gray-500">
                            {{ $v->model }}
                        </div>

                    </td>


                    {{-- Branch --}}
                    <td class="px-5 py-4 text-gray-600">

                        {{ $v->branch->name }}

                    </td>


                    {{-- Purchase --}}
                    <td class="px-5 py-4 text-right font-medium text-gray-700">

                        ₹{{ number_format($v->purchase->net_rate, 0) }}

                    </td>


                    {{-- Sale --}}
                    <td class="px-5 py-4 text-right font-medium text-gray-700">

                        ₹{{ number_format($v->sale->net_rate, 0) }}

                    </td>


                    {{-- Profit / Loss --}}
                    <td class="px-5 py-4 text-right">

                        <span
                            class="inline-flex items-center rounded-lg px-3 py-1.5
                            text-sm font-semibold
                            {{ $v->sale->profit_loss >= 0
                                ? 'bg-green-50 text-green-700'
                                : 'bg-red-50 text-red-700' }}"
                        >
                            @if($v->sale->profit_loss >= 0)
                                +
                            @endif

                            ₹{{ number_format($v->sale->profit_loss, 0) }}
                        </span>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="6"
                        class="px-5 py-12 text-center"
                    >

                        <div class="mx-auto flex max-w-sm flex-col items-center">

                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">

                                <x-heroicon-o-chart-bar
                                    class="h-6 w-6 text-gray-400"
                                />

                            </div>

                            <h3 class="mt-4 text-sm font-semibold text-gray-800">
                                No sales data
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                There are no sold vehicles available for this report.
                            </p>

                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</x-card>

@endsection
