@extends('layouts.counselor')

@section('title', 'Tableau de Bord Exécutif')

@section('content')
<style>
/* ════════════════════════════════════════════
   COUNSELOR DASHBOARD — CapAvenir Executive System
   Inspired by Power BI, Salesforce, HubSpot & Notion Enterprise
   Fully aligned with cap-theme tokens
   ════════════════════════════════════════════ */
.cd {
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
    display: flex; flex-direction: column; gap: 2.5rem;
}

.cd *, .cd *::before, .cd *::after { box-sizing: border-box; margin: 0; padding: 0; }
.cd a { color: inherit; text-decoration: none; }

/* ── CLASSIC EXECUTIVE CARD ── */
.cd .glass-card {
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
.cd .glass-card:hover {
    transform: translateY(-2px);
    border-color: var(--glass-border-vivid);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
}
[data-theme="dark"] .cd .glass-card {
    background: rgba(18, 24, 36, 0.65);
    box-shadow: 0 16px 48px rgba(0, 0, 0, 0.4);
}
[data-theme="dark"] .cd .glass-card:hover {
    border-color: rgba(255, 106, 0, 0.35);
    box-shadow: 0 24px 64px rgba(0, 0, 0, 0.5);
}

/* ── TABS FOR EXECUTIVE VIEW ── */
.cd-tabs-nav {
    display: flex;
    gap: .75rem;
    border-bottom: 1px solid var(--ink10);
    padding-bottom: .75rem;
    margin-bottom: 1rem;
    overflow-x: auto;
}
.cd-tab-btn {
    padding: .6rem 1.25rem;
    font-family: var(--font-main);
    font-size: .85rem;
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
    gap: .5rem;
}
.cd-tab-btn:hover {
    color: var(--ink);
    background: var(--ink06);
}
.cd-tab-btn.active {
    color: var(--accent2);
    background: color-mix(in srgb, var(--accent2) 8%, transparent);
    border-color: color-mix(in srgb, var(--accent2) 20%, transparent);
}
.cd-tab-pane {
    display: none;
    animation: tabFadeIn 0.5s var(--ease) forwards;
}
.cd-tab-pane.active {
    display: block;
}
@keyframes tabFadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: none; }
}

/* ── KPI GRID ── */
.cd-kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.25rem;
}
.cd-kpi {
    padding: 1.5rem;
}
.cd-kpi-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: .75rem;
}
.cd-kpi-label {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--ink30);
}
.cd-kpi-icon {
    font-size: 1.25rem;
    color: var(--accent2);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: color-mix(in srgb, var(--accent2) 8%, transparent);
    display: flex;
    align-items: center;
    justify-content: center;
}
.cd-kpi-val {
    font-family: var(--font-serif);
    font-size: 2.5rem;
    font-weight: 600;
    line-height: 1;
    letter-spacing: -.03em;
    color: var(--ink);
}
.cd-kpi-val sup {
    font-size: 1.15rem;
    color: var(--ink30);
    font-weight: 400;
}
.cd-kpi-sub {
    font-size: .76rem;
    color: var(--ink60);
    margin-top: .4rem;
    display: flex;
    align-items: center;
    gap: .35rem;
}
.cd-kpi-sub-trend {
    font-weight: 700;
}
.cd-kpi-sub-trend.up { color: var(--accent3); }
.cd-kpi-sub-trend.down { color: #ef4444; }

/* ── PRIORITIZATION PANEL ── */
.cd-priorities {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
}
.cd-list-title {
    font-family: var(--font-serif);
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1.25rem;
    color: var(--ink);
}
.cd-alert-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: var(--ink06);
    border: 1px solid var(--ink10);
    border-radius: var(--r);
    margin-bottom: .85rem;
    transition: var(--transition);
}
.cd-alert-item:hover {
    border-color: var(--accent);
    background: color-mix(in srgb, var(--accent) 4%, var(--cream));
}
.cd-alert-badge {
    padding: .25rem .6rem;
    border-radius: var(--rx);
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
}
.cd-alert-badge.risk { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
.cd-alert-badge.recommendation { background: rgba(0, 87, 184, 0.1); color: var(--accent2); border: 1px solid rgba(0, 87, 184, 0.2); }
.cd-alert-badge.priority { background: rgba(255, 106, 0, 0.1); color: var(--accent); border: 1px solid rgba(255, 106, 0, 0.2); }

.cd-alert-content { flex: 1; }
.cd-alert-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: .25rem; }
.cd-alert-student { font-weight: 700; font-size: .88rem; color: var(--ink); }
.cd-alert-desc { font-size: .8rem; color: var(--ink60); line-height: 1.5; margin-bottom: .5rem; }
.cd-alert-action {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    font-size: .75rem;
    font-weight: 700;
    color: var(--accent2);
    cursor: pointer;
}
.cd-alert-action:hover { text-decoration: underline; }

