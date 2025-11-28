@props(['title', 'icon'=>null])
<section {{$attributes->class(" p-5 flex flex-col gap-5 relative md:px-10 lg:px-20")}}>
    <h2 class="title mb-5">{{$title}}</h2>
    {{$slot}}
</section>
