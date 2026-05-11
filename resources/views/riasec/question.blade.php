@extends('layouts.student')
@section('title', 'Question ' . $step . ' — Test RIASEC')

@section('content')
<style>
/* ── Progress header strip ── */
.q-progress-bar {
    position: sticky; top: 64px; z-index: 100;
    background: var(--paper);
    border-bottom: 1px solid var(--glass-border);
    padding: .75rem 2.5rem;
    display: flex; align-items: center; gap: 1.2rem;
    transition: background .4s ease;
}
.q-progress-track {
    flex: 1; height: 4px; background: var(--ink10);
    border-radius: var(--rx); overflow: hidden;
}
.q-progress-fill {
    height: 100%; border-radius: var(--rx);
    background: var(--accent);
    transition: width .6s cubic-bezier(.4,0,.2,1);
}
.q-progress-label { font-size: .75rem; font-weight: 700; color: var(--ink30); white-space: nowrap; }
.q-progress-pct   { font-size: .75rem; font-weight: 700; color: var(--accent); }

/* ── Page layout ── */
.q-page { padding: 2.5rem 2.5rem 5rem; max-width: 760px; margin: 0 auto; }

/* ── Dim badge ── */
.q-dim-badge {
    display: inline-flex; align-items: center; gap: .45rem;
    padding: .3rem .8rem; border-radius: var(--rx);
    font-size: .68rem; font-weight: 700; letter-spacing: .07em; text-transform: uppercase;
    border: 1px solid;
}
.q-cat-badge {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .28rem .7rem; border-radius: var(--rx);
    font-size: .68rem; font-weight: 600; letter-spacing: .05em;
    background: var(--ink06); border: 1px solid var(--glass-border);
    color: var(--ink30);
}

/* ── Question card ── */
.q-card {
    background: var(--ink06); border: 1px solid var(--glass-border);
    border-radius: var(--rl); padding: 2rem 2.2rem 1.8rem;
    margin-bottom: 1.5rem;
    transition: border-color .3s var(--ease);
}
.q-card:hover { border-color: var(--glass-border-vivid); }

.q-meta { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; margin-bottom: 1.5rem; }

.q-text {
    font-family: var(--font-serif);
    font-size: clamp(1.1rem,2.5vw,1.4rem);
    font-weight: 300; font-style: italic;
    color: var(--ink); line-height: 1.55;
    margin-bottom: 2rem;
}

/* ── Likert scale ── */
.likert-wrap { display: flex; justify-content: center; gap: .6rem; flex-wrap: wrap; margin-bottom: 1rem; }
.likert-option input[type="radio"] { display: none; }
.likert-option label {
    display: flex; flex-direction: column; align-items: center; gap: .4rem;
    width: 68px; padding: .85rem .4rem;
    border-radius: var(--r); border: 1.5px solid var(--glass-border);
    cursor: pointer; background: var(--ink06);
    transition: all .2s var(--ease);
    user-select: none;
}
.likert-option label:hover {
    border-color: var(--accent);
    background: color-mix(in srgb,var(--accent) 6%,transparent);
    transform: translateY(-3px);
}
.likert-option input:checked + label {
    border-color: var(--accent);
    background: color-mix(in srgb,var(--accent) 10%,transparent);
    transform: translateY(-4px);
    box-shadow: 0 4px 14px color-mix(in srgb,var(--accent) 20%,transparent);
}
.likert-num {
    font-family: var(--font-serif); font-size: 1.4rem;
    font-weight: 300; line-height: 1; color: var(--ink);
}
.likert-option input:checked + label .likert-num { color: var(--accent); font-weight: 600; }
.likert-emoji { font-size: 1rem; line-height: 1; }
.likert-lbl { font-size: .62rem; color: var(--ink30); text-align: center; line-height: 1.3; max-width: 60px; }
.likert-option input:checked + label .likert-lbl { color: var(--ink60); }

.likert-legend { display: flex; justify-content: space-between; font-size: .7rem; color: var(--ink30); padding: 0 .2rem; margin-bottom: 1.8rem; }

/* ── Navigation buttons ── */
.q-nav { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }

.btn-fill {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .75rem 1.6rem; font-family: var(--font-main);
    font-size: .86rem; font-weight: 600; color: #fff;
    background: var(--accent); border: none; border-radius: var(--r);
    cursor: pointer; text-decoration: none;
    box-shadow: 0 4px 18px color-mix(in srgb,var(--accent) 30%,transparent);
    transition: var(--transition);
}
.btn-fill:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 28px color-mix(in srgb,var(--accent) 42%,transparent); }
.btn-fill:disabled { opacity: .38; cursor: not-allowed; transform: none !important; box-shadow: none !important; }
.btn-fill.btn-complete { background: var(--accent3); box-shadow: 0 4px 18px color-mix(in srgb,var(--accent3) 30%,transparent); }
.btn-fill.btn-complete:hover:not(:disabled) { box-shadow: 0 8px 28px color-mix(in srgb,var(--accent3) 42%,transparent); }

