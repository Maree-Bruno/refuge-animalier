@props([
    'id',
    'label',
    'name',
    'placeholder' => '',
    'required' => false,
    'hidden' => false,
    'value' => '',
])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-1 w-full']) }}>
    <label
        for="{{ $id }}"
        class="{{ $hidden ? 'hidden' : '' }} text-black subsubtitle leading-9"
    >
        {{ $label }}
        @if($required)
            <small class="xsmalltext">(*{{ __('labels.required') }})</small>
        @endif
    </label>

    <div class="relative w-full">
        <textarea
            id="{{ $id }}"
            name="{{ $name }}"
            rows="5"
            placeholder="{{ $placeholder }}"
            class="w-full p-2.5 bg-blueslate text-white rounded-xl outline-2 outline-offset-[-2px]
                   outline-white leading-6 placeholder:text-gray-400"
        >{{ old($name, $value) }}</textarea>

        @error($name)
        <p class="text-red-600 text-sm mt-1">{!! $message !!}</p>
        @enderror
    </div>
</div>
