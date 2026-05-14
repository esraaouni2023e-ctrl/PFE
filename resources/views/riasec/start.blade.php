@extends('layouts.student')
@section('title', 'Test Psychométrique Complet — CapAvenir')

@section('content')
<style>
/* ── Page wrapper ── */
.riasec-page { padding: 2.5rem 2.5rem 4rem; max-width: 900px; margin: 0 auto; }

/* ── Eyebrow + Hero ── */
.riasec-eyebrow {
    display: inline-flex; align-items: center; gap: .5rem;
    font-size: .7rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
    color: var(--accent); margin-bottom: 1rem;
}
.riasec-eyebrow::before { content:''; width:14px; height:1px; background:var(--accent); }

.riasec-hero-title {
    font-family: var(--font-serif);
    font-size: clamp(2rem,4vw,3rem);
    font-weight: 300; letter-spacing: -.04em;
    font-style: italic; color: var(--ink); line-height: 1.1;
    margin-bottom: .85rem;
}
.riasec-hero-title em { color: var(--accent); font-style: italic; }

.riasec-hero-sub {
    font-size: .92rem; color: var(--ink60); max-width: 560px;
    line-height: 1.7; margin-bottom: 2rem;
}

/* ── Stats ── */
.riasec-stats { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2.5rem; }
.stat-chip {
    display: flex; align-items: center; gap: .5rem;
    padding: .45rem .9rem; border-radius: var(--rx);
    background: var(--ink06); border: 1px solid var(--glass-border);
    font-size: .78rem; font-weight: 600; color: var(--ink60);
}
.stat-chip strong { color: var(--ink); }

/* ── Resume banner ── */
.resume-banner {
    background: color-mix(in srgb, var(--accent) 6%, transparent);
    border: 1px solid color-mix(in srgb, var(--accent) 22%, transparent);
    border-radius: var(--rl); padding: 1.1rem 1.4rem;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;
}
.resume-banner-text { font-size: .85rem; color: var(--ink60); }
.resume-banner-text strong { color: var(--ink); }
.resume-banner-actions { display: flex; gap: .6rem; }

/* ── Dimensions grid ── */
.dim-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: .85rem; margin-bottom: 2.5rem; }
@media(max-width:640px){ .dim-grid{ grid-template-columns:repeat(2,1fr); } }

.dim-card {
    background: var(--ink06); border: 1px solid var(--glass-border);
    border-radius: var(--rl); padding: 1.1rem 1.2rem;
    transition: border-color .25s var(--ease), transform .25s var(--ease);
    cursor: default;
}
.dim-card:hover { border-color: var(--glass-border-vivid); transform: translateY(-3px); }
.dim-card-top { display: flex; align-items: center; gap: .6rem; margin-bottom: .5rem; }
.dim-letter {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: .9rem; color: #fff; flex-shrink: 0;
}
.dim-name  { font-size: .82rem; font-weight: 700; color: var(--ink); }
.dim-trait { font-size: .72rem; color: var(--ink30); margin-top: .15rem; }

/* ── Instructions card ── */
.instr-card {
    background: var(--ink06); border: 1px solid var(--glass-border);
    border-radius: var(--rl); padding: 1.5rem 1.8rem; margin-bottom: 2.5rem;
}
.instr-title {
    font-family: var(--font-serif); font-size: 1rem; font-style: italic;
    color: var(--ink); font-weight: 400; margin-bottom: 1rem;
}
.instr-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
@media(max-width:540px){ .instr-grid{ grid-template-columns:1fr; } }
.instr-item { display: flex; align-items: flex-start; gap: .6rem; }
.instr-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: .1rem; }
.instr-text { font-size: .82rem; color: var(--ink60); line-height: 1.6; }
.instr-text strong { color: var(--ink); }

