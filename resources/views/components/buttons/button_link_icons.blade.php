@props(['href'=>'#','icon'=>null,'color' => false])

<a href="{{ $href }}"
    {{ $attributes->class(['flex p-3 justify-center items-center gap-2.5 rounded-md text-blueslate']) }}>

    @if($icon)
        <x-dynamic-component
            :component="'svg.'.$icon"
            :color="$color ? 'svg-white' : 'svg-black'"
        />
    @endif

    <span class="font-semibold lg:text-sm">{{ $slot }}</span>
</a>
