@extends('layouts.admin')
@section('title', $question ? 'Modifier la question' : 'Nouvelle question RIASEC')

@section('content')
<style>
    /* ════════════════════════════════════════════
       CAPAVENIR RIASEC QUESTION FORM PANEL
    ════════════════════════════════════════════ */
    .form-wrapper {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        font-family: var(--font-main);
        color: var(--ink);
    }

    .form-card {
        max-width: 800px;
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 2.5rem;
        box-shadow: var(--shadow-card);
    }

    .form-title {
        font-family: var(--font-serif);
        font-size: 1.4rem;
        font-style: italic;
        font-weight: 400;
        color: var(--ink);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 1px solid var(--glass-border);
        padding-bottom: 1rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        margin-bottom: 1.5rem;
    }
    .form-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--ink60);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .form-label span {
        color: var(--red);
        margin-left: 2px;
    }

    .form-control {
        width: 100%;
        font-family: var(--font-main);
        font-size: 0.875rem;
        background: var(--ink06);
        border: 1px solid var(--glass-border);
        color: var(--ink);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        outline: none;
        transition: var(--transition);
    }
    .form-control:focus {
        border-color: var(--accent);
        background: var(--paper);
    }
    .form-control.error {
        border-color: var(--red);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
        line-height: 1.6;
    }

    .form-hint {
        font-size: 0.75rem;
        color: var(--ink30);
        margin-top: 0.35rem;
        line-height: 1.4;
    }
    .form-error {
        font-size: 0.75rem;
        color: var(--red);
        margin-top: 0.3rem;
        font-weight: 600;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    @media(max-width: 600px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.25rem;
    }
    @media(max-width: 700px) {
        .form-row-3 {
            grid-template-columns: 1fr;
        }
    }

    /* Dimension Picker */
    .dim-picker {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-top: 0.25rem;
    }
    .dim-option input {
        display: none;
    }
    .dim-option label {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        font-family: var(--font-serif);
        font-weight: 700;
        font-size: 1.15rem;
        cursor: pointer;
        border: 2px solid var(--glass-border);
        background: var(--ink06);
        color: var(--ink60);
        transition: var(--transition);
        user-select: none;
    }
    .dim-option label:hover {
        border-color: var(--ink30);
        color: var(--ink);
    }
    .dim-option input:checked + label {
        color: white;
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .char-counter {
        float: right;
        font-size: 0.7rem;
        color: var(--ink30);
        font-weight: 600;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--glass-border);
    }

    .btn-action-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: var(--r);
        font-size: 0.85rem;
        font-weight: 700;
        background: var(--accent2);
        color: white;
        text-decoration: none;
        border: 1px solid var(--accent2);
        transition: var(--transition);
        cursor: pointer;
    }
    .btn-action-primary:hover {
        background: color-mix(in srgb, var(--accent2) 90%, #000);
        border-color: color-mix(in srgb, var(--accent2) 90%, #000);
        transform: translateY(-1px);
    }
    
    .btn-action-glass {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.75rem 1.5rem;
        border-radius: var(--r);
        font-size: 0.85rem;
        font-weight: 600;
        background: var(--ink06);
        color: var(--ink60);
        text-decoration: none;
        border: 1px solid var(--glass-border);
        transition: var(--transition);
        cursor: pointer;
    }
    .btn-action-glass:hover {
        background: var(--ink10);
        color: var(--ink);
        border-color: var(--ink30);
    }
</style>

<div class="form-wrapper">
    {{-- Back Nav --}}
    <div style="margin-bottom: 0.5rem;">
        <a href="{{ route('admin.riasec.questions.index') }}" class="btn-action-glass" style="padding: 0.5rem 1rem; font-size: 0.8rem;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à l'inventaire
        </a>
    </div>

    @php
        $dimConfig = [
            'R' => ['color'=>'#f97316','label'=>'Réaliste — activités concrètes et manuelles'],
            'I' => ['color'=>'#3b82f6','label'=>'Investigateur — curiosité scientifique et analytique'],
            'A' => ['color'=>'#ec4899','label'=>'Artistique — créativité et expression libre'],
            'S' => ['color'=>'#10b981','label'=>'Social — aide, enseignement, empathie'],
            'E' => ['color'=>'#8b5cf6','label'=>'Entreprenant — leadership, persuasion et projets'],
            'C' => ['color'=>'#94a3b8','label'=>'Conventionnel — organisation, précision et rigueur'],
        ];
    @endphp

    <div class="form-card">
        <h3 class="form-title">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            {{ $question ? 'Modifier la Question' : 'Créer une Nouvelle Question' }}
        </h3>

        <form action="{{ $question ? route('admin.riasec.questions.update', $question) : route('admin.riasec.questions.store') }}" method="POST">
            @csrf
            @if($question)
                @method('PUT')
            @endif

            {{-- Dimension --}}
            <div class="form-group">
                <label class="form-label">Dimension RIASEC <span>*</span></label>
                <div class="dim-picker">
                    @foreach($dimConfig as $d => $cfg)
                        <div class="dim-option">
                            <input type="radio" name="dimension" id="dim_{{ $d }}" value="{{ $d }}"
                                   {{ old('dimension', $question?->dimension) === $d ? 'checked' : '' }}>
                            <label for="dim_{{ $d }}"
                                   style="{{ old('dimension', $question?->dimension) === $d ? 'background:'.$cfg['color'].'; border-color:'.$cfg['color'] : '' }}"
                                   title="{{ $cfg['label'] }}"
                                   onclick="this.previousElementSibling.checked=true; updateDimLabels()">
                                {{ $d }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('dimension') <p class="form-error">{{ $message }}</p> @enderror
                <p class="form-hint" id="dim-hint" style="margin-top: 0.5rem; font-weight: 600; color: var(--accent2);">
                    {{ $question ? ($dimConfig[$question->dimension]['label'] ?? '') : 'Sélectionnez le code Holland cible.' }}
                </p>
            </div>

            {{-- Category & Response Type --}}
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="categorie">Catégorie d'évaluation <span>*</span></label>
                    <select name="categorie" id="categorie" class="form-control {{ $errors->has('categorie') ? 'error' : '' }}" style="cursor: pointer;">
                        <option value="">— Choisir —</option>
                        <option value="loisirs" {{ old('categorie', $question?->categorie) === 'loisirs' ? 'selected' : '' }}>Loisirs & Centres d'intérêt</option>
                        <option value="preferences_professionnelles" {{ old('categorie', $question?->categorie) === 'preferences_professionnelles' ? 'selected' : '' }}>Préférences Professionnelles</option>
                        <option value="qualites_personnelles" {{ old('categorie', $question?->categorie) === 'qualites_personnelles' ? 'selected' : '' }}>Qualités Personnelles</option>
                    </select>
                    @error('categorie') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="type_reponse">Type de Réponse <span>*</span></label>
                    <select name="type_reponse" id="type_reponse" class="form-control {{ $errors->has('type_reponse') ? 'error' : '' }}" style="cursor: pointer;">
                        <option value="likert" {{ old('type_reponse', $question?->type_reponse ?? 'likert') === 'likert' ? 'selected' : '' }}>Likert (Échelle de 1 à 5)</option>
                        <option value="boolean" {{ old('type_reponse', $question?->type_reponse) === 'boolean' ? 'selected' : '' }}>Binaire (Oui / Non)</option>
                        <option value="choice" {{ old('type_reponse', $question?->type_reponse) === 'choice' ? 'selected' : '' }}>Choix Multiple</option>
                    </select>
                    @error('type_reponse') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Text FR --}}
            <div class="form-group">
                <label class="form-label" for="texte_fr">
                    Texte de la Question (Français) <span>*</span>
                    <span class="char-counter" id="cnt-fr">0/500</span>
                </label>
                <textarea name="texte_fr" id="texte_fr" maxlength="500"
                          class="form-control {{ $errors->has('texte_fr') ? 'error' : '' }}"
                          oninput="document.getElementById('cnt-fr').textContent=this.value.length+'/500'"
                          placeholder="Rédigez l'affirmation comportementale (ex: J'aime organiser les activités d'un groupe...)">{{ old('texte_fr', $question?->texte_fr) }}</textarea>
                @error('texte_fr') <p class="form-error">{{ $message }}</p> @enderror
                <p class="form-hint">
                    Utilisez le présent de l'indicatif et la première personne du singulier.
                </p>
            </div>

            {{-- Text AR --}}
            <div class="form-group">
                <label class="form-label" for="texte_ar">
                    Texte de la Question (Arabe - Optionnel)
                    <span class="char-counter" id="cnt-ar">0/500</span>
                </label>
                <textarea name="texte_ar" id="texte_ar" maxlength="500" dir="rtl"
                          class="form-control {{ $errors->has('texte_ar') ? 'error' : '' }}"
                          oninput="document.getElementById('cnt-ar').textContent=this.value.length+'/500'"
                          placeholder="الترجمة العربية للسؤال... (اختياري)">{{ old('texte_ar', $question?->texte_ar) }}</textarea>
                @error('texte_ar') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Weight, Order, Version --}}
            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label" for="poids">Coefficient / Poids <span>*</span></label>
                    <select name="poids" id="poids" class="form-control" style="cursor: pointer;">
                        <option value="1" {{ old('poids', $question?->poids ?? 1) == 1 ? 'selected' : '' }}>1.0 — Standard</option>
                        <option value="2" {{ old('poids', $question?->poids) == 2 ? 'selected' : '' }}>2.0 — Important</option>
                        <option value="3" {{ old('poids', $question?->poids) == 3 ? 'selected' : '' }}>3.0 — Critique</option>
                    </select>
                    @error('poids') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="ordre">Séquence / Ordre <span>*</span></label>
                    <input type="number" name="ordre" id="ordre" min="0" max="999"
                           value="{{ old('ordre', $question?->ordre ?? 0) }}"
                           class="form-control {{ $errors->has('ordre') ? 'error' : '' }}">
                    @error('ordre') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="version">Version de l'algorithme</label>
                    <input type="text" name="version" id="version" maxlength="10"
                           value="{{ old('version', $question?->version ?? '1.0') }}"
                           class="form-control" placeholder="1.0">
                </div>
            </div>

            {{-- Source & Status --}}
            <div class="form-row" style="margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="source">Source bibliographique / Options</label>
                    <input type="text" name="source" id="source" maxlength="100"
                           value="{{ old('source', $question?->source ?? 'Holland 1985') }}"
                           class="form-control" placeholder="Holland 1985">
                    <p class="form-hint">Indiquez [INV] dans la source si la question requiert un codage inversé.</p>
                </div>

                <div class="form-group" style="justify-content: center;">
                    <label class="form-label">Disponibilité</label>
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; margin-top: 0.5rem; user-select: none;">
                        <input type="hidden" name="actif" value="0">
                        <input type="checkbox" name="actif" value="1"
                               {{ old('actif', $question?->actif ?? true) ? 'checked' : '' }}
                               style="width: 18px; height: 18px; accent-color: var(--accent3); cursor: pointer;">
                        <span style="font-size: 0.85rem; font-weight: 600; color: var(--ink60);">
                            Activer la question (Visible pour les étudiants)
                        </span>
                    </label>
                </div>
            </div>

            {{-- Actions --}}
            <div class="form-actions">
                <button type="submit" class="btn-action-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Enregistrer la question
                </button>
                <a href="{{ route('admin.riasec.questions.index') }}" class="btn-action-glass">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const fr = document.getElementById('texte_fr');
    const ar = document.getElementById('texte_ar');
    if (fr) document.getElementById('cnt-fr').textContent = fr.value.length + '/500';
    if (ar) document.getElementById('cnt-ar').textContent = ar.value.length + '/500';
});

