<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CV — {{ $user->name }}</title>
    <style>
        @page { margin: 0; }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', 'Helvetica', sans-serif;
            font-size: 10pt;
            color: #2c3e50;
            line-height: 1.45;
        }

        .page { width: 100%; min-height: 100%; display: table; }
        .sidebar {
            display: table-cell;
            width: 32%;
            background: #1a2332;
            color: #e8e8e8;
            padding: 40px 22px;
            vertical-align: top;
        }
        .main {
            display: table-cell;
            width: 68%;
            padding: 40px 30px;
            vertical-align: top;
            background: #ffffff;
        }

        /* ── Sidebar ── */
        .sidebar-name {
            font-size: 16pt;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }
        .sidebar-job {
            font-size: 9pt;
            color: #d4622a;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        .sidebar-section-title {
            font-size: 8pt;
            font-weight: bold;
            color: #d4622a;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 8px;
            margin-top: 22px;
            padding-bottom: 5px;
            border-bottom: 1px solid rgba(255,255,255,0.12);
        }
        .sidebar-text {
            font-size: 9pt;
            color: #c8c8c8;
            margin-bottom: 4px;
        }
        .sidebar-text strong { color: #ffffff; }

        .skill-row {
            margin-bottom: 8px;
        }
        .skill-name {
            font-size: 8.5pt;
            color: #e0e0e0;
            margin-bottom: 3px;
        }
        .skill-bar {
            width: 100%;
            height: 4px;
            background: rgba(255,255,255,0.12);
            border-radius: 2px;
        }
        .skill-bar-fill {
            height: 4px;
            background: #d4622a;
            border-radius: 2px;
        }

        .lang-item {
            font-size: 9pt;
            color: #c8c8c8;
            margin-bottom: 5px;
        }
        .lang-item strong { color: #ffffff; }

        /* ── Main Content ── */
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            color: #d4622a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            margin-top: 25px;
            padding-bottom: 6px;
            border-bottom: 2px solid #d4622a;
        }
        .section-title:first-child { margin-top: 0; }

        .summary-text {
            font-size: 9.5pt;
            color: #555555;
            line-height: 1.55;
            margin-bottom: 5px;
        }

        .exp-item { margin-bottom: 16px; }
        .exp-position {
            font-size: 10.5pt;
            font-weight: bold;
            color: #1a2332;
        }
        .exp-company {
            font-size: 9.5pt;
            color: #d4622a;
            font-weight: bold;
        }
        .exp-date {
            font-size: 8pt;
            color: #95a5a6;
            font-style: italic;
            margin-bottom: 4px;
        }
        .exp-desc {
            font-size: 9pt;
            color: #555555;
            line-height: 1.5;
        }

        .edu-item { margin-bottom: 12px; }
        .edu-degree {
            font-size: 10pt;
            font-weight: bold;
            color: #1a2332;
        }
        .edu-institution {
            font-size: 9pt;
            color: #777777;
        }
        .edu-date {
            font-size: 8pt;
            color: #95a5a6;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="page">
        {{-- ═══ SIDEBAR ═══ --}}
        <div class="sidebar">
            <div class="sidebar-name">{{ $user->name }}</div>
            @if($cv->target_job)
                <div class="sidebar-job">{{ $cv->target_job }}</div>
            @endif

            {{-- Contact --}}
            <div class="sidebar-section-title">Contact</div>
            <div class="sidebar-text">✉ {{ $user->email }}</div>

            {{-- Compétences --}}
            @if($cv->skills->isNotEmpty())
                <div class="sidebar-section-title">Compétences</div>
                @foreach($cv->skills as $skill)
                    <div class="skill-row">
                        <div class="skill-name">{{ $skill->name }}</div>
                        <div class="skill-bar">
                            @php
                                $pct = match($skill->level) {
                                    'Débutant' => 25,
                                    'Intermédiaire' => 50,
                                    'Avancé' => 75,
                                    'Expert' => 95,
                                    default => 60,
                                };
                            @endphp
                            <div class="skill-bar-fill" style="width: {{ $pct }}%;"></div>
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- Langues --}}
            @if($cv->languages->isNotEmpty())
                <div class="sidebar-section-title">Langues</div>
                @foreach($cv->languages as $lang)
                    <div class="lang-item">
                        <strong>{{ $lang->name }}</strong>
                        @if($lang->level) — {{ $lang->level }} @endif
                    </div>
                @endforeach
            @endif
        </div>

        {{-- ═══ MAIN ═══ --}}
        <div class="main">
            {{-- Résumé --}}
            @if($cv->summary)
                <div class="section-title">Profil</div>
                <div class="summary-text">{{ $cv->summary }}</div>
            @endif

            {{-- Expériences --}}
            @if($cv->experiences->isNotEmpty())
                <div class="section-title">Expériences Professionnelles</div>
                @foreach($cv->experiences as $exp)
                    <div class="exp-item">
                        <div class="exp-position">{{ $exp->position }}</div>
                        <div class="exp-company">{{ $exp->company }}</div>
                        <div class="exp-date">
                            {{ $exp->start_date->format('m/Y') }} —
                            {{ $exp->is_current ? 'Présent' : $exp->end_date?->format('m/Y') }}
                        </div>
                        <div class="exp-desc">{!! nl2br(e($exp->description)) !!}</div>
                    </div>
                @endforeach
            @endif

            {{-- Formations --}}
            @if($cv->educations->isNotEmpty())
                <div class="section-title">Formation</div>
                @foreach($cv->educations as $edu)
                    <div class="edu-item">
                        <div class="edu-degree">
                            {{ $edu->degree }}
                            @if($edu->field_of_study) — {{ $edu->field_of_study }} @endif
                        </div>
                        <div class="edu-institution">{{ $edu->institution }}</div>
                        <div class="edu-date">
                            {{ $edu->start_date->format('Y') }} —
                            {{ $edu->is_current ? 'Présent' : $edu->end_date?->format('Y') }}
                        </div>
                        @if($edu->description)
                            <div class="exp-desc" style="margin-top:3px;">{{ $edu->description }}</div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</body>
</html>
