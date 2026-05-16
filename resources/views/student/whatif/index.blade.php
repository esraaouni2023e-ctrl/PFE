@extends('layouts.student')
@section('title', 'Simulateur What-If — Score FG')

@section('content')
<style>
:root {
    --ink:#0b0c10;--paper:#f7f5f0;--cream:#ede9e1;--warm:#e8e1d4;
    --accent:#d4622a;--accent2:#1a4f6e;--accent3:#4a7c59;--gold:#c8973a;
    --ink60:rgba(11,12,16,.6);--ink30:rgba(11,12,16,.3);--ink15:rgba(11,12,16,.15);
    --ink10:rgba(11,12,16,.1);--ink06:rgba(11,12,16,.06);
    --r:8px;--rl:16px;--rx:999px;--ease:cubic-bezier(.16,1,.3,1);
}
[data-theme="dark"]:root, [data-theme="dark"] {
    --ink:#f0ede6;--paper:#10100d;--cream:#18170f;--warm:#1f1e14;
    --ink60:rgba(240,237,230,.6);--ink30:rgba(240,237,230,.3);
    --ink15:rgba(240,237,230,.15);--ink10:rgba(240,237,230,.08);--ink06:rgba(240,237,230,.04);
}
.wi { font-family:'DM Sans',sans-serif;color:var(--ink);background:var(--paper);padding:2rem 2.5rem 5rem;max-width:1200px;margin:0 auto; }
.wi *,.wi *::before,.wi *::after{box-sizing:border-box;margin:0;padding:0}

/* Hero */
.wi-hero{background:var(--cream);border:1px solid var(--ink10);border-radius:20px;padding:3rem;margin-bottom:2rem;position:relative;overflow:hidden}
.wi-hero-bg{position:absolute;font-family:'Fraunces',serif;font-weight:300;font-style:italic;font-size:10rem;color:transparent;-webkit-text-stroke:1px color-mix(in srgb,var(--ink) 5%,transparent);right:2%;top:50%;transform:translateY(-50%);pointer-events:none;user-select:none}
.wi-hero-inner{position:relative;z-index:2;max-width:600px}
.wi-eyebrow{font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--accent);display:flex;align-items:center;gap:.5rem;margin-bottom:1rem}
.wi-eyebrow::before{content:'';width:18px;height:1px;background:var(--accent)}
.wi-title{font-family:'Fraunces',serif;font-size:2.8rem;font-weight:300;letter-spacing:-.04em;line-height:1.1;margin-bottom:.75rem}
.wi-title em{font-style:italic;color:var(--accent)}
.wi-sub{font-size:.9rem;color:var(--ink60);line-height:1.7;max-width:480px}

/* Layout */
.wi-layout{display:grid;grid-template-columns:1fr 1fr;gap:1.75rem;align-items:start}
@media(max-width:900px){.wi-layout{grid-template-columns:1fr}}

/* Panel */
.wi-panel{background:var(--paper);border:1px solid var(--ink10);border-radius:var(--rl);overflow:hidden}
.wi-panel-head{padding:1.25rem 1.5rem;border-bottom:1px solid var(--ink10);background:var(--cream);display:flex;align-items:center;gap:.75rem}
.wi-panel-head h2{font-family:'Fraunces',serif;font-size:1.1rem;font-weight:600;letter-spacing:-.02em}
.wi-panel-body{padding:1.5rem}

/* Form elements */
.wi-field{margin-bottom:1.25rem}
.wi-label{display:block;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink30);margin-bottom:.5rem}
.wi-input{width:100%;padding:.75rem 1rem;background:var(--cream);border:1px solid var(--ink15);border-radius:var(--r);color:var(--ink);font-family:'DM Sans',sans-serif;font-size:.9rem;transition:border-color .2s}
.wi-input:focus{outline:none;border-color:var(--accent)}
select.wi-input{cursor:pointer}
.wi-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}

/* Notes grid */
.wi-notes-grid{display:flex;flex-direction:column;gap:.75rem;margin-top:.5rem}
.wi-note-row{display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;background:var(--cream);border-radius:var(--r);border:1px solid var(--ink10)}
.wi-note-label{flex:1;font-size:.82rem;font-weight:500;color:var(--ink60)}
.wi-note-coef{font-size:.68rem;font-weight:700;text-transform:uppercase;color:var(--accent);letter-spacing:.06em;flex-shrink:0;width:40px;text-align:center}
.wi-note-input{width:80px;padding:.45rem .75rem;background:var(--paper);border:1px solid var(--ink15);border-radius:var(--r);text-align:center;font-family:'Fraunces',serif;font-size:1rem;font-weight:600;color:var(--ink);transition:border-color .2s}
.wi-note-input:focus{outline:none;border-color:var(--accent)}

