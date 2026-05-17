@extends('layouts.counselor')

@section('title', 'Dossier CRM — ' . $student->name)

@section('content')
<style>
/* ════════════════════════════════════════════
   STUDENT PROFILE CRM & MEETING SUITE
   Aesthetically matches high-end executive design
   Inspired by Salesforce and Power BI CRM
   ════════════════════════════════════════════ */
.sp {
    --ink:     var(--text-primary);
    --paper:   var(--bg-base);
    --cream:   var(--bg-1);
    --warm:    var(--bg-2);
    --accent:  var(--indigo);
    --accent2: var(--violet);
    --accent3: var(--success);
    --gold:    var(--warning);
    --ink60:   var(--text-secondary);
    --ink30:   var(--text-muted);
    --ink15:   var(--ink15);
    --ink10:   var(--glass-border);
    --ink06:   var(--glass-bg);
    --r:   var(--r);
    --rl:  var(--rl);
    --rx:  var(--rx);
    --ease: cubic-bezier(.16,1,.3,1);

    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    display: flex;
    flex-direction: column;
    gap: 1.75rem;
}

.sp *, .sp *::before, .sp *::after { box-sizing: border-box; margin: 0; padding: 0; }
.sp a { color: inherit; text-decoration: none; }

/* ── CLASSIC EXECUTIVE CARD ── */
.sp .glass-card {
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: var(--rl);
    box-shadow: var(--shadow-card);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    transition: transform 0.4s var(--ease), border-color 0.4s var(--ease), box-shadow 0.4s var(--ease);
    padding: 1.75rem;
    position: relative;
    overflow: hidden;
}
.sp .glass-card:hover {
    border-color: var(--glass-border-vivid);
}
[data-theme="dark"] .sp .glass-card {
    background: rgba(18, 24, 36, 0.65);
    box-shadow: 0 16px 48px rgba(0, 0, 0, 0.4);
}

/* ── BACK LINK ── */
.sp-back {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    font-size: .8rem;
    font-weight: 700;
    color: var(--accent2);
    text-transform: uppercase;
    letter-spacing: .05em;
    margin-bottom: .5rem;
    transition: var(--transition);
}
.sp-back:hover { transform: translateX(-4px); }

/* ── HERO BANNER ── */
.sp-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    padding: 2rem;
    flex-wrap: wrap;
}
.sp-hero-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}
.sp-hero-avatar {
    width: 64px;
    height: 64px;
    border-radius: var(--r);
    background: linear-gradient(135deg, var(--accent2), var(--accent));
    color: #fff;
    font-family: var(--font-serif);
    font-size: 1.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px color-mix(in srgb, var(--accent2) 20%, transparent);
}
.sp-hero-name {
    font-family: var(--font-serif);
    font-size: 1.8rem;
    font-weight: 300;
    letter-spacing: -.03em;
    color: var(--ink);
}
.sp-hero-name em {
    font-style: italic;
    color: var(--accent2);
}
.sp-hero-pills {
    display: flex;
    gap: .5rem;
    margin-top: .4rem;
    flex-wrap: wrap;
}
.sp-pill {
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
    padding: .22rem .6rem;
    border-radius: var(--rx);
}
.sp-pill.priority-Urgent { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
.sp-pill.priority-Surveillance { background: rgba(255, 140, 26, 0.1); color: var(--gold); border: 1px solid rgba(255, 140, 26, 0.2); }
.sp-pill.priority-Standard { background: rgba(0, 87, 184, 0.1); color: var(--accent2); border: 1px solid rgba(0, 87, 184, 0.2); }
.sp-pill.priority-Haute-performance { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }

/* ── SCORE ARC GAUGE ── */
.sp-hero-score {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.sp-hero-score-label {
    text-align: right;
}
.sp-hero-score-title { font-size: .7rem; font-weight: 700; text-transform: uppercase; color: var(--ink30); }
.sp-hero-score-desc { font-size: .78rem; color: var(--ink60); font-style: italic; }

/* ── LAYOUT GRID ── */
.sp-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1.5rem;
}

/* ── TABS ── */
.sp-tabs {
    display: flex;
    gap: .5rem;
    border-bottom: 1px solid var(--ink10);
    padding-bottom: .75rem;
    margin-bottom: 1.25rem;
    overflow-x: auto;
}
.sp-tab-btn {
    padding: .55rem 1.1rem;
    font-size: .8rem;
    font-weight: 600;
    color: var(--ink60);
    background: transparent;
    border: 1px solid transparent;
    border-radius: var(--r);
    cursor: pointer;
    transition: var(--transition);
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: .4rem;
}
.sp-tab-btn:hover {
    color: var(--ink);
    background: var(--ink06);
}
.sp-tab-btn.active {
    color: var(--accent2);
    background: color-mix(in srgb, var(--accent2) 8%, transparent);
    border-color: color-mix(in srgb, var(--accent2) 20%, transparent);
}
.sp-tab-pane {
    display: none;
    animation: tabFadeIn 0.4s var(--ease) forwards;
}
.sp-tab-pane.active {
    display: block;
}

/* ── VISIOCONFERENCE INTEGRATION ── */
.sp-video-workspace {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 1.25rem;
    height: 480px;
}
.sp-video-frame {
    background: #090D16;
    border-radius: var(--r);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255, 255, 255, 0.05);
}
.sp-video-stream {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.85;
}
.sp-video-placeholder {
    position: absolute;
    text-align: center;
    color: rgba(255,255,255,0.7);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}
