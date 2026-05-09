<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats des recommandations — CapAvenir</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Fraunces:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --accent:  #d4622a;
            --accent2: #1a4f6e;
            --accent3: #4a7c59;
            --gold:    #c8973a;
            --ink:     #0b0c10;
            --paper:   #f7f5f0;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--paper);
            padding: 2rem 1rem;
        }
        .page-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(212,98,42,.10);
            color: var(--accent);
            border-radius: 50px;
            padding: .3rem .9rem;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .page-title {
            font-family: 'Fraunces', serif;
            font-size: 2.2rem;
            font-weight: 600;
            color: var(--ink);
        }
        .meta-pill {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: #fff;
            border: 1.5px solid rgba(11,12,16,.12);
            border-radius: 50px;
            padding: .35rem .9rem;
            font-size: .82rem;
            font-weight: 600;
            color: rgba(11,12,16,.7);
            margin: .25rem;
        }
        .total-badge {
            background: linear-gradient(135deg, var(--accent2), #0e3048);
            color: #fff;
            border-radius: 50px;
            padding: .5rem 1.4rem;
            font-size: .88rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
        }
        /* ── Cards ─────────────────────────────────── */
        .rec-card {
            background: #ffffff;
            border: none;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(11,12,16,.07);
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
            height: 100%;
        }
        .rec-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(11,12,16,.12);
        }
        .rec-card-header {
            background: linear-gradient(135deg, var(--accent2) 0%, #0e3048 100%);
            padding: 1.4rem 1.5rem 1rem;
            position: relative;
        }
        .rec-rank {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255,255,255,.18);
            color: #fff;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            font-size: .8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .rec-filiere-name {
            font-family: 'Fraunces', serif;
            font-size: 1.05rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: .3rem;
            line-height: 1.3;
        }
        .rec-universite {
            font-size: .78rem;
            color: rgba(255,255,255,.75);
            display: flex;
            align-items: center;
            gap: .3rem;
        }
        .rec-card-body {
            padding: 1.25rem 1.5rem;
        }
        /* Tags */
        .tag {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            border-radius: 50px;
            padding: .2rem .65rem;
            font-size: .73rem;
            font-weight: 600;
            margin: .15rem .1rem;
        }
        .tag-riasec   { background: rgba(26,79,110,.1);  color: var(--accent2); }
        .tag-emploi   { background: rgba(74,124,89,.1);  color: var(--accent3); }
        .tag-croiss   { background: rgba(200,151,58,.12); color: var(--gold); }
        .tag-etabl    { background: rgba(11,12,16,.06);  color: rgba(11,12,16,.6); }
        /* Scores */
        .score-label {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: rgba(11,12,16,.5);
            margin-bottom: .3rem;
        }
        .score-value {
            font-size: .85rem;
            font-weight: 700;
            color: var(--ink);
        }
        .progress {
            height: 6px;
            border-radius: 10px;
            background: rgba(11,12,16,.07);
        }
        .explanation-box {
            background: rgba(26,79,110,.05);
            border-left: 3px solid var(--accent2);
            border-radius: 0 8px 8px 0;
            padding: .7rem 1rem;
            font-size: .82rem;
            color: rgba(11,12,16,.65);
            margin-top: .75rem;
            line-height: 1.5;
        }
        /* Back button */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: #fff;
            border: 1.5px solid rgba(11,12,16,.15);
            border-radius: 10px;
            padding: .65rem 1.4rem;
            font-size: .9rem;
            font-weight: 600;
            color: var(--ink);
            text-decoration: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .btn-back:hover {
            border-color: var(--accent);
            color: var(--accent);
            box-shadow: 0 3px 12px rgba(212,98,42,.15);
        }
    </style>
