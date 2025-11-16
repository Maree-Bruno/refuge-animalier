@props(['icon'=>null, 'href'=>'', 'color'=>true])
<li {!!$attributes->class(['flex p-3 justify-center items-center gap-2.5 rounded-md cursor-pointer
text-white nav-item-animation nav-item '])
!!}>
    <a class="flex-row flex-auto items-center gap-2 flex" href="{{ $href }}">
        @if(!is_null($icon))
            <x-dynamic-component :component="'svg.'.$icon" :color="$color ? 'svg-white' : 'svg-black'"/>
        @endif
        <span class="font-semibold lg:text-sm">{{$slot}}</span>
    </a>
</li>
