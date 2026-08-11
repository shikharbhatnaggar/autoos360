@extends('layouts.app')

@section('page-title', 'Brokers')

@section('content')

<!-- <x-section-header
    title="Our Agents"
    subtitle="Manage brokers and view their purchase activity"
>
    <x-slot:actions>
        <a
            href="{{ route('brokers.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg
                   bg-blue-600 px-4 py-2.5 text-sm font-medium text-white
                   transition hover:bg-blue-700
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
            <span class="text-lg leading-none">+</span>
            Add Broker
        </a>
    </x-slot:actions>
</x-section-header> -->


{{-- ========================================================= --}}
{{-- Success Message --}}
{{-- ========================================================= --}}

@if(session('success'))

    <div class="mb-6 flex items-center gap-3 rounded-lg border border-green-200
                bg-green-50 px-4 py-3 text-sm text-green-700">

        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-green-100">
            <span class="text-sm font-bold">✓</span>
        </div>

        <span>
            {{ session('success') }}
        </span>

    </div>

@endif


{{-- ========================================================= --}}
{{-- Broker List --}}
{{-- ========================================================= --}}

<x-card class="mt-6">

    <x-slot:header>

        <div class="flex flex-wrap items-center justify-between gap-3">

            <h2 class="font-semibold">

                Our Agents

            </h2>

            <a
                href="{{ route('brokers.create') }}"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">

                Add Broker

            </a>

        </div>

    </x-slot:header>



    {{-- Desktop Table --}}
    <div class="overflow-x-auto">

        <table class="w-full min-w-[720px] text-sm">

            <thead>

                <tr class="border-b border-gray-200 bg-gray-50/70">

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Name
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mobile
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Address
                    </th>

                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Purchases
                    </th>

                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-gray-100">

            @forelse($brokers as $broker)

                <tr class="group transition hover:bg-gray-50">

                    {{-- Name --}}
                    <td class="px-5 py-4">

                        <a
                            href="{{ route('brokers.edit', $broker) }}"
                            class="font-medium text-gray-800 transition hover:text-blue-600"
                        >
                            {{ $broker->name }}
                        </a>

                    </td>


                    {{-- Mobile --}}
                    <td class="px-5 py-4 text-gray-600">

                        {{ $broker->mobile ?? '—' }}

                    </td>


                    {{-- Address --}}
                    <td class="px-5 py-4 text-gray-600">

                        <span class="block max-w-xs truncate">
                            {{ $broker->address ?? '—' }}
                        </span>

                    </td>


                    {{-- Purchases --}}
                    <td class="px-5 py-4 text-right">

                        <span
                            class="inline-flex min-w-8 items-center justify-center rounded-full
                                   bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700"
                        >
                            {{ $broker->purchases_count }}
                        </span>

                    </td>


                    {{-- Actions --}}
                    <td class="px-5 py-4 text-right whitespace-nowrap">

                        <div class="inline-flex items-center gap-1">

                            <a
                                href="{{ route('brokers.edit', $broker) }}"
                                class="rounded-lg px-3 py-2 text-xs font-medium text-blue-600
                                       transition hover:bg-blue-50 hover:text-blue-700"
                            >
                                Edit
                            </a>


                            <form
                                method="POST"
                                action="{{ route('brokers.destroy', $broker) }}"
                                class="inline"
                                onsubmit="return confirm('Remove {{ $broker->name }}? This cannot be undone.')"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="rounded-lg px-3 py-2 text-xs font-medium text-red-600
                                           transition hover:bg-red-50 hover:text-red-700"
                                >
                                    Remove
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="5"
                        class="px-5 py-12 text-center"
                    >

                        <div class="mx-auto flex max-w-sm flex-col items-center">

                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">

                                <x-heroicon-o-user-group class="h-6 w-6 text-gray-400"/>

                            </div>

                            <h3 class="mt-4 text-sm font-semibold text-gray-800">
                                No brokers yet
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Add your first broker to start tracking purchase activity.
                            </p>

                            <a
                                href="{{ route('brokers.create') }}"
                                class="mt-4 text-sm font-medium text-blue-600 hover:text-blue-700"
                            >
                                + Add Broker
                            </a>

                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</x-card>


{{-- ========================================================= --}}
{{-- Pagination --}}
{{-- ========================================================= --}}

@if($brokers->hasPages())

    <div class="mt-5">
        {{ $brokers->links() }}
    </div>

@endif

@endsection
