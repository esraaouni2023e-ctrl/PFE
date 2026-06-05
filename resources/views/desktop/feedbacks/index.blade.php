@extends('layouts.admin')

@section('title', 'Gestion des Feedbacks')

@section('content')
<style>
    /* ════════════════════════════════════════════
       CAPAVENIR RECOMMENDATION FEEDBACKS PANEL
    ════════════════════════════════════════════ */
    .feedbacks-wrapper {
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

    /* Rating stars */
    .star-active {
        color: var(--gold);
    }
    .star-inactive {
        color: var(--ink10);
    }

    /* Badge styles */
    .badge-relevant {
        background: color-mix(in srgb, var(--accent3) 12%, transparent);
        color: var(--accent3);
        border: 1px solid color-mix(in srgb, var(--accent3) 25%, transparent);
        padding: 0.2rem 0.6rem;
        border-radius: var(--rx);
        font-size: 0.72rem;
        font-weight: 700;
    }
    .badge-irrelevant {
        background: color-mix(in srgb, #ef4444 12%, transparent);
        color: #ef4444;
        border: 1px solid color-mix(in srgb, #ef4444 25%, transparent);
        padding: 0.2rem 0.6rem;
        border-radius: var(--rx);
        font-size: 0.72rem;
        font-weight: 700;
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
</style>

<div class="feedbacks-wrapper">
    {{-- Header --}}
    <div class="glass-card" style="background: var(--ink06); display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 2rem; border-radius: var(--rl); border: 1px solid var(--glass-border);">
        <div>
            <h3 style="font-family: var(--font-serif); font-size: 1.4rem; font-weight: 400; font-style: italic; color: var(--ink);">Retours & Feedbacks Recommandations</h3>
            <p style="font-size: 0.82rem; color: var(--ink60); margin-top: 0.3rem;">Visualisez l'adéquation ressentie par les étudiants vis-à-vis des suggestions de l'IA SIAEPI v5.0 et ajustez l'algorithme.</p>
        </div>
    </div>

    {{-- KPIs --}}
    @php
        $totalFeedback = \App\Models\RecommendationFeedback::count();
        $relevantCount = \App\Models\RecommendationFeedback::where('is_relevant', true)->count();
        $avgStarRating = round(\App\Models\RecommendationFeedback::avg('rating') ?? 0, 2);
        $relevancePct = $totalFeedback > 0 ? round(($relevantCount / $totalFeedback) * 100, 1) : 0;
    @endphp
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Total Feedbacks</span>
                <div class="kpi-icon-wrapper" style="background: color-mix(in srgb, var(--accent) 8%, transparent); color: var(--accent);">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value">{{ $totalFeedback }}</div>
            <p class="kpi-sub">Retours enregistrés en base</p>
        </div>

        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Taux de Pertinence</span>
                <div class="kpi-icon-wrapper" style="background: color-mix(in srgb, var(--accent3) 8%, transparent); color: var(--accent3);">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h47m0 0l-3-3m3 3l-3 3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" style="color: var(--accent3);">{{ $relevancePct }}%</div>
            <p class="kpi-sub">Adéquation positive des filières proposées</p>
        </div>

        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Note Moyenne</span>
                <div class="kpi-icon-wrapper" style="background: color-mix(in srgb, var(--gold) 8%, transparent); color: var(--gold);">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.367 1.243.583 1.83l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.178 0l-3.97 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.83.582-1.83h4.907a1 1 0 00.95-.69l1.519-4.674z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" style="color: var(--gold);">{{ $avgStarRating }} <span style="font-size:1.2rem; font-weight:300;">/ 5</span></div>
            <p class="kpi-sub">Adéquation moyenne qualitative</p>
        </div>
    </div>

    {{-- Aggregated feedbacks by filiere --}}
    <div class="custom-table-wrapper">
        <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--glass-border); display: flex; align-items: center; gap: 0.50rem;">
            <svg class="section-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent3);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <h4 style="font-family: var(--font-serif); font-size: 1.15rem; font-style: italic; font-weight: 400; color: var(--ink);">Synthèse par Filière</h4>
        </div>
        <div style="overflow-x:auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Code Filière</th>
                        <th>Nom de la Filière</th>
                        <th>Total Votes</th>
                        <th>Recommandation jugée utile</th>
                        <th>Note d'adéquation moy.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aggregated as $row)
                        @php
                            $filiereDb = \App\Models\Filiere::where('code_filiere', $row->filiere_code)->first();
                            $pRelevance = $row->total_count > 0 ? round(($row->positive_count / $row->total_count) * 100) : 0;
                        @endphp
                        <tr>
                            <td style="font-family: monospace; font-weight:700; color:var(--accent);">{{ $row->filiere_code }}</td>
                            <td style="font-weight:600; color:var(--ink);">{{ $filiereDb ? $filiereDb->nom_filiere : 'Filière Excel ou Externe' }}</td>
                            <td>{{ $row->total_count }}</td>
                            <td>
                                <span class="{{ $pRelevance >= 70 ? 'badge-relevant' : 'badge-irrelevant' }}">
                                    {{ $pRelevance }}% positive
                                </span>
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:0.4rem;">
                                    <span style="font-weight:700; color:var(--gold);">{{ round($row->avg_rating, 2) }}</span>
                                    <div style="display:inline-flex;">
                                        @for($star = 1; $star <= 5; $star++)
                                            <span class="{{ $star <= $row->avg_rating ? 'star-active' : 'star-inactive' }}">★</span>
                                        @endfor
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:3rem; color:var(--ink30);">Aucune donnée de synthèse pour le moment.</td>
                        </tr>
                    @endempty
                </tbody>
            </table>
        </div>
    </div>

    {{-- Detailed Feedback Table --}}
    <div class="custom-table-wrapper">
        <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--glass-border); display: flex; align-items: center; gap: 0.50rem;">
            <svg class="section-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
            </svg>
            <h4 style="font-family: var(--font-serif); font-size: 1.15rem; font-style: italic; font-weight: 400; color: var(--ink);">Retours détaillés des Étudiants</h4>
        </div>
        <div style="overflow-x:auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Date & Heure</th>
                        <th>Étudiant</th>
                        <th>Filière évaluée</th>
                        <th>Verdict</th>
                        <th>Note</th>
                        <th>Commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $fb)
                        @php
                            $filiereDb = \App\Models\Filiere::where('code_filiere', $fb->filiere_code)->first();
                        @endphp
                        <tr>
                            <td style="font-size: 0.82rem; color: var(--ink60);">
                                {{ $fb->created_at->timezone('Africa/Tunis')->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <div class="user-meta-info">
                                    <div class="user-avatar-text">
                                        {{ strtoupper(substr($fb->user->name ?? 'E', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="user-name-label">{{ $fb->user->name ?? 'Étudiant anonymisé' }}</div>
                                        <div class="user-email-label">{{ $fb->user->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight:600; color:var(--ink);">{{ $filiereDb ? $filiereDb->nom_filiere : 'Filière Excel ou Externe' }}</div>
                                <div style="font-family: monospace; font-size:0.75rem; color:var(--ink30);">{{ $fb->filiere_code }}</div>
                            </td>
                            <td>
                                <span class="{{ $fb->is_relevant ? 'badge-relevant' : 'badge-irrelevant' }}">
                                    {{ $fb->is_relevant ? '👍 Pertinent' : '👎 Non pertinent' }}
                                </span>
                            </td>
                            <td>
                                <div style="display:inline-flex;">
                                    @for($star = 1; $star <= 5; $star++)
                                        <span class="{{ $star <= $fb->rating ? 'star-active' : 'star-inactive' }}">★</span>
                                    @endfor
                                </div>
                            </td>
                            <td style="font-size: 0.82rem; line-height: 1.4; color: var(--ink60); max-width: 250px;">
                                {{ $fb->comment ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:3rem; color:var(--ink30);">
                                Aucun retour d'adéquation soumis pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div style="padding:1.5rem 2rem; display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--glass-border); background:var(--ink06);">
            <div style="font-size:0.78rem; color:var(--ink60); font-weight:500;">
                Affichage de {{ $feedbacks->firstItem() }} à {{ $feedbacks->lastItem() }} sur {{ $feedbacks->total() }}
            </div>
            <div class="pagination-custom">
                {{ $feedbacks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
