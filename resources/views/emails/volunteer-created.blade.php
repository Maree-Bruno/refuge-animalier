@component('mail::message')
    # Bienvenue {{ $user->name }} !

    Votre compte bénévole a été créé avec succès.

    Voici vos identifiants de connexion :

    **Email :** {{ $user->email }}
    **Mot de passe :** {{ $password }}

    @component('mail::button', ['url' => route('login')])
        Se connecter
    @endcomponent

    **Important :** Pour des raisons de sécurité, nous vous recommandons de changer votre mot de passe lors de votre première connexion.

    Cordialement,
    {{ config('app.name') }}
@endcomponent