.sp-video-avatar-pulse {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent2), var(--accent));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
    box-shadow: 0 0 0 0 rgba(0, 87, 184, 0.4);
    animation: pulseAvatar 2s infinite;
}
@keyframes pulseAvatar {
    0% { box-shadow: 0 0 0 0 rgba(0, 87, 184, 0.6); }
    70% { box-shadow: 0 0 0 20px rgba(0, 87, 184, 0); }
    100% { box-shadow: 0 0 0 0 rgba(0, 87, 184, 0); }
}
.sp-video-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: rgba(239, 68, 68, 0.85);
    color: #fff;
    padding: .25rem .6rem;
    border-radius: var(--rx);
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .05em;
    display: flex;
    align-items: center;
    gap: .4rem;
    text-transform: uppercase;
}
.sp-video-badge-dot {
    width: 6px;
    height: 6px;
    background: #fff;
    border-radius: 50%;
    animation: rolePulse 1s ease infinite;
}
.sp-video-controls {
    position: absolute;
    bottom: 1rem;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: .75rem;
    background: rgba(0,0,0,0.65);
    padding: .5rem .85rem;
    border-radius: var(--rx);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.1);
}
.sp-video-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: none;
    background: rgba(255,255,255,0.15);
    color: #fff;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
}
.sp-video-btn:hover {
    background: rgba(255,255,255,0.3);
}
.sp-video-btn.active-off {
    background: #ef4444 !important;
}

/* Chat next to video */
.sp-video-chat {
    border: 1px solid var(--ink10);
    background: var(--paper);
    border-radius: var(--r);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.sp-chat-header {
    padding: .75rem 1rem;
    background: var(--ink06);
    border-bottom: 1px solid var(--ink10);
    font-weight: 700;
    font-size: .8rem;
    color: var(--ink);
}
.sp-chat-messages {
    flex: 1;
    padding: 1rem;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: .85rem;
}
.sp-chat-bubble {
    max-width: 85%;
    padding: .65rem .85rem;
    border-radius: var(--r);
    font-size: .78rem;
    line-height: 1.45;
}
.sp-chat-bubble.counselor {
    background: var(--accent2);
    color: #fff;
    align-self: flex-end;
    border-bottom-right-radius: 2px;
}
.sp-chat-bubble.student {
    background: var(--cream);
    color: var(--ink);
    align-self: flex-start;
    border-bottom-left-radius: 2px;
    border: 1px solid var(--ink10);
}
.sp-chat-input-wrap {
    padding: .75rem;
    border-top: 1px solid var(--ink10);
    display: flex;
    gap: .5rem;
}
.sp-chat-input {
    flex: 1;
    padding: .5rem .75rem;
    border: 1px solid var(--ink10);
    border-radius: var(--r);
    background: var(--paper);
    color: var(--ink);
    font-size: .78rem;
    outline: none;
}
.sp-chat-input:focus { border-color: var(--accent2); }

/* ── STUDENT SUCCESS FORECAST ── */
.sp-forecast-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}
.sp-forecast-card {
    padding: 1.25rem;
    border-radius: var(--r);
    background: var(--ink06);
    border: 1px solid var(--ink10);
    text-align: center;
}
.sp-forecast-val {
    font-family: var(--font-serif);
    font-size: 2.2rem;
    font-weight: 600;
    margin-bottom: .25rem;
}
.sp-forecast-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; color: var(--ink30); }
.sp-forecast-desc { font-size: .74rem; color: var(--ink60); margin-top: .4rem; line-height: 1.4; }

.sp-trajectory-card {
    padding: 1.5rem;
    border-radius: var(--r);
    background: var(--ink06);
    border: 1px solid var(--ink10);
    margin-bottom: 1.5rem;
}
.sp-milestones {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}
.sp-milestone-item {
    background: var(--paper);
    border: 1px solid var(--ink10);
    padding: .85rem;
    border-radius: var(--r);
}

/* ── STAG ── */
.sp-stag {
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--accent2);
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    margin-bottom: .4rem;
}
.sp-stag::before {
    content: '';
    width: 12px;
    height: 1px;
    background: var(--accent2);
}
.sp-sec-title {
    font-family: var(--font-serif);
    font-size: 1.35rem;
    font-weight: 300;
    letter-spacing: -.03em;
    margin-bottom: 1rem;
    color: var(--ink);
}
.sp-sec-title em {
    font-style: italic;
    color: var(--accent2);
}

/* ── OMNICHANNEL CRM SIDEBAR ── */
.sp-crm-box {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.sp-select {
    width: 100%;
    padding: .55rem .75rem;
    border-radius: var(--r);
    border: 1px solid var(--ink10);
    background: var(--paper);
    color: var(--ink);
    font-family: var(--font-main);
    font-size: .8rem;
    font-weight: 600;
    outline: none;
    cursor: pointer;
}
.sp-textarea {
    width: 100%;
    min-height: 100px;
    padding: .75rem;
    border-radius: var(--r);
    border: 1px solid var(--ink10);
    background: var(--paper);
    color: var(--ink);
    font-family: var(--font-main);
    font-size: .8rem;
    outline: none;
    resize: vertical;
}
.sp-textarea:focus, .sp-select:focus { border-color: var(--accent2); }

.sp-comm-log {
    max-height: 240px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: .65rem;
}
.sp-comm-item {
    background: var(--ink06);
    border: 1px solid var(--ink10);
    padding: .75rem;
    border-radius: var(--r);
    font-size: .76rem;
}
.sp-comm-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: .25rem;
    font-weight: 700;
}

