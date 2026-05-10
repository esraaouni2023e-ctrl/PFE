@extends('layouts.admin')

@section('title', 'Audit & Sécurité')

@section('content')
<style>
.audit-container { padding: 2rem; }
.audit-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
.audit-stat-card {
    background: var(--paper); border: 1px solid var(--ink10);
    border-radius: var(--rl); padding: 2rem; text-align: center;
}
.audit-stat-val { font-family: 'Fraunces', serif; font-size: 2.5rem; color: var(--accent2); margin-bottom: 0.5rem; }
.audit-stat-label { font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink60); }
.audit-logs-card {
    background: var(--paper); border: 1px solid var(--ink10); border-radius: var(--rl); overflow: hidden;
}
.audit-logs-header { padding: 1.5rem 2rem; background: var(--cream); border-bottom: 1px solid var(--ink10); }
.audit-title { font-family: 'Fraunces', serif; font-size: 1.25rem; }
.audit-table { width: 100%; border-collapse: collapse; }
.audit-table th, .audit-table td { padding: 1rem 2rem; border-bottom: 1px solid var(--ink06); text-align: left; }
.audit-table th { font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink30); background: var(--cream); }
.audit-table tr:hover { background: var(--ink03); }
</style>

<div class="audit-container">
    <div style="margin-bottom: 2rem;">
        <p class="ad-eyebrow" style="color:var(--accent);">Performances</p>
        <h2 class="ad-sh">Audit & <em>Sécurité</em></h2>
    </div>

    {{-- Stats (Platform performance proxy) --}}
    <div class="audit-grid">
        <div class="audit-stat-card">
            <div class="audit-stat-val">{{ $stats['total_tests'] }}</div>
            <div class="audit-stat-label">Tests Complétés</div>
        </div>
        <div class="audit-stat-card">
            <div class="audit-stat-val" style="color:var(--gold);">{{ $stats['average_score'] }}%</div>
            <div class="audit-stat-label">Précision Globale Moyenne</div>
        </div>
        <div class="audit-stat-card">
            <div class="audit-stat-val" style="color:var(--accent3);">{{ $stats['active_students'] }}</div>
            <div class="audit-stat-label">Étudiants Actifs (Orientés)</div>
        </div>
    </div>

    {{-- Audit Logs --}}
    <div class="audit-logs-card">
        <div class="audit-logs-header">
            <h3 class="audit-title">Journal de Sécurité (Logs RGPD)</h3>
        </div>
        <div style="overflow-x:auto;">
            <table class="audit-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                        <th>Détails</th>
                        <th>Adresse IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td style="font-size:0.85rem; color:var(--ink60);">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td><strong>{{ $log->user->name ?? 'Système' }}</strong><br><span style="font-size:0.75rem; color:var(--ink30);">{{ $log->user->email ?? '' }}</span></td>
                            <td><span style="background:var(--ink10); padding:0.2rem 0.5rem; border-radius:4px; font-size:0.8rem; font-weight:600;">{{ $log->action }}</span></td>
                            <td style="font-size:0.85rem;">{{ $log->details ?? '-' }}</td>
                            <td style="font-family:monospace; font-size:0.8rem; color:var(--ink60);">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:3rem; color:var(--ink30);">
                                Aucun log de sécurité enregistré pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem 2rem; border-top: 1px solid var(--ink10); background: var(--cream);">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
