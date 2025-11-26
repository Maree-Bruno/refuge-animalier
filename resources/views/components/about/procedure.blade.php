@props(['src', 'alt', 'step', 'title', 'text', 'icon', 'link'=>null, 'icon', 'button_class', 'label',
'reverse'=>false, 'showArrow'=>true])
<article {{ $attributes->class(['flex flex-col lg:p-10 xl:max-w-[90%] relative']) }}>
    <div class="flex flex-col sm:flex-row gap-10 lg:gap-24 {{$reverse ? 'sm:flex-row-reverse' : 'sm:flex-row'}} ">
        <img
            src="{{$src}}"
            alt="{{$alt}}"
            class=" lg:aspect-square lg:h-auto sm:h-[300px] rounded-2xl sm:max-w-1/3 h-auto object-cover object-top
        object-fit-fill">
        <div class="space-y-6 flex flex-col">
            <div class="space-y-5">
                <div class="flex gap-5">
                    <div class="w-10 h-10 lg:w-20 lg:h-20 p-8 md:p-10 bg-blueslate/20 rounded-[10000px] inline-flex
                justify-center items-center gap-2.5">
                        <small class="title text-blueslate">{{$step}}</small>
                    </div>
                    <h3 class="subtitle text-blueslate">{{$title}}</h3>
                </div>
                <p class="text-lg leading-8 lg:text lg:leading-9 self-stretch justify-start">
                    {{$text}}
                </p>
            </div>
        </div>
    </div>
    @if($link)
        <div class="space-y-5 w-fit self-end">
            <p class="font-bold text-blueslate">Découvrez nos animaux</p>
            <x-buttons.button_link_icons
                icon="{{$icon}}"
                href="{{$link}}"
                class="{{$button_class}}"
            >
                {{$label}}
            </x-buttons.button_link_icons>
        </div>
    @endif
    @if($showArrow)
        <x-svg.curvedarrow class="absolute {{$reverse ? 'right-10 rotate-90' : 'left-10 -scale-x-100 -rotate-45'}}
        -bottom-60
         hidden md:block"/>
    @endif
</article>
