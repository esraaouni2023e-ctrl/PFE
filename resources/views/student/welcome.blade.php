@extends('layouts.student')

@section('title', 'Bienvenue')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;1,9..144,300;1,9..144,400&display=swap" rel="stylesheet">

<style>
/* ── TOKEN SYSTEM (aligned with CapAvenir identity) ── */
.wlc-root {
    --ink:      #0b0c10;
    --paper:    #f7f5f0;
    --cream:    #ede9e1;
    --warm:     #e8e1d4;
    --accent:   #d4622a;   /* terracotta — primary */
    --accent2:  #1a4f6e;   /* marine     — secondary */
    --accent3:  #4a7c59;   /* sage        — success/check */
    --gold:     #c8973a;
    --ink60:    rgba(11,12,16,.6);
    --ink30:    rgba(11,12,16,.3);
    --ink10:    rgba(11,12,16,.1);
    --ink06:    rgba(11,12,16,.06);
    --r:        6px;
    --rl:       16px;
    --rx:       999px;
    --ease:     cubic-bezier(.16,1,.3,1);

    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    background: var(--paper);
    padding: 2rem 3rem 4rem;
}

/* dark-mode support (inherits from parent layout) */
@media (prefers-color-scheme: dark) {
    .wlc-root {
        --ink:    #f0ede6;
        --paper:  #10100d;
        --cream:  #18170f;
        --warm:   #1f1e14;
        --ink60:  rgba(240,237,230,.6);
        --ink30:  rgba(240,237,230,.3);
        --ink10:  rgba(240,237,230,.08);
        --ink06:  rgba(240,237,230,.04);
    }
}
[data-theme="light"] .wlc-root {
    --ink:    #0b0c10;
    --paper:  #f7f5f0;
    --cream:  #ede9e1;
    --warm:   #e8e1d4;
    --ink60:  rgba(11,12,16,.6);
    --ink30:  rgba(11,12,16,.3);
    --ink10:  rgba(11,12,16,.1);
    --ink06:  rgba(11,12,16,.06);
}
[data-theme="dark"] .wlc-root {
    --ink:    #f0ede6;
    --paper:  #10100d;
    --cream:  #18170f;
    --warm:   #1f1e14;
    --ink60:  rgba(240,237,230,.6);
    --ink30:  rgba(240,237,230,.3);
    --ink10:  rgba(240,237,230,.08);
    --ink06:  rgba(240,237,230,.04);
}

/* ── GLOBAL RESETS SCOPED ── */
.wlc-root *, .wlc-root *::before, .wlc-root *::after { box-sizing: border-box; margin: 0; padding: 0; }
.wlc-root a { color: inherit; text-decoration: none; }

/* ── REVEAL ANIMATIONS ── */
.wlc-root .rev {
    opacity: 0;
    transform: translateY(28px);
    transition: opacity .8s var(--ease), transform .8s var(--ease);
}
.wlc-root .rev.vis { opacity: 1; transform: none; }
.wlc-root .rev-d1 { transition-delay: .1s; }
.wlc-root .rev-d2 { transition-delay: .2s; }
.wlc-root .rev-d3 { transition-delay: .3s; }
.wlc-root .rev-d4 { transition-delay: .4s; }

/* ════════════════════════════════════════════
   HERO — editorial, asymmetric
════════════════════════════════════════════ */
.wlc-hero {
    position: relative;
    border: 1px solid var(--ink10);
    border-radius: 20px;
    background: var(--cream);
    padding: 5rem 4rem 4.5rem;
    overflow: hidden;
    margin-bottom: 1.5rem;
    animation: wlcFadeUp .9s var(--ease) both;
}
@keyframes wlcFadeUp {
    from { opacity: 0; transform: translateY(32px); }
    to   { opacity: 1; transform: none; }
}

/* Decorative editorial word (background) */
.wlc-hero-bg-word {
    position: absolute;
    font-family: 'Fraunces', serif;
    font-weight: 300;
    font-style: italic;
    font-size: clamp(8rem, 18vw, 16rem);
    color: transparent;
    -webkit-text-stroke: 1px color-mix(in srgb, var(--ink) 6%, transparent);
    line-height: 1;
    letter-spacing: -.05em;
    right: -2%;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    user-select: none;
    white-space: nowrap;
}

