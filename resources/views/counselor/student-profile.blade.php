@extends('layouts.counselor')

@section('title', 'Profil — ' . $student->name)

@section('content')
<style>
/* ════════════════════════════════════════════
   STUDENT PROFILE — CapAvenir System (Counselor)
════════════════════════════════════════════ */
.sp {
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
    --r:    6px;
    --rl:   16px;
    --rx:   999px;
    --ease: cubic-bezier(.16,1,.3,1);

    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
}

[data-theme="dark"]  .sp { --ink:#f0ede6;--paper:#10100d;--cream:#18170f;--warm:#1f1e14;--ink60:rgba(240,237,230,.6);--ink30:rgba(240,237,230,.3);--ink15:rgba(240,237,230,.15);--ink10:rgba(240,237,230,.08);--ink06:rgba(240,237,230,.04); }

.sp *, .sp *::before, .sp *::after { box-sizing: border-box; margin: 0; padding: 0; }
.sp a { color: inherit; text-decoration: none; }

/* ── Reveal ── */
.sp .rev { opacity: 0; transform: translateY(20px); transition: opacity .6s var(--ease), transform .6s var(--ease); }
.sp .rev.vis { opacity: 1; transform: none; }
.sp .rev-d1 { transition-delay: .06s; }
.sp .rev-d2 { transition-delay: .12s; }
.sp .rev-d3 { transition-delay: .18s; }
.sp .rev-d4 { transition-delay: .24s; }

/* ── Section tag ── */
.sp .stag {
    font-size: .68rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase;
    color: var(--accent2); display: inline-flex; align-items: center; gap: .45rem; margin-bottom: .5rem;
}
.sp .stag::before { content: ''; width: 14px; height: 1px; background: var(--accent2); }
.sp .stag-accent::before { background: var(--accent); }
.sp .stag-accent { color: var(--accent); }

/* ── Section heading ── */
.sp .sh {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.3rem, 2.5vw, 1.7rem);
    font-weight: 300; letter-spacing: -.035em; line-height: 1.1;
}
.sp .sh em { font-style: italic; color: var(--accent2); }

/* ── Card ── */
.sp .card {
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: var(--rl);
    transition: border-color .25s var(--ease);
}
.sp .card:hover { border-color: var(--ink15); }

/* ── Pill ── */
.sp .pill {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .28rem .75rem; border-radius: var(--rx);
    font-size: .68rem; font-weight: 700; letter-spacing: .05em;
}
.sp .pill-marine { background: color-mix(in srgb,var(--accent2) 10%,transparent); color: var(--accent2); border: 1px solid color-mix(in srgb,var(--accent2) 22%,transparent); }
.sp .pill-sage   { background: color-mix(in srgb,var(--accent3) 10%,transparent); color: var(--accent3); border: 1px solid color-mix(in srgb,var(--accent3) 22%,transparent); }
.sp .pill-accent { background: color-mix(in srgb,var(--accent) 10%,transparent); color: var(--accent); border: 1px solid color-mix(in srgb,var(--accent) 22%,transparent); }
.sp .pill-ink    { background: var(--ink06); color: var(--ink60); border: 1px solid var(--ink10); }
.sp .pill-gold   { background: color-mix(in srgb,var(--gold) 10%,transparent); color: var(--gold); border: 1px solid color-mix(in srgb,var(--gold) 22%,transparent); }

/* ── Buttons ── */
.sp .btn-fill {
    display: inline-flex; align-items: center; gap: .55rem;
    padding: .78rem 1.6rem; border-radius: var(--r);
    background: var(--accent2); color: #fff;
    font-family: 'DM Sans', sans-serif; font-size: .85rem; font-weight: 600;
    border: none; cursor: pointer;
    box-shadow: 0 4px 18px color-mix(in srgb, var(--accent2) 30%, transparent);
    transition: all .3s var(--ease);
}
.sp .btn-fill:hover { transform: translateY(-2px); box-shadow: 0 8px 28px color-mix(in srgb, var(--accent2) 42%, transparent); }

