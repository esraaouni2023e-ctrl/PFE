@extends('layouts.student')

@section('title', 'Tableau de Bord')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;1,9..144,300;1,9..144,400&display=swap" rel="stylesheet">

<style>
/* ════════════════════════════════════════════
   DESIGN TOKENS — CapAvenir System
════════════════════════════════════════════ */
.db {
    --ink:     #0b0c10;
    --paper:   #f7f5f0;
    --cream:   #ede9e1;
    --warm:    #e8e1d4;
    --accent:  #d4622a;   /* terracotta */
    --accent2: #1a4f6e;   /* marine */
    --accent3: #4a7c59;   /* sage */
    --gold:    #c8973a;
    --ink60:   rgba(11,12,16,.6);
    --ink30:   rgba(11,12,16,.3);
    --ink15:   rgba(11,12,16,.15);
    --ink10:   rgba(11,12,16,.1);
    --ink06:   rgba(11,12,16,.06);
    --r:       6px;
    --rl:      16px;
    --rx:      999px;
    --ease:    cubic-bezier(.16,1,.3,1);

    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    background: var(--paper);
    padding: 2rem 3rem 5rem;
}

/* Dark mode */
[data-theme="dark"]  .db { --ink:#f0ede6;--paper:#10100d;--cream:#18170f;--warm:#1f1e14;--ink60:rgba(240,237,230,.6);--ink30:rgba(240,237,230,.3);--ink15:rgba(240,237,230,.15);--ink10:rgba(240,237,230,.08);--ink06:rgba(240,237,230,.04); }
[data-theme="light"] .db { --ink:#0b0c10;--paper:#f7f5f0;--cream:#ede9e1;--warm:#e8e1d4;--ink60:rgba(11,12,16,.6);--ink30:rgba(11,12,16,.3);--ink15:rgba(11,12,16,.15);--ink10:rgba(11,12,16,.1);--ink06:rgba(11,12,16,.06); }

.db *, .db *::before, .db *::after { box-sizing: border-box; margin: 0; padding: 0; }
.db a { color: inherit; text-decoration: none; }

/* ── REVEAL ── */
.db .rev { opacity: 0; transform: translateY(28px); transition: opacity .8s var(--ease), transform .8s var(--ease); }
.db .rev.vis { opacity: 1; transform: none; }
.db .rev-d1 { transition-delay: .1s; }
.db .rev-d2 { transition-delay: .2s; }
.db .rev-d3 { transition-delay: .3s; }
.db .rev-d4 { transition-delay: .4s; }

/* ── SECTION TAG ── */
.db .stag {
    font-size: .72rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase;
    color: var(--accent); display: inline-flex; align-items: center; gap: .5rem; margin-bottom: 1rem;
}
.db .stag::before { content: ''; width: 18px; height: 1px; background: var(--accent); }

/* ── SECTION HEADING ── */
.db .sh {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.8rem, 3.5vw, 3rem);
    font-weight: 300; letter-spacing: -.03em; line-height: 1.1;
}
.db .sh em { font-style: italic; color: var(--accent); }

/* ── GLASS CARD ── */
.db .card {
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: var(--rl);
    transition: all .3s var(--ease);
}
.db .card:hover { border-color: var(--ink30); }

/* ── BUTTONS ── */
.db .btn-fill {
    display: inline-flex; align-items: center; gap: .6rem;
    padding: .85rem 1.75rem; border-radius: var(--r);
    background: var(--accent); color: #fff;
    font-family: 'DM Sans', sans-serif; font-size: .9rem; font-weight: 500;
    border: none; cursor: pointer; text-decoration: none;
    box-shadow: 0 6px 24px color-mix(in srgb, var(--accent) 38%, transparent);
    transition: all .3s var(--ease); position: relative; overflow: hidden;
}
.db .btn-fill:hover { transform: translateY(-2px); box-shadow: 0 12px 36px color-mix(in srgb, var(--accent) 45%, transparent); }

.db .btn-dark {
    display: inline-flex; align-items: center; gap: .6rem;
    padding: .85rem 1.75rem; border-radius: var(--r);
    background: var(--ink); color: var(--paper);
    font-family: 'DM Sans', sans-serif; font-size: .9rem; font-weight: 500;
    border: none; cursor: pointer; text-decoration: none;
    transition: all .3s var(--ease);
}
.db .btn-dark:hover { transform: translateY(-2px); opacity: .88; }

.db .btn-ghost {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .75rem 1.5rem; border-radius: var(--r);
    background: transparent; border: 1px solid var(--ink30);
    color: var(--ink); font-family: 'DM Sans', sans-serif;
    font-size: .88rem; font-weight: 500; cursor: pointer; transition: all .25s;
}
.db .btn-ghost:hover { background: var(--ink10); border-color: var(--ink60); }

/* ── PILL BADGE ── */
.db .pill {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .3rem .85rem; border-radius: var(--rx);
    font-size: .72rem; font-weight: 600; letter-spacing: .06em;
}
.db .pill-accent { background: color-mix(in srgb,var(--accent) 10%,transparent); color: var(--accent); border: 1px solid color-mix(in srgb,var(--accent) 25%,transparent); }
.db .pill-sage   { background: color-mix(in srgb,var(--accent3) 10%,transparent); color: var(--accent3); border: 1px solid color-mix(in srgb,var(--accent3) 25%,transparent); }
.db .pill-marine { background: color-mix(in srgb,var(--accent2) 10%,transparent); color: var(--accent2); border: 1px solid color-mix(in srgb,var(--accent2) 25%,transparent); }
.db .pill-gold   { background: color-mix(in srgb,var(--gold) 12%,transparent); color: var(--gold); border: 1px solid color-mix(in srgb,var(--gold) 28%,transparent); }

/* ────────────────────────────────────────
   § 1 — HERO
──────────────────────────────────────── */
.db-hero {
    position: relative;
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: 20px;
    padding: 4.5rem 4rem 4rem;
    overflow: hidden;
    margin-bottom: 1.5rem;
    animation: dbFadeUp .9s var(--ease) both;
}
@keyframes dbFadeUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:none; } }