/* Decorative circle */
.wlc-hero-orb {
    position: absolute;
    width: 420px; height: 420px;
    border-radius: 50%;
    background: radial-gradient(circle at 40% 40%,
        color-mix(in srgb, var(--accent) 16%, transparent),
        color-mix(in srgb, var(--accent2) 10%, transparent) 50%,
        transparent 75%);
    right: 5%; top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    animation: orbBreath 7s ease-in-out infinite;
}
@keyframes orbBreath {
    0%,100% { transform: translateY(-50%) scale(1); }
    50%      { transform: translateY(-54%) scale(1.07); }
}

/* Hero inner content */
.wlc-hero-inner {
    position: relative;
    z-index: 10;
    max-width: 600px;
}

.wlc-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    font-size: .75rem;
    font-weight: 600;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--accent);
    margin-bottom: 2rem;
}
.wlc-eyebrow::before {
    content: '';
    width: 18px; height: 1px;
    background: var(--accent);
}
.eyebrow-live {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: var(--accent3);
    animation: livePulse 2s ease-in-out infinite;
}
@keyframes livePulse {
    0%,100% { opacity: 1; box-shadow: 0 0 0 0 color-mix(in srgb, var(--accent3) 50%, transparent); }
    50%      { opacity: .6; box-shadow: 0 0 0 6px transparent; }
}

.wlc-hero-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(2.8rem, 5.5vw, 5rem);
    font-weight: 300;
    line-height: 1.05;
    letter-spacing: -.04em;
    margin-bottom: 1.5rem;
}
.wlc-hero-title em  { font-style: italic; color: var(--accent); }
.wlc-hero-title strong { font-weight: 600; }

.wlc-hero-sub {
    font-size: 1.05rem;
    color: var(--ink60);
    line-height: 1.75;
    margin-bottom: 2.75rem;
    max-width: 480px;
}

/* CTA row */
.wlc-ctas {
    display: flex;
    gap: .875rem;
    flex-wrap: wrap;
}
.wlc-btn-main {
    display: inline-flex;
    align-items: center;
    gap: .6rem;
    padding: .9rem 1.875rem;
    border-radius: var(--r);
    background: var(--accent);
    color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: .95rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 6px 24px color-mix(in srgb, var(--accent) 38%, transparent);
    transition: all .3s var(--ease);
    position: relative;
    overflow: hidden;
}
.wlc-btn-main::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,.14), transparent);
    opacity: 0;
    transition: .3s;
}
.wlc-btn-main:hover { transform: translateY(-3px); box-shadow: 0 14px 40px color-mix(in srgb, var(--accent) 45%, transparent); }
.wlc-btn-main:hover::after { opacity: 1; }

.wlc-btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .9rem 1.75rem;
    border-radius: var(--r);
    background: transparent;
    border: 1px solid var(--ink30);
    color: var(--ink);
    font-family: 'DM Sans', sans-serif;
    font-size: .95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all .3s;
}
.wlc-btn-ghost:hover { background: var(--ink10); border-color: var(--ink60); }

/* ════════════════════════════════════════════
   STEP CARDS — borderless grid cells
════════════════════════════════════════════ */
.wlc-steps-section {
    margin-bottom: 1.5rem;
}
.wlc-steps-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 1rem;
    padding: 0 .5rem;
    margin-bottom: 1.5rem;
}
.wlc-section-tag {
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--accent);
    display: flex;
    align-items: center;
    gap: .5rem;
}
.wlc-section-tag::before { content: ''; width: 18px; height: 1px; background: var(--accent); }
.wlc-section-heading {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.6rem, 3vw, 2.4rem);
    font-weight: 300;
    letter-spacing: -.03em;
    line-height: 1.1;
}
.wlc-section-heading em { font-style: italic; color: var(--accent); }

/* Grid — joined cells */
.wlc-steps-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    background: var(--ink10);
    border: 1px solid var(--ink10);
    border-radius: var(--rl);
    overflow: hidden;
    gap: 1px;
}
.wlc-step {
    background: var(--paper);
    padding: 2.75rem 2.25rem;
    position: relative;
    transition: background .3s var(--ease);
    cursor: default;
}
.wlc-step:hover { background: var(--cream); }

