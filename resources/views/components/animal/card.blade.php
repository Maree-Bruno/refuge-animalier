@props(['name', 'src', 'srcset' => '', 'sizes' => '', 'age', 'gender', 'species', 'description', 'href'=>''])

<article
    class="animal-card group w-72 h-fit p-5 bg-transparent rounded-[40px] shadow-[0px_4px_30px_0px_rgba(0,0,0,0.05)]
    2xl:scale-105">
    <div
        class="card-normal flex flex-col justify-center items-center gap-5 group-hover:opacity-0 transition-opacity duration-300">
        <img
            src="{{$src}}"
            @if($srcset)
                srcset="{{$srcset}}"
            @endif
            @if($sizes)
                sizes="{{$sizes}}"
            @endif
            alt="Photo de {{$name}}"
            class="self-stretch h-72 rounded-tl-[20px] rounded-tr-[20px] rounded-bl-[200px] rounded-br-[200px]
            shadow-[inset_0px_0px_100px_0px_rgba(0,0,0,0.80)] object-cover object-top object-fit-fill">
        <h3 class="subtitle">{{$name}}</h3>
    </div>
    <div
        class="card-hover opacity-0 group-hover:opacity-100 transition-all duration-300 text-white p-5 rounded-2xl ">
        <div class="card-hover-animal relative z-10 overflow-hidden">
            <img
                src="{{$src}}"
                @if($srcset)
                    srcset="{{$srcset}}"
                @endif
                @if($sizes)
                    sizes="{{$sizes}}"
                @endif
                alt="Photo de {{$name}}"
                class="absolute inset-0 w-full h-full object-cover rounded-2xl ">
            <div class="absolute inset-0 bg-black/50 rounded-2xl"></div>
            <div class="flex flex-col justify-center z-10">
                <div class="mb-4">
                    <p class="subtitle">{{$name}}</p>
                    <div class="flex flex-col gap-6">
                        <div class="flex justify-between items-center">
                            <span class="xsmalltext">{{$age}}</span>
                            <span class="xsmalltext">{{$gender}}</span>
                        </div>
                        <span class="xsmalltext">{{$species}}</span>
                    </div>
                </div>
                <div class="card-hover-content xsmalltext leading-6 container">
                    <p>
                        {{$description}}
                    </p>
                </div>
            </div>
            <x-buttons.button_link_icons href="{{$href}}" icon="arrow_right" class="button-orange self-center">
                {{__('homepage.animals_section.see_animal')}}
            </x-buttons.button_link_icons>
        </div>
    </div>
</article>
