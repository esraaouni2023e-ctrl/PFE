<x-auth-layout>
    <x-slot name="page-title">Vérification de l'e-mail</x-slot>

    <div style="margin-bottom:2rem;">
        <h2 class="heading-3 mb-2">Vérifiez votre <em>e-mail</em></h2>
        <p class="body-small">
            Merci de vous être inscrit ! Avant de commencer, pourriez-vous vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer ? Si vous n'avez pas reçu l'e-mail, nous vous en enverrons volontiers un autre.
        </p>
    </div>

    @if (session('message'))
        <div style="margin-bottom:1.5rem; padding:1rem; background:rgba(74, 124, 89, 0.1); border:1px solid rgba(74, 124, 89, 0.2); border-radius:var(--r); color:var(--accent3); font-size:0.875rem;">
            Un nouveau lien de vérification a été envoyé à l'adresse e-mail que vous avez fournie lors de l'inscription.
        </div>
    @endif

    <div style="display:flex; flex-direction:column; gap:1rem;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-futuristic">
                Renvoyer l'e-mail de vérification
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" style="text-align:center;">
            @csrf
            <button type="submit" style="background:none; border:none; color:var(--ink60); font-size:0.875rem; font-weight:500; cursor:pointer; transition:color 0.2s;" onmouseover="this.style.color='var(--ink)'" onmouseout="this.style.color='var(--ink60)'">
                Se déconnecter
            </button>
        </form>
    </div>
</x-auth-layout>
