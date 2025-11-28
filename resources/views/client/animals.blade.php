<x-layouts.client>
    <x-layouts.section title="Nos pensionnaires">
        <div class="">
            <input type="checkbox" id="filter" class="faq-toggle">
            <label for="filter" class="faq-header max-w-fit">
                <div class="w-fit">
                    <h3 class="button-green button-animation p-2 rounded-lg font-semibold flex gap-2
                    text-blueslate">
                        <span>
                            <x-svg.filter/>
                        </span>
                        Filtres
                    </h3>
                </div>
            </label>
            <div class="faq-content">
                <form action="" method="get" class="flex flex-col justify-center items-end">
                    <div class="grid grid-cols-2 gap-5 items-start w-full">
                        <x-form.select_label for="gender" label="Genre">
                            <option value="none">-- Sélectionner un genre --</option>
                            <option value="male">Mâle</option>
                            <option value="female">Femelle</option>
                        </x-form.select_label>
                        <x-form.select_label for="race" label="Race">
                            <option value="none">-- Sélectionner une race --</option>
                            <option value="dog">Chien</option>
                            <option value="chat">Chat</option>
                            <option value="hamster">Hamster</option>
                        </x-form.select_label>
                        <x-form.select_label for="species" label="Espèce">
                            <option value="none">-- Sélectionner une espèce --</option>
                            <option value="golden_retriever">Golden Retriever</option>
                            <option value="chihuahua">Chihuahua</option>
                            <option value="shiba_inu">Shiba Inu</option>
                        </x-form.select_label>
                        <x-form.select_label for="coat" label="Pelage">
                            <option value="none">-- Sélectionner un pelage --</option>
                            <option value="black">Noir</option>
                            <option value="brown">Brun</option>
                            <option value="beige">Beige</option>
                        </x-form.select_label>
                        <x-form.input_label
                            id="search"
                            type="search"
                            name="search"
                            label="Rechercher"
                            placeholder="Rechercher"
                            :value="old('search')"
                        />
                    </div>
                    <div class="flex gap-5">
                        <x-buttons.submit_button
                            class="button-yellow text-blueslate font-semibold "
                            icon="filter"
                            svgclass="svg-strokeblack">
                            Filtrer
                        </x-buttons.submit_button>
                        <x-buttons.submit_button
                            class="button-orange text-blueslate font-semibold "
                            icon="close"
                            svgclass="svg-strokeblack">
                            Reset
                        </x-buttons.submit_button>
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-col items-center gap-5 sm:grid sm:grid-cols-2 sm:justify-items-center
                lg:grid-cols-3 lg:gap-10 2xl:grid-cols-4 2xl:gap-20">
            @foreach ([1,2,3,4,5,6,7,8,9] as $i)
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

    </x-layouts.section>
</x-layouts.client>
