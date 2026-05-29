@extends('layouts.student')
@section('title', 'Future Simulator — CapAvenir')

@section('content')
@include('student.whatif._styles')

<div class="fs">
    {{-- Hero --}}
    <section class="fs-hero">
        <div class="fs-hero-bg">Future</div>
        <div class="fs-hero-inner">
            <div class="fs-eyebrow">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.9rem;height:.9rem"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                Future Simulator
            </div>
            <h1 class="fs-title">Simule tes <em>futurs</em> possibles</h1>
            <p class="fs-sub">Explore 6 dimensions de ton avenir académique et professionnel. Change tes notes, compare des filières, découvre les salaires et l'employabilité.</p>
        </div>
    </section>

    {{-- Tabs --}}
    <div class="fs-tabs">
        <button class="fs-tab active" data-tab="notes">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.85rem;height:.85rem"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-3-3V18m3-3l3 3m-9-3l-3 3m2.25-13.5h1.5a2.25 2.25 0 012.25 2.25v6.75a2.25 2.25 0 01-2.25 2.25h-1.5a2.25 2.25 0 01-2.25-2.25V5.25a2.25 2.25 0 012.25-2.25z"/></svg>
            Notes
        </button>
        <button class="fs-tab" data-tab="specialite">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.85rem;height:.85rem"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
            Spécialité
        </button>
        <button class="fs-tab" data-tab="filiere">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.85rem;height:.85rem"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5"/></svg>
            Filière Alt.
        </button>

        <button class="fs-tab" data-tab="secteurs">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.85rem;height:.85rem"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
            Employabilité
        </button>
        <button class="fs-tab" data-tab="roi">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.85rem;height:.85rem"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Salaires & ROI
        </button>
        <button class="fs-tab" data-tab="carriere">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.85rem;height:.85rem"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
            Carrière
        </button>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         MODULE 1 — VARIATION DE NOTES
    ════════════════════════════════════════════════════════════ --}}
    <div class="fs-section active" id="section-notes">
        <div class="fs-layout">
            <div class="fs-panel">
                <div class="fs-panel-head"><h2>📊 Paramètres de simulation</h2></div>
                <div class="fs-panel-body">
                    <div id="fs-alert1" class="fs-alert"></div>
                    <div class="fs-field">
                        <label class="fs-label">Section du BAC</label>
                        <select class="fs-select" id="fs-section-bac">
                            <option value="">— Choisissez —</option>
                            @foreach($sections as $s)
                                <option value="{{ $s }}" {{ ($profile?->section_bac===$s)?'selected':'' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fs-field">
                        <label class="fs-label">Moyenne Générale (0–20)</label>
                        <input type="number" class="fs-input" id="fs-mg" min="0" max="20" step="0.01" value="{{ $profile?->moyenne_generale ?? '' }}" placeholder="Ex: 14.50">
                    </div>
                    <div class="fs-field">
                        <label class="fs-label">Nom du scénario (optionnel)</label>
                        <input type="text" class="fs-input" id="fs-label" placeholder="Ex: Scénario optimiste">
                    </div>
                    <div class="fs-field">
                        <div class="fs-label">Notes par matière</div>
                        <div class="fs-notes-grid" id="fs-notes-grid">
                            <div style="padding:.75rem;text-align:center;color:var(--ink30);font-size:.82rem">Sélectionnez une section BAC</div>
                        </div>
                    </div>
                    <button class="fs-btn" id="fs-sim-btn" disabled>Simuler mon Score FG</button>
                </div>
            </div>
            <div>
                <div class="fs-panel">
                    <div class="fs-panel-head"><h2>📈 Résultats</h2></div>
                    <div class="fs-panel-body">
                        @if(!$compatibilite['has_profile'])
                        <div class="fs-placeholder" style="padding: 2.5rem 1.5rem;">
                            <div style="font-size:3rem;margin-bottom:1rem;filter: grayscale(1); opacity: 0.6;">🧭</div>
                            <div style="font-size:.88rem;font-weight:600;color:var(--ink60);margin-bottom:1.25rem;line-height:1.6">
                                Vous devez d'abord passer le test psychométrique pour débloquer ces résultats de simulation.
                            </div>
                            <a href="{{ route('student.pipeline') }}" class="fs-btn" style="max-width:240px;margin:0 auto;background:var(--accent);">Passer le test RIASEC</a>
                        </div>
                        @else
                        <div class="fs-placeholder" id="fs-result-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div style="font-size:.88rem;font-weight:500">Remplissez le formulaire et cliquez sur Simuler</div>
                        </div>
                        <div id="fs-result-notes" style="display:none">
                            <div class="fs-score-box">
                                <div class="fs-score-label">Score Formule Globale (FG)</div>
                                <span class="fs-score-num" id="fs-score-val">—</span>
                                <div><span class="fs-badge fs-badge-green" id="fs-niveau-badge">—</span></div>
                            </div>
                            <div class="fs-label" style="margin-bottom:.5rem">Formations accessibles</div>
                            <div id="fs-formations-list"></div>
                        </div>
                        @endif
                    </div>
                </div>
                @if($compatibilite['has_profile'] && $historiqueRecent->isNotEmpty())
                <div class="fs-panel" style="margin-top:1.25rem">
                    <div class="fs-panel-head"><h2>🕐 Simulations récentes</h2></div>
                    <div class="fs-panel-body" style="padding:1rem">
                        @foreach($historiqueRecent as $h)
                        <div class="fs-hist-item">
                            <div class="fs-hist-score">{{ $h->score_fg }}</div>
                            <div class="fs-hist-info">
                                <div class="fs-hist-section">{{ $h->section_bac }}</div>
                                <div class="fs-hist-date">{{ $h->created_at->diffForHumans() }} · MG: {{ $h->moyenne_generale }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         MODULE 2 — CHANGEMENT DE SPÉCIALITÉ
    ════════════════════════════════════════════════════════════ --}}
    <div class="fs-section" id="section-specialite">
        <div class="fs-layout">
            <div class="fs-panel">
                <div class="fs-panel-head"><h2>🔄 Changer de spécialité</h2></div>
                <div class="fs-panel-body">
                    <div id="fs-alert2" class="fs-alert"></div>
                    <div class="fs-field">
                        <label class="fs-label">Section actuelle</label>
                        <select class="fs-select" id="fs-spec-actuelle">
                            <option value="">— Actuelle —</option>
                            @foreach($sections as $s)<option value="{{ $s }}" {{ ($profile?->section_bac===$s)?'selected':'' }}>{{ $s }}</option>@endforeach
                        </select>
                    </div>
                    <div class="fs-field">
                        <label class="fs-label">Nouvelle section envisagée</label>
                        <select class="fs-select" id="fs-spec-nouvelle">
                            <option value="">— Nouvelle section —</option>
                            @foreach($sections as $s)<option value="{{ $s }}">{{ $s }}</option>@endforeach
                        </select>
                    </div>
                    <div class="fs-field">
                        <label class="fs-label">Score Actuel (FG)</label>
                        <input type="number" class="fs-input" id="fs-spec-score" min="0" max="300" step="0.01" value="{{ $profile?->score_fg ?? '' }}" placeholder="Ex: 145.20">
                    </div>
                    <button class="fs-btn fs-btn-alt" id="fs-spec-btn">Comparer les spécialités</button>
                </div>
            </div>
             <div class="fs-panel">
                <div class="fs-panel-head"><h2>⚡ Résultat comparatif</h2></div>
                <div class="fs-panel-body">
                    <div id="fs-result-spec" style="display:none"></div>
                    <div class="fs-placeholder" id="fs-placeholder-spec">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
                        <div style="font-size:.88rem;font-weight:500">Choisissez deux sections et comparez</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         MODULE 3 — FILIÈRE ALTERNATIVE
    ════════════════════════════════════════════════════════════ --}}
    <div class="fs-section" id="section-filiere">
        <div class="fs-panel" style="margin-bottom:1.5rem">
            <div class="fs-panel-head"><h2>🎯 Comparer des filières</h2></div>
            <div class="fs-panel-body">
                <div id="fs-alert3" class="fs-alert"></div>
                <div class="fs-grid-2" style="margin-bottom:1rem">
                    @for($i=1;$i<=3;$i++)
                    <div class="fs-field">
                        <label class="fs-label">Filière {{ $i }}{{ $i<=2?' *':'' }}</label>
                        <select class="fs-select fs-filiere-select">
                            <option value="">— {{ $i<=2?'Obligatoire':'Optionnel' }} —</option>
                            @foreach($formations as $f)<option value="{{ $f->id }}">{{ $f->nom }}</option>@endforeach
                        </select>
                    </div>
                    @endfor
                </div>
                <button class="fs-btn" id="fs-filiere-btn" style="max-width:300px">Comparer</button>
            </div>
        </div>
        <div id="fs-result-filiere" style="display:none">
            <div class="fs-layout">
                <div class="fs-panel">
                    <div class="fs-panel-head"><h2>Radar comparatif</h2></div>
                    <div class="fs-panel-body"><div class="fs-chart-wrap"><canvas id="fs-radar-chart"></canvas></div></div>
                </div>
                <div class="fs-panel">
                    <div class="fs-panel-head"><h2>Détails</h2></div>
                    <div class="fs-panel-body" id="fs-filiere-cards"></div>
                </div>
            </div>
        </div>
    </div>



    {{-- ════════════════════════════════════════════════════════════
         MODULE 5 — SECTEURS & EMPLOYABILITÉ
    ════════════════════════════════════════════════════════════ --}}
    <div class="fs-section" id="section-secteurs">
        <div class="fs-panel">
            <div class="fs-panel-head"><h2>📈 Marché du travail tunisien — Taux d'insertion par secteur</h2></div>
            <div class="fs-panel-body" id="fs-secteurs-list"></div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         MODULE 6 — SALAIRES & ROI
    ════════════════════════════════════════════════════════════ --}}
    <div class="fs-section" id="section-roi">
        <div class="fs-layout">
            <div class="fs-panel">
                <div class="fs-panel-head"><h2>💰 Retour sur investissement</h2></div>
                <div class="fs-panel-body">
                    <div class="fs-field">
                        <label class="fs-label">Niveau d'études visé</label>
                        <select class="fs-select" id="fs-roi-niveau">
                            @foreach($niveaux as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
                        </select>
                    </div>
                    <div class="fs-field">
                        <label class="fs-label">Formation spécifique (optionnel)</label>
                        <select class="fs-select" id="fs-roi-formation">
                            <option value="">— Estimation générale —</option>
                            @foreach($formations as $f)<option value="{{ $f->id }}">{{ $f->nom }}</option>@endforeach
                        </select>
                    </div>
                    <button class="fs-btn" id="fs-roi-btn">Calculer le ROI</button>
                </div>
            </div>
            <div class="fs-panel">
                <div class="fs-panel-head"><h2>📊 Projection financière</h2></div>
                <div class="fs-panel-body">
                    <div id="fs-result-roi" style="display:none"></div>
                    <div class="fs-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div style="font-size:.88rem;font-weight:500">Choisissez un niveau et calculez votre ROI</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         MODULE 7 — COMPATIBILITÉ CARRIÈRE
    ════════════════════════════════════════════════════════════ --}}
    <div class="fs-section" id="section-carriere">
        <div class="fs-panel">
            <div class="fs-panel-head"><h2>🧭 Compatibilité carrière — Basée sur votre profil RIASEC</h2></div>
            <div class="fs-panel-body" id="fs-compat-list"></div>
        </div>
    </div>
</div>

@include('student.whatif._scripts')
@endsection
