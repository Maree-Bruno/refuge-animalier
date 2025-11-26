@props(['id', 'title', 'content'])
<article class="space-y-2.5">
    <input type="checkbox" id="{{$id}}" class="faq-toggle">
    <label for="{{$id}}" class="faq-header">
        <div class="subsubtitle">{{$title}}</div>
        <x-svg.arrow_right class="faq-icon"/>
    </label>
    <div class="faq-content mb-4">
        <p class="smalltext">
            {{$content}}
        </p>
    </div>
</article>
