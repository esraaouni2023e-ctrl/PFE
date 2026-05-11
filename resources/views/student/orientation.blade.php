@extends('layouts.student')

@section('title', 'Espace Orientation')

@section('content')
    @include('student.orientation.styles')

    <div class="or" id="orRoot">

        {{-- ════ HERO COMPACT ════ --}}
        <section class="or-hero">
            <div class="or-hero-bgword">Formation</div>
            <div class="or-hero-orb"></div>

            <div class="or-hero-inner">
                <div class="or-eyebrow">
                    <span class="or-eyebrow-dot"></span>
                    Espace Orientation · Tunisie 2026
                </div>

                <h1 class="or-hero-title">
                    Trouve ta <em>formation</em> idéale
                </h1>

                <p class="or-hero-sub">
                    Base de données complète des formations en Tunisie — fiches descriptives, débouchés, conditions d'accès.
                </p>

                <div class="or-hero-actions">
                    <div class="or-hero-meta">
                        <span class="pill pill-accent">📚 {{ count($domaines) }} domaines</span>
                        <span class="pill pill-sage">🎓 {{ \App\Models\Filiere::count() }} filières</span>
                        <span class="pill pill-marine">🇹🇳 Tunisie 2026</span>
                    </div>
                    @php
                        $pipelineStatus = \App\Http\Controllers\Student\OrientationPipelineController::getStatus(auth()->id());
                        $pipelineStep   = $pipelineStatus['step'];
                        $pipelineLabel  = match($pipelineStep) {
                            1 => '📊 Étape 1/3 — Calculer mon Score',
                            2 => '🧠 Étape 2/3 — Passer le test RIASEC',
                            3 => '✅ Voir mes recommandations',
                        };
                    @endphp
                    <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                        <a href="{{ route('student.pipeline') }}"
                           class="btn-fill or-cta-btn"
                           style="background:linear-gradient(135deg,#6366f1,#a855f7);
                                  box-shadow:0 4px 18px rgba(99,102,241,0.35);
                                  font-size:.95rem;padding:.85rem 2rem;gap:.6rem;"
                           onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 28px rgba(99,102,241,0.5)'"
                           onmouseout="this.style.transform='';this.style.boxShadow='0 4px 18px rgba(99,102,241,0.35)'">
                            {{ $pipelineLabel }}
                        </a>
                    </div>
                </div>

            </div>
        </section>

        {{-- ════ MAIN LAYOUT: SIDEBAR + CONTENT ════ --}}
        <div class="or-layout">

            {{-- ════ SIDEBAR ════ --}}
            <aside class="or-sidebar" id="orSidebar">

                {{-- Section RECHERCHE --}}
                <div class="or-sidebar-block">
                    <div class="or-sidebar-label">🔍 Recherche</div>
                    <form method="GET" action="{{ route('student.orientation') }}" id="searchForm">
                        <input type="hidden" name="domaine" value="{{ request('domaine') }}">
                        <input type="hidden" name="etablissement" value="{{ request('etablissement') }}">
                        <input type="hidden" name="niveau" value="{{ request('niveau') }}">
                        <div class="or-search-inner">
                            <span class="or-search-icon">🔍</span>
                            <input type="text" name="recherche" class="or-search-input" id="searchInput"
                                value="{{ request('recherche') }}" placeholder="Formation, établissement…"
                                autocomplete="off">
                        </div>
                        <div style="display:flex;gap:.5rem;margin-top:.625rem;">
                            <button type="submit" class="btn-fill" style="flex:1;justify-content:center;">Chercher</button>
                        </div>
                    </form>
                </div>

                {{-- Section DOMAINE (Select Dropdown) --}}
                <div class="or-sidebar-block">
                    <div class="or-sidebar-label">🎯 Domaine</div>
                    <form method="GET" action="{{ route('student.orientation') }}">
                        <input type="hidden" name="recherche" value="{{ request('recherche') }}">
                        <input type="hidden" name="etablissement" value="{{ request('etablissement') }}">
                        <input type="hidden" name="niveau" value="{{ request('niveau') }}">
                        <select name="domaine" class="or-search-input" style="padding-left: 0.9rem;" onchange="this.form.submit()">
                            <option value="">Tous les domaines</option>
                            @foreach($domaines as $d)
                                <option value="{{ $d }}" {{ request('domaine') == $d ? 'selected' : '' }}>
                                    {{ $d }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                {{-- Section ÉTABLISSEMENT (Select Dropdown) --}}
                <div class="or-sidebar-block">
                    <div class="or-sidebar-label">🏛️ Établissement</div>
                    <form method="GET" action="{{ route('student.orientation') }}">
                        <input type="hidden" name="recherche" value="{{ request('recherche') }}">
                        <input type="hidden" name="domaine" value="{{ request('domaine') }}">
                        <input type="hidden" name="niveau" value="{{ request('niveau') }}">
                        <select name="etablissement" class="or-search-input" style="padding-left: 0.9rem;" onchange="this.form.submit()">
                            <option value="">Tous les établissements</option>
                            @foreach($etablissements as $etab)
                                <option value="{{ $etab }}" {{ request('etablissement') == $etab ? 'selected' : '' }}>
                                    {{ $etab }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                {{-- Section NIVEAU D'ÉTUDES --}}
                <div class="or-sidebar-block">
                    <div class="or-sidebar-label">📐 Niveau d'études</div>
                    <form method="GET" action="{{ route('student.orientation') }}" id="niveauForm">
                        <input type="hidden" name="domaine" value="{{ request('domaine') }}">
                        <input type="hidden" name="recherche" value="{{ request('recherche') }}">
                        <input type="hidden" name="etablissement" value="{{ request('etablissement') }}">
                        <div class="or-nivel-grid">
                            <button type="submit" name="niveau" value="" class="or-nivel-btn {{ !request('niveau') ? 'active' : '' }}">
                                Tous
                            </button>
                            @foreach($niveaux as $n)
                                <button type="submit" name="niveau" value="{{ $n }}" class="or-nivel-btn {{ request('niveau') == $n ? 'active' : '' }}">
                                    {{ $n }}
                                </button>
                            @endforeach
                        </div>
                    </form>
                </div>

                {{-- Section SPÉCIALITÉS --}}
                @if(isset($specialites) && $specialites->count() > 0)
                <div class="or-sidebar-block">
                    <div class="or-sidebar-label">🏷️ Spécialités</div>
                    <div class="or-spec-list">
                        @foreach($specialites->take(5) as $spec)
                            <a href="{{ route('student.orientation', array_merge(request()->all(), ['domaine' => $spec->domaine, 'page' => 1])) }}"
                                class="or-spec-pill {{ request('domaine') == $spec->domaine ? 'active' : '' }}">
                                <span class="or-spec-pill-icon">{{ $spec->icon }}</span>
                                <span class="or-spec-pill-name">{{ $spec->nom }}</span>
                                <span class="or-spec-pill-count">{{ $spec->nb_formations }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- RESET FILTRES --}}
                @if(request()->hasAny(['domaine', 'etablissement', 'recherche', 'niveau']))
                <div class="or-sidebar-block" style="background: var(--paper); border-top: 1px solid var(--ink10);">
                    <a href="{{ route('student.orientation') }}" class="btn-ghost" style="width: 100%; justify-content: center; font-size: 0.75rem; border-style: dashed;">
                        ✕ Réinitialiser les filtres
                    </a>
                </div>
                @endif

            </aside>

            {{-- ════ MAIN CONTENT ════ --}}
            <main class="or-main">

                <div class="or-results-header rev">
                    <div class="or-results-info">
                        <span class="or-results-count-big">{{ $filieres->total() }}</span>
                        <span class="or-results-count-label">
                            formation{{ $filieres->total() > 1 ? 's' : '' }} trouvée{{ $filieres->total() > 1 ? 's' : '' }}
                            @if($domaine) · <strong>{{ $domaine }}</strong>@endif
                            @if($niveau) · <strong>{{ $niveau }}</strong>@endif
                            @if($recherche) · "<em>{{ $recherche }}</em>"@endif
                        </span>
                    </div>

                    <button class="or-filter-toggle" id="filterToggle" aria-label="Filtres">
                        ⚙️ Filtres
                    </button>
                </div>

                <div class="or-grid">
                    @forelse($filieres as $filiere)
                        <article class="or-card">
                            <div class="or-card-stripe" style="--stripe-color: var(--accent); width: 100%; height: 4px;"></div>

                            <div class="or-card-body">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                    <span style="font-size: 0.65rem; font-weight: 700; color: var(--ink30); text-transform: uppercase; letter-spacing: 0.05em;">
                                        #{{ $filiere->code_filiere }}
                                    </span>
                                    <span class="pill pill-accent" style="font-size: 0.6rem;">
                                        {{ $filiere->domaine }}
                                    </span>
                                </div>

                                <h3 style="font-family: var(--font-serif); font-size: 1rem; font-weight: 600; color: var(--ink); margin-bottom: 0.25rem; line-height: 1.3; min-height: 2.6rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $filiere->nom_filiere }}
                                </h3>

                                <div style="font-size: 0.8rem; font-weight: 600; color: var(--ink60); display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">
                                    🏛️ {{ $filiere->etablissement }}
                                </div>

                                <div style="font-size: 0.7rem; color: var(--ink30); margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $filiere->universite }}
                                </div>

                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; padding-top: 1rem; border-top: 1px solid var(--ink06); margin-top: auto;">
                                    <div style="text-align: center;">
                                        <div style="font-size: 0.55rem; font-weight: 700; color: var(--ink30); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.2rem;">2023</div>
                                        <div style="font-family: var(--font-serif); font-size: 0.85rem; font-weight: 600; color: var(--ink60);">
                                            {{ $filiere->sdo_2023 ? number_format($filiere->sdo_2023, 2, '.', ' ') : 'N/A' }}
                                        </div>
                                    </div>
                                    <div style="text-align: center; border-left: 1px solid var(--ink06); border-right: 1px solid var(--ink06);">
                                        <div style="font-size: 0.55rem; font-weight: 700; color: var(--ink30); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.2rem;">2024</div>
                                        <div style="font-family: var(--font-serif); font-size: 0.85rem; font-weight: 600; color: var(--ink60);">
                                            {{ $filiere->sdo_2024 ? number_format($filiere->sdo_2024, 2, '.', ' ') : 'N/A' }}
                                        </div>
                                    </div>
                                    <div style="text-align: center;">
                                        <div style="font-size: 0.55rem; font-weight: 700; color: var(--ink30); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.2rem;">2025</div>
                                        <div style="font-family: var(--font-serif); font-size: 0.95rem; font-weight: 700; color: var(--accent);">
                                            {{ $filiere->sdo_2025 ? number_format($filiere->sdo_2025, 2, '.', ' ') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="or-empty rev vis">
                            <div class="or-empty-icon">🔍</div>
                            <h3 class="or-empty-title">Aucune formation trouvée</h3>
                            <p class="or-empty-sub">Essayez d'élargir votre recherche ou de changer de filtre.</p>
                            <a href="{{ route('student.orientation') }}" class="btn-fill">Voir toutes les formations</a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div style="margin-top: 2rem; display: flex; justify-content: center;">
                    <nav class="or-pagination">
                        {{-- Previous Page Link --}}
                        @if($filieres->onFirstPage())
                            <span class="or-page-item or-page-wide disabled">← Précédent</span>
                        @else
                            <a href="{{ $filieres->previousPageUrl() }}" class="or-page-item or-page-wide">← Précédent</a>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            $onEachSide = 1;
                            $window = $onEachSide * 2;
                            $lastPage = $filieres->lastPage();
                            $currentPage = $filieres->currentPage();
                            
                            $start = max($currentPage - $onEachSide, 1);
                            $end = min($start + $window, $lastPage);
                            if ($end - $start < $window) {
                                $start = max($end - $window, 1);
                            }
                        @endphp

                        @if($start > 1)
                            <a href="{{ $filieres->url(1) }}" class="or-page-item">1</a>
                            @if($start > 2)
                                <span class="or-page-item disabled" style="border:none;background:transparent;">...</span>
                            @endif
                        @endif

                        @for($i = $start; $i <= $end; $i++)
                            @if($i == $currentPage)
                                <span class="or-page-item active">{{ $i }}</span>
                            @else
                                <a href="{{ $filieres->url($i) }}" class="or-page-item">{{ $i }}</a>
                            @endif
                        @endfor

                        @if($end < $lastPage)
                            @if($end < $lastPage - 1)
                                <span class="or-page-item disabled" style="border:none;background:transparent;">...</span>
                            @endif
                            <a href="{{ $filieres->url($lastPage) }}" class="or-page-item">{{ $lastPage }}</a>
                        @endif

                        {{-- Next Page Link --}}
                        @if($filieres->hasMorePages())
                            <a href="{{ $filieres->nextPageUrl() }}" class="or-page-item or-page-wide">Suivant →</a>
                        @else
                            <span class="or-page-item or-page-wide disabled">Suivant →</span>
                        @endif
                    </nav>
                </div>

            </main>
        </div>

    </div>{{-- /or --}}

    {{-- ════ FICHE MODAL ════ --}}
    <div class="or-modal-backdrop" id="ficheModal">
        <div class="or-modal-panel" id="fichePanel">
            <div id="ficheLoading" style="padding:4rem;text-align:center;display:none;">
                <div style="font-size:2.5rem;margin-bottom:1rem;">⏳</div>
                <div style="color:var(--ink60);font-weight:500;">Chargement de la fiche…</div>
            </div>
            <div id="ficheContent"></div>
        </div>
    </div>

    {{-- Hidden formation data (JSON) --}}
    {{-- Modal logic removed as filieres use cards without detail popups currently --}}

    @include('student.orientation.scripts')

@endsection