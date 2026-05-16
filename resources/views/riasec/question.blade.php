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

    {{-- Early Stopping Gauge --}}
    @if(!empty($earlyStopData))
    <div class="q-card" style="background:#f8fafc; border:1px solid #e2e8f0; padding:1.5rem; text-align:center;">
        <div style="position:relative; width:150px; height:75px; margin:0 auto;">
            <svg viewBox="0 0 100 50" style="width:100%; height:100%; overflow:visible;">
                <path d="M 10 50 A 40 40 0 0 1 90 50" fill="none" stroke="#e0e0e0" stroke-width="10" stroke-linecap="round" />
                @php
                    $score = $earlyStopData['confidence'];
                    $color = $score >= 85 ? '#22c55e' : ($score >= 70 ? '#eab308' : '#3b82f6');
                    $dashOffset = (M_PI * 40) - ($score / 100) * (M_PI * 40);
                @endphp
                <path d="M 10 50 A 40 40 0 0 1 90 50" fill="none" stroke="{{ $color }}" stroke-width="10" stroke-linecap="round" 
                      stroke-dasharray="125.66" stroke-dashoffset="{{ $dashOffset }}" style="transition: stroke-dashoffset 1s;" />
            </svg>
            <div style="position:absolute; bottom:-10px; width:100%; font-weight:bold; font-size:1.5rem; color:{{ $color }};">{{ $score }}%</div>
        </div>
        
        <p style="margin-top:1.5rem; font-weight:600; color:#475569; font-size:1.1rem;">
            {{ $earlyStopData['message'] }}
        </p>

        @if($earlyStopData['confidence'] >= 70 || $earlyStopData['stop'])
        <div style="margin-top:1.5rem; display:flex; justify-content:center;">
            <button type="button" 
               @click="window.location.href = '{{ route('riasec.complete') }}'"
               class="btn-fill" 
               style="background:var(--ink); width:100%; max-width:300px; display:flex; align-items:center; justify-content:center; height:45px; border:none; cursor:pointer;">
                Terminer le test maintenant
            </button>
        </div>
        @endif
    </div>
    @endif

    {{-- Feedback anti-ennui (Changement de vague) --}}
    @if(!empty($feedback))
    <div class="q-card" style="background: color-mix(in srgb, var(--accent) 8%, transparent); border-color: color-mix(in srgb, var(--accent) 40%, transparent); padding: 1.5rem;">
        <div style="display: flex; gap: 1rem; align-items: center;">
            <div style="flex-shrink:0; width:40px; height:40px; border-radius:10px; background:var(--accent); color:#fff; display:flex; align-items:center; justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8c0 4.5-4.5 4.5-4.5 9a1.5 1.5 0 0 1-3 0c0-4.5-4.5-4.5-4.5-9a4.5 4.5 0 0 1 9 0Z"/><path d="M12 21h.01"/></svg>
            </div>
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
            'R'=>['color'=>'#d4622a','bg'=>'rgba(212,98,42,.1)','label'=>'Réaliste','icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>'],
            'I'=>['color'=>'#1a4f6e','bg'=>'rgba(26,79,110,.1)','label'=>'Investigateur','icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m16 16-3.5-3.5"/><circle cx="11" cy="11" r="4"/></svg>'],
            'A'=>['color'=>'#c8973a','bg'=>'rgba(200,151,58,.1)','label'=>'Artistique','icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10Zm0-13a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/><path d="m19 12-5 5"/></svg>'],
            'S'=>['color'=>'#4a7c59','bg'=>'rgba(74,124,89,.1)','label'=>'Social','icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>'],
            'E'=>['color'=>'#7c4a7c','bg'=>'rgba(124,74,124,.1)','label'=>'Entreprenant','icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 14.2 0L21 21Z"/><path d="M9 12h6"/><path d="M12 9v6"/></svg>'],
            'C'=>['color'=>'#4a6e6e','bg'=>'rgba(74,110,110,.1)','label'=>'Conventionnel','icon'=>'<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M8 7h8"/><path d="M8 12h8"/><path d="M8 17h8"/></svg>'],
        ];
        $catLabel = ['loisirs'=>'Loisirs','preferences_professionnelles'=>'Pref. Pro.','qualites_personnelles'=>'Qualités'];
        $db = $dimBadge[$question->dimension] ?? ['color'=>'var(--accent)','bg'=>'var(--ink06)','label'=>$question->dimension,'icon'=>''];
        @endphp

        <div class="q-meta">
            <span class="q-dim-badge"
                  style="color:{{ $db['color'] }};background:{{ $db['bg'] }};border-color:{{ $db['color'] }}40;">
                {!! $db['icon'] !!} {{ $db['label'] }}
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
                $icons = [
                    1=>'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>',
                    2=>'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5-2 4-2 4 2 4 2"/></svg>',
                    3=>'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>',
                    4=>'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12s1.5 2 4 2 4-2 4-2"/></svg>',
                    5=>'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="9 11 12 14 22 4"/></svg>'
                ];
                $lbls   = [1=>'Pas du tout',2=>'Peu',3=>'Neutre',4=>'Plutôt',5=>'Tout à fait'];
                @endphp
                @foreach([1,2,3,4,5] as $v)
                <div class="likert-option">
                    <input type="radio" id="v{{ $v }}" name="valeur" value="{{ $v }}"
                           {{ $existingAnswer == $v ? 'checked' : '' }}
                           x-on:change="onSelect({{ $v }})">
                    <label for="v{{ $v }}">
                        <span class="likert-emoji" style="color:var(--ink30)">{!! $icons[$v] !!}</span>
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
            $dimIcon2  = [
                'R'=>'<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
                'I'=>'<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m16 16-3.5-3.5"/><circle cx="11" cy="11" r="4"/></svg>',
                'A'=>'<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10Zm0-13a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/><path d="m19 12-5 5"/></svg>',
                'S'=>'<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
                'E'=>'<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 14.2 0L21 21Z"/><path d="M9 12h6"/><path d="M12 9v6"/></svg>',
                'C'=>'<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M8 7h8"/><path d="M8 12h8"/><path d="M8 17h8"/></svg>'
            ];
            @endphp
            @foreach($dimColors2 as $d => $col)
            @php
            $ans = $progress->answeredByDimension[$d] ?? 0;
            $tot = ($progress->answeredByDimension[$d] ?? 0) + ($progress->remainingByDimension[$d] ?? 0);
            $pct = $tot > 0 ? round($ans/$tot*100) : 0;
            @endphp
            <div class="dim-mini-item">
                <div class="dim-mini-label">{!! $dimIcon2[$d] !!}<br>{{ $d }}</div>
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
