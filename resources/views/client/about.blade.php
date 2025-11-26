<x-layouts.client>
    <div class="bg-white shadow-[inset_0_-4px_30px_rgba(0,0,0,0.1)] py-4 flex justify-center">
        <section class="p-5 flex flex-col gap-6 leading-9 sm:flex-row lg:px-10 lg:justify-between xl:max-w-10/12
        xl:gap-20">
            <div class="aspect-square max-h-80 place-self-center sm:max-h-none">
                <img
                    src="{{URL('images/billy.webp')}}"
                    alt="Photo de Billy"
                    class=" h-full aspect-square rounded-tl-[20px] rounded-tr-[20px] rounded-bl-[500px]
                    rounded-br-[500px] object-cover object-top
                    object-fit-fill xl:max-h-[500px]">
            </div>
            <div class=" flex flex-col space-y-5 lg:py-10 lg:w-3/4 xl:max-w-2/5">
                <h2 class="title">
                    Qui sommes-nous ?
                </h2>
                <p class="subsubtitle leading-8">
                    Nous sommes Happy Paws. Un refuge pour animaux responsable.
                </p>
                <p class="text-lg lg:text leading-8">
                    Nous offrons un environnement sécurisant et bienveillant aux animaux en situation d’abandon dans
                    notre refuge situé à Passendale.
                </p>
                <p class="text-lg lg:text leading-8">
                    Chaque jour, nos équipes se mobilisent pour garantir leur santé, leur bien-être et leur offrir une
                    seconde chance.
                </p>
            </div>
        </section>
    </div>
    <x-layouts.section title="Nos valeurs">
        <div class="grid sm:grid-cols-2 gap-5 justify-items-center about-card-display">
            <x-about.value-card
                title="Bienveillance"
                text="Chaque animal est accueilli avec douceur et respect. Nous croyons que l'amour et la patience sont les clés de leur réhabilitation."
                icon="linkedin"
                iconclass="lg:scale-200"
                bg="bg-honeyyellow"/>

            <x-about.value-card
                title="Transparence"
                text="Nos portes sont ouvertes. Découvrez comment nous prenons soin de nos protégés et où va chaque don que vous nous confiez."
                icon="linkedin"
                iconclass="lg:scale-200"
                bg="bg-greenmint"/>

            <x-about.value-card
                title="Engagement"
                text="24h/24, 7j/7, notre équipe veille sur le bien-être de chaque pensionnaire. Leur bonheur est notre mission quotidienne."
                icon="linkedin"
                iconclass="lg:scale-200"
                bg="bg-sweetorange"/>

            <x-about.value-card
                title="Communauté"
                text="Ensemble, nous créons un réseau de soutien solide. Bénévoles, adoptants et donateurs : vous êtes le cœur battant de notre refuge."
                icon="linkedin"
                iconclass=" svg-fillwhite lg:scale-200"
                bg="bg-blueslate"/>
        </div>
    </x-layouts.section>
    <div class="bg-lighthoneyyellow/40 shadow-inner py-10">
        <x-layouts.section title="Comment fonctionnons-nous ?">
            <div class="flex flex-col justify-center items-center space-y-16 lg:space-y-24">
                <x-about.procedure
                    src="{{URL('images/billy.webp')}}"
                    alt="Photo de Billy"
                    step="1"
                    title="Cherchez votre compagnon."
                    text="Consultez notre catalogue d’animaux disponibles à l’adoption et trouvez celui qui correspond
                            à votre mode de vie et à votre environnement. Chaque profil est décrit avec soin pour vous
                            aider dans votre choix."
                    link="{{route('animals')}}"
                    icon="arrow_right"
                    button_class="button-orange flex-row-reverse"
                    label="Tous nos animaux"
                />
                <x-about.procedure
                    reverse="true"
                    src="{{URL('images/billy.webp')}}"
                    alt="Photo de Billy"
                    step="2"
                    title="Remplissez votre demande d’adoption."
                    text="Complétez notre formulaire en ligne afin que nous puissions mieux comprendre votre situation, vos attentes et le cadre de vie que vous pourrez offrir à l’animal."
                />
                <x-about.procedure
                    src="{{URL('images/billy.webp')}}"
                    alt="Photo de Billy"
                    step="3"
                    title="Nous traitons votre demande."
                    text="Notre équipe analyse votre dossier avec attention. Selon le profil de l’animal, nous pouvons vous contacter pour un entretien complémentaire afin de garantir la meilleure compatibilité."
                />
                <x-about.procedure
                    reverse="true"
                    src="{{URL('images/billy.webp')}}"
                    alt="Photo de Billy"
                    step="4"
                    title="Vous rencontrez l’animal."
                    text="Nous organisons une première rencontre dans un cadre sécurisé. Vous pourrez découvrir son caractère, poser vos questions et passer un moment avec lui pour confirmer votre choix."
                />
                <x-about.procedure
                    src="{{URL('images/billy.webp')}}"
                    alt="Photo de Billy"
                    step="5"
                    title="Nous finalisons avec vous les derniers détails."
                    text="Signature du contrat d’adoption, conseils personnalisés, carnet de santé, alimentation recommandée… nous vous accompagnons pour une transition réussie."
                />
                <x-about.procedure
                    reverse="true"
                    src="{{URL('images/billy.webp')}}"
                    alt="Photo de Billy"
                    step="6"
                    title="Félicitations ! Vous vous êtes trouvé un ami pour toute sa vie !"
                    text="Votre nouveau compagnon rejoint votre foyer. Nous restons disponibles après l’adoption pour vous soutenir si vous avez des questions ou besoin de conseils."
                />
            </div>

        </x-layouts.section>
    </div>

</x-layouts.client>