.btn-ghost {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .72rem 1.2rem; font-family: var(--font-main);
    font-size: .84rem; font-weight: 600; color: var(--ink60);
    background: transparent; border: 1px solid var(--glass-border);
    border-radius: var(--r); cursor: pointer; text-decoration: none;
    transition: var(--transition);
}
.btn-ghost:hover { color: var(--ink); border-color: var(--ink30); background: var(--ink06); }

/* ── Dim mini-progress ── */
.dim-mini-grid { display: grid; grid-template-columns: repeat(6,1fr); gap: .5rem; }
.dim-mini-item { text-align: center; }
.dim-mini-label { font-size: .68rem; font-weight: 700; color: var(--ink30); margin-bottom: .4rem; }
.dim-mini-track { height: 4px; background: var(--ink10); border-radius: var(--rx); overflow: hidden; }
.dim-mini-fill  { height: 100%; border-radius: var(--rx); transition: width 1s var(--ease); }

/* ── Toast ── */
.q-toast {
    position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999;
    padding: .65rem 1.1rem; border-radius: var(--r);
    font-size: .8rem; font-weight: 600;
    background: var(--accent3); color: #fff;
    box-shadow: 0 4px 20px rgba(0,0,0,.2);
    transform: translateY(60px); opacity: 0;
    transition: all .3s var(--ease);
}
.q-toast.show { transform: translateY(0); opacity: 1; }
.q-toast.err  { background: #ef4444; }
</style>

{{-- Toast --}}
<div id="qToast" class="q-toast"></div>

{{-- ── Progress header ── --}}
<div class="q-progress-bar">
    <a href="{{ route('riasec.question.entry') }}"
       style="font-size:.75rem;color:var(--ink30);text-decoration:none;display:flex;align-items:center;gap:.3rem;white-space:nowrap;">
        ← Accueil
    </a>
    <div class="q-progress-track">
        <div class="q-progress-fill" style="width:{{ round(($step/$totalSteps)*100) }}%"></div>
    </div>
    <span class="q-progress-label">{{ $step }}/{{ $totalSteps }}</span>
    <span class="q-progress-pct">{{ round(($step/$totalSteps)*100) }}%</span>
</div>

{{-- ── Main ── --}}
<div class="q-page" x-data="riasecQ()" x-init="init()">

    {{-- Feedback anti-ennui (Changement de vague) --}}
    @if(!empty($feedback))
    <div class="q-card" style="background: color-mix(in srgb, var(--accent) 8%, transparent); border-color: color-mix(in srgb, var(--accent) 40%, transparent); padding: 1.5rem;">
        <div style="display: flex; gap: 1rem; align-items: center;">
            <div style="font-size: 1.5rem;">📣</div>
            <p style="margin: 0; font-family: var(--font-main); font-weight: 600; color: var(--ink); line-height: 1.5;">
                {{ $feedback }}
            </p>
        </div>
    </div>
    @endif

    {{-- Question card --}}
    <div class="q-card">
        {{-- Meta badges --}}
        @php
        $dimBadge = [
            'R'=>['color'=>'#d4622a','bg'=>'rgba(212,98,42,.1)','label'=>'Réaliste','emoji'=>'🔧'],
            'I'=>['color'=>'#1a4f6e','bg'=>'rgba(26,79,110,.1)','label'=>'Investigateur','emoji'=>'🔬'],
            'A'=>['color'=>'#c8973a','bg'=>'rgba(200,151,58,.1)','label'=>'Artistique','emoji'=>'🎨'],
            'S'=>['color'=>'#4a7c59','bg'=>'rgba(74,124,89,.1)','label'=>'Social','emoji'=>'🤝'],
            'E'=>['color'=>'#7c4a7c','bg'=>'rgba(124,74,124,.1)','label'=>'Entreprenant','emoji'=>'🚀'],
            'C'=>['color'=>'#4a6e6e','bg'=>'rgba(74,110,110,.1)','label'=>'Conventionnel','emoji'=>'📋'],
        ];
        $catLabel = ['loisirs'=>'Loisirs','preferences_professionnelles'=>'Pref. Pro.','qualites_personnelles'=>'Qualités'];
        $db = $dimBadge[$question->dimension] ?? ['color'=>'var(--accent)','bg'=>'var(--ink06)','label'=>$question->dimension,'emoji'=>'❓'];
        @endphp

        <div class="q-meta">
            <span class="q-dim-badge"
                  style="color:{{ $db['color'] }};background:{{ $db['bg'] }};border-color:{{ $db['color'] }}40;">
                {{ $db['emoji'] }} {{ $db['label'] }}
            </span>
            <span class="q-cat-badge">
                {{ $catLabel[$question->categorie] ?? $question->categorie }}
            </span>
            @if($question->poids > 1)
            <span class="q-cat-badge" style="color:var(--gold);border-color:color-mix(in srgb,var(--gold) 25%,transparent);background:color-mix(in srgb,var(--gold) 8%,transparent);">
                ⭐ Question clé
            </span>
            @endif
        </div>

        {{-- Texte --}}
        <p class="q-text">{{ $question->texte_fr }}</p>
        <p style="font-size:.82rem;color:var(--ink60);line-height:1.55;margin-top:-1rem;margin-bottom:1.4rem;">
            Sur une echelle de 1 a 5 (1 = Pas du tout, 5 = Tout a fait), a quel point cette activite vous attire-t-elle ?
        </p>

        {{-- Likert --}}
        <form id="qForm">
            @csrf
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <input type="hidden" name="temps_ms" id="tempsMsField" value="">

            <div class="likert-wrap">
                @php
                $emojis = [1=>'😕',2=>'🤔',3=>'😐',4=>'🙂',5=>'😄'];
                $lbls   = [1=>'Pas du tout',2=>'Peu',3=>'Neutre',4=>'Plutôt',5=>'Tout à fait'];
                @endphp
                @foreach([1,2,3,4,5] as $v)
                <div class="likert-option">
                    <input type="radio" id="v{{ $v }}" name="valeur" value="{{ $v }}"
                           {{ $existingAnswer == $v ? 'checked' : '' }}
                           x-on:change="onSelect({{ $v }})">
                    <label for="v{{ $v }}">
                        <span class="likert-emoji">{{ $emojis[$v] }}</span>
                        <span class="likert-num">{{ $v }}</span>
                        <span class="likert-lbl">{{ $lbls[$v] }}</span>
                    </label>
                </div>
                @endforeach
            </div>
            <div class="likert-legend">
                <span>Pas du tout d'accord</span>
                <span>Tout à fait d'accord</span>
            </div>
        </form>

        {{-- Navigation --}}
        <div class="q-nav">
            @if($step > 1 && empty($isAdaptive))
            <a href="{{ route('riasec.question', ['step' => $step - 1]) }}" class="btn-ghost">
                ← Précédent
            </a>
            @else
            <div></div>
            @endif

            @if(!$isLast)
            <button class="btn-fill" :disabled="!answered || saving"
                    x-on:click="submit('next')">
                <span x-text="saving ? 'Enregistrement…' : 'Suivant →'"></span>
            </button>
            @else
            <button class="btn-fill btn-complete" :disabled="!answered || saving"
                    x-on:click="submit('finish')">
                <span x-text="saving ? 'Finalisation…' : '✓ Terminer le test'"></span>
            </button>
            @endif
        </div>
    </div>

    {{-- Dim mini-progression --}}
    <div class="q-card" style="padding:1.2rem 1.5rem;">
        <p style="font-size:.68rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--ink30);margin-bottom:.9rem;">
            Couverture par dimension
        </p>
        <div class="dim-mini-grid">
            @php
            $dimColors2 = ['R'=>'#d4622a','I'=>'#1a4f6e','A'=>'#c8973a','S'=>'#4a7c59','E'=>'#7c4a7c','C'=>'#4a6e6e'];
            $dimEmoji2  = ['R'=>'🔧','I'=>'🔬','A'=>'🎨','S'=>'🤝','E'=>'🚀','C'=>'📋'];
            @endphp
            @foreach($dimColors2 as $d => $col)
            @php
            $ans = $progress->answeredByDimension[$d] ?? 0;
            $tot = ($progress->answeredByDimension[$d] ?? 0) + ($progress->remainingByDimension[$d] ?? 0);
            $pct = $tot > 0 ? round($ans/$tot*100) : 0;
            @endphp
            <div class="dim-mini-item">
                <div class="dim-mini-label">{{ $dimEmoji2[$d] }}<br>{{ $d }}</div>
                <div class="dim-mini-track">
                    <div class="dim-mini-fill" style="width:{{ $pct }}%;background:{{ $col }}"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function riasecQ() {
    return {
        answered: {{ $existingAnswer ? 'true' : 'false' }},
        saving: false,
        startTime: Date.now(),
        _timer: null,

        init() { this.startTime = Date.now(); },

        onSelect(v) { this.answered = true; },

        async submit(action) {
            if (!this.answered || this.saving) return;
            this.saving = true;
            document.getElementById('tempsMsField').value = Math.min(Date.now() - this.startTime, 300000);

            const form = document.getElementById('qForm');
            const data = Object.fromEntries(new FormData(form).entries());

            try {
                const res = await fetch('{{ route("riasec.answer") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data),
                });
                const json = await res.json();

                if (json.success) {
                    this.showToast('✓ Réponse enregistrée');
                    if (json.completed || action === 'finish') {
                        setTimeout(() => window.location.href = '{{ route("riasec.complete") }}', 600);
                    } else {
                        setTimeout(() => window.location.href = json.next_url, 350);
                    }
                } else {
                    this.showToast(json.message || 'Erreur. Réessaie.', true);
                    this.saving = false;
                }
            } catch(e) {
                this.showToast('Erreur réseau.', true);
                this.saving = false;
            }
        },

        showToast(msg, err = false) {
            const t = document.getElementById('qToast');
            t.textContent = msg;
            t.className = 'q-toast show' + (err ? ' err' : '');
            clearTimeout(this._timer);
            this._timer = setTimeout(() => { t.className = 'q-toast'; }, 2400);
        }
    }
}
</script>
@endsection
