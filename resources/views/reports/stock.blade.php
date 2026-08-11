@extends('layouts.app')

@section('page-title', 'Stock')

@section('content')

{{-- ========================================================= --}}
{{-- Page Header --}}
{{-- ========================================================= --}}

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between print:hidden">

    <x-section-header
        title="Our Stock"
        subtitle="View vehicles currently available in inventory"
    />

    <div class="flex items-center gap-3">

        <button
            type="button"
            onclick="window.print()"
            class="inline-flex items-center gap-2 rounded-lg bg-slate-800
                   px-4 py-2.5 text-sm font-medium text-white
                   transition hover:bg-slate-900
                   focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2"
        >
            <x-heroicon-o-printer class="h-4 w-4"/>
            Print
        </button>

        <a
            href="{{ route('vehicles.index') }}"
            class="inline-flex items-center rounded-lg border border-gray-300
                   bg-white px-4 py-2.5 text-sm font-medium text-gray-700
                   transition hover:bg-gray-50"
        >
            Back to Vehicles
        </a>

    </div>

</div>


{{-- ========================================================= --}}
{{-- Print Header --}}
{{-- ========================================================= --}}

<div class="mb-6 hidden print:block">

    <h1 class="text-xl font-bold text-gray-900">
        Stock Report
    </h1>

    <p class="mt-1 text-sm text-gray-500">
        As of {{ now()->format('d-m-Y') }}
    </p>

</div>


{{-- ========================================================= --}}
{{-- Filters --}}
{{-- ========================================================= --}}

{{-- ReportService::stockInHand() doesn't currently return a branch list,
     so this remains a plain branch_id filter. --}}

<form
    method="GET"
    class="mb-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm print:hidden"
>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end">

        <div class="w-full sm:w-48">

            <label
                for="branch_id"
                class="mb-2 block text-sm font-medium text-gray-700"
            >
                Branch ID
            </label>

            <input
                id="branch_id"
                type="text"
                name="branch_id"
                value="{{ request('branch_id') }}"
                placeholder="All branches"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5
                       text-sm text-gray-700 placeholder-gray-400
                       transition focus:border-blue-500 focus:outline-none
                       focus:ring-2 focus:ring-blue-500/20"
            >

        </div>


        <div class="flex items-center gap-3">

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-lg
                       bg-blue-600 px-4 py-2.5 text-sm font-medium text-white
                       transition hover:bg-blue-700
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                <x-heroicon-o-funnel class="h-4 w-4"/>
                Filter
            </button>


            @if(request('branch_id'))

                <a
                    href="{{ route('reports.stock') }}"
                    class="inline-flex items-center rounded-lg px-3 py-2.5
                           text-sm font-medium text-gray-500
                           transition hover:bg-gray-100 hover:text-gray-700"
                >
                    Clear
                </a>

            @endif

        </div>

    </div>

</form>


{{-- ========================================================= --}}
{{-- Summary Cards --}}
{{-- ========================================================= --}}

<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">

    {{-- Vehicles in Stock --}}
    <x-card>

        <div class="flex items-center justify-between gap-3">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Vehicles in Stock
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


    {{-- Capital Locked --}}
    <x-card>

        <div class="flex items-center justify-between gap-3">

            <div>

                <p class="text-sm font-medium text-gray-500">
                    Capital Locked
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-900">
                    ₹{{ number_format($capital_locked, 2) }}
                </p>

            </div>

            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-50">

                <x-heroicon-o-banknotes class="h-6 w-6 text-indigo-600"/>

            </div>

        </div>

    </x-card>

</div>


{{-- ========================================================= --}}
{{-- Stock Table --}}
{{-- ========================================================= --}}

<x-card>

    <x-slot:header>

        <div>

            <h2 class="text-base font-semibold text-gray-800">
                Vehicles in Stock
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                {{ $count }}
                {{ Str::plural('vehicle', $count) }}
                currently available
            </p>

        </div>

    </x-slot:header>


    <div class="overflow-x-auto">

        <table class="w-full min-w-[1100px] text-sm">

            <thead>

                <tr class="border-b border-gray-200 bg-gray-50/70">

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        SR No.
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Vehicle No.
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Model
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Branch
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Seller
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        D.O.P
                    </th>

                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Days in Stock
                    </th>

                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Net Rate
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-gray-100">

            @forelse($vehicles as $v)

                <tr class="transition hover:bg-gray-50">

                    {{-- SR No --}}
                    <td class="px-5 py-4">

                        <a
                            href="{{ route('vehicles.show', $v) }}"
                            class="font-medium text-blue-600 transition hover:text-blue-700
                                   print:text-black print:no-underline"
                        >
                            {{ $v->sr_no }}
                        </a>

                    </td>


                    {{-- Vehicle No --}}
                    <td class="px-5 py-4 font-medium text-gray-800">

                        {{ $v->vehicle_no }}

                    </td>


                    {{-- Model --}}
                    <td class="px-5 py-4 text-gray-600">

                        {{ $v->model }}

                    </td>


                    {{-- Branch --}}
                    <td class="px-5 py-4 text-gray-600">

                        {{ $v->branch->name }}

                    </td>


                    {{-- Seller --}}
                    <td class="px-5 py-4 text-gray-600">

                        {{ $v->purchase->seller_name }}

                    </td>


                    {{-- Date of Purchase --}}
                    <td class="px-5 py-4 whitespace-nowrap text-gray-600">

                        {{ $v->purchase->purchase_date->format('d-m-Y') }}

                    </td>


                    {{-- Days in Stock --}}
                    <td class="px-5 py-4 text-right">

                        <span
                            class="inline-flex rounded-md bg-gray-100 px-2.5 py-1
                                   text-xs font-semibold text-gray-700"
                        >
                            {{ $v->purchase->purchase_date->diffInDays(now()) }} days
                        </span>

                    </td>


                    {{-- Net Rate --}}
                    <td class="px-5 py-4 text-right font-semibold text-gray-800">

                        ₹{{ number_format($v->purchase->net_rate ?? 0, 2) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="8"
                        class="px-5 py-12 text-center"
                    >

                        <div class="mx-auto flex max-w-sm flex-col items-center">

                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100"
                            >
                                <x-heroicon-o-truck
                                    class="h-6 w-6 text-gray-400"
                                />
                            </div>

                            <h3 class="mt-4 text-sm font-semibold text-gray-800">
                                No vehicles in stock
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                There are currently no vehicles matching this stock report.
                            </p>

                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>


            {{-- ================================================= --}}
            {{-- Total --}}
            {{-- ================================================= --}}

            @if($count)

                <tfoot>

                    <tr class="border-t border-gray-200 bg-gray-50 font-semibold">

                        <td
                            class="px-5 py-4 text-gray-700"
                            colspan="7"
                        >
                            Total ({{ $count }} vehicles)
                        </td>

                        <td class="px-5 py-4 text-right text-gray-900">

                            ₹{{ number_format($capital_locked, 2) }}

                        </td>

                    </tr>

                </tfoot>

            @endif

        </table>

    </div>

</x-card>


{{-- ========================================================= --}}
{{-- Print Styles --}}
{{-- ========================================================= --}}

<style>
@media print {

    a {
        color: inherit !important;
        text-decoration: none !important;
    }

    body {
        background: white !important;
    }

    .shadow,
    .shadow-sm,
    .shadow-md,
    .shadow-lg {
        box-shadow: none !important;
    }

}
</style>

@endsection
