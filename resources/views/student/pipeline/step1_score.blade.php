@extends('layouts.student')
@section('title', 'Assistant Orientation — Étape 1')

@section('content')
<style>
/* ── Page wrapper ── */
.wizard-page { padding: 2.5rem 2.5rem 4rem; max-width: 800px; margin: 0 auto; font-family: var(--font-main); }

/* ── Eyebrow + Hero ── */
.wizard-eyebrow {
    display: inline-flex; align-items: center; gap: .5rem;
    font-size: .7rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
    color: var(--accent); margin-bottom: 1rem;
}
.wizard-eyebrow::before { content:''; width:14px; height:1px; background:var(--accent); }

.wizard-hero-title {
    font-family: var(--font-serif);
    font-size: clamp(2rem,4vw,2.8rem);
    font-weight: 300; letter-spacing: -.04em;
    font-style: italic; color: var(--ink); line-height: 1.1;
    margin-bottom: .85rem;
}
.wizard-hero-title em { color: var(--accent); font-style: italic; }

.wizard-hero-sub {
    font-size: .95rem; color: var(--ink60); max-width: 600px;
    line-height: 1.7; margin-bottom: 2rem;
}

/* ── Form Card ── */
.wizard-card {
    background: var(--paper); border: 1px solid var(--glass-border);
    border-radius: var(--rl); padding: 2.5rem; margin-bottom: 2rem;
    box-shadow: 0 10px 40px rgba(11,12,16,0.04);
}

.wizard-field { margin-bottom: 1.5rem; }
.wizard-label { display: block; font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--ink60); margin-bottom: .5rem; }
.wizard-input { width: 100%; padding: .85rem 1rem; background: var(--cream); border: 1px solid var(--ink10); border-radius: var(--r); font-family: var(--font-main); font-size: .95rem; color: var(--ink); transition: border-color .2s; }
.wizard-input:focus { outline: none; border-color: var(--accent); }

.wizard-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; }
@media(max-width: 600px) { .wizard-grid-2 { grid-template-columns: 1fr; } }

/* ── Notes matières ── */
.wizard-note-row { display: flex; align-items: center; gap: .75rem; padding: .75rem 1rem; background: var(--cream); border-radius: var(--r); border: 1px solid var(--ink10); margin-bottom: .5rem; }
.wizard-note-label { flex: 1; font-size: .85rem; font-weight: 500; color: var(--ink); }
.wizard-note-coef { font-size: .7rem; font-weight: 700; color: var(--accent); width: 35px; text-align: center; }
.wizard-note-input { width: 80px; padding: .5rem .75rem; background: var(--paper); border: 1px solid var(--ink15); border-radius: var(--r); text-align: center; font-family: var(--font-serif); font-size: 1.05rem; font-weight: 600; color: var(--ink); transition: border-color .2s; }
.wizard-note-input:focus { outline: none; border-color: var(--accent); }

/* ── CTA ── */
.wizard-cta { display: flex; align-items: center; justify-content: flex-end; gap: 1rem; margin-top: 2.5rem; border-top: 1px solid var(--ink06); padding-top: 1.5rem; }

.btn-fill {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .9rem 2.2rem; font-family: var(--font-main);
    font-size: .95rem; font-weight: 600; color: #fff;
    background: linear-gradient(135deg, var(--accent), var(--accent2)); border: none; border-radius: var(--r);
    cursor: pointer; text-decoration: none;
    box-shadow: 0 4px 18px color-mix(in srgb,var(--accent) 30%,transparent);
    transition: transform .25s var(--ease), box-shadow .25s var(--ease);
}
.btn-fill:hover { transform: translateY(-2px); box-shadow: 0 8px 28px color-mix(in srgb,var(--accent) 42%,transparent); }

/* ── Steps indicator ── */
.wizard-steps { display: flex; align-items: center; justify-content: center; gap: .5rem; margin-bottom: 2.5rem; }
.step-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--ink15); }
.step-dot.active { background: var(--accent); transform: scale(1.3); }
.step-line { width: 40px; height: 2px; background: var(--ink10); }

