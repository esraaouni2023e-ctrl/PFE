@extends('layouts.student')

@section('title', 'Espace Orientation')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;1,9..144,300;1,9..144,400&display=swap" rel="stylesheet">

<style>
/* ════════════════════════════════════════════
   ORIENTATION PAGE — CapAvenir Design System
════════════════════════════════════════════ */
.or {
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
    --r:       6px;
    --rl:      16px;
    --rx:      999px;
    --ease:    cubic-bezier(.16,1,.3,1);

    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    background: var(--paper);
    padding: 2rem 3rem 5rem;
}

[data-theme="dark"]  .or { --ink:#f0ede6;--paper:#10100d;--cream:#18170f;--warm:#1f1e14;--ink60:rgba(240,237,230,.6);--ink30:rgba(240,237,230,.3);--ink15:rgba(240,237,230,.15);--ink10:rgba(240,237,230,.08);--ink06:rgba(240,237,230,.04); }
[data-theme="light"] .or { --ink:#0b0c10;--paper:#f7f5f0;--cream:#ede9e1;--warm:#e8e1d4;--ink60:rgba(11,12,16,.6);--ink30:rgba(11,12,16,.3);--ink15:rgba(11,12,16,.15);--ink10:rgba(11,12,16,.1);--ink06:rgba(11,12,16,.06); }

.or *, .or *::before, .or *::after { box-sizing: border-box; margin: 0; padding: 0; }
.or a { color: inherit; text-decoration: none; }

/* ── Reveal ── */
.or .rev { opacity: 0; transform: translateY(24px); transition: opacity .7s var(--ease), transform .7s var(--ease); }
.or .rev.vis { opacity: 1; transform: none; }
.or .rev-d1 { transition-delay: .08s; }
.or .rev-d2 { transition-delay: .16s; }
.or .rev-d3 { transition-delay: .24s; }
.or .rev-d4 { transition-delay: .32s; }

/* ── Section tag ── */
.or .stag {
    font-size: .72rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase;
    color: var(--accent); display: inline-flex; align-items: center; gap: .5rem; margin-bottom: 1rem;
}
.or .stag::before { content: ''; width: 18px; height: 1px; background: var(--accent); }

/* ── Section heading ── */
.or .sh {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.8rem, 4vw, 3rem);
    font-weight: 300; letter-spacing: -.03em; line-height: 1.08;
}
.or .sh em { font-style: italic; color: var(--accent); }

/* ── Pill ── */
.or .pill {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .28rem .8rem; border-radius: var(--rx);
    font-size: .72rem; font-weight: 600; letter-spacing: .05em;
}
.or .pill-accent { background: color-mix(in srgb,var(--accent) 10%,transparent); color: var(--accent); border: 1px solid color-mix(in srgb,var(--accent) 25%,transparent); }
.or .pill-sage   { background: color-mix(in srgb,var(--accent3) 10%,transparent); color: var(--accent3); border: 1px solid color-mix(in srgb,var(--accent3) 25%,transparent); }
.or .pill-marine { background: color-mix(in srgb,var(--accent2) 10%,transparent); color: var(--accent2); border: 1px solid color-mix(in srgb,var(--accent2) 25%,transparent); }
.or .pill-ink    { background: var(--ink06); color: var(--ink60); border: 1px solid var(--ink10); }

/* ── Button ── */
.or .btn-fill {
    display: inline-flex; align-items: center; gap: .55rem;
    padding: .8rem 1.65rem; border-radius: var(--r);
    background: var(--accent); color: #fff;
    font-family: 'DM Sans', sans-serif; font-size: .88rem; font-weight: 500;
    border: none; cursor: pointer;
    box-shadow: 0 4px 20px color-mix(in srgb, var(--accent) 35%, transparent);
    transition: all .3s var(--ease);
}
.or .btn-fill:hover { transform: translateY(-2px); box-shadow: 0 10px 32px color-mix(in srgb, var(--accent) 45%, transparent); }

.or .btn-ghost {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .7rem 1.4rem; border-radius: var(--r);
    background: transparent; border: 1px solid var(--ink30);
    color: var(--ink); font-family: 'DM Sans', sans-serif;
    font-size: .85rem; font-weight: 500; cursor: pointer; transition: all .25s;
}
.or .btn-ghost:hover { background: var(--ink10); border-color: var(--ink60); }

.or .btn-danger {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .7rem 1.1rem; border-radius: var(--r);
    background: color-mix(in srgb, #ef4444 8%, transparent);
    border: 1px solid color-mix(in srgb, #ef4444 22%, transparent);
    color: #ef4444; font-family: 'DM Sans', sans-serif;
    font-size: .85rem; font-weight: 500; cursor: pointer; transition: all .25s;
    text-decoration: none;
}
.or .btn-danger:hover { background: color-mix(in srgb, #ef4444 14%, transparent); }

/* ── Card ── */
.or .card {
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: var(--rl);
    transition: border-color .25s var(--ease), background .25s;
}
.or .card:hover { border-color: var(--ink30); }

/* ────────────────────────────────
   § HERO
──────────────────────────────── */
.or-hero {
    position: relative;
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: 20px;
    padding: 4.5rem 4rem 4rem;
    overflow: hidden;
    margin-bottom: 1.5rem;
    animation: orFadeUp .9s var(--ease) both;
}
@keyframes orFadeUp { from { opacity:0; transform:translateY(28px); } to { opacity:1; transform:none; } }

.or-hero-bgword {
    position: absolute;
    font-family: 'Fraunces', serif; font-weight: 300; font-style: italic;
    font-size: clamp(8rem, 17vw, 15rem);
    color: transparent;
    -webkit-text-stroke: 1px color-mix(in srgb, var(--ink) 5%, transparent);
    line-height: 1; letter-spacing: -.05em;
    right: -2%; top: 50%; transform: translateY(-50%);
    pointer-events: none; user-select: none; white-space: nowrap;
}
.or-hero-orb {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle at 40% 40%,
        color-mix(in srgb, var(--accent) 14%, transparent),
        color-mix(in srgb, var(--accent2) 9%, transparent) 55%,
        transparent 75%);
    right: 4%; top: 50%; transform: translateY(-50%);
    pointer-events: none;
    animation: orbBreath 7s ease-in-out infinite;
}
@keyframes orbBreath { 0%,100%{transform:translateY(-50%) scale(1);}50%{transform:translateY(-54%) scale(1.07);} }

.or-hero-inner { position: relative; z-index: 10; max-width: 640px; }

.or-eyebrow {
    display: inline-flex; align-items: center; gap: .5rem;
    font-size: .75rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
    color: var(--accent); margin-bottom: 2rem;
}
.or-eyebrow::before { content: ''; width: 18px; height: 1px; background: var(--accent); }
.or-eyebrow-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent3); animation: dotPulse 2s ease-in-out infinite; }
@keyframes dotPulse { 0%,100%{opacity:1;}50%{opacity:.4;} }

.or-hero-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(2.8rem, 5vw, 4.5rem);
    font-weight: 300; line-height: 1.05; letter-spacing: -.04em;
    margin-bottom: 1.25rem;
}
.or-hero-title em { font-style: italic; color: var(--accent); }

.or-hero-sub {
    font-size: 1.02rem; color: var(--ink60); line-height: 1.75;
    margin-bottom: 2.5rem; max-width: 500px;
}

.or-hero-meta {
    display: flex; flex-wrap: wrap; gap: .625rem;
}

/* ────────────────────────────────
   § SEARCH BAR
──────────────────────────────── */
.or-search-wrap {
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: var(--rl);
    padding: 1.5rem 1.75rem;
    margin-bottom: 1.5rem;
    display: flex; gap: .875rem; align-items: center; flex-wrap: wrap;
}
.or-search-inner {
    flex: 1; min-width: 220px; position: relative;
}
.or-search-icon {
    position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
    font-size: .95rem; pointer-events: none; color: var(--ink30);
}
.or-search-input {
    width: 100%;
    padding: .8rem 1rem .8rem 2.75rem;
    background: var(--paper);
    border: 1px solid var(--ink10);
    border-radius: var(--r);
    color: var(--ink); font-family: 'DM Sans', sans-serif; font-size: .9rem;
    transition: border-color .2s;
}
.or-search-input:focus { outline: none; border-color: var(--accent); }
.or-search-input::placeholder { color: var(--ink30); }

/* ────────────────────────────────
   § SPECIALTIES GRID
──────────────────────────────── */
.or-spec-section { margin-bottom: 1.5rem; }
.or-spec-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 1px;
    background: var(--ink10);
    border: 1px solid var(--ink10);
    border-radius: var(--rl);
    overflow: hidden;
}
.or-spec-card {
    background: var(--paper);
    padding: 1.5rem 1.25rem;
    cursor: pointer; text-decoration: none;
    display: flex; flex-direction: column;
    transition: background .25s var(--ease);
}
.or-spec-card:hover { background: var(--cream); }
.or-spec-card.active { background: var(--ink); }
.or-spec-card.active .or-spec-name { color: var(--paper); }
.or-spec-card.active .or-spec-count { color: color-mix(in srgb, var(--paper) 50%, transparent); }
.or-spec-card.active .or-spec-icon-wrap { background: rgba(255,255,255,.1); }

