@extends('layouts.admin')
@section('title', $question ? 'Modifier la question' : 'Nouvelle question RIASEC')

@section('content')
<style>
.form-card {
    max-width: 760px;
    background: var(--ink06);
    border: 1px solid var(--glass-border);
    border-radius: var(--rl);
    padding: 2rem 2.5rem;
}
.form-group { margin-bottom: 1.5rem; }
.form-label {
    display: block;
    font-size: .76rem; font-weight: 700;
    letter-spacing: .06em; text-transform: uppercase;
    color: var(--ink30); margin-bottom: .5rem;
}
.form-label span { color: var(--red); margin-left: 2px; }

.form-control {
    width: 100%;
    font-family: var(--font-main); font-size: .88rem;
    background: var(--ink06); border: 1px solid var(--glass-border);
    color: var(--ink); border-radius: var(--r);
    padding: .7rem 1rem; outline: none;
    transition: border-color .2s var(--ease);
}
.form-control:focus { border-color: var(--accent); }
.form-control.error { border-color: var(--red); }

textarea.form-control { resize: vertical; min-height: 90px; line-height: 1.55; }

.form-hint {
    font-size: .72rem; color: var(--ink30);
    margin-top: .35rem; line-height: 1.45;
}
.form-error { font-size: .75rem; color: var(--red); margin-top: .3rem; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; }
@media(max-width:600px){ .form-row{ grid-template-columns:1fr; } }

.form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
@media(max-width:700px){ .form-row-3{ grid-template-columns:1fr; } }

