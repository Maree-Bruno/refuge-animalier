@props(['title', 'text', 'icon', 'bg', 'iconclass'])
<article class="w-fit h-full flex flex-col relative space-y-4 items-center justify-center bg-white
                rounded-[40px] shadow-[0px_4px_30px_0px_rgba(0,0,0,0.1)] p-5 md:p-10 aspect-square">
    <x-svg.paws_about class="svg-fillblue absolute w-8 h-8 top-10 left-10 lg:scale-200"/>
    <div class="{{$bg}} w-fit p-4 rounded-full lg:p-8">
        @if(!is_null($icon))
            <x-dynamic-component :component="'svg.'.$icon" class="{{$iconclass}}"/>
        @endif
    </div>
    <h3 class="subsubtitle">{{$title}}</h3>
    <p class="text leading-7 max-w-80">{{$text}}</p>
</article>
