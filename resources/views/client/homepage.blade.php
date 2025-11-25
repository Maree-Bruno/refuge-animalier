<x-layouts.client>
    <div class="bg-lightgreenmint/40 shadow-[inset_0_-4px_30px_rgba(0,0,0,0.1)] py-8">
    <section class="p-5 flex flex-col gap-6 leading-9 md:flex-row-reverse md:items-center lg:px-28 backdrop-blur-md">
        <div class="group-img-homepage relative w-full place-items-center">
            <div class="relative group-img-homepage-1 place-self-end">
                <div class="rounded-3xl overflow-hidden shadow-[0px_4px_30px_0px_rgba(0,0,0,0.25)]">
                    <img
                        src="{{URL('images/billy.webp')}}"
                        alt="Hamster"
                        class="w-full h-full object-cover aspect-square lg:w-60 lg:h-auto"
                    />
                </div>
                <div class="absolute -top-4 -left-4 bg-sweetorange rounded-2xl p-4 shadow-lg">
                    <x-svg.linkedin/>
                </div>
            </div>

            <div class="relative group-img-homepage-2 place-self-start">
                <div class="rounded-3xl overflow-hidden shadow-[0px_4px_30px_0px_rgba(0,0,0,0.25)]">
                    <img
                        src="{{URL('images/billy.webp')}}"
                        alt="Chat"
                        class="w-full h-full object-cover aspect-square lg:w-60 lg:h-auto"
                    />
                </div>
                <div class="absolute -top-4 -right-4 bg-blueslate rounded-2xl p-4 shadow-lg">
                    <x-svg.linkedin/>

                </div>
            </div>

            <div class="relative group-img-homepage-3">
                <div class="rounded-3xl overflow-hidden shadow-[0px_4px_30px_0px_rgba(0,0,0,0.25)]">
                    <img
                        src="{{URL('images/billy.webp')}}"
                        alt="Chien"
                        class="w-full h-full object-cover aspect-square lg:w-60 lg:h-auto"
                    />
                </div>
                <div class="absolute -bottom-4 -right-4 bg-honeyyellow rounded-2xl p-4 shadow-lg">
                    <x-svg.linkedin/>

                </div>
            </div>
        </div>
        <div class=" flex flex-col gap-2.5 lg:w-11/12">
            <h2 class="text-4xl font-bold font-quicksand">
                Parce que sauver un animal, c'est sauver le monde. Un cœur à la fois.
            </h2>
            <p class="text-xl leading-9">
                Chez Happy Paws, nous recueillons, soignons et replaçons les animaux
                abandonnés ou maltraités. Chaque adoption est une nouvelle chance, un nouveau départ, et un pas de
                plus vers un monde plus bienveillant.
            </p>
            <div class="w-fit flex flex-wrap gap-2.5">
                <x-buttons.button_link_icons
                    icon="paws"
                    class="button-yellow"
                    href="{{route('animals')}}"
                >
                    Adoptez !
                </x-buttons.button_link_icons>
                <x-buttons.button_link_icons
                    icon="arrow_right"
                    class="button-orange flex-row-reverse"
                    href="{{route('about')}}"
                >
                    En savoir plus
                </x-buttons.button_link_icons>
            </div>
        </div>
    </section>
    </div>

    <x-layouts.section title="Quelques chiffres" class="relative space-y-10">
        <div class="flex flex-wrap flex-row justify-center gap-5 sm:gap-10 lg:flex-nowrap lg:justify-between lg:px-14">
            <x-homepage.stats class="bg-lightgreenmint/60 border-2 border-greenmint" text="C’est le nombre d’adoption cette année."
                              number="60"/>
            <x-homepage.stats class="bg-lightsweetorange/60 border-2 border-sweetorange" text="C’est le nombre d’animaux recueillis
            cette année."
                              number="120"/>
            <x-homepage.stats class="bg-lighthoneyyellow/60 border-2 border-honeyyellow" text="C’est le nombre
            d’animaux ayant
            retrouvé une famille."
                              number="300"/>
        </div>
        <p class="text leading-9 max-w-3/4 self-center border border-transparent p-5 rounded-2xl bg-blueslate
        text-white">
            Chaque statistique représente bien plus qu’un chiffre : c’est une
            histoire qui évolue, une vie qui reprend confiance. Grâce à votre soutien, nous pouvons accueillir,
            soigner et offrir une seconde chance à ceux qui en ont besoin. Ensemble, bâtissons un refuge où chaque
            animal trouve enfin sa place.
        </p>
    </x-layouts.section>
    <x-layouts.section title="Nos pensionnaires prêt à l’adoption" class="relative">
        <div class="flex flex-col gap-5 items-center justify-center md:flex-row">
            <x-animal.card
                name="Billy"
                age="1 an"
                gender="Mâle"
                species="Golden Retriever"
                src="{{URL('images/billy.webp')}}"
                description="Billy est un chien doux, affectueux et plein d’énergie. Il adore passer du temps avec les
            humains et se montre toujours enthousiaste lorsqu’il s’agit de jouer ou de partir en promenade. Très
            sociable, il s’entend bien avec les autres chiens et n’a aucun problème à rencontrer de nouvelles
            personnes. Il apprend vite, surtout lorsqu’on utilise des méthodes positives. Billy apprécie
            particulièrement les activités en extérieur : courir, renifler, explorer… tout l’intéresse ! Malgré son énergie, il sait aussi se poser et profiter d’un moment de calme auprès de ses humains de confiance. Billy conviendrait parfaitement à une famille active ou à une personne qui aime passer du temps dehors. Avec de la patience, de la bienveillance et un cadre stable, il deviendra un compagnon fidèle et équilibré."
            />
            <x-animal.card
                name="Billy"
                age="1 an"
                gender="Mâle"
                species="Golden Retriever"
                src="{{URL('images/billy.webp')}}"
                description="Billy est un chien doux, affectueux et plein d’énergie. Il adore passer du temps avec les
            humains et se montre toujours enthousiaste lorsqu’il s’agit de jouer ou de partir en promenade. Très
            sociable, il s’entend bien avec les autres chiens et n’a aucun problème à rencontrer de nouvelles
            personnes. Il apprend vite, surtout lorsqu’on utilise des méthodes positives. Billy apprécie
            particulièrement les activités en extérieur : courir, renifler, explorer… tout l’intéresse ! Malgré son énergie, il sait aussi se poser et profiter d’un moment de calme auprès de ses humains de confiance. Billy conviendrait parfaitement à une famille active ou à une personne qui aime passer du temps dehors. Avec de la patience, de la bienveillance et un cadre stable, il deviendra un compagnon fidèle et équilibré."
            />
            <x-animal.card
                name="Billy"
                age="1 an"
                gender="Mâle"
                species="Golden Retriever"
                src="{{URL('images/billy.webp')}}"
                description="Billy est un chien doux, affectueux et plein d’énergie. Il adore passer du temps avec les
            humains et se montre toujours enthousiaste lorsqu’il s’agit de jouer ou de partir en promenade. Très
            sociable, il s’entend bien avec les autres chiens et n’a aucun problème à rencontrer de nouvelles
            personnes. Il apprend vite, surtout lorsqu’on utilise des méthodes positives. Billy apprécie
            particulièrement les activités en extérieur : courir, renifler, explorer… tout l’intéresse ! Malgré son énergie, il sait aussi se poser et profiter d’un moment de calme auprès de ses humains de confiance. Billy conviendrait parfaitement à une famille active ou à une personne qui aime passer du temps dehors. Avec de la patience, de la bienveillance et un cadre stable, il deviendra un compagnon fidèle et équilibré."
            />
        </div>
        <x-buttons.button_link_icons icon="arrow_right" class="button-green flex-row-reverse self-end">
            Voir tous nos pensionnaires
        </x-buttons.button_link_icons>
    </x-layouts.section>
    <div class="bg-lighthoneyyellow/40 shadow-inner py-10">
        <x-layouts.section title="Foire aux questions (FAQ)" class="relative">
            <div class="flex flex-col space-y-5 divide-y divide-blueslate">
                <x-homepage.faq
                    id="1"
                    title="Comment adopter un animal dans votre refuge ?"
                    content="Il suffit de nous rendre visite pendant les horaires d’ouverture, de rencontrer l’animal, puis de remplir un formulaire d’adoption. Un entretien est effectué pour s’assurer que l’adoption correspond bien à votre mode de vie. Une participation aux frais vétérinaires est demandée."
                />
                <x-homepage.faq
                    id="2"
                    title="Comment adopter un animal dans votre refuge ?"
                    content="Il suffit de nous rendre visite pendant les horaires d’ouverture, de rencontrer l’animal, puis de remplir un formulaire d’adoption. Un entretien est effectué pour s’assurer que l’adoption correspond bien à votre mode de vie. Une participation aux frais vétérinaires est demandée."
                />
                <x-homepage.faq
                    id="3"
                    title="Comment adopter un animal dans votre refuge ?"
                    content="Il suffit de nous rendre visite pendant les horaires d’ouverture, de rencontrer l’animal, puis de remplir un formulaire d’adoption. Un entretien est effectué pour s’assurer que l’adoption correspond bien à votre mode de vie. Une participation aux frais vétérinaires est demandée."
                />
            </div>
        </x-layouts.section>
    </div>
</x-layouts.client>
