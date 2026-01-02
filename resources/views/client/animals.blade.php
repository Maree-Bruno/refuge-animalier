@php
    $sizes = config('images.sizes');

    $buildSrcset = function (?string $photo) use ($sizes) {
        if (!$photo) {
            return null;
        }

        $srcset = [];

        foreach ($sizes as $size) {
            $path = sprintf(
                'animals/variants/%sx%s/%s',
                $size['width'],
                $size['height'],
                $photo
            );
            $srcset[] = Storage::disk('images')->url($path) . ' ' . $size['width'] . 'w';
        }

        return implode(', ', $srcset);
    };

    $buildSizes = function (?string $photo) use ($sizes) {
        if (!$photo) {
            return null;
        }

        return sprintf(
            '(max-width: 640px) %spx, (max-width: 1024px) %spx, %spx',
            $sizes['sm']['width'],
            $sizes['md']['width'],
            $sizes['lg']['width']
        );
    };
@endphp


<x-layouts.client>
    <x-layouts.section :title="__('animals/client_index.title')">
        <div class="">
            <input type="checkbox" id="filter" class="faq-toggle">
            <label for="filter" class="faq-header max-w-fit">
                <div class="w-fit">
                    <h3 class="button-green button-animation p-2 rounded-lg font-semibold flex gap-2
                    text-blueslate">
                        <span>
                            <x-svg.filter/>
                        </span>
                        {{ __('animals/client_index.filters') }}
                    </h3>
                </div>
            </label>

            <div class="faq-content">
                <form action="" method="get" class="flex flex-col justify-center items-end">
                    <div class="grid grid-cols-2 gap-5 items-start w-full">

                        <x-form.select_label for="gender" :label="__('animals/client_index.gender')">
                            <option value="none">{{ __('animals/client_index.select_gender') }}</option>
                            <option value="male">{{ __('animals/client_index.male') }}</option>
                            <option value="female">{{ __('animals/client_index.female') }}</option>
                        </x-form.select_label>

                        <x-form.select_label for="race" :label="__('animals/client_index.race')">
                            <option value="none">{{ __('animals/client_index.select_race') }}</option>
                            <option value="dog">{{ __('animals/race.animals.dog') }}</option>
                            <option value="cat">{{ __('animals/race.animals.cat') }}</option>
                            <option value="hamster">{{ __('animals/race.animals.hamster') }}</option>
                        </x-form.select_label>

                        <x-form.select_label for="species" :label="__('animals/client_index.species')">
                            <option value="none">{{ __('animals/client_index.select_species') }}</option>
                            <option value="golden_retriever">{{ __('animals/client_index.golden_retriever') }}</option>
                            <option value="chihuahua">{{ __('animals/client_index.chihuahua') }}</option>
                            <option value="shiba_inu">{{ __('animals/client_index.shiba_inu') }}</option>
                        </x-form.select_label>

                        <x-form.select_label for="coat" :label="__('animals/client_index.coat')">
                            <option value="none">{{ __('animals/client_index.select_coat') }}</option>
                            <option value="black">{{ __('animals/client_index.black') }}</option>
                            <option value="brown">{{ __('animals/client_index.brown') }}</option>
                            <option value="beige">{{ __('animals/client_index.beige') }}</option>
                        </x-form.select_label>

                        <x-form.input_label
                            id="search"
                            type="search"
                            name="search"
                            :label="__('animals/client_index.search')"
                            :placeholder="__('animals/client_index.search')"
                            :value="old('search')"
                        />
                    </div>

                    <div class="flex gap-5">
                        <x-buttons.submit_button
                            class="button-yellow text-blueslate font-semibold"
                            icon="filter"
                            svgclass="svg-strokeblack">
                            {{ __('animals/client_index.filter_button') }}
                        </x-buttons.submit_button>

                        <x-buttons.submit_button
                            class="button-orange text-blueslate font-semibold"
                            icon="close"
                            svgclass="svg-strokeblack">
                            {{ __('animals/client_index.reset_button') }}
                        </x-buttons.submit_button>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex flex-col items-center gap-5 sm:grid sm:grid-cols-2 sm:justify-items-center
                lg:grid-cols-3 lg:gap-10 2xl:grid-cols-4 2xl:gap-20">
            @forelse($animals as $animal)
                @php
                    $photo = is_array($animal->pictures) && count($animal->pictures) > 0
                        ? $animal->pictures[0]
                        : null;

                    $src = $photo
                        ? Storage::disk('images')->url('animals/originals/'.$photo)
                        : asset('images/billy.webp');
                @endphp

                <x-animal.card
                    name="{{ $animal->name }}"
                    src="{{ $src }}"
                    :srcset="$buildSrcset($photo)"
                    :sizes="$buildSizes($photo)"
                    age="{{ $animal->age }}"
                    gender="{{ $animal->sex }}"
                    species="{{ $animal->specie->name }}"
                    description="{{ $animal->description }}"
                    href="{{ route('animals_show', $animal) }}"
                />
            @empty
                <p class="col-span-full text-center text-gray-500">
                    Aucun animal de disponible
                </p>
            @endforelse

        </div>
    </x-layouts.section>
</x-layouts.client>
