@extends('layouts.admin')

@section('title', 'Vue Générale')

@section('content')
<style>
/* ════════════════════════════════════════════
   ADMIN DASHBOARD — CapAvenir 2026
   Editorial · Data-dense · Precision-crafted
════════════════════════════════════════════ */
.ad {
    --ink:     #0b0c10;
    --paper:   #f7f5f0;
    --cream:   #ede9e1;
    --warm:    #e8e1d4;
    --accent:  #d4622a;
    --accent2: #1a4f6e;
    --accent3: #4a7c59;
    --gold:    #c8973a;
    --red:     #b83232;
    --ink60:   rgba(11,12,16,.6);
    --ink30:   rgba(11,12,16,.3);
    --ink15:   rgba(11,12,16,.15);
    --ink10:   rgba(11,12,16,.1);
    --ink06:   rgba(11,12,16,.06);
    --ink03:   rgba(11,12,16,.03);
    --r:   6px;
    --rl:  16px;
    --rx:  999px;
    --ease: cubic-bezier(.16,1,.3,1);
    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    display: flex; flex-direction: column; gap: 0;
}

[data-theme="dark"] .ad {
    --ink:#f0ede6;--paper:#10100d;--cream:#18170f;--warm:#1f1e14;
    --ink60:rgba(240,237,230,.6);--ink30:rgba(240,237,230,.3);
    --ink15:rgba(240,237,230,.15);--ink10:rgba(240,237,230,.08);
    --ink06:rgba(240,237,230,.04);--ink03:rgba(240,237,230,.02);
}
[data-theme="light"] .ad {
    --ink:#0b0c10;--paper:#f7f5f0;--cream:#ede9e1;--warm:#e8e1d4;
    --ink60:rgba(11,12,16,.6);--ink30:rgba(11,12,16,.3);
    --ink15:rgba(11,12,16,.15);--ink10:rgba(11,12,16,.1);
    --ink06:rgba(11,12,16,.06);--ink03:rgba(11,12,16,.03);
}

.ad *, .ad *::before, .ad *::after { box-sizing: border-box; margin: 0; padding: 0; }
.ad a { color: inherit; text-decoration: none; }

/* ── REVEAL ── */
.ad .rev { opacity: 0; transform: translateY(22px); transition: opacity .7s var(--ease), transform .7s var(--ease); }
.ad .rev.vis { opacity: 1; transform: none; }
.ad .rev-d1 { transition-delay: .08s; }
.ad .rev-d2 { transition-delay: .16s; }
.ad .rev-d3 { transition-delay: .24s; }
.ad .rev-d4 { transition-delay: .32s; }

/* ── SECTION LABEL ── */
.ad-eyebrow {
    font-size: .65rem; font-weight: 700; letter-spacing: .14em;
    text-transform: uppercase; display: flex; align-items: center; gap: .5rem;
    margin-bottom: .55rem;
}
.ad-eyebrow::before { content: ''; width: 16px; height: 1px; background: currentColor; }

/* ── HEADING ── */
.ad-sh {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.3rem, 2.2vw, 1.75rem);
    font-weight: 300; letter-spacing: -.04em; line-height: 1.1;
}
.ad-sh em { font-style: italic; }

/* ══════════════════════════════
   § 1 — KPI BAND
   Full-width borderless grid,
   separated by 1px vertical rules
══════════════════════════════ */
.ad-kpi-band {
    display: grid; grid-template-columns: repeat(4, 1fr);
    border: 1px solid var(--ink10);
    border-radius: var(--rl);
    overflow: hidden;
    background: var(--ink10);
    gap: 1px;
    margin-bottom: 1.5rem;
}
.ad-kpi-cell {
    background: var(--cream);
    padding: 2rem 1.75rem;
    display: flex; flex-direction: column;
    position: relative; overflow: hidden;
    transition: background .25s var(--ease);
}
.ad-kpi-cell:hover { background: var(--warm); }

/* Corner accent line */
.ad-kpi-cell::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 2px;
}
.ad-kpi-cell:nth-child(1)::before { background: var(--accent3); }
.ad-kpi-cell:nth-child(2)::before { background: var(--accent2); }
.ad-kpi-cell:nth-child(3)::before { background: var(--gold); }
.ad-kpi-cell:nth-child(4)::before { background: var(--red); }

