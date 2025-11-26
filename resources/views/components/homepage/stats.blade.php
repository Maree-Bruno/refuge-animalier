@props(['number', 'text'])
<div {{$attributes->class('w-36 h-36 flex flex-col items-center justify-center gap-2 rounded-4xl p-3
shadow-[0px_4px_30px_0px_rgba(0,0,0,0.25)] lg:w-58 lg:h-58 lg:rounded-[40px] lg:p-[25px] transition-all
hover:scale-105')}}>
    <span class="font-bold text-4xl lg:text-7xl">{{$number}}</span>
    <p class="xsmalltext">{{$text}}</p>
</div>
