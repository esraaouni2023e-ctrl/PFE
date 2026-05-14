<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CV — {{ $user->name }}</title>
    <style>
        @page { margin: 55px 50px; }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', 'Helvetica', sans-serif;
            font-size: 9.5pt;
            color: #444444;
            line-height: 1.5;
            background: #ffffff;
        }

        .header-name {
            font-size: 18pt;
            font-weight: bold;
            color: #111111;
            letter-spacing: -0.5px;
        }
        .header-job {
            font-size: 9pt;
            color: #888888;
            margin-top: 2px;
        }
        .header-contact {
            font-size: 8pt;
            color: #aaaaaa;
            margin-top: 4px;
        }
        .header-line {
            width: 40px;
            height: 2px;
            background: #111111;
            margin: 16px 0 20px;
        }

        .section { margin-bottom: 18px; }
        .section-title {
            font-size: 8.5pt;
            font-weight: bold;
            color: #111111;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .summary-text {
            font-size: 9pt;
            color: #666666;
            line-height: 1.65;
        }

        .item { margin-bottom: 14px; }
        .item-title {
            font-size: 9.5pt;
            font-weight: bold;
            color: #222222;
        }
        .item-sub { font-size: 8.5pt; color: #888888; }
        .item-date { font-size: 7.5pt; color: #aaaaaa; }
        .item-desc {
            font-size: 8.5pt;
            color: #666666;
            line-height: 1.55;
            margin-top: 3px;
        }

        .skills-text { font-size: 8.5pt; color: #666666; }
        .lang-text { font-size: 8.5pt; color: #666666; margin-bottom: 3px; }
    </style>
</head>
<body>
    <div class="header-name">{{ $user->name }}</div>
    @if($cv->target_job)
        <div class="header-job">{{ $cv->target_job }}</div>
    @endif
    <div class="header-contact">{{ $user->email }}</div>
    <div class="header-line"></div>

    @if($cv->summary)
        <div class="section">
            <div class="section-title">Profil</div>
            <div class="summary-text">{{ $cv->summary }}</div>
        </div>
    @endif

    @if($cv->experiences->isNotEmpty())
        <div class="section">
            <div class="section-title">Expérience</div>
            @foreach($cv->experiences as $exp)
                <div class="item">
                    <div class="item-title">{{ $exp->position }}</div>
                    <div class="item-sub">{{ $exp->company }}</div>
                    <div class="item-date">
                        {{ $exp->start_date->format('m/Y') }} —
                        {{ $exp->is_current ? 'Présent' : $exp->end_date?->format('m/Y') }}
                    </div>
                    <div class="item-desc">{!! nl2br(e($exp->description)) !!}</div>
                </div>
            @endforeach
        </div>
    @endif

    @if($cv->educations->isNotEmpty())
        <div class="section">
            <div class="section-title">Formation</div>
            @foreach($cv->educations as $edu)
                <div class="item">
                    <div class="item-title">
                        {{ $edu->degree }}@if($edu->field_of_study) — {{ $edu->field_of_study }}@endif
                    </div>
                    <div class="item-sub">{{ $edu->institution }}</div>
                    <div class="item-date">
                        {{ $edu->start_date->format('Y') }} —
                        {{ $edu->is_current ? 'Présent' : $edu->end_date?->format('Y') }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($cv->skills->isNotEmpty())
        <div class="section">
            <div class="section-title">Compétences</div>
            <div class="skills-text">
                @foreach($cv->skills as $skill)
                    {{ $skill->name }}@if($skill->level) ({{ $skill->level }})@endif{{ !$loop->last ? '  ·  ' : '' }}
                @endforeach
            </div>
        </div>
    @endif

    @if($cv->languages->isNotEmpty())
        <div class="section">
            <div class="section-title">Langues</div>
            @foreach($cv->languages as $lang)
                <div class="lang-text">{{ $lang->name }}@if($lang->level) — {{ $lang->level }}@endif</div>
            @endforeach
        </div>
    @endif
</body>
</html>
