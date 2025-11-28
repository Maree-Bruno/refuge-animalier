<x-layouts.client>
    <x-layouts.section title="Besoin d’un renseignement ?" class="py-10">
        <div class="flex flex-col md:flex-row md:justify-between gap-20">
            <div class="w-fit space-y-5 md:max-w-1/3">
                <div class=" container space-y-10 md:max-h-[550px] md:overflow-scroll">
                    <div class="space-y-5">
                        <p>
                            Notre priorité reste le travail de terrain et l’accueil du public au refuge.
                        </p>
                        <p>
                            Nous faisons de notre mieux pour répondre à chaque message, mais les délais peuvent varier
                            selon l’affluence.
                        </p>
                        <p>
                            L’email est à privilégier pour toute demande non urgente.
                        </p>
                        <p>
                            Cela nous permet de traiter les messages plus efficacement tout en assurant le bien-être des
                            animaux sur place.
                        </p>
                    </div>
                </div>
                <div class="bg-honeyyellow p-5 rounded-xl flex gap-4 items-start">
                    <x-svg.warning class="w-8 h-8 shrink-0"/>
                    <p class="font-semibold">
                        En cas d’urgence vitale pour un animal, merci de ne pas attendre notre réponse.
                        Adressez-vous immédiatement à un vétérinaire ou aux autorités compétentes.
                    </p>
                </div>
            </div>

            {{-- Formulaire --}}
            <div class="w-full">
                <form action="" method="post" class="flex flex-col space-y-2.5">
                    @csrf

                    <x-form.input_label
                        id="name"
                        type="text"
                        name="name"
                        label="Nom"
                        placeholder="John Doe"
                        required
                        :value="old('name')"
                    />
                    <x-form.input_label
                        id="firstname"
                        type="text"
                        name="firstname"
                        label="Prénom"
                        placeholder="John Doe"
                        required
                        :value="old('firstname')"
                    />

                    <x-form.input_label
                        id="email"
                        type="email"
                        name="email"
                        label="Email"
                        placeholder="john@doe.com"
                        required
                        :value="old('email')"
                    />

                    <x-form.input_label
                        id="tel"
                        type="tel"
                        name="tel"
                        label="Téléphone"
                        placeholder="+32 493333464"
                        required
                        :value="old('tel')"
                    />

                    <x-form.textarea_label
                        id="message"
                        name="message"
                        label="Message"
                        placeholder="Votre message"
                        required
                        :value="old('message')"
                    />

                    <x-buttons.submit_button
                        icon="send"
                        class="flex-row-reverse button-yellow subsubtitle place-self-center"
                        svgclass="svg-strokeblack"
                    >
                        Envoyer
                    </x-buttons.submit_button>

                </form>
            </div>

        </div>
    </x-layouts.section>
</x-layouts.client>
