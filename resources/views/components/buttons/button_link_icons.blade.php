@props(['href'=>'#','icon'=>null,'color' => false])

<a href="{{ $href }}"
    {{ $attributes->class(['flex p-3 items-center gap-2.5 rounded-md text-black button-animation w-fit']) }}>

    @if($icon)
        <x-dynamic-component
            :component="'svg.'.$icon"
            :color="$color ? 'svg-strokewhite' : 'svg-strokeblack'"
        />
    @endif

    <span class="font-semibold text-sm">{{ $slot }}</span>
</a>
