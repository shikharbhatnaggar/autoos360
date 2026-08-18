<div {{ $attributes->merge([
    'class' => 'bg-white rounded-xl border border-gray-200 shadow-sm transition-all
        duration-200
        ease-out
        hover:-translate-y-1
        hover:shadow-md
        hover:border-gray-300'
]) }}>

    @isset($header)
        <div class="px-4 py-3 border-b border-gray-200 sm:px-6 sm:py-4">
            {{ $header }}
        </div>
    @endisset

    <div class="p-4 sm:p-6">
        {{ $slot }}
    </div>

</div>
