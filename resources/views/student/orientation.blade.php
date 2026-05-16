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
                        <span class="pill pill-accent">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.75rem;height:.75rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                            {{ count($domaines) }} domaines
                        </span>
                        <span class="pill pill-sage">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.75rem;height:.75rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147L12 15l7.74-4.853a4.5 4.5 0 00-2.122-3.933L12 3 6.382 6.214a4.5 4.5 0 00-2.122 3.933z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v7.5" />
                            </svg>
                            {{ \App\Models\Filiere::count() }} filières
                        </span>
                        <span class="pill pill-marine">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.75rem;height:.75rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            Tunisie 2026
                        </span>
                    </div>
                    @php
                        $pipelineStatus = \App\Http\Controllers\Student\OrientationPipelineController::getStatus(auth()->id());
                        $pipelineStep   = $pipelineStatus['step'];
                        $pipelineLabel  = match($pipelineStep) {
                            1 => 'Passer le test',
                            2 => 'Passer le test',
                            3 => 'Voir mes recommandations',
                        };
                        $pipelineIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 14 4-4"/><path d="M3.34 19a10 10 0 1 1 17.32 0"/></svg>';
                    @endphp
                    <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                        <a href="{{ route('student.pipeline') }}"
                           class="btn-fill or-cta-btn"
                           style="background:linear-gradient(135deg,#6366f1,#a855f7);
                                  box-shadow:0 4px 18px rgba(99,102,241,0.35);
                                  font-size:.95rem;padding:.85rem 2rem;gap:.6rem;"
                           onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 28px rgba(99,102,241,0.5)'"
                           onmouseout="this.style.transform='';this.style.boxShadow='0 4px 18px rgba(99,102,241,0.35)'">
                            {!! $pipelineIcon !!} {{ $pipelineLabel }}
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
                    <div class="or-sidebar-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.9rem;height:.9rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        Recherche
                    </div>
                    <form method="GET" action="{{ route('student.orientation') }}" id="searchForm">
                        <input type="hidden" name="domaine" value="{{ request('domaine') }}">
                        <input type="hidden" name="etablissement" value="{{ request('etablissement') }}">

                        <div class="or-search-inner">
                            <span class="or-search-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1.1rem;height:1.1rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </span>
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
                    <div class="or-sidebar-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.9rem;height:.9rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-3.015-3.015 3 3 0 00-3.015 3.015 3 3 0 003.015 3.015 3 3 0 003.015-3.015zM17.03 16.122a3 3 0 00-3.015-3.015 3 3 0 00-3.015 3.015 3 3 0 003.015 3.015 3 3 0 003.015-3.015zM13.28 10.122a3 3 0 00-3.015-3.015 3 3 0 00-3.015 3.015 3 3 0 003.015 3.015 3 3 0 003.015-3.015z" />
                        </svg>
                        Domaine
                    </div>
                    <form method="GET" action="{{ route('student.orientation') }}">
                        <input type="hidden" name="recherche" value="{{ request('recherche') }}">
                        <input type="hidden" name="etablissement" value="{{ request('etablissement') }}">

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
                    <div class="or-sidebar-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.9rem;height:.9rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                        Établissement
                    </div>
                    <form method="GET" action="{{ route('student.orientation') }}">
                        <input type="hidden" name="recherche" value="{{ request('recherche') }}">
                        <input type="hidden" name="domaine" value="{{ request('domaine') }}">

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



                {{-- Section SPÉCIALITÉS --}}
                @if(isset($specialites) && $specialites->count() > 0)
                <div class="or-sidebar-block">
                    <div class="or-sidebar-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.9rem;height:.9rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 003.182 0l4.318-4.318a2.25 2.25 0 000-3.182L11.159 3.659A2.25 2.25 0 009.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>
                        Spécialités
                    </div>
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

                    <button class="or-filter-toggle" id="filterToggle" aria-label="Filtres" style="display:flex;align-items:center;gap:.4rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1rem;height:1rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 18H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 12h9" />
                        </svg>
                        Filtres
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

                                <div style="font-size: 0.8rem; font-weight: 600; color: var(--ink60); display: flex; align-items: center; gap: .4rem; margin-bottom: .25rem;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:.9rem;height:.9rem;flex-shrink:0;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                                    </svg>
                                    <span style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">{{ $filiere->etablissement }}</span>
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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:4rem;height:4rem;margin:0 auto 1.5rem; opacity:.3;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
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
                            <span class="or-page-item or-page-wide disabled"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18' /></svg> Précédent</span>
                        @else
                            <a href="{{ $filieres->previousPageUrl() }}" class="or-page-item or-page-wide"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18' /></svg> Précédent</a>
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
                            <a href="{{ $filieres->nextPageUrl() }}" class="or-page-item or-page-wide">Suivant <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3' /></svg></a>
                        @else
                            <span class="or-page-item or-page-wide disabled">Suivant <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3' /></svg></span>
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
                <div style="font-size:2.5rem;margin-bottom:1rem;"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z' /></svg></div>
                <div style="color:var(--ink60);font-weight:500;">Chargement de la fiche…</div>
            </div>
            <div id="ficheContent"></div>
        </div>
    </div>

    {{-- Hidden formation data (JSON) --}}
    {{-- Modal logic removed as filieres use cards without detail popups currently --}}

    @include('student.orientation.scripts')

@endsection