/* Button */
.wi-btn{width:100%;padding:1rem;background:var(--accent);color:#fff;border:none;border-radius:var(--r);font-family:'DM Sans',sans-serif;font-size:.95rem;font-weight:600;cursor:pointer;transition:all .3s var(--ease);box-shadow:0 4px 18px color-mix(in srgb,var(--accent) 30%,transparent);display:flex;align-items:center;justify-content:center;gap:.75rem;margin-top:1.5rem}
.wi-btn:hover{transform:translateY(-2px);box-shadow:0 8px 28px color-mix(in srgb,var(--accent) 40%,transparent)}
.wi-btn:disabled{opacity:.5;cursor:not-allowed;transform:none}

/* Result panel */
.wi-score-box{text-align:center;padding:2.5rem 1.5rem;background:var(--cream);border-radius:var(--rl);border:1px solid var(--ink10);margin-bottom:1.5rem;animation:fadeInUp .6s var(--ease)}
@keyframes fadeInUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:none}}
.wi-score-label{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--ink30);margin-bottom:.5rem}
.wi-score-num{font-family:'Fraunces',serif;font-size:4.5rem;font-weight:600;letter-spacing:-.06em;line-height:1;color:var(--accent);display:block}
.wi-score-niveau{display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .9rem;border-radius:var(--rx);background:color-mix(in srgb,var(--accent) 10%,transparent);border:1px solid color-mix(in srgb,var(--accent) 22%,transparent);color:var(--accent);font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-top:.75rem}

