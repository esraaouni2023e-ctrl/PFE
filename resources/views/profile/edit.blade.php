@extends('layouts.student')

@section('title', 'Mon Profil')

@section('content')
<div class="db" id="dbRoot">
    {{-- ════════════════════════════════
         § 1 · HEADER
    ════════════════════════════════ --}}
    <section class="db-hero" style="padding: 3.5rem 4rem 3rem;">
        <div class="db-hero-bgword">Profil</div>
        <div class="db-hero-orb"></div>

        <div class="db-hero-inner">
            <div class="db-hero-left">
                <div class="db-hero-eyebrow">
                    <span class="eyebrow-dot"></span>
                    Paramètres du compte
                </div>

                <h1 class="db-hero-title">
                    Gère ton<br>
                    <em>identité</em><br>
                    <strong>numérique.</strong>
                </h1>

                <p class="db-hero-sub">
                    Mets à jour tes informations personnelles, ton mot de passe et gère la sécurité de ton compte.
                </p>
            </div>

            <div class="db-hero-right">
                <div class="db-ring-wrap" style="width: 160px; height: 160px;">
                    <div class="db-ring-center">
                        <div class="avatar-nav" style="width: 80px; height: 80px; font-size: 2.5rem; border: 4px solid var(--paper); box-shadow: var(--shadow-card); overflow:hidden;">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════
         § 2 · CONTENT
    ════════════════════════════════ --}}
    <div class="profile-grid" style="display: flex; flex-direction: column; gap: 2rem; max-width: 900px; margin: 0 auto;">
        
        {{-- Profile Info --}}
        <section class="db-section rev">
            <div class="db-section-header">
                <div>
                    <p class="stag">Informations personnelles</p>
                    <h2 class="sh" style="font-size: 2rem;">Détails du <em>compte</em></h2>
                </div>
            </div>
            <div class="card" style="padding: 2.5rem; background: var(--cream);">
                <div style="max-width: 600px;">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </section>

        {{-- Password Update --}}
        <section class="db-section rev">
            <div class="db-section-header">
                <div>
                    <p class="stag">Sécurité</p>
                    <h2 class="sh" style="font-size: 2rem;">Mot de <em>passe</em></h2>
                </div>
            </div>
            <div class="card" style="padding: 2.5rem; background: var(--cream);">
                <div style="max-width: 600px;">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </section>

        {{-- Delete Account --}}
        <section class="db-section rev" style="margin-bottom: 5rem;">
            <div class="db-section-header">
                <div>
                    <p class="stag">Zone critique</p>
                    <h2 class="sh" style="font-size: 2rem; color: #ef4444;">Suppression <em>définitive</em></h2>
                </div>
            </div>
            <div class="card" style="padding: 2.5rem; background: color-mix(in srgb, #ef4444 5%, var(--cream)); border-color: color-mix(in srgb, #ef4444 20%, var(--ink10));">
                <div style="max-width: 600px;">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </section>
    </div>
</div>

<style>
/* ════════════════════════════════════════════
   REUSE DASHBOARD STYLES (Scoped to .db)
════════════════════════════════════════════ */
.db {
    --ink:     #0b0c10;
    --paper:   #f7f5f0;
    --cream:   #ede9e1;
    --warm:    #e8e1d4;
    --accent:  #d4622a;
    --accent2: #1a4f6e;
    --accent3: #4a7c59;
    --gold:    #c8973a;
    --ink60:   rgba(11,12,16,.6);
    --ink30:   rgba(11,12,16,.3);
    --ink15:   rgba(11,12,16,.15);
    --ink10:   rgba(11,12,16,.1);
    --ink06:   rgba(11,12,16,.06);
    --r:       6px;
    --rl:      16px;
    --rx:      999px;
    --ease:    cubic-bezier(.16,1,.3,1);

    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    padding: 2rem 3rem 5rem;
}

.db .rev { opacity: 0; transform: translateY(28px); transition: opacity .8s var(--ease), transform .8s var(--ease); }
.db .rev.vis { opacity: 1; transform: none; }

.db .stag {
    font-size: .72rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase;
    color: var(--accent); display: inline-flex; align-items: center; gap: .5rem; margin-bottom: 1rem;
}
.db .stag::before { content: ''; width: 18px; height: 1px; background: var(--accent); }

