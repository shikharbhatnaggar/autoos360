@props([
    'section',
    'type',
    'name',
    'document' => null,
])

<div
    class="rounded-xl border border-gray-200 bg-white p-4"
    x-data="{ preview: null }"
>

    <div class="flex flex-col gap-4 lg:flex-row lg:items-start">

        {{-- Document Name --}}
        <div class="lg:w-56">

            <label class="text-sm font-semibold text-gray-800">
                {{ $name }}
            </label>

            <p class="mt-1 text-xs text-gray-400">
                Optional
            </p>

        </div>


        {{-- Document Number --}}
        <div class="flex-1">

            <label class="mb-2 block text-xs font-medium text-gray-500">
                Document No.
            </label>

            <input
                type="text"
                name="document[{{ $section }}][{{ $type }}][document_no]"
                value="{{ old(
                    "document.$section.$type.document_no",
                    $document?->document_no
                ) }}"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm
                       focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >

        </div>


        {{-- Valid Till --}}
        <div class="w-full lg:w-44">

            <label class="mb-2 block text-xs font-medium text-gray-500">
                Valid Till
            </label>

            <input
                type="date"
                name="document[{{ $section }}][{{ $type }}][valid_till]"
                value="{{ old(
                    "document.$section.$type.valid_till",
                    $document?->valid_till?->format('Y-m-d')
                ) }}"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm
                       focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >

        </div>


        {{-- Upload --}}
        <div class="w-full lg:w-64">

            <label class="mb-2 block text-xs font-medium text-gray-500">
                Document Image
            </label>

            <input
                type="file"
                accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                name="document[{{ $section }}][{{ $type }}][file]"
                @change="
                    const file = $event.target.files[0];

                    if (file) {
                        preview = URL.createObjectURL(file);
                    }
                "
                class="block w-full text-sm text-gray-600
                       file:mr-3 file:rounded-lg file:border-0
                       file:bg-gray-100 file:px-3 file:py-2
                       file:text-sm file:font-medium
                       file:text-gray-700
                       hover:file:bg-gray-200"
            >

            <p class="mt-1 text-xs text-gray-400">
                JPG, JPEG or PNG · Max 5 MB
            </p>

        </div>

    </div>


    {{-- Preview --}}
    <div class="mt-4 flex items-center gap-4">

        @if($document?->file_path)

            <div class="relative">

                <img
                    src="{{ Storage::disk('public')->url($document->file_path) }}"
                    alt="{{ $name }}"
                    class="h-24 w-32 rounded-lg border border-gray-200 object-cover"
                >

                <span
                    class="absolute bottom-1 left-1 rounded bg-black/60 px-2 py-0.5 text-[10px] text-white"
                >
                    Current
                </span>

            </div>

        @endif


        <template x-if="preview">

            <div>

                <img
                    :src="preview"
                    alt="Preview"
                    class="h-24 w-32 rounded-lg border border-blue-200 object-cover"
                >

                <p class="mt-1 text-xs text-blue-600">
                    New image
                </p>

            </div>

        </template>

    </div>

</div>