/* ── STUDENT DIRECTORY ── */
.cd-search-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.cd-search-input-wrap {
    position: relative;
    flex: 1;
    max-width: 400px;
}
.cd-search-input {
    width: 100%;
    padding: .65rem 1rem .65rem 2.5rem;
    background: var(--paper);
    border: 1px solid var(--ink10);
    border-radius: var(--r);
    font-family: var(--font-main);
    font-size: .85rem;
    color: var(--ink);
    outline: none;
    transition: var(--transition);
}
.cd-search-input:focus {
    border-color: var(--accent2);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent2) 15%, transparent);
}
.cd-search-input-wrap svg {
    position: absolute;
    left: .85rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--ink30);
}
.cd-grid-directory {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.25rem;
}
.cd-stud-card {
    border-radius: var(--rl);
    background: var(--cream);
    border: 1px solid var(--ink10);
    transition: all 0.4s var(--ease);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.cd-stud-card:hover {
    transform: translateY(-4px);
    border-color: var(--accent2);
    box-shadow: var(--shadow-card);
}
.cd-stud-card-header {
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: .75rem;
    border-bottom: 1px solid var(--ink06);
}
.cd-stud-avatar {
    width: 42px;
    height: 42px;
    border-radius: var(--r);
    background: linear-gradient(135deg, var(--accent2), var(--accent));
    color: #fff;
    font-family: var(--font-serif);
    font-size: 1.2rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cd-stud-info { flex: 1; }
.cd-stud-name { font-weight: 700; font-size: .95rem; color: var(--ink); }
.cd-stud-email { font-size: .75rem; color: var(--ink30); }
.cd-stud-card-body {
    padding: 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.cd-stud-score-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.cd-stud-score-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; color: var(--ink30); }
.cd-stud-score-val { font-family: var(--font-serif); font-size: 1rem; font-weight: 600; color: var(--accent2); }
.cd-stud-score-bar {
    height: 5px;
    background: var(--ink10);
    border-radius: var(--rx);
    overflow: hidden;
}
.cd-stud-score-bar-fill {
    height: 100%;
    border-radius: var(--rx);
    background: var(--accent2);
}
.cd-stud-meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .75rem;
}
.cd-stud-meta-item {
    font-size: .75rem;
    color: var(--ink60);
    display: flex;
    align-items: center;
    gap: .4rem;
}
.cd-stud-card-footer {
    padding: .85rem 1.25rem;
    background: var(--ink06);
    border-top: 1px solid var(--ink10);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.cd-stud-status {
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
    padding: .2rem .5rem;
    border-radius: var(--rx);
}
.cd-stud-status.completed { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
.cd-stud-status.ongoing { background: rgba(0, 87, 184, 0.1); color: var(--accent2); border: 1px solid rgba(0, 87, 184, 0.2); }
.cd-stud-status.pending { background: var(--ink10); color: var(--ink30); }

.cd-stud-link {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    font-size: .78rem;
    font-weight: 700;
    color: var(--accent2);
}
.cd-stud-link:hover { text-decoration: underline; }

/* ── COHORTE & STATS ── */
.cd-stats-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 1.5rem;
}
.cd-chart-container {
    height: 240px;
    position: relative;
}

/* ── BENCHMARK AXE 9 ── */
.cd-bench-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}
.cd-table-wrap {
    overflow-x: auto;
}
.cd-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
    font-size: .82rem;
}
.cd-table th {
    padding: .75rem .5rem;
    border-bottom: 2px solid var(--ink10);
    color: var(--ink30);
    font-weight: 700;
    text-transform: uppercase;
    font-size: .68rem;
}
.cd-table td {
    padding: .85rem .5rem;
    border-bottom: 1px solid var(--ink06);
    color: var(--ink60);
    vertical-align: middle;
}
.cd-table tr:hover td {
    color: var(--ink);
    background: var(--ink06);
}
.cd-bench-trend {
    font-weight: 700;
    color: var(--accent3);
}

