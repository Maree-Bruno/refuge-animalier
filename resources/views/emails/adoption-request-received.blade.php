<x-mail::message>
    # Demande d'adoption bien reçue

    Bonjour {{ $adoptionRequest->adopter->name }},

    Nous avons bien reçu votre demande d'adoption pour **{{ $adoptionRequest->animal->name }}**.

    ## Détails de votre demande

    - **Animal :** {{ $adoptionRequest->animal->name }}
    - **Date de la demande :** {{ $adoptionRequest->request_date->format('d/m/Y à H:i') }}
    - **Statut :** En attente de traitement

    ## Votre message

    {{ $adoptionRequest->message }}

    ---

    Notre équipe va étudier votre demande dans les meilleurs délais. Vous recevrez une notification par email dès qu'une décision sera prise.

    Si vous avez des questions, n'hésitez pas à nous contacter.

    Merci pour votre intérêt,<br>
    {{ config('app.name') }}
</x-mail::message>
