@extends('layouts.app')

@section('page-title', 'Broker')

@section('content')

<!-- <x-section-header
    title="Edit Broker"
    subtitle="Update broker contact information"
/> -->

<form
    method="POST"
    action="{{ route('brokers.update', $broker) }}"
    class="space-y-6"
>
    @csrf
    @method('PUT')

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
                    Update the broker's contact information
                </p>
            </div>

        </x-slot:header>


        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Name --}}
            <div class="md:col-span-2">

                <x-input
                    name="name"
                    label="Name"
                    :value="old('name', $broker->name)"
                    required
                />

            </div>


            {{-- Mobile --}}
            <x-input
                name="mobile"
                label="Mobile"
                :value="old('mobile', $broker->mobile)"
            />


            {{-- Address --}}
            <x-input
                name="address"
                label="Address"
                :value="old('address', $broker->address)"
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
            Update Broker
        </x-button>

    </div>

</form>

@endsection