.sp .btn-ghost {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .65rem 1.3rem; border-radius: var(--r);
    background: transparent; border: 1px solid var(--ink15);
    color: var(--ink60); font-family: 'DM Sans', sans-serif;
    font-size: .82rem; font-weight: 600; cursor: pointer; transition: all .25s;
}
.sp .btn-ghost:hover { background: var(--ink06); border-color: var(--ink30); color: var(--ink); }

/* ══════ BACK BAR ══════ */
.sp-back {
    display: inline-flex; align-items: center; gap: .5rem;
    font-size: .82rem; font-weight: 600; color: var(--ink60);
    margin-bottom: 2rem; transition: color .2s;
}
.sp-back:hover { color: var(--accent2); }

/* ══════ HERO HEADER ══════ */
.sp-hero {
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: 20px;
    padding: 2.5rem;
    display: flex; align-items: center; gap: 2rem;
    margin-bottom: 2rem;
    position: relative; overflow: hidden;
}
.sp-hero-orb {
    position: absolute; width: 350px; height: 350px; border-radius: 50%;
    background: radial-gradient(circle at 40% 40%,
        color-mix(in srgb, var(--accent2) 12%, transparent),
        color-mix(in srgb, var(--accent) 7%, transparent) 55%, transparent 75%);
    right: -5%; top: 50%; transform: translateY(-50%);
    pointer-events: none;
}
.sp-hero-avatar {
    width: 88px; height: 88px; border-radius: var(--rl); flex-shrink: 0;
    background: var(--accent2);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Fraunces', serif; font-size: 2.4rem; font-weight: 600;
    color: #fff;
    box-shadow: 0 8px 28px color-mix(in srgb, var(--accent2) 30%, transparent);
}
.sp-hero-info { position: relative; z-index: 1; flex: 1; }
.sp-hero-name {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 300; letter-spacing: -.04em; line-height: 1.1;
    margin-bottom: .35rem;
}
.sp-hero-name em { font-style: italic; color: var(--accent2); }
.sp-hero-email { font-size: .85rem; color: var(--ink60); margin-bottom: 1rem; }
.sp-hero-pills { display: flex; flex-wrap: wrap; gap: .5rem; }

.sp-hero-score {
    position: relative; z-index: 1;
    text-align: center; flex-shrink: 0;
}
.sp-hero-score-num {
    font-family: 'Fraunces', serif;
    font-size: 3rem; font-weight: 600;
    letter-spacing: -.05em; line-height: 1;
    color: var(--accent2);
}
.sp-hero-score-label {
    font-size: .65rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .08em; color: var(--ink30); margin-top: .25rem;
}

/* ══════ CONTENT GRID ══════ */
.sp-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 2rem;
}

/* ══════ AI SUMMARY BOX ══════ */
.sp-ai-box {
    background: var(--ink);
    border-radius: var(--rl);
    padding: 2.25rem;
    color: var(--paper);
    position: relative; overflow: hidden;
    margin-bottom: 1.5rem;
}
[data-theme="dark"] .sp-ai-box { background: var(--cream); color: var(--ink); }

.sp-ai-badge {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .35rem .85rem; border-radius: var(--rx);
    font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
    background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
    color: rgba(255,255,255,.8); margin-bottom: 1.25rem;
}
[data-theme="dark"] .sp-ai-badge {
    background: var(--ink06); border-color: var(--ink10); color: var(--ink60);
}

.sp-ai-text {
    font-family: 'Fraunces', serif;
    font-size: 1.1rem; font-weight: 300; font-style: italic;
    line-height: 1.75; opacity: .9;
}

/* ══════ INFO GRID ══════ */
.sp-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; }

.sp-info-box { padding: 1.5rem; }
.sp-info-label {
    font-size: .65rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .1em; color: var(--ink30); margin-bottom: .875rem;
}
.sp-tag-list { display: flex; flex-wrap: wrap; gap: .5rem; }
.sp-tag {
    padding: .4rem .85rem; border-radius: var(--r);
    background: var(--paper); border: 1px solid var(--ink10);
    font-size: .78rem; font-weight: 600; color: var(--ink);
    transition: border-color .2s;
}
.sp-tag:hover { border-color: var(--ink30); }
.sp-tag-interest {
    border-color: color-mix(in srgb, var(--accent2) 22%, transparent);
    background: color-mix(in srgb, var(--accent2) 6%, transparent);
    color: var(--accent2);
}
.sp-empty-text { font-size: .82rem; color: var(--ink30); font-style: italic; }