.or-spec-icon-wrap {
    width: 40px; height: 40px; border-radius: var(--r);
    background: color-mix(in srgb, var(--accent) 10%, transparent);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; margin-bottom: 1rem;
    transition: background .25s;
}
.or-spec-card:hover:not(.active) .or-spec-icon-wrap {
    background: color-mix(in srgb, var(--accent) 16%, transparent);
}
.or-spec-name {
    font-family: 'Fraunces', serif; font-size: .88rem; font-weight: 600;
    letter-spacing: -.02em; line-height: 1.3; margin-bottom: .3rem;
    color: var(--ink);
}
.or-spec-count {
    font-size: .7rem; font-weight: 600; letter-spacing: .05em;
    text-transform: uppercase; color: var(--accent);
}

/* ────────────────────────────────
   § FILTER BAR
──────────────────────────────── */
.or-filter-bar {
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: var(--rl);
    padding: 1rem 1.5rem;
    display: flex; align-items: center; flex-wrap: wrap;
    gap: 1rem; justify-content: space-between;
    margin-bottom: 1.5rem;
}
.or-filter-group {
    display: flex; align-items: center; gap: .5rem; flex-wrap: wrap;
}
.or-filter-label {
    font-size: .7rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
    color: var(--ink30); white-space: nowrap;
}
.or-tab {
    display: inline-flex; align-items: center;
    padding: .32rem .85rem; border-radius: var(--rx);
    font-size: .78rem; font-weight: 600; text-decoration: none;
    border: 1px solid var(--ink10); background: var(--paper); color: var(--ink60);
    transition: all .2s; white-space: nowrap;
}
.or-tab:hover { border-color: var(--ink30); color: var(--ink); background: var(--warm); }
.or-tab.active {
    background: var(--accent); color: #fff; border-color: var(--accent);
    box-shadow: 0 3px 12px color-mix(in srgb, var(--accent) 35%, transparent);
}
.or-select {
    background: var(--paper); border: 1px solid var(--ink10);
    border-radius: var(--r); padding: .38rem .85rem;
    color: var(--ink); font-family: 'DM Sans', sans-serif; font-size: .82rem;
    font-weight: 600; cursor: pointer;
    transition: border-color .2s;
}
.or-select:focus { outline: none; border-color: var(--accent); }
.or-results-count {
    font-size: .8rem; color: var(--ink60); font-weight: 500; white-space: nowrap;
}

