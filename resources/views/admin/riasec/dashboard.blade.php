@extends('layouts.admin')
@section('title', 'Dashboard RIASEC')

@section('content')
<style>
.kpi-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:1.2rem; margin-bottom:2rem; }
.kpi-card {
    background:var(--ink06); border:1px solid var(--glass-border);
    border-radius:var(--rl); padding:1.4rem 1.6rem;
    transition:border-color .3s var(--ease);
}
.kpi-card:hover { border-color:var(--ink30); }
.kpi-val  { font-family:var(--font-serif); font-size:2.2rem; font-weight:600; line-height:1; color:var(--ink); letter-spacing:-.04em; }
.kpi-lbl  { font-size:.72rem; font-weight:700; color:var(--ink30); text-transform:uppercase; letter-spacing:.07em; margin-top:.4rem; }
.kpi-sub  { font-size:.75rem; color:var(--ink60); margin-top:.2rem; }
.kpi-accent { color:var(--accent); }
.kpi-green  { color:var(--accent3); }
.kpi-gold   { color:var(--gold); }
.kpi-blue   { color:var(--accent2); }

.charts-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:2rem; }
@media(max-width:900px){ .charts-grid{ grid-template-columns:1fr; } }

.chart-card {
    background:var(--ink06); border:1px solid var(--glass-border);
    border-radius:var(--rl); padding:1.5rem;
}
.chart-title {
    font-size:.78rem; font-weight:700; letter-spacing:.07em;
    text-transform:uppercase; color:var(--ink30); margin-bottom:1.2rem;
}

.recent-table { width:100%; border-collapse:collapse; }
.recent-table th {
    font-size:.68rem; font-weight:700; letter-spacing:.08em;
    text-transform:uppercase; color:var(--ink30);
    padding:.6rem 1rem; text-align:left;
    border-bottom:1px solid var(--glass-border);
}
.recent-table td {
    padding:.75rem 1rem; font-size:.82rem; color:var(--ink60);
    border-bottom:1px solid var(--ink06);
    transition:color .2s;
}
.recent-table tr:hover td { color:var(--ink); }
.recent-table tr:last-child td { border-bottom:none; }

.holland-pill {
    display:inline-flex; align-items:center; justify-content:center;
    width:44px; height:28px; border-radius:var(--r);
    font-family:var(--font-serif); font-size:.9rem; font-weight:600;
    font-style:italic; background:var(--accent);
    color:#fff; letter-spacing:-.02em;
}

.dim-bar-row { display:flex; align-items:center; gap:.8rem; margin-bottom:.75rem; }
.dim-bar-label { font-size:.78rem; font-weight:700; color:var(--ink60); width:22px; flex-shrink:0; }
.dim-bar-track { flex:1; height:8px; background:var(--ink10); border-radius:99px; overflow:hidden; }
.dim-bar-fill  { height:100%; border-radius:99px; transition:width 1.2s var(--ease); }
.dim-bar-score { font-size:.72rem; color:var(--ink30); width:36px; text-align:right; flex-shrink:0; }

.action-row {
    display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;
    margin-bottom:2.5rem;
}
</style>

{{-- ── Actions ──────────────────────────────────────────────────────── --}}
<div class="action-row">
    <a href="{{ route('admin.riasec.questions.index') }}" class="btn-primary">
        📋 Gérer les questions
    </a>
    <a href="{{ route('admin.riasec.questions.create') }}" class="btn-glass">
        ＋ Nouvelle question
    </a>
    <a href="{{ route('admin.riasec.export') }}" class="btn-glass">
        📥 Exporter CSV
    </a>
</div>

{{-- ── KPIs ─────────────────────────────────────────────────────────── --}}
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-val kpi-accent">{{ $totalTests }}</div>
        <div class="kpi-lbl">Tests lancés</div>
        <div class="kpi-sub">{{ $inProgressTests }} en cours</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-val kpi-green">{{ $completedTests }}</div>
        <div class="kpi-lbl">Tests complétés</div>
        <div class="kpi-sub">{{ $totalTests > 0 ? round($completedTests/$totalTests*100) : 0 }}% de complétion</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-val kpi-blue">{{ $totalAnswers }}</div>
        <div class="kpi-lbl">Réponses totales</div>
        <div class="kpi-sub">{{ $completedTests > 0 ? round($totalAnswers/$completedTests) : 0 }} / test</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-val kpi-gold">{{ $activeQuestions }}/{{ $totalQuestions }}</div>
        <div class="kpi-lbl">Questions actives</div>
        <div class="kpi-sub">6 dimensions RIASEC</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-val" style="color:var(--accent2)">{{ round($avgCoherence ?? 0) }}%</div>
        <div class="kpi-lbl">Cohérence moy.</div>
        <div class="kpi-sub">Score de fiabilité</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-val" style="color:#a855f7">{{ $hollandDistrib->first()?->code_holland ?? 'N/A' }}</div>
        <div class="kpi-lbl">Profil dominant</div>
        <div class="kpi-sub">Code Holland le plus fréquent</div>
    </div>
</div>

