@php
    $rang = $f['Rang'] ?? ($loop->index + 1);
    $rankClass = $rang === 1 ? 'top1' : ($rang === 2 ? 'top2' : ($rang === 3 ? 'top3' : ''));
    $riasecPct = round(($f['Compatibilite_Psychometrique'] ?? $f['Score_RIASEC'] ?? 0) * 100);
    $acadPct = round(($f['Score_Academique'] ?? 0) * 100);
    $accessPct = round(($f['Score_Accessibilite'] ?? 0) * 100);
    $marketPct = round(($f['Score_Marche'] ?? 0) * 100);

    $sdoGap = $f['SDO_Gap'] ?? null;
@endphp
<div class="rec-card">
    <div class="rec-card-top">
        <div class="rec-rank {{ $rankClass }}">#{{ $rang }}</div>
    </div>

    <div class="rec-nom">
        {{ $f['Nom_Filiere'] ?? 'Filière' }}
        @if(!empty($f['is_pareto_optimal']))
            <span style="font-size: 0.7rem; background: var(--gold); color: #fff; padding: 2px 6px; border-radius: 4px; margin-left: 6px; vertical-align: middle;">⭐ Choix Optimal</span>
        @endif
        @if(!empty($f['is_serendipity']))
            <span style="font-size: 0.7rem; background: var(--accent); color: #fff; padding: 2px 6px; border-radius: 4px; margin-left: 6px; vertical-align: middle;">💡 Option Découverte</span>
        @endif
    </div>
    <div class="rec-eta">
        <span class="rec-eta-ic">
            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            {{ $f['Etablissement'] ?? '' }}
        </span>
        <span class="rec-eta-ic" style="opacity:.7">
            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            {{ $f['Universite'] ?? '' }}
        </span>
    </div>

    @if(!empty($f['Explication']) && is_array($f['Explication']))
    <div class="rec-expliq" style="font-size: 0.82rem; margin-bottom:1rem;">
        @if(!empty($f['Explication']['raisons']))
            <div style="color:var(--ink60); font-style:normal; margin-bottom: 8px; line-height:1.45;">
                @foreach($f['Explication']['raisons'] as $raison)
                    <p style="margin-bottom: 4px;">{{ $raison }}</p>
                @endforeach
            </div>
        @endif
        @if(!empty($f['Explication']['points_forts']))
            <ul style="margin:0; padding-left:1.2rem; color: #10b981; font-style:normal;">
                @foreach($f['Explication']['points_forts'] as $fort)
                    <li style="margin-bottom: 2px;">{{ $fort }}</li>
                @endforeach
            </ul>
        @endif
        @if(!empty($f['Explication']['points_faibles']))
            <ul style="margin-top:4px; margin-bottom:0; padding-left:1.2rem; color: #ef4444; font-style:normal;">
                @foreach($f['Explication']['points_faibles'] as $faible)
                    <li style="margin-bottom: 2px;">{{ $faible }}</li>
                @endforeach
            </ul>
        @endif
    </div>
    @elseif(!empty($f['Explication']) && is_string($f['Explication']))
    <div class="rec-expliq">{{ $f['Explication'] }}</div>
    @endif

    <div class="rec-bars">
        <div class="rec-bar-row">
            <div class="rec-bar-lbl">Vocation</div>
            <div class="rec-bar-track"><div class="rec-bar-fill" style="width:{{ $riasecPct }}%;background:var(--accent)"></div></div>
            <div class="rec-bar-val" style="color:var(--accent)">{{ $riasecPct }}%</div>
        </div>
        <div class="rec-bar-row">
            <div class="rec-bar-lbl">Cognitive</div>
            <div class="rec-bar-track"><div class="rec-bar-fill" style="width:{{ $acadPct }}%;background:var(--accent2)"></div></div>
            <div class="rec-bar-val" style="color:var(--accent2)">{{ $acadPct }}%</div>
        </div>
        <div class="rec-bar-row">
            <div class="rec-bar-lbl">Access</div>
            <div class="rec-bar-track"><div class="rec-bar-fill" style="width:{{ $accessPct }}%;background:var(--gold)"></div></div>
            <div class="rec-bar-val" style="color:var(--gold)">{{ $accessPct }}%</div>
        </div>
        @if($sdoGap !== null)
            <div style="font-size:0.75rem; font-weight:600; margin-top:-0.2rem; margin-bottom:0.6rem; color:{{ $sdoGap >= 0 ? 'var(--accent3)' : '#ef4444' }};">
                SDO Gap : {{ $sdoGap >= 0 ? '+' : '' }}{{ round($sdoGap, 1) }}
            </div>
        @endif
        <div class="rec-bar-row">
            <div class="rec-bar-lbl">Market</div>
            <div class="rec-bar-track"><div class="rec-bar-fill" style="width:{{ $marketPct }}%;background:var(--accent3)"></div></div>
            <div class="rec-bar-val" style="color:var(--accent3)">{{ $marketPct }}%</div>
        </div>
    </div>

    <div class="rec-tags">
        @if(!empty($f['Type_Transition']))
        <span class="rec-tag transition">{{ $f['Type_Transition'] }}</span>
        @endif
        @if(!empty($f['Taux_Employabilite']))
        <span class="rec-tag emploi">{{ $f['Taux_Employabilite'] }}</span>
        @endif
        @if(!empty($f['Code_RIASEC']))
        <span class="rec-tag transition" style="font-family:var(--font-serif);font-style:italic">{{ $f['Code_RIASEC'] }}</span>
        @endif
    </div>

    @if(!empty($f['Career_Path']))
    <div class="career-acc">
        <button type="button" class="career-acc-trigger" onclick="toggleCareerAccordion(this)">
            <span>💼 Métiers & Débouchés Réels</span>
            <svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.2s;"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="career-acc-content">
            <div class="career-acc-inner">
                <div class="career-domain-title">{{ $f['Career_Path']['domain_label'] ?? 'Domaine Professionnel' }}</div>
                @foreach($f['Career_Path']['careers'] ?? [] as $career)
                    <div class="career-item">
                        <div class="career-item-title">{{ $career['title'] }}</div>
                        <div class="career-item-desc">{{ $career['description'] }}</div>
                        <div class="career-meta-row">
                            <span class="career-meta-badge salary">💰 {{ $career['salary_range'] }}</span>
                            <span class="career-meta-badge employability">📈 Employabilité : {{ $career['employability'] }}</span>
                        </div>
                        <div style="font-size:0.68rem; color:var(--ink40); font-weight:600; margin-top:0.25rem;">
                            📍 Secteurs en Tunisie : {{ implode(', ', $career['secteurs'] ?? []) }}
                        </div>
                        <div class="career-skills-list">
                            @foreach($career['skills_hard'] ?? [] as $sh)
                                <span class="career-skill-tag" style="color:var(--accent2); background:color-mix(in srgb, var(--accent2) 8%, transparent);">{{ $sh }}</span>
                            @endforeach
                            @foreach($career['skills_soft'] ?? [] as $ss)
                                <span class="career-skill-tag">{{ $ss }}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Feedback UI --}}
    @php
        $codeF = $f['Code_Filiere'] ?? $f['code_filiere'] ?? '';
        $existingFb = $feedbacks[$codeF] ?? null;
        $isLikeActive = $existingFb && $existingFb['is_relevant'] == true;
        $isDislikeActive = $existingFb && $existingFb['is_relevant'] == false;
        $ratingVal = $existingFb ? $existingFb['rating'] : 0;
    @endphp
    <div class="rec-feedback-container" data-filiere="{{ $codeF }}" style="margin-top:1.1rem; padding-top:1rem; border-top:1px solid var(--glass-border);">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; flex-wrap:wrap;">
            <span style="font-size:0.72rem; font-weight:600; color:var(--ink60);">Recommandation pertinente ?</span>
            <div style="display:flex; align-items:center; gap:0.4rem;">
                <button type="button" class="feedback-btn like-btn {{ $isLikeActive ? 'active' : '' }}" onclick="submitFeedback(this, '{{ $codeF }}', true)" title="Oui, pertinent">
                    👍 Oui
                </button>
                <button type="button" class="feedback-btn dislike-btn {{ $isDislikeActive ? 'active' : '' }}" onclick="submitFeedback(this, '{{ $codeF }}', false)" title="Non, non pertinent">
                    👎 Non
                </button>
            </div>
        </div>
        <div class="feedback-stars-container" style="display:{{ $existingFb ? 'flex' : 'none' }}; align-items:center; justify-content:space-between; gap:0.5rem; margin-top:0.75rem;">
            <span style="font-size:0.7rem; font-weight:500; color:var(--ink30);">Note d'adéquation :</span>
            <div class="feedback-stars" data-rating="{{ $ratingVal }}">
                @for($star = 1; $star <= 5; $star++)
                    <span class="feedback-star {{ $star <= $ratingVal ? 'active' : '' }}" onclick="rateFeedback(this, '{{ $codeF }}', {{ $star }})">★</span>
                @endfor
            </div>
        </div>
    </div>
</div>
