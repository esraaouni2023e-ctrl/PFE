<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CV — {{ $user->name }}</title>
    <style>
        @page { margin: 50px; }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9.5pt;
            color: #333333;
            line-height: 1.5;
            background: #ffffff;
        }

        .header {
            margin-bottom: 30px;
        }

        .name {
            font-size: 20pt;
            font-weight: 300;
            color: #111111;
            letter-spacing: -0.5px;
            margin-bottom: 2px;
        }

        .job-title {
            font-size: 11pt;
            color: #666666;
            margin-bottom: 10px;
        }

        .contact-info {
            font-size: 8.5pt;
            color: #888888;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 10pt;
            font-weight: bold;
            color: #666666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .summary-text {
            color: #444444;
            line-height: 1.5;
        }

        .item {
            margin-bottom: 15px;
            position: relative;
        }

        .item-row {
            display: table;
            width: 100%;
            margin-bottom: 2px;
        }

        .item-cell-left {
            display: table-cell;
            font-weight: bold;
            color: #111111;
        }

        .item-cell-right {
            display: table-cell;
            text-align: right;
            font-size: 8.5pt;
            color: #888888;
            width: 120px;
        }

        .item-subtitle {
            font-size: 9pt;
            color: #666666;
            margin-bottom: 4px;
        }

        .item-desc {
            font-size: 9pt;
            color: #555555;
            line-height: 1.45;
        }

        .skills-list {
            margin-top: 5px;
        }

        .skill-item {
            display: inline-block;
            background: #f5f5f5;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8.5pt;
            color: #444444;
            margin-right: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="name">{{ $user->name }}</div>
        @if($cv->target_job)
            <div class="job-title">{{ $cv->target_job }}</div>
        @endif
        <div class="contact-info">
            ✉ {{ $user->email }}
        </div>
    </div>

    {{-- Profil / Résumé --}}
    @if($cv->summary)
        <div class="section">
            <div class="section-title">Résumé</div>
            <div class="summary-text">{{ $cv->summary }}</div>
        </div>
    @endif

    {{-- Expériences --}}
    @if($cv->experiences->isNotEmpty())
        <div class="section">
            <div class="section-title">Expérience</div>
            @foreach($cv->experiences as $exp)
                <div class="item">
                    <div class="item-row">
                        <div class="item-cell-left">{{ $exp->position }}</div>
                        <div class="item-cell-right">
                            {{ $exp->start_date->format('m/Y') }} —
                            {{ $exp->is_current ? 'Présent' : $exp->end_date?->format('m/Y') }}
                        </div>
                    </div>
                    <div class="item-subtitle">{{ $exp->company }}</div>
                    <div class="item-desc">{!! nl2br(e($exp->description)) !!}</div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Formations --}}
    @if($cv->educations->isNotEmpty())
        <div class="section">
            <div class="section-title">Éducation</div>
            @foreach($cv->educations as $edu)
                <div class="item">
                    <div class="item-row">
                        <div class="item-cell-left">
                            {{ $edu->degree }}
                            @if($edu->field_of_study) — {{ $edu->field_of_study }} @endif
                        </div>
                        <div class="item-cell-right">
                            {{ $edu->start_date->format('Y') }} —
                            {{ $edu->is_current ? 'Présent' : $edu->end_date?->format('Y') }}
                        </div>
                    </div>
                    <div class="item-subtitle">{{ $edu->institution }}</div>
                    @if($edu->description)
                        <div class="item-desc" style="margin-top: 2px;">{{ $edu->description }}</div>
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
                    <span class="skill-item">
                        {{ $skill->name }}@if($skill->level) ({{ $skill->level }})@endif
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Langues --}}
    @if($cv->languages->isNotEmpty())
        <div class="section">
            <div class="section-title">Langues</div>
            <div class="skills-list">
                @foreach($cv->languages as $lang)
                    <span class="skill-item">
                        {{ $lang->name }}@if($lang->level) ({{ $lang->level }})@endif
                    </span>
                @endforeach
            </div>
        </div>
    @endif
</body>
</html>