/* BG editorial word */
.db-hero-bgword {
    position: absolute;
    font-family: 'Fraunces', serif; font-weight: 300; font-style: italic;
    font-size: clamp(9rem, 19vw, 18rem);
    color: transparent;
    -webkit-text-stroke: 1px color-mix(in srgb, var(--ink) 5%, transparent);
    line-height: 1; letter-spacing: -.05em;
    right: -2%; top: 50%; transform: translateY(-50%);
    pointer-events: none; user-select: none;
}
/* Decorative orb */
.db-hero-orb {
    position: absolute; border-radius: 50%;
    width: 460px; height: 460px;
    background: radial-gradient(circle at 40% 40%,
        color-mix(in srgb, var(--accent) 14%, transparent),
        color-mix(in srgb, var(--accent2) 9%, transparent) 50%,
        transparent 75%);
    right: 3%; top: 50%; transform: translateY(-50%);
    pointer-events: none;
    animation: orbBreath 7s ease-in-out infinite;
}
@keyframes orbBreath { 0%,100%{transform:translateY(-50%) scale(1);} 50%{transform:translateY(-54%) scale(1.06);} }

.db-hero-inner {
    position: relative; z-index: 10;
    display: grid; grid-template-columns: 1fr auto;
    align-items: center; gap: 4rem;
}

/* Left text */
.db-hero-left {}
.db-hero-eyebrow {
    display: inline-flex; align-items: center; gap: .5rem;
    font-size: .75rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
    color: var(--accent); margin-bottom: 2rem;
}
.db-hero-eyebrow::before { content: ''; width: 18px; height: 1px; background: var(--accent); }
.eyebrow-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent3); animation: livePulse 2s ease-in-out infinite; }
@keyframes livePulse { 0%,100%{opacity:1;box-shadow:0 0 0 0 color-mix(in srgb,var(--accent3) 50%,transparent);} 50%{opacity:.6;box-shadow:0 0 0 6px transparent;} }

.db-hero-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(2.8rem, 5.5vw, 5.2rem);
    font-weight: 300; line-height: 1.04; letter-spacing: -.04em;
    margin-bottom: 1.5rem;
}
.db-hero-title em { font-style: italic; color: var(--accent); }
.db-hero-title strong { font-weight: 600; }

.db-hero-sub { font-size: 1rem; color: var(--ink60); line-height: 1.75; margin-bottom: 2.5rem; max-width: 480px; }

.db-hero-ctas { display: flex; gap: .875rem; flex-wrap: wrap; margin-bottom: 2.5rem; }

/* Quick stat pills */
.db-hero-stats { display: flex; flex-wrap: wrap; gap: .625rem; }
.db-stat-pill {
    display: flex; align-items: center; gap: .45rem;
    padding: .45rem 1rem; border-radius: var(--rx);
    background: var(--paper); border: 1px solid var(--ink10);
    font-size: .78rem; font-weight: 500; color: var(--ink60);
}
.db-stat-pill b { color: var(--ink); font-weight: 600; }

/* Right: progress ring */
.db-hero-right { display: flex; flex-direction: column; align-items: center; gap: 1.25rem; }
.db-ring-wrap { position: relative; width: 200px; height: 200px; flex-shrink: 0; animation: dbFloat 6s ease-in-out infinite; }
@keyframes dbFloat { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-12px);} }
.db-ring-wrap svg { transform: rotate(-90deg); }
.db-ring-center {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: .25rem;
}
.db-ring-emoji { font-size: 2.8rem; line-height: 1; }
.db-ring-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--ink60); }
.db-ring-val {
    font-family: 'Fraunces', serif; font-size: 2.2rem; font-weight: 600;
    letter-spacing: -.04em; color: var(--accent);
}
.db-name-card {
    padding: .875rem 1.75rem; border-radius: var(--rl);
    background: var(--paper); border: 1px solid var(--ink10);
    text-align: center;
}
.db-name-card .name { font-family: 'Fraunces', serif; font-size: 1.05rem; font-weight: 600; letter-spacing: -.02em; }
.db-name-card .role { font-size: .75rem; color: var(--accent); font-weight: 600; letter-spacing: .06em; text-transform: uppercase; margin-top: .2rem; }

/* ────────────────────────────────────────
   § 2 — PROFIL IA
──────────────────────────────────────── */
.db-profile-grid {
    display: grid; grid-template-columns: 300px 1fr;
    gap: 1.5rem; margin-bottom: 1.5rem;
}