/* ══════ FORM SECTION ══════ */
.sp-form-card { padding: 2rem; margin-bottom: 1.5rem; }
.sp-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; }
.sp-form-label {
    display: block; font-size: .68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .1em; color: var(--ink30); margin-bottom: .6rem;
}
.sp-textarea {
    width: 100%; min-height: 140px;
    background: var(--paper); border: 1px solid var(--ink10);
    border-radius: var(--r); padding: 1rem;
    font-family: 'DM Sans', sans-serif; font-size: .88rem;
    color: var(--ink); line-height: 1.7; resize: vertical;
    transition: border-color .25s;
}
.sp-textarea:focus { outline: none; border-color: var(--accent2); }
.sp-textarea::placeholder { color: var(--ink30); }

.sp-select {
    width: 100%; padding: .75rem 1rem;
    background: color-mix(in srgb, var(--accent2) 5%, var(--paper));
    border: 1px solid color-mix(in srgb, var(--accent2) 22%, transparent);
    border-radius: var(--r);
    font-family: 'DM Sans', sans-serif; font-size: .85rem;
    font-weight: 600; color: var(--ink); cursor: pointer;
    transition: border-color .25s;
}
.sp-select:focus { outline: none; border-color: var(--accent2); }

.sp-form-footer {
    display: flex; justify-content: space-between; align-items: center;
    padding-top: 1.25rem; border-top: 1px solid var(--ink10);
    flex-wrap: wrap; gap: 1rem;
}

/* ══════ SIDEBAR ══════ */
.sp-side-card { padding: 1.75rem; margin-bottom: 1.5rem; }

.sp-side-avatar {
    width: 72px; height: 72px; border-radius: var(--rl);
    background: var(--accent2); margin: 0 auto 1.25rem;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Fraunces', serif; font-size: 2rem; font-weight: 600; color: #fff;
}
.sp-side-name {
    text-align: center;
    font-family: 'Fraunces', serif; font-size: 1.15rem; font-weight: 600;
    letter-spacing: -.02em; margin-bottom: .2rem;
}
.sp-side-email { text-align: center; font-size: .78rem; color: var(--ink30); margin-bottom: 1.5rem; }

/* Info rows */
.sp-info-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: .75rem 0;
    border-bottom: 1px solid var(--ink06);
}
.sp-info-row:last-child { border-bottom: none; }
.sp-info-row-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--ink30); }
.sp-info-row-val { font-size: .85rem; font-weight: 600; }

/* Score ring */
.sp-ring-wrap { text-align: center; padding: 1rem 0; margin-bottom: .5rem; }

/* ══════ TIMELINE ══════ */
.sp-timeline { display: flex; flex-direction: column; gap: 0; }
.sp-tl-item {
    padding-left: 1.25rem;
    border-left: 2px solid var(--ink10);
    position: relative;
    padding-bottom: 1.25rem;
}
.sp-tl-item:last-child { padding-bottom: 0; }
.sp-tl-item::before {
    content: '';
    position: absolute; left: -5px; top: 2px;
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--accent2);
    border: 2px solid var(--cream);
}
.sp-tl-content {
    background: var(--paper); border: 1px solid var(--ink10);
    border-radius: var(--r); padding: .875rem 1rem;
}
.sp-tl-head {
    display: flex; justify-content: space-between; align-items: center;
    font-size: .85rem; font-weight: 600; margin-bottom: .2rem;
}
.sp-tl-score {
    font-family: 'Fraunces', serif; font-weight: 600;
    letter-spacing: -.02em; color: var(--accent2);
}
.sp-tl-date { font-size: .68rem; color: var(--ink30); }

/* ── Responsive ── */
@media (max-width: 1100px) {
    .sp-grid { grid-template-columns: 1fr; }
    .sp-hero { flex-direction: column; text-align: center; align-items: center; }
    .sp-hero-pills { justify-content: center; }
}
@media (max-width: 600px) {
    .sp-info-grid, .sp-form-grid { grid-template-columns: 1fr; }
    .sp-hero { padding: 2rem 1.5rem; }
}
</style>