.db .sh {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.8rem, 3.5vw, 3rem);
    font-weight: 300; letter-spacing: -.03em; line-height: 1.1;
}
.db .sh em { font-style: italic; color: var(--accent); }

.db .card {
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: var(--rl);
    transition: all .3s var(--ease);
}
.db .card:hover { border-color: var(--ink30); }

.db-hero {
    position: relative;
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: 20px;
    padding: 4.5rem 4rem 4rem;
    overflow: hidden;
    margin-bottom: 3rem;
}

.db-hero-bgword {
    position: absolute;
    font-family: 'Fraunces', serif; font-weight: 300; font-style: italic;
    font-size: clamp(9rem, 19vw, 18rem);
    color: transparent;
    -webkit-text-stroke: 1px color-mix(in srgb, var(--ink) 5%, transparent);
    line-height: 1; letter-spacing: -.05em;
    right: -2%; top: 50%; transform: translateY(-50%);
    pointer-events: none; user-select: none;
}

.db-hero-orb {
    position: absolute; border-radius: 50%;
    width: 460px; height: 460px;
    background: radial-gradient(circle at 40% 40%,
        color-mix(in srgb, var(--accent) 14%, transparent),
        color-mix(in srgb, var(--accent2) 9%, transparent) 50%,
        transparent 75%);
    right: 3%; top: 50%; transform: translateY(-50%);
    pointer-events: none;
}

.db-hero-inner {
    position: relative; z-index: 10;
    display: grid; grid-template-columns: 1fr auto;
    align-items: center; gap: 4rem;
}

.db-hero-eyebrow {
    display: inline-flex; align-items: center; gap: .5rem;
    font-size: .75rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
    color: var(--accent); margin-bottom: 2rem;
}
.db-hero-eyebrow::before { content: ''; width: 18px; height: 1px; background: var(--accent); }

.db-hero-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(2.8rem, 5.5vw, 5.2rem);
    font-weight: 300; line-height: 1.04; letter-spacing: -.04em;
    margin-bottom: 1.5rem;
}
.db-hero-title em { font-style: italic; color: var(--accent); }
.db-hero-title strong { font-weight: 600; }

.db-hero-sub { font-size: 1rem; color: var(--ink60); line-height: 1.75; margin-bottom: 2.5rem; max-width: 480px; }

.db-section { margin-bottom: 3rem; }
.db-section-header {
    display: flex; justify-content: space-between; align-items: flex-end;
    margin-bottom: 1.75rem; gap: 1rem; flex-wrap: wrap;
}

/* Form Styles Adaptation */
.db input, .db select, .db textarea {
    background: var(--paper) !important;
    border: 1px solid var(--ink10) !important;
    border-radius: var(--r) !important;
    padding: 0.75rem 1rem !important;
    font-family: 'DM Sans', sans-serif !important;
    color: var(--ink) !important;
    width: 100%;
}
.db input:focus {
    border-color: var(--accent) !important;
    outline: none !important;
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 15%, transparent) !important;
}
.db label {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--ink60);
    margin-bottom: 0.5rem;
    display: block;
}
.db .mt-1 { margin-top: 0.25rem; }
.db .mt-2 { margin-top: 0.5rem; }
.db .mt-4 { margin-top: 1rem; }
.db .mt-6 { margin-top: 1.5rem; }

.db .primary-button {
    background: var(--accent);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: var(--r);
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}
.db .primary-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px color-mix(in srgb, var(--accent) 30%, transparent);
}

.db .secondary-button {
    background: var(--ink10);
    color: var(--ink);
    padding: 0.75rem 1.5rem;
    border-radius: var(--r);
    border: none;
    font-weight: 600;
    cursor: pointer;
}

@media (max-width: 860px) {
    .db-hero-inner { grid-template-columns: 1fr; }
    .db-hero-right { display: none; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const revEls = document.querySelectorAll('.db .rev');
    const revObs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); revObs.unobserve(e.target); } });
    }, { threshold: .08, rootMargin: '0px 0px -40px 0px' });
    revEls.forEach(el => revObs.observe(el));
});
</script>
@endsection