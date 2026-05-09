@extends('layouts.student')
@section('title', 'Comparateur de Filières')

@section('content')
<style>
:root{--ink:#0b0c10;--paper:#f7f5f0;--cream:#ede9e1;--warm:#e8e1d4;--accent:#d4622a;--accent2:#1a4f6e;--accent3:#4a7c59;--gold:#c8973a;--ink60:rgba(11,12,16,.6);--ink30:rgba(11,12,16,.3);--ink15:rgba(11,12,16,.15);--ink10:rgba(11,12,16,.1);--ink06:rgba(11,12,16,.06);--r:8px;--rl:16px;--rx:999px;--ease:cubic-bezier(.16,1,.3,1)}
.cp{font-family:'DM Sans',sans-serif;color:var(--ink);background:var(--paper);padding:2rem 2.5rem 5rem;max-width:1300px;margin:0 auto}
.cp *,.cp *::before,.cp *::after{box-sizing:border-box;margin:0;padding:0}
/* Header */
.cp-header{margin-bottom:2rem}
.cp-eyebrow{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--accent);display:flex;align-items:center;gap:.5rem;margin-bottom:.75rem}
.cp-eyebrow::before{content:'';width:18px;height:1px;background:var(--accent)}
.cp-title{font-family:'Fraunces',serif;font-size:2.8rem;font-weight:300;letter-spacing:-.04em;line-height:1.1;margin-bottom:.625rem}
.cp-title em{font-style:italic;color:var(--accent)}
.cp-sub{font-size:.9rem;color:var(--ink60);line-height:1.7}
/* Selector panel */
.cp-select-panel{background:var(--cream);border:1px solid var(--ink10);border-radius:var(--rl);padding:1.75rem;margin-bottom:2rem}
.cp-select-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.25rem}
@media(max-width:1000px){.cp-select-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.cp-select-grid{grid-template-columns:1fr}}
.cp-select-input{width:100%;padding:.75rem 1rem;background:var(--paper);border:1px solid var(--ink15);border-radius:var(--r);color:var(--ink);font-family:'DM Sans',sans-serif;font-size:.87rem;transition:border-color .2s}
.cp-select-input:focus{outline:none;border-color:var(--accent)}
.cp-select-label{display:block;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink30);margin-bottom:.4rem}
.cp-btn{padding:.875rem 2rem;background:var(--accent);color:#fff;border:none;border-radius:var(--r);font-family:'DM Sans',sans-serif;font-size:.9rem;font-weight:600;cursor:pointer;box-shadow:0 4px 16px color-mix(in srgb,var(--accent) 28%,transparent);transition:all .3s var(--ease);display:inline-flex;align-items:center;gap:.5rem}
.cp-btn:hover{transform:translateY(-2px);box-shadow:0 8px 24px color-mix(in srgb,var(--accent) 38%,transparent)}
.cp-btn:disabled{opacity:.4;cursor:not-allowed;transform:none}
/* Results */
.cp-results{display:none}
.cp-layout{display:grid;grid-template-columns:1fr 1fr;gap:1.75rem;align-items:start;margin-bottom:2rem}
@media(max-width:900px){.cp-layout{grid-template-columns:1fr}}
/* Chart panel */
.cp-panel{background:var(--paper);border:1px solid var(--ink10);border-radius:var(--rl);overflow:hidden}
.cp-panel-head{padding:1.25rem 1.5rem;border-bottom:1px solid var(--ink10);background:var(--cream);display:flex;align-items:center;gap:.75rem}
.cp-panel-head h2{font-family:'Fraunces',serif;font-size:1.1rem;font-weight:600;letter-spacing:-.02em}
.cp-panel-body{padding:1.5rem}
.cp-chart-wrap{position:relative;height:350px;display:flex;align-items:center;justify-content:center}
/* Table comparatif */
.cp-table{width:100%;border-collapse:collapse;font-size:.85rem}
.cp-table th{text-align:left;padding:.625rem 1rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink30);border-bottom:2px solid var(--ink10);background:var(--cream)}
.cp-table td{padding:.875rem 1rem;border-bottom:1px solid var(--ink10);vertical-align:middle}
.cp-table tr:last-child td{border-bottom:none}
.cp-table tr:hover td{background:var(--cream)}
.cp-table .cp-th-name{font-weight:700;color:var(--ink)}
/* Formation header cells */
.cp-formation-header{display:flex;align-items:center;gap:.5rem;padding:.75rem 1rem;min-width:160px}
.cp-f-icon{width:32px;height:32px;border-radius:var(--r);display:flex;align-items:center;justify-content:center;font-size:.95rem;flex-shrink:0}
.cp-f-label{font-size:.78rem;font-weight:600;line-height:1.3;color:var(--ink)}
/* Score bar in table */
.cp-score-bar{display:flex;align-items:center;gap:.625rem}
.cp-score-track{flex:1;height:6px;background:var(--ink10);border-radius:var(--rx);overflow:hidden}
.cp-score-fill{height:100%;border-radius:var(--rx)}
.cp-score-val{font-family:'Fraunces',serif;font-size:.9rem;font-weight:600;width:42px;text-align:right;flex-shrink:0}
/* Metric labels */
.cp-metric-row td:first-child{font-weight:600;color:var(--ink60);font-size:.82rem}
/* Placeholder */
.cp-placeholder{text-align:center;padding:4rem 2rem;background:var(--cream);border:1px solid var(--ink10);border-radius:20px}
.cp-placeholder-icon{font-size:4rem;margin-bottom:1.25rem}
.cp-placeholder-title{font-family:'Fraunces',serif;font-size:1.8rem;font-weight:300;letter-spacing:-.03em;margin-bottom:.625rem}
.cp-placeholder-sub{font-size:.87rem;color:var(--ink60);line-height:1.7}
</style>

