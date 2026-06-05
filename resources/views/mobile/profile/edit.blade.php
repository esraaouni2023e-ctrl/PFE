@extends(
    auth()->user()->isCounselor() 
        ? 'layouts.counselor' 
        : (auth()->user()->isSuperAdmin() || auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin'
            ? 'layouts.admin' 
            : 'layouts.student')
)

@section('title', 'Paramètres Profil')

@section('content')
<style>
    .prof-mob-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .prof-section-card {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 1.25rem;
        box-shadow: var(--shadow-card);
    }
    .prof-section-card.danger-card {
        border-color: color-mix(in srgb, var(--red) 25%, transparent);
        background: color-mix(in srgb, var(--red) 3%, var(--paper));
    }
    .prof-section-card h3 {
        font-family: var(--font-serif);
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: var(--ink);
    }
    .prof-section-card p.subtitle {
        font-size: 0.76rem;
        color: var(--ink60);
        margin-bottom: 1.25rem;
    }

    /* Style overrides for inputs and buttons inside profile forms on mobile */
    .prof-mob-container form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .prof-mob-container label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ink30);
        margin-bottom: 4px;
        display: block;
    }
    .prof-mob-container input, 
    .prof-mob-container select {
        width: 100%;
        min-height: 44px;
        padding: 0.65rem 0.85rem;
        background: var(--cream);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        color: var(--ink);
        font-family: inherit;
        font-size: 16px !important; /* Prevents auto-zoom */
        outline: none;
    }
    .prof-mob-container input:focus {
        border-color: var(--accent);
    }
    .prof-mob-container button[type="submit"],
    .prof-mob-container .primary-button {
        width: 100%;
        min-height: 44px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: var(--r);
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px color-mix(in srgb, var(--accent) 20%, transparent);
        cursor: pointer;
        transition: var(--transition);
        margin-top: 0.5rem;
    }
    .prof-mob-container button[type="submit"]:active {
        transform: translateY(1px);
    }
</style>

<div class="prof-mob-container">
    {{-- Header --}}
    <div>
        <h1 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 700; color: var(--ink); margin-bottom: 2px;">
            Mon Profil
        </h1>
        <p style="font-size: 0.8rem; color: var(--ink60);">Gérer l'accès et les détails de mon compte</p>
    </div>

    {{-- Details Section --}}
    <section class="prof-section-card">
        <h3>Détails du compte</h3>
        <p class="subtitle">Mettre à jour les informations de base et l'adresse e-mail</p>
        <div>
            @include('profile.partials.update-profile-information-form')
        </div>
    </section>

    {{-- Password Section --}}
    <section class="prof-section-card">
        <h3>Sécurité</h3>
        <p class="subtitle">Changer le mot de passe de connexion</p>
        <div>
            @include('profile.partials.update-password-form')
        </div>
    </section>

    {{-- Danger Zone Section --}}
    <section class="prof-section-card danger-card">
        <h3 style="color: var(--red);">Zone de danger</h3>
        <p class="subtitle">Supprimer définitivement votre compte et ses données</p>
        <div>
            @include('profile.partials.delete-user-form')
        </div>
    </section>
</div>
@endsection
