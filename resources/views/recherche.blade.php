@extends('layouts.student')

@section('title', 'Recherche de Filières')

@section('content')
    @include('student.orientation.styles')

    <div class="or" id="orRoot">

        {{-- ════ HERO COMPACT ════ --}}
        <section class="or-hero">
            <div class="or-hero-bgword">Filières</div>
            <div class="or-hero-orb"></div>

            <div class="or-hero-inner">
                <div class="or-eyebrow">
                    <span class="or-eyebrow-dot"></span>
                    Base de données · Tunisie 2024/2025
                </div>

                <h1 class="or-hero-title">
                    Recherche de <em>Filières</em>
                </h1>

                <p class="or-hero-sub">
                    Explorez toutes les filières universitaires tunisiennes. Consultez les scores d'orientation (SDO) des années précédentes pour mieux orienter votre choix.
                </p>

                <div class="or-hero-actions">
                    <div class="or-hero-meta">
                        <span class="pill pill-accent">📚 {{ \App\Models\Filiere::count() }} filières</span>
                        <span class="pill pill-marine">🇹🇳 {{ count($domaines) }} domaines</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ════ MAIN LAYOUT: SIDEBAR + CONTENT ════ --}}
        <div class="or-layout">

            {{-- ════ SIDEBAR ════ --}}
            <aside class="or-sidebar" id="orSidebar">
                <div class="or-sidebar-block">
                    <div class="or-sidebar-label">🔍 Recherche</div>
                    <form method="GET" action="{{ route('recherche') }}">
                        <div class="or-search-inner">
                            <span class="or-search-icon">🔍</span>
                            <input type="text" name="recherche" class="or-search-input" 
                                value="{{ request('recherche') }}" placeholder="Nom, code, établissement..."
                                autocomplete="off">
                        </div>
                        
                        <div style="margin-top: 1rem;">
                            <div class="or-sidebar-label">🎯 Domaine</div>
                            <div class="or-sidebar-tabs">
                                @foreach($domaines as $d)
                                    @php 
                                        $d_val = ($d === 'Toutes') ? '' : $d;
                                        $isActive = request('domaine') == $d_val || (request('domaine') == '' && $d === 'Toutes');
                                    @endphp
                                    <a href="{{ route('recherche', array_merge(request()->all(), ['domaine' => $d_val])) }}" 
                                       class="or-sidebar-tab {{ $isActive ? 'active' : '' }}">
                                        {{ $d }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div style="margin-top: 1rem;">
                            <div class="or-sidebar-label">🏛️ Établissement</div>
                            <select name="etablissement" class="or-search-input" style="padding-left: 0.9rem;">
                                <option value="">Tous les établissements</option>
                                @foreach($etablissements as $etab)
                                    <option value="{{ $etab }}" {{ request('etablissement') == $etab ? 'selected' : '' }}>
                                        {{ $etab }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="display:flex;gap:.5rem;margin-top:1.5rem;">
                            <button type="submit" class="btn-fill" style="flex:1;justify-content:center;">Chercher</button>
                            <a href="{{ route('recherche') }}" class="btn-danger" title="Réinitialiser">✕</a>
                        </div>
                    </form>
                </div>
            </aside>

            {{-- ════ MAIN CONTENT ════ --}}
            <main class="or-main">
                <div class="or-results-header">
                    <div class="or-results-info">
                        <span class="or-results-count-big">{{ $filieres->total() }}</span>
                        <span class="or-results-count-label">
                            formation{{ $filieres->total() > 1 ? 's' : '' }} trouvée{{ $filieres->total() > 1 ? 's' : '' }}
                        </span>
                    </div>
                </div>

                <div class="or-grid">
                    @forelse($filieres as $filiere)
                        <article class="or-card">
                            {{-- Top stripe --}}
                            <div class="or-card-stripe" style="--stripe-color: var(--accent); width: 100%; height: 4px;"></div>

                            <div class="or-card-body">
                                {{-- Row 1: Code & Domaine --}}
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                    <span style="font-size: 0.65rem; font-weight: 700; color: var(--ink30); text-transform: uppercase; letter-spacing: 0.05em;">
                                        #{{ $filiere->code_filiere }}
                                    </span>
                                    <span class="pill pill-accent" style="font-size: 0.6rem;">
                                        {{ $filiere->domaine }}
                                    </span>
                                </div>

                                {{-- Row 2: Nom --}}
                                <h3 style="font-family: var(--font-serif); font-size: 1rem; font-weight: 600; color: var(--ink); margin-bottom: 0.25rem; line-height: 1.3; min-height: 2.6rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $filiere->nom_filiere }}
                                </h3>

                                {{-- Row 3: Etablissement --}}
                                <div style="font-size: 0.8rem; font-weight: 600; color: var(--ink60); display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">
                                    🏛️ {{ $filiere->etablissement }}
                                </div>

                                {{-- Row 4: Université --}}
                                <div style="font-size: 0.7rem; color: var(--ink30); margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $filiere->universite }}
                                </div>

                                {{-- Row 5: SDO Metrics --}}
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
                        <div style="grid-column: 1 / -1; padding: 5rem 2rem; text-align: center; background: var(--cream); border-radius: var(--rl); border: 1px solid var(--ink10);">
                            <div style="font-size: 3rem; margin-bottom: 1.25rem;">🔍</div>
                            <h3 style="font-family: var(--font-serif); font-size: 1.8rem; font-weight: 300; color: var(--ink); margin-bottom: 0.625rem;">Aucune formation trouvée</h3>
                            <p style="font-size: 0.9rem; color: var(--ink60); margin-bottom: 2rem;">Essayez de modifier vos filtres ou d'élargir votre recherche.</p>
                            <a href="{{ route('recherche') }}" class="btn-fill">Réinitialiser la recherche</a>
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
    </div>

    <style>
        /* Custom styles for the table and select */
        select.or-search-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%230b0c10' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m2 4 4 4 4-4'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.9rem center;
            padding-right: 2.5rem;
        }
        
        /* Pagination custom styling to match the theme */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            list-style: none;
        }
        .page-item .page-link {
            border: 1px solid var(--ink10);
            background: var(--paper);
            color: var(--ink60);
            padding: 0.5rem 1rem;
            border-radius: var(--r);
            text-decoration: none;
            font-weight: 600;
        }
        .page-item.active .page-link {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
        }
        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
@endsection