<div class="cp">

    <div class="cp-header">
        <div class="cp-eyebrow">📊 Outil de comparaison</div>
        <h1 class="cp-title">Comparer des <em>filières</em></h1>
        <p class="cp-sub">Sélectionnez 2 à 4 formations pour les comparer côte-à-côte (radar, salaire, durée…)</p>
    </div>

    {{-- Sélection --}}
    <div class="cp-select-panel">
        <div class="cp-select-grid" id="selectGrid">
            @for($i = 1; $i <= 4; $i++)
            <div>
                <label class="cp-select-label">Filière {{ $i }}{{ $i <= 2 ? ' *' : '' }}</label>
                <select class="cp-select-input cp-f-select" id="f{{ $i }}">
                    <option value="">— {{ $i <= 2 ? 'Obligatoire' : 'Optionnel' }} —</option>
                    @foreach($formations as $f)
                        <option value="{{ $f->id }}">{{ $f->nom }} ({{ $f->etablissement }})</option>
                    @endforeach
                </select>
            </div>
            @endfor
        </div>
        <button class="cp-btn" id="comparerBtn" onclick="lancer()">
            📊 Comparer
        </button>
    </div>

    {{-- Résultats --}}
    <div class="cp-results" id="cpResults">
        <div class="cp-layout">
            {{-- Radar --}}
            <div class="cp-panel">
                <div class="cp-panel-head">
                    <span style="font-size:1.2rem">🕸️</span>
                    <h2>Graphique radar</h2>
                </div>
                <div class="cp-panel-body">
                    <div class="cp-chart-wrap">
                        <canvas id="radarChart"></canvas>
                    </div>
                    <div id="radarLegend" style="display:flex;flex-wrap:wrap;gap:.5rem;justify-content:center;margin-top:1rem"></div>
                </div>
            </div>

            {{-- Info cards --}}
            <div>
                <div class="cp-panel">
                    <div class="cp-panel-head">
                        <span style="font-size:1.2rem">📋</span>
                        <h2>Résumé comparatif</h2>
                    </div>
                    <div class="cp-panel-body">
                        <div id="summaryCards" style="display:flex;flex-direction:column;gap:.75rem"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tableau détaillé --}}
        <div class="cp-panel">
            <div class="cp-panel-head">
                <span style="font-size:1.2rem">📊</span>
                <h2>Tableau comparatif détaillé</h2>
            </div>
            <div class="cp-panel-body" style="overflow-x:auto">
                <table class="cp-table" id="cpTable">
                    <thead id="cpTableHead"></thead>
                    <tbody id="cpTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Placeholder initial --}}
    <div class="cp-placeholder" id="cpPlaceholder">
        <div class="cp-placeholder-icon">⚖️</div>
        <h2 class="cp-placeholder-title">Prêt à comparer</h2>
        <p class="cp-placeholder-sub">Sélectionnez au minimum 2 formations ci-dessus<br>et cliquez sur « Comparer »</p>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(function() {
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const COLORS = ['#d4622a','#1a4f6e','#4a7c59','#c8973a'];
    let radarInstance = null;

    window.lancer = async function() {
        const ids = [...document.querySelectorAll('.cp-f-select')]
            .map(s => s.value)
            .filter(v => v !== '');

        if (ids.length < 2) {
            alert('Veuillez sélectionner au moins 2 formations.');
            return;
        }

        const btn = document.getElementById('comparerBtn');
        btn.disabled = true;
        btn.innerHTML = '⏳ Chargement…';

        try {
            const res = await fetch('{{ route('student.comparateur.data') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ ids }),
            });
            const data = await res.json();

            if (!data.success) throw new Error(data.message);

            renderRadar(data.formations);
            renderSummary(data.formations);
            renderTable(data.formations);

            document.getElementById('cpPlaceholder').style.display = 'none';
            document.getElementById('cpResults').style.display = 'block';
        } catch(e) {
            alert('Erreur: ' + e.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '📊 Comparer';
        }
    };

    function renderRadar(formations) {
        const labels = ['Compatibilité', 'Salaire', 'Rapidité', 'Insertion', 'Accessibilité'];
        const datasets = formations.map((f, i) => ({
            label: f.nom.substring(0, 25) + '…',
            data: [f.radar.matching, f.radar.salaire, f.radar.rapidite, f.radar.insertion, f.radar.accessibilite],
            backgroundColor: COLORS[i] + '22',
            borderColor: COLORS[i],
            pointBackgroundColor: COLORS[i],
            borderWidth: 2,
            pointRadius: 4,
        }));

        if (radarInstance) radarInstance.destroy();

        radarInstance = new Chart(document.getElementById('radarChart'), {
            type: 'radar',
            data: { labels, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: 'rgba(11,12,16,.08)' },
                        pointLabels: { font: { size: 11, family: "'DM Sans', sans-serif" }, color: 'rgba(11,12,16,.6)' },
                        ticks: { display: false },
                    }
                },
                plugins: { legend: { display: false } },
            },
        });

        // Custom legend
        const legend = document.getElementById('radarLegend');
        legend.innerHTML = formations.map((f, i) => `
            <div style="display:flex;align-items:center;gap:.35rem;font-size:.75rem;font-weight:600;color:${COLORS[i]}">
                <div style="width:12px;height:12px;border-radius:3px;background:${COLORS[i]}"></div>
                ${f.icon} ${f.nom.substring(0, 20)}
            </div>
        `).join('');
    }

    function renderSummary(formations) {
        const container = document.getElementById('summaryCards');
        container.innerHTML = formations.map((f, i) => `
            <div style="display:flex;align-items:center;gap:.875rem;padding:1rem 1.125rem;background:var(--cream);border-radius:8px;border-left:3px solid ${COLORS[i]}">
                <div style="font-size:1.4rem;flex-shrink:0">${f.icon}</div>
                <div style="flex:1;min-width:0">
                    <div style="font-weight:600;font-size:.85rem;color:var(--ink);line-height:1.3;margin-bottom:.2rem">${f.nom}</div>
                    <div style="font-size:.73rem;color:var(--ink60)">${f.etablissement} · ${f.niveau} · ${f.duree}</div>
                    <div style="font-size:.73rem;color:var(--ink60);margin-top:.15rem">💰 ${f.salaire_min} – ${f.salaire_max} / mois</div>
                </div>
                <div style="text-align:right;flex-shrink:0">
                    <div style="font-family:'Fraunces',serif;font-size:1.4rem;font-weight:600;color:${COLORS[i]}">${f.radar.matching}%</div>
                    <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--ink30)">match</div>
                </div>
            </div>
        `).join('');
    }

    function renderTable(formations) {
        const metrics = [
            { key: 'matching',     label: '🎯 Compatibilité' },
            { key: 'salaire',      label: '💰 Salaire' },
            { key: 'rapidite',     label: '⚡ Rapidité' },
            { key: 'insertion',    label: '🚀 Insertion pro' },
            { key: 'accessibilite',label: '🔓 Accessibilité' },
        ];

        // Header
        document.getElementById('cpTableHead').innerHTML = `<tr>
            <th style="width:160px">Critère</th>
            ${formations.map((f, i) => `
                <th>
                    <div class="cp-formation-header">
                        <div class="cp-f-icon" style="background:${COLORS[i]}22;border:1px solid ${COLORS[i]}44">${f.icon}</div>
                        <div class="cp-f-label">${f.nom.substring(0,30)}</div>
                    </div>
                </th>
            `).join('')}
        </tr>`;

        // Info rows
        const infoRows = [
            { label: '🏛️ Établissement', fn: f => f.etablissement },
            { label: '📍 Ville',          fn: f => f.ville },
            { label: '📐 Niveau',         fn: f => f.niveau },
            { label: '⏱ Durée',          fn: f => f.duree },
            { label: '🏷️ Domaine',       fn: f => f.domaine },
            { label: '💰 Salaire min',    fn: f => f.salaire_min },
            { label: '💰 Salaire max',    fn: f => f.salaire_max },
        ];

        let tbody = infoRows.map(row => `
            <tr class="cp-metric-row">
                <td>${row.label}</td>
                ${formations.map(f => `<td>${row.fn(f)}</td>`).join('')}
            </tr>
        `).join('');

        // Score rows with bars
        tbody += '<tr><td colspan="${formations.length+1}" style="height:8px;background:var(--cream)"></td></tr>';
        tbody += metrics.map(m => `
            <tr class="cp-metric-row">
                <td>${m.label}</td>
                ${formations.map((f, i) => `
                    <td>
                        <div class="cp-score-bar">
                            <div class="cp-score-track">
                                <div class="cp-score-fill" style="width:${f.radar[m.key]}%;background:${COLORS[i]}"></div>
                            </div>
                            <div class="cp-score-val" style="color:${COLORS[i]}">${f.radar[m.key]}%</div>
                        </div>
                    </td>
                `).join('')}
            </tr>
        `).join('');

        document.getElementById('cpTableBody').innerHTML = tbody;
    }
})();
</script>
@endsection
