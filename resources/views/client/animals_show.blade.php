<x-layouts.client>
    <section class="p-5 flex flex-col gap-6 leading-9 lg:px-20 mb-0">
        <h2 class="text-4xl font-bold font-quicksand text-blueslate">
            Billy
        </h2>
        <div class="2xl:flex 2xl:flex-row space-y-10 2xl:space-x-10">
            <div class="flex flex-col sm:flex-row gap-20">
                <div class="flex flex-col lg:flex-row gap-5">
                    <img
                        src="{{ URL('images/billy.webp') }}"
                        alt="Billy"
                        class="w-full h-full object-cover aspect-square md:max-h-[500px] md:max-w-[500px] lg:w-[500px]
                    lg:h-auto
                    rounded-2xl 2xl:max-h-[500px]"
                    />
                    <div class="max-w-[640px] overflow-scroll md:max-w-[500px] flex flex-row
                    lg:flex-col lg:h-[500px] lg:max-w-24 gap-5 md:min-w-[72px]">
                        @foreach ([1,2,3,4,5,6,7,8,9] as $i)
                            <img
                                src="{{ URL('images/billy.webp') }}"
                                alt="Billy"
                                class="object-cover w-24 h-auto aspect-square max-h-24 max-h24 rounded-2xl"
                            />
                        @endforeach
                    </div>
                </div>
                <div class="md:max-h-[500px] space-y-2.5">
                    <h3 class="subsubtitle">Caractéristiques</h3>
                    <div class="grid grid-cols-2 gap-5 justify-content-center">
                        <p class="flex flex-col">
                            <span class="font-quicksand font-bold">Age</span>
                            <span class="xsmalltext">1 an</span>
                        </p>
                        <p class="flex flex-col">
                            <span class="font-quicksand font-bold">Race</span>
                            <span class="xsmalltext">Chien</span>
                        </p>
                        <p class="flex flex-col">
                            <span class="font-quicksand font-bold">Espèce</span>
                            <span class="xsmalltext">Golden Retriever</span>
                        </p>
                        <p class="flex flex-col">
                            <span class="font-quicksand font-bold">Pelage</span>
                            <span class="xsmalltext">Beige</span>
                        </p>
                    </div>
                    <div class="space-y-5">
                        <div>
                            <p class="font-quicksand font-bold">Convient pour</p>
                            <div class="space-x-2.5">
                                <span class="xsmalltext">Chien</span>
                                <span class="xsmalltext">Chat</span>
                                <span class="xsmalltext">Bébé</span>
                                <span class="xsmalltext">Enfant</span>
                            </div>
                        </div>
                        <div>
                            <p class="font-quicksand font-bold">Sortie</p>
                            <p class="xsmalltext">Les sorties ne posent pas de problème</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:px-10 2xl:max-w-[500px]">
                <h3 class="subsubtitle">Description</h3>
                <p class="text leading-9 max-w-11/12">
                    Billy est un chien doux, affectueux et plein d’énergie. Il adore passer du temps avec les humains et
                    se montre toujours enthousiaste lorsqu’il s’agit de jouer ou de partir en promenade. Très sociable,
                    il s’entend bien avec les autres chiens et n’a aucun problème à rencontrer de nouvelles personnes.
                    <br>
                    Il apprend vite, surtout lorsqu’on utilise des méthodes positives. Billy apprécie particulièrement
                    les activités en extérieur : courir, renifler, explorer… tout l’intéresse !<br>
                    Malgré son énergie, il sait aussi se poser et profiter d’un moment de calme auprès de ses humains de
                    confiance. Billy conviendrait parfaitement à une famille active ou à une personne qui aime passer du
                    temps dehors. Avec de la patience, de la bienveillance et un cadre stable, il deviendra un compagnon
                    fidèle et équilibré. <br>
                </p>
            </div>
        </div>
    </section>
    <x-layouts.section title="Interessé•e ?">
        <div class="flex flex-col md:flex-row md:justify-between gap-20">
            <div class="w-fit space-y-5 md:max-w-1/3">
                <div class="container space-y-10 md:max-h-[550px] md:overflow-scroll">
                    <p class="smalltext leading-8">
                        Adopter Billy, c’est offrir une nouvelle chance à un chien qui ne demande qu’à aimer et être
                        aimé.<br>
                        Notre équipe est disponible pour répondre à toutes vos questions et vous guider dans les
                        démarches d’adoption.<br>

                        Avant de nous envoyer votre demande, assurez-vous de pouvoir lui offrir :<br>
                        - du temps pour les promenades et les activités,<br>
                        - un environnement sécurisant,<br>
                        - de l’attention et de la stabilité,<br>
                        - beaucoup d’affection et de respect.<br>

                        Si vous pensez être la bonne famille pour lui, remplissez le formulaire ci-contre : nous vous
                        recontacterons rapidement pour discuter de votre projet d’adoption et organiser une
                        rencontre.<br>
                    </p>
                </div>
            </div>
            <div class="w-full">
                <form action="" method="post" class="flex flex-col space-y-2.5">
                    @csrf
                    <x-form.input_label
                        id="name"
                        type="text"
                        name="name"
                        :label="__('contact.name')"
                        :placeholder="__('contact.placeholder_name')"
                        required
                    />

                    <x-form.input_label
                        id="firstname"
                        type="text"
                        name="firstname"
                        :label="__('contact.firstname')"
                        :placeholder="__('contact.placeholder_firstname')"
                        required
                    />

                    <x-form.input_label
                        id="email"
                        type="email"
                        name="email"
                        :label="__('contact.email')"
                        :placeholder="__('contact.placeholder_email')"
                        required
                    />

                    <x-form.input_label
                        id="tel"
                        type="tel"
                        name="tel"
                        :label="__('contact.phone')"
                        :placeholder="__('contact.placeholder_phone')"
                        required
                    />

                    <x-form.textarea_label
                        id="message"
                        name="message"
                        :label="__('contact.message')"
                        :placeholder="__('contact.placeholder_message')"
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
    <x-layouts.section title="D'autres pourraient vous interressé">
        <div class="flex flex-col items-center gap-5 sm:grid sm:grid-cols-2 sm:justify-items-center
                lg:grid-cols-3 lg:gap-10  2xl:gap-20">
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
    </x-layouts.section>
</x-layouts.client>
