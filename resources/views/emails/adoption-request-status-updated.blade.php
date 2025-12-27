<x-mail::message>
    # Mise à jour de votre demande d'adoption

    Bonjour {{ $adoptionRequest->adopter->name }},

    Votre demande d'adoption pour **{{ $adoptionRequest->animal->name }}** a été mise à jour.

    ## Nouveau statut

    @if($adoptionRequest->status === 'pending')
        **En cours d'examen**

        Votre demande est actuellement en cours d'examen par notre équipe. Nous prenons le temps d'étudier chaque demande avec attention pour assurer le bien-être de nos animaux.

        Nous vous tiendrons informé de l'évolution de votre demande.

    @elseif($adoptionRequest->status === 'accepted')
        **Acceptée** 🎉

        Excellente nouvelle ! Votre demande d'adoption a été acceptée.

        Notre équipe va vous contacter très prochainement pour organiser la rencontre avec **{{ $adoptionRequest->animal->name }}** et finaliser les démarches d'adoption.

        **Date d'acceptation :** {{ $adoptionRequest->adoption_date?->format('d/m/Y à H:i') }}

        <x-mail::button :url="url('/contact')">
            Nous contacter
        </x-mail::button>

    @elseif($adoptionRequest->status === 'rejected')
        **Refusée**

        Malheureusement, nous ne pouvons pas donner suite favorable à votre demande d'adoption pour **{{ $adoptionRequest->animal->name }}**.

        Cette décision a été prise en tenant compte de nombreux critères visant à assurer le meilleur placement pour l'animal.

        Nous vous encourageons à consulter nos autres animaux disponibles à l'adoption.

        <x-mail::button :url="url('/animals')">
            Voir les animaux disponibles
        </x-mail::button>

    @endif

    ---

    Si vous avez des questions, n'hésitez pas à nous contacter.

    Cordialement,
    {{ config('app.name') }}
</x-mail::message>
