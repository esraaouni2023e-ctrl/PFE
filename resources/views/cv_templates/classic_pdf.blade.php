<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CV — {{ $user->name }}</title>
    <style>
        @page { margin: 40px; }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Times New Roman', serif;
            font-size: 10pt;
            color: #222222;
            line-height: 1.5;
            background: #ffffff;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px double #333333;
            padding-bottom: 15px;
        }

        .name {
            font-size: 22pt;
            font-weight: bold;
            color: #111111;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .job-title {
            font-size: 12pt;
            font-style: italic;
            color: #555555;
            margin-bottom: 10px;
        }

        .contact-info {
            font-size: 9pt;
            color: #444444;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #111111;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #333333;
            padding-bottom: 3px;
            margin-bottom: 12px;
        }

        .summary-text {
            font-size: 9.5pt;
            color: #333333;
            line-height: 1.5;
            text-align: justify;
        }

        .grid-2 {
            display: table;
            width: 100%;
        }

        .col {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }

        .col-left {
            padding-right: 15px;
        }

        .col-right {
            padding-left: 15px;
        }

        .item {
            margin-bottom: 12px;
        }

        .item-header {
            font-size: 10pt;
            font-weight: bold;
            color: #111111;
        }

        .item-subheader {
            font-size: 9.5pt;
            font-style: italic;
            color: #444444;
        }

        .item-date {
            font-size: 8.5pt;
            color: #666666;
            margin-bottom: 4px;
        }

        .item-desc {
            font-size: 9pt;
            color: #333333;
            line-height: 1.45;
            text-align: justify;
        }

        .list-items {
            list-style-type: none;
        }

        .list-item {
            font-size: 9.5pt;
            margin-bottom: 5px;
            color: #222222;
        }

        .list-item strong {
            color: #111111;
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
            <div class="section-title">Profil Professionnel</div>
            <div class="summary-text">{{ $cv->summary }}</div>
        </div>
    @endif

    {{-- Expériences --}}
    @if($cv->experiences->isNotEmpty())
        <div class="section">
            <div class="section-title">Expériences Professionnelles</div>
            @foreach($cv->experiences as $exp)
                <div class="item">
                    <div class="item-header">{{ $exp->position }}</div>
                    <div class="item-subheader">{{ $exp->company }}</div>
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
            <div class="section-title">Parcours Académique / Formation</div>
            @foreach($cv->educations as $edu)
                <div class="item">
                    <div class="item-header">
                        {{ $edu->degree }}
                        @if($edu->field_of_study) — {{ $edu->field_of_study }} @endif
                    </div>
                    <div class="item-subheader">{{ $edu->institution }}</div>
                    <div class="item-date">
                        {{ $edu->start_date->format('Y') }} —
                        {{ $edu->is_current ? 'Présent' : $edu->end_date?->format('Y') }}
                    </div>
                    @if($edu->description)
                        <div class="item-desc" style="margin-top: 3px;">{{ $edu->description }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Compétences & Langues --}}
    @if($cv->skills->isNotEmpty() || $cv->languages->isNotEmpty())
        <div class="section">
            <div class="grid-2">
                @if($cv->skills->isNotEmpty())
                    <div class="col col-left">
                        <div class="section-title">Compétences</div>
                        <ul class="list-items">
                            @foreach($cv->skills as $skill)
                                <li class="list-item">
                                    <strong>{{ $skill->name }}</strong>
                                    @if($skill->level) — {{ $skill->level }} @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($cv->languages->isNotEmpty())
                    <div class="col col-right">
                        <div class="section-title">Langues</div>
                        <ul class="list-items">
                            @foreach($cv->languages as $lang)
                                <li class="list-item">
                                    <strong>{{ $lang->name }}</strong>
                                    @if($lang->level) — {{ $lang->level }} @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    @endif
</body>
</html>