/* Featured (middle) step */
.wlc-step.featured {
    background: var(--ink);
}
.wlc-step.featured .wlc-step-num   { color: color-mix(in srgb, var(--paper) 15%, transparent); -webkit-text-stroke-color: color-mix(in srgb, var(--paper) 15%, transparent); }
.wlc-step.featured .wlc-step-icon-wrap { background: rgba(255,255,255,.12); }
.wlc-step.featured .wlc-step-icon-wrap span { filter: none; }
.wlc-step.featured .wlc-step-title { color: var(--paper); }
.wlc-step.featured .wlc-step-desc  { color: color-mix(in srgb, var(--paper) 58%, transparent); }
.wlc-step.featured .wlc-step-badge { background: var(--gold); color: var(--ink); }
.wlc-step.featured:hover { background: color-mix(in srgb, var(--ink) 92%, var(--accent)); }

/* Decorative step number */
.wlc-step-num {
    font-family: 'Fraunces', serif;
    font-size: 4rem;
    font-weight: 300;
    color: transparent;
    -webkit-text-stroke: 1px var(--ink10);
    letter-spacing: -.05em;
    line-height: 1;
    margin-bottom: 1.5rem;
    display: block;
    transition: color .3s, -webkit-text-stroke-color .3s;
}
.wlc-step:hover:not(.featured) .wlc-step-num {
    color: transparent;
    -webkit-text-stroke-color: color-mix(in srgb, var(--accent) 30%, transparent);
}

/* Icon */
.wlc-step-icon-wrap {
    width: 48px; height: 48px;
    border-radius: var(--r);
    background: color-mix(in srgb, var(--accent) 10%, transparent);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.35rem;
    margin-bottom: 1.5rem;
    transition: background .3s;
}
.wlc-step:hover:not(.featured) .wlc-step-icon-wrap {
    background: color-mix(in srgb, var(--accent) 16%, transparent);
}

.wlc-step-title {
    font-family: 'Fraunces', serif;
    font-size: 1.25rem;
    font-weight: 600;
    letter-spacing: -.025em;
    margin-bottom: .625rem;
    color: var(--ink);
}
.wlc-step-desc {
    font-size: .9rem;
    color: var(--ink60);
    line-height: 1.7;
    margin-bottom: 1.25rem;
}
.wlc-step-badge {
    display: inline-block;
    font-size: .7rem;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: .25rem .75rem;
    border-radius: var(--rx);
    background: color-mix(in srgb, var(--accent3) 12%, transparent);
    color: var(--accent3);
}

/* ════════════════════════════════════════════
   QUICK LINKS — 2×2 action grid
════════════════════════════════════════════ */
.wlc-actions-section {
    margin-bottom: 1.5rem;
}
.wlc-actions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1px;
    background: var(--ink10);
    border: 1px solid var(--ink10);
    border-radius: var(--rl);
    overflow: hidden;
}
.wlc-action-card {
    background: var(--paper);
    padding: 2.25rem 2rem;
    display: flex;
    align-items: flex-start;
    gap: 1.25rem;
    transition: background .3s var(--ease), border-color .3s;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}
.wlc-action-card:hover { background: var(--cream); }
.wlc-action-icon {
    width: 52px; height: 52px;
    border-radius: var(--rl);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
    border: 1px solid var(--ink10);
    background: var(--cream);
    transition: all .3s;
}
.wlc-action-card:hover .wlc-action-icon {
    background: color-mix(in srgb, var(--accent) 10%, transparent);
    border-color: color-mix(in srgb, var(--accent) 30%, transparent);
}
.wlc-action-body {}
.wlc-action-title {
    font-family: 'Fraunces', serif;
    font-size: 1.1rem;
    font-weight: 600;
    letter-spacing: -.02em;
    margin-bottom: .375rem;
}
.wlc-action-desc {
    font-size: .88rem;
    color: var(--ink60);
    line-height: 1.6;
}
.wlc-action-arrow {
    margin-left: auto;
    font-size: 1.1rem;
    color: var(--ink30);
    transition: all .3s;
    align-self: center;
    flex-shrink: 0;
}
.wlc-action-card:hover .wlc-action-arrow {
    color: var(--accent);
    transform: translateX(4px);
}