.ad-kpi-icon {
    width: 38px; height: 38px; border-radius: var(--r);
    background: var(--ink06); border: 1px solid var(--ink10);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.05rem; margin-bottom: 1.25rem;
}
.ad-kpi-number {
    font-family: 'Fraunces', serif;
    font-size: 3.2rem; font-weight: 600;
    letter-spacing: -.07em; line-height: 1;
    color: var(--ink);
}
.ad-kpi-label {
    font-size: .65rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .11em; color: var(--ink30); margin-top: .5rem;
}
.ad-kpi-badge {
    margin-top: 1rem;
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .22rem .65rem; border-radius: var(--rx);
    font-size: .62rem; font-weight: 700; letter-spacing: .04em;
    align-self: flex-start;
}

/* ══════════════════════════════
   § 2 — ANALYTICS ROW
   Wide chart | Ecosystem bars
══════════════════════════════ */
.ad-row2 {
    display: grid; grid-template-columns: 1fr 340px;
    gap: 1px; background: var(--ink10);
    border: 1px solid var(--ink10); border-radius: var(--rl);
    overflow: hidden; margin-bottom: 1.5rem;
}
.ad-panel {
    background: var(--cream); padding: 2rem 2rem 1.75rem;
    display: flex; flex-direction: column;
}
.ad-panel-header { margin-bottom: 1.5rem; }

/* Ecosystem bars */
.ad-eco { display: flex; flex-direction: column; gap: 1.5rem; }
.ad-eco-item {}
.ad-eco-row {
    display: flex; justify-content: space-between; align-items: baseline;
    margin-bottom: .45rem;
}
.ad-eco-name { font-size: .78rem; font-weight: 500; color: var(--ink60); }
.ad-eco-count { font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 600; letter-spacing: -.03em; }
.ad-eco-pct   { font-size: .68rem; color: var(--ink30); }
.ad-bar-track {
    height: 4px; background: var(--ink10); border-radius: var(--rx); overflow: hidden;
}
.ad-bar-fill {
    height: 100%; border-radius: var(--rx);
    transition: width 1.2s var(--ease) .3s;
}

/* Infrastructure health */
.ad-health { display: flex; flex-direction: column; gap: 1.25rem; }
.ad-health-row {}
.ad-health-head {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: .45rem;
}
.ad-health-label { font-size: .78rem; font-weight: 500; color: var(--ink60); }
.ad-health-val   { font-size: .78rem; font-weight: 700; }
.ad-health-divider {
    margin-top: 1.5rem; padding-top: 1.25rem;
    border-top: 1px solid var(--ink06);
    display: flex; justify-content: space-between; align-items: center;
}
.ad-uptime-badge {
    display: flex; align-items: center; gap: .4rem;
    font-size: .7rem; font-weight: 700; color: var(--accent3);
}
.ad-uptime-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--accent3); animation: upPulse 2s ease infinite;
}
@keyframes upPulse { 0%,100%{opacity:1;} 50%{opacity:.3;} }

/* ══════════════════════════════
   § 3 — RECENT USERS TABLE
   Full-width, high-density
══════════════════════════════ */
.ad-table-wrap {
    border: 1px solid var(--ink10); border-radius: var(--rl); overflow: hidden;
}
.ad-table-topbar {
    background: var(--cream);
    padding: 1.6rem 2rem;
    display: flex; justify-content: space-between; align-items: center;
    border-bottom: 1px solid var(--ink10); flex-wrap: wrap; gap: 1rem;
}
.ad-table {
    width: 100%; border-collapse: collapse;
    background: var(--paper);
}
.ad-table thead tr { background: var(--cream); }
.ad-table thead th {
    padding: .75rem 2rem; text-align: left;
    font-size: .62rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .12em; color: var(--ink30);
    border-bottom: 1px solid var(--ink10);
}
.ad-table tbody tr {
    border-bottom: 1px solid var(--ink06);
    transition: background .2s;
}
.ad-table tbody tr:last-child { border-bottom: none; }
.ad-table tbody tr:hover { background: var(--ink03); }
.ad-table td { padding: 1rem 2rem; vertical-align: middle; }

