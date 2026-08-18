@props([
    'title',
    'value',
    'icon' => null,
    'color' => 'blue',
    'valueClass' => 'text-2xl',
])

@php
$colors = [
    'blue' => 'bg-blue-100 text-blue-600',
    'green' => 'bg-green-100 text-green-600',
    'red' => 'bg-red-100 text-red-600',
    'yellow' => 'bg-yellow-100 text-yellow-600',
];

$badge = $colors[$color] ?? $colors['blue'];
@endphp

<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 sm:p-6 transition-all
        duration-200
        ease-out
        hover:-translate-y-1
        hover:shadow-md
        hover:border-gray-300">

    <div class="flex items-center justify-between gap-3">

        <div>

            <p class="text-sm text-gray-500">

                {{ $title }}

            </p>

            <h2 class="mt-2 {{ $valueClass }} font-bold text-gray-800">

                {{ $value }}
                
            </h2>
            

        </div>

        @if($icon)

            <div class="w-12 h-12 rounded-xl {{ $badge }} flex items-center justify-center">

                {{ $icon }}

            </div>

        @endif

    </div>

</div>