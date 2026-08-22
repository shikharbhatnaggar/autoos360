@props([
    'title',
    'subtitle' => '',
])

<div class="mb-6">

    <h2 class="text-2xl font-bold text-gray-800">

        {{ $title }}

    </h2>

    @if($subtitle)

        <p class="mt-1 text-gray-500">

            {{ $subtitle }}

        </p>

    @endif

</div>