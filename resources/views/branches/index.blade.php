@extends('layouts.app')

@section('page-title', 'Branches')

@section('content')

<!-- <x-section-header
    title="Our Locations"
    subtitle="Manage your branches and monitor vehicle inventory"
>
    <x-slot:actions>

        @can('create', App\Models\Branch::class)

            <a
                href="{{ route('branches.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg
                       bg-blue-600 px-4 py-2.5 text-sm font-medium text-white
                       transition hover:bg-blue-700
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                <span class="text-lg leading-none">+</span>
                Add Branch
            </a>

        @endcan

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
{{-- Branch List --}}
{{-- ========================================================= --}}

<x-card>

    <x-slot:header>

        <div class="flex flex-wrap items-center justify-between gap-3">

            <h2 class="font-semibold">

                Branches

            </h2>

            <a
                href="{{ route('branches.create') }}"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">

                Add Branch

            </a>

        </div>

    </x-slot:header>


    <div class="overflow-x-auto">

        <table class="w-full min-w-[950px] text-sm">

            <thead>

                <tr class="border-b border-gray-200 bg-gray-50/70">

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Name
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Code
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Phone
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Address
                    </th>

                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Status
                    </th>

                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Vehicles
                    </th>

                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-gray-100">

            @forelse($branches as $branch)

                <tr class="group transition hover:bg-gray-50">

                    {{-- Name --}}
                    <td class="px-5 py-4">

                        <a
                            href="{{ route('branches.edit', $branch) }}"
                            class="font-medium text-gray-800 transition hover:text-blue-600"
                        >
                            {{ $branch->name }}
                        </a>

                    </td>


                    {{-- Code --}}
                    <td class="px-5 py-4">

                        <span
                            class="inline-flex rounded-md bg-gray-100 px-2.5 py-1
                                   font-mono text-xs font-medium text-gray-600"
                        >
                            {{ $branch->code }}
                        </span>

                    </td>


                    {{-- Phone --}}
                    <td class="px-5 py-4 text-gray-600">

                        {{ $branch->phone ?? '—' }}

                    </td>


                    {{-- Address --}}
                    <td class="px-5 py-4 text-gray-600">

                        <span class="block max-w-xs truncate">
                            {{ $branch->address ?? '—' }}
                        </span>

                    </td>


                    {{-- Status --}}
                    <td class="px-5 py-4">

                        @if($branch->is_active)

                            <x-badge variant="success">
                                Active
                            </x-badge>

                        @else

                            <x-badge variant="neutral">
                                Inactive
                            </x-badge>

                        @endif

                    </td>


                    {{-- Vehicles --}}
                    <td class="px-5 py-4 text-right">

                        <span
                            class="inline-flex min-w-8 items-center justify-center rounded-full
                                   bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700"
                        >
                            {{ $branch->vehicles_count }}
                        </span>

                    </td>


                    {{-- Actions --}}
                    <td class="px-5 py-4 text-right whitespace-nowrap">

                        <a
                            href="{{ route('branches.edit', $branch) }}"
                            class="inline-flex items-center rounded-lg px-3 py-2
                                   text-xs font-medium text-blue-600
                                   transition hover:bg-blue-50 hover:text-blue-700"
                        >
                            Edit
                        </a>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="7"
                        class="px-5 py-12 text-center"
                    >

                        <div class="mx-auto flex max-w-sm flex-col items-center">

                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100"
                            >
                                <x-heroicon-o-building-office-2
                                    class="h-6 w-6 text-gray-400"
                                />
                            </div>

                            <h3 class="mt-4 text-sm font-semibold text-gray-800">
                                No branches yet
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Add your first branch to start managing locations.
                            </p>

                            @can('create', App\Models\Branch::class)

                                <a
                                    href="{{ route('branches.create') }}"
                                    class="mt-4 text-sm font-medium text-blue-600 hover:text-blue-700"
                                >
                                    + Add Branch
                                </a>

                            @endcan

                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</x-card>

@endsection
