@props([
    'id',
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'hidden' => false,
])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-1 w-full mb-5']) }}>
    <label
        for="{{ $id }}"
        class="{{ $hidden ? 'hidden' : '' }} text-black font-semibold sm:text-lg leading-9"
    >
        {{ $label }}
        @if($required)
            <small class="xsmalltext">(*{{ __('labels.required') }})</small>
        @endif
    </label>

    <div class="relative w-full">
        <input
            type="{{ $type }}"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            class="w-full p-2.5 bg-blueslate text-white rounded-xl leading-6 placeholder:text-gray-400"
        />

        @error($name)
        <p class="text-red-600 text-sm mt-1">{!! $message !!}</p>
        @enderror
    </div>
</div>
