@extends('layouts.admin')

@section('title', 'Audit & Sécurité')

@section('content')
<style>
    /* ════════════════════════════════════════════
       CAPAVENIR SECURITY AUDIT & LOGS PANEL
    ════════════════════════════════════════════ */
    .audit-wrapper {
        display: flex;
        flex-direction: column;
        gap: 2.5rem;
        font-family: var(--font-main);
        color: var(--ink);
    }

    /* KPI Grid */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
    }
    .kpi-card {
        background: var(--ink06);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 1.5rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        border-color: var(--glass-border-vivid);
        background: var(--ink10);
        box-shadow: var(--shadow-card);
    }
    .kpi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .kpi-title {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--ink60);
    }
    .kpi-icon-wrapper {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: color-mix(in srgb, var(--accent) 8%, transparent);
        color: var(--accent);
    }
    .kpi-value {
        font-family: var(--font-serif);
        font-size: 2.2rem;
        font-weight: 400;
        line-height: 1.1;
        color: var(--ink);
    }
    .kpi-sub {
        font-size: 0.75rem;
        color: var(--ink30);
        margin-top: 0.35rem;
        font-weight: 500;
    }

    /* Table Design */
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
        padding: 1.1rem 2rem;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: var(--ink30);
        border-bottom: 2px solid var(--glass-border);
    }
    .custom-table td {
        padding: 1.25rem 2rem;
        border-bottom: 1px solid var(--glass-border);
        font-size: 0.875rem;
        color: var(--ink60);
        vertical-align: middle;
    }
    .custom-table tr:hover td {
        background: var(--ink06);
        color: var(--ink);
    }

    /* Action labels */
    .badge-action {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.65rem;
        border-radius: var(--rx);
        font-size: 0.72rem;
        font-weight: 700;
        background: var(--ink10);
        color: var(--ink);
        border: 1px solid var(--glass-border);
    }

    /* Flex helpers */
    .user-meta-info {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }
    .user-avatar-text {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--accent2);
        color: var(--paper);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
    }
    .user-name-label {
        font-weight: 600;
        color: var(--ink);
        line-height: 1.2;
    }
    .user-email-label {
        font-size: 0.72rem;
        color: var(--ink30);
    }

    /* IP Badge */
    .ip-badge {
        font-family: monospace;
        font-size: 0.75rem;
        background: var(--ink06);
        border: 1px solid var(--glass-border);
        color: var(--ink60);
        padding: 0.15rem 0.45rem;
        border-radius: 6px;
    }
</style>

<div class="audit-wrapper">
    {{-- Header --}}
    <div class="glass-card" style="background: var(--ink06); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-family: var(--font-serif); font-size: 1.4rem; font-weight: 400; font-style: italic; color: var(--ink);">Audit & Journal de Sécurité</h3>
            <p style="font-size: 0.82rem; color: var(--ink60); margin-top: 0.3rem;">Tracez l'historique complet des actions, des validations de conseillers, des calculs d'orientation et garantissez la conformité RGPD.</p>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Tests Traités</span>
                <div class="kpi-icon-wrapper" style="background: color-mix(in srgb, var(--accent3) 8%, transparent); color: var(--accent3);">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" style="color: var(--accent3);">{{ $stats['total_tests'] }}</div>
            <p class="kpi-sub">Total des diagnostics terminés</p>
        </div>

        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Précision Moyenne</span>
                <div class="kpi-icon-wrapper" style="background: color-mix(in srgb, var(--gold) 8%, transparent); color: var(--gold);">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" style="color: var(--gold);">{{ $stats['average_score'] }}%</div>
            <p class="kpi-sub">Fiabilité globale de l'algorithme CAT</p>
        </div>

        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Étudiants Orientés</span>
                <div class="kpi-icon-wrapper">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value">{{ $stats['active_students'] }}</div>
            <p class="kpi-sub">Apprenants possédant un profil RIASEC complet</p>
        </div>
    </div>

    {{-- Audit Logs --}}
    <div class="custom-table-wrapper">
        <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--glass-border); display: flex; align-items: center; gap: 0.50rem;">
            <svg class="section-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h4 style="font-family: var(--font-serif); font-size: 1.15rem; font-style: italic; font-weight: 400; color: var(--ink);">Journal de Sécurité (Conformité RGPD)</h4>
        </div>
        <div style="overflow-x:auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Date & Heure</th>
                        <th>Utilisateur</th>
                        <th>Action réalisée</th>
                        <th>Détails de l'évènement</th>
                        <th>Adresse IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td style="font-size: 0.82rem; color: var(--ink60);">
                                {{ $log->created_at->timezone('Africa/Tunis')->format('d/m/Y H:i:s') }}
                            </td>
                            <td>
                                <div class="user-meta-info">
                                    <div class="user-avatar-text">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="user-name-label">{{ $log->user->name ?? 'Système' }}</div>
                                        <div class="user-email-label">{{ $log->user->email ?? 'Automate autonome' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-action">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td style="font-size: 0.82rem; line-height: 1.4; color: var(--ink60);">
                                {{ $log->details ?? 'Aucune spécification complémentaire.' }}
                            </td>
                            <td>
                                <span class="ip-badge">{{ $log->ip_address ?? '127.0.0.1' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:3rem; color:var(--ink30);">
                                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="opacity: 0.4; margin-bottom: 0.5rem; display: block; margin-left: auto; margin-right: auto;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Aucun log de sécurité enregistré pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div style="padding:1.5rem 2rem; display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--glass-border); background:var(--ink06);">
            <div style="font-size:0.78rem; color:var(--ink60); font-weight:500;">
                Affichage de {{ $logs->firstItem() }} à {{ $logs->lastItem() }} sur {{ $logs->total() }}
            </div>
            <div class="pagination-custom">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