/* ────────────────────────────────
   § FORMATIONS GRID
──────────────────────────────── */
.or-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1px;
    background: var(--ink10);
    border: 1px solid var(--ink10);
    border-radius: var(--rl);
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.or-card {
    background: var(--paper);
    display: flex; flex-direction: column;
    cursor: pointer;
    transition: background .25s var(--ease);
    position: relative; overflow: hidden;
}
.or-card:hover { background: var(--cream); }

.or-card-accent-line { height: 3px; background: var(--accent); width: 0; transition: width .4s var(--ease); }
.or-card:hover .or-card-accent-line { width: 100%; }

.or-card-body { padding: 1.75rem 1.75rem 1.5rem; display: flex; flex-direction: column; flex: 1; }

.or-card-header {
    display: flex; justify-content: space-between; align-items: flex-start;
    margin-bottom: 1.25rem; gap: 1rem;
}
.or-card-left { display: flex; align-items: center; gap: .875rem; }
.or-card-icon {
    width: 46px; height: 46px; border-radius: var(--r); flex-shrink: 0;
    background: color-mix(in srgb, var(--accent) 10%, transparent);
    border: 1px solid color-mix(in srgb, var(--accent) 22%, transparent);
    display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
    transition: all .25s;
}
.or-card:hover .or-card-icon {
    background: color-mix(in srgb, var(--accent) 16%, transparent);
}
.or-card-name {
    font-family: 'Fraunces', serif; font-size: .95rem; font-weight: 600;
    letter-spacing: -.02em; line-height: 1.3; margin-bottom: .2rem; color: var(--ink);
}
.or-card-place { font-size: .73rem; color: var(--ink60); font-weight: 500; }

.or-card-score-wrap { text-align: right; flex-shrink: 0; }
.or-card-score-num {
    font-family: 'Fraunces', serif; font-size: 1.4rem; font-weight: 600;
    letter-spacing: -.04em; line-height: 1; color: var(--accent);
}
.or-card-score-label {
    font-size: .6rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; color: var(--ink30);
}

/* Score bar */
.or-bar-track { height: 3px; background: var(--ink10); border-radius: var(--rx); overflow: hidden; margin-bottom: 1rem; }
.or-bar-fill  { height: 100%; background: var(--accent); border-radius: var(--rx); transition: width .9s var(--ease); }

