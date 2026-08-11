@extends('layouts.app')

@section('page-title', 'Branch')

@section('content')

<!-- <x-section-header
    title="Edit Branch"
    subtitle="Update branch information and availability"
/> -->

<form
    method="POST"
    action="{{ route('branches.update', $branch) }}"
    class="space-y-6"
>
    @csrf
    @method('PUT')


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
                    Update the branch's basic information
                </p>
            </div>

        </x-slot:header>


        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Name --}}
            <x-input
                name="name"
                label="Name"
                :value="old('name', $branch->name)"
                required
            />


            {{-- Code --}}
            <div>

                <label
                    for="branch_code"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Code
                </label>

                <input
                    id="branch_code"
                    type="text"
                    disabled
                    value="{{ $branch->code }}"
                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5
                           text-sm text-gray-500"
                >

                <p class="mt-1.5 text-xs text-gray-400">
                    Branch code cannot be changed after creation.
                </p>

            </div>


            {{-- Phone --}}
            <x-input
                name="phone"
                label="Phone"
                :value="old('phone', $branch->phone)"
            />


            {{-- Address --}}
            <x-input
                name="address"
                label="Address"
                :value="old('address', $branch->address)"
            />


            {{-- Active Status --}}
            <div class="md:col-span-2">

                <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-4">

                    <div class="flex items-start gap-3">

                        {{-- Hidden field ensures unchecked checkbox submits 0 --}}
                        <input
                            type="hidden"
                            name="is_active"
                            value="0"
                        >

                        <input
                            type="checkbox"
                            id="is_active"
                            name="is_active"
                            value="1"
                            @checked(old('is_active', $branch->is_active))
                            class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-600
                                   focus:ring-2 focus:ring-blue-500"
                        >

                        <div>

                            <label
                                for="is_active"
                                class="cursor-pointer text-sm font-medium text-gray-800"
                            >
                                Branch is active
                            </label>

                            <p class="mt-1 text-xs text-gray-500">
                                Active branches can be used for vehicle inventory and other operations.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

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
            Update Branch
        </x-button>

    </div>

</form>

@endsection