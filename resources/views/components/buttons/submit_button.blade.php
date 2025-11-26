@props(['icon'=>null, 'svgclass'])
<button type="submit" {{$attributes->class('flex p-3 items-center justify-center gap-2.5 rounded-md
                    button-animation w-fit')}}>
    @if($icon)
        <x-dynamic-component
            :component="'svg.'.$icon"
            :class="$svgclass"
        />
    @endif
    <span class="">
    {{ $slot }}
    </span>
</button>