/* ── Flash ── */
.wizard-alert { padding: .875rem 1.125rem; border-radius: var(--r); font-size: .85rem; font-weight: 500; margin-bottom: 1.5rem; background: color-mix(in srgb,var(--accent) 8%,transparent); border: 1px solid color-mix(in srgb,var(--accent) 22%,transparent); color: var(--accent); }
</style>

<div class="wizard-page">

    {{-- ── Steps Indicator ── --}}
    <div class="wizard-steps">
        <div class="step-dot active" title="Étape 1 : Profil Académique"></div>
        <div class="step-line"></div>
        <div class="step-dot" title="Étape 2 : Test Psychologique"></div>
        <div class="step-line"></div>
        <div class="step-dot" title="Étape 3 : Recommandations"></div>
    </div>

    {{-- ── Eyebrow ── --}}
    <p class="wizard-eyebrow">Étape 1/3 · Profil Académique</p>

    {{-- ── Titre hero ── --}}
    <h1 class="wizard-hero-title">
        Calcul de ton <em>Score FG</em>
    </h1>
    <p class="wizard-hero-sub">
        Pour te proposer les meilleures filières, nous avons besoin de tes résultats au Baccalauréat. Ces informations nous permettront de calculer ton score d'accès à l'université.
    </p>

    @if(session('info'))
    <div class="wizard-alert">ℹ️ {{ session('info') }}</div>
    @endif
    @if($errors->any())
    <div class="wizard-alert" style="color:#ef4444; border-color:#ef4444; background:color-mix(in srgb,#ef4444 8%,transparent);">⚠️ {{ $errors->first() }}</div>
    @endif

    <form action="{{ route('student.pipeline.storeStep1') }}" method="POST">
        @csrf
        @php
            $currentSection = old('section_bac', $profile->section_bac ?? '');
        @endphp
        <div class="wizard-card">
            
            <div class="wizard-grid-2">
                <div class="wizard-field">
                    <label class="wizard-label" for="section_bac">Section du Bac</label>
                    <select class="wizard-input" id="section_bac" name="section_bac" required onchange="loadMatieres(this.value)">
                        <option value="">— Choisissez votre section —</option>
                        @foreach($sections as $s)
                            <option value="{{ $s }}" {{ $currentSection === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="wizard-field">
                    <label class="wizard-label" for="annee_bac">Année d'obtention</label>
                    <select class="wizard-input" id="annee_bac" name="annee_bac" required>
                        @for($y = date('Y') + 1; $y >= 2015; $y--)
                            <option value="{{ $y }}" {{ ($profile->annee_bac ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="wizard-grid-2">
                <div class="wizard-field">
                    <label class="wizard-label" for="moyenne_generale">Moyenne Générale</label>
                    <input type="number" class="wizard-input" id="moyenne_generale" name="moyenne_generale"
                           min="0" max="20" step="0.01"
                           value="{{ old('moyenne_generale', $profile->moyenne_generale ?? '') }}"
                           placeholder="Ex: 14.50" required>
                </div>
                <div class="wizard-field">
                    <label class="wizard-label" for="gouvernorat">Gouvernorat</label>
                    <select class="wizard-input" id="gouvernorat" name="gouvernorat" required>
                        <option value="">— Votre gouvernorat —</option>
                        @foreach($gouvernorats as $g)
                            <option value="{{ $g }}" {{ ($profile->gouvernorat ?? '') === $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-top: 2.5rem;">
                <label class="wizard-label">Notes par matière</label>
                <div id="notesContainer">
                    @php
                        $matiereLabels = \App\Services\ScoreFGService::MATIERES_LABELS;
                        $formules = [
                            'Mathématiques' => ['math'=>2,'sp'=>1.5,'svt'=>0.5,'fr'=>1,'ang'=>1],
                            'Sciences expérimentales' => ['math'=>1,'sp'=>1.5,'svt'=>1.5,'fr'=>1,'ang'=>1],
                            'Économie et gestion' => ['eco'=>1.5,'gest'=>1.5,'math'=>0.5,'hg'=>0.5,'fr'=>1,'ang'=>1],
                            'Technique' => ['tech'=>1.5,'math'=>1.5,'sp'=>1,'fr'=>1,'ang'=>1],
                            'Informatique' => ['algo'=>1.5,'sp'=>0.5,'sti'=>0.5,'fr'=>1,'ang'=>1],
                            'Lettres' => ['ar'=>1.5,'philo'=>1.5,'hg'=>1,'fr'=>1,'ang'=>1],
                            'Sport' => ['bio'=>1.5,'sport'=>1,'ep'=>0.5,'sp'=>0.5,'ph'=>0.5,'fr'=>1,'ang'=>1],
                        ];
                    @endphp
                    @if($currentSection)
                        <div style="font-size:.85rem;color:var(--ink60);margin-bottom:1rem;font-style:italic">
                            Saisissez les notes de votre relevé de baccalauréat.
                        </div>
                        @php
                            $section_matieres = $formules[$currentSection] ?? [];
                        @endphp
                        @foreach($section_matieres as $code => $coef)
                            <div class="wizard-note-row">
                                <div class="wizard-note-label">{{ $matiereLabels[$code] ?? $code }}</div>
                                <div class="wizard-note-coef">×{{ $coef }}</div>
                                <input type="number" class="wizard-note-input"
                                    name="notes_matieres[{{ $code }}]"
                                    min="0" max="20" step="0.25"
                                    value="{{ old('notes_matieres.'.$code, $profile->notes_matieres[$code] ?? '') }}"
                                    placeholder="—" required>
                            </div>
                        @endforeach
                    @else
                        <div id="notesPlaceholder" style="text-align:center;padding:2.5rem;color:var(--ink30);font-size:.88rem;background:var(--ink06);border-radius:var(--r);border:1px dashed var(--ink15);">
                            ← Sélectionnez votre section de Bac pour afficher les matières correspondantes.
                        </div>
                    @endif
                </div>
            </div>

            <div class="wizard-cta">
                <a href="{{ route('student.orientation') }}" style="color:var(--ink60); text-decoration:none; font-size:.9rem; font-weight:600; margin-right:auto; transition:color .2s;" onmouseover="this.style.color='var(--ink)'" onmouseout="this.style.color='var(--ink60)'">Annuler</a>
                <button type="submit" class="btn-fill">
                    Passer au test RIASEC ➔
                </button>
            </div>
        </div>
    </form>

</div>

<script>
const formulesData = @json($formules ?? []);
const labelsData = @json($matiereLabels ?? []);
const existingData = @json(old('notes_matieres', $profile->notes_matieres ?? []));

function loadMatieres(section) {
    const container = document.getElementById('notesContainer');
    if (!section) {
        container.innerHTML = '<div id="notesPlaceholder" style="text-align:center;padding:2.5rem;color:var(--ink30);font-size:.88rem;background:var(--ink06);border-radius:var(--r);border:1px dashed var(--ink15);">← Sélectionnez votre section de Bac en haut pour afficher les matières correspondantes.</div>';
        return;
    }
    
    const sectionMatieres = formulesData[section];
    if (!sectionMatieres) {
        container.innerHTML = '<div style="color:#ef4444;text-align:center;padding:1rem;">Section inconnue.</div>';
        return;
    }

    container.innerHTML = `<div style="font-size:.85rem;color:var(--ink60);margin-bottom:1rem;font-style:italic">Saisissez les notes de votre relevé de baccalauréat pour la section ${section}.</div>`;
    
    Object.entries(sectionMatieres).forEach(([code, coef]) => {
        const div = document.createElement('div');
        div.className = 'wizard-note-row';
        const label = labelsData[code] || code;
        const oldVal = existingData[code] || '';
        div.innerHTML = `
            <div class="wizard-note-label">${label}</div>
            <div class="wizard-note-coef">×${coef}</div>
            <input type="number" class="wizard-note-input" name="notes_matieres[${code}]"
                min="0" max="20" step="0.25" required
                value="${oldVal}" placeholder="—">
        `;
        container.appendChild(div);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const section = document.getElementById('section_bac').value;
    const placeholder = document.getElementById('notesPlaceholder');
    // Ne charger dynamiquement que s'il y a un placeholder (sinon le serveur a déjà rendu les champs)
    if (section && placeholder) {
        loadMatieres(section);
    }
});
</script>
@endsection