/* ── REPORTING AXE 7 ── */
.cd-report-box {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 2rem;
}
.cd-report-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}
.cd-report-card {
    padding: 1.25rem;
    border-radius: var(--r);
    background: var(--ink06);
    border: 1px solid var(--ink10);
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.cd-report-card:hover {
    border-color: var(--accent2);
    background: color-mix(in srgb, var(--accent2) 4%, var(--cream));
}
.cd-report-card.selected {
    border-color: var(--accent2);
    background: color-mix(in srgb, var(--accent2) 8%, var(--cream));
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent2) 15%, transparent);
}
.cd-report-card-title { font-weight: 700; font-size: .88rem; color: var(--ink); margin-bottom: .25rem; }
.cd-report-card-desc { font-size: .75rem; color: var(--ink30); line-height: 1.4; }

.cd-btn-export {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    padding: .8rem 1.5rem;
    border-radius: var(--r);
    font-family: var(--font-main);
    font-weight: 600;
    font-size: .85rem;
    cursor: pointer;
    transition: var(--transition);
}
.cd-btn-export-primary {
    background: var(--accent2);
    color: #fff;
    border: none;
    box-shadow: 0 4px 16px color-mix(in srgb, var(--accent2) 30%, transparent);
}
.cd-btn-export-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 24px color-mix(in srgb, var(--accent2) 45%, transparent);
}
.cd-btn-export-secondary {
    background: var(--ink06);
    color: var(--ink);
    border: 1px solid var(--ink10);
}
.cd-btn-export-secondary:hover {
    background: var(--ink10);
}

