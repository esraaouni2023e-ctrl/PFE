@extends('layouts.counselor')

@section('title', 'Tableau de Bord')

@section('content')
<style>
/* ════════════════════════════════════════════
   COUNSELOR DASHBOARD — CapAvenir System
   Aligned with student dashboard tokens
════════════════════════════════════════════ */
.cd {
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
    --ink10:   rgba(11,12,16,.1);
    --ink06:   rgba(11,12,16,.06);
    --r:   6px;
    --rl:  16px;
    --rx:  999px;
    --ease: cubic-bezier(.16,1,.3,1);
    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    display: flex; flex-direction: column; gap: 2.5rem;
}

[data-theme="dark"]  .cd { --ink:#f0ede6;--paper:#10100d;--cream:#18170f;--warm:#1f1e14;--ink60:rgba(240,237,230,.6);--ink30:rgba(240,237,230,.3);--ink10:rgba(240,237,230,.08);--ink06:rgba(240,237,230,.04); }
[data-theme="light"] .cd { --ink:#0b0c10;--paper:#f7f5f0;--cream:#ede9e1;--warm:#e8e1d4;--ink60:rgba(11,12,16,.6);--ink30:rgba(11,12,16,.3);--ink10:rgba(11,12,16,.1);--ink06:rgba(11,12,16,.06); }

.cd *, .cd *::before, .cd *::after { box-sizing: border-box; margin: 0; padding: 0; }
.cd a { color: inherit; text-decoration: none; }

/* ── CARD ── */
.cd .card {
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: var(--rl);
    transition: all .3s var(--ease);
}
.cd .card:hover { border-color: rgba(11,12,16,.25); }
[data-theme="dark"] .cd .card:hover { border-color: rgba(240,237,230,.18); }

/* ── KPI GRID ── */
.cd-kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.25rem;
}

/* ── KPI CARD ── */
.cd-kpi {
    padding: 1.75rem;
    display: flex; flex-direction: column;
}
.cd-kpi-label {
    font-size: .68rem; font-weight: 700; letter-spacing: .12em;
    text-transform: uppercase; margin-bottom: .6rem;
}
.cd-kpi-val {
    font-family: 'Fraunces', serif;
    font-size: 2.6rem; font-weight: 600;
    line-height: 1; letter-spacing: -.05em;
}
.cd-kpi-val sup { font-size: 1.4rem; color: var(--ink30); font-weight: 400; }
.cd-kpi-sub {
    font-size: .78rem; color: var(--ink60);
    margin-top: .35rem; font-style: italic;
}
.cd-kpi-pill {
    margin-top: 1.1rem;
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .25rem .7rem; border-radius: var(--rx);
    font-size: .68rem; font-weight: 700;
}
.cd-kpi-bar {
    margin-top: 1.1rem; height: 4px;
    background: var(--ink10); border-radius: var(--rx); overflow: hidden;
}
.cd-kpi-bar-fill {
    height: 100%; border-radius: var(--rx);
    background: var(--accent2);
    transition: width .8s var(--ease);
}

/* Focus list */
.cd-focus-list { display: flex; flex-direction: column; gap: .75rem; margin-top: .75rem; }
.cd-focus-row  { display: flex; justify-content: space-between; align-items: center; }
.cd-focus-label { font-size: .8rem; color: var(--ink60); }
.cd-focus-val   { font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 600; letter-spacing: -.02em; }

/* ── SECTION HEADER ── */
.cd-sec-head {
    display: flex; justify-content: space-between;
    align-items: flex-end; margin-bottom: 1.75rem;
    flex-wrap: wrap; gap: 1rem;
}
.cd-stag {
    font-size: .7rem; font-weight: 700; letter-spacing: .12em;
    text-transform: uppercase; color: var(--accent2);
    display: inline-flex; align-items: center; gap: .45rem; margin-bottom: .5rem;
}
.cd-stag::before { content: ''; width: 14px; height: 1px; background: var(--accent2); }
.cd-sh {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.4rem, 2.5vw, 1.9rem);
    font-weight: 300; letter-spacing: -.035em; line-height: 1.1;
}
.cd-sh em { font-style: italic; color: var(--accent2); }
.cd-sub {
    font-size: .72rem; color: var(--ink30); font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase; margin-top: .25rem;
}

