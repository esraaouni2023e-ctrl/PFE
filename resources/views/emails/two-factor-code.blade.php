<x-mail::message>
# Bonjour {{ $user->name }},

Voici votre code de vérification pour vous connecter à votre compte. 

Ce code est valable pendant 10 minutes.

<x-mail::panel>
**{{ $user->two_factor_code }}**
</x-mail::panel>

Si vous n'avez pas demandé ce code, vous pouvez ignorer cet email en toute sécurité.

Merci,<br>
L'équipe {{ config('app.name') }}
</x-mail::message>
