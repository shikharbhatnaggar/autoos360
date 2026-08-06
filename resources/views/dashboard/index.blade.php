@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('content')

<!-- <x-section-header
    title="Dashboard"
    subtitle="Business overview"/> -->

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <x-stat-card
        title="In Stock"
        :value="$inStockCount"
        color="blue">

        <x-slot:icon>
            <x-heroicon-o-truck class="w-6 h-6"/>
        </x-slot:icon>

    </x-stat-card>

    <x-stat-card
        title="Sold This Month"
        :value="$soldThisMonthCount"
        color="green">

        <x-slot:icon>
            <x-heroicon-o-banknotes class="w-6 h-6"/>
        </x-slot:icon>

    </x-stat-card>

    <x-stat-card
        title="Profit"
        :value="'₹'.number_format($monthProfit)"
        color="{{ $monthProfit >= 0 ? 'green' : 'red' }}">

        <x-slot:icon>
            <x-heroicon-o-chart-bar class="w-6 h-6"/>
        </x-slot:icon>

    </x-stat-card>

</div>


<x-card class="mt-6">

    <x-slot:header>

        <div class="flex items-center justify-between">

            <h2 class="font-semibold">

                Recent Vehicles

            </h2>

            <a
                href="{{ route('vehicles.create') }}"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">

                Add Purchase

            </a>

        </div>

    </x-slot:header>

    <x-table>

        <x-slot:head>

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

        </x-slot:head>

        @foreach($recent as $vehicle)

            <tr class="hover:bg-gray-50 transition">

                <td class="px-6 py-4">

                    <a
                        href="{{ route('vehicles.show',$vehicle) }}"
                        class="font-medium text-blue-600 hover:text-blue-700">

                        {{ $vehicle->sr_no }}

                    </a>

                </td>

                <td class="px-6 py-4">

                    {{ $vehicle->vehicle_no }}

                </td>

                <td class="px-6 py-4">

                    {{ $vehicle->model }}

                </td>

                <td class="px-6 py-4">

                    {{ $vehicle->branch->name }}

                </td>

                <td class="px-6 py-4">

                    @if($vehicle->status=='sold')

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

        @endforeach

    </x-table>

</x-card>
@endsection
