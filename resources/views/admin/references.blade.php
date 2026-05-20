@extends('layouts.admin')

@section('title', 'Référentiel Universitaire')

@section('content')
<style>
    /* ════════════════════════════════════════════
       CAPAVENIR UNIVERSITY REFERENCES PANEL
    ════════════════════════════════════════════ */
    .ref-wrapper {
        display: flex;
        flex-direction: column;
        gap: 2.5rem;
        font-family: var(--font-main);
        color: var(--ink);
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

    /* Grid cards for branches */
    .branch-card {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 2rem;
        box-shadow: var(--shadow-card);
        margin-bottom: 1.5rem;
    }
    .branch-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 1px solid var(--glass-border);
        padding-bottom: 1rem;
        margin-bottom: 1rem;
        gap: 1rem;
    }
    .branch-name {
        font-family: var(--font-serif);
        font-size: 1.4rem;
        font-weight: 400;
        font-style: italic;
        color: var(--accent2);
    }
    .branch-score-pill {
        display: inline-block;
        font-family: var(--font-main);
        font-size: 0.75rem;
        font-weight: 700;
        background: color-mix(in srgb, var(--accent2) 8%, transparent);
        color: var(--accent2);
        padding: 0.2rem 0.65rem;
        border-radius: var(--rx);
        border: 1px solid color-mix(in srgb, var(--accent2) 20%, transparent);
        vertical-align: middle;
        margin-left: 0.5rem;
    }

    .branch-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    .branch-table th {
        padding: 0.75rem 1rem;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--ink30);
        border-bottom: 2px solid var(--glass-border);
        text-align: left;
    }
    .branch-table td {
        padding: 0.9rem 1rem;
        border-bottom: 1px solid var(--glass-border);
        font-size: 0.85rem;
        color: var(--ink60);
    }

    /* Criteria Inline Form */
    .criteria-inline-box {
        margin-top: 1.5rem;
        padding: 1.25rem;
        background: var(--ink03);
        border: 1px dashed var(--glass-border);
        border-radius: var(--r);
    }

    .btn-action-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 8px;
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

    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.65rem 1.25rem;
        border-radius: var(--r);
        font-size: 0.82rem;
        font-weight: 700;
        background: var(--ink);
        color: var(--paper);
        border: 1px solid var(--ink);
        cursor: pointer;
        transition: var(--transition);
    }
    .btn-submit:hover {
        background: var(--accent);
        border-color: var(--accent);
        color: white;
    }
    .btn-submit-danger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.5rem 1rem;
        border-radius: var(--r);
        font-size: 0.78rem;
        font-weight: 600;
        background: transparent;
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
        cursor: pointer;
        transition: var(--transition);
    }
    .btn-submit-danger:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }
</style>