/* ── CTA ── */
.riasec-cta { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
.phase-form {
    background: var(--ink06); border: 1px solid var(--glass-border);
    border-radius: var(--rl); padding: 1.5rem 1.8rem; margin-bottom: 2rem;
}
.phase-form-title {
    font-family: var(--font-serif); font-size: 1rem; font-style: italic;
    color: var(--ink); font-weight: 400; margin-bottom: .45rem;
}
.phase-form-note { font-size: .8rem; color: var(--ink60); line-height: 1.6; margin-bottom: 1.1rem; }
.phase-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
@media(max-width:680px){ .phase-grid{ grid-template-columns:1fr; } }
.phase-field { display: flex; flex-direction: column; gap: .4rem; }
.phase-field label { font-size: .76rem; font-weight: 700; color: var(--ink); }
.phase-field input,
.phase-field select,
.phase-field textarea {
    width: 100%; border: 1px solid var(--glass-border); border-radius: var(--r);
    background: color-mix(in srgb, var(--paper) 88%, #fff 12%);
    color: var(--ink); padding: .75rem .85rem; font: inherit; font-size: .84rem;
}
.phase-field textarea { min-height: 92px; resize: vertical; }
.phase-field.full { grid-column: 1 / -1; }
.phase-error { margin-top: .3rem; font-size: .72rem; color: #b42318; }

.btn-fill {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .8rem 1.8rem; font-family: var(--font-main);
    font-size: .88rem; font-weight: 600; color: #fff;
    background: var(--accent); border: none; border-radius: var(--r);
    cursor: pointer; text-decoration: none;
    box-shadow: 0 4px 18px color-mix(in srgb,var(--accent) 30%,transparent);
    transition: var(--transition);
}
.btn-fill:hover { transform: translateY(-2px); box-shadow: 0 8px 28px color-mix(in srgb,var(--accent) 42%,transparent); }

.btn-ghost {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .75rem 1.4rem; font-family: var(--font-main);
    font-size: .84rem; font-weight: 600; color: var(--ink60);
    background: transparent; border: 1px solid var(--glass-border);
    border-radius: var(--r); cursor: pointer; text-decoration: none;
    transition: var(--transition);
}
.btn-ghost:hover { color: var(--ink); border-color: var(--ink30); background: var(--ink06); }

.cta-note { font-size: .72rem; color: var(--ink30); margin-top: .5rem; }
</style>

<div class="riasec-page">

    {{-- ── Eyebrow ── --}}
    <p class="riasec-eyebrow">🧠 Test Psychométrique Complet · CapAvenir</p>

    {{-- ── Titre hero ── --}}
    <h1 class="riasec-hero-title">
        Découvre ton <em>profil</em><br>psychométrique complet
    </h1>
    <p class="riasec-hero-sub">
        Ce test combine <strong>4 dimensions scientifiques</strong> — RIASEC (Holland), Big Five (OCEAN),
        Aptitudes cognitives (GATB) et Valeurs (Schwartz) — pour générer des recommandations
        de filières précises et personnalisées.
    </p>

    {{-- ── Statistiques ── --}}
    <div class="riasec-stats">
        <div class="stat-chip">📝 <strong>{{ $totalQuestions }}</strong> questions</div>
        <div class="stat-chip">⏱ <strong>~15-20</strong> minutes</div>
        <div class="stat-chip">🎯 <strong>4</strong> blocs scientifiques</div>
        <div class="stat-chip">🔒 <strong>100%</strong> anonyme</div>
    </div>

    {{-- ── Grille des 4 blocs ── --}}
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.85rem;margin-bottom:2.5rem;">
        @foreach([
            ['icon'=>'🧭','label'=>'RIASEC · Holland',   'color'=>'#d4622a','desc'=>'6 dimensions vocales : Réaliste, Investigateur, Artistique, Social, Entreprenant, Conventionnel.'],
            ['icon'=>'🌊','label'=>'Big Five · OCEAN',   'color'=>'#1a4f6e','desc'=>'5 traits de personnalité : Ouverture, Conscienciosité, Extraversion, Agréabilité, Stabilité.'],
            ['icon'=>'⚡','label'=>'Aptitudes · GATB',   'color'=>'#c8973a','desc'=>'4 aptitudes cognitives : Intelligence générale, Verbal, Numérique, Spatial.'],
            ['icon'=>'💎','label'=>'Valeurs · Schwartz', 'color'=>'#4a7c59','desc'=>'4 valeurs fondamentales : Sécurité, Réussite, Bienveillance, Autonomie.'],
        ] as $bloc)
        <div class="dim-card">
            <div class="dim-card-top">
                <div class="dim-letter" style="background:{{ $bloc['color'] }};font-size:1rem;">{{ $bloc['icon'] }}</div>
                <span style="font-size:.85rem;font-weight:700;color:var(--ink);">{{ $bloc['label'] }}</span>
            </div>
            <div class="dim-trait" style="font-size:.75rem;">{{ $bloc['desc'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- ── Bannière test en cours ── --}}
    @if($hasOngoingTest ?? false)
    <div class="resume-banner">
        <div class="resume-banner-text">
            <strong>⏱ Test en cours</strong> —
            {{ $progress->answered }} / {{ $progress->total }} questions répondues
            ({{ round($progress->percentage) }}%)
        </div>
        <div class="resume-banner-actions">
            <a href="{{ route('riasec.question', ['step' => $progress->answered + 1]) }}" class="btn-fill">
                Continuer →
            </a>
            <form action="{{ route('riasec.initialize') }}" method="POST">
                @csrf <input type="hidden" name="restart" value="1">
                <button type="submit" class="btn-ghost"
                        onclick="return confirm('Effacer le test en cours ?')">
                    Recommencer
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- ── Flash ── --}}
    @if(session('info'))
    <div style="background:color-mix(in srgb,var(--accent2) 8%,transparent);border:1px solid color-mix(in srgb,var(--accent2) 22%,transparent);color:var(--accent2);border-radius:var(--r);padding:.65rem 1rem;margin-bottom:1.5rem;font-size:.83rem;">
        ℹ️ {{ session('info') }}
    </div>
    @endif



    {{-- ── Instructions ── --}}
    <div class="instr-card">
        <p class="instr-title">📌 Comment bien répondre ?</p>
        <div class="instr-grid">
            <div class="instr-item">
                <span class="instr-icon">🎯</span>
                <p class="instr-text">Réponds selon ce que tu <strong>ressens vraiment</strong>, pas selon la «bonne» réponse.</p>
            </div>
            <div class="instr-item">
                <span class="instr-icon">⚡</span>
                <p class="instr-text">Va vite : ta <strong>première réaction</strong> est souvent la plus honnête.</p>
            </div>
            <div class="instr-item">
                <span class="instr-icon">📊</span>
                <p class="instr-text">Échelle de <strong>1</strong> (Pas du tout) à <strong>5</strong> (Tout à fait) pour chaque question.</p>
            </div>
            <div class="instr-item">
                <span class="instr-icon">💾</span>
                <p class="instr-text">Tes réponses sont <strong>sauvegardées automatiquement</strong> à chaque étape.</p>
            </div>
        </div>
    </div>

    {{-- ── Phase initiale ── --}}
    @if(!($hasOngoingTest ?? false))
    <form action="{{ route('riasec.initialize') }}" method="POST">
        @csrf
        <div class="phase-form">
            <p class="phase-form-title">Avant de commencer</p>
            <p class="phase-form-note">
                Ces informations restent confidentielles et servent uniquement a contextualiser tes resultats
                d'orientation.
            </p>
            <div class="phase-grid">
                <div class="phase-field">
                    <label for="age">Age</label>
                    <input id="age" name="age" type="number" min="12" max="80" value="{{ old('age') }}" required>
                    @error('age') <span class="phase-error">{{ $message }}</span> @enderror
                </div>

                <div class="phase-field">
                    <label for="niveau_etudes">Niveau d'etudes actuel</label>
                    <select id="niveau_etudes" name="niveau_etudes" required>
                        <option value="">Choisir...</option>
                        @foreach(['Lycee', 'Prepa', 'Universite', 'Formation professionnelle', 'Autre'] as $level)
                            <option value="{{ $level }}" @selected(old('niveau_etudes') === $level)>{{ $level }}</option>
                        @endforeach
                    </select>
                    @error('niveau_etudes') <span class="phase-error">{{ $message }}</span> @enderror
                </div>

                <div class="phase-field full">
                    <label for="filieres_envisagees">Idees de filieres ou domaines deja envisages</label>
                    <textarea id="filieres_envisagees" name="filieres_envisagees" placeholder="Ex: informatique, medecine, design, commerce...">{{ old('filieres_envisagees') }}</textarea>
                    @error('filieres_envisagees') <span class="phase-error">{{ $message }}</span> @enderror
                </div>

                <div class="phase-field">
                    <label for="matieres_aimees">Matieres que tu aimes</label>
                    <textarea id="matieres_aimees" name="matieres_aimees" placeholder="Ex: maths, SVT, francais, economie...">{{ old('matieres_aimees') }}</textarea>
                    @error('matieres_aimees') <span class="phase-error">{{ $message }}</span> @enderror
                </div>

                <div class="phase-field">
                    <label for="matieres_detestees">Matieres que tu aimes moins</label>
                    <textarea id="matieres_detestees" name="matieres_detestees" placeholder="Ex: physique, histoire, langues...">{{ old('matieres_detestees') }}</textarea>
                    @error('matieres_detestees') <span class="phase-error">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="riasec-cta">
            <button type="submit" class="btn-fill" style="font-size:.95rem;padding:.9rem 2.2rem;">
                🚀 Démarrer le test
            </button>
            <a href="{{ route('student.orientation') }}" class="btn-ghost">
                ← Retour à l'orientation
            </a>
        </div>
        <p class="cta-note">Sans inscription requise · Résultats immédiats · Gratuit</p>
    </form>

    {{-- ── Simulation express (1 seul bouton) ── --}}
    @auth
    <div style="margin-top:2.5rem;padding-top:2rem;border-top:1px solid var(--glass-border);">
        <p style="font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--ink30);margin-bottom:.75rem;">
            ⚡ Mode rapide
        </p>
        <form action="{{ route('riasec.auto') }}" method="POST">
            @csrf
            <button type="submit"
                    class="btn-ghost"
                    style="border-color:color-mix(in srgb,var(--accent2) 40%,transparent);color:var(--accent2);gap:.6rem;"
                    onclick="this.disabled=true;this.innerHTML='⏳ Simulation en cours…';this.form.submit();">
                <span style="font-size:1.1rem;">⚡</span>
                <div style="text-align:left;">
                    <div style="font-size:.88rem;font-weight:700;">Simulation express</div>
                    <div style="font-size:.72rem;font-weight:400;color:var(--ink30);">Calcule le score · Passe le test · Génère les recommandations</div>
                </div>
            </button>
        </form>
    </div>
    @endauth
    @endif

</div>
@endsection