/* Left column cards */
.db-avatar-card {
    display: flex; flex-direction: column; align-items: center;
    gap: 1.5rem; padding: 2.5rem 2rem; text-align: center;
}
.db-avatar {
    position: relative; width: 120px; height: 120px; border-radius: 50%;
    background: var(--ink); display: flex; align-items: center; justify-content: center;
    font-size: 3rem; flex-shrink: 0;
}
.db-avatar-badge {
    position: absolute; bottom: 4px; right: 4px;
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--accent3); display: flex; align-items: center; justify-content: center;
    font-size: .8rem; border: 2px solid var(--paper);
}
.db-avatar-name { font-family: 'Fraunces', serif; font-size: 1.4rem; font-weight: 600; letter-spacing: -.03em; }

/* Skill rows */
.db-skills { width: 100%; display: flex; flex-direction: column; gap: .625rem; }
.db-skill-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: .6rem 1rem; border-radius: var(--r);
    border: 1px solid var(--ink10); background: var(--paper);
    transition: all .25s;
}
.db-skill-row:hover { border-color: var(--ink30); background: var(--warm); }
.db-skill-name { font-size: .82rem; font-weight: 500; color: var(--ink60); }
.db-skill-val  { font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 600; letter-spacing: -.02em; }

/* Right column: radar + strengths + timeline */
.db-radar-col { padding: 2.5rem 2.5rem 2rem; display: flex; flex-direction: column; gap: 2rem; }
.db-radar-wrap { max-width: 360px; margin: 0 auto; width: 100%; }

.db-subsec-label {
    font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em;
    color: var(--ink30); margin-bottom: .875rem;
}
/* Strength tags */
.db-tags { display: flex; flex-wrap: wrap; gap: .5rem; }
.db-tag {
    padding: .3rem .875rem; border-radius: var(--rx);
    border: 1px solid var(--ink10); background: var(--paper);
    font-size: .78rem; font-weight: 500; color: var(--ink60);
    transition: all .2s;
}
.db-tag:hover { border-color: var(--accent); color: var(--accent); }

/* Timeline rows */
.db-timeline { display: flex; flex-direction: column; gap: .625rem; }
.db-tl-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: .75rem 1rem; border-radius: var(--r);
    border: 1px solid var(--ink10); background: var(--paper);
}
.db-tl-title  { font-size: .85rem; font-weight: 500; }
.db-tl-date   { font-size: .72rem; color: var(--ink60); margin-top: .15rem; }
.db-tl-score  { font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 600; color: var(--accent); }

/* ────────────────────────────────────────
   § 3 — PARCOURS
──────────────────────────────────────── */
.db-parcours-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: auto auto;
    gap: 1px;
    background: var(--ink10);
    border: 1px solid var(--ink10);
    border-radius: var(--rl);
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.db-pc {
    background: var(--paper); padding: 2rem 1.75rem;
    display: flex; flex-direction: column;
    transition: background .3s var(--ease); cursor: pointer;
    position: relative; overflow: hidden;
}
.db-pc:hover { background: var(--cream); }
.db-pc.featured { background: var(--ink); grid-row: span 2; }
.db-pc.featured:hover { background: color-mix(in srgb, var(--ink) 90%, var(--accent)); }
.db-pc.featured .db-pc-title   { color: var(--paper); }
.db-pc.featured .db-pc-desc    { color: color-mix(in srgb, var(--paper) 55%, transparent); }
.db-pc.featured .db-pc-meta    { color: color-mix(in srgb, var(--paper) 45%, transparent); background: rgba(255,255,255,.06); border-color: rgba(255,255,255,.1); }

.db-pc-score-num {
    font-family: 'Fraunces', serif; font-size: 2.5rem; font-weight: 600;
    letter-spacing: -.05em; line-height: 1; color: var(--accent);
}
.db-pc-score-label { font-size: .68rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: var(--ink30); }
.db-pc.featured .db-pc-score-num { color: var(--gold); }
.db-pc.featured .db-pc-score-label { color: rgba(255,255,255,.3); }

.db-pc-icon {
    width: 48px; height: 48px; border-radius: var(--r);
    background: color-mix(in srgb, var(--accent) 10%, transparent);
    border: 1px solid color-mix(in srgb, var(--accent) 20%, transparent);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; margin-bottom: 1.25rem; flex-shrink: 0;
}
.db-pc.featured .db-pc-icon { background: rgba(255,255,255,.1); border-color: rgba(255,255,255,.15); }

.db-pc-title { font-family: 'Fraunces', serif; font-size: 1.1rem; font-weight: 600; letter-spacing: -.025em; margin-bottom: .5rem; }
.db-pc.featured .db-pc-title { font-size: 1.3rem; }
.db-pc-desc  { font-size: .88rem; color: var(--ink60); line-height: 1.65; flex: 1; margin-bottom: 1.25rem; }

