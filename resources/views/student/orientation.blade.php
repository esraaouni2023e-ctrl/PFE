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
                        <span class="pill pill-accent">📚 {{ $specialites->count() }} spécialités</span>
                        <span class="pill pill-sage">🎓 {{ $formations->total() }} formations</span>
                        <span class="pill pill-marine">🇹🇳 Tunisie 2026</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                        <a href="{{ route('student.orientation.nova') }}" class="btn-fill or-cta-btn">
                            ✨ Calculer mon Score FG
                        </a>
                        <a href="{{ route('riasec.start') }}"
                           style="display:inline-flex;align-items:center;gap:.5rem;
                                  padding:.7rem 1.4rem;border-radius:8px;font-weight:600;font-size:.85rem;
                                  background:linear-gradient(135deg,#6366f1,#a855f7);
                                  color:#fff;text-decoration:none;
                                  box-shadow:0 4px 18px rgba(99,102,241,0.35);
                                  transition:all .25s ease;"
                           onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 28px rgba(99,102,241,0.5)'"
                           onmouseout="this.style.transform='';this.style.boxShadow='0 4px 18px rgba(99,102,241,0.35)'">
                            🧠 Test RIASEC
                        </a>
                    </div>
                </div>

            </div>
        </section>

        {{-- ════ MAIN LAYOUT: SIDEBAR + CONTENT ════ --}}
        <div class="or-layout">

            {{-- ════ SIDEBAR ════ --}}
            <aside class="or-sidebar" id="orSidebar">

                {{-- Search --}}
                <div class="or-sidebar-block">
                    <div class="or-sidebar-label">🔍 Recherche</div>
                    <form method="GET" action="{{ route('student.orientation') }}" id="searchForm">
                        <input type="hidden" name="domaine" value="{{ $domaine }}">
                        <input type="hidden" name="niveau" value="{{ $niveau }}">
                        <div class="or-search-inner">
                            <span class="or-search-icon">🔍</span>
                            <input type="text" name="search" class="or-search-input" id="searchInput"
                                value="{{ $search }}" placeholder="Formation, établissement…"
                                autocomplete="off">
                        </div>
                        <div style="display:flex;gap:.5rem;margin-top:.625rem;">
                            <button type="submit" class="btn-fill" style="flex:1;justify-content:center;">Chercher</button>
                            @if($search || $domaine !== 'Toutes' || $niveau)
                                <a href="{{ route('student.orientation') }}" class="btn-danger" title="Réinitialiser">✕</a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Domaines --}}
                <div class="or-sidebar-block">
                    <div class="or-sidebar-label">🎯 Domaine</div>
                    <div class="or-sidebar-tabs">
                        @foreach($domaines as $d)
                            <a href="{{ route('student.orientation', ['domaine' => $d, 'search' => $search, 'niveau' => $niveau]) }}"
                                class="or-sidebar-tab {{ $domaine === $d ? 'active' : '' }}">
                                {{ $d }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Niveau --}}
                <div class="or-sidebar-block">
                    <div class="or-sidebar-label">📐 Niveau d'études</div>
                    <form method="GET" action="{{ route('student.orientation') }}" id="niveauForm">
                        <input type="hidden" name="domaine" value="{{ $domaine }}">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <div class="or-nivel-grid">
                            <button type="submit" name="niveau" value="" class="or-nivel-btn {{ !$niveau ? 'active' : '' }}">
                                Tous
                            </button>
                            @foreach($niveaux as $n)
                                <button type="submit" name="niveau" value="{{ $n }}" class="or-nivel-btn {{ $niveau === $n ? 'active' : '' }}">
                                    {{ $n }}
                                </button>
                            @endforeach
                        </div>
                    </form>
                </div>

                {{-- Spécialités --}}
                <div class="or-sidebar-block">
                    <div class="or-sidebar-label">🏷️ Spécialités</div>
                    <div class="or-spec-list">
                        @foreach($specialites as $spec)
                            <a href="{{ route('student.orientation', ['domaine' => $spec->domaine, 'search' => $search, 'niveau' => $niveau]) }}"
                                class="or-spec-pill {{ $domaine === $spec->domaine ? 'active' : '' }}">
                                <span class="or-spec-pill-icon">{{ $spec->icon }}</span>
                                <span class="or-spec-pill-name">{{ $spec->nom }}</span>
                                <span class="or-spec-pill-count">{{ $spec->nb_formations }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

            </aside>

            {{-- ════ MAIN CONTENT ════ --}}
            <main class="or-main">

                {{-- Results header --}}
                <div class="or-results-header rev">
                    <div class="or-results-info">
                        <span class="or-results-count-big">{{ $formations->total() }}</span>
                        <span class="or-results-count-label">
                            formation{{ $formations->total() > 1 ? 's' : '' }}
                            @if($domaine !== 'Toutes') · <strong>{{ $domaine }}</strong>@endif
                            @if($niveau) · <strong>{{ $niveau }}</strong>@endif
                            @if($search) · "<em>{{ $search }}</em>"@endif
                        </span>
                    </div>

                    {{-- Mobile filter toggle --}}
                    <button class="or-filter-toggle" id="filterToggle" aria-label="Filtres">
                        ⚙️ Filtres
                    </button>
                </div>

                {{-- Formations --}}
                @php
                    $niveauPill = [
                        'Licence'    => 'pill-sage',
                        'Master'     => 'pill-marine',
                        'Ingénierie' => 'pill-accent',
                        'Doctorat'   => 'pill-gold',
                    ];
                    $colorMap = [
                        'indigo' => 'var(--accent2)',
                        'cyan'   => 'var(--accent3)',
                        'violet' => 'var(--accent)',
                        'green'  => 'var(--accent3)',
                        'amber'  => 'var(--gold)',
                    ];
                @endphp

                @if($formations->isEmpty())
                    <div class="or-empty rev">
                        <div class="or-empty-icon">🔍</div>
                        <h3 class="or-empty-title">Aucune formation trouvée</h3>
                        <p class="or-empty-sub">Essayez d'élargir votre recherche ou de changer de filtre.</p>
                        <a href="{{ route('student.orientation') }}" class="btn-fill">Voir toutes les formations</a>
                    </div>
                @else
                    <div class="or-grid">

                        @foreach($formations as $formation)
                            @php
                                $spec = $formation->specialite;
                                $np   = $niveauPill[$formation->niveau] ?? 'pill-ink';
                                $sc   = $colorMap[$spec->color ?? 'indigo'] ?? 'var(--accent)';
                                $matchScore = $formation->score_matching;
                                $matchColor = $matchScore >= 80 ? 'var(--accent3)' : ($matchScore >= 60 ? 'var(--gold)' : 'var(--accent)');
                            @endphp
                            <article class="or-card btn-fiche rev rev-d{{ ($loop->index % 3) + 1 }}" data-id="{{ $formation->id }}">

                                {{-- Top stripe (match color) --}}
                                <div class="or-card-stripe" style="--stripe-color:{{ $matchColor }};"></div>

                                <div class="or-card-body">

                                    {{-- Row 1 : Level badge + match score --}}
                                    <div class="or-card-row-top">
                                        <div class="or-card-badges">
                                            <span class="pill {{ $np }}">{{ $formation->niveau }}</span>
                                            <span class="pill pill-ink">{{ $spec->icon }} {{ $spec->domaine }}</span>
                                        </div>
                                        <div class="or-match-chip" style="--chip-color:{{ $matchColor }}">
                                            <span class="or-match-num">{{ $matchScore }}%</span>
                                            <span class="or-match-lbl">match</span>
                                        </div>
                                    </div>

                                    {{-- Row 2 : Icon + Name --}}
                                    <div class="or-card-identity">
                                        <div class="or-card-icon" style="--icon-bg:{{ $matchColor }}">{{ $formation->icon }}</div>
                                        <div class="or-card-name">{{ $formation->nom }}</div>
                                    </div>

                                    {{-- Row 3 : Établissement + Ville --}}
                                    <div class="or-card-meta">
                                        <span class="or-meta-item">🏛️ {{ $formation->etablissement }}</span>
                                        <span class="or-meta-sep">·</span>
                                        <span class="or-meta-item">📍 {{ $formation->ville }}</span>
                                        <span class="or-meta-sep">·</span>
                                        <span class="or-meta-item">⏱ {{ $formation->duree }}</span>
                                    </div>

                                    {{-- Row 4 : Match bar --}}
                                    <div class="or-bar-track">
                                        <div class="match-bar-fill or-bar-fill" style="width:{{ $matchScore }}%;background:{{ $matchColor }};"></div>
                                    </div>

                                    {{-- Row 5 : Description --}}
                                    <p class="or-card-desc">{{ $formation->description }}</p>

                                    {{-- Row 6 : Footer (salary + CTA) --}}
                                    <div class="or-card-footer">
                                        <div class="or-salary">
                                            <div class="or-salary-label">Salaire estimé</div>
                                            <div class="or-salary-val">{{ $formation->salaire_min }} – {{ $formation->salaire_max }}</div>
                                        </div>
                                        <div style="display:flex;align-items:center;gap:.5rem">
                                            @php
                                                $inWishlist = in_array($formation->id, $userVoeuxIds ?? []);
                                            @endphp
                                            <button class="btn-voeu {{ $inWishlist ? 'active' : '' }}" data-id="{{ $formation->id }}" title="Ajouter aux vœux" onclick="event.stopPropagation(); window.toggleVoeu(this, {{ $formation->id }})">
                                                {{ $inWishlist ? '❤️' : '🤍' }}
                                            </button>
                                            <button class="or-card-btn" data-id="{{ $formation->id }}">Fiche →</button>
                                        </div>
                                    </div>

                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($formations->hasPages())
                        <nav class="or-pagination" aria-label="Pagination">
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
                        </nav>
                    @endif
                @endif

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
    @foreach($formations as $formation)
        @php
            $spec = $formation->specialite;
            $np   = $niveauPill[$formation->niveau] ?? 'pill-ink';
        @endphp
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
            'niveau_pill'       => $np,
        ]) !!}</script>
    @endforeach

    @include('student.orientation.scripts')

@endsection