/* ════════════════════════════════════════════
   CTA FOOTER — marine background
════════════════════════════════════════════ */
.wlc-footer-cta {
    position: relative;
    border-radius: var(--rl);
    background: var(--accent2);
    overflow: hidden;
    padding: 4.5rem 4rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    flex-wrap: wrap;
}
/* Grain texture */
.wlc-footer-cta::before {
    content: '';
    position: absolute; inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.06'/%3E%3C/svg%3E");
    opacity: .7;
    pointer-events: none;
}
/* Decorative orb inside CTA */
.wlc-footer-cta::after {
    content: '';
    position: absolute;
    width: 420px; height: 420px;
    border-radius: 50%;
    background: radial-gradient(circle, color-mix(in srgb, var(--accent) 30%, transparent), transparent 70%);
    right: -8%; top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}
.wlc-cta-body { position: relative; z-index: 2; }
.wlc-cta-label {
    display: inline-block;
    margin-bottom: 1.25rem;
    padding: .3rem .875rem;
    border-radius: var(--rx);
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.2);
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: rgba(255,255,255,.7);
}
.wlc-cta-title {
    font-family: 'Fraunces', serif;
    font-size: clamp(1.8rem, 3.5vw, 2.75rem);
    font-weight: 300;
    line-height: 1.1;
    letter-spacing: -.04em;
    color: #fff;
    margin-bottom: .75rem;
}
.wlc-cta-title em { font-style: italic; }
.wlc-cta-sub {
    font-size: .95rem;
    color: rgba(255,255,255,.58);
    line-height: 1.65;
}
.wlc-cta-action { position: relative; z-index: 2; flex-shrink: 0; }
.wlc-btn-cta {
    display: inline-flex;
    align-items: center;
    gap: .6rem;
    padding: 1.05rem 2.25rem;
    border-radius: var(--r);
    background: #fff;
    color: var(--accent2);
    font-family: 'DM Sans', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 8px 32px rgba(0,0,0,.2);
    transition: all .3s var(--ease);
    white-space: nowrap;
}
.wlc-btn-cta:hover { transform: translateY(-3px); box-shadow: 0 18px 50px rgba(0,0,0,.25); }

.wlc-cta-trust {
    width: 100%;
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    margin-top: 2.5rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255,255,255,.1);
    position: relative;
    z-index: 2;
}
.wlc-cta-trust-item {
    display: flex;
    align-items: center;
    gap: .4rem;
    font-size: .82rem;
    color: rgba(255,255,255,.5);
}
.wlc-cta-trust-item strong { color: rgba(255,255,255,.8); font-weight: 500; }

/* ── RESPONSIVE ── */
@media (max-width: 900px) {
    .wlc-hero { padding: 3.5rem 2.5rem 3rem; }
    .wlc-hero-bg-word, .wlc-hero-orb { display: none; }
    .wlc-steps-grid { grid-template-columns: 1fr; }
    .wlc-actions-grid { grid-template-columns: 1fr; }
    .wlc-footer-cta { padding: 3rem 2.5rem; flex-direction: column; }
    .wlc-footer-cta::after { display: none; }
    .wlc-steps-header { flex-direction: column; align-items: flex-start; gap: .5rem; }
}
@media (max-width: 600px) {
    .wlc-root { padding: 1rem 1rem 3rem; }
    .wlc-hero { padding: 2.5rem 1.5rem 2.5rem; border-radius: var(--rl); }
    .wlc-hero-title { font-size: 2.4rem; }
    .wlc-ctas { flex-direction: column; }
    .wlc-btn-main, .wlc-btn-ghost { justify-content: center; }
    .wlc-footer-cta { padding: 2.5rem 1.5rem; border-radius: var(--rl); }
}
</style>

