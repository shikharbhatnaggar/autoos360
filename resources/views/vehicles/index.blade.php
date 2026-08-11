@extends('layouts.app')
@section('page-title','Vehicles')
@section('content')

<x-card class="mt-6">

    <x-slot:header>

        <div class="flex flex-wrap items-center justify-between gap-3">

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


    <form method="GET"
        class="mb-6 rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-4 p-4 lg:flex-row lg:items-end">

            {{-- Search --}}
            <div class="w-full lg:flex-1">
                <label for="search"
                    class="mb-1.5 block text-xs font-medium text-slate-600">
                    Search
                </label>

                <div class="relative">
                    <x-heroicon-o-magnifying-glass
                        class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                    />

                    <input
                        id="search"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="SR No / Vehicle No / Model"
                        class="w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-10 pr-3 text-sm text-slate-700
                            placeholder:text-slate-400
                            focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    >
                </div>
            </div>

            {{-- Status --}}
            <div class="w-full lg:w-48">
                <label for="status"
                    class="mb-1.5 block text-xs font-medium text-slate-600">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700
                        focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                >
                    <option value="">All Status</option>

                    <option value="in_stock"
                        @selected(request('status') === 'in_stock')>
                        In Stock
                    </option>

                    <option value="sold"
                        @selected(request('status') === 'sold')>
                        Sold
                    </option>
                </select>
            </div>

            {{-- Branch --}}
            @if($branches->count() > 1)
                <div class="w-full lg:w-56">
                    <label for="branch_id"
                        class="mb-1.5 block text-xs font-medium text-slate-600">
                        Branch
                    </label>

                    <select
                        id="branch_id"
                        name="branch_id"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700
                            focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200"
                    >
                        <option value="">All Branches</option>

                        @foreach($branches as $b)
                            <option value="{{ $b->id }}"
                                @selected(request('branch_id') == $b->id)>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex items-center gap-2">

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5
                        text-sm font-medium text-white transition
                        hover:bg-slate-800
                        focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                    <x-heroicon-o-funnel class="h-4 w-4"/>
                    Filter
                </button>

                @if(request()->hasAny(['search', 'status', 'branch_id']))
                    <a
                        href="{{ route('vehicles.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-slate-300
                            bg-white px-4 py-2.5 text-sm font-medium text-slate-600
                            transition hover:bg-slate-50"
                    >
                        Reset
                    </a>
                @endif

            </div>

        </div>
    </form>

        
        
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

                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                    Net Rate
                </th>
                
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                    P&amp;L
                </th>

            </tr>
        </x-slot:head>

        <tbody>
        @forelse($vehicles as $v)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4"><a href="{{ route('vehicles.show', $v) }}" class="text-blue-600">{{ $v->sr_no }}</a></td>
                <td class="px-6 py-4">{{ $v->vehicle_no }}</td>
                <td class="px-6 py-4">{{ $v->model }}</td>
                <td class="px-6 py-4">{{ $v->branch->name }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-0.5 rounded text-xs {{ $v->status === 'sold' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $v->status === 'sold' ? 'Sold' : 'In Stock' }}
                    </span>
                </td>
                <td class="px-6 py-4">₹{{ number_format($v->purchase->net_rate ?? 0, 0) }}</td>
                <td class="px-6 py-4 {{ ($v->sale->profit_loss ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $v->sale ? '₹'.number_format($v->sale->profit_loss, 0) : '—' }}
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-400">No vehicles found.</td></tr>
        @endforelse
        </tbody>
    </x-table>
</x-card>

<div class="mt-4">{{ $vehicles->links() }}</div>
@endsection
