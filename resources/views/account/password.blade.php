@extends('layouts.app')

@section('page-title','Change Password')

@section('content')

<x-card class="max-w-2xl">

    <x-slot:header>

        <div>

            <h2 class="text-lg font-semibold">

                Update your account password.

            </h2>

            <!-- <p class="text-sm text-gray-500 mt-1">

                

            </p> -->

        </div>

    </x-slot:header>

    <form
        method="POST"
        action="{{ route('account.password.update') }}"
        class="space-y-6">

        @csrf
        @method('PUT')

        <x-input
            type="password"
            name="current_password"
            label="Current Password"/>

        <x-input
            type="password"
            name="password"
            label="New Password"/>

        <x-input
            type="password"
            name="password_confirmation"
            label="Confirm Password"/>

        <div class="flex justify-end">

            <x-button type="submit">

                Update Password

            </x-button>

        </div>

    </form>

</x-card>

@endsection