/* Form footer buttons */
.sp-btn-fill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    padding: .65rem 1.25rem;
    border-radius: var(--r);
    font-family: var(--font-main);
    font-weight: 600;
    font-size: .8rem;
    background: var(--accent2);
    color: #fff;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 12px color-mix(in srgb, var(--accent2) 20%, transparent);
    transition: var(--transition);
}
.sp-btn-fill:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px color-mix(in srgb, var(--accent2) 35%, transparent);
}
.sp-btn-ghost {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
    padding: .6rem 1.1rem;
    border-radius: var(--r);
    background: var(--ink06);
    border: 1px solid var(--ink10);
    color: var(--ink);
    font-family: var(--font-main);
    font-weight: 600;
    font-size: .78rem;
    cursor: pointer;
    transition: var(--transition);
}
.sp-btn-ghost:hover {
    background: var(--ink10);
}

/* ── TIMELINE ── */
.sp-tl-item {
    padding-left: 1.25rem;
    border-left: 2px solid var(--ink10);
    position: relative;
    padding-bottom: 1.25rem;
}
.sp-tl-item:last-child { padding-bottom: 0; }
.sp-tl-item::before {
    content: '';
    position: absolute;
    left: -5px;
    top: 3px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--accent2);
    border: 2px solid var(--cream);
}
.sp-tl-item.note::before { background: var(--accent); }
.sp-tl-item.test::before { background: var(--accent3); }
.sp-tl-item.appointment::before { background: var(--accent2); }

@media (max-width: 1100px) {
    .sp-grid { grid-template-columns: 1fr; }
    .sp-video-workspace { grid-template-columns: 1fr; height: auto; }
    .sp-video-frame { height: 300px; }
    .sp-video-chat { height: 250px; }
}
</style>

