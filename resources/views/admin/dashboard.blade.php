@extends('layouts.admin')

@section('title', 'SIAEPI v2.0 Dashboard')

@section('content')
<style>
/* ════════════════════════════════════════════
   SIAEPI v2.0 ADMIN DASHBOARD
════════════════════════════════════════════ */
.siaepi {
    --ink: #0b0c10; --paper: #f7f5f0; --cream: #ede9e1; --warm: #e8e1d4;
    --accent: #d4622a; --accent2: #1a4f6e; --accent3: #4a7c59; --red: #b83232;
    --r: 8px; font-family: 'DM Sans', sans-serif;
    color: var(--ink);
}
.siaepi-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
.siaepi-card { background: var(--cream); padding: 1.5rem; border-radius: var(--r); border: 1px solid rgba(11,12,16,0.1); }
.siaepi-card h3 { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: rgba(11,12,16,0.5); margin-bottom: 0.5rem; }
.siaepi-card .val { font-family: 'Fraunces', serif; font-size: 2.5rem; font-weight: 600; line-height: 1; }
.siaepi-section { margin-bottom: 2.5rem; background: var(--paper); padding: 2rem; border-radius: var(--r); border: 1px solid rgba(11,12,16,0.1); }
.siaepi-section h2 { font-family: 'Fraunces', serif; font-size: 1.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(11,12,16,0.1); padding-bottom: 0.5rem; }

/* Table */
.st { width: 100%; border-collapse: collapse; }
.st th { text-align: left; padding: 0.75rem; border-bottom: 2px solid rgba(11,12,16,0.1); font-size: 0.85rem; text-transform: uppercase; }
.st td { padding: 1rem 0.75rem; border-bottom: 1px solid rgba(11,12,16,0.05); vertical-align: middle; }
.badge { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold; }
.badge-red { background: rgba(184,50,50,0.1); color: var(--red); }
.badge-orange { background: rgba(212,98,42,0.1); color: var(--accent); }
.badge-green { background: rgba(74,124,89,0.1); color: var(--accent3); }
.btn-sm { padding: 0.35rem 0.75rem; font-size: 0.8rem; background: var(--ink); color: #fff; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
.btn-outline { background: transparent; border: 1px solid var(--ink); color: var(--ink); }
</style>

<div class="siaepi">
    <div style="margin-bottom: 2rem;">
        <p style="font-weight:bold; letter-spacing: 2px; color: var(--accent); font-size:0.8rem;">📊 ADMIN - CAPAVENIR SIAEPI v2.0</p>
        <h1 style="font-family: 'Fraunces', serif; font-size: 2.5rem;">Centre de Contrôle Psychométrique</h1>
    </div>

    {{-- KPIs --}}
    <div class="siaepi-grid">
        <div class="siaepi-card">
            <h3>Tests Complets</h3>
            <div class="val">{{ $totalTests }}</div>
        </div>
        <div class="siaepi-card" style="border-bottom: 4px solid var(--red);">
            <h3>Profils Flagués (Fraude)</h3>
            <div class="val" style="color: var(--red);">{{ $flaggedCount }}</div>
        </div>
        <div class="siaepi-card">
            <h3>Confiance Moyenne</h3>
            <div class="val">{{ number_format($avgConfidence, 1) }}%</div>
        </div>
    </div>

    {{-- Tableau Profils Suspects --}}
    <div class="siaepi-section">
        <h2>⚠️ Tableau des profils suspects</h2>
        @if($suspectProfiles->count() > 0)
        <table class="st">
            <thead>
                <tr>
                    <th>Étudiant</th>
                    <th>Date</th>
                    <th>Code RIASEC</th>
                    <th>Alertes</th>
                    <th>Statut Validation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suspectProfiles as $profile)
                @php 
                    $alerts = json_decode($profile->flag_reason, true) ?? [];
                    $alertCount = array_sum(array_column($alerts, 'count'));
                @endphp
                <tr>
                    <td><strong>{{ $profile->user->name ?? 'Anonyme' }}</strong><br><small>{{ $profile->user->email ?? '' }}</small></td>
                    <td>{{ $profile->created_at->format('d/m/Y H:i') }}</td>
                    <td><strong>{{ $profile->code_holland }}</strong> ({{ number_format($profile->confidence_score, 1) }}%)</td>
                    <td>
                        <span class="badge badge-red">{{ $alertCount }} alerte(s)</span>
                        <ul style="margin: 0; padding-left: 1rem; font-size: 0.75rem; color: rgba(11,12,16,0.6); margin-top: 0.25rem;">
                            @foreach($alerts as $type => $data)
                                <li>{{ $type }} : {{ $data['message'] ?? '' }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        @if($profile->validation_status === 'pending_manual_review')
                            <span class="badge badge-orange">En attente</span>
                        @else
                            <span class="badge badge-green">Approuvé</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn-sm btn-outline">Détail</button>
                        @if($profile->validation_status === 'pending_manual_review')
                        <form action="#" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-sm" style="background:var(--accent3);">Lever le flag</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color: rgba(11,12,16,0.5); font-style: italic;">Aucun profil suspect détecté pour le moment.</p>
        @endif
    </div>

    <div class="siaepi-grid" style="grid-template-columns: 1fr 1fr;">
        {{-- Graphique Distribution --}}
        <div class="siaepi-section" style="margin-bottom:0;">
            <h2>Distribution des Profils Dominants</h2>
            <canvas id="riasecChart" height="250"></canvas>
        </div>

        {{-- Graphique Certitude (Placeholder interactif) --}}
        <div class="siaepi-section" style="margin-bottom:0;">
            <h2>Courbes de Certitude (Dernier Profil)</h2>
            <canvas id="certaintyChart" height="250"></canvas>
            <p style="font-size:0.75rem; color: rgba(11,12,16,0.5); margin-top:1rem; text-align:center;">Évolution de l'information (SEM) au cours du test.</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart 1: Distribution
    const ctx1 = document.getElementById('riasecChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Nombre d\'étudiants',
                data: {!! json_encode($chartData) !!},
                backgroundColor: 'rgba(26, 79, 110, 0.8)',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Chart 2: Certitude (Dummy Data for Demo)
    const ctx2 = document.getElementById('certaintyChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['Q1','Q2','Q3','Q4','Q5','Q6','Q7','Q8','Q9','Q10','Q11','Q12'],
            datasets: [
                { label: 'Réaliste', data: [0, 5, 5, 5, 25, 25, 45, 60, 60, 60, 80, 85], borderColor: '#d4622a', tension: 0.2 },
                { label: 'Social', data: [0, 0, 15, 30, 30, 50, 50, 50, 75, 82, 82, 85], borderColor: '#1a4f6e', tension: 0.2 },
                { label: 'Investigateur', data: [0, 0, 0, 5, 5, 5, 5, 5, 5, 5, 5, 10], borderColor: '#c8973a', tension: 0.2, borderDash: [5, 5] }
            ]
        },
        options: { responsive: true, scales: { y: { max: 100 } } }
    });
});
</script>
@endsection