.or-card-desc {
    font-size: .83rem; color: var(--ink60); line-height: 1.65; flex: 1;
    margin-bottom: 1.1rem;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.or-card-tags { display: flex; flex-wrap: wrap; gap: .4rem; margin-bottom: 1.1rem; }

.or-card-footer {
    display: flex; justify-content: space-between; align-items: center;
    padding-top: 1rem; border-top: 1px solid var(--ink10); margin-top: auto;
}
.or-salary-label { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--ink30); margin-bottom: .15rem; }
.or-salary-val   { font-family: 'Fraunces', serif; font-size: .88rem; font-weight: 600; letter-spacing: -.02em; color: var(--accent); }

.or-card-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .5rem 1rem; border-radius: var(--r);
    background: var(--paper); border: 1px solid var(--ink10);
    font-family: 'DM Sans', sans-serif; font-size: .78rem; font-weight: 600;
    color: var(--ink60); cursor: pointer; transition: all .22s;
}
.or-card:hover .or-card-btn {
    background: var(--accent); color: #fff; border-color: var(--accent);
}

/* ────────────────────────────────
   § EMPTY STATE
──────────────────────────────── */
.or-empty {
    padding: 5rem 2rem; text-align: center;
    background: var(--cream); border: 1px solid var(--ink10);
    border-radius: var(--rl);
}
.or-empty-icon { font-size: 3rem; margin-bottom: 1.25rem; }
.or-empty-title { font-family: 'Fraunces', serif; font-size: 1.8rem; font-weight: 300; letter-spacing: -.03em; margin-bottom: .625rem; }
.or-empty-sub { font-size: .9rem; color: var(--ink60); line-height: 1.7; margin-bottom: 2rem; }

