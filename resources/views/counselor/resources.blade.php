@extends('layouts.counselor')

@section('page-heading')
Ressources<br><em>IA.</em>
@endsection

@section('page-subtitle')
Consultez la base de connaissances scientifiques, découvrez le fonctionnement de nos moteurs d'IA et accédez aux guides méthodologiques d'orientation.
@endsection

@section('content')
<style>
    .cr-container {
        font-family: var(--font-main);
        display: flex;
        flex-direction: column;
        gap: 2.5rem;
        padding-bottom: 4rem;
    }

    /* Engine Stats Banner */
    .cr-stats-banner {
        background: linear-gradient(135deg, var(--accent2), color-mix(in srgb, var(--accent2) 75%, #000));
        border-radius: var(--rl);
        padding: 2rem;
        color: #fff;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        box-shadow: 0 10px 30px -15px color-mix(in srgb, var(--accent2) 30%, transparent);
    }

    .cr-stat-item {
        display: flex;
        flex-direction: column;
        gap: .25rem;
        border-right: 1px solid rgba(255, 255, 255, 0.15);
        padding-right: 1rem;
    }

    .cr-stat-item:last-child {
        border-right: none;
        padding-right: 0;
    }

    .cr-stat-value {
        font-size: 1.85rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .cr-stat-label {
        font-size: .75rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: .02em;
    }

    /* Section Styling */
    .cr-section {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .cr-section-title {
        font-family: var(--font-serif);
        font-size: 1.75rem;
        font-weight: 300;
        color: var(--ink);
        margin: 0;
        display: flex;
        align-items: center;
        gap: .75rem;
        letter-spacing: -.02em;
    }

    .cr-section-title em {
        font-style: italic;
        color: var(--accent);
        font-weight: 600;
    }

    .cr-section-intro {
        font-size: .92rem;
        color: var(--ink60);
        line-height: 1.65;
        max-width: 750px;
        margin: 0;
    }

    /* RIASEC Grid */
    .cr-riasec-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-top: .5rem;
    }

    .cr-riasec-card {
        background: var(--paper);
        border: 1px solid var(--ink10);
        border-radius: var(--rl);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        transition: var(--transition);
        position: relative;
    }

    .cr-riasec-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -10px rgba(0, 0, 0, 0.05);
    }

    .cr-riasec-letter {
        font-family: var(--font-serif);
        font-size: 3rem;
        font-weight: 800;
        line-height: .9;
    }

    .cr-riasec-name {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
    }

    .cr-riasec-desc {
        font-size: .85rem;
        color: var(--ink60);
        line-height: 1.6;
        flex-grow: 1;
    }

    .cr-riasec-careers-title {
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ink);
        letter-spacing: .02em;
        margin-bottom: .25rem;
    }

    .cr-riasec-careers {
        font-size: .8rem;
        color: var(--ink80);
        font-weight: 500;
        line-height: 1.4;
    }

    /* GATB Grid */
    .cr-gatb-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.25rem;
    }

    .cr-gatb-card {
        background: var(--paper);
        border: 1px solid var(--ink10);
        border-radius: var(--r);
        padding: 1.25rem;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        transition: var(--transition);
    }

    .cr-gatb-card:hover {
        border-color: var(--accent2);
    }

    .cr-gatb-code {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--ink06);
        color: var(--ink);
        font-weight: 700;
        font-size: .95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .cr-gatb-info {
        display: flex;
        flex-direction: column;
        gap: .25rem;
    }

    .cr-gatb-name {
        font-size: .95rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
    }

    .cr-gatb-desc {
        font-size: .8rem;
        color: var(--ink60);
        line-height: 1.5;
        margin: 0;
    }

    /* Guides Grid */
    .cr-guides-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .cr-guide-card {
        background: var(--paper);
        border: 1px solid var(--ink10);
        border-radius: var(--rl);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        transition: var(--transition);
        text-decoration: none;
    }

    .cr-guide-card:hover {
        border-color: var(--accent);
        transform: translateY(-2px);
    }

    .cr-guide-badge {
        background: var(--ink06);
        color: var(--ink80);
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: .25rem .6rem;
        border-radius: var(--rx);
        align-self: flex-start;
    }

    .cr-guide-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
    }

    .cr-guide-desc {
        font-size: .88rem;
        color: var(--ink60);
        line-height: 1.6;
        margin: 0;
        flex-grow: 1;
    }

    .cr-guide-footer {
        display: flex;
        align-items: center;
        gap: .4rem;
        font-size: .75rem;
        color: var(--ink60);
        font-weight: 500;
        border-top: 1px solid var(--ink10);
        padding-top: .75rem;
    }

    /* FAQ Section Accordion */
    .cr-faq-list {
        display: flex;
        flex-direction: column;
        gap: .75rem;
    }

    .cr-faq-item {
        background: var(--paper);
        border: 1px solid var(--ink10);
        border-radius: var(--r);
        overflow: hidden;
        transition: var(--transition);
    }

    .cr-faq-item[open] {
        border-color: var(--accent2);
    }

    .cr-faq-summary {
        padding: 1.25rem;
        font-size: .95rem;
        font-weight: 700;
        color: var(--ink);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        user-select: none;
        list-style: none;
    }

    .cr-faq-summary::-webkit-details-marker {
        display: none;
    }

    .cr-faq-summary svg {
        transition: transform .3s ease;
        flex-shrink: 0;
    }

    .cr-faq-item[open] .cr-faq-summary svg {
        transform: rotate(180deg);
        color: var(--accent);
    }

    .cr-faq-answer {
        padding: 0 1.25rem 1.25rem 1.25rem;
        font-size: .88rem;
        color: var(--ink60);
        line-height: 1.65;
        margin: 0;
        border-top: 1px dashed var(--ink10);
        padding-top: 1rem;
    }

    /* Dark Mode Overrides */
    [data-theme="dark"] .cr-riasec-card,
    [data-theme="dark"] .cr-gatb-card,
    [data-theme="dark"] .cr-guide-card,
    [data-theme="dark"] .cr-faq-item {
        background: var(--ink06);
        border-color: var(--ink10);
    }

    [data-theme="dark"] .cr-gatb-code {
        background: var(--paper);
    }