{{-- ── Graphiques ───────────────────────────────────────────────────── --}}
<div class="charts-grid">

    {{-- Radar des scores moyens --}}
    <div class="chart-card">
        <p class="chart-title">Scores moyens par dimension</p>
        <canvas id="radarAvg" height="220"></canvas>
    </div>

    {{-- Tests par jour --}}
    <div class="chart-card">
        <p class="chart-title">Tests complétés (30 derniers jours)</p>
        <canvas id="lineTests" height="220"></canvas>
    </div>

    {{-- Donut codes Holland --}}
    <div class="chart-card">
        <p class="chart-title">Répartition codes Holland (Top 10)</p>
        <canvas id="donutHolland" height="220"></canvas>
    </div>

    {{-- Barres moyens par dimension + tableau --}}
    <div class="chart-card">
        <p class="chart-title">Scores moyens détaillés</p>
        @php
        $dimColors = ['R'=>'#f97316','I'=>'#3b82f6','A'=>'#ec4899','S'=>'#10b981','E'=>'#8b5cf6','C'=>'#94a3b8'];
        $dimLabels = ['R'=>'Réaliste','I'=>'Investigateur','A'=>'Artistique','S'=>'Social','E'=>'Entreprenant','C'=>'Conventionnel'];
        $scoresArr = ['R'=>$avgScores->r??0,'I'=>$avgScores->i??0,'A'=>$avgScores->a??0,'S'=>$avgScores->s??0,'E'=>$avgScores->e??0,'C'=>$avgScores->c??0];
        @endphp
        <div>
            @foreach($scoresArr as $d => $sc)
            <div class="dim-bar-row">
                <span class="dim-bar-label">{{ $d }}</span>
                <div class="dim-bar-track">
                    <div class="dim-bar-fill" style="width:0%;background:{{ $dimColors[$d] }}" data-w="{{ $sc }}"></div>
                </div>
                <span class="dim-bar-score">{{ $sc }}%</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Derniers tests ───────────────────────────────────────────────── --}}
<div class="glass-card" style="padding:0;overflow:hidden;">
    <div style="padding:1.2rem 1.5rem;border-bottom:1px solid var(--glass-border);display:flex;align-items:center;justify-content:space-between;">
        <p class="chart-title" style="margin:0">Derniers tests complétés</p>
        <a href="{{ route('admin.riasec.export') }}" class="btn-glass" style="padding:.38rem .8rem;font-size:.75rem;">
            📥 CSV
        </a>
    </div>
    <table class="recent-table">
        <thead>
            <tr>
                <th>Session</th>
                <th>Code Holland</th>
                <th>Score R</th><th>Score I</th><th>Score A</th>
                <th>Score S</th><th>Score E</th><th>Score C</th>
                <th>Cohérence</th>
                <th>Complété le</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentTests as $p)
            <tr>
                <td style="font-family:monospace;font-size:.75rem;color:var(--ink30);">
                    {{ substr($p->test_session_id, 0, 8) }}…
                </td>
                <td><span class="holland-pill">{{ $p->code_holland }}</span></td>
                <td>{{ $p->score_r }}%</td>
                <td>{{ $p->score_i }}%</td>
                <td>{{ $p->score_a }}%</td>
                <td>{{ $p->score_s }}%</td>
                <td>{{ $p->score_e }}%</td>
                <td>{{ $p->score_c }}%</td>
                <td>
                    @php $coh = $p->score_coherence ?? 0; @endphp
                    <span style="color:{{ $coh>=60 ? 'var(--accent3)' : 'var(--gold)' }};font-weight:600;">
                        {{ $coh }}%
                    </span>
                </td>
                <td>{{ $p->complete_at?->diffForHumans() ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align:center;padding:2rem;color:var(--ink30);">
                    Aucun test complété pour l'instant.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
const textColor = isDark ? 'rgba(240,237,230,.5)' : 'rgba(11,12,16,.45)';
const gridColor = isDark ? 'rgba(240,237,230,.07)' : 'rgba(11,12,16,.07)';

// ── Radar ------------------------------------------------------------------
new Chart(document.getElementById('radarAvg'), {
    type: 'radar',
    data: {
        labels: ['Réaliste', 'Investigateur', 'Artistique', 'Social', 'Entreprenant', 'Conventionnel'],
        datasets: [{
            label: 'Score moyen',
            data: [{{ $avgScores->r??0 }}, {{ $avgScores->i??0 }}, {{ $avgScores->a??0 }},
                   {{ $avgScores->s??0 }}, {{ $avgScores->e??0 }}, {{ $avgScores->c??0 }}],
            backgroundColor: 'rgba(212,98,42,0.15)',
            borderColor: 'rgba(212,98,42,0.8)',
            borderWidth:2,
            pointBackgroundColor:['#f97316','#3b82f6','#ec4899','#10b981','#8b5cf6','#94a3b8'],
            pointRadius:5,
        }]
    },
    options: {
        responsive:true,
        plugins:{ legend:{display:false} },
        scales:{ r:{
            min:0, max:100,
            ticks:{ stepSize:25, color:textColor, font:{size:10} },
            grid:{ color:gridColor },
            pointLabels:{ color:textColor, font:{size:10} },
            angleLines:{ color:gridColor },
        }}
    }
});

// ── Line tests/jour -------------------------------------------------------
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
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99,102,241,0.12)',
            borderWidth:2, fill:true, tension:0.4,
            pointRadius:3, pointBackgroundColor:'#6366f1',
        }]
    },
    options: {
        responsive:true,
        plugins:{ legend:{display:false} },
        scales:{
            x:{ ticks:{color:textColor,font:{size:10}}, grid:{color:gridColor} },
            y:{ ticks:{color:textColor,font:{size:10}}, grid:{color:gridColor}, beginAtZero:true },
        }
    }
});

// ── Donut Holland -------------------------------------------------------
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
            borderColor: isDark ? '#10100d' : '#f7f5f0',
            borderWidth:3, hoverOffset:6,
        }]
    },
    options: {
        responsive:true, cutout:'68%',
        plugins:{
            legend:{ position:'right', labels:{ color:textColor, font:{size:11}, padding:12, boxWidth:14 } }
        }
    }
});

// ── Animate dim bars -----------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('.dim-bar-fill').forEach(b => {
            b.style.width = b.dataset.w + '%';
        });
    }, 400);
});
</script>
@endsection
