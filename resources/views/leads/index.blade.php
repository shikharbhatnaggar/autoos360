@extends('layouts.app')

@section('page-title', 'Leads Management')

@section('content')
<div class="space-y-6">

    {{-- Session Alerts Integration --}}
    @include('partials.alerts')

    {{-- Header Actions Area --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Inbound Customer Leads</h2>
            <p class="text-sm text-gray-500 mt-1">Track, manage, and follow up with digital store inquiries and test-drive requests.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                Total Unattended: {{ $leads->where('status', 'new')->count() }}
            </span>
        </div>
    </div>

    {{-- Main Inventory Leads Data Grid --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact Info</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Target Asset</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-gray-50 transition-colors {{ $lead->status === 'new' ? 'bg-blue-50/30' : '' }}">
                            
                            {{-- Customer Profile --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-700 text-sm">
                                        {{ strtoupper(substr($lead->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $lead->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $lead->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Contact Matrix --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <div class="space-y-0.5">
                                    <div class="flex items-center gap-1.5">
                                        <x-heroicon-o-phone class="w-3.5 h-3.5 text-gray-400" />
                                        <span>{{ $lead->mobile }}</span>
                                    </div>
                                    @if($lead->email)
                                        <div class="flex items-center gap-1.5 text-xs text-gray-400">
                                            <x-heroicon-o-envelope class="w-3.5 h-3.5 text-gray-400" />
                                            <span class="truncate max-w-[160px]">{{ $lead->email }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Target Stock Vehicle Entity --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($lead->vehicle)
                                    <a href="{{ route('vehicles.show', $lead->vehicle) }}" class="group">
                                        <div class="font-medium text-blue-600 group-hover:underline">
                                            {{ $lead->vehicle->vehicle_no }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $lead->vehicle->model }}</div>
                                    </a>
                                @else
                                    <span class="text-xs italic text-gray-400">General Inquiry</span>
                                @endif
                            </td>

                            {{-- Message Segment --}}
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                                <p class="truncate" title="{{ $lead->message }}">
                                    {{ $lead->message ?? 'No additional comments provided.' }}
                                </p>
                            </td>

                            {{-- Status Badges --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($lead->status === 'new')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> New Lead
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                        {{ ucfirst($lead->status) }}
                                    </span>
                                @endif
                            </td>

                            {{-- Operational Control Pipeline Buttons --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    @if($lead->status === 'new')
                                        <form action="{{ route('leads.acknowledge', $lead) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center justify-center p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition" title="Mark as Contacted">
                                                <x-heroicon-o-check class="w-5 h-5" />
                                            </button>
                                        </form>
                                    @endif
                                    <a href="tel:{{ $lead->mobile }}" class="inline-flex items-center justify-center p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Call Client">
                                        <x-heroicon-o-phone class="w-5 h-5" />
                                    </a>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <x-heroicon-o-user-group class="mx-auto h-12 w-12 text-gray-300" />
                                <h3 class="mt-2 text-sm font-semibold text-gray-900">No leads found</h3>
                                <p class="mt-1 text-sm text-gray-500">When potential car buyers request test drives, they will show up here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