.db-pc-meta {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .25rem .75rem; border-radius: var(--rx);
    font-size: .72rem; font-weight: 600;
    background: var(--cream); border: 1px solid var(--ink10); color: var(--ink60);
    margin-right: .4rem; margin-bottom: .5rem;
}
.db-pc-bottom { display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 1rem; }
.db-pc-arrow {
    width: 34px; height: 34px; border-radius: var(--r);
    background: var(--cream); border: 1px solid var(--ink10);
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; cursor: pointer; transition: all .25s; color: var(--ink60);
}
.db-pc:hover .db-pc-arrow { background: var(--accent); color: #fff; border-color: var(--accent); }
.db-pc.featured .db-pc-arrow { background: rgba(255,255,255,.1); border-color: rgba(255,255,255,.15); color: var(--paper); }
.db-pc.featured:hover .db-pc-arrow { background: var(--accent); border-color: var(--accent); }

/* ────────────────────────────────────────
   § 4 — MATCHING
──────────────────────────────────────── */
.db-matching-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1px; background: var(--ink10);
    border: 1px solid var(--ink10); border-radius: var(--rl); overflow: hidden;
    margin-bottom: 1.5rem;
}
.db-mcard {
    background: var(--paper); padding: 1.75rem;
    display: flex; flex-direction: column; gap: 1rem;
    transition: background .3s var(--ease); cursor: pointer;
}
.db-mcard:hover { background: var(--cream); }
.db-mcard-head { display: flex; align-items: center; gap: 1rem; }
.db-mcard-icon {
    width: 48px; height: 48px; border-radius: var(--r);
    background: var(--cream); border: 1px solid var(--ink10);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.35rem; flex-shrink: 0; transition: all .25s;
}
.db-mcard:hover .db-mcard-icon { background: color-mix(in srgb,var(--accent) 10%,transparent); border-color: color-mix(in srgb,var(--accent) 25%,transparent); }
.db-mcard-name { font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 600; letter-spacing: -.02em; line-height: 1.25; }
.db-mcard-univ { font-size: .78rem; color: var(--ink60); margin-top: .2rem; }

/* Score bar */
.db-bar-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: .45rem; }
.db-bar-label { font-size: .7rem; font-weight: 600; text-transform: uppercase; letter-spacing: .07em; color: var(--ink30); }
.db-bar-score { font-family: 'Fraunces', serif; font-size: 1.15rem; font-weight: 600; letter-spacing: -.03em; color: var(--accent); }
.db-bar-track { height: 4px; background: var(--ink10); border-radius: var(--rx); overflow: hidden; }
.db-bar-fill  { height: 100%; background: var(--accent); border-radius: var(--rx); transition: width 1s var(--ease); }

.db-mcard-foot { display: flex; justify-content: space-between; align-items: center; }

/* ────────────────────────────────────────
   § 5 — SIMULATEUR
──────────────────────────────────────── */
.db-sim-header {
    text-align: center; margin-bottom: 2.5rem;
}
.db-sim-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 1px; background: var(--ink10);
    border: 1px solid var(--ink10); border-radius: var(--rl); overflow: hidden;
    margin-bottom: 2rem;
}
.db-sim-path {
    background: var(--paper); padding: 2.5rem 2rem;
    text-align: center; cursor: pointer;
    transition: background .3s var(--ease);
    display: flex; flex-direction: column; align-items: center;
}
.db-sim-path:hover   { background: var(--cream); }
.db-sim-path.active  { background: var(--ink); }
.db-sim-path.active:hover { background: color-mix(in srgb,var(--ink) 92%,var(--accent)); }
.db-sim-path.active .db-sim-path-title { color: var(--paper); }
.db-sim-path.active .db-metric-label   { color: rgba(255,255,255,.4); }
.db-sim-path.active .db-metric-track   { background: rgba(255,255,255,.07); }
.db-sim-path.active .db-sim-duration   { background: rgba(255,255,255,.07); border-color: rgba(255,255,255,.1); color: rgba(255,255,255,.55); }
.db-sim-path.active .db-metric-val     { color: var(--gold); }

.db-sim-icon { font-size: 2.5rem; margin-bottom: .875rem; }
.db-sim-path-title {
    font-family: 'Fraunces', serif; font-size: 1.3rem; font-weight: 600;
    letter-spacing: -.03em; margin-bottom: 1.75rem;
}
.db-sim-rec {
    font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
    color: var(--accent3); display: block; margin-top: .25rem;
}
.db-metric { width: 100%; text-align: left; margin-bottom: 1rem; }
.db-metric-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: .4rem; }
.db-metric-label { font-size: .75rem; font-weight: 500; color: var(--ink60); }
.db-metric-val   { font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 600; color: var(--accent); letter-spacing: -.02em; }
.db-metric-track { height: 4px; background: var(--ink10); border-radius: var(--rx); overflow: hidden; }
.db-metric-fill  { height: 100%; border-radius: var(--rx); transition: width .8s var(--ease) .3s; }
.db-sim-duration {
    margin-top: 1.25rem; padding: .5rem 1rem; border-radius: var(--rx);
    background: var(--cream); border: 1px solid var(--ink10);
    font-size: .78rem; font-weight: 500; color: var(--ink60); width: 100%; text-align: center;
}

.db-sim-cta { text-align: center; }

/* ────────────────────────────────────────
   § SECTIONS WRAPPER
──────────────────────────────────────── */
.db-section { margin-bottom: 3rem; }
.db-section-header {
    display: flex; justify-content: space-between; align-items: flex-end;
    margin-bottom: 1.75rem; gap: 1rem; flex-wrap: wrap;
}