</style>

<div class="cr-container">

    <!-- A. Engine Stats Banner -->
    <div class="cr-stats-banner">
        <div class="cr-stat-item">
            <span class="cr-stat-value">{{ $engineStats['accuracy'] }}%</span>
            <span class="cr-stat-label">Précision IA</span>
        </div>
        <div class="cr-stat-item">
            <span class="cr-stat-value">{{ $engineStats['studentsProcessed'] }}</span>
            <span class="cr-stat-label">Élèves Suivis</span>
        </div>
        <div class="cr-stat-item">
            <span class="cr-stat-value">{{ $engineStats['testsAnalyzed'] }}</span>
            <span class="cr-stat-label">Tests Profilés</span>
        </div>
        <div class="cr-stat-item">
            <span class="cr-stat-value">{{ $engineStats['avgProcessingTime'] }}</span>
            <span class="cr-stat-label">Calcul Profil</span>
        </div>
        <div class="cr-stat-item">
            <span class="cr-stat-value" style="font-size: 1.15rem; font-weight: 700; height: 33px; display: flex; align-items: center;">{{ $engineStats['modelVersion'] }}</span>
            <span class="cr-stat-label">Moteur IA</span>
        </div>
        <div class="cr-stat-item">
            <span class="cr-stat-value" style="font-size: 1.15rem; font-weight: 700; height: 33px; display: flex; align-items: center;">{{ $engineStats['lastTraining'] }}</span>
            <span class="cr-stat-label">Entraînement</span>
        </div>
    </div>

    <!-- B. RIASEC Section -->
    <div class="cr-section">
        <h2 class="cr-section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent);"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10M6 10h10"/></svg>
            Le Modèle <em>RIASEC.</em>
        </h2>
        <p class="cr-section-intro">
            Théorisé par John Holland, le typage RIASEC permet d'identifier six types de personnalités professionnelles. La plupart des personnes possèdent des affinités avec deux ou trois types dominants, dont la combinaison forme leur code d'orientation.
        </p>

        <div class="cr-riasec-grid">
            @foreach($riasecDimensions as $d)
                <div class="cr-riasec-card" style="border-top: 3px solid {{ $d['color'] }};">
                    <span class="cr-riasec-letter" style="color: {{ $d['color'] }};">{{ $d['code'] }}</span>
                    <h3 class="cr-riasec-name">{{ $d['name'] }}</h3>
                    <p class="cr-riasec-desc">{{ $d['desc'] }}</p>
                    <div style="border-top: 1px solid var(--ink10); padding-top: .75rem;">
                        <span class="cr-riasec-careers-title">Carrières Types</span>
                        <div class="cr-riasec-careers">{{ $d['careers'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- C. GATB Section -->
    <div class="cr-section" style="margin-top: 1rem;">
        <h2 class="cr-section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent2);"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96-.44 2.5 2.5 0 0 1 0-3.12 3 3 0 0 1 0-3.88 2.5 2.5 0 0 1 0-3.12A2.5 2.5 0 0 1 9.5 2Z"/><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96-.44 2.5 2.5 0 0 0 0-3.12 3 3 0 0 0 0-3.88 2.5 2.5 0 0 0 0-3.12A2.5 2.5 0 0 0 14.5 2Z"/></svg>
            Aptitudes Cognitives <em>GATB.</em>
        </h2>
        <p class="cr-section-intro">
            La batterie GATB (General Aptitude Test Battery) évalue les facultés intellectuelles et motrices fondamentales indispensables à l'assimilation des apprentissages et à la réussite professionnelle.
        </p>

        <div class="cr-gatb-grid">
            @foreach($gatbAptitudes as $g)
                <div class="cr-gatb-card">
                    <div class="cr-gatb-code">{{ $g['code'] }}</div>
                    <div class="cr-gatb-info">
                        <h4 class="cr-gatb-name">{{ $g['name'] }}</h4>
                        <p class="cr-gatb-desc">{{ $g['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- D. Guides Section -->
    <div class="cr-section" style="margin-top: 1rem;">
        <h2 class="cr-section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent3);"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            Guides <em>Pratiques.</em>
        </h2>
        <p class="cr-section-intro">
            Consultez nos fiches pratiques et méthodologies rédigées pour perfectionner l'accompagnement individuel des étudiants.
        </p>

        <div class="cr-guides-grid">
            @foreach($guides as $guide)
                <a href="#" class="cr-guide-card">
                    <span class="cr-guide-badge">{{ $guide['category'] }}</span>
                    <h3 class="cr-guide-title">{{ $guide['title'] }}</h3>
                    <p class="cr-guide-desc">{{ $guide['desc'] }}</p>
                    <div class="cr-guide-footer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>Lecture : {{ $guide['readTime'] }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- E. FAQ Section -->
    <div class="cr-section" style="margin-top: 1rem;">
        <h2 class="cr-section-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent);"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Questions <em>Fréquentes.</em>
        </h2>
        <p class="cr-section-intro">
            Retrouvez les réponses aux interrogations régulières des conseillers d'orientation sur l'usage de la plateforme CapAvenir.
        </p>

        <div class="cr-faq-list">
            @foreach($faq as $item)
                <details class="cr-faq-item">
                    <summary class="cr-faq-summary">
                        <span>{{ $item['q'] }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </summary>
                    <p class="cr-faq-answer">
                        {{ $item['a'] }}
                    </p>
                </details>
            @endforeach
        </div>
    </div>

</div>
@endsection
