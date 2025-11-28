<div {{ $attributes->merge(['class' => 'flex flex-col gap-0.5 w-full']) }}>
    <label for="{{$for}}" class="text-black font-bold sm:text-lg leading-9">{{$label}}</label>
    <select name="{{$for}}" id="{{$for}}" class="p-3 bg-blueslate text-white rounded-xl leading-6 placeholder:text-gray-400">
        {{$slot}}
    </select>
</div>