/* ────────────────────────────────────────
   § FOOTER
──────────────────────────────────────── */
.db-footer {
    padding: 2rem 0; border-top: 1px solid var(--ink10);
    display: flex; justify-content: space-between; align-items: center;
    flex-wrap: wrap; gap: 1rem;
}
.db-footer-links { display: flex; gap: 2rem; flex-wrap: wrap; }
.db-footer-links a { font-size: .82rem; color: var(--ink30); transition: color .2s; }
.db-footer-links a:hover { color: var(--accent); }
.db-footer-copy { font-size: .8rem; color: var(--ink30); }

/* ────────────────────────────────────────
   RESPONSIVE
──────────────────────────────────────── */
@media (max-width: 1000px) {
    .db-hero-inner   { grid-template-columns: 1fr; }
    .db-hero-right   { display: none; }
    .db-profile-grid { grid-template-columns: 1fr; }
    .db-parcours-grid { grid-template-columns: 1fr 1fr; }
    .db-pc.featured  { grid-row: auto; }
    .db-sim-grid     { grid-template-columns: 1fr; }
}
@media (max-width: 700px) {
    .db                { padding: 1rem 1rem 3rem; }
    .db-hero           { padding: 2.5rem 1.5rem; border-radius: var(--rl); }
    .db-parcours-grid  { grid-template-columns: 1fr; }
    .db-matching-grid  { grid-template-columns: 1fr; }
    .db-hero-ctas      { flex-direction: column; }
}
</style>

<div class="db" id="dbRoot">

{{-- ════════════════════════════════
     § 1 · HERO
════════════════════════════════ --}}
<section class="db-hero">
    <div class="db-hero-bgword">Avenir</div>
    <div class="db-hero-orb"></div>

    <div class="db-hero-inner">

        {{-- Left --}}
        <div class="db-hero-left">
            <div class="db-hero-eyebrow">
                <span class="eyebrow-dot"></span>
                IA d'Orientation Active · 2026
            </div>

            <h1 class="db-hero-title">
                Ton avenir<br>
                <em>se dessine</em><br>
                <strong>ici.</strong>
            </h1>

            <p class="db-hero-sub">
                Découvre tes forces réelles, construis un parcours 100% toi et choisis avec confiance — propulsé par l'IA.
            </p>

            <div class="db-hero-ctas">
                <a href="#test-section" class="btn-fill">🧠 Passer le test intelligent →</a>
                <a href="#matching-section" class="btn-ghost">Voir mes recommandations</a>
            </div>

            <div class="db-hero-stats">
                <div class="db-stat-pill">⚡ <b>3</b> tests complétés</div>
                <div class="db-stat-pill">🎯 <b>12</b> parcours suggérés</div>
                <div class="db-stat-pill">🏆 Top <b>15%</b> des profils</div>
            </div>
        </div>

        {{-- Right: ring --}}
        <div class="db-hero-right">
            <div class="db-ring-wrap">
                <svg width="200" height="200" viewBox="0 0 200 200">
                    <defs>
                        <linearGradient id="dbRingGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#d4622a"/>
                            <stop offset="100%" stop-color="#1a4f6e"/>
                        </linearGradient>
                    </defs>
                    <circle cx="100" cy="100" r="86" fill="none" stroke-width="8"
                            stroke="color-mix(in srgb, var(--ink) 8%, transparent)"/>
                    <circle cx="100" cy="100" r="86" fill="none"
                            stroke="url(#dbRingGrad)" stroke-width="8"
                            stroke-linecap="round"
                            stroke-dasharray="540.35"
                            stroke-dashoffset="118.88"/>
                </svg>
                <div class="db-ring-center">
                    <span class="db-ring-emoji">🎓</span>
                    <span class="db-ring-label">Profil IA</span>
                    <span class="db-ring-val">78%</span>
                </div>
            </div>

            <div class="db-name-card">
                <div class="name">{{ auth()->user()->name }}</div>
                <div class="role">Étudiant · Orientation IA</div>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════
     § 2 · PROFIL IA
