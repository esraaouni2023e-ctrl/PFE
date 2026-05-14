<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CV — {{ $user->name }}</title>
    <style>
        @page { margin: 50px 45px; }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', 'Helvetica', sans-serif;
            font-size: 10pt;
            color: #333333;
            line-height: 1.5;
            background: #ffffff;
        }

        /* ── Header ── */
        .header {
            text-align: center;
            padding-bottom: 18px;
            border-bottom: 2px solid #2c3e50;
            margin-bottom: 20px;
        }
        .header-name {
            font-size: 20pt;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 1px;
        }
        .header-job {
            font-size: 10pt;
            color: #7f8c8d;
            margin-top: 2px;
        }
        .header-contact {
            font-size: 8.5pt;
            color: #95a5a6;
            margin-top: 5px;
        }

        /* ── Sections ── */
        .section { margin-bottom: 18px; }
        .section-title {
            font-size: 10.5pt;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding-bottom: 5px;
            border-bottom: 1px solid #bdc3c7;
            margin-bottom: 10px;
        }

        .summary-text {
            font-size: 9.5pt;
            color: #555555;
            line-height: 1.6;
        }

        .item { margin-bottom: 14px; }
        .item-title {
            font-size: 10pt;
            font-weight: bold;
            color: #2c3e50;
        }
        .item-subtitle {
            font-size: 9pt;
            color: #7f8c8d;
        }
        .item-date {
            font-size: 8pt;
            color: #95a5a6;
            font-style: italic;
        }
        .item-desc {
            font-size: 9pt;
            color: #555555;
            line-height: 1.5;
            margin-top: 3px;
        }

        .skills-list {
            font-size: 9pt;
            color: #555555;
        }

        .lang-item {
            font-size: 9pt;
            color: #555555;
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    {{-- ═══ HEADER ═══ --}}
    <div class="header">
        <div class="header-name">{{ $user->name }}</div>
        @if($cv->target_job)
            <div class="header-job">{{ $cv->target_job }}</div>
        @endif
        <div class="header-contact">{{ $user->email }}</div>
    </div>

    {{-- Résumé --}}
    @if($cv->summary)
        <div class="section">
            <div class="section-title">Profil</div>
            <div class="summary-text">{{ $cv->summary }}</div>
        </div>
    @endif

    {{-- Expériences --}}
    @if($cv->experiences->isNotEmpty())
        <div class="section">
            <div class="section-title">Expériences Professionnelles</div>
            @foreach($cv->experiences as $exp)
                <div class="item">
                    <div class="item-title">{{ $exp->position }}</div>
                    <div class="item-subtitle">{{ $exp->company }}</div>
                    <div class="item-date">
                        {{ $exp->start_date->format('m/Y') }} —
                        {{ $exp->is_current ? 'Présent' : $exp->end_date?->format('m/Y') }}
                    </div>
                    <div class="item-desc">{!! nl2br(e($exp->description)) !!}</div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Formations --}}
    @if($cv->educations->isNotEmpty())
        <div class="section">
            <div class="section-title">Formation</div>
            @foreach($cv->educations as $edu)
                <div class="item">
                    <div class="item-title">
                        {{ $edu->degree }}
                        @if($edu->field_of_study) — {{ $edu->field_of_study }} @endif
                    </div>
                    <div class="item-subtitle">{{ $edu->institution }}</div>
                    <div class="item-date">
                        {{ $edu->start_date->format('Y') }} —
                        {{ $edu->is_current ? 'Présent' : $edu->end_date?->format('Y') }}
                    </div>
                    @if($edu->description)
                        <div class="item-desc">{{ $edu->description }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Compétences --}}
    @if($cv->skills->isNotEmpty())
        <div class="section">
            <div class="section-title">Compétences</div>
            <div class="skills-list">
                @foreach($cv->skills as $skill)
                    <strong>{{ $skill->name }}</strong>@if($skill->level) ({{ $skill->level }})@endif{{ !$loop->last ? '  •  ' : '' }}
                @endforeach
            </div>
        </div>
    @endif

    {{-- Langues --}}
    @if($cv->languages->isNotEmpty())
        <div class="section">
            <div class="section-title">Langues</div>
            @foreach($cv->languages as $lang)
                <div class="lang-item">
                    <strong>{{ $lang->name }}</strong>
                    @if($lang->level) — {{ $lang->level }} @endif
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>