/* ────────────────────────────────
   § PAGINATION
──────────────────────────────── */
.or-pagination {
    display: flex; justify-content: center; gap: .375rem; flex-wrap: wrap;
    margin-top: 2rem;
}
.or-page-item {
    display: inline-flex; align-items: center; justify-content: center;
    width: 36px; height: 36px; border-radius: var(--r);
    font-size: .82rem; font-weight: 600; cursor: pointer; text-decoration: none;
    border: 1px solid var(--ink10); background: var(--paper); color: var(--ink60);
    transition: all .2s;
}
.or-page-item:hover { border-color: var(--ink30); color: var(--ink); }
.or-page-item.active { background: var(--accent); color: #fff; border-color: var(--accent); }
.or-page-item.disabled { opacity: .35; pointer-events: none; }
.or-page-wide { width: auto; padding: 0 .875rem; }

/* ────────────────────────────────
   § FICHE MODAL
──────────────────────────────── */
.or-modal-backdrop {
    display: none; position: fixed; inset: 0; z-index: 2000;
    justify-content: center; align-items: center; padding: 1rem;
    background: rgba(0,0,0,.55); backdrop-filter: blur(8px);
}
.or-modal-backdrop.open { display: flex; }
.or-modal-panel {
    position: relative; width: 100%; max-width: 800px; max-height: 90vh;
    overflow-y: auto; border-radius: 20px;
    background: var(--paper); border: 1px solid var(--ink15);
    box-shadow: 0 32px 80px rgba(0,0,0,.35);
    transform: scale(.94) translateY(20px); opacity: 0;
    transition: all .4s var(--ease);
}
.or-modal-backdrop.open .or-modal-panel { transform: none; opacity: 1; }
.or-modal-panel::-webkit-scrollbar { width: 4px; }
.or-modal-panel::-webkit-scrollbar-thumb { background: var(--ink10); border-radius: 4px; }

.or-modal-header {
    padding: 2rem 2rem 1.5rem;
    border-bottom: 1px solid var(--ink10);
    background: var(--cream);
    position: relative;
}
.or-modal-close {
    position: absolute; top: 1.25rem; right: 1.25rem;
    width: 32px; height: 32px; border-radius: var(--r);
    background: var(--ink06); border: 1px solid var(--ink10);
    color: var(--ink60); font-size: .9rem; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all .2s;
}
.or-modal-close:hover { background: var(--ink10); color: var(--ink); }

.or-modal-icon {
    width: 56px; height: 56px; border-radius: var(--r);
    background: color-mix(in srgb, var(--accent) 10%, transparent);
    border: 1px solid color-mix(in srgb, var(--accent) 22%, transparent);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; margin-bottom: 1.25rem;
}
.or-modal-title {
    font-family: 'Fraunces', serif; font-size: 1.6rem; font-weight: 600;
    letter-spacing: -.03em; margin-bottom: .35rem; color: var(--ink);
}
.or-modal-subtitle { font-size: .88rem; color: var(--ink60); }
.or-modal-tags { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: 1rem; }

/* Score row */
.or-modal-score-row {
    display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;
    padding: 1.25rem 2rem; border-bottom: 1px solid var(--ink10);
    background: var(--paper);
}
.or-modal-score-big {
    font-family: 'Fraunces', serif; font-size: 3rem; font-weight: 600;
    letter-spacing: -.05em; color: var(--accent); line-height: 1;
}
.or-modal-score-sub {
    font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em;
    color: var(--ink30);
}
.or-modal-bar-track { height: 6px; background: var(--ink10); border-radius: var(--rx); overflow: hidden; flex: 1; }
.or-modal-bar-fill  { height: 100%; background: var(--accent); border-radius: var(--rx); transition: width 1s var(--ease) .3s; }

.or-modal-body {
    padding: 2rem;
    display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;
}
.or-modal-section { }
.or-modal-section.full { grid-column: 1 / -1; }
.or-modal-section-label {
    font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em;
    color: var(--ink30); margin-bottom: .75rem;
}
.or-modal-text-box {
    background: var(--cream); border: 1px solid var(--ink10); border-radius: var(--r);
    padding: 1rem 1.125rem; font-size: .85rem; color: var(--ink60); line-height: 1.75;
}
.or-modal-list { display: flex; flex-direction: column; gap: .5rem; }
.or-modal-list-item {
    display: flex; align-items: flex-start; gap: .6rem;
    font-size: .83rem; color: var(--ink60); line-height: 1.6;
}
.or-modal-list-item::before { content: '✦'; color: var(--accent); flex-shrink: 0; font-size: .75rem; margin-top: 2px; }

.or-modal-kv {
    background: var(--cream); border: 1px solid var(--ink10); border-radius: var(--r);
    padding: 1rem 1.125rem;
}
.or-modal-kv-val {
    font-family: 'Fraunces', serif; font-size: 1.3rem; font-weight: 600;
    letter-spacing: -.03em; color: var(--accent); margin-bottom: .25rem;
}
.or-modal-kv-sub { font-size: .72rem; color: var(--ink30); }

.or-modal-footer {
    padding: 1.25rem 2rem; border-top: 1px solid var(--ink10);
    display: flex; justify-content: flex-end; gap: .75rem; flex-wrap: wrap;
    background: var(--cream);
}

/* ── Responsive ── */
@media (max-width: 900px) {
    .or-hero { padding: 3rem 2.5rem; }
    .or-hero-bgword, .or-hero-orb { display: none; }
    .or-spec-grid { grid-template-columns: repeat(3, 1fr); }
    .or-modal-body { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .or { padding: 1rem 1rem 3rem; }
    .or-hero { padding: 2.5rem 1.5rem; border-radius: var(--rl); }
    .or-grid { grid-template-columns: 1fr; }
    .or-spec-grid { grid-template-columns: repeat(2, 1fr); }
    .or-search-wrap { padding: 1rem 1.25rem; }
    .or-filter-bar { padding: .875rem 1.1rem; flex-direction: column; align-items: flex-start; }
}
</style>

<div class="or" id="orRoot">

{{-- ════ HERO ════ --}}
<section class="or-hero">
    <div class="or-hero-bgword">Formation</div>
    <div class="or-hero-orb"></div>

    <div class="or-hero-inner">
        <div class="or-eyebrow">
            <span class="or-eyebrow-dot"></span>
            Espace Orientation · Tunisie 2026
        </div>

        <h1 class="or-hero-title">
            Trouve ta<br>
            formation <em>idéale</em>
        </h1>

        <p class="or-hero-sub">
            Base de données complète des spécialités et formations disponibles en Tunisie — fiches descriptives, débouchés, conditions d'accès et salaires estimés.
        </p>

        <div class="or-hero-meta">
            <span class="pill pill-accent">📚 {{ $specialites->count() }} spécialités</span>
            <span class="pill pill-sage">🎓 {{ $formations->total() }} formations</span>
            <span class="pill pill-marine">🇹🇳 Tunisie 2026</span>
        </div>
    </div>
</section>

{{-- ════ SEARCH ════ --}}
<div class="or-search-wrap rev">
    <form method="GET" action="{{ route('student.orientation') }}" id="searchForm"
          style="display:contents;">
        <input type="hidden" name="domaine" value="{{ $domaine }}">
        <input type="hidden" name="niveau"  value="{{ $niveau }}">

        <div class="or-search-inner">
            <span class="or-search-icon">🔍</span>
            <input type="text" name="search" class="or-search-input" id="searchInput"
                   value="{{ $search }}"
                   placeholder="Formation, établissement, secteur…">
        </div>

        <button type="submit" class="btn-fill">Rechercher</button>

        @if($search || $domaine !== 'Toutes' || $niveau)
        <a href="{{ route('student.orientation') }}" class="btn-danger">✕ Réinitialiser</a>
        @endif
    </form>
</div>

{{-- ════ SPECIALTIES ════ --}}
<section class="or-spec-section rev rev-d1">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:1.1rem;gap:1rem;">
        <div>
            <p class="stag">Domaines</p>
        </div>
    </div>

    <div class="or-spec-grid">
        @php
        $colorIcons = [
            'Informatique & IA' => '💻',
            'Sciences' => '🔬',
            'Ingénierie' => '⚙️',
            'Médecine' => '🩺',
            'Économie' => '📊',
            'Design'   => '🎨',
        ];
        @endphp
        @foreach($specialites as $spec)
        <a href="{{ route('student.orientation', ['domaine' => $spec->domaine, 'search' => $search, 'niveau' => $niveau]) }}"
           class="or-spec-card {{ $domaine === $spec->domaine ? 'active' : '' }} rev rev-d{{ ($loop->index % 4) + 1 }}">
            <div class="or-spec-icon-wrap">{{ $spec->icon }}</div>
            <div class="or-spec-name">{{ $spec->nom }}</div>
            <div class="or-spec-count">{{ $spec->nb_formations }} formations</div>
        </a>
        @endforeach
    </div>
</section>

{{-- ════ FILTER BAR ════ --}}
<div class="or-filter-bar rev">
    {{-- Domain tabs --}}
    <div class="or-filter-group">
        <span class="or-filter-label">Domaine :</span>
        @foreach($domaines as $d)
        <a href="{{ route('student.orientation', ['domaine' => $d, 'search' => $search, 'niveau' => $niveau]) }}"
           class="or-tab {{ $domaine === $d ? 'active' : '' }}">{{ $d }}</a>
        @endforeach
    </div>

    {{-- Niveau + count --}}
    <div class="or-filter-group">
        <span class="or-filter-label">Niveau :</span>
        <form method="GET" action="{{ route('student.orientation') }}" style="margin:0;" id="niveauForm">
            <input type="hidden" name="domaine" value="{{ $domaine }}">
            <input type="hidden" name="search"  value="{{ $search }}">
            <select name="niveau" class="or-select" onchange="document.getElementById('niveauForm').submit()">
                <option value="">Tous niveaux</option>
                @foreach($niveaux as $n)
                <option value="{{ $n }}" {{ $niveau === $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
        </form>
        <span class="or-results-count">
            {{ $formations->total() }} résultat{{ $formations->total() > 1 ? 's' : '' }}
        </span>
    </div>
</div>

{{-- ════ FORMATIONS ════ --}}
<section>
    @if($formations->isEmpty())
    <div class="or-empty rev">
        <div class="or-empty-icon">🔍</div>
        <h3 class="or-empty-title">Aucune formation trouvée</h3>
        <p class="or-empty-sub">Essayez d'élargir votre recherche ou de changer de filtre.</p>
        <a href="{{ route('student.orientation') }}" class="btn-fill">Voir toutes les formations</a>
    </div>
    @else

    <div class="or-grid">
        @php
        $colorMap = [
            'indigo' => 'var(--accent2)',
            'cyan'   => 'var(--accent3)',
            'violet' => 'var(--accent)',
            'green'  => 'var(--accent3)',
            'amber'  => 'var(--gold)',
        ];
        $niveauPill = [
            'Licence'    => 'pill-sage',
            'Master'     => 'pill-marine',
            'Ingénierie' => 'pill-accent',
            'Doctorat'   => 'pill-gold',
        ];
        @endphp

        @foreach($formations as $formation)
        @php
        $spec  = $formation->specialite;
        $sc    = $colorMap[$spec->color ?? 'indigo'] ?? 'var(--accent)';
        $np    = $niveauPill[$formation->niveau] ?? 'pill-ink';
        @endphp
        <div class="or-card btn-fiche rev rev-d{{ ($loop->index % 3) + 1 }}" data-id="{{ $formation->id }}">
            <div class="or-card-accent-line"></div>
            <div class="or-card-body">

                <div class="or-card-header">
                    <div class="or-card-left">
                        <div class="or-card-icon">{{ $formation->icon }}</div>
                        <div>
                            <div class="or-card-name">{{ $formation->nom }}</div>
                            <div class="or-card-place">{{ $formation->etablissement }} · {{ $formation->ville }}</div>
                        </div>
                    </div>
                    <div class="or-card-score-wrap">
                        <div class="or-card-score-num">{{ $formation->score_matching }}%</div>
                        <div class="or-card-score-label">match</div>
                    </div>
                </div>

                <div class="or-bar-track">
                    <div class="or-bar-fill match-bar-fill" style="width:{{ $formation->score_matching }}%;"></div>
                </div>

                <p class="or-card-desc">{{ $formation->description }}</p>

                <div class="or-card-tags">
                    <span class="pill {{ $np }}">{{ $formation->niveau }}</span>
                    <span class="pill pill-ink">⏱ {{ $formation->duree }}</span>
                    <span class="pill pill-ink">{{ $spec->icon }} {{ $spec->domaine }}</span>
                </div>

                <div class="or-card-footer">
                    <div>
                        <div class="or-salary-label">Salaire estimé</div>
                        <div class="or-salary-val">{{ $formation->salaire_min }} – {{ $formation->salaire_max }}</div>
                    </div>
                    <button class="or-card-btn" data-id="{{ $formation->id }}">
                        📋 Voir la fiche →
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($formations->hasPages())
    <div class="or-pagination">
        @if($formations->onFirstPage())
            <span class="or-page-item or-page-wide disabled">← Préc.</span>
        @else
            <a href="{{ $formations->previousPageUrl() }}" class="or-page-item or-page-wide">← Préc.</a>
        @endif

        @foreach($formations->getUrlRange(1, $formations->lastPage()) as $page => $url)
            @if($page == $formations->currentPage())
                <span class="or-page-item active">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="or-page-item">{{ $page }}</a>
            @endif
        @endforeach

        @if($formations->hasMorePages())
            <a href="{{ $formations->nextPageUrl() }}" class="or-page-item or-page-wide">Suiv. →</a>
        @else
            <span class="or-page-item or-page-wide disabled">Suiv. →</span>
        @endif
    </div>
    @endif

    @endif
</section>

</div>{{-- /or --}}

{{-- ════ FICHE MODAL ════ --}}
<div class="or-modal-backdrop" id="ficheModal">
    <div class="or-modal-panel" id="fichePanel">
        <div id="ficheLoading" style="padding:4rem;text-align:center;display:none;">
            <div style="font-size:2.5rem;margin-bottom:1rem;animation:orFadeUp 1s ease-in-out infinite;">⏳</div>
            <div style="color:var(--ink60);font-weight:500;">Chargement de la fiche…</div>
        </div>
        <div id="ficheContent"></div>
    </div>
</div>

{{-- Hidden formation data (JSON) --}}
@foreach($formations as $formation)
@php $spec = $formation->specialite; $c = $colorMap[$spec->color ?? 'indigo'] ?? 'var(--accent)'; @endphp
<script type="application/json" id="fiche-data-{{ $formation->id }}">{!! json_encode([
    'id'                => $formation->id,
    'nom'               => $formation->nom,
    'etablissement'     => $formation->etablissement,
    'ville'             => $formation->ville,
    'duree'             => $formation->duree,
    'niveau'            => $formation->niveau,
    'description'       => $formation->description,
    'debouches'         => $formation->debouches,
    'conditions_acces'  => $formation->conditions_acces,
    'salaire_min'       => $formation->salaire_min,
    'salaire_max'       => $formation->salaire_max,
    'secteur'           => $formation->secteur,
    'icon'              => $formation->icon,
    'score_matching'    => $formation->score_matching,
    'specialite_nom'    => $spec->nom,
    'specialite_icon'   => $spec->icon,
    'specialite_domaine'=> $spec->domaine,
    'niveau_pill'       => $np ?? 'pill-ink',
]) !!}</script>
@endforeach

<script>
(function () {
    /* ── Scroll reveal ── */
    const revEls = document.querySelectorAll('#orRoot .rev');
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

    /* ── Fiche modal ── */
    const modal   = document.getElementById('ficheModal');
    const panel   = document.getElementById('fichePanel');
    const content = document.getElementById('ficheContent');
    const loading = document.getElementById('ficheLoading');

    const openModal  = () => { modal.classList.add('open'); document.body.style.overflow = 'hidden'; };
    const closeModal = () => {
        modal.classList.remove('open');
        setTimeout(() => { content.innerHTML = ''; document.body.style.overflow = ''; }, 380);
    };

    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    function niveauPillClass(n) {
        return { Licence:'pill-sage', Master:'pill-marine', 'Ingénierie':'pill-accent', Doctorat:'pill-gold' }[n] || 'pill-ink';
    }

    function renderFiche(d) {
        const circ   = 2 * Math.PI * 40;
        const offset = circ * (1 - d.score_matching / 100);

        return `
        <div class="or-modal-header">
            <button class="or-modal-close" id="ficheClose">✕</button>
            <div style="display:flex;flex-wrap:wrap;gap:1rem;align-items:flex-start;">
                <div class="or-modal-icon">${d.icon}</div>
                <div style="flex:1;min-width:200px;">
                    <div class="or-modal-tags" style="margin:0 0 .75rem;">
                        <span class="pill ${niveauPillClass(d.niveau)}">${d.niveau}</span>
                        <span class="pill pill-ink">${d.specialite_icon} ${d.specialite_domaine}</span>
                        <span class="pill pill-ink">⏱ ${d.duree}</span>
                    </div>
                    <div class="or-modal-title">${d.nom}</div>
                    <div class="or-modal-subtitle">🏛️ ${d.etablissement} — ${d.ville}</div>
                </div>
            </div>
        </div>

        <div class="or-modal-score-row">
            <div>
                <div class="or-modal-score-big">${d.score_matching}%</div>
                <div class="or-modal-score-sub">Compatibilité IA</div>
            </div>
            <div class="or-modal-bar-track">
                <div class="or-modal-bar-fill" style="width:${d.score_matching}%;"></div>
            </div>
            <svg width="88" height="88" style="flex-shrink:0;">
                <defs>
                    <linearGradient id="ringG" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#d4622a"/>
                        <stop offset="100%" stop-color="#1a4f6e"/>
                    </linearGradient>
                </defs>
                <g transform="rotate(-90 44 44)">
                    <circle cx="44" cy="44" r="40" fill="none" stroke-width="6"
                            stroke="rgba(11,12,16,.07)"/>
                    <circle cx="44" cy="44" r="40" fill="none"
                            stroke="url(#ringG)" stroke-width="6" stroke-linecap="round"
                            stroke-dasharray="${circ}"
                            stroke-dashoffset="${offset}"
                            style="transition:stroke-dashoffset 1.2s cubic-bezier(.16,1,.3,1) .3s;"/>
                </g>
            </svg>
        </div>

        <div class="or-modal-body">
            <div class="or-modal-section full">
                <div class="or-modal-section-label">📝 Description</div>
                <div class="or-modal-text-box">${d.description}</div>
            </div>
            <div class="or-modal-section">
                <div class="or-modal-section-label">🚀 Débouchés</div>
                <div class="or-modal-text-box">
                    <div class="or-modal-list">
                        ${d.debouches.split(',').map(s => `<div class="or-modal-list-item">${s.trim()}</div>`).join('')}
                    </div>
                </div>
            </div>
            <div class="or-modal-section">
                <div class="or-modal-section-label">📋 Conditions d'accès</div>
                <div class="or-modal-text-box">
                    <div class="or-modal-list">
                        ${d.conditions_acces.split('.').filter(s=>s.trim()).map(s => `<div class="or-modal-list-item">${s.trim()}</div>`).join('')}
                    </div>
                </div>
            </div>
            <div class="or-modal-section">
                <div class="or-modal-section-label">💰 Salaire estimé</div>
                <div class="or-modal-kv">
                    <div class="or-modal-kv-val">${d.salaire_min} → ${d.salaire_max}</div>
                    <div class="or-modal-kv-sub">/mois · après quelques années d'expérience</div>
                </div>
            </div>
            <div class="or-modal-section">
                <div class="or-modal-section-label">🏢 Secteur</div>
                <div class="or-modal-kv">
                    <div class="or-modal-kv-val" style="font-size:1rem;">${d.secteur}</div>
                    <div class="or-modal-kv-sub">Spécialité : ${d.specialite_nom}</div>
                </div>
            </div>
        </div>

        <div class="or-modal-footer">
            <button class="btn-ghost" id="ficheCloseBtn">← Retour</button>
            <button class="btn-fill" onclick="alert('⭐ Fonctionnalité disponible bientôt !')">
                ⭐ Sauvegarder
            </button>
        </div>`;
    }

    function attachCard(el) {
        el.addEventListener('click', function (e) {
            e.stopPropagation();
            const id   = this.dataset.id;
            const raw  = document.getElementById('fiche-data-' + id);
            if (!raw) return;
            try {
                const data = JSON.parse(raw.textContent);
                content.innerHTML = renderFiche(data);
                /* animate modal bar */
                setTimeout(() => {
                    const fill = panel.querySelector('.or-modal-bar-fill');
                    if (fill) { const w = fill.style.width; fill.style.width='0'; setTimeout(()=>{fill.style.width=w;},80); }
                }, 50);
                openModal();
                document.getElementById('ficheClose')?.addEventListener('click', closeModal);
                document.getElementById('ficheCloseBtn')?.addEventListener('click', closeModal);
            } catch {
                content.innerHTML = '<div style="padding:2rem;text-align:center;color:#ef4444;">Erreur de chargement.</div>';
                openModal();
            }
        });
    }

    document.querySelectorAll('.btn-fiche, .or-card').forEach(attachCard);

})();
</script>

@endsection