/* Dim picker */
.dim-picker { display:flex; gap:.7rem; flex-wrap:wrap; }
.dim-option input { display:none; }
.dim-option label {
    display:flex; align-items:center; justify-content:center;
    width:48px; height:48px; border-radius:12px;
    font-weight:800; font-size:1.1rem; cursor:pointer;
    border:2px solid var(--glass-border);
    background:var(--ink06); color:var(--ink60);
    transition:all .2s var(--ease);
}
.dim-option label:hover { border-color:var(--ink30); color:var(--ink); }
.dim-option input:checked + label { border-color:var(--accent); color:#fff; }

/* Char counter */
.char-counter { float:right; font-size:.7rem; color:var(--ink30); }

.form-actions { display:flex; align-items:center; gap:.8rem; margin-top:2rem; padding-top:1.5rem; border-top:1px solid var(--glass-border); }
</style>

{{-- Back --}}
<div style="margin-bottom:1.5rem;">
    <a href="{{ route('admin.riasec.questions.index') }}"
       style="font-size:.82rem;color:var(--ink30);text-decoration:none;display:inline-flex;align-items:center;gap:.35rem;">
        ← Retour à la liste
    </a>
</div>

@php
$dimConfig = [
    'R' => ['color'=>'#f97316','label'=>'Réaliste'],
    'I' => ['color'=>'#3b82f6','label'=>'Investigateur'],
    'A' => ['color'=>'#ec4899','label'=>'Artistique'],
    'S' => ['color'=>'#10b981','label'=>'Social'],
    'E' => ['color'=>'#8b5cf6','label'=>'Entreprenant'],
    'C' => ['color'=>'#94a3b8','label'=>'Conventionnel'],
];
@endphp

<div class="form-card">
    <form action="{{ $question
        ? route('admin.riasec.questions.update', $question)
        : route('admin.riasec.questions.store') }}"
          method="POST">
        @csrf
        @if($question)
            @method('PUT')
        @endif

        {{-- ── Dimension ────────────────────────────────────────────── --}}
        <div class="form-group">
            <label class="form-label">Dimension RIASEC <span>*</span></label>
            <div class="dim-picker">
                @foreach($dimConfig as $d => $cfg)
                <div class="dim-option">
                    <input type="radio" name="dimension" id="dim_{{ $d }}" value="{{ $d }}"
                           {{ old('dimension', $question?->dimension) === $d ? 'checked' : '' }}>
                    <label for="dim_{{ $d }}"
                           style="{{ old('dimension', $question?->dimension) === $d
                                ? 'background:'.$cfg['color'].';border-color:'.$cfg['color']
                                : '' }}"
                           title="{{ $cfg['label'] }}"
                           onclick="this.previousElementSibling.checked=true;updateDimLabels()">
                        {{ $d }}
                    </label>
                </div>
                @endforeach
            </div>
            @error('dimension')<p class="form-error">{{ $message }}</p>@enderror
            <p class="form-hint" id="dim-hint" style="margin-top:.5rem;">
                {{ $question ? ($dimConfig[$question->dimension]['label'] ?? '') : 'Sélectionnez la dimension Holland' }}
            </p>
        </div>

        {{-- ── Catégorie & Type ─────────────────────────────────────── --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="categorie">Catégorie <span>*</span></label>
                <select name="categorie" id="categorie" class="form-control {{ $errors->has('categorie') ? 'error' : '' }}">
                    <option value="">— Choisir —</option>
                    <option value="loisirs" {{ old('categorie',$question?->categorie)==='loisirs'?'selected':'' }}>🎯 Loisirs</option>
                    <option value="preferences_professionnelles" {{ old('categorie',$question?->categorie)==='preferences_professionnelles'?'selected':'' }}>💼 Préférences professionnelles</option>
                    <option value="qualites_personnelles" {{ old('categorie',$question?->categorie)==='qualites_personnelles'?'selected':'' }}>⚡ Qualités personnelles</option>
                </select>
                @error('categorie')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="type_reponse">Type de réponse <span>*</span></label>
                <select name="type_reponse" id="type_reponse" class="form-control {{ $errors->has('type_reponse') ? 'error' : '' }}">
                    <option value="likert"  {{ old('type_reponse',$question?->type_reponse??'likert')==='likert'?'selected':'' }}>📊 Likert (1→5)</option>
                    <option value="boolean" {{ old('type_reponse',$question?->type_reponse)==='boolean'?'selected':'' }}>✅ Oui / Non</option>
                    <option value="choice"  {{ old('type_reponse',$question?->type_reponse)==='choice'?'selected':'' }}>📝 Choix multiple</option>
                </select>
                @error('type_reponse')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ── Texte FR ─────────────────────────────────────────────── --}}
        <div class="form-group">
            <label class="form-label" for="texte_fr">
                Texte de la question (FR) <span>*</span>
                <span class="char-counter" id="cnt-fr">0/500</span>
            </label>
            <textarea name="texte_fr" id="texte_fr" maxlength="500"
                      class="form-control {{ $errors->has('texte_fr') ? 'error' : '' }}"
                      oninput="document.getElementById('cnt-fr').textContent=this.value.length+'/500'"
                      placeholder="Ex: J'aime construire ou réparer des objets de mes propres mains…">{{ old('texte_fr', $question?->texte_fr) }}</textarea>
            @error('texte_fr')<p class="form-error">{{ $message }}</p>@enderror
            <p class="form-hint">
                Rédigez une affirmation comportementale claire, à la 1ère personne, adaptée à un lycéen.
            </p>
        </div>

        {{-- ── Texte AR (optionnel) ────────────────────────────────── --}}
        <div class="form-group">
            <label class="form-label" for="texte_ar">
                Texte arabe (optionnel)
                <span class="char-counter" id="cnt-ar">0/500</span>
            </label>
            <textarea name="texte_ar" id="texte_ar" maxlength="500" dir="rtl"
                      class="form-control {{ $errors->has('texte_ar') ? 'error' : '' }}"
                      oninput="document.getElementById('cnt-ar').textContent=this.value.length+'/500'"
                      placeholder="الترجمة العربية للسؤال (اختياري)">{{ old('texte_ar', $question?->texte_ar) }}</textarea>
            @error('texte_ar')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- ── Poids, Ordre, Version ───────────────────────────────── --}}
        <div class="form-row-3">
            <div class="form-group">
                <label class="form-label" for="poids">Poids <span>*</span></label>
                <select name="poids" id="poids" class="form-control">
                    <option value="1" {{ old('poids',$question?->poids??1)==1?'selected':'' }}>1 — Normal</option>
                    <option value="2" {{ old('poids',$question?->poids)==2?'selected':'' }}>2 — Important ⭐</option>
                    <option value="3" {{ old('poids',$question?->poids)==3?'selected':'' }}>3 — Clé ⭐⭐</option>
                </select>
                @error('poids')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="ordre">Ordre d'affichage <span>*</span></label>
                <input type="number" name="ordre" id="ordre" min="0" max="999"
                       value="{{ old('ordre', $question?->ordre ?? 0) }}"
                       class="form-control {{ $errors->has('ordre') ? 'error' : '' }}">
                @error('ordre')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="version">Version</label>
                <input type="text" name="version" id="version" maxlength="10"
                       value="{{ old('version', $question?->version ?? '1.0') }}"
                       class="form-control" placeholder="1.0">
            </div>
        </div>

        {{-- ── Source & Actif ─────────────────────────────────────── --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="source">Source / Référence</label>
                <input type="text" name="source" id="source" maxlength="100"
                       value="{{ old('source', $question?->source ?? 'Holland 1985') }}"
                       class="form-control" placeholder="Holland 1985">
                <p class="form-hint">Ajoutez [INV] pour marquer comme question inversée.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Statut</label>
                <label style="display:flex;align-items:center;gap:.8rem;cursor:pointer;margin-top:.3rem;">
                    <input type="hidden" name="actif" value="0">
                    <input type="checkbox" name="actif" value="1"
                           {{ old('actif', $question?->actif ?? true) ? 'checked' : '' }}
                           style="width:18px;height:18px;accent-color:var(--accent3)">
                    <span style="font-size:.85rem;color:var(--ink60);">
                        Question active (visible dans les tests)
                    </span>
                </label>
            </div>
        </div>

        {{-- ── Actions ─────────────────────────────────────────────── --}}
        <div class="form-actions">
            <button type="submit" class="btn-primary">
                {{ $question ? '💾 Enregistrer les modifications' : '＋ Créer la question' }}
            </button>
            <a href="{{ route('admin.riasec.questions.index') }}" class="btn-glass">Annuler</a>
        </div>
    </form>
</div>

<script>
// Init char counters
document.addEventListener('DOMContentLoaded', () => {
    const fr = document.getElementById('texte_fr');
    const ar = document.getElementById('texte_ar');
    if (fr) document.getElementById('cnt-fr').textContent = fr.value.length + '/500';
    if (ar) document.getElementById('cnt-ar').textContent = ar.value.length + '/500';
});

const dimLabels = {
    R:'Réaliste — activités concrètes et manuelles',
    I:'Investigateur — curiosité scientifique et analytique',
    A:'Artistique — créativité et expression',
    S:'Social — aide, enseignement, empathie',
    E:'Entreprenant — leadership et persuasion',
    C:'Conventionnel — organisation et rigueur',
};
const dimColors = {
    R:'#f97316',I:'#3b82f6',A:'#ec4899',S:'#10b981',E:'#8b5cf6',C:'#94a3b8'
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
// Init au chargement
document.querySelectorAll('input[name="dimension"]').forEach(r => {
    r.addEventListener('change', updateDimLabels);
});
updateDimLabels();
</script>
@endsection
