@extends('layouts.app')

@section('page-title', 'Branch')

@section('content')

<!-- <x-section-header
    title="Add Branch"
    subtitle="Create a new branch location for your organization"
/> -->

<form
    method="POST"
    action="{{ route('branches.store') }}"
    class="space-y-6"
>
    @csrf


    {{-- ========================================================= --}}
    {{-- Branch Details --}}
    {{-- ========================================================= --}}

    <x-card>

        <x-slot:header>

            <div>
                <h2 class="text-base font-semibold text-gray-800">
                    Branch Details
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Enter the basic information for the new branch
                </p>
            </div>

        </x-slot:header>


        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Name --}}
            <x-input
                name="name"
                label="Name"
                :value="old('name')"
                required
            />


            {{-- Code --}}
            <div>

                <label
                    for="code"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Code
                </label>

                <x-input
                    name="code"
                    :value="old('code')"
                    placeholder="e.g. HYD-01"
                    required
                />

                <p class="mt-1.5 text-xs text-gray-400">
                    Must be unique. This code cannot be changed after the branch is created.
                </p>

            </div>


            {{-- Phone --}}
            <x-input
                name="phone"
                label="Phone"
                :value="old('phone')"
            />


            {{-- Address --}}
            <x-input
                name="address"
                label="Address"
                :value="old('address')"
            />

        </div>

    </x-card>


    {{-- ========================================================= --}}
    {{-- Actions --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

        <a
            href="{{ route('branches.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-gray-300
                   bg-white px-5 py-2.5 text-sm font-medium text-gray-700
                   transition hover:bg-gray-50"
        >
            Cancel
        </a>

        <x-button
            type="submit"
            variant="primary"
            size="md"
        >
            Save Branch
        </x-button>

    </div>

</form>

@endsection