</head>
<body>
<div class="container" style="max-width:1200px;">

    {{-- ── En-tête ────────────────────────────────────────────────── --}}
    <div class="page-header">
        <div class="brand-badge"><i class="bi bi-stars"></i> CapAvenir IA</div>
        <h1 class="page-title">Vos recommandations de filières</h1>
        <p class="text-muted" style="font-size:.95rem;">
            Résultats personnalisés pour votre profil
        </p>

        <div class="mt-3">
            <span class="meta-pill"><i class="bi bi-mortarboard-fill text-warning"></i> Score BG : <strong>{{ number_format($scoreFg, 2) }}</strong></span>
            <span class="meta-pill"><i class="bi bi-person-circle text-primary"></i> RIASEC : <strong>{{ strtoupper($riasec) }}</strong></span>
        </div>

        <div class="mt-3">
            <span class="total-badge">
                <i class="bi bi-collection-fill"></i>
                {{ $totalFilieresAccessibles }} filières accessibles analysées
            </span>
        </div>
    </div>

    {{-- ── Grille des recommandations ──────────────────────────────── --}}
    <div class="row g-4 mb-5">
        @forelse ($recommendations as $index => $rec)
        <div class="col-md-6 col-xl-4">
            <div class="rec-card">

                {{-- Header --}}
                <div class="rec-card-header">
                    <div class="rec-rank">#{{ $index + 1 }}</div>
                    <div class="rec-filiere-name">{{ $rec['Nom_Filiere'] ?? '—' }}</div>
                    <div class="rec-universite">
                        <i class="bi bi-building"></i>
                        {{ $rec['Universite'] ?? '' }}
                    </div>
                </div>

                {{-- Body --}}
                <div class="rec-card-body">

                    {{-- Tags --}}
                    <div class="mb-3">
                        @if (!empty($rec['Etablissement']))
                            <span class="tag tag-etabl"><i class="bi bi-geo-alt"></i> {{ $rec['Etablissement'] }}</span>
                        @endif
                        @if (!empty($rec['RIASEC']))
                            <span class="tag tag-riasec"><i class="bi bi-diagram-3"></i> {{ $rec['RIASEC'] }}</span>
                        @endif
                        @if (!empty($rec['Taux_Employabilite']))
                            <span class="tag tag-emploi"><i class="bi bi-briefcase"></i> {{ $rec['Taux_Employabilite'] }}</span>
                        @endif
                        @if (!empty($rec['Croissance_Domaine']))
                            <span class="tag tag-croiss"><i class="bi bi-graph-up-arrow"></i> {{ $rec['Croissance_Domaine'] }}</span>
                        @endif
                    </div>

                    <hr class="my-3" style="border-color:rgba(11,12,16,.08);">

                    {{-- Scores avec barres de progression --}}
                    <div class="row g-3">

                        @php
                            $scores = [
                                ['label' => 'Score Académique',     'key' => 'Score_Academique',    'color' => 'primary'],
                                ['label' => 'Score Psychologique',  'key' => 'Score_Psychologique', 'color' => 'success'],
                                ['label' => 'Score Marché',         'key' => 'Score_Marche',        'color' => 'warning'],
                                ['label' => 'SRF (Global)',         'key' => 'SRF',                 'color' => 'danger'],
                            ];
                        @endphp

                        @foreach ($scores as $score)
                            @php $val = $rec[$score['key']] ?? 0; @endphp
                            <div class="col-6">
                                <div class="score-label">{{ $score['label'] }}</div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="score-value">{{ number_format($val, 1) }}</span>
                                    <span style="font-size:.7rem;color:rgba(11,12,16,.4);">/ 100</span>
                                </div>
                                <div class="progress">
                                    <div
                                        class="progress-bar bg-{{ $score['color'] }}"
                                        role="progressbar"
                                        style="width: {{ min(100, max(0, $val)) }}%;"
                                        aria-valuenow="{{ $val }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                    ></div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    {{-- SDO 2025 --}}
                    @if (!empty($rec['SDO_2025']))
                    <div class="mt-3">
                        <div class="score-label">Score de désirabilité globale (SDO 2025)</div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="score-value" style="color:var(--accent2);">{{ number_format($rec['SDO_2025'], 2) }}</span>
                            <span style="font-size:.7rem;color:rgba(11,12,16,.4);">/ 100</span>
                        </div>
                        <div class="progress">
                            <div
                                class="progress-bar"
                                role="progressbar"
                                style="width: {{ min(100, $rec['SDO_2025']) }}%; background: linear-gradient(90deg, var(--accent2), #2a7bae);"
                                aria-valuenow="{{ $rec['SDO_2025'] }}"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            ></div>
                        </div>
                    </div>
                    @endif

                    {{-- Explication (si disponible) --}}
                    @if (!empty($rec['explanation']))
                        <div class="explanation-box">
                            <i class="bi bi-lightbulb-fill me-1" style="color:var(--gold);"></i>
                            {{ $rec['explanation'] }}
                        </div>
                    @endif

                    {{-- Code filière --}}
                    @if (!empty($rec['Code_Filiere']))
                        <div class="text-end mt-2">
                            <small class="text-muted" style="font-size:.7rem;">
                                Code : <span class="font-monospace">{{ $rec['Code_Filiere'] }}</span>
                            </small>
                        </div>
                    @endif

                </div>
            </div>
        </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Aucune recommandation trouvée pour ce profil.
                </div>
            </div>
        @endforelse
    </div>

    {{-- ── Bouton retour ─────────────────────────────────────────── --}}
    <div class="text-center pb-5">
        <a href="{{ route('recommendations.form') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Nouvelle recherche
        </a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
