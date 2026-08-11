@extends('layouts.app')

@section('page-title', 'Broker')

@section('content')

<x-section-header
    title="Add Broker"
    subtitle="Add a new broker to your branch"
/>

<form
    method="POST"
    action="{{ route('brokers.store') }}"
    class="space-y-6"
>
    @csrf

    {{-- ========================================================= --}}
    {{-- Broker Details --}}
    {{-- ========================================================= --}}

    <x-card>

        <x-slot:header>

            <div>
                <h2 class="text-base font-semibold text-gray-800">
                    Broker Details
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Enter the broker's contact information
                </p>
            </div>

        </x-slot:header>


        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Name --}}
            <div class="md:col-span-2">

                <x-input
                    name="name"
                    label="Name"
                    :value="old('name')"
                    required
                />

            </div>


            {{-- Mobile --}}
            <x-input
                name="mobile"
                label="Mobile"
                :value="old('mobile')"
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
            href="{{ route('brokers.index') }}"
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
            Save Broker
        </x-button>

    </div>

</form>

@endsection