/* Formations accessibles */
.wi-f-list{display:flex;flex-direction:column;gap:.75rem}
.wi-f-item{display:flex;align-items:center;gap:.875rem;padding:.875rem 1rem;background:var(--cream);border-radius:var(--r);border:1px solid var(--ink10);transition:all .2s}
.wi-f-item:hover{border-color:var(--ink30);background:var(--warm)}
.wi-f-icon{width:36px;height:36px;border-radius:var(--r);background:color-mix(in srgb,var(--accent) 10%,transparent);border:1px solid color-mix(in srgb,var(--accent) 20%,transparent);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0}
.wi-f-info{flex:1;min-width:0}
.wi-f-nom{font-size:.85rem;font-weight:600;color:var(--ink);line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.wi-f-meta{font-size:.72rem;color:var(--ink60);margin-top:.15rem}
.wi-f-score{font-family:'Fraunces',serif;font-size:1.1rem;font-weight:600;color:var(--accent3);flex-shrink:0}

/* Placeholder */
.wi-placeholder{text-align:center;padding:3rem 1.5rem;color:var(--ink30)}
.wi-placeholder-icon{font-size:3rem;margin-bottom:1rem}
.wi-placeholder-text{font-size:.9rem;font-weight:500}

/* Historique mini */
.wi-hist-list{display:flex;flex-direction:column;gap:.5rem;margin-top:1rem}
.wi-hist-item{display:flex;align-items:center;gap:.75rem;padding:.625rem .875rem;background:var(--cream);border-radius:var(--r);border:1px solid var(--ink10);cursor:pointer;transition:all .2s}
.wi-hist-item:hover{border-color:var(--ink30)}
.wi-hist-score{font-family:'Fraunces',serif;font-size:1rem;font-weight:600;color:var(--accent);width:50px;flex-shrink:0}
.wi-hist-info{flex:1;min-width:0}
.wi-hist-section{font-size:.78rem;font-weight:600;color:var(--ink);line-height:1.2}
.wi-hist-date{font-size:.68rem;color:var(--ink30);margin-top:.1rem}

/* Alert */
.wi-alert{padding:1rem 1.25rem;border-radius:var(--r);font-size:.85rem;font-weight:500;display:none;margin-bottom:1rem}
.wi-alert.error{background:color-mix(in srgb,#ef4444 8%,transparent);border:1px solid color-mix(in srgb,#ef4444 22%,transparent);color:#ef4444}
.wi-alert.success{background:color-mix(in srgb,var(--accent3) 8%,transparent);border:1px solid color-mix(in srgb,var(--accent3) 22%,transparent);color:var(--accent3)}
</style>

<div class="wi">
    {{-- Hero --}}
    <section class="wi-hero">
        <div class="wi-hero-bg">What-If</div>
        <div class="wi-hero-inner">
            <div class="wi-eyebrow">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.9rem;height:.9rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.503 7.503 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                </svg>
                Simulateur interact
            </div>
            <h1 class="wi-title">Calcule ton <em>Score FG</em></h1>
            <p class="wi-sub">Modifie tes notes et vois instantanément les filières accessibles — sans aucune IA.</p>
        </div>
    </section>

    <div class="wi-layout">

        {{-- ══ PANEL FORMULAIRE ══ --}}
        <div class="wi-panel">
            <div class="wi-panel-head">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.25rem;height:1.25rem;color:var(--accent2);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 18H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 12h9" />
                </svg>
                <h2>Paramètres de simulation</h2>
            </div>
            <div class="wi-panel-body">
                <div id="alertBox" class="wi-alert"></div>

                {{-- Section BAC --}}
                <div class="wi-field">
                    <label class="wi-label" for="sectionBac">Section du BAC</label>
                    <select class="wi-input" id="sectionBac" name="section_bac">
                        <option value="">— Choisissez votre section —</option>
                        @foreach($sections as $section)
                            <option value="{{ $section }}"
                                {{ ($profile?->section_bac === $section) ? 'selected' : '' }}>
                                {{ $section }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Moyenne générale --}}
                <div class="wi-field">
                    <label class="wi-label" for="mg">Moyenne Générale (0–20)</label>
                    <input type="number" class="wi-input" id="mg" name="moyenne_generale"
                        min="0" max="20" step="0.01"
                        value="{{ $profile?->moyenne_generale ?? '' }}"
                        placeholder="Ex: 14.50">
                </div>

                {{-- Label personnalisé --}}
                <div class="wi-field">
                    <label class="wi-label" for="simLabel">Nom du scénario (optionnel)</label>
                    <input type="text" class="wi-input" id="simLabel" placeholder="Ex: Scénario optimiste">
                </div>

                {{-- Notes par matière (dynamique) --}}
                <div class="wi-field">
                    <div class="wi-label">Notes par matière</div>
                    <div class="wi-notes-grid" id="notesGrid">
                        <div style="padding:1rem;text-align:center;color:var(--ink30);font-size:.85rem">
                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18' /></svg> Sélectionnez d'abord votre section BAC
                        </div>
                    </div>
                </div>

                <button class="wi-btn" id="simulerBtn" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1.25rem;height:1.25rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-3-3V18m3-3l3 3m-9-3l-3 3m2.25-13.5h1.5a2.25 2.25 0 012.25 2.25v6.75a2.25 2.25 0 01-2.25 2.25h-1.5a2.25 2.25 0 01-2.25-2.25V5.25a2.25 2.25 0 012.25-2.25z" />
                    </svg>
                    <span>Simuler mon Score FG</span>
                </button>
            </div>
        </div>

        {{-- ══ PANEL RÉSULTAT ══ --}}
        <div>
            <div class="wi-panel" id="resultPanel">
                <div class="wi-panel-head">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.25rem;height:1.25rem;color:var(--accent);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    <h2>Résultats de la simulation</h2>
                </div>
                <div class="wi-panel-body">
                    {{-- Placeholder initial --}}
                    <div class="wi-placeholder" id="resultPlaceholder">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:3rem;height:3rem;margin:0 auto 1rem; opacity:.2;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="wi-placeholder-text">Remplissez le formulaire<br>et cliquez sur Simuler</div>
                    </div>

                    {{-- Résultat (masqué initialement) --}}
                    <div id="resultContent" style="display:none">
                        <div class="wi-score-box">
                            <div class="wi-score-label">Score Formule Globale (FG)</div>
                            <span class="wi-score-num" id="scoreFgNum">—</span>
                            <div><span class="wi-score-niveau" id="scoreNiveau">—</span></div>
                        </div>

                        <div style="margin-bottom:1rem">
                            <div class="wi-label" style="display:flex;align-items:center;gap:.4rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1rem;height:1rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147L12 15l7.74-4.853a4.5 4.5 0 00-2.122-3.933L12 3 6.382 6.214a4.5 4.5 0 00-2.122 3.933z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v7.5" />
                                </svg>
                                Formations accessibles avec ce score
                            </div>
                            <div class="wi-f-list" id="formationsList"></div>
                        </div>

                        <div style="display:flex;gap:.75rem;flex-wrap:wrap">
                            <a href="{{ route('student.voeux.index') }}"
                               style="display:inline-flex;align-items:center;gap:.4rem;padding:.65rem 1.1rem;border-radius:8px;background:var(--cream);border:1px solid var(--ink15);font-size:.82rem;font-weight:600;color:var(--ink60);text-decoration:none;transition:all .2s">
                               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.9rem;height:.9rem;color:var(--accent);"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg> Voir mes vœux
                            </a>
                            <a href="{{ route('student.comparateur.index') }}"
                               style="display:inline-flex;align-items:center;gap:.4rem;padding:.65rem 1.1rem;border-radius:8px;background:var(--cream);border:1px solid var(--ink15);font-size:.82rem;font-weight:600;color:var(--ink60);text-decoration:none;transition:all .2s">
                               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.9rem;height:.9rem;color:var(--accent2);"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg> Comparer des filières
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Historique récent --}}
            @if($historiqueRecent->isNotEmpty())
            <div class="wi-panel" style="margin-top:1.25rem">
                <div class="wi-panel-head">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.25rem;height:1.25rem;color:var(--ink30);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2>Simulations récentes</h2>
                </div>
                <div class="wi-panel-body" style="padding:1rem">
                    <div class="wi-hist-list">
                        @foreach($historiqueRecent as $hist)
                        <div class="wi-hist-item" onclick="chargerSimulation({{ $hist->score_fg }}, '{{ $hist->section_bac }}', '{{ $hist->niveau_score }}')">
                            <div class="wi-hist-score">{{ $hist->score_fg }}</div>
                            <div class="wi-hist-info">
                                <div class="wi-hist-section">{{ $hist->section_bac }}</div>
                                <div class="wi-hist-date">{{ $hist->created_at->diffForHumans() }}</div>
                            </div>
                            <div style="font-size:.68rem;font-weight:700;color:var(--ink30)">MG: {{ $hist->moyenne_generale }}</div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('student.whatif.historique') }}"
                       style="display:block;text-align:center;margin-top:.875rem;font-size:.78rem;font-weight:600;color:var(--accent);text-decoration:none">
                       Voir tout l'historique <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3' /></svg>
                    </a>
                </div>
            </div>
            @endif
        </div>

    </div>