<div class="ref-wrapper">
    {{-- Page Title Header --}}
    <div class="glass-card" style="background: var(--ink06); display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h3 style="font-family: var(--font-serif); font-size: 1.4rem; font-weight: 400; font-style: italic; color: var(--ink);">Référentiel Universitaire</h3>
            <p style="font-size: 0.82rem; color: var(--ink60); margin-top: 0.3rem;">Pilotez les filières d'études disponibles, configurez les conditions de score minimum d'admission et ajustez les coefficients des matières.</p>
        </div>
    </div>

    {{-- Onglets de Navigation Système --}}
    <div style="display: flex; gap: 1rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem; margin-bottom: 2rem; flex-wrap: wrap;">
        <a href="{{ route('admin.references.index') }}" style="text-decoration: none; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 700; color: var(--accent); border-bottom: 2px solid var(--accent); transition: var(--transition);">
            Pondérations & Seuils BAC
        </a>
        <a href="{{ route('admin.filieres.import') }}" style="text-decoration: none; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 700; color: var(--ink60); border-bottom: 2px solid transparent; transition: var(--transition);" onmouseover="this.style.color='var(--ink)'" onmouseout="this.style.color='var(--ink60)'">
            Import Excel des Filières IA
        </a>
    </div>

    {{-- Notification Success --}}
    @if(session('success'))
        <div style="background: var(--accent3); color: #fff; padding: 1rem 1.5rem; border-radius: var(--r); display: flex; align-items: center; gap: 0.75rem;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span style="font-size: 0.85rem; font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Form card for adding branch --}}
    <div class="form-card">
        <h3 class="form-title">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Ajouter une Nouvelle Filière
        </h3>
        <form action="{{ route('admin.references.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 1.5rem;">
                <div class="form-group">
                    <label>Intitulé de la filière</label>
                    <input type="text" name="name" class="form-input" required placeholder="ex: Génie Logiciel">
                </div>
                <div class="form-group">
                    <label>Score BAC Requis (Seuil /20)</label>
                    <input type="number" step="0.01" min="0" max="20" name="required_bac_score" class="form-input" required value="10.00">
                </div>
            </div>
            <div class="form-group">
                <label>Description des débouchés & conditions</label>
                <textarea name="description" class="form-input" rows="3" placeholder="Présentation synthétique de la filière et de ses prérequis académiques..."></textarea>
            </div>
            <div style="text-align: right; margin-top: 0.5rem;">
                <button type="submit" class="btn-submit">
                    Enregistrer la Filière
                </button>
            </div>
        </form>
    </div>

    {{-- Branches Iteration --}}
    <div>
        <h4 style="font-family: var(--font-serif); font-size: 1.2rem; font-style: italic; font-weight: 400; color: var(--ink60); margin-bottom: 1.25rem;">
            Filières Référencées
        </h4>

        @foreach($sections as $section)
            <div class="branch-card">
                <div class="branch-header">
                    <div>
                        <h3 class="branch-name">
                            {{ $section->name }} 
                            <span class="branch-score-pill">Moyenne BAC ≥ {{ number_format($section->required_bac_score, 2) }}</span>
                        </h3>
                        @if($section->description)
                            <p style="margin-top: 0.5rem; color: var(--ink60); font-size: 0.85rem; line-height: 1.45;">
                                {{ $section->description }}
                            </p>
                        @endif
                    </div>
                    <form action="{{ route('admin.references.destroy', $section) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette filière ainsi que tous ses coefficients associés ?');">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="btn-submit-danger">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Supprimer
                        </button>
                    </form>
                </div>

                @if($section->criteria->count() > 0)
                    <table class="branch-table">
                        <thead>
                            <tr>
                                <th>Matière Évaluée</th>
                                <th>Coefficient Pondérateur</th>
                                <th style="width: 50px; text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($section->criteria as $criterion)
                                <tr>
                                    <td style="font-weight: 600; color: var(--ink);">{{ $criterion->subject }}</td>
                                    <td>x{{ number_format($criterion->coefficient, 2) }}</td>
                                    <td style="text-align: right;">
                                        <form action="{{ route('admin.references.criteria.destroy', $criterion) }}" method="POST">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-delete" title="Retirer cette matière">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="font-size: 0.8rem; color: var(--ink30); padding: 1rem 0; font-style: italic;">
                        Aucune matière spécifique n'est paramétrée pour le calcul de score de cette filière.
                    </div>
                @endif

                {{-- Add criteria inline form --}}
                <div class="criteria-inline-box">
                    <form action="{{ route('admin.references.criteria.store') }}" method="POST" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
                        @csrf
                        <input type="hidden" name="reference_section_id" value="{{ $section->id }}">
                        
                        <div style="flex: 1.5; min-width: 200px;">
                            <label style="font-size: 0.72rem; font-weight: 700; color: var(--ink60); text-transform: uppercase;">Nom de la Matière</label>
                            <input type="text" name="subject" class="form-input" style="width: 100%; margin-top: 0.25rem;" required placeholder="ex: Mathématiques">
                        </div>

                        <div style="flex: 0.8; min-width: 100px;">
                            <label style="font-size: 0.72rem; font-weight: 700; color: var(--ink60); text-transform: uppercase;">Coefficient</label>
                            <input type="number" step="0.1" name="coefficient" class="form-input" style="width: 100%; margin-top: 0.25rem;" required value="1.0">
                        </div>

                        <button type="submit" class="btn-submit" style="height: 38px; white-space: nowrap;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            Ajouter le Critère
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
