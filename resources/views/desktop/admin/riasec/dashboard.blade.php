@extends('layouts.admin')
@section('title', 'Dashboard RIASEC')

@section('content')
<style>
    /* ════════════════════════════════════════════
       CAPAVENIR RIASEC PSYCHOMETRIC ANALYTICS
    ════════════════════════════════════════════ */
    .riasec-wrapper {
        display: flex;
        flex-direction: column;
        gap: 2.5rem;
        font-family: var(--font-main);
        color: var(--ink);
    }

    /* Actions row */
    .action-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    /* KPI Grid */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.5rem;
    }
    .kpi-card {
        background: var(--ink06);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 1.5rem;
        transition: var(--transition);
        position: relative;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        border-color: var(--glass-border-vivid);
    }
    .kpi-val {
        font-family: var(--font-serif);
        font-size: 2.2rem;
        font-weight: 400;
        line-height: 1.1;
        color: var(--ink);
    }
    .kpi-lbl {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--ink30);
        margin-top: 0.5rem;
    }
    .kpi-sub {
        font-size: 0.75rem;
        color: var(--ink60);
        margin-top: 0.2rem;
    }

    /* Charts */
    .charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    @media (max-width: 900px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }
    .chart-card {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 2rem;
        box-shadow: var(--shadow-card);
    }
    .chart-title {
        font-family: var(--font-serif);
        font-size: 1.1rem;
        font-style: italic;
        color: var(--ink);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Dim bars styling */
    .dim-bar-row {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        margin-bottom: 0.85rem;
    }
    .dim-bar-label {
        font-family: var(--font-serif);
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--ink);
        width: 22px;
        flex-shrink: 0;
        text-align: center;
    }
    .dim-bar-track {
        flex: 1;
        height: 10px;
        background: var(--ink06);
        border-radius: 99px;
        overflow: hidden;
        border: 1px solid var(--glass-border);
    }
    .dim-bar-fill {
        height: 100%;
        border-radius: 99px;
        width: 0%; /* Animated via js */
        transition: width 1s ease-out;
    }
    .dim-bar-score {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--ink60);
        width: 38px;
        text-align: right;
        flex-shrink: 0;
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
        padding: 1.1rem 2rem;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: var(--ink30);
        border-bottom: 2px solid var(--glass-border);
    }
    .custom-table td {
        padding: 1.1rem 2rem;
        border-bottom: 1px solid var(--glass-border);
        font-size: 0.85rem;
        color: var(--ink60);
        vertical-align: middle;
    }
    .custom-table tr:hover td {
        background: var(--ink06);
        color: var(--ink);
    }

    .holland-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--accent) 0%, #ff8a43 100%);
        color: white;
        font-family: var(--font-serif);
        font-size: 0.85rem;
        font-weight: 600;
        font-style: italic;
        padding: 0.15rem 0.6rem;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(255, 94, 0, 0.2);
    }

    .btn-action-primary {
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
    .btn-action-primary:hover {
        background: color-mix(in srgb, var(--accent2) 90%, #000);
        border-color: color-mix(in srgb, var(--accent2) 90%, #000);
        transform: translateY(-1px);
    }
    
    .btn-action-glass {
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
    .btn-action-glass:hover {
        background: var(--ink10);
        color: var(--ink);
        border-color: var(--ink30);
    }
</style>

<div class="riasec-wrapper">
    {{-- Header --}}
    <div class="glass-card" style="background: var(--ink06); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 1.5rem;">
        <div>
            <h3 style="font-family: var(--font-serif); font-size: 1.4rem; font-weight: 400; font-style: italic; color: var(--ink);">Moteur Psychométrique RIASEC</h3>
            <p style="font-size: 0.82rem; color: var(--ink60); margin-top: 0.3rem;">Visualisez la distribution des types de Holland, configurez le questionnaire adaptatif de l'étudiant et observez les indicateurs d'orientation.</p>
        </div>
    </div>

    {{-- Onglets de Navigation IA --}}
    <div style="display: flex; gap: 1rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 0.5rem; margin-bottom: 2rem; flex-wrap: wrap;">
        <a href="{{ route('admin.riasec.dashboard') }}" style="text-decoration: none; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 700; color: var(--accent); border-bottom: 2px solid var(--accent); transition: var(--transition);">
            Analyses & Statistiques Globales
        </a>
        <a href="{{ route('admin.riasec.questions.index') }}" style="text-decoration: none; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 700; color: var(--ink60); border-bottom: 2px solid transparent; transition: var(--transition);" onmouseover="this.style.color='var(--ink)'" onmouseout="this.style.color='var(--ink60)'">
            Banque de Questions RIASEC
        </a>
    </div>

    {{-- Actions row --}}
    <div class="action-row">
        <a href="{{ route('admin.riasec.questions.index') }}" class="btn-action-primary">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Gérer les questions
        </a>
        <a href="{{ route('admin.riasec.questions.create') }}" class="btn-action-glass">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Nouvelle question
        </a>
        <a href="{{ route('admin.riasec.export') }}" class="btn-action-glass">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Exporter au format CSV
        </a>
    </div>

    {{-- KPIs --}}
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-val" style="color: var(--accent);">{{ $totalTests }}</div>
            <div class="kpi-lbl">Tests initiés</div>
            <div class="kpi-sub">{{ $inProgressTests }} en cours d'évaluation</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-val" style="color: var(--accent3);">{{ $completedTests }}</div>
            <div class="kpi-lbl">Tests finalisés</div>
            <div class="kpi-sub">{{ $totalTests > 0 ? round($completedTests / $totalTests * 100) : 0 }}% de taux de complétion</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-val" style="color: var(--accent2);">{{ $totalAnswers }}</div>
            <div class="kpi-lbl">Réponses enregistrées</div>
            <div class="kpi-sub">Moyenne de {{ $completedTests > 0 ? round($totalAnswers / $completedTests) : 0 }} par test</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-val" style="color: var(--gold);">{{ $activeQuestions }}/{{ $totalQuestions }}</div>
            <div class="kpi-lbl">Questions actives</div>
            <div class="kpi-sub">Couvrant les 6 dimensions</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-val" style="color: var(--accent2);">{{ round($avgCoherence ?? 0) }}%</div>
            <div class="kpi-lbl">Fiabilité CAT</div>
            <div class="kpi-sub">Cohérence moyenne</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-val" style="color: #7c3aed;">{{ $hollandDistrib->first()?->code_holland ?? 'N/A' }}</div>
            <div class="kpi-lbl">Trigramme dominant</div>
            <div class="kpi-sub">Type psychologique fréquent</div>
        </div>
    </div>

    {{-- Charts Grid --}}
    <div class="charts-grid">
        <div class="chart-card">
            <h4 class="chart-title">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--accent);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.003 9.003 0 1020.95 12H11V3.055z" />
                </svg>
                Typologie Globale (Radar RIASEC)
            </h4>
            <div style="position:relative; height:220px; width:100%;">
                <canvas id="radarAvg"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <h4 class="chart-title">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--accent3);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Diagnostics complétés (Derniers 30j)
            </h4>
            <div style="position:relative; height:220px; width:100%;">
                <canvas id="lineTests"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <h4 class="chart-title">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--accent2);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364.364l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                Top 10 Holland Dominants
            </h4>
            <div style="position:relative; height:220px; width:100%;">
                <canvas id="donutHolland"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <h4 class="chart-title">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--gold);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Répartition Détaillée par Dimension
            </h4>
            @php
                $dimColors = ['R'=>'#f97316','I'=>'#3b82f6','A'=>'#ec4899','S'=>'#10b981','E'=>'#8b5cf6','C'=>'#94a3b8'];
                $scoresArr = ['R'=>$avgScores->r??0,'I'=>$avgScores->i??0,'A'=>$avgScores->a??0,'S'=>$avgScores->s??0,'E'=>$avgScores->e??0,'C'=>$avgScores->c??0];
            @endphp
            <div style="display:flex; flex-direction:column; justify-content:center; height:100%;">
                @foreach($scoresArr as $d => $sc)
                    <div class="dim-bar-row">
                        <span class="dim-bar-label">{{ $d }}</span>
                        <div class="dim-bar-track">
                            <div class="dim-bar-fill" style="width:0%; background:{{ $dimColors[$d] }}" data-w="{{ $sc }}"></div>
                        </div>
                        <span class="dim-bar-score">{{ $sc }}%</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent tests table --}}
    <div class="custom-table-wrapper">
        <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--glass-border); display: flex; align-items: center; justify-content: space-between;">
            <h4 style="font-family: var(--font-serif); font-size: 1.15rem; font-style: italic; font-weight: 400; color: var(--ink); margin: 0;">
                Derniers Diagnostics Étudiants Complétés
            </h4>
        </div>
        <div style="overflow-x:auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Session ID</th>
                        <th>Type Holland</th>
                        <th>Réaliste (R)</th>
                        <th>Investigateur (I)</th>
                        <th>Artistique (A)</th>
                        <th>Social (S)</th>
                        <th>Entreprenant (E)</th>
                        <th>Conventionnel (C)</th>
                        <th>Cohérence</th>
                        <th>Date de fin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTests as $p)
                        <tr>
                            <td style="font-family:monospace; font-size:0.75rem; color:var(--ink30);">
                                {{ substr($p->test_session_id, 0, 8) }}…
                            </td>
                            <td>
                                <span class="holland-badge">{{ $p->code_holland }}</span>
                            </td>
                            <td>{{ $p->score_r }}%</td>
                            <td>{{ $p->score_i }}%</td>
                            <td>{{ $p->score_a }}%</td>
                            <td>{{ $p->score_s }}%</td>
                            <td>{{ $p->score_e }}%</td>
                            <td>{{ $p->score_c }}%</td>
                            <td>
                                @php $coh = $p->score_coherence ?? 0; @endphp
                                <span style="color:{{ $coh>=60 ? 'var(--accent3)' : 'var(--gold)' }}; font-weight:600;">
                                    {{ $coh }}%
                                </span>
                            </td>
                            <td>
                                <span style="font-size:0.8rem; color:var(--ink60);">
                                    {{ $p->complete_at?->timezone('Africa/Tunis')->format('d/m/Y H:i') ?? '—' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align:center; padding:3rem; color:var(--ink30);">
                                Aucun test complété pour l'instant.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor = isDark ? 'rgba(240,237,230,.5)' : 'rgba(11,12,16,.45)';
    const gridColor = isDark ? 'rgba(240,237,230,.07)' : 'rgba(11,12,16,.07)';

    // Radar Avg Scores
    new Chart(document.getElementById('radarAvg'), {
        type: 'radar',
        data: {
            labels: ['Réaliste', 'Investigateur', 'Artistique', 'Social', 'Entreprenant', 'Conventionnel'],
            datasets: [{
                label: 'Score moyen',
                data: [{{ $avgScores->r??0 }}, {{ $avgScores->i??0 }}, {{ $avgScores->a??0 }},
                       {{ $avgScores->s??0 }}, {{ $avgScores->e??0 }}, {{ $avgScores->c??0 }}],
                backgroundColor: 'rgba(234, 88, 12, 0.15)',
                borderColor: 'rgba(234, 88, 12, 0.85)',
                borderWidth: 2,
                pointBackgroundColor: ['#ea580c','#3b82f6','#ec4899','#10b981','#8b5cf6','#94a3b8'],
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                r: {
                    min: 0, max: 100,
                    ticks: { stepSize: 25, color: textColor, font: { size: 9 } },
                    grid: { color: gridColor },
                    pointLabels: { color: textColor, font: { size: 9, family: 'DM Sans', weight: 'bold' } },
                    angleLines: { color: gridColor },
                }
            }
        }
    });

    // Line tests/jour
    const daysData = @json($testsPerDay);
    const dayLabels = Object.keys(daysData);
    const dayValues = Object.values(daysData);
    new Chart(document.getElementById('lineTests'), {
        type: 'line',
        data: {
            labels: dayLabels,
            datasets: [{
                label: 'Tests complétés',
                data: dayValues,
                borderColor: '#0A2540',
                backgroundColor: 'rgba(10, 37, 64, 0.08)',
                borderWidth: 2,
                fill: true,
                tension: 0.35,
                pointRadius: 2.5,
                pointBackgroundColor: '#0A2540',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: textColor, font: { size: 9 } }, grid: { color: gridColor } },
                y: { ticks: { color: textColor, font: { size: 9 } }, grid: { color: gridColor }, beginAtZero: true },
            }
        }
    });

    // Donut Holland Distribution
    const hollandLabels = @json($hollandDistrib->pluck('code_holland'));
    const hollandData   = @json($hollandDistrib->pluck('total'));
    const palette = ['#f97316','#3b82f6','#ec4899','#10b981','#8b5cf6','#94a3b8','#eab308','#14b8a6','#f43f5e','#a855f7'];
    new Chart(document.getElementById('donutHolland'), {
        type: 'doughnut',
        data: {
            labels: hollandLabels,
            datasets: [{
                data: hollandData,
                backgroundColor: palette,
                borderColor: isDark ? '#1a1d24' : '#ffffff',
                borderWidth: 2,
                hoverOffset: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: { color: textColor, font: { size: 10, family: 'DM Sans' }, padding: 8, boxWidth: 10 }
                }
            }
        }
    });

    // Animate dimension bars
    setTimeout(() => {
        document.querySelectorAll('.dim-bar-fill').forEach(b => {
            b.style.width = b.dataset.w + '%';
        });
    }, 300);
});
</script>
@endsection