</div>

<script>
(function() {
    const sectionSel = document.getElementById('sectionBac');
    const mgInput    = document.getElementById('mg');
    const notesGrid  = document.getElementById('notesGrid');
    const simulerBtn = document.getElementById('simulerBtn');
    const alertBox   = document.getElementById('alertBox');

    let matieres = {};

    // ── Charger les matières quand la section change ──
    sectionSel.addEventListener('change', async function() {
        const section = this.value;
        if (!section) {
            notesGrid.innerHTML = `<div style="padding:1rem;text-align:center;color:var(--ink30);font-size:.85rem"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18' /></svg> Sélectionnez d'abord votre section BAC</div>`;
            simulerBtn.disabled = true;
            return;
        }

        notesGrid.innerHTML = '<div style="padding:1rem;text-align:center;color:var(--ink30)">Chargement…</div>';

        const res = await fetch(`{{ route('student.whatif.matieres') }}?section=${encodeURIComponent(section)}`);
        const data = await res.json();
        matieres = data.matieres;

        notesGrid.innerHTML = '';

        const prefill = @json($profile?->notes_matieres ?? []);

        Object.entries(matieres).forEach(([code, info]) => {
            const div = document.createElement('div');
            div.className = 'wi-note-row';
            div.innerHTML = `
                <div class="wi-note-label">${info.label}</div>
                <div class="wi-note-coef">×${info.coef}</div>
                <input type="number" class="wi-note-input" name="notes[${code}]"
                    min="0" max="20" step="0.25"
                    value="${prefill[code] ?? ''}"
                    placeholder="—">
            `;
            notesGrid.appendChild(div);
        });

        simulerBtn.disabled = false;
    });

    // ── Déclencher le changement si section déjà sélectionnée ──
    if (sectionSel.value) sectionSel.dispatchEvent(new Event('change'));

    // ── Soumission ──
    simulerBtn.addEventListener('click', async function() {
        hideAlert();
        const section = sectionSel.value;
        const mg      = parseFloat(mgInput.value);

        if (!section) { showAlert('Veuillez sélectionner votre section BAC.', 'error'); return; }
        if (isNaN(mg) || mg < 0 || mg > 20) { showAlert('Moyenne générale invalide (0–20).', 'error'); return; }

        const notes = {};
        let allFilled = true;
        document.querySelectorAll('.wi-note-input').forEach(input => {
            const match = input.name.match(/notes\[(.+)\]/);
            if (match) {
                const val = parseFloat(input.value);
                if (isNaN(val)) { allFilled = false; return; }
                notes[match[1]] = val;
            }
        });

        if (!allFilled) { showAlert('Veuillez renseigner toutes les notes.', 'error'); return; }

        simulerBtn.disabled = true;
        simulerBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1.25rem;height:1.25rem;animation:spin 2s linear infinite;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg><span>Calcul en cours…</span>';

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
            const res = await fetch('{{ route('student.whatif.calculer') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                body: JSON.stringify({
                    section_bac: section,
                    moyenne_generale: mg,
                    notes,
                    label: document.getElementById('simLabel').value || null,
                }),
            });

            const data = await res.json();

            if (data.success) {
                afficherResultat(data.score_fg, data.niveau, data.formations);
            } else {
                showAlert(data.message || 'Erreur de calcul.', 'error');
            }
        } catch (e) {
            showAlert('Erreur réseau. Veuillez réessayer.', 'error');
        } finally {
            simulerBtn.disabled = false;
            simulerBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1.25rem;height:1.25rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-3-3V18m3-3l3 3m-9-3l-3 3m2.25-13.5h1.5a2.25 2.25 0 012.25 2.25v6.75a2.25 2.25 0 01-2.25 2.25h-1.5a2.25 2.25 0 01-2.25-2.25V5.25a2.25 2.25 0 012.25-2.25z" /></svg><span>Simuler mon Score FG</span>';
        }
    });

    function afficherResultat(score, niveau, formations) {
        document.getElementById('resultPlaceholder').style.display = 'none';
        const resultContent = document.getElementById('resultContent');
        resultContent.style.display = 'block';

        const niveauColors = { excellent: 'var(--accent3)', bon: 'var(--gold)', moyen: 'var(--accent)', faible: '#ef4444' };
        const color = niveauColors[niveau] || 'var(--accent)';

        document.getElementById('scoreFgNum').textContent = score.toFixed(2);
        document.getElementById('scoreFgNum').style.color = color;
        document.getElementById('scoreNiveau').textContent = niveau.toUpperCase();
        document.getElementById('scoreNiveau').style.color = color;
        document.getElementById('scoreNiveau').style.background = `color-mix(in srgb, ${color} 10%, transparent)`;
        document.getElementById('scoreNiveau').style.borderColor = `color-mix(in srgb, ${color} 25%, transparent)`;

        const list = document.getElementById('formationsList');
        list.innerHTML = '';

        if (!formations || formations.length === 0) {
            list.innerHTML = '<div style="text-align:center;padding:1.5rem;color:var(--ink30);font-size:.85rem">Aucune formation accessible avec ce score.</div>';
            return;
        }

        formations.forEach(f => {
            const div = document.createElement('div');
            div.className = 'wi-f-item';
            div.innerHTML = `
                <div class="wi-f-icon">${f.icon}</div>
                <div class="wi-f-info">
                    <div class="wi-f-nom">${f.nom}</div>
                    <div class="wi-f-meta">${f.etablissement} · ${f.niveau} · ${f.duree}</div>
                </div>
                <div class="wi-f-score">${f.score_matching}%</div>
            `;
            list.appendChild(div);
        });
    }

    window.chargerSimulation = function(score, section, niveau) {
        document.getElementById('resultPlaceholder').style.display = 'none';
        document.getElementById('resultContent').style.display = 'block';
        document.getElementById('scoreFgNum').textContent = parseFloat(score).toFixed(2);
        document.getElementById('scoreNiveau').textContent = niveau.toUpperCase();
        sectionSel.value = section;
        sectionSel.dispatchEvent(new Event('change'));
    };

    function showAlert(msg, type) {
        alertBox.textContent = msg;
        alertBox.className = `wi-alert ${type}`;
        alertBox.style.display = 'block';
        setTimeout(() => alertBox.style.display = 'none', 5000);
    }
    function hideAlert() { alertBox.style.display = 'none'; }
})();
</script>
@endsection