/* ── SEARCH ── */
.cd-search-wrap { position: relative; }
.cd-search {
    background: var(--ink06); border: 1px solid var(--ink10);
    border-radius: var(--r); padding: .55rem 1rem .55rem 2.3rem;
    font-size: .82rem; color: var(--ink);
    font-family: 'DM Sans', sans-serif; width: 250px;
    outline: none; transition: border-color .25s;
}
.cd-search:focus { border-color: var(--accent2); }
.cd-search::placeholder { color: var(--ink30); }
.cd-search-icon {
    position: absolute; left: .75rem; top: 50%; transform: translateY(-50%);
    color: var(--ink30); pointer-events: none; font-size: .85rem;
}

/* ── STUDENT GRID ── */
.cd-student-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.25rem;
}

/* ── STUDENT CARD ── */
.cd-scard {
    overflow: hidden; padding: 0;
    transition: transform .3s var(--ease), border-color .3s var(--ease);
}
.cd-scard:hover {
    transform: translateY(-4px);
    border-color: color-mix(in srgb, var(--accent2) 35%, transparent) !important;
}
.cd-scard-bar {
    height: 3px;
    background: linear-gradient(90deg, var(--accent2), color-mix(in srgb,var(--accent2) 30%,transparent));
}
.cd-scard-body { padding: 1.4rem; }
.cd-scard-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.2rem; }
.cd-scard-meta { display: flex; align-items: center; gap: .8rem; }

/* Avatar */
.cd-avatar {
    width: 44px; height: 44px; border-radius: var(--r);
    background: var(--accent2);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Fraunces', serif; font-size: 1.15rem; font-weight: 600;
    color: #fff; flex-shrink: 0;
}
.cd-scard-name { font-weight: 700; font-size: .95rem; line-height: 1.2; margin-bottom: .15rem; }
.cd-scard-email { font-size: .72rem; color: var(--ink30); }

/* Status badge */
.cd-status {
    display: inline-flex; font-size: .66rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    padding: .25rem .65rem; border-radius: var(--rx); white-space: nowrap;
}

/* Score bar */
.cd-score-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: .45rem; }
.cd-score-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--ink30); }
.cd-score-val   { font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 600; letter-spacing: -.02em; }
.cd-bar-track   { height: 4px; background: var(--ink10); border-radius: var(--rx); overflow: hidden; }
.cd-bar-fill    { height: 100%; border-radius: var(--rx); background: var(--accent2); transition: width 1s var(--ease); }

/* Card footer */
.cd-scard-foot {
    padding: .85rem 1.4rem;
    background: var(--ink06);
    border-top: 1px solid var(--ink10);
    display: flex; align-items: center; justify-content: space-between;
}
.cd-scard-date { font-size: .7rem; color: var(--ink30); font-style: italic; }
.cd-scard-link {
    width: 32px; height: 32px; border-radius: 50%;
    background: var(--cream); border: 1px solid var(--ink10);
    display: flex; align-items: center; justify-content: center;
    color: var(--ink60); font-size: .9rem;
    transition: all .25s var(--ease);
}
.cd-scard-link:hover { background: var(--accent2); color: #fff; border-color: var(--accent2); }

/* Empty state */
.cd-empty {
    text-align: center; padding: 4rem 2rem; color: var(--ink30);
}
.cd-empty-icon { font-size: 3rem; margin-bottom: 1rem; }
.cd-empty-title { font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 600; color: var(--ink60); margin-bottom: .5rem; }
.cd-empty-desc  { font-size: .85rem; }

/* ── MINI CHART ── */
.cd-chart-wrap { margin-top: 1.1rem; height: 56px; }

/* ── ANALYTICS ROW ── */
.cd-analytics {
    display: grid;
    grid-template-columns: 340px 1fr;
    gap: 1.25rem;
}
.cd-analytics-card { padding: 1.75rem; }
.cd-chart-lg { height: 200px; margin-top: .75rem; }

/* ── BOTTOM ROW ── */
.cd-bottom-row {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 1.25rem;
}

/* ── ACTIVITY TIMELINE ── */
.cd-activity { padding: 1.75rem; }
.cd-tl { display: flex; flex-direction: column; gap: 0; }
.cd-tl-item {
    padding-left: 1.25rem;
    border-left: 2px solid var(--ink10);
    position: relative;
    padding-bottom: 1.1rem;
}
.cd-tl-item:last-child { padding-bottom: 0; }
.cd-tl-item::before {
    content: '';
    position: absolute; left: -5px; top: 3px;
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--accent2);
    border: 2px solid var(--cream);
}
.cd-tl-item.tl-accent::before { background: var(--accent); }
.cd-tl-item.tl-sage::before { background: var(--accent3); }
.cd-tl-item.tl-gold::before { background: var(--gold); }

