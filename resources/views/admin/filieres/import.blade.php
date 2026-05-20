@extends('layouts.admin')

@section('title', 'Import des filières — Admin')

@section('content')
<style>
    /* ════════════════════════════════════════════
       CAPAVENIR BRANCHES IMPORT PANEL
    ════════════════════════════════════════════ */
    .import-wrapper {
        display: flex;
        flex-direction: column;
        gap: 2.5rem;
        font-family: var(--font-main);
        color: var(--ink);
    }

    .dashboard-two-col {
        display: grid;
        grid-template-columns: 1.25fr 0.75fr;
        gap: 2rem;
    }
    @media (max-width: 1024px) {
        .dashboard-two-col {
            grid-template-columns: 1fr;
        }
    }

    .form-card {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 2rem;
        box-shadow: var(--shadow-card);
    }
    .form-title {
        font-family: var(--font-serif);
        font-size: 1.3rem;
        font-style: italic;
        font-weight: 400;
        color: var(--ink);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        margin-bottom: 1.25rem;
    }
    .form-group label {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--ink60);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .form-input {
        background: var(--ink06);
        border: 1px solid var(--glass-border);
        border-radius: 10px;
        padding: 0.7rem 0.9rem;
        font-size: 0.875rem;
        color: var(--ink);
        outline: none;
        transition: var(--transition);
        font-family: var(--font-main);
    }
    .form-input:focus {
        border-color: var(--accent);
        background: var(--paper);
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: var(--r);
        font-size: 0.85rem;
        font-weight: 700;
        background: var(--accent2);
        color: white;
        border: 1px solid var(--accent2);
        cursor: pointer;
        width: 100%;
        transition: var(--transition);
    }
    .btn-submit:hover {
        background: color-mix(in srgb, var(--accent2) 90%, #000);
        border-color: color-mix(in srgb, var(--accent2) 90%, #000);
        transform: translateY(-1px);
    }

    .code-console {
        background: var(--ink06);
        border: 1px solid var(--glass-border);
        border-radius: 10px;
        padding: 1.25rem;
        font-family: monospace;
        font-size: 0.78rem;
        color: var(--ink60);
        margin-top: 1.5rem;
    }

    .category-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.85rem 0;
        border-bottom: 1px solid var(--glass-border);
    }
    .category-row:last-child {
        border-bottom: none;
    }

    .btn-action-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 6px;
        border: 1px solid rgba(239, 68, 68, 0.2);
        background: rgba(239, 68, 68, 0.05);
        color: #ef4444;
        cursor: pointer;
        transition: var(--transition);
    }
    .btn-action-delete:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }

    .alert-box {
        padding: 1rem 1.5rem;
        border-radius: var(--r);
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .alert-success {
        background: var(--accent3);
        color: white;
    }
    .alert-danger {
        background: var(--red);
        color: white;
    }
</style>

<div class="import-wrapper">
    {{-- Header --}}
    <div class="glass-card" style="background: var(--ink06); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 1.5rem;">
        <div>
            <h3 style="font-family: var(--font-serif); font-size: 1.4rem; font-weight: 400; font-style: italic; color: var(--ink);">Importation des Filières</h3>
            <p style="font-size: 0.82rem; color: var(--ink60); margin-top: 0.3rem;">Ajoutez en masse les filières d'études universitaires tunisiennes à l'aide de classeurs Excel structurés.</p>
        </div>
        <span class="badge-role badge-role-counselor" style="font-size: 0.75rem; padding: 0.35rem 0.85rem;">
            {{ number_format($totalRows) }} filières actives
        </span>
    </div>

    {{-- Onglets de Navigation Système --}}
    <div style="display: flex; gap: 1rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem; margin-bottom: 2rem; flex-wrap: wrap;">
        <a href="{{ route('admin.references.index') }}" style="text-decoration: none; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 700; color: var(--ink60); border-bottom: 2px solid transparent; transition: var(--transition);" onmouseover="this.style.color='var(--ink)'" onmouseout="this.style.color='var(--ink60)'">
            Pondérations & Seuils BAC
        </a>
        <a href="{{ route('admin.filieres.import') }}" style="text-decoration: none; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 700; color: var(--accent); border-bottom: 2px solid var(--accent); transition: var(--transition);">
            Import Excel des Filières IA
        </a>
    </div>

    {{-- Session alerts --}}
    @if(session('success'))
        <div class="alert-box alert-success">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert-box alert-danger">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <div class="dashboard-two-col">
        {{-- Import form --}}
        <div class="form-card">
            <h3 class="form-title">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--accent);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                Télécharger un Fichier Excel
            </h3>

            <form action="{{ route('admin.filieres.import.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Catégorie de destination</label>
                    <select name="categorie" required class="form-input" style="cursor: pointer; appearance: none; background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%231e293b\' stroke-width=\'2\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M19.5 8.25l-7.5 7.5-7.5-7.5\'/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1rem;">
                        <option value="">— Sélectionner une catégorie —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('categorie') === $cat ? 'selected' : '' }}>
                                {{ $cat }} — {{ \App\Models\Filiere::CATEGORIES[$cat] ?? $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label>Fichier Excel (.xlsx, .xls, .csv)</label>
                    <input type="file" name="fichier" accept=".xlsx,.xls,.csv" required class="form-input" style="background:var(--ink03); border:1px dashed var(--glass-border); padding: 1.5rem; text-align: center;">
                    <p style="font-size: 0.72rem; color: var(--ink30); margin-top: 0.5rem; line-height: 1.35;">
                        Structure obligatoire : Code_Filiere, Nom_Filiere, Universite, Etablissement, SDO_2023, SDO_2024, SDO_2025, Code_RIASEC, Taux_Employabilite, Croissance_Domaine, Alignment_National, source.
                    </p>
                </div>

                <button type="submit" class="btn-submit">
                    Lancer l'Importation Automatique
                </button>
            </form>

            {{-- Artisan helper --}}
            <div class="code-console">
                <span style="font-weight:700; color:var(--accent2); display:block; margin-bottom:0.5rem;">Console Artisan Alternative :</span>
                php artisan filieres:import<br>
                <span style="color:var(--ink30);"># Import spécifique :</span> php artisan filieres:import --file=INFO_Filieres.xlsx<br>
                <span style="color:var(--ink30);"># Simulation à blanc :</span> php artisan filieres:import --dry-run
            </div>
        </div>

        {{-- Stats by category --}}
        <div class="form-card" style="display:flex; flex-direction:column; gap:1.5rem;">
            <h3 class="form-title" style="margin-bottom:0;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--accent2);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.003 9.003 0 1020.95 12H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
                Volume par Catégorie
            </h3>

            <div>
                @forelse($categories as $cat)
                    <div class="category-row">
                        <div>
                            <strong style="color: var(--accent2); font-size: 0.8rem; font-family: var(--font-main);">{{ $cat }}</strong>
                            <span style="font-size: 0.76rem; color: var(--ink30); margin-left: 0.25rem;">
                                {{ \App\Models\Filiere::CATEGORIES[$cat] ?? '' }}
                            </span>
                        </div>
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <span style="font-weight: 700; font-size: 0.88rem; color: var(--ink);">
                                {{ number_format($stats[$cat] ?? 0) }}
                            </span>
                            
                            @if(($stats[$cat] ?? 0) > 0)
                                <form action="{{ route('admin.filieres.import.destroy', $cat) }}" method="POST" onsubmit="return confirm('Souhaitez-vous purger l\'intégralité des filières de la catégorie {{ $cat }} ? Cette action supprimera les données définitivement.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-delete" title="Purger la catégorie">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="font-size:0.8rem; color:var(--ink30); font-style:italic; text-align:center;">
                        Aucune catégorie n'est répertoriée.
                    </div>
                @endforelse

                <div style="display:flex; justify-content:space-between; margin-top:1.5rem; padding-top:1rem; border-top: 1px solid var(--glass-border); font-family: var(--font-serif); font-size: 1.15rem; font-style: italic; font-weight: 400; color: var(--ink);">
                    <span>Total cumulé</span>
                    <span style="font-family: var(--font-main); font-weight:700; color:var(--accent);">{{ number_format($totalRows) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