<div class="wlc-root" id="wlcRoot">

    {{-- ════ HERO ════ --}}
    <section class="wlc-hero">
        <div class="wlc-hero-bg-word">Futur</div>
        <div class="wlc-hero-orb"></div>

        <div class="wlc-hero-inner">
            <div class="wlc-eyebrow">
                <span class="eyebrow-live"></span>
                Orientation IA · Tunisie 2026
            </div>

            <h1 class="wlc-hero-title">
                Prêt à révéler<br>
                ton <em>vrai</em> potentiel ?
            </h1>

            <p class="wlc-hero-sub">
                Oublie les tests classiques. CapAvenir mappe tes aptitudes, tes valeurs et ta personnalité pour te proposer les parcours qui te correspondent <strong>vraiment</strong>.
            </p>

            <div class="wlc-ctas">
                <a href="{{ route('student.dashboard') }}" class="wlc-btn-main">
                    Explorer mon tableau de bord <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3' /></svg>
                </a>
                <a href="#steps" class="wlc-btn-ghost">
                    Comment ça marche
                </a>
            </div>
        </div>
    </section>

    {{-- ════ STEPS ════ --}}
    <section class="wlc-steps-section rev" id="steps">
        <div class="wlc-steps-header">
            <div>
                <p class="wlc-section-tag">En 3 étapes</p>
                <h2 class="wlc-section-heading">De l'analyse au choix <em>éclairé</em></h2>
            </div>
        </div>

        <div class="wlc-steps-grid">

            {{-- Step 01 --}}
            <div class="wlc-step rev rev-d1">
                <span class="wlc-step-num">01</span>
                <div class="wlc-step-icon-wrap"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='var(--accent)' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M9.75 3.104v17.792m0-17.792c-1.33 0-2.603.33-3.737.921m3.737-.921c1.33 0 2.603.33 3.737.921m-7.474 0c-1.134.591-2.073 1.488-2.686 2.563m2.686-2.563C5.104 5.313 5.25 6.398 5.25 7.5c0 1.102-.146 2.187-.437 3.187m7.474-7.5c1.134.591 2.073 1.488 2.686 2.563m-2.686-2.563c.291.956.437 2.041.437 3.143 0 1.102-.146 2.187-.437 3.187m0 0c-.29.983-.63 1.923-1.01 2.809m-10.148-2.81c.38.886.72 1.826 1.01 2.81m10.148-2.81c-.426 1.256-.832 2.532-1.217 3.824m-10.148-3.824c.385 1.292.79 2.568 1.217 3.824m10.148-3.824c-.426 1.256-.832 2.532-1.217 3.824m-10.148-3.824c.385 1.292.79 2.568 1.217 3.824m10.148-3.824a15.753 15.753 0 01-1.217 3.824m-10.148-3.824a15.753 15.753 0 001.217 3.824m10.148-3.824c.426-1.256.832-2.532 1.217-3.824m-10.148 3.824a15.753 15.753 0 01-1.217-3.824M12 21.75V15' /></svg></div>
                <h3 class="wlc-step-title">Analyse profonde</h3>
                <p class="wlc-step-desc">
                    On explore tes aptitudes, tes intérêts et ton style d'intelligence via un quiz adaptatif — engageant, jamais ennuyeux.
                </p>
                <span class="wlc-step-badge">12 minutes</span>
            </div>

            {{-- Step 02 — featured --}}
            <div class="wlc-step featured rev rev-d2">
                <span class="wlc-step-num">02</span>
                <div class="wlc-step-icon-wrap"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='var(--accent2)' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M20.25 12c0 4.556-3.694 8.25-8.25 8.25S3.75 16.556 3.75 12 7.444 3.75 12 3.75 20.25 7.444 20.25 12zM12 3c.392 0 .771.027 1.143.08M12 3c-.392 0-.771.027-1.143.08M12 3v3.25m0 14.75c.392 0 .771-.027 1.143-.08M12 21c-.392 0-.771-.027-1.143-.08M12 21v-3.25m9.143-5.82c.027-.372.04-.751.04-1.143 0-.392-.013-.771-.04-1.143M21 12h-3.25m-14.75 0c-.027.372-.04.751-.04 1.143 0 .392.013.771.04 1.143M3 12h3.25m14.286-4.5a11.948 11.948 0 01-3.143 3.143m-14.286-3.143a11.948 11.948 0 003.143 3.143m14.286 11.25a11.948 11.948 0 00-3.143-3.143m-14.286 3.143a11.948 11.948 0 013.143-3.143' /></svg></div>
                <h3 class="wlc-step-title">Mapping IA</h3>
                <p class="wlc-step-desc">
                    L'IA génère ton profil personnalisé et te suggère les parcours qui te correspondent à 90 %+, adaptés au système tunisien.
                </p>
                <span class="wlc-step-badge">Résultats instantanés</span>
            </div>

            {{-- Step 03 --}}
            <div class="wlc-step rev rev-d3">
                <span class="wlc-step-num">03</span>
                <div class="wlc-step-icon-wrap"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='var(--accent3)' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M15.59 14.37a6 6 0 01-5.84 7.38 4.75 4.75 0 01-4.51-3.46 8.97 8.97 0 005.54-3.92zM9 15.165V15.303a3 3 0 01-3 3V15.303a3 3 0 013-3z' /></svg></div>
                <h3 class="wlc-step-title">Construction</h3>
                <p class="wlc-step-desc">
                    Accède aux fiches formations, simule ton futur et construis ton propre chemin — avec l'appui d'un conseiller si besoin.
                </p>
                <span class="wlc-step-badge">À ton rythme</span>
            </div>

        </div>
    </section>

    {{-- ════ QUICK ACTIONS ════ --}}
    <section class="wlc-actions-section rev rev-d1">
        <div class="wlc-steps-header" style="margin-bottom: 1.5rem;">
            <div>
                <p class="wlc-section-tag">Par où commencer ?</p>
                <h2 class="wlc-section-heading">Tes prochaines <em>actions</em></h2>
            </div>
        </div>

        <div class="wlc-actions-grid">

            <a href="{{ route('student.test') ?? '#' }}" class="wlc-action-card rev rev-d1">
                <div class="wlc-action-icon"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='var(--accent)' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M9.75 3.104v17.792m0-17.792c-1.33 0-2.603.33-3.737.921m3.737-.921c1.33 0 2.603.33 3.737.921m-7.474 0c-1.134.591-2.073 1.488-2.686 2.563m2.686-2.563C5.104 5.313 5.25 6.398 5.25 7.5c0 1.102-.146 2.187-.437 3.187m7.474-7.5c1.134.591 2.073 1.488 2.686 2.563m-2.686-2.563c.291.956.437 2.041.437 3.143 0 1.102-.146 2.187-.437 3.187m0 0c-.29.983-.63 1.923-1.01 2.809m-10.148-2.81c.38.886.72 1.826 1.01 2.81m10.148-2.81c-.426 1.256-.832 2.532-1.217 3.824m-10.148-3.824c.385 1.292.79 2.568 1.217 3.824m10.148-3.824c-.426 1.256-.832 2.532-1.217 3.824m-10.148-3.824c.385 1.292.79 2.568 1.217 3.824m10.148-3.824a15.753 15.753 0 01-1.217 3.824m-10.148-3.824a15.753 15.753 0 001.217 3.824m10.148-3.824c.426-1.256.832-2.532 1.217-3.824m-10.148 3.824a15.753 15.753 0 01-1.217-3.824M12 21.75V15' /></svg></div>
                <div class="wlc-action-body">
                    <div class="wlc-action-title">Passer le test IA</div>
                    <p class="wlc-action-desc">Commence le quiz adaptatif pour obtenir ton profil complet en 12 minutes.</p>
                </div>
                <span class="wlc-action-arrow"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3' /></svg></span>
            </a>

            <a href="{{ route('student.profile') ?? '#' }}" class="wlc-action-card rev rev-d2">
                <div class="wlc-action-icon"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='var(--accent2)' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M20.25 12c0 4.556-3.694 8.25-8.25 8.25S3.75 16.556 3.75 12 7.444 3.75 12 3.75 20.25 7.444 20.25 12zM12 3c.392 0 .771.027 1.143.08M12 3c-.392 0-.771.027-1.143.08M12 3v3.25m0 14.75c.392 0 .771-.027 1.143-.08M12 21c-.392 0-.771-.027-1.143-.08M12 21v-3.25m9.143-5.82c.027-.372.04-.751.04-1.143 0-.392-.013-.771-.04-1.143M21 12h-3.25m-14.75 0c-.027.372-.04.751-.04 1.143 0 .392.013.771.04 1.143M3 12h3.25m14.286-4.5a11.948 11.948 0 01-3.143 3.143m-14.286-3.143a11.948 11.948 0 003.143 3.143m14.286 11.25a11.948 11.948 0 00-3.143-3.143m-14.286 3.143a11.948 11.948 0 013.143-3.143' /></svg></div>
                <div class="wlc-action-body">
                    <div class="wlc-action-title">Voir mon profil IA</div>
                    <p class="wlc-action-desc">Découvre tes aptitudes, tes valeurs et tes formations recommandées.</p>
                </div>
                <span class="wlc-action-arrow"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3' /></svg></span>
            </a>

            <a href="{{ route('student.simulator') ?? '#' }}" class="wlc-action-card rev rev-d3">
                <div class="wlc-action-icon"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='var(--gold)' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 21l-8.25-1.875L1.5 12l2.25-7.125L12 3l8.25 1.875L22.5 12l-2.25 7.125L12 21z' /></svg></div>
                <div class="wlc-action-body">
                    <div class="wlc-action-title">Simulateur de vie</div>
                    <p class="wlc-action-desc">Visualise ton futur selon chaque filière : métiers, salaires, évolution.</p>
                </div>
                <span class="wlc-action-arrow"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3' /></svg></span>
            </a>

            <a href="{{ route('student.advisor') ?? '#' }}" class="wlc-action-card rev rev-d4">
                <div class="wlc-action-icon"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='var(--accent)' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M15.191 6.062h-6.382c-.876 0-1.591.715-1.591 1.591v8.694c0 .876.715 1.591 1.591 1.591h6.382c.876 0 1.591-.715 1.591-1.591V7.653c0-.876-.715-1.591-1.591-1.591zM12 15.75h.008v.008H12v-.008z' /></svg></div>
                <div class="wlc-action-body">
                    <div class="wlc-action-title">Parler à un conseiller</div>
                    <p class="wlc-action-desc">Réserve une séance avec un conseiller certifié ou discute avec le chatbot IA.</p>
                </div>
                <span class="wlc-action-arrow"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3' /></svg></span>
            </a>

        </div>
    </section>

    {{-- ════ FOOTER CTA ════ --}}
    <section class="wlc-footer-cta rev">
        <div class="wlc-cta-body">
            <div class="wlc-cta-label">Prêt à démarrer ?</div>
            <h2 class="wlc-cta-title">
                Commence ton voyage<br>
                <em>aujourd'hui.</em>
            </h2>
            <p class="wlc-cta-sub">Plus de 15 000 étudiants ont déjà trouvé leur voie grâce à CapAvenir.</p>
        </div>

        <div class="wlc-cta-action">
            <a href="{{ route('student.dashboard') }}" class="wlc-btn-cta">
                Aller à mon tableau de bord <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3' /></svg>
            </a>
        </div>

        <div class="wlc-cta-trust">
            <div class="wlc-cta-trust-item">✓ <strong>Sans engagement</strong></div>
            <div class="wlc-cta-trust-item">✓ <strong>Résultats en 12 min</strong></div>
            <div class="wlc-cta-trust-item">✓ <strong>100% adapté à la Tunisie</strong></div>
        </div>
    </section>

</div>

<script>
(function () {
    /* ── Scroll reveal ── */
    const els = document.querySelectorAll('#wlcRoot .rev');
    if (!els.length) return;
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); obs.unobserve(e.target); } });
    }, { threshold: .1, rootMargin: '0px 0px -40px 0px' });
    els.forEach(el => obs.observe(el));

    /* ── Smooth scroll for anchor CTAs ── */
    document.querySelectorAll('#wlcRoot a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const t = document.querySelector(a.getAttribute('href'));
            if (t) { e.preventDefault(); t.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
        });
    });
})();
</script>
@endsection