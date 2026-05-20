@extends('layouts.admin')
@section('title', 'Questions RIASEC')

@section('content')
<style>
    /* ════════════════════════════════════════════
       CAPAVENIR RIASEC QUESTIONS ENGINE LIST
    ════════════════════════════════════════════ */
    .questions-wrapper {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        font-family: var(--font-main);
        color: var(--ink);
    }

    /* Dim stats bar */
    .dim-count-bar {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex-wrap: wrap;
    }
    .dim-count-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--ink60);
    }
    .dim-dot {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 6px;
        font-family: var(--font-serif);
        font-weight: 700;
        font-size: 0.82rem;
        color: white;
        flex-shrink: 0;
    }

    /* Filters Box */
    .filters-bar {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        background: var(--ink03);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 1rem;
    }
    .filter-select, .filter-input {
        font-family: var(--font-main);
        font-size: 0.8rem;
        font-weight: 600;
        background: var(--paper);
        border: 1px solid var(--glass-border);
        color: var(--ink);
        border-radius: 8px;
        padding: 0.55rem 0.85rem;
        outline: none;
        transition: var(--transition);
    }
    .filter-select:focus, .filter-input:focus {
        border-color: var(--accent);
    }
    .filter-input {
        min-width: 220px;
        flex: 1;
    }

    /* Table */
    .custom-table-wrapper {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        overflow: hidden;
        box-shadow: var(--shadow-card);
    }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .custom-table th {
        padding: 1.1rem 1.5rem;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: var(--ink30);
        border-bottom: 2px solid var(--glass-border);
    }
    .custom-table td {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--glass-border);
        font-size: 0.85rem;
        color: var(--ink60);
        vertical-align: middle;
    }
    .custom-table tr:hover td {
        background: var(--ink06);
        color: var(--ink);
    }

    /* Toggle Switch */
    .act-toggle {
        display: inline-block;
        width: 38px;
        height: 20px;
        position: relative;
        cursor: pointer;
    }
    .act-toggle input {
        display: none;
    }
    .act-toggle-track {
        width: 100%;
        height: 100%;
        border-radius: 99px;
        background: var(--ink15);
        transition: background .3s;
        border: 1px solid var(--glass-border);
    }
    .act-toggle input:checked ~ .act-toggle-track {
        background: var(--accent3);
    }
    .act-toggle-thumb {
        position: absolute;
        top: 2px;
        left: 2px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #fff;
        transition: transform .3s var(--ease);
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }
    .act-toggle input:checked ~ .act-toggle-thumb {
        transform: translateX(18px);
    }

    /* Action Buttons */
    .btn-action-glass {
        width: 30px;
        height: 30px;
        padding: 0;
        border-radius: 8px;
        border: 1px solid var(--glass-border);
        background: var(--ink06);
        color: var(--ink60);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: var(--transition);
    }
    .btn-action-glass:hover {
        background: var(--ink10);
        color: var(--ink);
        border-color: var(--ink30);
    }
    .btn-action-glass.danger:hover {
        background: color-mix(in srgb, var(--red) 10%, transparent);
        color: var(--red);
        border-color: color-mix(in srgb, var(--red) 30%, transparent);
    }

    .btn-header-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.25rem;
        border-radius: var(--r);
        font-size: 0.82rem;
        font-weight: 700;
        background: var(--accent2);
        color: white;
        text-decoration: none;
        border: 1px solid var(--accent2);
        transition: var(--transition);
    }
    .btn-header-primary:hover {
        background: color-mix(in srgb, var(--accent2) 90%, #000);
        border-color: color-mix(in srgb, var(--accent2) 90%, #000);
        transform: translateY(-1px);
    }
    
    .btn-header-glass {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.65rem 1.25rem;
        border-radius: var(--r);
        font-size: 0.82rem;
        font-weight: 600;
        background: var(--ink06);
        color: var(--ink60);
        text-decoration: none;
        border: 1px solid var(--glass-border);
        transition: var(--transition);
        cursor: pointer;
    }
    .btn-header-glass:hover {
        background: var(--ink10);
        color: var(--ink);
        border-color: var(--ink30);
    }
</style>

<div class="questions-wrapper">
    {{-- Onglets de Navigation IA --}}
    <div style="display: flex; gap: 1rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
        <a href="{{ route('admin.riasec.dashboard') }}" style="text-decoration: none; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 700; color: var(--ink60); border-bottom: 2px solid transparent; transition: var(--transition);" onmouseover="this.style.color='var(--ink)'" onmouseout="this.style.color='var(--ink60)'">
            Analyses & Statistiques Globales
        </a>
        <a href="{{ route('admin.riasec.questions.index') }}" style="text-decoration: none; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 700; color: var(--accent); border-bottom: 2px solid var(--accent); transition: var(--transition);">
            Banque de Questions RIASEC
        </a>
    </div>

    {{-- Top Flash Alert --}}
    @if(session('success'))
        <div style="background: var(--accent3); color: #fff; padding: 1rem 1.5rem; border-radius: var(--r); display: flex; align-items: center; gap: 0.75rem; font-size: 0.85rem; font-weight: 600;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Actions Bar / Summary --}}
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
        <div class="dim-count-bar">
            @php
                $dimColors2 = ['R'=>'#f97316','I'=>'#3b82f6','A'=>'#ec4899','S'=>'#10b981','E'=>'#8b5cf6','C'=>'#94a3b8'];
            @endphp
            @foreach($dimColors2 as $d => $color)
                <div class="dim-count-item" title="Dimension {{ $d }}">
                    <div class="dim-dot" style="background: {{ $color }};">
                        {{ $d }}
                    </div>
                    <span style="font-family: var(--font-serif); font-size: 0.95rem; font-weight: 400; color: var(--ink);">
                        {{ $stats['byDim'][$d] ?? 0 }}
                    </span>
                </div>
            @endforeach
            <span style="font-size: 0.75rem; color: var(--ink30); font-weight: 700; margin-left: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">
                Total : {{ $stats['total'] }} / Actives : {{ $stats['actives'] }}
            </span>
        </div>
        
        <div style="display: flex; gap: 0.6rem; align-items: center;">
            <a href="{{ route('admin.riasec.questions.create') }}" class="btn-header-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle question
            </a>
            <a href="{{ route('admin.riasec.export') }}" class="btn-header-glass">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                CSV
            </a>
            <a href="{{ route('admin.riasec.dashboard') }}" class="btn-header-glass">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Analytique
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.riasec.questions.index') }}" class="filters-bar">
        <input type="text" name="q" placeholder="Rechercher dans le texte..." value="{{ request('q') }}" class="filter-input">

        <select name="dimension" class="filter-select">
            <option value="">Toutes les dimensions</option>
            @foreach(['R','I','A','S','E','C'] as $d)
                <option value="{{ $d }}" {{ request('dimension') === $d ? 'selected' : '' }}>
                    {{ $d }} — {{ ['R'=>'Réaliste','I'=>'Investigateur','A'=>'Artistique','S'=>'Social','E'=>'Entreprenant','C'=>'Conventionnel'][$d] }}
                </option>
            @endforeach
        </select>

        <select name="categorie" class="filter-select">
            <option value="">Toutes catégories</option>
            <option value="loisirs" {{ request('categorie') === 'loisirs' ? 'selected' : '' }}>Loisirs</option>
            <option value="preferences_professionnelles" {{ request('categorie') === 'preferences_professionnelles' ? 'selected' : '' }}>Préférences pro.</option>
            <option value="qualites_personnelles" {{ request('categorie') === 'qualites_personnelles' ? 'selected' : '' }}>Qualités personnelles</option>
        </select>

        <select name="actif" class="filter-select">
            <option value="">Tous les statuts</option>
            <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actives uniquement</option>
            <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactives uniquement</option>
        </select>

        <button type="submit" class="btn-header-primary" style="padding: 0.55rem 1.25rem;">Filtrer</button>
        
        @if(request()->hasAny(['q','dimension','categorie','actif']))
            <a href="{{ route('admin.riasec.questions.index') }}" class="btn-header-glass" style="padding: 0.55rem 1rem;">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Réinitialiser
            </a>
        @endif
    </form>

    {{-- Questions Table --}}
    <div class="custom-table-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th style="width: 60px;">Dim.</th>
                    <th style="width: 140px;">Catégorie</th>
                    <th>Texte de la question</th>
                    <th style="width: 80px;">Poids</th>
                    <th style="width: 80px;">Ordre</th>
                    <th style="width: 100px;">Type</th>
                    <th style="width: 80px;">Statut</th>
                    <th style="width: 100px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $q)
                    @php
                        $catLabels = ['loisirs'=>'Loisirs','preferences_professionnelles'=>'Pref. Pro','qualites_personnelles'=>'Qualités'];
                        $catColors = ['loisirs'=>'var(--gold)','preferences_professionnelles'=>'var(--accent2)','qualites_personnelles'=>'var(--accent3)'];
                    @endphp
                    <tr>
                        <td style="color: var(--ink30); font-size: 0.78rem; font-weight: 700;">#{{ $q->id }}</td>
                        <td>
                            <div class="dim-dot" style="background: {{ $dimColors2[$q->dimension] ?? '#888' }}">
                                {{ $q->dimension }}
                            </div>
                        </td>
                        <td>
                            <span style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: {{ $catColors[$q->categorie] ?? 'var(--ink30)' }}; border: 1px solid color-mix(in srgb, {{ $catColors[$q->categorie] ?? 'var(--ink30)' }} 30%, transparent); padding: 0.15rem 0.45rem; border-radius: 4px; background: color-mix(in srgb, {{ $catColors[$q->categorie] ?? 'var(--ink30)' }} 6%, transparent);">
                                {{ $catLabels[$q->categorie] ?? $q->categorie }}
                            </span>
                        </td>
                        <td style="font-weight: 600; color: var(--ink); line-height: 1.45;">
                            {{ Str::limit($q->texte_fr, 90) }}
                        </td>
                        <td>
                            <span style="font-weight: 700; color: {{ $q->poids > 1 ? 'var(--gold)' : 'var(--ink30)' }}; display: inline-flex; align-items: center; gap: 0.2rem;">
                                {{ $q->poids }}
                                @if($q->poids > 1)
                                    <svg width="12" height="12" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--gold);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.969 0 1.371 1.24.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.176 0l-3.97 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.97-2.883c-.783-.57-.38-1.81.588-1.81h4.906a1 1 0 00.95-.69l1.519-4.674z" />
                                    </svg>
                                @endif
                            </span>
                        </td>
                        <td style="font-size: 0.78rem; color: var(--ink30); font-weight: 700;">
                            {{ $q->ordre }}
                        </td>
                        <td>
                            <span style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase; color: var(--ink30); letter-spacing: 0.05em;">
                                {{ strtoupper($q->type_reponse) }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.riasec.questions.toggle', $q) }}" method="POST">
                                @csrf
                                <label class="act-toggle" title="{{ $q->actif ? 'Désactiver' : 'Activer' }}">
                                    <input type="checkbox" {{ $q->actif ? 'checked' : '' }} onchange="this.closest('form').submit()">
                                    <div class="act-toggle-track"></div>
                                    <div class="act-toggle-thumb"></div>
                                </label>
                            </form>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: inline-flex; align-items: center; gap: 0.4rem; justify-content: flex-end;">
                                <a href="{{ route('admin.riasec.questions.edit', $q) }}" class="btn-action-glass" title="Modifier la question">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>

                                <form action="{{ route('admin.riasec.questions.destroy', $q) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette question ? Cette action est irréversible.');">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-glass danger" title="Supprimer définitivement">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 4rem 2rem; color: var(--ink30);">
                            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="opacity: 0.3; margin-bottom: 0.75rem; display: block; margin-left: auto; margin-right: auto;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span style="font-weight: 600;">Aucune question trouvée</span>
                            <p style="font-size: 0.8rem; margin-top: 0.25rem;">Modifiez vos critères de recherche ou <a href="{{ route('admin.riasec.questions.create') }}" style="color: var(--accent); font-weight: 700; text-decoration: none;">ajoutez-en une nouvelle</a>.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        {{-- Pagination footer --}}
        @if($questions->hasPages())
            <div style="padding: 1.5rem 2rem; display: flex; justify-content: center; border-top: 1px solid var(--glass-border); background: var(--ink06);">
                {{ $questions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
