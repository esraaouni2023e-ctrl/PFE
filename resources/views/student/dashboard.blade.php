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
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}
.db-select {
    appearance: none; -webkit-appearance: none;
    background: var(--paper); border: 1px solid var(--ink10);
    border-radius: var(--rx); padding: 0.6rem 2.5rem 0.6rem 1.2rem;
    font-family: inherit; font-size: 0.85rem; font-weight: 500; color: var(--ink);
    cursor: pointer; outline: none; transition: all 0.3s var(--ease);
}
.db-select:hover, .db-select:focus { border-color: var(--ink30); box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
.db-mcard {
    background: var(--paper); padding: 1.75rem;
    display: flex; flex-direction: column; gap: 1rem;
    transition: all .3s var(--ease); cursor: pointer;
    border-radius: var(--rl); border: 1px solid var(--ink10);
}
.db-mcard:hover {
    background: var(--cream); transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.05); border-color: var(--ink30);
}
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
    text-align: center; margin-bottom: 3.5rem;
}
.db-sim-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-bottom: 3rem;
}
.db-sim-path {
    background: var(--cream); padding: 3rem 2rem;
    text-align: center; cursor: pointer;
    transition: all .4s var(--ease);
    display: flex; flex-direction: column; align-items: center;
    border-radius: 24px; border: 1px solid var(--ink10);
    position: relative;
}
.db-sim-path:hover   { background: var(--warm); transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.06); border-color: var(--ink30); }
.db-sim-path.active  { background: var(--ink); transform: translateY(-8px); box-shadow: 0 24px 48px color-mix(in srgb, var(--ink) 30%, transparent); border-color: var(--ink); }
.db-sim-path.active .db-sim-path-title { color: var(--paper); }
.db-sim-path.active .db-metric-label   { color: rgba(255,255,255,.5); }
.db-sim-path.active .db-metric-track   { background: rgba(255,255,255,.1); }
.db-sim-path.active .db-sim-duration   { background: rgba(255,255,255,.08); border-color: rgba(255,255,255,.12); color: rgba(255,255,255,.6); }
.db-sim-path.active .db-metric-val     { color: var(--gold); }

.db-sim-icon { 
    width: 64px; height: 64px; border-radius: 18px;
    background: var(--paper); display: flex; align-items: center; justify-content: center;
    font-size: 2rem; margin-bottom: 1.5rem;
    box-shadow: 0 8px 16px rgba(0,0,0,0.04);
}
.db-sim-path.active .db-sim-icon { background: rgba(255,255,255,.1); }

.db-sim-path-title {
    font-family: 'Fraunces', serif; font-size: 1.4rem; font-weight: 600;
    letter-spacing: -.02em; margin-bottom: 2rem; line-height: 1.2;
}
.db-sim-rec {
    font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em;
    color: var(--accent); background: color-mix(in srgb, var(--accent) 12%, transparent);
    padding: .2rem .6rem; border-radius: var(--rx);
    display: inline-block; margin-top: .5rem;
}
.db-sim-path.active .db-sim-rec { color: var(--gold); background: rgba(255,255,255,.1); }

.db-metric { width: 100%; text-align: left; margin-bottom: 1.25rem; }
.db-metric-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: .5rem; }
.db-metric-label { font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: var(--ink30); }
.db-metric-val   { font-family: 'Fraunces', serif; font-size: 1rem; font-weight: 600; color: var(--accent); }
.db-metric-track { height: 6px; background: var(--ink10); border-radius: var(--rx); overflow: hidden; }
.db-metric-fill  { height: 100%; border-radius: var(--rx); transition: width 1s var(--ease); }

.db-sim-duration {
    margin-top: 1.5rem; padding: .6rem 1.25rem; border-radius: var(--rx);
    background: var(--paper); border: 1px solid var(--ink10);
    font-size: .8rem; font-weight: 600; color: var(--ink60); width: 100%; text-align: center;
}

