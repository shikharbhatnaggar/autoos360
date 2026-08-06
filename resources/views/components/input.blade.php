@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'icon' => false,
])

<div>

    @if($label)
        <label
            for="{{ $name }}"
            class="mb-2 block text-sm font-medium text-gray-700">

            {{ $label }}

        </label>
    @endif

    <div
        x-data="{ show:false }"
        class="relative">

        {{-- Left Icon --}}
        @if($icon)

            <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">

                {{ $icon }}

            </div>

        @endif

        <input

            :type="'{{ $type }}' === 'password'
                    ? (show ? 'text' : 'password')
                    : '{{ $type }}'"

            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name,$value) }}"
            placeholder="{{ $placeholder }}"

            {{ $attributes->merge([
                'class' =>
                'w-full rounded-lg border bg-white py-2.5 pr-10
                '.($icon ? 'pl-10 ' : 'pl-4 ').'

                border-gray-300

                focus:border-blue-500
                focus:ring-2
                focus:ring-blue-500

                disabled:bg-gray-100

                '.($errors->has($name)
                    ? 'border-red-500'
                    : '')
            ]) }}>

        {{-- Password Toggle --}}
        @if($type=='password')

            <button
                type="button"
                @click="show=!show"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">

                <template x-if="!show">

                    <x-heroicon-o-eye class="w-5 h-5"/>

                </template>

                <template x-if="show">

                    <x-heroicon-o-eye-slash class="w-5 h-5"/>

                </template>

            </button>

        @endif

    </div>

    @error($name)

        <p class="mt-2 text-sm text-red-600">

            {{ $message }}

        </p>

    @enderror

</div>