════════════════════════════════ --}}
<section class="db-section rev" id="profile-section">
    <div class="db-section-header">
        <div>
            <p class="stag">Profil cognitif</p>
            <h2 class="sh">Ton profil <em>IA</em></h2>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn-ghost">✏️ Enrichir mon profil</a>
    </div>

    <div class="db-profile-grid">

        {{-- Left card: avatar + skills --}}
        <div class="card db-avatar-card rev rev-d1">
            <div class="db-avatar">
                🎓
                <div class="db-avatar-badge">⚡</div>
            </div>
            <div>
                <div class="db-avatar-name">{{ explode(' ', auth()->user()->name)[0] }}</div>
                <span class="pill pill-sage" style="margin-top:.5rem;">Niveau : Explorateur IA</span>
            </div>

            @php
            $skills = [
                ['label'=>'Créativité',     'val'=>92, 'color'=>'var(--accent)'],
                ['label'=>'Logique',         'val'=>85, 'color'=>'var(--accent2)'],
                ['label'=>'Intérêt Tech',    'val'=>89, 'color'=>'var(--accent)'],
                ['label'=>'Social',          'val'=>64, 'color'=>'var(--accent3)'],
                ['label'=>'Gestion',         'val'=>71, 'color'=>'var(--gold)'],
            ];
            @endphp
            <div class="db-skills">
                @foreach($skills as $s)
                <div class="db-skill-row">
                    <span class="db-skill-name">{{ $s['label'] }}</span>
                    <span class="db-skill-val" style="color:{{ $s['color'] }};">{{ $s['val'] }}%</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Right card: radar + strengths + timeline --}}
        <div class="card db-radar-col rev rev-d2">
            <div class="db-radar-wrap">
                <canvas id="profileRadar" height="300"></canvas>
            </div>

            <div>
                <div class="db-subsec-label">⚡ Points forts détectés</div>
                <div class="db-tags">
                    @foreach(['Résolution de problèmes','Pensée analytique','Curiosité technique','Adaptabilité','Créativité numérique','Communication écrite'] as $tag)
                    <span class="db-tag">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>

            <div>
                <div class="db-subsec-label">📈 Progression du profil</div>
                <div class="db-timeline">
                    @foreach([
                        ['Test Cognitif Global','12 Fév 2026','92%'],
                        ['Intérêts Professionnels','10 Fév 2026','78%'],
                        ['Compétences Techniques','5 Fév 2026','85%'],
                    ] as $tl)
                    <div class="db-tl-row">
                        <div>
                            <div class="db-tl-title">{{ $tl[0] }}</div>
                            <div class="db-tl-date">{{ $tl[1] }}</div>
                        </div>
                        <div class="db-tl-score">{{ $tl[2] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════
     § 2.5 · PORTFOLIO DE COMPÉTENCES
════════════════════════════════ --}}
<section class="db-section rev" id="portfolio-section">
    <div class="db-section-header">
        <div>
            <p class="stag">Mes Réalisations</p>
            <h2 class="sh">Portfolio & <em>Certifications</em></h2>
        </div>
    </div>

    <div class="db-portfolio-container" style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; margin-bottom: 3rem;">
        {{-- Upload Zone --}}
        <div class="card" style="padding: 2rem; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; border-style: dashed; border-width: 2px;">
            <div style="font-size: 2.5rem; margin-bottom: 1rem;">📁</div>
            <h3 style="font-family: 'Fraunces', serif; font-size: 1.2rem; margin-bottom: 0.5rem;">Ajouter un document</h3>
            <p style="font-size: 0.85rem; color: var(--ink60); margin-bottom: 1.5rem;">L'IA analysera votre certificat ou projet pour extraire vos compétences réelles.</p>
            
            <form action="{{ route('student.portfolio.store') }}" method="POST" enctype="multipart/form-data" style="width: 100%;">
                @csrf
                <input type="text" name="title" placeholder="Titre (ex: Certificat Python)" required style="width: 100%; padding: 0.8rem; margin-bottom: 1rem; border-radius: var(--r); border: 1px solid var(--ink10); background: var(--paper);">
                <select name="type" required style="width: 100%; padding: 0.8rem; margin-bottom: 1rem; border-radius: var(--r); border: 1px solid var(--ink10); background: var(--paper);">
                    <option value="certificate">Certificat</option>
                    <option value="project">Projet</option>
                    <option value="document">Autre Document</option>
                </select>
                <input type="file" name="file" accept=".pdf,.jpg,.png" required style="margin-bottom: 1rem; width: 100%; font-size: 0.85rem;">
                <button type="submit" class="btn-fill" style="width: 100%; justify-content: center;">🚀 Uploader & Analyser</button>
            </form>
        </div>

        {{-- Uploaded Items --}}
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @forelse($portfolios as $item)
            <div class="card" style="padding: 1.5rem; display: flex; gap: 1.5rem; align-items: flex-start;">
                <div style="width: 50px; height: 50px; border-radius: var(--r); background: var(--ink10); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                    {{ $item->type === 'certificate' ? '🎓' : '📄' }}
                </div>
                <div style="flex: 1;">
                    <h4 style="font-family: 'Fraunces', serif; font-size: 1.1rem; margin-bottom: 0.25rem;">{{ $item->title }}</h4>
                    <p style="font-size: 0.85rem; color: var(--ink60); margin-bottom: 0.75rem;">{{ $item->ai_analysis_summary ?? 'Analyse en cours...' }}</p>
                    
                    @if($item->extracted_skills)
                    <div class="db-tags">
                        @foreach($item->extracted_skills as $skill)
                        <span class="db-tag" style="font-size: 0.7rem; padding: 0.2rem 0.6rem;">{{ is_array($skill) ? json_encode($skill) : $skill }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
                <form action="{{ route('student.portfolio.destroy', $item) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-ghost" style="padding: 0.5rem; color: #d93838; border-color: transparent;">🗑️</button>
                </form>
            </div>
            @empty
            <div class="card" style="padding: 3rem 2rem; text-align: center; color: var(--ink30);">
                <p>Aucun document dans votre portfolio pour le moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ════════════════════════════════
     § 3 · ROADMAP DE CARRIÈRE (TIMELINE)
════════════════════════════════ --}}
<section class="db-section rev" id="parcours-section">
    <div class="db-section-header">
        <div>
            <p class="stag">Sur mesure</p>
            <h2 class="sh">Ta Roadmap de <em>Carrière</em></h2>
        </div>
    </div>

    @if($roadmaps->count() > 0)
        @foreach($roadmaps as $roadmap)
        <div class="card" style="padding: 2.5rem; margin-bottom: 1.5rem; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h3 style="font-family: 'Fraunces', serif; font-size: 1.5rem; color: var(--accent);">Objectif : {{ $roadmap->target_job }}</h3>
                <span class="pill pill-sage">Généré par IA</span>
            </div>

            <div style="position: relative; padding-left: 2rem; border-left: 2px solid var(--ink10); display: flex; flex-direction: column; gap: 2rem;">
                @if(is_array($roadmap->steps))
                    @foreach($roadmap->steps as $step)
                    <div style="position: relative;">
                        {{-- Timeline Dot --}}
                        <div style="position: absolute; left: -2.65rem; top: 0; width: 20px; height: 20px; border-radius: 50%; background: var(--accent); border: 4px solid var(--paper);"></div>
                        
                        <div style="background: var(--paper); border: 1px solid var(--ink10); border-radius: var(--rl); padding: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <h4 style="font-weight: 600; font-size: 1.1rem;">{{ $step['title'] ?? 'Étape' }}</h4>
                                <span class="pill pill-gold">{{ $step['duration'] ?? '' }}</span>
                            </div>
                            <p style="font-size: 0.9rem; color: var(--ink60); line-height: 1.6;">{{ $step['description'] ?? '' }}</p>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
        @endforeach
    @else
        <div class="card" style="padding: 3rem 2rem; text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🗺️</div>
            <h3 style="font-family: 'Fraunces', serif; font-size: 1.5rem; margin-bottom: 1rem;">Créez votre chemin vers le succès</h3>
            <p style="color: var(--ink60); margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">Entrez le métier de vos rêves et laissez l'IA tracer le chemin universitaire exact étape par étape pour y arriver.</p>
            
            <form action="{{ route('student.roadmap.generate') }}" method="POST" style="display: flex; gap: 1rem; max-width: 500px; margin: 0 auto;">
                @csrf
                <input type="text" name="target_job" placeholder="Ex: Data Scientist, Ingénieur IA..." required style="flex: 1; padding: 0.8rem 1.2rem; border-radius: var(--rx); border: 1px solid var(--ink30); background: transparent;">
                <button type="submit" class="btn-fill" style="border-radius: var(--rx);">✨ Générer</button>
            </form>
        </div>
    @endif