/* Loader simulation */
.export-loader {
    display: none;
    align-items: center;
    gap: .5rem;
    font-size: .8rem;
    color: var(--accent2);
    font-weight: 600;
}
.spinner {
    width: 16px;
    height: 16px;
    border: 2px solid var(--ink10);
    border-top-color: var(--accent2);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── STAG HEADINGS ── */
.cd-stag {
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
.cd-stag::before {
    content: '';
    width: 12px;
    height: 1px;
    background: var(--accent2);
}
.cd-sec-title {
    font-family: var(--font-serif);
    font-size: 1.5rem;
    font-weight: 300;
    letter-spacing: -.03em;
    margin-bottom: 1.5rem;
    color: var(--ink);
}
.cd-sec-title em {
    font-style: italic;
    color: var(--accent2);
}

/* Reveal elements */
.cd .rev { opacity: 0; transform: translateY(15px); transition: opacity .5s var(--ease), transform .5s var(--ease); }
.cd .rev.vis { opacity: 1; transform: none; }

@media (max-width: 1100px) {
    .cd-priorities { grid-template-columns: 1fr; }
    .cd-stats-grid { grid-template-columns: 1fr; }
    .cd-bench-grid { grid-template-columns: 1fr; }
    .cd-report-box { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .cd-kpi-grid { grid-template-columns: 1fr; }
    .cd-report-options { grid-template-columns: 1fr; }
}
</style>

<div class="cd" id="cdRoot">

    {{-- ═══ TABS NAV ═══ --}}
    <div class="cd-tabs-nav rev">
        <button class="cd-tab-btn active" data-tab="general">
            💼 Centre Stratégique
        </button>
        <button class="cd-tab-btn" data-tab="directory">
            👥 Répertoire Étudiants
        </button>
        <button class="cd-tab-btn" data-tab="benchmark">
            🌍 Benchmark International (Axe 9)
        </button>
        <button class="cd-tab-btn" data-tab="reporting">
            📈 Rapports Institutionnels (Axe 7)
        </button>
    </div>

    {{-- ════════════════════════════════════════════
       TAB 1 : CENTRE STRATÉGIQUE
       ════════════════════════════════════════════ --}}
    <div class="cd-tab-pane active" id="tab-general">
        
        {{-- KPI GRID --}}
        <div class="cd-kpi-grid rev" style="margin-bottom: 2rem;">
            {{-- KPI 1: Total Students --}}
            <div class="glass-card cd-kpi">
                <div class="cd-kpi-head">
                    <span class="cd-kpi-label">Étudiants Suivis</span>
                    <div class="cd-kpi-icon">👥</div>
                </div>
                <div class="cd-kpi-val">{{ $students->count() }}</div>
                <p class="cd-kpi-sub">
                    <span class="cd-kpi-sub-trend up">↑ +14%</span> vs. mois dernier
                </p>
            </div>

            {{-- KPI 2: Certified Profiles --}}
            @php
                $completedCount = $students->filter(fn($s) => ($s->profile->status ?? 'pending') === 'completed')->count();
                $ongoingCount   = $students->filter(fn($s) => ($s->profile->status ?? 'pending') === 'ongoing')->count();
                $pendingCount   = $students->count() - $completedCount - $ongoingCount;
            @endphp
            <div class="glass-card cd-kpi">
                <div class="cd-kpi-head">
                    <span class="cd-kpi-label">Dossiers Clôturés</span>
                    <div class="cd-kpi-icon" style="color:var(--accent3); background:rgba(16,185,129,0.1)">✓</div>
                </div>
                <div class="cd-kpi-val">{{ $completedCount }}</div>
                <p class="cd-kpi-sub">
                    <span class="cd-kpi-sub-trend up">↑ {{ $students->count() > 0 ? round(($completedCount/$students->count())*100) : 0 }}%</span> de taux de complétion
                </p>
            </div>

            {{-- KPI 3: Satisfaction / Success Rate --}}
            <div class="glass-card cd-kpi">
                <div class="cd-kpi-head">
                    <span class="cd-kpi-label">Taux de Succès</span>
                    <div class="cd-kpi-icon" style="color:var(--gold); background:rgba(255,140,26,0.1)">📈</div>
                </div>
                <div class="cd-kpi-val">{{ $kpis['success_rate'] }}<sup>%</sup></div>
                <p class="cd-kpi-sub">
                    Satisfaction conseiller active <strong style="color:var(--gold)">{{ $kpis['counselor_satisfaction'] }}/5</strong>
                </p>
            </div>

            {{-- KPI 4: Pending Actions --}}
            <div class="glass-card cd-kpi">
                <div class="cd-kpi-head">
                    <span class="cd-kpi-label">Cas Prioritaires</span>
                    <div class="cd-kpi-icon" style="color:#ef4444; background:rgba(239,68,68,0.1)">🚨</div>
                </div>
                <div class="cd-kpi-val" style="color:#ef4444;">{{ $students->filter(fn($s) => ($s->profile->ai_score ?? rand(55,95)) < 65)->count() }}</div>
                <p class="cd-kpi-sub">
                    Nécessitant une alerte d'orientation
                </p>
            </div>
        </div>

        {{-- PRIORITIES & REAL-TIME ALERTS --}}
        <div class="cd-priorities rev" style="margin-bottom: 2rem;">
            
            {{-- Explainable AI Alerts --}}
            <div class="glass-card">
                <p class="cd-stag">Algorithme Prédictif</p>
                <h3 class="cd-sec-title">Alertes d'Orientation <em>Explainable IA</em></h3>

                <div style="display:flex; flex-direction:column; gap:.25rem;">
                    @foreach($iaInsights as $insight)
                        <div class="cd-alert-item">
                            <span class="cd-alert-badge {{ $insight['type'] }}">{{ $insight['type'] }}</span>
                            <div class="cd-alert-content">
                                <div class="cd-alert-header">
                                    <span class="cd-alert-student">{{ $insight['student'] }} · <span style="font-weight: 500; font-size:.78rem; color:var(--ink30);">{{ $insight['title'] }}</span></span>
                                </div>
                                <p class="cd-alert-desc">"{{ $insight['explanation'] }}"</p>
                                <div class="cd-alert-action">
                                    <span>➔ Action recommandée : <strong>{{ $insight['action'] }}</strong></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Daily planning agenda --}}
            <div class="glass-card">
                <p class="cd-stag">Meeting Suite</p>
                <h3 class="cd-sec-title">Prochains <em>Rendez-vous</em></h3>

                <div style="display:flex; flex-direction:column; gap:.75rem;">
                    @forelse($appointments->where('status', 'scheduled') as $apt)
                        <div style="padding:.85rem; border-radius:var(--r); background:var(--ink06); border:1px solid var(--ink10); display:flex; align-items:center; justify-content:space-between;">
                            <div>
                                <div style="font-weight:700; font-size:.85rem;">{{ $apt->student->name }}</div>
                                <div style="font-size:.72rem; color:var(--ink30); margin-top:.15rem;">{{ $apt->scheduled_at->format('d/m/Y à H:i') }} (Hybride)</div>
                            </div>
                            <a href="{{ route('counselor.student.show', $apt->student) }}" class="cd-stud-link" style="font-size:.72rem;">Profil →</a>
                        </div>
                    @empty
                        <div style="text-align:center; padding:2rem 0; color:var(--ink30); font-style:italic; font-size:.82rem;">
                            Aucun entretien planifié aujourd'hui.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- COHORTE ANALYSIS & HEATMAPS --}}
        <div class="cd-stats-grid rev">
            {{-- Distribution chart card --}}
            <div class="glass-card">
                <p class="cd-stag">Performance</p>
                <h3 class="cd-sec-title">Suivi des <em>Trajectoires</em></h3>
                <div class="cd-chart-container">
                    <canvas id="doughnutDossiers"></canvas>
                </div>
            </div>

            {{-- Bar chart card --}}
            <div class="glass-card">
                <p class="cd-stag">Flux Institutionnel</p>
                <h3 class="cd-sec-title">Tendances de Choix <em>de Cohorte</em></h3>
                <div class="cd-chart-container">
                    <canvas id="barCohortTrends"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- ════════════════════════════════════════════
       TAB 2 : DIRECTORY (RÉPERTOIRE ÉTUDIANTS)
       ════════════════════════════════════════════ --}}
    <div class="cd-tab-pane" id="tab-directory">
        <div class="glass-card rev">
            <div class="cd-search-bar">
                <div>
                    <p class="cd-stag">Recherche Intelligente</p>
                    <h3 class="cd-sec-title" style="margin-bottom: 0;">Portefeuille <em>Conseiller</em></h3>
                </div>
                <div class="cd-search-input-wrap">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin='round' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'/></svg>
                    <input type="text" class="cd-search-input" id="searchStudentGrid" placeholder="Filtrer par nom ou email...">
                </div>
            </div>

            <div class="cd-grid-directory" id="directoryGrid">
                @forelse($students as $student)
                    @php
                        $score = $student->profile->ai_score ?? rand(62,96);
                        $status = $student->profile->status ?? 'pending';
                        $aiRisk = $score < 65;
                    @endphp
                    <div class="cd-stud-card" data-search-name="{{ strtolower($student->name) }}" data-search-email="{{ strtolower($student->email) }}">
                        <div class="cd-stud-card-header">
                            <div class="cd-stud-avatar">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                            <div class="cd-stud-info">
                                <div class="cd-stud-name">{{ $student->name }}</div>
                                <div class="cd-stud-email">{{ $student->email }}</div>
                            </div>
                        </div>
                        <div class="cd-stud-card-body">
                            <div>
                                <div class="cd-stud-score-row">
                                    <span class="cd-stud-score-label">Adéquation IA</span>
                                    <span class="cd-stud-score-val" style="color:{{ $aiRisk ? '#ef4444' : 'var(--accent2)' }};">{{ $score }}%</span>
                                </div>
                                <div class="cd-stud-score-bar" style="margin-top: .4rem;">
                                    <div class="cd-stud-score-bar-fill animate-bar" style="width: {{ $score }}%; background:{{ $aiRisk ? '#ef4444' : 'var(--accent2)' }}"></div>
                                </div>
                            </div>

                            <div class="cd-stud-meta-grid">
                                <div class="cd-stud-meta-item">
                                    <span>🗓</span> Inscrit le {{ $student->created_at->format('d/m/Y') }}
                                </div>
                                <div class="cd-stud-meta-item">
                                    <span>💡</span> {{ $student->careerRoadmaps->count() }} Pistes
                                </div>
                            </div>
                        </div>
                        <div class="cd-stud-card-footer">
                            <span class="cd-stud-status {{ $status }}">{{ $status === 'completed' ? 'Certifié' : ($status === 'ongoing' ? 'Suivi actif' : 'En attente') }}</span>
                            @if($aiRisk)
                                <span style="font-size: .65rem; font-weight:700; color:#ef4444; display:flex; align-items:center; gap:.2rem;">⚠️ Alerte Risque</span>
                            @endif
                            <a href="{{ route('counselor.student.show', $student) }}" class="cd-stud-link">Ouvrir le CRM →</a>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1/-1; text-align:center; padding:4rem; color:var(--ink30);">
                        Aucun étudiant dans votre portefeuille.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════
       TAB 3 : BENCHMARK INTERNATIONAL (AXE 9)
       ════════════════════════════════════════════ --}}
    <div class="cd-tab-pane" id="tab-benchmark">
        <div class="cd-bench-grid rev">
            
            {{-- Establishments performance --}}
            <div class="glass-card">
                <p class="cd-stag">Palmarès National</p>
                <h3 class="cd-sec-title">Comparaison des <em>Établissements</em></h3>
                <div class="cd-table-wrap">
                    <table class="cd-table">
                        <thead>
                            <tr>
                                <th>Lycée / Université</th>
                                <th>Candidats</th>
                                <th>Adéquation moyenne</th>
                                <th>Secteur d'excellence</th>
                                <th>Conformité</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($benchmarkEstablishments as $est)
                            <tr>
                                <td style="font-weight: 700; color:var(--ink);">{{ $est['name'] }}</td>
                                <td>{{ $est['count'] }}</td>
                                <td style="font-weight: 700; color:var(--accent2);">{{ $est['score'] }}%</td>
                                <td><span style="font-size: .72rem; padding: .2rem .5rem; border-radius: 4px; background:var(--ink06);">{{ $est['major'] }}</span></td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:.4rem;">
                                        <span class="cd-bench-trend">{{ $est['conformity'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Regional comparison --}}
            <div class="glass-card">
                <p class="cd-stag">Flux & Démographie</p>
                <h3 class="cd-sec-title">Cartographie des <em>Régions</em></h3>
                <div class="cd-table-wrap">
                    <table class="cd-table">
                        <thead>
                            <tr>
                                <th>Gouvernorat</th>
                                <th>Étudiants inscrits</th>
                                <th>Adéquation moyenne</th>
                                <th>Filière dominante</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($benchmarkRegions as $reg)
                            <tr>
                                <td style="font-weight: 700; color:var(--ink); display:flex; align-items:center; gap:.5rem;">
                                    <span style="width: 8px; height:8px; border-radius:50%; background:{{ $reg['color'] }}"></span>
                                    {{ $reg['name'] }}
                                </td>
                                <td>{{ $reg['count'] }}</td>
                                <td style="font-weight: 700; color:{{ $reg['color'] }}">{{ $reg['adequacy'] }}%</td>
                                <td>{{ $reg['major'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Global streams growth --}}
            <div class="glass-card">
                <p class="cd-stag">Veille Mondiale</p>
                <h3 class="cd-sec-title">Filières Mondiales en <em>Forte Croissance</em></h3>
                <div class="cd-table-wrap">
                    <table class="cd-table">
                        <thead>
                            <tr>
                                <th>Discipline Technologique</th>
                                <th>Taux de croissance</th>
                                <th>Niveau de demande mondiale</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($benchmarkGlobalStreams as $stream)
                            <tr>
                                <td style="font-weight: 700; color:var(--ink);">{{ $stream['name'] }}</td>
                                <td style="font-weight: 700; color:var(--accent3);">{{ $stream['growth'] }} / an</td>
                                <td>
                                    <span style="font-size: .68rem; font-weight:700; padding:.2rem .6rem; border-radius:var(--rx); background:rgba(16,185,129,0.1); color:#10b981; border:1px solid rgba(16,185,129,0.2)">
                                        {{ $stream['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Opportunities panel --}}
            <div class="glass-card">
                <p class="cd-stag">Bourses & Mobilités</p>
                <h3 class="cd-sec-title">Opportunités d'Excellence <em>Internationales</em></h3>
                <div style="display:flex; flex-direction:column; gap:.75rem;">
                    @foreach($benchmarkOpportunities as $opp)
                        <div style="padding:1rem; border-radius:var(--r); background:var(--ink06); border:1px solid var(--ink10); display:flex; align-items:flex-start; justify-content:space-between; gap:1rem;">
                            <div>
                                <div style="font-weight:700; font-size:.85rem; color:var(--ink);">{{ $opp['name'] }}</div>
                                <div style="font-size:.74rem; color:var(--ink60); margin-top:.2rem;">Secteur : {{ $opp['type'] }} · Limite : <span style="font-weight:600;">{{ $opp['deadline'] }}</span></div>
                            </div>
                            <span style="font-size:.65rem; font-weight:700; text-transform:uppercase; padding:.2rem .5rem; border-radius:var(--rx); 
                                background:{{ $opp['level'] === 'Critique' ? 'rgba(239,68,68,0.1)' : 'rgba(255,140,26,0.1)' }};
                                color:{{ $opp['level'] === 'Critique' ? '#ef4444' : 'var(--accent)' }};">
                                {{ $opp['level'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    {{-- ════════════════════════════════════════════
       TAB 4 : REPORTING INSTITUTIONNEL (AXE 7)
       ════════════════════════════════════════════ --}}
    <div class="cd-tab-pane" id="tab-reporting">
        <div class="glass-card rev">
            <p class="cd-stag">Reporting Exécutif v5.0</p>
            <h3 class="cd-sec-title">Générateur de Rapports <em>Premium</em></h3>

            <div class="cd-report-box">
                <div>
                    <div style="margin-bottom: 1.5rem;">
                        <span style="font-size: .7rem; font-weight:700; text-transform:uppercase; color:var(--ink30);">Étape 1 : Choisir le type de bilan institutionnel</span>
                    </div>

                    <div class="cd-report-options">
                        <div class="cd-report-card selected" data-report="pdf-premium">
                            <div>
                                <span style="font-size: 1.5rem;">💎</span>
                                <div class="cd-report-card-title" style="margin-top: .5rem;">Bilan Cohorte PDF Premium</div>
                                <div class="cd-report-card-desc">Rapport d'excellence consolidant les trajectoires de réussite, les risques d'incompatibilité et les recommandations de réorientation IA.</div>
                            </div>
                        </div>

                        <div class="cd-report-card" data-report="regional">
                            <div>
                                <span style="font-size: 1.5rem;">🗺️</span>
                                <div class="cd-report-card-title" style="margin-top: .5rem;">Rapports Régionaux</div>
                                <div class="cd-report-card-desc">Analyse démographique des flux d'orientation post-bac et adéquation universitaire par gouvernorat (Tunisie).</div>
                            </div>
                        </div>

                        <div class="cd-report-card" data-report="counselor">
                            <div>
                                <span style="font-size: 1.5rem;">🏆</span>
                                <div class="cd-report-card-title" style="margin-top: .5rem;">Performance Conseiller</div>
                                <div class="cd-report-card-desc">Indicateurs de satisfaction, temps moyen d'accompagnement par fiche CRM et taux d'efficacité des interventions.</div>
                            </div>
                        </div>

                        <div class="cd-report-card" data-report="audit">
                            <div>
                                <span style="font-size: 1.5rem;">🔒</span>
                                <div class="cd-report-card-title" style="margin-top: .5rem;">Registre d'Audit Qualité</div>
                                <div class="cd-report-card-desc">Registre d'homologation des modifications de trajectoires, conformité avec le RGPD et historique des décisions.</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Export config panel --}}
                <div style="background:var(--ink06); border:1px solid var(--ink10); padding: 1.75rem; border-radius:var(--r); display:flex; flex-direction:column; justify-content:space-between;">
                    <div>
                        <div style="margin-bottom: 1.5rem;">
                            <span style="font-size: .7rem; font-weight:700; text-transform:uppercase; color:var(--ink30);">Étape 2 : Configuration du format</span>
                        </div>

                        <div style="display:flex; flex-direction:column; gap:1.25rem; margin-bottom: 2rem;">
                            <div>
                                <label style="display:block; font-size:.78rem; font-weight:700; color:var(--ink60); margin-bottom:.5rem;">Format d'exportation cible</label>
                                <select id="exportFormat" style="width:100%; padding:.7rem; border-radius:var(--r); border:1px solid var(--ink10); background:var(--paper); color:var(--ink); font-family:var(--font-main); font-weight:600; cursor:pointer;">
                                    <option value="pdf">📄 Fichier PDF Haute Résolution (.pdf)</option>
                                    <option value="excel">📊 Tableur Microsoft Excel (.xlsx)</option>
                                    <option value="dashboard">💻 Dashboard Interactif Exportable (.json)</option>
                                </select>
                            </div>

                            <div style="display:flex; align-items:center; gap:.5rem;">
                                <input type="checkbox" id="includeAI" checked style="width:16px; height:16px; accent-color:var(--accent2); cursor:pointer;">
                                <label for="includeAI" style="font-size:.8rem; color:var(--ink60); font-weight:500; cursor:pointer;">Inclure les prédictions du Success Forecast Engine</label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div style="display:flex; gap:1rem; align-items:center;">
                            <button class="cd-btn-export cd-btn-export-primary" id="btnExportSubmit" style="flex:1;">
                                📥 Exporter le Rapport
                            </button>
                            <div class="export-loader" id="exportLoader">
                                <span class="spinner"></span> Génération...
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Tab Switching ── */
    const tabBtns = document.querySelectorAll('.cd-tab-btn');
    const tabPanes = document.querySelectorAll('.cd-tab-pane');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.tab;

            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanes.forEach(p => p.classList.remove('active'));

            btn.classList.add('active');
            const pane = document.getElementById('tab-' + target);
            if (pane) pane.classList.add('active');
        });
    });

    /* ── Search grid filter ── */
    const searchGrid = document.getElementById('searchStudentGrid');
    const gridCards = document.querySelectorAll('#directoryGrid .cd-stud-card');

    searchGrid?.addEventListener('input', () => {
        const q = searchGrid.value.toLowerCase().trim();
        gridCards.forEach(card => {
            const name = card.dataset.searchName || '';
            const email = card.dataset.searchEmail || '';
            if (!q || name.includes(q) || email.includes(q)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });

    /* ── Report Card Selection ── */
    const reportCards = document.querySelectorAll('.cd-report-card');
    reportCards.forEach(card => {
        card.addEventListener('click', () => {
            reportCards.forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
        });
    });

    /* ── Export Simulation ── */
    const btnExport = document.getElementById('btnExportSubmit');
    const loader = document.getElementById('exportLoader');

    btnExport?.addEventListener('click', () => {
        btnExport.style.display = 'none';
        loader.style.display = 'flex';

        setTimeout(() => {
            loader.style.display = 'none';
            btnExport.style.display = 'inline-flex';
            
            const format = document.getElementById('exportFormat').value;
            const report = document.querySelector('.cd-report-card.selected').dataset.report;
            alert(`Succès : Votre rapport "${report}" au format [${format.toUpperCase()}] a été généré avec succès dans vos téléchargements !`);
        }, 1800);
    });

    /* ── Reveal on scroll ── */
    const revEls = document.querySelectorAll('#cdRoot .rev');
    const revObs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); revObs.unobserve(e.target); } });
    }, { threshold: .06, rootMargin: '0px 0px -30px 0px' });
    revEls.forEach(el => revObs.observe(el));

    /* ── Colors based on theme ── */
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const tickCol  = isDark ? 'rgba(240,237,230,.4)'  : 'rgba(10,25,47,.4)';

    /* ── Doughnut Dossiers Status Chart ── */
    const dCtx = document.getElementById('doughnutDossiers')?.getContext('2d');
    if (dCtx && typeof Chart !== 'undefined') {
        new Chart(dCtx, {
            type: 'doughnut',
            data: {
                labels: ['Certifiés (Clôturés)', 'Suivi actif', 'En attente'],
                datasets: [{
                    data: [{{ $completedCount }}, {{ $ongoingCount }}, {{ $pendingCount }}],
                    backgroundColor: ['#10b981', '#0057B8', isDark ? 'rgba(240,237,230,.15)' : 'rgba(10,25,47,.12)'],
                    borderWidth: 0
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '72%',
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

    /* ── Cohort Trends Bar Chart ── */
    const cCtx = document.getElementById('barCohortTrends')?.getContext('2d');
    if (cCtx && typeof Chart !== 'undefined') {
        const cohortLabels = {!! json_encode(array_keys($cohortStats)) !!};
        const cohortData = {!! json_encode(array_values($cohortStats)) !!};
        
        new Chart(cCtx, {
            type: 'bar',
            data: {
                labels: cohortLabels,
                datasets: [{
                    label: 'Pourcentage (%)',
                    data: cohortData,
                    backgroundColor: ['#0057B8', '#10b981', '#FF8C1A', '#FF6A00', '#4a7c59', 'rgba(10,25,47,.25)'],
                    borderWidth: 0,
                    borderRadius: 4
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: tickCol, font: { family: "'DM Sans'", size: 10, weight: 600 } }
                    },
                    y: {
                        beginAtZero: true, max: 100,
                        grid: { color: isDark ? 'rgba(240,237,230,.06)' : 'rgba(10,25,47,.06)' },
                        ticks: { color: tickCol, font: { family: "'DM Sans'", size: 10 }, callback: v => v + '%' }
                    }
                }
            }
        });
    }

});
</script>
@endsection