<div class="sp" id="spRoot">

    {{-- ═══ BACK LINK ═══ --}}
    <a href="{{ route('counselor.dashboard') }}" class="sp-back">← Retour au tableau de bord</a>

    {{-- ═══ HERO HEADER ═══ --}}
    @php
        $score  = $student->profile->ai_score ?? 0;
        $status = $student->profile->status ?? 'pending';
        $statusPill = match($status) {
            'completed' => ['class' => 'pill-sage',   'lbl' => '✅ Certifié'],
            'ongoing'   => ['class' => 'pill-marine', 'lbl' => '🔵 Suivi actif'],
            default     => ['class' => 'pill-ink',    'lbl' => '⏳ En attente'],
        };
    @endphp
    <section class="sp-hero rev">
        <div class="sp-hero-orb"></div>

        <div class="sp-hero-avatar">
            {{ strtoupper(substr($student->name, 0, 1)) }}
        </div>

        <div class="sp-hero-info">
            <h1 class="sp-hero-name">{{ $student->name }}</h1>
            <p class="sp-hero-email">{{ $student->email }}</p>
            <div class="sp-hero-pills">
                <span class="pill {{ $statusPill['class'] }}">{{ $statusPill['lbl'] }}</span>
                <span class="pill pill-ink">📅 Inscrit le {{ $student->created_at->format('d/m/Y') }}</span>
                <span class="pill pill-gold">🎓 Étudiant</span>
            </div>
        </div>

        <div class="sp-hero-score">
            <svg width="100" height="100" viewBox="0 0 100 100">
                <defs>
                    <linearGradient id="scoreGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#1a4f6e"/>
                        <stop offset="100%" stop-color="#d4622a"/>
                    </linearGradient>
                </defs>
                <g transform="rotate(-90 50 50)">
                    <circle cx="50" cy="50" r="44" fill="none" stroke-width="5" stroke="rgba(11,12,16,.06)"/>
                    <circle cx="50" cy="50" r="44" fill="none" stroke="url(#scoreGrad)" stroke-width="5"
                            stroke-linecap="round"
                            stroke-dasharray="{{ 2 * 3.14159 * 44 }}"
                            stroke-dashoffset="{{ 2 * 3.14159 * 44 * (1 - $score / 100) }}"
                            style="transition: stroke-dashoffset 1.2s cubic-bezier(.16,1,.3,1) .4s;"/>
                </g>
                <text x="50" y="50" text-anchor="middle" dominant-baseline="central"
                      font-family="'Fraunces', serif" font-size="22" font-weight="600"
                      fill="currentColor" letter-spacing="-1">{{ $score }}%</text>
            </svg>
            <div class="sp-hero-score-label">Score IA</div>
        </div>
    </section>

    {{-- ═══ CONTENT GRID ═══ --}}
    <div class="sp-grid">

        {{-- ── LEFT COLUMN ── --}}
        <div>
            {{-- AI Summary --}}
            <div class="sp-ai-box rev rev-d1">
                <div class="sp-ai-badge">✦ Synthèse IA</div>
                <p class="sp-ai-text">
                    "{{ $student->profile->summary ?? 'Le système structure actuellement les données pour générer une synthèse prédictive optimale.' }}"
                </p>
            </div>

            {{-- Skills & Interests --}}
            <div class="sp-info-grid rev rev-d2">
                <div class="card sp-info-box">
                    <div class="sp-info-label">🧠 Expertise & Aptitudes</div>
                    <div class="sp-tag-list">
                        @forelse(explode(',', $student->profile->skills ?? '') as $skill)
                            @if(trim($skill)) <span class="sp-tag">{{ trim($skill) }}</span> @endif
                        @empty
                            <p class="sp-empty-text">Aucune donnée disponible…</p>
                        @endforelse
                    </div>
                </div>
                <div class="card sp-info-box">
                    <div class="sp-info-label">💡 Pôles d'Intérêt</div>
                    <div class="sp-tag-list">
                        @forelse(explode(',', $student->profile->interests ?? '') as $interest)
                            @if(trim($interest)) <span class="sp-tag sp-tag-interest">{{ trim($interest) }}</span> @endif
                        @empty
                            <p class="sp-empty-text">Aucune donnée disponible…</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Counselor Form --}}
            <div class="card sp-form-card rev rev-d3">
                <p class="stag stag-accent">Suivi du conseiller</p>
                <h3 class="sh" style="margin-bottom:1.5rem;">Notes & <em>accompagnement</em></h3>

                <form action="{{ route('counselor.student.update', $student) }}" method="POST">
                    @csrf
                    <div class="sp-form-grid">
                        <div>
                            <label class="sp-form-label">Observations globales</label>
                            <textarea name="counselor_observations" class="sp-textarea"
                                placeholder="Notes sur le profil…">{{ $student->profile->counselor_observations ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="sp-form-label">Plan d'accompagnement</label>
                            <textarea name="coaching_plan" class="sp-textarea"
                                placeholder="Étapes recommandées…">{{ $student->profile->coaching_plan ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="sp-form-footer">
                        <div style="max-width:240px;">
                            <label class="sp-form-label">Statut du dossier</label>
                            <select name="status" class="sp-select">
                                <option value="pending"    {{ ($student->profile->status ?? '') === 'pending'    ? 'selected' : '' }}>⏳ En attente</option>
                                <option value="ongoing"    {{ ($student->profile->status ?? '') === 'ongoing'    ? 'selected' : '' }}>🤝 Suivi actif</option>
                                <option value="completed"  {{ ($student->profile->status ?? '') === 'completed'  ? 'selected' : '' }}>✅ Dossier clôturé</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-fill">📋 Mettre à jour le dossier</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── RIGHT SIDEBAR ── --}}
        <div>
            {{-- Quick Info --}}
            <div class="card sp-side-card rev rev-d1">
                <div class="sp-side-avatar">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                <div class="sp-side-name">{{ $student->name }}</div>
                <div class="sp-side-email">{{ $student->email }}</div>

                <div>
                    <div class="sp-info-row">
                        <span class="sp-info-row-label">Inscrit le</span>
                        <span class="sp-info-row-val">{{ $student->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="sp-info-row">
                        <span class="sp-info-row-label">Progression IA</span>
                        <span class="sp-info-row-val" style="color:var(--accent2);">{{ $score }}%</span>
                    </div>
                    <div class="sp-info-row">
                        <span class="sp-info-row-label">Statut</span>
                        <span class="pill {{ $statusPill['class'] }}" style="font-size:.6rem;">{{ $statusPill['lbl'] }}</span>
                    </div>
                    <div class="sp-info-row">
                        <span class="sp-info-row-label">Tests passés</span>
                        <span class="sp-info-row-val">{{ $testAttempts->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- Test Timeline --}}
            <div class="card sp-side-card rev rev-d2">
                <p class="stag" style="margin-bottom:1rem;">Parcours de tests</p>

                <div class="sp-timeline">
                    @forelse($testAttempts as $attempt)
                    <div class="sp-tl-item">
                        <div class="sp-tl-content">
                            <div class="sp-tl-head">
                                <span>{{ $attempt->test->title ?? 'Test orientation' }}</span>
                                <span class="sp-tl-score">{{ $attempt->score }}%</span>
                            </div>
                            <div class="sp-tl-date">
                                {{ $attempt->completed_at ? \Carbon\Carbon::parse($attempt->completed_at)->format('d/m/Y') : 'En cours' }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div style="text-align:center;padding:1.5rem 0;">
                        <div style="font-size:2rem;margin-bottom:.5rem;">📝</div>
                        <p class="sp-empty-text">Aucun test effectué pour le moment</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card sp-side-card rev rev-d3" style="text-align:center;">
                <p class="stag" style="justify-content:center;margin-bottom:1rem;">Actions rapides</p>
                <div style="display:flex;flex-direction:column;gap:.6rem;">
                    <a href="mailto:{{ $student->email }}" class="btn-ghost" style="justify-content:center;">
                        ✉️ Envoyer un email
                    </a>
                    <button class="btn-ghost" style="justify-content:center;" onclick="window.print()">
                        🖨️ Imprimer la fiche
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const revEls = document.querySelectorAll('#spRoot .rev');
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); obs.unobserve(e.target); } });
    }, { threshold: .08, rootMargin: '0px 0px -30px 0px' });
    revEls.forEach(el => obs.observe(el));
})();
</script>
@endsection