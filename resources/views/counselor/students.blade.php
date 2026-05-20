@extends('layouts.counselor')

@section('page-heading')
Mes<br><em>Étudiants.</em>
@endsection

@section('page-subtitle')
Gérez votre portefeuille d'étudiants, suivez leur progression et identifiez rapidement les profils nécessitant une attention ou une intervention prioritaire.
@endsection

@section('content')
<style>
    .csl-container {
        font-family: var(--font-main);
        display: flex;
        flex-direction: column;
        gap: 2rem;
        padding-bottom: 3rem;
    }

    /* Stats Bar */
    .csl-stats-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
    }

    .csl-stat-card {
        background: var(--paper);
        border: 1px solid var(--ink10);
        border-radius: var(--rl);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: transform .3s var(--ease), border-color .3s var(--ease);
    }

    .csl-stat-card:hover {
        transform: translateY(-2px);
        border-color: var(--accent);
    }

    .csl-stat-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: var(--r);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .csl-stat-info {
        display: flex;
        flex-direction: column;
    }

    .csl-stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.2;
    }

    .csl-stat-label {
        font-size: .8rem;
        color: var(--ink60);
        font-weight: 500;
    }

    /* Filter Bar */
    .csl-filter-bar {
        background: var(--paper);
        border: 1px solid var(--ink10);
        border-radius: var(--rl);
        padding: 1.25rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
        justify-content: space-between;
    }

    .csl-filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        width: 100%;
    }

    .csl-search-wrapper {
        position: relative;
        flex: 1;
        min-width: 250px;
    }

    .csl-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ink30);
        pointer-events: none;
    }

    .csl-search-input {
        width: 100%;
        padding: .75rem 1rem .75rem 2.5rem;
        border: 1px solid var(--ink10);
        border-radius: var(--r);
        background: var(--cream);
        font-size: .88rem;
        font-family: var(--font-main);
        color: var(--ink);
        transition: var(--transition);
    }

    .csl-search-input:focus {
        outline: none;
        border-color: var(--accent);
        background: var(--paper);
    }

    .csl-select {
        padding: .75rem 2rem .75rem 1rem;
        border: 1px solid var(--ink10);
        border-radius: var(--r);
        background: var(--cream);
        font-size: .88rem;
        font-family: var(--font-main);
        color: var(--ink);
        min-width: 180px;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%231e293b' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem;
        transition: var(--transition);
    }

    .csl-select:focus {
        outline: none;
        border-color: var(--accent);
        background-color: var(--paper);
    }

    .csl-btn-reset {
        padding: .75rem 1.25rem;
        border: 1px solid var(--ink10);
        border-radius: var(--r);
        background: var(--paper);
        color: var(--ink60);
        font-size: .88rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .csl-btn-reset:hover {
        background: var(--ink06);
        color: var(--ink);
    }

    .csl-btn-submit {
        padding: .75rem 1.5rem;
        border: none;
        border-radius: var(--r);
        background: var(--accent2);
        color: #fff;
        font-size: .88rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .csl-btn-submit:hover {
        background: var(--accent);
        transform: translateY(-1px);
    }

    /* Grid layout */
    .csl-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    /* Card design */
    .csl-card {
        background: var(--paper);
        border: 1px solid var(--ink10);
        border-radius: var(--rl);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        position: relative;
        transition: var(--transition);
    }

    .csl-card:hover {
        border-color: var(--accent2);
        box-shadow: 0 10px 30px -15px rgba(0, 45, 107, 0.1);
        transform: translateY(-3px);
    }

    .csl-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .csl-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent2), var(--accent));
        color: #fff;
        font-weight: 700;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 2px solid var(--paper);
        box-shadow: 0 0 0 2px var(--ink10);
    }

    .csl-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .csl-student-info {
        flex: 1;
        min-width: 0;
    }

    .csl-student-name {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .csl-student-email {
        font-size: .8rem;
        color: var(--ink60);
        margin: .15rem 0 0 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .csl-badges {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
    }

    .csl-badge {
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: .25rem .65rem;
        border-radius: var(--rx);
        display: inline-flex;
        align-items: center;
        gap: .3rem;
    }

    /* Risk Badges */
    .csl-badge-risk-high {
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .csl-badge-risk-medium {
        background: rgba(245, 158, 11, 0.08);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .csl-badge-risk-standard {
        background: rgba(0, 45, 107, 0.08);
        color: var(--accent2);
        border: 1px solid rgba(0, 45, 107, 0.2);
    }

    .csl-badge-risk-excellent {
        background: rgba(16, 185, 129, 0.08);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    /* Status Badges */
    .csl-badge-status-pending {
        background: var(--ink06);
        color: var(--ink60);
        border: 1px solid var(--ink10);
    }

    .csl-badge-status-ongoing {
        background: rgba(255, 94, 0, 0.08);
        color: var(--accent);
        border: 1px solid rgba(255, 94, 0, 0.2);
    }

    .csl-badge-status-completed {
        background: rgba(16, 185, 129, 0.08);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    /* AI Score Bar */
    .csl-score-section {
        display: flex;
        flex-direction: column;
        gap: .4rem;
        background: var(--ink06);
        padding: .75rem 1rem;
        border-radius: var(--r);
        border: 1px solid var(--ink10);
    }

    .csl-score-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: .75rem;
        font-weight: 600;
        color: var(--ink60);
    }

    .csl-score-value {
        font-weight: 700;
        color: var(--ink);
    }

    .csl-progress-bg {
        width: 100%;
        height: 6px;
        background: var(--ink10);
        border-radius: 3px;
        overflow: hidden;
    }

    .csl-progress-fill {
        height: 100%;
        border-radius: 3px;
        transition: width .6s ease;
    }

    /* Details Section */
    .csl-details {
        font-size: .8rem;
        color: var(--ink60);
        line-height: 1.5;
        flex-grow: 1;
    }

    .csl-details-label {
        font-weight: 700;
        color: var(--ink);
        display: block;
        margin-bottom: .25rem;
        text-transform: uppercase;
        font-size: .65rem;
        letter-spacing: .02em;
    }

    .csl-interests {
        color: var(--ink80);
        font-weight: 500;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Card Footer */
    .csl-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid var(--ink10);
    }

    .csl-meta-stats {
        display: flex;
        gap: .75rem;
        font-size: .72rem;
        color: var(--ink60);
        font-weight: 500;
    }

    .csl-meta-item {
        display: flex;
        align-items: center;
        gap: .3rem;
    }

    .csl-btn-crm {
        font-size: .78rem;
        font-weight: 700;
        color: var(--accent);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: .25rem;
        transition: var(--transition);
    }

    .csl-btn-crm:hover {
        color: var(--accent2);
        transform: translateX(2px);
    }

    /* Empty state */
    .csl-empty-state {
        background: var(--paper);
        border: 1px dashed var(--ink30);
        border-radius: var(--rl);
        padding: 4rem 2rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1.25rem;
    }

    .csl-empty-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--ink06);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ink30);
    }

    .csl-empty-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
    }

    .csl-empty-desc {
        font-size: .88rem;
        color: var(--ink60);
        max-width: 400px;
        margin: 0;
    }

    /* Dark Mode overrides */
    [data-theme="dark"] .csl-stat-card {
        background: var(--ink06);
        border-color: var(--ink10);
    }
    [data-theme="dark"] .csl-filter-bar {
        background: var(--ink06);
        border-color: var(--ink10);
    }
    [data-theme="dark"] .csl-card {
        background: var(--ink06);
        border-color: var(--ink10);
    }
    [data-theme="dark"] .csl-search-input,
    [data-theme="dark"] .csl-select,
    [data-theme="dark"] .csl-btn-reset {
        background-color: var(--paper);
        border-color: var(--ink10);
        color: var(--ink);
    }
    [data-theme="dark"] .csl-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23f8fafc' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    }
    [data-theme="dark"] .csl-empty-state {
        background: var(--ink06);
    }
</style>

<div class="csl-container">

    <!-- A. Stats Bar -->
    <div class="csl-stats-bar">
        <div class="csl-stat-card">
            <div class="csl-stat-icon-wrap" style="background: rgba(0, 45, 107, 0.08); color: var(--accent2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="csl-stat-info">
                <span class="csl-stat-value">{{ $stats['total'] }}</span>
                <span class="csl-stat-label">Total Étudiants</span>
            </div>
        </div>

        <div class="csl-stat-card">
            <div class="csl-stat-icon-wrap" style="background: rgba(16, 185, 129, 0.08); color: #10b981;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="csl-stat-info">
                <span class="csl-stat-value">{{ $stats['completed'] }}</span>
                <span class="csl-stat-label">Profils Clôturés</span>
            </div>
        </div>

        <div class="csl-stat-card">
            <div class="csl-stat-icon-wrap" style="background: rgba(255, 94, 0, 0.08); color: var(--accent);">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="csl-stat-info">
                <span class="csl-stat-value">{{ $stats['ongoing'] }}</span>
                <span class="csl-stat-label">Suivis en Cours</span>
            </div>
        </div>

        <div class="csl-stat-card">
            <div class="csl-stat-icon-wrap" style="background: rgba(239, 68, 68, 0.08); color: #ef4444;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div class="csl-stat-info">
                <span class="csl-stat-value">{{ $stats['atRisk'] }}</span>
                <span class="csl-stat-label">Forte Incohérence IA</span>
            </div>
        </div>
    </div>

    <!-- B. Filter Bar -->
    <div class="csl-filter-bar">
        <form method="GET" action="{{ route('counselor.students') }}" class="csl-filter-form">
            <div class="csl-search-wrapper">
                <svg class="csl-search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher un étudiant par nom ou email..." class="csl-search-input">
            </div>

            <select name="status" class="csl-select">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="ongoing" {{ $statusFilter === 'ongoing' ? 'selected' : '' }}>En cours</option>
                <option value="completed" {{ $statusFilter === 'completed' ? 'selected' : '' }}>Certifiés & clos</option>
            </select>

            <select name="risk" class="csl-select">
                <option value="">Tous les niveaux</option>
                <option value="high" {{ $riskFilter === 'high' ? 'selected' : '' }}>Risque élevé</option>
                <option value="medium" {{ $riskFilter === 'medium' ? 'selected' : '' }}>Surveillance</option>
                <option value="standard" {{ $riskFilter === 'standard' ? 'selected' : '' }}>Standard</option>
                <option value="excellent" {{ $riskFilter === 'excellent' ? 'selected' : '' }}>Excellent</option>
            </select>

            <button type="submit" class="csl-btn-submit">Filtrer</button>

            @if($search || $statusFilter || $riskFilter)
                <a href="{{ route('counselor.students') }}" class="csl-btn-reset" style="text-decoration: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    <!-- C. Students Grid / D. Empty State -->
    @if($studentsData->count() > 0)
        <div class="csl-grid">
            @foreach($studentsData as $s)
                <div class="csl-card">
                    <div class="csl-card-header">
                        <div class="csl-avatar">
                            @if($s['user']->avatar)
                                <img src="{{ asset('storage/' . $s['user']->avatar) }}" alt="{{ $s['user']->name }}">
                            @else
                                {{ strtoupper(substr($s['user']->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="csl-student-info">
                            <h3 class="csl-student-name">{{ $s['user']->name }}</h3>
                            <p class="csl-student-email">{{ $s['user']->email }}</p>
                        </div>
                    </div>

                    <div class="csl-badges">
                        <!-- Risk Badge -->
                        @if($s['riskLevel'] === 'high')
                            <span class="csl-badge csl-badge-risk-high">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                {{ $s['riskLabel'] }}
                            </span>
                        @elseif($s['riskLevel'] === 'medium')
                            <span class="csl-badge csl-badge-risk-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                {{ $s['riskLabel'] }}
                            </span>
                        @elseif($s['riskLevel'] === 'standard')
                            <span class="csl-badge csl-badge-risk-standard">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                {{ $s['riskLabel'] }}
                            </span>
                        @else
                            <span class="csl-badge csl-badge-risk-excellent">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                {{ $s['riskLabel'] }}
                            </span>
                        @endif

                        <!-- Status Badge -->
                        @if($s['status'] === 'completed')
                            <span class="csl-badge csl-badge-status-completed">Certifié</span>
                        @elseif($s['status'] === 'ongoing')
                            <span class="csl-badge csl-badge-status-ongoing">En cours</span>
                        @else
                            <span class="csl-badge csl-badge-status-pending">En attente</span>
                        @endif
                    </div>

                    <!-- AI Score section -->
                    <div class="csl-score-section">
                        <div class="csl-score-header">
                            <span>Indice d'adéquation IA</span>
                            <span class="csl-score-value">{{ $s['aiScore'] }}%</span>
                        </div>
                        <div class="csl-progress-bg">
                            @php
                                $fillColor = '#ef4444'; // Red
                                if ($s['aiScore'] >= 65 && $s['aiScore'] < 78) $fillColor = '#f59e0b'; // Amber
                                elseif ($s['aiScore'] >= 78 && $s['aiScore'] < 90) $fillColor = 'var(--accent2)'; // Blue
                                elseif ($s['aiScore'] >= 90) $fillColor = '#10b981'; // Green
                            @endphp
                            <div class="csl-progress-fill" style="width: {{ $s['aiScore'] }}%; background: {{ $fillColor }};"></div>
                        </div>
                    </div>

                    <!-- Interests details -->
                    <div class="csl-details">
                        <span class="csl-details-label">Secteurs visés / Intérêts</span>
                        <span class="csl-interests">{{ $s['interests'] }}</span>
                    </div>

                    <!-- Footer meta + CRM link -->
                    <div class="csl-card-footer">
                        <div class="csl-meta-stats">
                            <div class="csl-meta-item" title="Rendez-vous planifiés">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                <span>{{ $s['appointmentCount'] }} RDV</span>
                            </div>
                            @if($s['hasRoadmap'])
                                <div class="csl-meta-item" style="color: var(--accent3);" title="Parcours IA Généré">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="6 2 18 2 18 6 6 6 6 2"/><rect width="14" height="14" x="5" y="6" rx="2"/><path d="M9 16h6"/></svg>
                                    <span>Roadmap</span>
                                </div>
                            @endif
                        </div>

                        <a href="{{ route('counselor.student.show', $s['user']->id) }}" class="csl-btn-crm">
                            Profil CRM
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="csl-empty-state">
            <div class="csl-empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            </div>
            <h3 class="csl-empty-title">Aucun étudiant trouvé</h3>
            <p class="csl-empty-desc">Aucun profil ne correspond aux critères de recherche et de filtrage actuels.</p>
            <a href="{{ route('counselor.students') }}" class="csl-btn-submit" style="text-decoration: none; margin-top: .5rem;">Voir tous les étudiants</a>
        </div>
    @endif

</div>
@endsection
