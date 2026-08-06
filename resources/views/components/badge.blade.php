@props([
    'variant' => 'primary',
])

@php
$variants = [
    'primary'=>'bg-blue-100 text-blue-700',
    'success'=>'bg-green-100 text-green-700',
    'danger'=>'bg-red-100 text-red-700',
    'warning'=>'bg-yellow-100 text-yellow-700',
    'gray'=>'bg-gray-100 text-gray-700',
];
@endphp

<span
{{ $attributes->merge([
'class'=>'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium '
. ($variants[$variant] ?? $variants['primary'])
]) }}>

{{ $slot }}

</span>