<div class="sp" id="spRoot">

    {{-- BACK LINK --}}
    <a href="{{ route('counselor.dashboard') }}" class="sp-back">← Retour au Tableau de bord</a>

    {{-- HERO HEADER --}}
    @php
        $score = $student->profile->ai_score ?? 84;
        $status = $student->profile->status ?? 'pending';
    @endphp
    <div class="glass-card sp-hero">
        <div class="sp-hero-info">
            <div class="sp-hero-avatar">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
            <div>
                <h1 class="sp-hero-name">{{ $student->name }}</h1>
                <div class="sp-hero-pills">
                    <span class="sp-pill priority-{{ str_replace(' ', '-', $crmPriority) }}">CRM : {{ $crmPriority }}</span>
                    <span class="sp-pill" style="background:var(--ink06); border:1px solid var(--ink10); color:var(--ink60);">Inscrit le {{ $student->created_at->format('d/m/Y') }}</span>
                    <span class="sp-pill" style="background:rgba(0, 87, 184, 0.1); color:var(--accent2); border:1px solid rgba(0, 87, 184, 0.2);">{{ $status === 'completed' ? 'Dossier certifié' : ($status === 'ongoing' ? 'Suivi actif' : 'En attente') }}</span>
                </div>
            </div>
        </div>

        <div class="sp-hero-score">
            <div class="sp-hero-score-label">
                <div class="sp-hero-score-title">Score de matching</div>
                <div class="sp-hero-score-desc">{{ $score >= 80 ? 'Adéquation Forte' : ($score >= 65 ? 'Adéquation Modérée' : 'Risque d\'incompatibilité') }}</div>
            </div>
            <svg width="64" height="64" viewBox="0 0 100 100">
                <defs>
                    <linearGradient id="scoreGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="var(--accent2)"/>
                        <stop offset="100%" stop-color="var(--accent)"/>
                    </linearGradient>
                </defs>
                <g transform="rotate(-90 50 50)">
                    <circle cx="50" cy="50" r="42" fill="none" stroke-width="8" stroke="var(--ink06)"/>
                    <circle cx="50" cy="50" r="42" fill="none" stroke="url(#scoreGrad)" stroke-width="8"
                            stroke-linecap="round"
                            stroke-dasharray="{{ 2 * 3.14159 * 42 }}"
                            stroke-dashoffset="{{ 2 * 3.14159 * 42 * (1 - $score / 100) }}"/>
                </g>
                <text x="50" y="50" text-anchor="middle" dominant-baseline="central"
                      font-family="'Fraunces', serif" font-size="24" font-weight="600"
                      fill="currentColor" letter-spacing="-1">{{ $score }}%</text>
            </svg>
        </div>
    </div>

    {{-- MAIN CONTENT GRID --}}
    <div class="sp-grid">
        
        {{-- LEFT COLUMN: DYNAMIC WORKSPACE --}}
        <div>
            <div class="sp-tabs">
                <button class="sp-tab-btn active" data-sp-tab="coaching">
                    🧘 Accompagnement & Notes
                </button>
                <button class="sp-tab-btn" data-sp-tab="video">
                    🎥 Visioconférence intégrée
                </button>
                <button class="sp-tab-btn" data-sp-tab="psychometrics">
                    🧠 Profil RIASEC / GATB
                </button>
                <button class="sp-tab-btn" data-sp-tab="success-engine">
                    🎓 Student Success Forecast
                </button>
                <button class="sp-tab-btn" data-sp-tab="audit-log">
                    🔒 Homologation & Audit
                </button>
            </div>

            {{-- TAB 1: COACHING --}}
            <div class="sp-tab-pane active" id="sp-tab-coaching">
                <div class="glass-card" style="display:flex; flex-direction:column; gap:1.5rem;">
                    <div>
                        <p class="sp-stag">Plan de Coaching</p>
                        <h3 class="sp-sec-title">Archétype Profil : <em>{{ $archetypeIcon }} {{ $archetype }}</em></h3>
                        <p style="font-size: .8rem; color: var(--ink60); line-height:1.5;">"{{ $archetypeDesc }}"</p>
                    </div>

                    {{-- Form observations --}}
                    <form action="{{ route('counselor.student.update', $student) }}" method="POST" style="display:flex; flex-direction:column; gap:1.25rem;">
                        @csrf
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem;">
                            <div>
                                <label style="display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.5rem;">Observations Conseiller</label>
                                <textarea name="counselor_observations" class="sp-textarea" placeholder="Rédiger vos observations professionnelles sur l'entretien...">{{ $student->profile->counselor_observations ?? '' }}</textarea>
                            </div>
                            <div>
                                <label style="display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.5rem;">Feuille de Route Actionnable</label>
                                <textarea name="coaching_plan" class="sp-textarea" placeholder="Définir les étapes concrètes d'accompagnement...">{{ $student->profile->coaching_plan ?? '' }}</textarea>
                            </div>
                        </div>

                        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
                            <div style="width:220px;">
                                <label style="display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.5rem;">Changer le statut du dossier</label>
                                <select name="status" class="sp-select">
                                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>En attente d'orientation</option>
                                    <option value="ongoing" {{ $status === 'ongoing' ? 'selected' : '' }}>Suivi actif (Coaching)</option>
                                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Dossier certifié & clôturé</option>
                                </select>
                            </div>
                            <button type="submit" class="sp-btn-fill">💾 Enregistrer l'Évolution</button>
                        </div>
                    </form>

                    {{-- Dynamic objectives --}}
                    <div>
                        <span style="display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:1rem;">Objectifs Personnalisés</span>
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                            @foreach($dynamicObjectives as $obj)
                                <div style="background:var(--ink06); border:1px solid var(--ink10); padding: 1rem; border-radius: var(--r);">
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.5rem;">
                                        <span style="font-weight:700; font-size:.8rem; display:flex; align-items:center; gap:.4rem;">{{ $obj['icon'] }} {{ $obj['label'] }}</span>
                                        <span style="font-family:var(--font-serif); font-size:.88rem; font-weight:600; color:var(--accent2);">{{ $obj['progress'] }}%</span>
                                    </div>
                                    <div style="height:4px; background:var(--ink10); border-radius:var(--rx); overflow:hidden;">
                                        <div style="height:100%; width:{{ $obj['progress'] }}%; background:var(--accent2); border-radius:var(--rx);"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 2: VIDEOCONFERENCE --}}
            <div class="sp-tab-pane" id="sp-tab-video">
                <div class="glass-card" style="padding: 1.25rem;">
                    <div style="margin-bottom: 1rem; display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <p class="sp-stag">Visioconférence intégrée</p>
                            <h3 class="sp-sec-title" style="margin-bottom: 0;">Meeting Virtuel <em>Sécurisé</em></h3>
                        </div>
                        <span style="font-size: .75rem; color:var(--ink30); font-weight:700;">SALLE DE CONFÉRENCE : #{{ $student->id }}42</span>
                    </div>

                    <div class="sp-video-workspace">
                        
                        {{-- Video Feed --}}
                        <div class="sp-video-frame" id="confVideoFrame">
                            <div class="sp-video-badge">
                                <span class="sp-video-badge-dot"></span>
                                EN DIRECT · CONNECTÉ
                            </div>
                            
                            {{-- Simulated Active Stream --}}
                            <div class="sp-video-placeholder" id="videoPlaceholder">
                                <div class="sp-video-avatar-pulse">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div style="font-weight:700; font-size:.9rem;">{{ $student->name }} (Étudiant)</div>
                                <div style="font-size:.72rem; opacity:.7;" id="screenShareStatus">Flux audio crypté • Caméra activée</div>
                            </div>

                            {{-- Controls --}}
                            <div class="sp-video-controls">
                                <button class="sp-video-btn" id="btnToggleCam" title="Désactiver Caméra">
                                    📹
                                </button>
                                <button class="sp-video-btn" id="btnToggleMic" title="Couper Micro">
                                    🎙️
                                </button>
                                <button class="sp-video-btn" id="btnToggleShare" title="Partager l'écran">
                                    🖥️
                                </button>
                                <button class="sp-video-btn active-off" id="btnEndCall" title="Raccrocher la visioconférence">
                                    📞
                                </button>
                            </div>
                        </div>

                        {{-- Conf Chat --}}
                        <div class="sp-video-chat">
                            <div class="sp-chat-header">Discussion en direct</div>
                            <div class="sp-chat-messages" id="chatContainer">
                                <div class="sp-chat-bubble student">
                                    Bonjour Monsieur, je suis bien connecté pour notre réunion d'orientation post-bac !
                                </div>
                                <div class="sp-chat-bubble counselor">
                                    Bonjour {{ explode(' ', $student->name)[0] }}. J'analyse actuellement tes résultats RIASEC et tes simulations IA.
                                </div>
                                <div class="sp-chat-bubble student">
                                    Super ! L'indice de saturation me faisait un peu peur mais je me sens prêt pour la suite.
                                </div>
                            </div>
                            <div class="sp-chat-input-wrap">
                                <input type="text" class="sp-chat-input" id="chatInput" placeholder="Écrire un message en direct...">
                                <button class="sp-btn-fill" id="btnSendChat" style="padding:.5rem .75rem;">➔</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- TAB 3: PSYCHOMETRICS --}}
            <div class="sp-tab-pane" id="sp-tab-psychometrics">
                <div class="glass-card" style="display:flex; flex-direction:column; gap:1.5rem;">
                    
                    {{-- GATB and RIASEC Summary --}}
                    <div>
                        <p class="sp-stag">Analytique Cognitive</p>
                        <h3 class="sp-sec-title">Dimensions d'Intérêts <em>RIASEC & Aptitudes GATB</em></h3>
                    </div>

                    <div style="display:grid; grid-template-columns: 1.2fr 1fr; gap:1.5rem;">
                        {{-- GATB scores --}}
                        <div style="display:flex; flex-direction:column; gap:.85rem;">
                            <span style="font-size: .7rem; font-weight:700; text-transform:uppercase; color:var(--ink30);">Aptitudes GATB (Cognitif Objectif)</span>
                            
                            {{-- Visual list of mock GATB dimensions --}}
                            @php
                                $gatb = [
                                    ['name' => 'G — Aptitude Générale', 'score' => 88, 'color' => 'var(--accent2)'],
                                    ['name' => 'V — Aptitude Verbale', 'score' => 74, 'color' => 'var(--accent3)'],
                                    ['name' => 'N — Aptitude Numérique', 'score' => 92, 'color' => 'var(--accent2)'],
                                    ['name' => 'S — Aptitude Spatiale', 'score' => 86, 'color' => 'var(--accent)'],
                                    ['name' => 'P — Perception Formes', 'score' => 78, 'color' => 'var(--gold)']
                                ];
                            @endphp
                            @foreach($gatb as $apt)
                                <div>
                                    <div style="display:flex; justify-content:space-between; font-size:.76rem; margin-bottom:.25rem; font-weight:600;">
                                        <span>{{ $apt['name'] }}</span>
                                        <span>{{ $apt['score'] }}/100</span>
                                    </div>
                                    <div style="height:4px; background:var(--ink10); border-radius:var(--rx); overflow:hidden;">
                                        <div style="height:100%; width:{{ $apt['score'] }}%; background:{{ $apt['color'] }};"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- RIASEC summary --}}
                        <div style="background:var(--ink06); border:1px solid var(--ink10); padding:1.25rem; border-radius:var(--r);">
                            <span style="display:block; font-size: .7rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.75rem;">Pôle RIASEC Dominant</span>
                            
                            <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1rem;">
                                <div style="width:50px; height:50px; border-radius:50%; background:linear-gradient(135deg, var(--accent2), var(--accent3)); display:flex; align-items:center; justify-content:center; font-size:1.5rem;">💻</div>
                                <div>
                                    <div style="font-weight:700; font-size:.95rem; color:var(--ink);">Investigateur · Réaliste (IR)</div>
                                    <div style="font-size:.74rem; color:var(--ink30); margin-top:.15rem;">Orientation Sciences Appliquées</div>
                                </div>
                            </div>

                            <p style="font-size:.76rem; color:var(--ink60); line-height:1.5; font-style:italic;">
                                "Le profil manifeste un intérêt prononcé pour la résolution de problèmes complexes et la manipulation d'outils techniques, parfaitement corrélé avec les scores GATB en logique numérique."
                            </p>
                        </div>
                    </div>

                    {{-- AI Direct suggestions --}}
                    <div style="background:var(--ink06); border:1px solid var(--ink10); padding: 1.25rem; border-radius: var(--r);">
                        <span style="display:block; font-size: .7rem; font-weight:700; text-transform:uppercase; color:var(--accent); margin-bottom:.85rem; letter-spacing:.05em;">🎓 Recommandations IA en Direct</span>
                        
                        <div style="display:flex; flex-direction:column; gap:.75rem;">
                            @foreach($aiSuggestions['priority_actions'] as $act)
                                <div style="display:flex; gap:.75rem; align-items:flex-start;">
                                    <span style="font-size:1.1rem; line-height:1;">🎯</span>
                                    <div>
                                        <div style="font-weight:700; font-size:.82rem; color:var(--ink);">{{ $act['title'] }}</div>
                                        <div style="font-size:.76rem; color:var(--ink60); margin-top:.15rem;">{{ $act['desc'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 4: SUCCESS FORECAST --}}
            <div class="sp-tab-pane" id="sp-tab-success-engine">
                <div class="glass-card" style="display:flex; flex-direction:column; gap:1.5rem;">
                    
                    <div>
                        <p class="sp-stag">Success Forecast Engine</p>
                        <h3 class="sp-sec-title">Gauges de <em>Réussite Prévisionnelle</em></h3>
                    </div>

                    {{-- Advanced Success & Risk Gauges --}}
                    <div class="sp-forecast-grid">
                        <div class="sp-forecast-card">
                            <div class="sp-forecast-val" style="color:var(--accent3);">{{ $successForecast['academic_success']['score'] }}%</div>
                            <div class="sp-forecast-label">Réussite Académique</div>
                            <div class="sp-forecast-desc">{{ $successForecast['academic_success']['desc'] }}</div>
                        </div>

                        <div class="sp-forecast-card">
                            <div class="sp-forecast-val" style="color:#ef4444;">{{ $successForecast['dropout_risk']['score'] }}%</div>
                            <div class="sp-forecast-label">Risque d'Abandon</div>
                            <div class="sp-forecast-desc">{{ $successForecast['dropout_risk']['desc'] }}</div>
                        </div>

                        <div class="sp-forecast-card">
                            <div class="sp-forecast-val" style="color:var(--accent2);">{{ $successForecast['satisfaction_rate']['score'] }}%</div>
                            <div class="sp-forecast-label">Taux de Satisfaction</div>
                            <div class="sp-forecast-desc">{{ $successForecast['satisfaction_rate']['desc'] }}</div>
                        </div>
                    </div>

                    {{-- Optimal Trajectory path with milestones --}}
                    <div class="sp-trajectory-card">
                        <span style="display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.5rem;">Trajectoire Optimale Prévue</span>
                        <div style="font-weight:700; font-size:.9rem; color:var(--accent2); line-height:1.45; margin-bottom:.5rem;">
                            {{ $successForecast['optimal_trajectory']['path'] }}
                        </div>
                        <p style="font-size:.78rem; color:var(--ink60); line-height:1.5; font-style:italic; margin-bottom: 1.25rem;">
                            "{{ $successForecast['optimal_trajectory']['rationale'] }}"
                        </p>

                        <span style="display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.75rem;">Jalons de Réussite de la Trajectoire</span>
                        <div class="sp-milestones">
                            @foreach($successForecast['optimal_trajectory']['milestones'] as $mile)
                                <div class="sp-milestone-item">
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.25rem;">
                                        <span style="font-weight:700; font-size:.78rem;">{{ $mile['title'] }}</span>
                                        <span style="font-size:.65rem; font-weight:700; text-transform:uppercase; padding:.15rem .4rem; border-radius:4px; 
                                            background:{{ $mile['status'] === 'Optimale' ? 'rgba(16,185,129,0.1)' : 'rgba(0, 87, 184, 0.1)' }}; 
                                            color:{{ $mile['status'] === 'Optimale' ? '#10b981' : 'var(--accent2)' }};">
                                            {{ $mile['status'] }}
                                        </span>
                                    </div>
                                    <p style="font-size:.72rem; color:var(--ink60); line-height:1.4;">{{ $mile['detail'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Predictive Indicators --}}
                    <div>
                        <span style="display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:1rem;">Gauges Prédictives Additionnelles (Axe 6)</span>
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                            @foreach($crmPredictiveIndicators as $indicator)
                                <div style="background:var(--ink06); border:1px solid var(--ink10); padding:1rem; border-radius:var(--r);">
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.5rem;">
                                        <span style="font-weight:700; font-size:.78rem;">{{ $indicator['icon'] }} {{ $indicator['label'] }}</span>
                                        <span style="font-weight:700; font-size:.78rem; color:{{ $indicator['color'] }}">{{ $indicator['level'] }} ({{ $indicator['value'] }}%)</span>
                                    </div>
                                    <p style="font-size:.72rem; color:var(--ink60); line-height:1.4;">{{ $indicator['desc'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            {{-- TAB 5: AUDIT LOG --}}
            <div class="sp-tab-pane" id="sp-tab-audit-log">
                <div class="glass-card" style="display:flex; flex-direction:column; gap:1.5rem;">
                    
                    {{-- Homologation check --}}
                    <div>
                        <p class="sp-stag">Registre d'Accompagnement</p>
                        <h3 class="sp-sec-title">Homologation de la <em>Trajectoire d'Orientation</em></h3>
                    </div>

                    {{-- Multicriteria validation indicators --}}
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:1rem; margin-bottom:1rem;">
                        @foreach($collaborativeValidation as $valKey => $validation)
                            <div style="background:var(--ink06); border:1px solid var(--ink10); padding: 1rem; border-radius: var(--r); text-align:center;">
                                <span style="font-size:1.5rem;">
                                    @if($validation['status'] === 'success') ✅ @elseif($validation['status'] === 'warning') ⚠️ @else ⏳ @endif
                                </span>
                                <div style="font-weight:700; font-size:.82rem; margin-top:.4rem; color:var(--ink);">{{ $validation['label'] }}</div>
                                <div style="font-size:.7rem; color:var(--ink30); margin-top:.2rem;">{{ $validation['desc'] }}</div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Form action for matching --}}
                    <div style="background:var(--ink06); border:1px solid var(--ink10); padding: 1.25rem; border-radius: var(--r);">
                        <span style="display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.85rem;">Enregistrer une décision d'orientation</span>
                        
                        <form action="{{ route('counselor.student.match', $student) }}" method="POST" style="display:flex; flex-direction:column; gap:1rem;">
                            @csrf
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                                <div>
                                    <label style="display:block; font-size:.72rem; font-weight:700; color:var(--ink60); margin-bottom:.4rem;">Type de décision</label>
                                    <select name="action_type" class="sp-select">
                                        <option value="approve">Homologuer la proposition IA</option>
                                        <option value="reject">Désapprouver / Rejeter la proposition IA</option>
                                        <option value="modify_trajectory">Ajuster / Modifier la trajectoire prédictive</option>
                                        <option value="change_field">Réorienter vers un autre pôle de formation</option>
                                    </select>
                                </div>
                                <div>
                                    <label style="display:block; font-size:.72rem; font-weight:700; color:var(--ink60); margin-bottom:.4rem;">Filière ou Option Cible (Optionnel)</label>
                                    <input type="text" name="target_field" placeholder="Ex: Génie Logiciel, Cloud Computing..." class="sp-select" style="background:var(--paper); cursor:text;">
                                </div>
                            </div>

                            <div>
                                <label style="display:block; font-size:.72rem; font-weight:700; color:var(--ink60); margin-bottom:.4rem;">Justification de la décision (Registre d'audit)</label>
                                <textarea name="justification" class="sp-textarea" required placeholder="Expliquer méthodologiquement les raisons de ce choix ou de cet ajustement d'orientation..."></textarea>
                            </div>

                            <button type="submit" class="sp-btn-fill" style="align-self:flex-start;">✒️ Valider et Inscrire la Décision</button>
                        </form>
                    </div>

                    {{-- Decision log history --}}
                    <div>
                        <span style="display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.85rem;">Registre d'Audit Historique</span>
                        <div style="display:flex; flex-direction:column; gap:.75rem;">
                            @foreach($decisionHistory as $hist)
                                <div style="font-size:.78rem; padding:.85rem; border-radius:var(--r); background:var(--paper); border:1px solid var(--ink10);">
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.25rem; font-weight:700; color:var(--ink);">
                                        <span>{{ $hist['who'] }} · <span style="font-weight:500; font-size:.72rem; color:var(--accent2);">{{ $hist['type'] }}</span></span>
                                        <span style="font-size:.68rem; color:var(--ink30);">{{ $hist['when'] }}</span>
                                    </div>
                                    <div style="font-weight:600; color:var(--ink60); margin-bottom:.25rem;">{{ $hist['what'] }}</div>
                                    <p style="font-size:.74rem; color:var(--ink30); line-height:1.4;">Motif : "{{ $hist['why'] }}"</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: SIDEBAR CONTROLS & OMNICHANNEL --}}
        <div class="sp-crm-box">
            
            {{-- Planification Intelligente Calendrier --}}
            <div class="glass-card">
                <p class="sp-stag">Agenda Intégré</p>
                <h3 class="sp-sec-title">Planifier un <em>Rendez-vous</em></h3>

                <form action="{{ route('counselor.appointments.store', $student) }}" method="POST" style="display:flex; flex-direction:column; gap:.75rem; margin-bottom: 1.25rem;">
                    @csrf
                    <div>
                        <label style="display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.4rem;">Date & Heure de la session</label>
                        <input type="datetime-local" name="scheduled_at" required class="sp-select" style="background:var(--paper); cursor:pointer;">
                    </div>
                    <div>
                        <label style="display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.4rem;">Format du meeting</label>
                        <select name="meeting_format" class="sp-select">
                            <option value="virtual">💻 Visioconférence Sécurisée en ligne</option>
                            <option value="physical">🏫 Entretien Physique en Bureau</option>
                            <option value="hybrid">🤝 Format Hybride d'orientation</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.4rem;">Motif / Thème principal</label>
                        <input type="text" name="notes" placeholder="Ex: Entretien de suivi vœu 1" class="sp-select" style="background:var(--paper); cursor:text;">
                    </div>
                    <button type="submit" class="sp-btn-fill" style="width:100%;">📅 Bloquer le Créneau</button>
                </form>

                @if($appointments->count() > 0)
                    <span style="display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.5rem;">Prochains rendez-vous</span>
                    <div style="display:flex; flex-direction:column; gap:.5rem;">
                        @foreach($appointments as $apt)
                            <div style="font-size:.74rem; padding:.65rem; background:var(--ink06); border:1px solid var(--ink10); border-radius:var(--r); display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <div style="font-weight:700;">{{ $apt->scheduled_at->format('d/m/Y H:i') }}</div>
                                    <div style="font-size:.68rem; color:var(--ink30); margin-top:.1rem;">Format : {{ $apt->notes ?: 'Orientation Individuelle' }}</div>
                                </div>
                                <span style="font-size:.62rem; font-weight:700; text-transform:uppercase; padding:.15rem .4rem; border-radius:4px; 
                                    background:{{ $apt->status === 'completed' ? 'rgba(16,185,129,0.1)' : 'rgba(0, 87, 184, 0.1)' }}; 
                                    color:{{ $apt->status === 'completed' ? '#10b981' : 'var(--accent2)' }};">
                                    {{ $apt->status === 'completed' ? 'Fait' : 'Prévu' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Omnichannel Communication Center --}}
            <div class="glass-card">
                <p class="sp-stag">Omnicanal v5.0</p>
                <h3 class="sp-sec-title">Console de <em>Communication</em></h3>

                <form action="{{ route('counselor.student.sendMessage', $student) }}" method="POST" style="display:flex; flex-direction:column; gap:.75rem; margin-bottom: 1.5rem;">
                    @csrf
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:.5rem;">
                        <div>
                            <label style="display:block; font-size:.65rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.3rem;">Canal ciblé</label>
                            <select name="channel" id="commChannel" class="sp-select">
                                <option value="chat">💬 Chat Interne</option>
                                <option value="email">✉ Email direct</option>
                                <option value="notification">🔔 Push Notification</option>
                                <option value="sms">📱 Alerte SMS</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block; font-size:.65rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.3rem;">Modèle intelligent</label>
                            <select id="commTemplate" class="sp-select">
                                <option value="">-- Personnalisé --</option>
                                <option value="convocation">Convocation entretien</option>
                                <option value="relance">Relance retard vœux</option>
                                <option value="conseil">Recommandation filière</option>
                                <option value="alerte">Alerte inactivité</option>
                            </select>
                        </div>
                    </div>

                    {{-- Hidden helper input for template type --}}
                    <input type="hidden" name="template_type" id="hiddenTemplateType" value="custom">

                    <div>
                        <label style="display:block; font-size:.65rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.4rem;">Corps du message</label>
                        <textarea name="message_body" id="commBody" class="sp-textarea" required placeholder="Saisir ou charger un modèle de message..."></textarea>
                    </div>

                    <button type="submit" class="sp-btn-fill" style="width:100%;">🚀 Transmettre le Message</button>
                </form>

                {{-- Communication history --}}
                <span style="display:block; font-size:.7rem; font-weight:700; text-transform:uppercase; color:var(--ink30); margin-bottom:.6rem;">Journal omnicanal des envois</span>
                <div class="sp-comm-log">
                    @foreach($crmCommunicationLog as $log)
                        <div class="sp-comm-item">
                            <div class="sp-comm-header">
                                <span>{{ $log['icon'] }} {{ $log['channel_label'] }}</span>
                                <span style="font-size:.65rem; color:var(--ink30); font-weight:500;">{{ $log['date'] }}</span>
                            </div>
                            <div style="font-weight:700; font-size:.7rem; margin-bottom:.2rem; color:var(--accent2);">{{ $log['subject'] }}</div>
                            <p style="color:var(--ink60); font-size:.72rem; line-height:1.4;">"{{ $log['body'] }}"</p>
                            <div style="text-align:right; font-size:.65rem; font-weight:700; color:var(--accent3); margin-top:.2rem;">
                                ✓ {{ $log['status'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── TAB NAVIGATION ── */
    const tabBtns = document.querySelectorAll('.sp-tab-btn');
    const tabPanes = document.querySelectorAll('.sp-tab-pane');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.spTab;

            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanes.forEach(p => p.classList.remove('active'));

            btn.classList.add('active');
            const pane = document.getElementById('sp-tab-' + target);
            if (pane) pane.classList.add('active');
        });
    });

    /* ── INTELLIGENT COMM TEMPLATES ── */
    const templates = {!! json_encode($crmCommunicationTemplates) !!};
    const selTemplate = document.getElementById('commTemplate');
    const txtBody = document.getElementById('commBody');
    const hiddenType = document.getElementById('hiddenTemplateType');

    selTemplate?.addEventListener('change', () => {
        const selected = selTemplate.value;
        if (selected && templates[selected]) {
            txtBody.value = templates[selected];
            hiddenType.value = selected;
        } else {
            txtBody.value = '';
            hiddenType.value = 'custom';
        }
    });

    /* ── VISIOCONFERENCE INTERACTIVE MOCK ACTIONS ── */
    const btnToggleCam = document.getElementById('btnToggleCam');
    const btnToggleMic = document.getElementById('btnToggleMic');
    const btnToggleShare = document.getElementById('btnToggleShare');
    const btnEndCall = document.getElementById('btnEndCall');
    const screenShareStatus = document.getElementById('screenShareStatus');
    const videoPlaceholder = document.getElementById('videoPlaceholder');
    const confVideoFrame = document.getElementById('confVideoFrame');

    let camActive = true;
    let micActive = true;
    let shareActive = false;

    btnToggleCam?.addEventListener('click', () => {
        camActive = !camActive;
        if (camActive) {
            btnToggleCam.classList.remove('active-off');
            btnToggleCam.innerHTML = '📹';
            videoPlaceholder.style.opacity = '1';
        } else {
            btnToggleCam.classList.add('active-off');
            btnToggleCam.innerHTML = '❌ 📹';
            videoPlaceholder.style.opacity = '0.3';
        }
    });

    btnToggleMic?.addEventListener('click', () => {
        micActive = !micActive;
        if (micActive) {
            btnToggleMic.classList.remove('active-off');
            btnToggleMic.innerHTML = '🎙️';
        } else {
            btnToggleMic.classList.add('active-off');
            btnToggleMic.innerHTML = '❌ 🎙️';
        }
    });

    btnToggleShare?.addEventListener('click', () => {
        shareActive = !shareActive;
        if (shareActive) {
            btnToggleShare.classList.add('active-off');
            btnToggleShare.innerHTML = '❌ 🖥️';
            screenShareStatus.innerHTML = 'Partage d\'écran actif · Flux audio crypté';
            confVideoFrame.style.background = '#1a202c';
        } else {
            btnToggleShare.classList.remove('active-off');
            btnToggleShare.innerHTML = '🖥️';
            screenShareStatus.innerHTML = 'Flux audio crypté • Caméra activée';
            confVideoFrame.style.background = '#090D16';
        }
    });

    btnEndCall?.addEventListener('click', () => {
        if (confirm("Voulez-vous vraiment clore cette session de visioconférence et enregistrer la fin d'appel ?")) {
            confVideoFrame.style.background = '#111';
            videoPlaceholder.innerHTML = '<span style="font-weight:700;color:#ef4444;font-size:1.1rem;">Visioconférence terminée</span><span style="font-size:.78rem;opacity:.7;">Entretien clos avec succès</span>';
            document.getElementById('btnToggleCam').disabled = true;
            document.getElementById('btnToggleMic').disabled = true;
            document.getElementById('btnToggleShare').disabled = true;
            btnEndCall.disabled = true;
            btnEndCall.style.opacity = '0.3';
        }
    });

    /* Chat Interactive direct posting */
    const chatInput = document.getElementById('chatInput');
    const chatContainer = document.getElementById('chatContainer');
    const btnSendChat = document.getElementById('btnSendChat');

    function sendChatMessage() {
        const msgText = chatInput.value.trim();
        if (!msgText) return;

        const newBubble = document.createElement('div');
        newBubble.className = 'sp-chat-bubble counselor';
        newBubble.textContent = msgText;
        chatContainer.appendChild(newBubble);
        chatInput.value = '';
        chatContainer.scrollTop = chatContainer.scrollHeight;

        // Mock automated student reply
        setTimeout(() => {
            const replyBubble = document.createElement('div');
            replyBubble.className = 'sp-chat-bubble student';
            replyBubble.textContent = "Merci pour ces éclaircissements, Monsieur. C'est enregistré !";
            chatContainer.appendChild(replyBubble);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }, 1500);
    }

    btnSendChat?.addEventListener('click', sendChatMessage);
    chatInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') sendChatMessage();
    });

});
</script>
@endsection