.cd-tl-text { font-size: .85rem; color: var(--ink60); line-height: 1.5; }
.cd-tl-text strong { color: var(--ink); font-weight: 600; }
.cd-tl-time { font-size: .68rem; color: var(--ink30); margin-top: .2rem; }

/* ── QUICK ACTIONS ── */
.cd-actions { padding: 1.75rem; }
.cd-action-btn {
    display: flex; align-items: center; gap: .75rem;
    width: 100%; padding: .9rem 1rem; border-radius: var(--r);
    background: var(--paper); border: 1px solid var(--ink10);
    font-family: 'DM Sans', sans-serif; font-size: .85rem;
    font-weight: 600; color: var(--ink); cursor: pointer;
    text-decoration: none; transition: all .25s var(--ease);
    text-align: left;
}
.cd-action-btn:hover {
    border-color: color-mix(in srgb, var(--accent2) 35%, transparent);
    background: color-mix(in srgb, var(--accent2) 4%, transparent);
}
.cd-action-icon {
    width: 38px; height: 38px; border-radius: var(--r);
    background: color-mix(in srgb, var(--accent2) 8%, transparent);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.cd-action-desc { font-size: .72rem; color: var(--ink30); font-weight: 500; margin-top: .1rem; }

/* ── PILL inline ── */
.cd-pill {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .22rem .6rem; border-radius: var(--rx);
    font-size: .65rem; font-weight: 700;
}

/* ── Reveal ── */
.cd .rev { opacity: 0; transform: translateY(20px); transition: opacity .6s var(--ease), transform .6s var(--ease); }
.cd .rev.vis { opacity: 1; transform: none; }
.cd .rev-d1 { transition-delay: .06s; }
.cd .rev-d2 { transition-delay: .12s; }
.cd .rev-d3 { transition-delay: .18s; }
.cd .rev-d4 { transition-delay: .24s; }

/* ── RESPONSIVE ── */
@media (max-width: 900px) {
    .cd-analytics { grid-template-columns: 1fr; }
    .cd-bottom-row { grid-template-columns: 1fr; }
}
@media (max-width: 700px) {
    .cd-kpi-grid { grid-template-columns: 1fr 1fr; }
    .cd-student-grid { grid-template-columns: 1fr; }
    .cd-search { width: 100%; }
}
@media (max-width: 480px) {
    .cd-kpi-grid { grid-template-columns: 1fr; }
}
</style>

<div class="cd" id="cdRoot">

    {{-- ═══ TOP KPI GRID ═══ --}}
    <div class="cd-kpi-grid rev">

        {{-- KPI 1 — Étudiants suivis --}}
        <div class="card cd-kpi">
            <p class="cd-kpi-label" style="color:var(--accent2);">Étudiants Suivis</p>
            <div class="cd-kpi-val">{{ $students->count() }}</div>
            <p class="cd-kpi-sub">Actifs sur la plateforme</p>
            <span class="cd-kpi-pill"
                  style="background:color-mix(in srgb,var(--accent2) 10%,transparent);color:var(--accent2);border:1px solid color-mix(in srgb,var(--accent2) 22%,transparent);">
                +2 cette semaine
            </span>
        </div>

        {{-- KPI 2 — Trajectoires validées --}}
        @php
            $completedCount = $students->filter(fn($s) => ($s->profile->status ?? 'pending') === 'completed')->count();
            $ongoingCount   = $students->filter(fn($s) => ($s->profile->status ?? 'pending') === 'ongoing')->count();
            $pendingCount   = $students->count() - $completedCount - $ongoingCount;
        @endphp
        <div class="card cd-kpi">
            <p class="cd-kpi-label" style="color:var(--accent);">Trajectoires Validées</p>
            <div class="cd-kpi-val">{{ $completedCount }}</div>
            <p class="cd-kpi-sub">Profils certifiés IA</p>
            <div class="cd-kpi-bar">
                <div class="cd-kpi-bar-fill" style="width:{{ $students->count() > 0 ? round($completedCount/$students->count()*100) : 0 }}%;background:var(--accent);"></div>
            </div>
        </div>

        {{-- KPI 3 — Indice de réussite --}}
        <div class="card cd-kpi">
            <p class="cd-kpi-label" style="color:var(--gold);">Indice de Réussite</p>
            <div class="cd-kpi-val">92<sup>%</sup></div>
            <p class="cd-kpi-sub">Satisfaction IA globale</p>
            <div class="cd-chart-wrap">
                <canvas id="miniSuccessChart"></canvas>
            </div>
        </div>

        {{-- KPI 4 — Focus quotidien --}}
        <div class="card cd-kpi">
            <p class="cd-kpi-label" style="color:var(--ink30);">Focus Quotidien</p>
            <div class="cd-focus-list">
                <div class="cd-focus-row">
                    <span class="cd-focus-label">Tests à valider</span>
                    <span class="cd-focus-val" style="color:var(--accent3);">{{ $students->where('profile.status', 'pending')->count() }}</span>
                </div>
                <div class="cd-focus-row">
                    <span class="cd-focus-label">Étudiants à risque</span>
                    <span class="cd-focus-val" style="color:#ef4444;">{{ $students->filter(fn($s) => ($s->profile->ai_score ?? 100) < 65)->count() }}</span>
                </div>
                <div class="cd-focus-row">
                    <span class="cd-focus-label">Rendez-vous prévus</span>
                    <span class="cd-focus-val" style="color:var(--accent);">{{ $appointments->where('status', 'scheduled')->count() }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- ═══ ANALYTICS ROW ═══ --}}
    <div class="cd-analytics rev">
        {{-- Distribution doughnut --}}
        <div class="card cd-analytics-card">
            <p class="cd-stag">Répartition</p>
            <h3 class="cd-sh">Statuts des <em>dossiers</em></h3>
            <div class="cd-chart-lg">
                <canvas id="statusDistChart"></canvas>
            </div>
        </div>

        {{-- Cohort analysis --}}
        <div class="card cd-analytics-card">
            <p class="cd-stag">Analyse de Cohorte</p>
            <h3 class="cd-sh">Tendances <em>d'orientation</em></h3>
            <div class="cd-chart-lg">
                <canvas id="cohortChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ═══ STUDENT DIRECTORY ═══ --}}
    <div class="rev">
        <div class="cd-sec-head">
            <div>
                <p class="cd-stag">Surveillance temps réel</p>
                <h2 class="cd-sh">Répertoire des <em>trajectoires</em></h2>
                <p class="cd-sub">Suivi individuel des profils IA</p>
            </div>
            <div class="cd-search-wrap">
                <span class="cd-search-icon">🔍</span>
                <input type="text" class="cd-search" placeholder="Rechercher un étudiant…"
                       id="studentSearch">
            </div>
        </div>

        <div class="cd-student-grid" id="studentGrid">
            @foreach($students as $student)
            @php
                $status = $student->profile->status ?? 'pending';
                $statusStyles = match($status) {
                    'completed' => ['bg'=>'color-mix(in srgb,var(--accent3) 10%,transparent)','cl'=>'var(--accent3)','bd'=>'color-mix(in srgb,var(--accent3) 25%,transparent)','lbl'=>'Certifié'],
                    'ongoing'   => ['bg'=>'color-mix(in srgb,var(--accent2) 10%,transparent)','cl'=>'var(--accent2)','bd'=>'color-mix(in srgb,var(--accent2) 25%,transparent)','lbl'=>'En cours'],
                    default     => ['bg'=>'var(--ink06)','cl'=>'var(--ink30)','bd'=>'var(--ink10)','lbl'=>'En attente'],
                };
                $score = $student->profile->ai_score ?? rand(62, 97);
                $scoreColor = $score >= 80 ? 'var(--accent2)' : ($score >= 65 ? 'var(--gold)' : '#ef4444');
            @endphp

            <div class="card cd-scard" data-name="{{ strtolower($student->name) }}">
                <div class="cd-scard-bar"></div>
                <div class="cd-scard-body">
                    <div class="cd-scard-head">
                        <div class="cd-scard-meta">
                            <div class="cd-avatar">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                            <div>
                                <div class="cd-scard-name">{{ $student->name }}</div>
                                <div class="cd-scard-email">{{ $student->email }}</div>
                            </div>
                        </div>
                        <span class="cd-status"
                              style="background:{{ $statusStyles['bg'] }};color:{{ $statusStyles['cl'] }};border:1px solid {{ $statusStyles['bd'] }};">
                            {{ $statusStyles['lbl'] }}
                        </span>
                    </div>

                    <div>
                        <div class="cd-score-head">
                            <span class="cd-score-label">Score IA</span>
                            <span class="cd-score-val" style="color:{{ $scoreColor }};">{{ $score }}%</span>
                        </div>
                        <div class="cd-bar-track">
                            <div class="cd-bar-fill match-fill" style="width:{{ $score }}%;background:{{ $scoreColor }};"></div>
                        </div>
                    </div>
                </div>

                <div class="cd-scard-foot">
                    <span class="cd-scard-date">
                        @if($score < 65)
                            <span style="color:#ef4444; font-weight:bold;">⚠️ Risque de décrochage</span>
                        @else
                            Inscrit le {{ $student->created_at->format('d/m/Y') }}
                        @endif
                    </span>
                    <a href="{{ route('counselor.student.show', $student) }}" class="cd-scard-link">→</a>
                </div>
            </div>
            @endforeach
        </div>

        @if($students->isEmpty())
        <div class="card cd-empty">
            <div class="cd-empty-icon">👥</div>
            <p class="cd-empty-title">Aucun étudiant assigné</p>
            <p class="cd-empty-desc">Les étudiants apparaîtront ici une fois assignés à votre portefeuille.</p>
        </div>
        @endif
    </div>

    {{-- ═══ BOTTOM ROW — Activity + Quick Actions ═══ --}}
    <div class="cd-bottom-row rev">

        {{-- Recent Activity Timeline --}}
        <div class="card cd-activity">
            <p class="cd-stag">Journal</p>
            <h3 class="cd-sh" style="margin-bottom:1.5rem;">Activités <em>récentes</em></h3>

            <div class="cd-tl">
                <div class="cd-tl-item tl-sage">
                    <div class="cd-tl-text"><strong>Dossier validé</strong> — Le profil de Sarah M. a été certifié par l'IA</div>
                    <div class="cd-tl-time">Il y a 2 heures</div>
                </div>
                <div class="cd-tl-item tl-accent">
                    <div class="cd-tl-text"><strong>Test complété</strong> — Ahmed K. a terminé le test d'orientation avancé (87%)</div>
                    <div class="cd-tl-time">Il y a 4 heures</div>
                </div>
                <div class="cd-tl-item">
                    <div class="cd-tl-text"><strong>Nouveau étudiant</strong> — Yasmine B. a été assignée à votre portefeuille</div>
                    <div class="cd-tl-time">Hier à 16:30</div>
                </div>
                <div class="cd-tl-item tl-gold">
                    <div class="cd-tl-text"><strong>Alerte IA</strong> — Score matching bas détecté pour Omar L. (54%)</div>
                    <div class="cd-tl-time">Hier à 11:15</div>
                </div>
                <div class="cd-tl-item tl-sage">
                    <div class="cd-tl-text"><strong>Plan mis à jour</strong> — Notes d'accompagnement modifiées pour Nour A.</div>
                    <div class="cd-tl-time">Il y a 2 jours</div>
                </div>
                <div class="cd-tl-item">
                    <div class="cd-tl-text"><strong>Rendez-vous</strong> — Session de conseil planifiée avec Amine R.</div>
                    <div class="cd-tl-time">Il y a 3 jours</div>
                </div>
            </div>
        </div>

        {{-- Appointments --}}
        <div class="card cd-actions">
            <p class="cd-stag">Planning</p>
            <h3 class="cd-sh" style="margin-bottom:1.25rem;">Mes <em>Rendez-vous</em></h3>

            <div style="display:flex;flex-direction:column;gap:.75rem;">
                @forelse($appointments->where('status', 'scheduled') as $apt)
                <div class="cd-action-btn" style="cursor: default;">
                    <div class="cd-action-icon">📅</div>
                    <div style="flex:1;">
                        <div>{{ $apt->student->name }}</div>
                        <div class="cd-action-desc">{{ $apt->scheduled_at->format('d/m/Y à H:i') }}</div>
                    </div>
                    <a href="{{ route('counselor.student.show', $apt->student) }}" class="btn-ghost" style="padding: 0.3rem 0.6rem; font-size: 0.7rem;">Dossier</a>
                </div>
                @empty
                <div style="text-align:center; padding: 2rem 0; color: var(--ink30);">
                    <p>Aucun rendez-vous planifié.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Reveal on scroll ── */
    const revEls = document.querySelectorAll('#cdRoot .rev');
    const revObs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); revObs.unobserve(e.target); } });
    }, { threshold: .06, rootMargin: '0px 0px -30px 0px' });
    revEls.forEach(el => revObs.observe(el));

    /* ── Colors ── */
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const gridCol  = isDark ? 'rgba(240,237,230,.05)' : 'rgba(11,12,16,.05)';
    const tickCol  = isDark ? 'rgba(240,237,230,.3)'  : 'rgba(11,12,16,.3)';

    /* ── Mini success sparkline ── */
    const ctx = document.getElementById('miniSuccessChart')?.getContext('2d');
    if (ctx && typeof Chart !== 'undefined') {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan','Fév','Mar','Avr','Mai','Jun'],
                datasets: [{
                    data: [65, 78, 72, 85, 88, 92],
                    borderColor: '#c8973a', borderWidth: 2,
                    tension: .4, pointRadius: 0,
                    fill: true, backgroundColor: 'rgba(200,151,58,.07)'
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { display: false }, y: { display: false, min: 0, max: 100 } }
            }
        });
    }

    /* ── Status distribution doughnut ── */
    const dCtx = document.getElementById('statusDistChart')?.getContext('2d');
    if (dCtx && typeof Chart !== 'undefined') {
        new Chart(dCtx, {
            type: 'doughnut',
            data: {
                labels: ['Certifié', 'En cours', 'En attente'],
                datasets: [{
                    data: [{{ $completedCount }}, {{ $ongoingCount }}, {{ $pendingCount }}],
                    backgroundColor: ['#4a7c59', '#1a4f6e', isDark ? 'rgba(240,237,230,.1)' : 'rgba(11,12,16,.08)'],
                    borderWidth: 0
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: tickCol,
                            font: { family: "'DM Sans', sans-serif", size: 11, weight: 600 },
                            padding: 14,
                            usePointStyle: true, pointStyleWidth: 8
                        }
                    }
                }
            }
        });
    }

    /* ── Cohort Analysis Chart ── */
    const cCtx = document.getElementById('cohortChart')?.getContext('2d');
    if (cCtx && typeof Chart !== 'undefined') {
        const cohortLabels = {!! json_encode(array_keys($cohortStats)) !!};
        const cohortData = {!! json_encode(array_values($cohortStats)) !!};
        
        new Chart(cCtx, {
            type: 'bar',
            data: {
                labels: cohortLabels,
                datasets: [{
                    label: 'Pourcentage d\'étudiants (%)',
                    data: cohortData,
                    backgroundColor: [
                        '#1a4f6e', '#4a7c59', '#c8973a', '#d4622a', isDark ? 'rgba(240,237,230,.2)' : 'rgba(11,12,16,.2)'
                    ],
                    borderWidth: 0,
                    borderRadius: 4
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: tickCol, font: { family: "'DM Sans'", size: 10, weight: 600 } }
                    },
                    y: {
                        beginAtZero: true, max: 100,
                        grid: { color: gridCol },
                        ticks: { color: tickCol, font: { family: "'DM Sans'", size: 11 }, callback: v => v + '%' }
                    }
                }
            }
        });
    }

    /* ── Bar animate on visible ── */
    const barObs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            const b = e.target, w = b.style.width;
            b.style.width = '0';
            setTimeout(() => { b.style.width = w; }, 100);
            barObs.unobserve(b);
        });
    }, { threshold: .3 });
    document.querySelectorAll('.match-fill').forEach(b => barObs.observe(b));

    /* ── Student search filter ── */
    const search = document.getElementById('studentSearch');
    const cards  = document.querySelectorAll('#studentGrid .cd-scard');
    search?.addEventListener('input', () => {
        const q = search.value.toLowerCase().trim();
        cards.forEach(c => {
            const name = c.dataset.name || '';
            c.style.display = (!q || name.includes(q)) ? '' : 'none';
        });
    });

});
</script>
@endsection