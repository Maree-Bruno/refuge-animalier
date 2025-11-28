<x-layouts.client>
    <div class="bg-lightgreenmint/40 shadow-[inset_2px_-4px_30px_rgba(0,0,0,0.1)] py-8">
        <section class="p-5 flex flex-col gap-6 leading-9 md:flex-row-reverse md:items-center lg:px-28 backdrop-blur-md">
            <div class="group-img-homepage relative w-full place-items-center">
                <div class="relative group-img-homepage-1 place-self-end">
                    <div class="rounded-3xl overflow-hidden shadow-[0px_4px_30px_0px_rgba(0,0,0,0.25)]">
                        <img
                            src="{{ URL('images/billy.webp') }}"
                            alt="Hamster"
                            class="w-full h-full object-cover aspect-square lg:w-60 lg:h-auto"
                        />
                    </div>
                    <div class="absolute -top-4 -left-4 bg-sweetorange rounded-2xl p-4 shadow-lg">
                        <x-svg.linkedin />
                    </div>
                </div>

                <div class="relative group-img-homepage-2 place-self-start">
                    <div class="rounded-3xl overflow-hidden shadow-[0px_4px_30px_0px_rgba(0,0,0,0.25)]">
                        <img
                            src="{{ URL('images/billy.webp') }}"
                            alt="Chat"
                            class="w-full h-full object-cover aspect-square lg:w-60 lg:h-auto"
                        />
                    </div>
                    <div class="absolute -top-4 -right-4 bg-blueslate rounded-2xl p-4 shadow-lg">
                        <x-svg.linkedin />
                    </div>
                </div>

                <div class="relative group-img-homepage-3">
                    <div class="rounded-3xl overflow-hidden shadow-[0px_4px_30px_0px_rgba(0,0,0,0.25)]">
                        <img
                            src="{{ URL('images/billy.webp') }}"
                            alt="Chien"
                            class="w-full h-full object-cover aspect-square lg:w-60 lg:h-auto"
                        />
                    </div>
                    <div class="absolute -bottom-4 -right-4 bg-honeyyellow rounded-2xl p-4 shadow-lg">
                        <x-svg.linkedin />
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-2.5 lg:w-11/12">
                <h2 class="text-4xl font-bold font-quicksand">
                    {{ __('homepage.hero.title') }}
                </h2>

                <p class="text-xl leading-9">
                    {{ __('homepage.hero.description') }}
                </p>

                <div class="w-fit flex flex-wrap gap-2.5">
                    <x-buttons.button_link_icons
                        icon="paws"
                        class="button-yellow"
                        href="{{ route('animals') }}"
                    >
                        {{ __('homepage.hero.adopt_button') }}
                    </x-buttons.button_link_icons>

                    <x-buttons.button_link_icons
                        icon="arrow_right"
                        class="button-orange flex-row-reverse"
                        href="{{ route('about') }}"
                    >
                        {{ __('homepage.hero.more_button') }}
                    </x-buttons.button_link_icons>
                </div>
            </div>
        </section>
    </div>

    <x-layouts.section :title="__('homepage.stats_section.title')" class="relative space-y-10">
        <div class="flex flex-wrap flex-row justify-center gap-5 sm:gap-10 lg:flex-nowrap lg:justify-between lg:px-14">
            <x-homepage.stats
                class="bg-lightsweetorange/30 border-4 border-sweetorange"
                text="{{ __('homepage.stats_section.rescued_number_text') }}"
                number="120"
            />
            <x-homepage.stats
                class="bg-lightgreenmint/20 border-4 border-greenmint"
                text="{{ __('homepage.stats_section.adoptions_number_text') }}"
                number="60"
            />
            <x-homepage.stats
                class="bg-lighthoneyyellow/20 border-4 border-honeyyellow"
                text="{{ __('homepage.stats_section.found_family_number_text') }}"
                number="300"
            />
        </div>

        <p class="text leading-9 max-w-3/4 self-center border border-transparent p-5 rounded-2xl bg-blueslate text-white">
            {{ __('homepage.stats_section.description') }}
        </p>
    </x-layouts.section>

    <x-layouts.section :title="__('homepage.animals_section.title')" class="relative">
        <div class="flex flex-col gap-5 items-center justify-center md:flex-row">
            @foreach ([1,2,3] as $i)
                <x-animal.card
                    :name="__('homepage.animals_section.animals.billy.name')"
                    :age="__('homepage.animals_section.animals.billy.age')"
                    :gender="__('homepage.animals_section.animals.billy.gender')"
                    :species="__('homepage.animals_section.animals.billy.species')"
                    src="{{ URL('images/billy.webp') }}"
                    :description="__('homepage.animals_section.animals.billy.description')"
                />
            @endforeach
        </div>

        <x-buttons.button_link_icons
            icon="arrow_right"
            class="button-green flex-row-reverse self-end"
        >
            {{ __('homepage.animals_section.see_all') }}
        </x-buttons.button_link_icons>
    </x-layouts.section>

    <div class="bg-lighthoneyyellow/40 shadow-[inset_2px_4px_30px_rgba(0,0,0,0.1)] py-10">
        <x-layouts.section title="{{__('homepage.faq_section.title')}}" class="relative">
            <div class="flex flex-col space-y-5 divide-y divide-blueslate">
                @foreach(trans('homepage.faq_section.items') as $id => $item)
                    <x-homepage.faq
                        id="{{ $id }}"
                        title="{{ $item['question'] }}"
                        content="{{ $item['answer'] }}"
                    />
                @endforeach
            </div>
        </x-layouts.section>
    </div>
</x-layouts.client>