</section>

{{-- ════════════════════════════════
     § 4 · MATCHING
════════════════════════════════ --}}
<section class="db-section rev" id="matching-section">
    <div class="db-section-header">
        <div>
            <p class="stag">Top recommandations</p>
            <h2 class="sh">Les formations qui te <em>correspondent</em></h2>
        </div>
    </div>

    <div class="db-matching-grid">
        @foreach($predictions as $f)
        <div class="db-mcard rev rev-d{{ ($loop->index % 3) + 1 }}">
            <div class="db-mcard-head">
                <div class="db-mcard-icon">{{ $f['icon'] }}</div>
                <div>
                    <div class="db-mcard-name">{{ $f['name'] }}</div>
                    <div class="db-mcard-univ">{{ $f['univ'] }}</div>
                </div>
            </div>

            <div>
                <div class="db-bar-row">
                    <span class="db-bar-label">Chances d'Admission</span>
                    <span class="db-bar-score" style="color: {{ $f['score'] > 80 ? 'var(--accent3)' : ($f['score'] > 60 ? 'var(--gold)' : 'var(--accent)') }};">{{ $f['score'] }}%</span>
                </div>
                <div class="db-bar-track">
                    <div class="db-bar-fill match-bar-fill" style="width:{{ $f['score'] }}%; background: {{ $f['score'] > 80 ? 'var(--accent3)' : ($f['score'] > 60 ? 'var(--gold)' : 'var(--accent)') }};"></div>
                </div>
            </div>

            <div class="db-mcard-foot">
                <span class="pill pill-sage" style="font-size:.7rem; background: {{ $f['score'] > 80 ? 'color-mix(in srgb,var(--accent3) 10%,transparent)' : ($f['score'] > 60 ? 'color-mix(in srgb,var(--gold) 10%,transparent)' : 'color-mix(in srgb,var(--accent) 10%,transparent)') }}; color: {{ $f['score'] > 80 ? 'var(--accent3)' : ($f['score'] > 60 ? 'var(--gold)' : 'var(--accent)') }}; border-color: currentColor;">Score IA</span>
                <button class="btn-ghost" style="padding:.4rem .9rem;font-size:.78rem;">Détails →</button>
            </div>
        </div>
        @endforeach
    </div>

    <div style="text-align:center;margin-top:1.5rem;">
        <button class="btn-ghost">🔄 Comparer ces options</button>
    </div>
</section>