.db-sim-cta { text-align: center; margin-top: 1rem; }

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
                <a href="#test-section" class="btn-fill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 14 4-4"/><path d="M3.34 19a10 10 0 1 1 17.32 0"/></svg>
                    Passer le test intelligent
                </a>
                <a href="#profile-section" class="btn-ghost">Voir mon profil IA</a>
            </div>

            <div class="db-hero-stats">
                <div class="db-stat-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent3)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                    <b>3</b> tests complétés
                </div>
                <div class="db-stat-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 14.5A2.5 2.5 0 0 0 14.5 12a2.5 2.5 0 0 0-2.5-2.5A2.5 2.5 0 0 0 9.5 12a2.5 2.5 0 0 0 2.5 2.5Z"/><path d="M10 2 2.23 7.74a2 2 0 0 0 .81 3.52l1.63.45a3 3 0 0 1 2.14 2.14l.45 1.63a2 2 0 0 0 3.52.81L16.5 10.5"/><path d="m18 16 4 4"/><path d="m20 16-4 4"/></svg>
                    <b>12</b> parcours suggérés
                </div>
                <div class="db-stat-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    Top <b>15%</b> des profils
                </div>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:0.5rem;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span class="db-ring-label">Profil IA</span>
                    <span class="db-ring-val">{{ $profilIaScore ?? 78 }}%</span>
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
        <div></div>
    </div>

    <div class="db-profile-grid">

        {{-- Left card: avatar + skills --}}
        <div class="card db-avatar-card rev rev-d1">

            <div class="db-avatar" style="overflow:hidden;">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" style="width:100%; height:100%; object-fit:cover;">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
                <div class="db-avatar-badge">✓</div>
            </div>

            <div>
                <div class="db-avatar-name">{{ explode(' ', auth()->user()->name)[0] }}</div>
                @if(isset($profilRiasec) && $profilRiasec)
                    <span class="pill pill-sage" style="margin-top:.5rem;">
                        Code Holland : <strong>{{ $profilRiasec->code_holland }}</strong>
                    </span>
                @else
                    <span class="pill pill-sage" style="margin-top:.5rem;">Niveau : Explorateur IA</span>
                @endif
            </div>

            <div class="db-skills">
                @foreach($dynamicSkills as $s)
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
                <div class="db-subsec-label">Points forts détectés</div>
                <div class="db-tags">
                    @foreach(['Résolution de problèmes','Pensée analytique','Curiosité technique','Adaptabilité','Créativité numérique','Communication écrite'] as $tag)
                    <span class="db-tag">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>

            <div>
                <div class="db-subsec-label">Progression du profil</div>
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
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:3rem;height:3rem;margin: 0 auto 1rem; color: var(--accent2);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-10.5v.75m.01 0a.75.75 0 01.75.75v.75m0 0a.75.75 0 01-.75.75H15m0 0a.75.75 0 01-.75-.75V5.25m.75 0V4.5m0 0a.75.75 0 01.75-.75h.75m0 0a.75.75 0 01.75.75v.75m0 0a.75.75 0 01-.75.75H15M9 15l-3 6m0 0l-1.5-3m1.5 3V9m12 0l3 6m0 0l1.5-3m-1.5 3V9" />
            </svg>
            <h3 style="font-family: 'Fraunces', serif; font-size: 1.5rem; margin-bottom: 1rem;">Créez votre chemin vers le succès</h3>
            <p style="color: var(--ink60); margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">Entrez le métier de vos rêves et laissez l'IA tracer le chemin universitaire exact étape par étape pour y arriver.</p>
            
            <form action="{{ route('student.roadmap.generate') }}" method="POST" style="display: flex; gap: 1rem; max-width: 500px; margin: 0 auto;">
                @csrf
                <input type="text" name="target_job" placeholder="Ex: Data Scientist, Ingénieur IA..." required style="flex: 1; padding: 0.8rem 1.2rem; border-radius: var(--rx); border: 1px solid var(--ink30); background: transparent;">
                <button type="submit" class="btn-fill" style="border-radius: var(--rx);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.1rem;height:1.1rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.456-2.455l.259-1.036.259 1.036a3.375 3.375 0 002.455 2.456l1.035.259-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                    </svg>
                    Générer
                </button>
            </form>
        </div>
    @endif
</section>

{{-- ════════════════════════════════
     § 4 · MATCHING
════════════════════════════════ --}}


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
        ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>', 'title' => 'Médecine', 'metrics' => ['Satisfaction' => 72, 'Revenu' => 88, 'Demande marché' => 90], 'duration' => '7 ans'],
        ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--accent2)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76Z"/></svg>', 'title' => 'Ingénierie', 'metrics' => ['Satisfaction' => 85, 'Revenu' => 82, 'Demande marché' => 87], 'duration' => '5 ans', 'active' => true],
        ['icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--accent3)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h7"/><path d="M16 5V3"/><path d="M8 5V3"/><path d="M3 9h18"/><path d="M21 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V13"/><path d="m15 15 3 3-3 3"/></svg>', 'title' => 'Data Science', 'metrics' => ['Satisfaction' => 91, 'Revenu' => 86, 'Demande marché' => 94], 'duration' => '4 ans'],
    ];
    @endphp

    <div class="db-sim-grid">
        @foreach($paths as $path)
        <div class="db-sim-path {{ isset($path['active']) ? 'active' : '' }} sim-path-toggle rev rev-d{{ $loop->index + 1 }}">
            <div class="db-sim-icon">{!! $path['icon'] !!}</div>
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

            <div class="db-sim-duration"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:0.85rem;height:0.85rem;display:inline-block;vertical-align:middle;margin-right:0.25rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z' /></svg>Durée : {{ $path['duration'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="db-sim-cta">
        <button class="btn-dark" style="padding:1rem 2.25rem;font-size:1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            Lancer le simulateur complet
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
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
    <span class="db-footer-copy"><i class="bi bi-stars"></i> CapAvenir 2026 · Ton orientation réinventée</span>
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