.ad-user-cell { display: flex; align-items: center; gap: .85rem; }
.ad-avatar {
    width: 36px; height: 36px; border-radius: var(--r);
    display: flex; align-items: center; justify-content: center;
    font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 600;
    color: #fff; flex-shrink: 0;
}
.ad-user-name  { font-size: .85rem; font-weight: 600; color: var(--ink); line-height: 1.2; }
.ad-user-email { font-size: .72rem; color: var(--ink30); margin-top: .1rem; }
.ad-user-date  { font-size: .78rem; color: var(--ink30); font-style: italic; }

/* Role pill */
.ad-role-pill {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .25rem .7rem; border-radius: var(--rx);
    font-size: .65rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase;
}

/* Action link */
.ad-action-link {
    width: 30px; height: 30px; border-radius: var(--r);
    background: var(--ink06); border: 1px solid var(--ink10);
    display: inline-flex; align-items: center; justify-content: center;
    color: var(--ink30); font-size: .85rem;
    transition: all .2s var(--ease);
}
.ad-action-link:hover { background: var(--accent); color: #fff; border-color: var(--accent); }

/* ── CTA BUTTON ── */
.ad-btn {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .6rem 1.25rem; border-radius: var(--r);
    background: var(--ink); color: var(--paper);
    font-family: 'DM Sans', sans-serif; font-size: .82rem; font-weight: 600;
    border: none; cursor: pointer; transition: all .25s var(--ease);
}
.ad-btn:hover { opacity: .85; transform: translateY(-1px); }
.ad-btn-ghost {
    background: transparent; color: var(--ink60);
    border: 1px solid var(--ink10);
}
.ad-btn-ghost:hover { border-color: var(--ink30); color: var(--ink); background: var(--ink06); }

/* ── EMPTY STATE ── */
.ad-empty {
    text-align: center; padding: 4rem 2rem;
    background: var(--paper);
}
.ad-empty-icon  { font-size: 2.5rem; margin-bottom: .75rem; }
.ad-empty-title { font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 400; color: var(--ink60); }
.ad-empty-desc  { font-size: .82rem; color: var(--ink30); margin-top: .4rem; }

/* ══════════════════════════════
   § 4 — GROWTH CHART + STATS
══════════════════════════════ */
.ad-row3 {
    display: grid; grid-template-columns: 1fr 340px;
    gap: 1px; background: var(--ink10);
    border: 1px solid var(--ink10); border-radius: var(--rl);
    overflow: hidden; margin-bottom: 1.5rem;
}
.ad-chart-panel { background: var(--cream); padding: 2rem; }
.ad-chart-box { height: 210px; margin-top: .75rem; }

.ad-stats-panel {
    background: var(--warm); padding: 2rem;
    display: flex; flex-direction: column; gap: 1rem;
}
.ad-stat-mini {
    background: var(--cream); border: 1px solid var(--ink10);
    border-radius: var(--r); padding: 1rem 1.15rem;
    display: flex; align-items: center; gap: .85rem;
    transition: border-color .2s;
}
.ad-stat-mini:hover { border-color: var(--ink15); }
.ad-stat-icon {
    width: 36px; height: 36px; border-radius: var(--r);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.ad-stat-val {
    font-family: 'Fraunces', serif; font-size: 1.15rem;
    font-weight: 600; letter-spacing: -.04em; line-height: 1;
}
.ad-stat-lbl { font-size: .65rem; color: var(--ink30); font-weight: 600; margin-top: .15rem; }

/* ══════════════════════════════
   § 5 — BOTTOM ROW
   Activity · Quick actions
══════════════════════════════ */
.ad-row4 {
    display: grid; grid-template-columns: 1fr 360px;
    gap: 1px; background: var(--ink10);
    border: 1px solid var(--ink10); border-radius: var(--rl);
    overflow: hidden;
}

/* Timeline */
.ad-tl-panel { background: var(--cream); padding: 2rem; }
.ad-tl { display: flex; flex-direction: column; gap: 0; }
.ad-tl-item {
    padding-left: 1.3rem; border-left: 2px solid var(--ink10);
    position: relative; padding-bottom: 1.15rem;
}
.ad-tl-item:last-child { padding-bottom: 0; }
.ad-tl-item::before {
    content: ''; position: absolute; left: -5px; top: 3px;
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--accent2); border: 2px solid var(--cream);
}
.ad-tl-item.tl-accent::before { background: var(--accent); }
.ad-tl-item.tl-sage::before   { background: var(--accent3); }
.ad-tl-item.tl-gold::before   { background: var(--gold); }
.ad-tl-item.tl-red::before    { background: var(--red); }

.ad-tl-text { font-size: .82rem; color: var(--ink60); line-height: 1.5; }
.ad-tl-text strong { color: var(--ink); font-weight: 600; }
.ad-tl-time { font-size: .65rem; color: var(--ink30); margin-top: .15rem; }

/* Quick actions */
.ad-qa-panel { background: var(--warm); padding: 2rem; }
.ad-qa-list { display: flex; flex-direction: column; gap: .65rem; }
.ad-qa-btn {
    display: flex; align-items: center; gap: .75rem;
    width: 100%; padding: .85rem 1rem; border-radius: var(--r);
    background: var(--cream); border: 1px solid var(--ink10);
    font-family: 'DM Sans', sans-serif; font-size: .82rem;
    font-weight: 600; color: var(--ink); cursor: pointer;
    text-decoration: none; text-align: left;
    transition: all .2s var(--ease);
}
.ad-qa-btn:hover {
    border-color: color-mix(in srgb, var(--accent) 35%, transparent);
    background: color-mix(in srgb, var(--accent) 4%, transparent);
}
.ad-qa-icon {
    width: 34px; height: 34px; border-radius: var(--r);
    background: var(--ink06); border: 1px solid var(--ink10);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.ad-qa-desc { font-size: .68rem; color: var(--ink30); font-weight: 500; margin-top: .1rem; }

/* ── RESPONSIVE (new) ── */
@media (max-width: 1100px) { .ad-row2, .ad-row3, .ad-row4 { grid-template-columns: 1fr; } }
@media (max-width: 900px)  { .ad-kpi-band { grid-template-columns: 1fr 1fr; } }
@media (max-width: 500px)  { .ad-kpi-band { grid-template-columns: 1fr; }
    .ad-table thead th:nth-child(3), .ad-table td:nth-child(3) { display: none; }
}
</style>

<div class="ad" id="adRoot">

    {{-- ══════════════════════════════
         § 1 · KPI BAND
    ══════════════════════════════ --}}
    <div class="ad-kpi-band rev">

        {{-- Total Users --}}
        <div class="ad-kpi-cell">
            <div class="ad-kpi-icon">👥</div>
            <div class="ad-kpi-number">{{ $totalUsers ?? 0 }}</div>
            <p class="ad-kpi-label">Utilisateurs Totaux</p>
            <span class="ad-kpi-badge"
                  style="background:color-mix(in srgb,var(--accent3) 10%,transparent);color:var(--accent3);border:1px solid color-mix(in srgb,var(--accent3) 22%,transparent);">
                +{{ $newUsersToday ?? 0 }} aujourd'hui
            </span>
        </div>

        {{-- Students --}}
        <div class="ad-kpi-cell">
            <div class="ad-kpi-icon">🎓</div>
            <div class="ad-kpi-number">{{ $studentCount ?? 0 }}</div>
            <p class="ad-kpi-label">Étudiants Actifs</p>
            <span class="ad-kpi-badge"
                  style="background:color-mix(in srgb,var(--accent2) 10%,transparent);color:var(--accent2);border:1px solid color-mix(in srgb,var(--accent2) 22%,transparent);">
                Trajectoires IA
            </span>
        </div>

        {{-- Counselors --}}
        <div class="ad-kpi-cell">
            <div class="ad-kpi-icon">👨‍🏫</div>
            <div class="ad-kpi-number">{{ $counselorCount ?? 0 }}</div>
            <p class="ad-kpi-label">Conseillers Certifiés</p>
            <span class="ad-kpi-badge"
                  style="background:color-mix(in srgb,var(--gold) 12%,transparent);color:var(--gold);border:1px solid color-mix(in srgb,var(--gold) 28%,transparent);">
                Staff Expert
            </span>
        </div>

        {{-- Admins --}}
        <div class="ad-kpi-cell">
            <div class="ad-kpi-icon">🛡️</div>
            <div class="ad-kpi-number">{{ $adminCount ?? 0 }}</div>
            <p class="ad-kpi-label">Administrateurs</p>
            <span class="ad-kpi-badge"
                  style="background:color-mix(in srgb,var(--red) 10%,transparent);color:var(--red);border:1px solid color-mix(in srgb,var(--red) 22%,transparent);">
                Accès Root
            </span>
        </div>
    </div>

    {{-- ══════════════════════════════
         § 2 · ANALYTICS ROW
    ══════════════════════════════ --}}
    <div class="ad-row2 rev rev-d1">

        {{-- Left: Role distribution --}}
        <div class="ad-panel">
            <div class="ad-panel-header">
                <p class="ad-eyebrow" style="color:var(--accent2);">Écosystème</p>
                <h3 class="ad-sh">Répartition des <em style="color:var(--accent2);">rôles</em></h3>
            </div>

            @php $totalForPct = max(1, ($studentCount + $counselorCount + $adminCount)); @endphp

            <div class="ad-eco">
                <div class="ad-eco-item">
                    <div class="ad-eco-row">
                        <span class="ad-eco-name">🎓 Étudiants</span>
                        <div style="display:flex;align-items:baseline;gap:.5rem;">
                            <span class="ad-eco-count" style="color:var(--accent2);">{{ $studentCount }}</span>
                            <span class="ad-eco-pct">{{ round(($studentCount / $totalForPct) * 100) }}%</span>
                        </div>
                    </div>
                    <div class="ad-bar-track">
                        <div class="ad-bar-fill" style="width:{{ round(($studentCount / $totalForPct) * 100) }}%;background:var(--accent2);"></div>
                    </div>
                </div>

                <div class="ad-eco-item">
                    <div class="ad-eco-row">
                        <span class="ad-eco-name">👨‍🏫 Conseillers</span>
                        <div style="display:flex;align-items:baseline;gap:.5rem;">
                            <span class="ad-eco-count" style="color:var(--gold);">{{ $counselorCount }}</span>
                            <span class="ad-eco-pct">{{ round(($counselorCount / $totalForPct) * 100) }}%</span>
                        </div>
                    </div>
                    <div class="ad-bar-track">
                        <div class="ad-bar-fill" style="width:{{ round(($counselorCount / $totalForPct) * 100) }}%;background:var(--gold);"></div>
                    </div>
                </div>

                <div class="ad-eco-item">
                    <div class="ad-eco-row">
                        <span class="ad-eco-name">🛡️ Administrateurs</span>
                        <div style="display:flex;align-items:baseline;gap:.5rem;">
                            <span class="ad-eco-count" style="color:var(--red);">{{ $adminCount }}</span>
                            <span class="ad-eco-pct">{{ round(($adminCount / $totalForPct) * 100) }}%</span>
                        </div>
                    </div>
                    <div class="ad-bar-track">
                        <div class="ad-bar-fill" style="width:{{ round(($adminCount / $totalForPct) * 100) }}%;background:var(--red);"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Infrastructure health --}}
        <div class="ad-panel" style="border-left:1px solid var(--ink10);background:var(--warm);">
            <div class="ad-panel-header">
                <p class="ad-eyebrow" style="color:var(--accent3);">Infrastructure</p>
                <h3 class="ad-sh">Charge <em style="color:var(--accent3);">système</em></h3>
            </div>

            <div class="ad-health">
                <div class="ad-health-row">
                    <div class="ad-health-head">
                        <span class="ad-health-label">🖥️ CPU</span>
                        <span class="ad-health-val" style="color:var(--accent3);">24%</span>
                    </div>
                    <div class="ad-bar-track">
                        <div class="ad-bar-fill" style="width:24%;background:var(--accent3);"></div>
                    </div>
                </div>
                <div class="ad-health-row">
                    <div class="ad-health-head">
                        <span class="ad-health-label">💾 RAM</span>
                        <span class="ad-health-val" style="color:var(--accent2);">58%</span>
                    </div>
                    <div class="ad-bar-track">
                        <div class="ad-bar-fill" style="width:58%;background:var(--accent2);"></div>
                    </div>
                </div>
                <div class="ad-health-row">
                    <div class="ad-health-head">
                        <span class="ad-health-label">💿 Disque</span>
                        <span class="ad-health-val" style="color:var(--gold);">42%</span>
                    </div>
                    <div class="ad-bar-track">
                        <div class="ad-bar-fill" style="width:42%;background:var(--gold);"></div>
                    </div>
                </div>
            </div>

            <div class="ad-health-divider">
                <span style="font-size:.68rem;color:var(--ink30);font-weight:500;">Dernière sync : maintenant</span>
                <span class="ad-uptime-badge">
                    <span class="ad-uptime-dot"></span>
                    Opérationnel
                </span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════
         § 3 · GROWTH + PLATFORM STATS
    ══════════════════════════════ --}}
    <div class="ad-row3 rev rev-d2">
        {{-- Growth chart --}}
        <div class="ad-chart-panel">
            <div class="ad-panel-header">
                <p class="ad-eyebrow" style="color:var(--accent);">Croissance</p>
                <h3 class="ad-sh">Inscriptions <em style="color:var(--accent);">mensuelles</em></h3>
            </div>
            <div class="ad-chart-box">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        {{-- Platform stats mini-cards --}}
        <div class="ad-stats-panel">
            <div class="ad-panel-header">
                <p class="ad-eyebrow" style="color:var(--gold);">Métriques</p>
                <h3 class="ad-sh">Indicateurs <em style="color:var(--gold);">clés</em></h3>
            </div>

            <div class="ad-stat-mini">
                <div class="ad-stat-icon" style="background:color-mix(in srgb,var(--accent2) 8%,transparent);border:1px solid color-mix(in srgb,var(--accent2) 18%,transparent);">📊</div>
                <div>
                    <div class="ad-stat-val" style="color:var(--accent2);">87%</div>
                    <div class="ad-stat-lbl">Taux de complétion IA</div>
                </div>
            </div>

            <div class="ad-stat-mini">
                <div class="ad-stat-icon" style="background:color-mix(in srgb,var(--accent3) 8%,transparent);border:1px solid color-mix(in srgb,var(--accent3) 18%,transparent);">✅</div>
                <div>
                    <div class="ad-stat-val" style="color:var(--accent3);">94%</div>
                    <div class="ad-stat-lbl">Satisfaction plateforme</div>
                </div>
            </div>

            <div class="ad-stat-mini">
                <div class="ad-stat-icon" style="background:color-mix(in srgb,var(--gold) 8%,transparent);border:1px solid color-mix(in srgb,var(--gold) 18%,transparent);">⏱️</div>
                <div>
                    <div class="ad-stat-val" style="color:var(--gold);">2.4s</div>
                    <div class="ad-stat-lbl">Temps de réponse moyen</div>
                </div>
            </div>

            <div class="ad-stat-mini">
                <div class="ad-stat-icon" style="background:color-mix(in srgb,var(--accent) 8%,transparent);border:1px solid color-mix(in srgb,var(--accent) 18%,transparent);">🔒</div>
                <div>
                    <div class="ad-stat-val" style="color:var(--accent);">100%</div>
                    <div class="ad-stat-lbl">Conformité sécurité</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════
         § 4 · USERS TABLE
    ══════════════════════════════ --}}
    <div class="ad-table-wrap rev rev-d3" style="margin-bottom:1.5rem;">
        <div class="ad-table-topbar">
            <div>
                <p class="ad-eyebrow" style="color:var(--accent);">Flux d'inscriptions</p>
                <h3 class="ad-sh">Utilisateurs <em style="color:var(--accent);">récents</em></h3>
            </div>
            <a href="{{ route('admin.users.index') }}" class="ad-btn">
                Voir tous les comptes →
            </a>
        </div>

        <div style="overflow-x:auto;">
            <table class="ad-table">
                <thead>
                    <tr>
                        <th>Identité</th>
                        <th>Rôle</th>
                        <th>Inscription</th>
                        <th style="width:50px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $user)
                    <tr>
                        {{-- Identity --}}
                        <td>
                            <div class="ad-user-cell">
                                <div class="ad-avatar"
                                     style="background:{{ $user->is_admin ? 'var(--red)' : ($user->role === 'counselor' ? 'var(--gold)' : 'var(--accent2)') }};">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="ad-user-name">{{ $user->name }}</div>
                                    <div class="ad-user-email">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Role --}}
                        <td>
                            @if($user->is_admin)
                                <span class="ad-role-pill"
                                      style="background:color-mix(in srgb,var(--red) 10%,transparent);color:var(--red);border:1px solid color-mix(in srgb,var(--red) 22%,transparent);">
                                    Admin
                                </span>
                            @elseif($user->role === 'counselor')
                                <span class="ad-role-pill"
                                      style="background:color-mix(in srgb,var(--gold) 12%,transparent);color:var(--gold);border:1px solid color-mix(in srgb,var(--gold) 28%,transparent);">
                                    Conseiller
                                </span>
                            @else
                                <span class="ad-role-pill"
                                      style="background:color-mix(in srgb,var(--accent2) 10%,transparent);color:var(--accent2);border:1px solid color-mix(in srgb,var(--accent2) 22%,transparent);">
                                    Étudiant
                                </span>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td class="ad-user-date">{{ $user->created_at->diffForHumans() }}</td>

                        {{-- Action --}}
                        <td style="text-align:right;">
                            <a href="{{ route('admin.users.index') }}" class="ad-action-link">→</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="ad-empty">
                                <div class="ad-empty-icon">👤</div>
                                <p class="ad-empty-title">Aucun utilisateur récent</p>
                                <p class="ad-empty-desc">Les nouveaux comptes apparaîtront ici.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════
         § 5 · ACTIVITY + QUICK ACTIONS
    ══════════════════════════════ --}}
    <div class="ad-row4 rev rev-d4">

        {{-- Activity timeline --}}
        <div class="ad-tl-panel">
            <div class="ad-panel-header">
                <p class="ad-eyebrow" style="color:var(--accent2);">Journal</p>
                <h3 class="ad-sh">Activité <em style="color:var(--accent2);">système</em></h3>
            </div>

            <div class="ad-tl">
                <div class="ad-tl-item tl-sage">
                    <div class="ad-tl-text"><strong>Nouvel inscrit</strong> — Un étudiant a rejoint la plateforme via l'inscription publique</div>
                    <div class="ad-tl-time">Il y a 45 minutes</div>
                </div>
                <div class="ad-tl-item tl-accent">
                    <div class="ad-tl-text"><strong>Mise à jour IA</strong> — Le modèle de matching a été recalibré (v2.4.1)</div>
                    <div class="ad-tl-time">Il y a 2 heures</div>
                </div>
                <div class="ad-tl-item tl-gold">
                    <div class="ad-tl-text"><strong>Audit sécurité</strong> — Scan journalier : 0 vulnérabilités détectées</div>
                    <div class="ad-tl-time">Il y a 6 heures</div>
                </div>
                <div class="ad-tl-item tl-red">
                    <div class="ad-tl-text"><strong>Alerte système</strong> — Pic de charge base de données (résolu automatiquement)</div>
                    <div class="ad-tl-time">Hier à 23:15</div>
                </div>
                <div class="ad-tl-item">
                    <div class="ad-tl-text"><strong>Sauvegarde</strong> — Backup automatique complété avec succès (12.4 GB)</div>
                    <div class="ad-tl-time">Hier à 03:00</div>
                </div>
                <div class="ad-tl-item tl-sage">
                    <div class="ad-tl-text"><strong>Conseiller ajouté</strong> — Un nouveau conseiller a été certifié et activé</div>
                    <div class="ad-tl-time">Il y a 2 jours</div>
                </div>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="ad-qa-panel">
            <div class="ad-panel-header">
                <p class="ad-eyebrow" style="color:var(--accent);">Raccourcis</p>
                <h3 class="ad-sh">Actions <em style="color:var(--accent);">rapides</em></h3>
            </div>

            <div class="ad-qa-list">
                <a href="{{ route('admin.users.index') }}" class="ad-qa-btn">
                    <div class="ad-qa-icon">👥</div>
                    <div>
                        <div>Gérer les utilisateurs</div>
                        <div class="ad-qa-desc">Activer, modifier ou supprimer des comptes</div>
                    </div>
                </a>
                <a href="#" class="ad-qa-btn">
                    <div class="ad-qa-icon">📊</div>
                    <div>
                        <div>Rapport analytique</div>
                        <div class="ad-qa-desc">Exporter les statistiques en PDF</div>
                    </div>
                </a>
                <a href="#" class="ad-qa-btn">
                    <div class="ad-qa-icon">🧠</div>
                    <div>
                        <div>Recalibrer IA</div>
                        <div class="ad-qa-desc">Relancer les algorithmes de matching</div>
                    </div>
                </a>
                <a href="#" class="ad-qa-btn">
                    <div class="ad-qa-icon">🔐</div>
                    <div>
                        <div>Audit de sécurité</div>
                        <div class="ad-qa-desc">Scanner les vulnérabilités</div>
                    </div>
                </a>
                <a href="#" class="ad-qa-btn">
                    <div class="ad-qa-icon">💾</div>
                    <div>
                        <div>Sauvegarde manuelle</div>
                        <div class="ad-qa-desc">Créer un backup de la base de données</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Colors ── */
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const gridCol = isDark ? 'rgba(240,237,230,.05)' : 'rgba(11,12,16,.05)';
    const tickCol = isDark ? 'rgba(240,237,230,.3)'  : 'rgba(11,12,16,.3)';

    /* ── Scroll reveal ── */
    const revEls = document.querySelectorAll('#adRoot .rev');
    const revObs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('vis'); revObs.unobserve(e.target); }
        });
    }, { threshold: .05, rootMargin: '0px 0px -30px 0px' });
    revEls.forEach(el => revObs.observe(el));

    /* ── Bar animate on visible ── */
    const barObs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            const b = e.target, w = b.style.width;
            b.style.width = '0';
            requestAnimationFrame(() => requestAnimationFrame(() => { b.style.width = w; }));
            barObs.unobserve(b);
        });
    }, { threshold: .3 });
    document.querySelectorAll('.ad-bar-fill').forEach(b => barObs.observe(b));

    /* ── Growth chart (Chart.js) ── */
    const gCtx = document.getElementById('growthChart')?.getContext('2d');
    if (gCtx && typeof Chart !== 'undefined') {
        new Chart(gCtx, {
            type: 'line',
            data: {
                labels: ['Jan','Fév','Mar','Avr','Mai','Jun'],
                datasets: [
                    {
                        label: 'Étudiants',
                        data: [12, 28, 35, 48, 62, {{ $studentCount ?? 0 }}],
                        borderColor: '#1a4f6e', borderWidth: 2.5,
                        tension: .4, pointRadius: 4, pointBackgroundColor: '#1a4f6e',
                        fill: true,
                        backgroundColor: isDark ? 'rgba(26,79,110,.15)' : 'rgba(26,79,110,.06)'
                    },
                    {
                        label: 'Conseillers',
                        data: [2, 3, 4, 5, 6, {{ $counselorCount ?? 0 }}],
                        borderColor: '#c8973a', borderWidth: 2,
                        tension: .4, pointRadius: 3, pointBackgroundColor: '#c8973a',
                        fill: false, borderDash: [5, 5]
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: tickCol,
                            font: { family: "'DM Sans'", size: 11, weight: 600 },
                            padding: 14, usePointStyle: true, pointStyleWidth: 8
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: gridCol },
                        ticks: { color: tickCol, font: { family: "'DM Sans'", size: 11, weight: 600 } }
                    },
                    y: {
                        min: 0,
                        grid: { color: gridCol },
                        ticks: { color: tickCol, font: { family: "'DM Sans'", size: 11 } }
                    }
                }
            }
        });
    }

});
</script>
@endsection