{{-- ════════════════════════════════
     § 5 · SIMULATEUR
════════════════════════════════ --}}
<section class="db-section rev" id="test-section">
    <div class="db-sim-header">
        <p class="stag" style="justify-content:center;">Simulation IA</p>
        <h2 class="sh" style="font-size:clamp(1.8rem,4vw,3rem);">Et si tu <em>choisissais</em>… ?</h2>
        <p style="font-size:.95rem;color:var(--ink60);max-width:460px;margin:.75rem auto 0;line-height:1.7;">
            Compare 3 chemins et visualise leur impact sur ta vie future
        </p>
    </div>

    @php
    $paths = [
        ['icon'=>'🩺','title'=>'Médecine',      'metrics'=>['Satisfaction'=>72,'Revenu'=>88,'Demande marché'=>90],'duration'=>'7 ans'],
        ['icon'=>'⚙️','title'=>'Ingénierie',   'metrics'=>['Satisfaction'=>85,'Revenu'=>82,'Demande marché'=>87],'duration'=>'5 ans','active'=>true],
        ['icon'=>'📊','title'=>'Data Science',  'metrics'=>['Satisfaction'=>91,'Revenu'=>86,'Demande marché'=>94],'duration'=>'4 ans'],
    ];
    @endphp

    <div class="db-sim-grid">
        @foreach($paths as $path)
        <div class="db-sim-path {{ isset($path['active']) ? 'active' : '' }} sim-path-toggle rev rev-d{{ $loop->index + 1 }}">
            <div class="db-sim-icon">{{ $path['icon'] }}</div>
            <div class="db-sim-path-title">
                {{ $path['title'] }}
                @if(isset($path['active']))
                <span class="db-sim-rec">Recommandé IA</span>
                @endif
            </div>

            @foreach($path['metrics'] as $label => $val)
            <div class="db-metric" style="width:100%;">
                <div class="db-metric-header">
                    <span class="db-metric-label">{{ $label }}</span>
                    <span class="db-metric-val">{{ $val }}%</span>
                </div>
                <div class="db-metric-track">
                    <div class="db-metric-fill match-bar-fill" style="width:{{ $val }}%;background:var(--accent);"></div>
                </div>
            </div>
            @endforeach

            <div class="db-sim-duration">⏱ Durée : {{ $path['duration'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="db-sim-cta">
        <button class="btn-dark" style="padding:1rem 2.25rem;font-size:1rem;">
            🔮 Lancer le simulateur complet →
        </button>
    </div>
</section>


{{-- ════════════════════════════════
     FOOTER
════════════════════════════════ --}}
<footer class="db-footer">
    <div class="db-footer-links">
        <a href="#">Confidentialité</a>
        <a href="#">Support</a>
        <a href="#">À propos</a>
    </div>
    <span class="db-footer-copy">✦ CapAvenir 2026 · Ton orientation réinventée</span>
</footer>

</div>{{-- /db --}}

{{-- ════════════════════════════════
     SCRIPTS
════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Scroll reveal ── */
    const revEls = document.querySelectorAll('#dbRoot .rev');
    const revObs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); revObs.unobserve(e.target); } });
    }, { threshold: .08, rootMargin: '0px 0px -40px 0px' });
    revEls.forEach(el => revObs.observe(el));

    /* ── Match bar animate ── */
    const barObs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            const b = e.target, w = b.style.width;
            b.style.width = '0';
            setTimeout(() => { b.style.width = w; }, 120);
            barObs.unobserve(b);
        });
    }, { threshold: .3 });
    document.querySelectorAll('.match-bar-fill').forEach(b => barObs.observe(b));

    /* ── Simulator toggle ── */
    document.querySelectorAll('.sim-path-toggle').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.sim-path-toggle').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
        });
    });

    /* ── Radar chart ── */
    const ctx = document.getElementById('profileRadar');
    if (ctx && typeof Chart !== 'undefined') {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const labelColor = isDark ? 'rgba(240,237,230,.55)' : 'rgba(11,12,16,.45)';
        const gridColor  = isDark ? 'rgba(240,237,230,.07)' : 'rgba(11,12,16,.08)';

        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Logique','Créativité','Social','Technique','Gestion','Communication'],
                datasets: [{
                    label: 'Ton profil',
                    data: [85, 92, 64, 89, 71, 78],
                    backgroundColor: 'color-mix(in srgb, #d4622a 12%, transparent)',
                    borderColor: '#d4622a',
                    borderWidth: 2,
                    pointBackgroundColor: '#d4622a',
                    pointBorderColor: isDark ? '#10100d' : '#f7f5f0',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }, {
                    label: 'Moyenne nationale',
                    data: [60, 65, 70, 60, 65, 60],
                    backgroundColor: 'transparent',
                    borderColor: isDark ? 'rgba(240,237,230,.15)' : 'rgba(11,12,16,.15)',
                    borderWidth: 1.5,
                    borderDash: [4, 4],
                    pointRadius: 0,
                }]
            },
            options: {
                scales: {
                    r: {
                        min: 0, max: 100,
                        angleLines: { color: gridColor, lineWidth: 1 },
                        grid:        { color: gridColor, lineWidth: 1 },
                        pointLabels: { color: labelColor, font: { size: 11, weight: '500', family: 'DM Sans' } },
                        ticks:       { display: false, stepSize: 25 }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: labelColor,
                            font: { size: 11, family: 'DM Sans' },
                            padding: 16, boxWidth: 10, usePointStyle: true
                        }
                    }
                },
                animation: { duration: 1200, easing: 'easeInOutQuart' },
                maintainAspectRatio: true,
            }
        });
    }
});
</script>

@endsection