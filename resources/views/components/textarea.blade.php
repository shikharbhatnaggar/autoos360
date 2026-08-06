@props([
'name',
'label'=>null,
'rows'=>4,
])

<div>

@if($label)

<label
for="{{ $name }}"
class="mb-2 block text-sm font-medium text-gray-700">

{{ $label }}

</label>

@endif

<textarea

name="{{ $name }}"
id="{{ $name }}"
rows="{{ $rows }}"

{{ $attributes->merge([
'class'=>'w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500'
]) }}>{{ old($name,$slot) }}</textarea>

@error($name)

<p class="mt-1 text-sm text-red-600">

{{ $message }}

</p>

@enderror

</div>