const dimLabels = {
    R: 'Réaliste — activités concrètes et manuelles',
    I: 'Investigateur — curiosité scientifique et analytique',
    A: 'Artistique — créativité et expression libre',
    S: 'Social — aide, enseignement, empathie',
    E: 'Entreprenant — leadership, persuasion et projets',
    C: 'Conventionnel — organisation, précision et rigueur',
};
const dimColors = {
    R: '#f97316', I: '#3b82f6', A: '#ec4899', S: '#10b981', E: '#8b5cf6', C: '#94a3b8'
};

function updateDimLabels() {
    const checked = document.querySelector('input[name="dimension"]:checked');
    if (!checked) return;
    document.getElementById('dim-hint').textContent = dimLabels[checked.value] || '';
    document.querySelectorAll('.dim-option label').forEach(lbl => {
        const d = lbl.getAttribute('for')?.replace('dim_','');
        const inp = document.getElementById('dim_' + d);
        if (inp?.checked) {
            lbl.style.background = dimColors[d];
            lbl.style.borderColor = dimColors[d];
            lbl.style.color = '#fff';
        } else {
            lbl.style.background = '';
            lbl.style.borderColor = '';
            lbl.style.color = '';
        }
    });
}

document.querySelectorAll('input[name="dimension"]').forEach(r => {
    r.addEventListener('change', updateDimLabels);
});
updateDimLabels();
</script>
@endsection
