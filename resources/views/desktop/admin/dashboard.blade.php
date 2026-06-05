@extends('layouts.admin')

@section('title', "Centre de Pilotage & d'Orientation")

@section('content')
<style>
    /* ════════════════════════════════════════════
       CAPAVENIR PREMIUM ADMIN DASHBOARD
       Dynamic Glassmorphic Layout
    ════════════════════════════════════════════ */
    .admin-dashboard {
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

    /* Section Layout */
    .dashboard-section {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 2rem;
        box-shadow: var(--shadow-card);
        position: relative;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--glass-border);
        padding-bottom: 1rem;
    }
    .section-title-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .section-title {
        font-family: var(--font-serif);
        font-size: 1.35rem;
        font-weight: 400;
        font-style: italic;
        color: var(--ink);
    }
    .section-icon {
        color: var(--accent);
        flex-shrink: 0;
    }

    /* Two Column Layout */
    .dashboard-two-col {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 2rem;
    }
    @media (max-width: 1024px) {
        .dashboard-two-col {
            grid-template-columns: 1fr;
        }
    }

    /* Table Design */
    .custom-table-wrapper {
        overflow-x: auto;
    }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .custom-table th {
        padding: 0.85rem 1.25rem;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--ink30);
        border-bottom: 2px solid var(--glass-border);
    }
    .custom-table td {
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid var(--glass-border);
        font-size: 0.875rem;
        color: var(--ink60);
        vertical-align: middle;
    }
    .custom-table tr:hover td {
        background: var(--ink06);
        color: var(--ink);
    }

    /* Flex helpers */
    .user-meta-info {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }
    .user-avatar-text {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--ink);
        color: var(--paper);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
    }
    .user-name-label {
        font-weight: 600;
        color: var(--ink);
        line-height: 1.25;
    }
    .user-email-label {
        font-size: 0.75rem;
        color: var(--ink30);
    }

    /* Action buttons */
    .action-btn-group {
        display: flex;
        gap: 0.4rem;
    }
    .btn-sm-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.45rem 0.85rem;
        border-radius: var(--r);
        font-size: 0.76rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        border: 1px solid var(--glass-border);
        background: var(--ink06);
        color: var(--ink60);
        transition: var(--transition);
    }
    .btn-sm-action:hover {
        background: var(--ink10);
        color: var(--ink);
        border-color: var(--ink30);
    }
    .btn-sm-action.primary {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
    }
    .btn-sm-action.primary:hover {
        background: color-mix(in srgb, var(--accent) 90%, #000);
        transform: translateY(-1px);
    }

    /* Badge styles */
    .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.65rem;
        border-radius: var(--rx);
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .badge-pill-orange {
        background: color-mix(in srgb, var(--accent) 10%, transparent);
        color: var(--accent);
        border: 1px solid color-mix(in srgb, var(--accent) 25%, transparent);
    }
    .badge-pill-green {
        background: color-mix(in srgb, var(--accent3) 10%, transparent);
        color: var(--accent3);
        border: 1px solid color-mix(in srgb, var(--accent3) 25%, transparent);
    }
    .badge-pill-red {
        background: color-mix(in srgb, var(--red) 10%, transparent);
        color: var(--red);
        border: 1px solid color-mix(in srgb, var(--red) 25%, transparent);
    }

    /* Empty state */
    .empty-state-card {
        text-align: center;
        padding: 2.5rem 1.5rem;
        color: var(--ink30);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }
    .empty-state-card svg {
        opacity: 0.4;
    }
</style>

<div class="admin-dashboard">
    {{-- ═══ TOP KPIS (ÉTUDIANTS & CONSEILLERS) ═══ --}}
    <div class="kpi-grid">
        {{-- Étudiants KPI --}}
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Étudiants Inscrits</span>
                <div class="kpi-icon-wrapper">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value">{{ $studentCount }}</div>
            <p class="kpi-sub">Total des apprenants sur CapAvenir</p>
        </div>

        {{-- Conseillers KPI --}}
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Conseillers Certifiés</span>
                <div class="kpi-icon-wrapper" style="background: color-mix(in srgb, var(--accent2) 8%, transparent); color: var(--accent2);">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" style="color: var(--accent2);">{{ $approvedCounselorsCount }}</div>
            <p class="kpi-sub">Accompagnateurs actifs sur le CRM</p>
        </div>

        {{-- Candidatures en Attente KPI --}}
        <div class="kpi-card" style="border-bottom: 3px solid {{ $pendingCounselorsCount > 0 ? 'var(--accent)' : 'var(--glass-border)' }};">
            <div class="kpi-header">
                <span class="kpi-title">Demandes en Attente</span>
                <div class="kpi-icon-wrapper" style="background: color-mix(in srgb, var(--accent) 8%, transparent); color: var(--accent);">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" style="color: {{ $pendingCounselorsCount > 0 ? 'var(--accent)' : 'var(--ink)' }};">{{ $pendingCounselorsCount }}</div>
            <p class="kpi-sub">Dossiers de conseillers à vérifier</p>
        </div>

        {{-- Moteur SIAEPI Tests --}}
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Analyses CAT</span>
                <div class="kpi-icon-wrapper" style="background: color-mix(in srgb, var(--accent3) 8%, transparent); color: var(--accent3);">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364.364l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" style="color: var(--accent3);">{{ $totalTests }}</div>
            <p class="kpi-sub">Tests avec certitude ~{{ number_format($avgConfidence, 1) }}%</p>
        </div>
    </div>

    {{-- ═══ SECTION : GESTION DES CONSEILLERS & CANDIDATURES ═══ --}}
    <div class="dashboard-section">
        <div class="section-header">
            <div class="section-title-wrapper">
                <svg class="section-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h2 class="section-title">Validation des Candidatures Conseillers</h2>
            </div>
            @if($pendingCounselorsCount > 0)
                <a href="{{ route('admin.counselors.index') }}" class="btn-sm-action primary">
                    Gérer les demandes ({{ $pendingCounselorsCount }})
                </a>
            @else
                <a href="{{ route('admin.counselors.index') }}" class="btn-sm-action">
                    Historique & Archives
                </a>
            @endif
        </div>

        @if($pendingCounselorsList->count() > 0)
            <div class="custom-table-wrapper">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Candidat</th>
                            <th>Université / Institution</th>
                            <th>Spécialité</th>
                            <th>Expérience</th>
                            <th>Date de soumission</th>
                            <th style="text-align: right;">Action rapide</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingCounselorsList as $csl)
                            @php $p = $csl->counselorProfile; @endphp
                            <tr>
                                <td>
                                    <div class="user-meta-info">
                                        <div class="user-avatar-text" style="background: var(--accent2);">
                                            {{ strtoupper(substr($csl->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="user-name-label">{{ $csl->name }}</div>
                                            <div class="user-email-label">{{ $csl->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $p?->university ?? 'Non renseignée' }}</td>
                                <td>
                                    <span class="badge-pill badge-pill-orange">{{ $p?->specialty ?? 'Orientation générale' }}</span>
                                </td>
                                <td>{{ $p?->experience_years ?? 0 }} an(s)</td>
                                <td>{{ $csl->created_at->timezone('Africa/Tunis')->format('d/m/Y H:i') }}</td>
                                <td style="text-align: right;">
                                    <a href="{{ route('admin.counselors.index') }}" class="btn-sm-action">
                                        Analyser le dossier
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state-card">
                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div style="font-weight: 600; color: var(--ink);">Toutes les candidatures ont été traitées</div>
                <div style="font-size: 0.8rem; color: var(--ink30);">Aucun dossier en attente d'approbation. CapAvenir tourne à plein régime !</div>
            </div>
        @endif
    </div>

    {{-- ═══ SECTION : MOTEUR PSYCHOMÉTRIQUE & ÉTUDIANTS SUSPECTS ═══ --}}
    <div class="dashboard-section" style="border-left: 4px solid var(--red);">
        <div class="section-header">
            <div class="section-title-wrapper">
                <svg class="section-icon" style="color: var(--red);" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h2 class="section-title" style="color: var(--red);">Sécurité SIAEPI : Alertes de Fraude & Profils Suspects</h2>
            </div>
            <span class="badge-pill badge-pill-red" style="font-size: 0.72rem; padding: 0.3rem 0.8rem;">
                {{ $flaggedCount }} Profil(s) Flagué(s)
            </span>
        </div>

        @if($suspectProfiles->count() > 0)
            <div class="custom-table-wrapper">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Étudiant suspect</th>
                            <th>Date du Test</th>
                            <th>Trigramme RIASEC</th>
                            <th>Description de l'Alerte</th>
                            <th>Statut</th>
                            <th style="text-align: right;">Actions de sécurité</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suspectProfiles as $profile)
                            @php 
                                $alerts = json_decode($profile->flag_reason, true) ?? [];
                                $alertCount = array_sum(array_column($alerts, 'count'));
                            @endphp
                            <tr>
                                <td>
                                    <div class="user-meta-info">
                                        <div class="user-avatar-text" style="background: var(--ink);">
                                            {{ strtoupper(substr($profile->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="user-name-label">{{ $profile->user->name ?? 'Étudiant anonyme' }}</div>
                                            <div class="user-email-label">{{ $profile->user->email ?? 'Non renseigné' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $profile->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <strong style="color: var(--accent2);">{{ $profile->code_holland }}</strong> 
                                    <span style="font-size: 0.75rem; color: var(--ink30);">({{ number_format($profile->confidence_score, 1) }}% certitude)</span>
                                </td>
                                <td>
                                    <div style="display:flex; flex-direction:column; gap:0.25rem;">
                                        @foreach($alerts as $type => $data)
                                            <div style="font-size: 0.76rem; line-height: 1.3;">
                                                <strong style="color: var(--red);">[{{ $type }}]</strong> {{ $data['message'] ?? '' }}
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-pill badge-pill-orange">Audit Manuel Requis</span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="action-btn-group" style="justify-content: flex-end;">
                                        <button class="btn-sm-action">
                                            Dossier CAT
                                        </button>
                                        {{-- Actions de lever de flag futures --}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state-card">
                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <div style="font-weight: 600; color: var(--ink);">Aucune alerte de fraude suspectée</div>
                <div style="font-size: 0.8rem; color: var(--ink30);">Le BehavioralAnalyzer de l'algorithme adaptatif n'a levé aucun drapeau critique.</div>
            </div>
        @endif
    </div>

    {{-- ═══ SECTION : GRAPHISME & AUDITS DE SÉCURITÉ ═══ --}}
    <div class="dashboard-two-col">
        {{-- Graphiques --}}
        <div class="dashboard-section" style="display:flex; flex-direction:column; gap:1.5rem;">
            <div class="section-header" style="margin-bottom:0;">
                <div class="section-title-wrapper">
                    <svg class="section-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <h2 class="section-title">Distribution Psychométrique (RIASEC)</h2>
                </div>
            </div>
            <div style="position:relative; width:100%; height:260px;">
                <canvas id="riasecChart"></canvas>
            </div>
        </div>

        {{-- Logs de sécurité / Audit --}}
        <div class="dashboard-section" style="display:flex; flex-direction:column; gap:1.5rem;">
            <div class="section-header" style="margin-bottom:0;">
                <div class="section-title-wrapper">
                    <svg class="section-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <h2 class="section-title">Journal d'Audit & Sécurité</h2>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn-sm-action">
                    Audit complet
                </a>
            </div>

            <div style="display:flex; flex-direction:column; gap:1rem;">
                @foreach($recentLogs as $log)
                    <div style="padding: 0.85rem; background: var(--ink06); border: 1px solid var(--glass-border); border-radius: var(--r); display:flex; flex-direction:column; gap:0.25rem; transition: var(--transition);">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span style="font-size:0.75rem; font-weight:700; color:var(--accent2); text-transform:uppercase;">
                                {{ $log->action }}
                            </span>
                            <span style="font-size:0.68rem; color:var(--ink30);">
                                {{ $log->created_at->timezone('Africa/Tunis')->format('H:i') }} · {{ $log->ip_address }}
                            </span>
                        </div>
                        <div style="font-size:0.8rem; color:var(--ink60); line-height:1.35;">
                            {{ $log->details }}
                        </div>
                        <div style="font-size:0.7rem; color:var(--ink30); font-weight:500;">
                            Auteur : <strong style="color:var(--ink60);">{{ $log->user->name ?? 'Système' }}</strong>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphic Distribution
    const ctx1 = document.getElementById('riasecChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Nombre d\'étudiants',
                data: {!! json_encode($chartData) !!},
                backgroundColor: 'rgba(234, 88, 12, 0.75)',
                borderColor: '#EA580C',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: 'rgba(30, 41, 55, 0.4)'
                    },
                    grid: {
                        color: 'rgba(30, 41, 55, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        color: 'rgba(30, 41, 55, 0.6)',
                        font: {
                            family: 'Fraunces',
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
@endsection