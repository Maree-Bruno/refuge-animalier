@php
    $sizes = config('images.sizes');

    $buildSrcset = function (?string $photo) use ($sizes) {
        if (!$photo) {
            return null;
        }

        $srcset = [];

        foreach ($sizes as $size) {
            $srcset[] = asset(
                sprintf(
                    'images/animals/variants/%sx%s/%s',
                    $size['width'],
                    $size['height'],
                    $photo
                )
            ) . ' ' . $size['width'] . 'w';
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

    $mainPhoto = is_array($animal->pictures) && count($animal->pictures) > 0
        ? $animal->pictures[0]
        : null;

    $mainSrc = $mainPhoto
        ? asset('images/animals/originals/'.$mainPhoto)
        : asset('images/billy.webp');
@endphp


<x-layouts.client>
    <section class="p-5 flex flex-col gap-6 leading-9 lg:px-20 mb-0">
        <a
            href="{{ route('animals') }}"
            class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900
            mb-6 transition-colors"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span class="text-sm font-medium">Retour aux animaux</span>
        </a>
        <h2 class="text-4xl font-bold font-quicksand text-blueslate">
            {{ $animal->name }}
        </h2>

        <div class="2xl:flex 2xl:flex-row space-y-10 2xl:space-x-10">
            <div class="flex flex-col sm:flex-row gap-20">
                <div class="flex flex-col lg:flex-row gap-5">
                    <img
                        src="{{ $mainSrc }}"
                        @if($mainPhoto)
                            srcset="{{ $buildSrcset($mainPhoto) }}"
                        sizes="{{ $buildSizes($mainPhoto) }}"
                        @endif
                        alt="{{ $animal->name }}"
                        class="w-full h-full object-cover aspect-square md:max-h-[500px] md:max-w-[500px]
                        lg:w-[500px] lg:h-auto rounded-2xl 2xl:max-h-[500px]"
                    />
                    @if($animal->pictures && count($animal->pictures) > 1)
                        <div class="max-w-[640px] overflow-scroll md:max-w-[500px] flex flex-row lg:flex-col
                        lg:h-[500px] lg:max-w-24 gap-5 md:min-w-[72px]">
                            @foreach (array_slice($animal->pictures, 1) as $galleryImage)
                                <img
                                    src="{{ asset('images/animals/originals/'.$galleryImage) }}"
                                    srcset="{{ $buildSrcset($galleryImage) }}"
                                    sizes="96px"
                                    alt="{{ $animal->name }}"
                                    class="object-cover w-24 h-auto aspect-square max-h-24 rounded-2xl cursor-pointer"
                                />
                            @endforeach
                        </div>
                    @endif


                </div>

                <div class="md:max-h-[500px] space-y-2.5">
                    <h3 class="subsubtitle">{{ __('animals/client_show.features_title') }}</h3>

                    <div class="grid grid-cols-2 gap-5">
                        <p class="flex flex-col">
                            <span class="font-quicksand font-bold">{{ __('animals/client_show.features.age') }}</span>
                            <span
                                class="xsmalltext">{{ $animal->age }} {{ __('animals/client_show.features.years') }}</span>
                        </p>

                        <p class="flex flex-col">
                            <span
                                class="font-quicksand font-bold">{{ __('animals/client_show.features.gender') }}</span>
                            <span
                                class="xsmalltext">{{ __('animals/client_show.features.gender_'.$animal->sex) }}</span>
                        </p>

                        <p class="flex flex-col">
                            <span
                                class="font-quicksand font-bold">{{ __('animals/client_show.features.species') }}</span>
                            <span class="xsmalltext">{{ $animal->specie->name }}</span>
                        </p>

                        <p class="flex flex-col">
                            <span class="font-quicksand font-bold">{{ __('animals/client_show.features.coat') }}</span>
                            <span class="xsmalltext">{{ $animal->coat->name }}</span>
                        </p>

                        <p class="flex flex-col">
                            <span class="font-quicksand font-bold">{{ __('animals/client_show.features.chip') }}</span>
                            <span class="xsmalltext">{{ $animal->chip }}</span>
                        </p>

                        <p class="flex flex-col">
                            <span
                                class="font-quicksand font-bold">{{ __('animals/client_show.features.admission_date') }}</span>
                            <span class="xsmalltext">{{ $animal->admission_date }}</span>
                        </p>
                    </div>

                    <div class="space-y-5">

                        @if($animal->suitable)
                            <div>
                                <p class="font-quicksand font-bold">{{ __('animals/client_show.features.suitable_for') }}</p>
                                <div class="space-x-2.5">
                                    <span
                                        class="xsmalltext">{{ __('animals/client_show.features.suitable.'.$animal->suitable) }}</span>
                                </div>
                            </div>
                        @endif

                        <div>
                            <p class="font-quicksand font-bold">{{ __('animals/client_show.features.outside') }}</p>
                            <p class="xsmalltext">
                                {{ $animal->outside ? __('animals/client_show.features.outside_yes') : __('animals/client_show.features.outside_no') }}
                            </p>
                        </div>

                    </div>
                </div>

            </div>

            <div class="md:px-10 2xl:max-w-[500px]">
                <h3 class="subsubtitle">{{ __('animals/client_show.description_title') }}</h3>
                <p class="text leading-9 max-w-11/12 whitespace-pre-line">
                    {{ $animal->description }}
                </p>
            </div>
        </div>
    </section>

    <x-layouts.section :title="__('animals/client_show.interested_title')">
        <div class="flex flex-col md:flex-row md:justify-between gap-20">
            <div class="w-fit space-y-5 md:max-w-1/3">
                <div class="container space-y-10 md:max-h-[550px] md:overflow-scroll">
                    <p class="smalltext leading-8 whitespace-pre-line">
                        {{ __('animals/client_show.adoption_text') }}
                    </p>
                </div>
            </div>

            <div class="w-full">
                <form action="{{route('adoption_requests.store', $animal)}}" method="post" class="flex flex-col
                space-y-2.5">
                    @csrf
                    <input type="hidden" name="animal_id" value="{{ $animal->id }}">
                    <x-form.input_label
                        id="name"
                        type="text"
                        name="name"
                        :label="__('contact.name')"
                        :placeholder="__('contact.placeholder_name')"
                        :value="old('name')"
                        required
                    />

                    <x-form.input_label
                        id="email"
                        type="email"
                        name="email"
                        :label="__('contact.email')"
                        :placeholder="__('contact.placeholder_email')"
                        :value="old('email')"
                        required
                    />

                    <x-form.input_label
                        id="tel"
                        type="tel"
                        name="tel"
                        :label="__('contact.phone')"
                        :placeholder="__('contact.placeholder_phone')"
                        :value="old('tel')"
                        required
                    />
                    <fieldset class="">
                        <legend class="sr-only">{{ __('labels.address') }}</legend>
                        <div class="flex flex-col justify-center items-center sm:flex-row sm:gap-5">
                            <x-form.input_label
                                id="address"
                                type="text"
                                name="address"
                                :label="__('labels.street')"
                                :placeholder="__('placeholder.street')"
                                required
                                :value="old('address')"
                            />
                            <x-form.input_label
                                id="number"
                                type="text"
                                name="number"
                                :label="__('labels.number')"
                                :placeholder="__('placeholder.number')"
                                :value="old('number')"
                                required
                                class="sm:max-w-2/5"
                            />
                        </div>
                        <div class="flex flex-col justify-center items-center sm:flex-row sm:gap-5">
                            <x-form.input_label
                                id="cp"
                                type="text"
                                name="cp"
                                :label="__('labels.cp')"
                                :placeholder="__('placeholder.cp')"
                                required
                                :value="old('cp')"
                                class="sm:max-w-fit"
                            />
                            <x-form.input_label
                                id="city"
                                type="text"
                                name="city"
                                :label="__('labels.city')"
                                :placeholder="__('placeholder.city')"
                                required
                                :value="old('city')"
                            />
                        </div>
                    </fieldset>

                    <x-form.textarea_label
                        id="message"
                        name="message"
                        :label="__('contact.message')"
                        :placeholder="__('contact.placeholder_message')"
                        :value="old('message')"
                        required
                    />

                    <x-buttons.submit_button
                        icon="send"
                        class="flex-row-reverse button-yellow subsubtitle place-self-center"
                        svgclass="svg-strokeblack"
                    >
                        {{ __('contact.submit') }}
                    </x-buttons.submit_button>
                </form>
            </div>
        </div>
    </x-layouts.section>

    @if($otherAnimals->count() > 0)
        <x-layouts.section :title="__('animals/client_show.others_title')">
            <div class="flex flex-col items-center gap-5 sm:grid sm:grid-cols-2
            sm:justify-items-center lg:grid-cols-3 lg:gap-10 2xl:gap-20">

                @foreach ($otherAnimals as $otherAnimal)
                    @php
                        $photo = is_array($animal->pictures) && count($animal->pictures) > 0
                            ? $animal->pictures[0]
                            : null;

                        $src = $photo
                            ? asset('images/animals/originals/'.$photo)
                            : asset('images/billy.webp');
                    @endphp
                    <x-animal.card
                        name="{{ $otherAnimal->name }}"
                        src="{{ asset($src) }}"
                        srcset="{{ $buildSrcset($photo) }}"
                        sizes="{{ $buildSizes($photo) }}"
                        age="{{ $otherAnimal->age }}"
                        gender="{{ $otherAnimal->sex }}"
                        species="{{ $otherAnimal->specie->name }}"
                        description="{{ $otherAnimal->description }}"
                        href="{{ route('animals_show', $otherAnimal) }}"
                    />
                @endforeach

            </div>
        </x-layouts.section>
    @endif
    @if (session('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 2000)"
            x-show="show"
            x-transition
            class="fixed top-5 right-5 z-50
               bg-green-600 text-white
               px-6 py-4 rounded-xl shadow-lg
               flex items-center gap-3"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7" />
            </svg>

            <span class="font-medium">
            {{ session('success') }}
        </span>
        </div>
    @endif

</x-layouts.client>
