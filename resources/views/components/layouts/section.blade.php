@props(['title', 'icon'=>null])
<section {{$attributes->class(" p-5 flex flex-col gap-5  relative lg:px-28")}}>
    <h2 class="subtitle mb-5">{{$title}}</h2>
    {{$slot}}
</section>
