@extends('layouts.student')

@section('title', 'Tableau de Bord')

@section('content')
<style>
    /* Mobile Dashboard Styling */
    .db-mob-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* Hero section */
    .hero-card-mob {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 1.5rem 1.25rem;
        box-shadow: var(--shadow-card);
        position: relative;
        overflow: hidden;
    }
    .hero-title-mob {
        font-family: var(--font-serif);
        font-size: 1.6rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 0.5rem;
    }
    .hero-title-mob em {
        font-style: italic;
        color: var(--accent);
    }
    .hero-sub-mob {
        font-size: 0.85rem;
        color: var(--ink60);
        line-height: 1.5;
        margin-bottom: 1.25rem;
    }

    /* Stats Grid */
    .stats-grid-mob {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }
    .stat-card-mob {
        background: var(--cream);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 0.75rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 4px;
    }
    .stat-card-mob.full-w {
        grid-column: span 2;
    }
    .stat-card-mob i {
        font-size: 1.2rem;
        color: var(--accent);
    }
    .stat-card-mob b {
        font-size: 1.15rem;
        font-family: var(--font-serif);
        color: var(--ink);
    }
    .stat-card-mob span {
        font-size: 0.7rem;
        color: var(--ink60);
        font-weight: 500;
    }

    /* Section Headers */
    .section-header-mob {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }
    .section-title-mob {
        font-family: var(--font-serif);
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--ink);
    }
    .section-title-mob em {
        font-style: italic;
        color: var(--accent);
    }

    /* Quick Action Buttons */
    .actions-grid-mob {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }
    .action-button-mob {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 1rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        text-decoration: none;
        color: var(--ink);
        box-shadow: var(--shadow-card);
        transition: var(--transition);
    }
    .action-button-mob:active {
        background: var(--cream);
    }
    .action-button-mob i {
        font-size: 1.5rem;
        color: var(--accent);
    }
    .action-button-mob h4 {
        font-size: 0.85rem;
        font-weight: 700;
    }
    .action-button-mob p {
        font-size: 0.7rem;
        color: var(--ink60);
        line-height: 1.3;
    }

    /* Recomended filieres list */
    .filiere-list-mob {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .filiere-card-mob {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        box-shadow: var(--shadow-card);
        text-decoration: none;
        color: var(--ink);
    }
    .filiere-card-mob:active {
        background: var(--cream);
    }
    .filiere-info-mob {
        flex: 1;
        min-width: 0;
    }
    .filiere-name-mob {
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .filiere-univ-mob {
        font-size: 0.72rem;
        color: var(--ink60);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .filiere-score-mob {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: color-mix(in srgb, var(--accent) 8%, transparent);
        color: var(--accent);
        font-size: 0.85rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid color-mix(in srgb, var(--accent) 15%, transparent);
    }

    /* Cognitive Profile Card */
    .profile-card-mob {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 1.25rem;
        box-shadow: var(--shadow-card);
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    .interest-row-mob {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .interest-meta-mob {
        display: flex;
        justify-content: space-between;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .interest-bar-track-mob {
        height: 6px;
        background: var(--cream);
        border-radius: var(--rx);
        overflow: hidden;
        border: 1px solid var(--glass-border);
    }
    .interest-bar-fill-mob {
        height: 100%;
        border-radius: var(--rx);
    }

    /* Tags list */
    .tags-container-mob {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }
    .tag-mob {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.35rem 0.75rem;
        border-radius: var(--rx);
        background: var(--cream);
        border: 1px solid var(--glass-border);
        color: var(--ink60);
    }

    /* Timeline */
    .timeline-mob {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .timeline-row-mob {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.65rem 0.85rem;
        background: var(--cream);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
    }
    .timeline-title-mob {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--ink);
    }
    .timeline-date-mob {
        font-size: 0.7rem;
        color: var(--ink30);
        margin-top: 1px;
    }
    .timeline-score-mob {
        font-family: var(--font-serif);
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--accent);
    }
</style>

<div class="db-mob-container">
    @if(session('success'))
    <div style="background: color-mix(in srgb, var(--success) 8%, var(--paper)); border: 1px solid color-mix(in srgb, var(--success) 20%, transparent); color: var(--success); padding: 0.75rem; border-radius: var(--r); text-align: center; font-weight: 600; font-size: 0.8rem;">
        {{ session('success') }}
    </div>
    @endif

    {{-- HERO SECTION --}}
    <section class="hero-card-mob">
        <h1 class="hero-title-mob">
            Ton avenir<br>
            <em>se dessine</em> <strong>ici.</strong>
        </h1>
        <p class="hero-sub-mob">
            Bienvenue, <strong>{{ explode(' ', $studentName ?? auth()->user()->name)[0] }}</strong>. Construis ton projet professionnel et découvre tes orientations idéales.
        </p>

        <div class="stats-grid-mob">
            <div class="stat-card-mob">
                <i class="bi bi-patch-check"></i>
                <b>{{ $dashboardStats['tests_completed'] ?? 0 }}</b>
                <span>Tests faits</span>
            </div>
            <div class="stat-card-mob">
                <i class="bi bi-compass"></i>
                <b>{{ $dashboardStats['suggested_paths'] ?? 0 }}</b>
                <span>Filières suggérées</span>
            </div>
            <div class="stat-card-mob full-w">
                <div style="display:flex; align-items:center; gap: 0.5rem; justify-content:center;">
                    <i class="bi bi-cpu" style="color:var(--accent3);"></i>
                    <span>Fiabilité Profil IA : <strong style="color:var(--ink); font-size: 0.9rem;">{{ $dashboardStats['reliability_score'] ?? 0 }}%</strong></span>
                </div>
            </div>
        </div>
    </section>

    {{-- QUICK ACTIONS --}}
    <section>
        <div class="section-header-mob">
            <h2 class="section-title-mob">Mon <em>Parcours</em></h2>
        </div>
        <div class="actions-grid-mob">
            <a href="{{ route('student.pipeline') }}" class="action-button-mob" style="border-color: var(--accent);">
                <i class="bi bi-rocket-takeoff"></i>
                <h4>Orientation IA</h4>
                <p>Passer les tests psychométriques adaptatifs.</p>
            </a>
            <button class="action-button-mob" onclick="document.getElementById('floatingChatMob')?.click();">
                <i class="bi bi-chat-left-dots" style="color: var(--accent3);"></i>
                <h4>Nova Chatbot</h4>
                <p>Discuter orientation avec notre IA intégrée.</p>
            </button>
            <a href="{{ route('student.cv.index') }}" class="action-button-mob">
                <i class="bi bi-file-earmark-person"></i>
                <h4>CV Builder</h4>
                <p>Créer et exporter un CV professionnel.</p>
            </a>
            <a href="{{ route('student.whatif.index') }}" class="action-button-mob">
                <i class="bi bi-magic" style="color: var(--gold);"></i>
                <h4>BAC What-If</h4>
                <p>Simuler vos notes du Bac et calculer vos chances.</p>
            </a>
        </div>
    </section>

    {{-- COGNITIVE PROFILE --}}
    <section>
        <div class="section-header-mob">
            <h2 class="section-title-mob">Mon Profil <em>Cognitif</em></h2>
        </div>
        <div class="profile-card-mob">
            {{-- Interests levels --}}
            <div style="display:flex; flex-direction:column; gap:0.8rem;">
                @foreach($dynamicSkills as $s)
                <div class="interest-row-mob">
                    <div class="interest-meta-mob">
                        <span style="color: var(--ink60);">{{ $s['label'] }}</span>
                        <span style="color: {{ $s['color'] }}">{{ $s['val'] }}%</span>
                    </div>
                    <div class="interest-bar-track-mob">
                        <div class="interest-bar-fill-mob" style="width: {{ $s['val'] }}%; background: {{ $s['color'] }};"></div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Strengths --}}
            <div>
                <h4 style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink30); margin-bottom: 0.5rem;">Points forts</h4>
                <div class="tags-container-mob">
                    @php
                        $forces = ['Test RIASEC à compléter'];
                        if(isset($profilRiasec) && !empty($profilRiasec->interpretation['forces'])) {
                            $forces = array_slice($profilRiasec->interpretation['forces'], 0, 5);
                        }
                    @endphp
                    @foreach($forces as $tag)
                        <span class="tag-mob">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Timeline --}}
            <div>
                <h4 style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink30); margin-bottom: 0.5rem;">Historique</h4>
                <div class="timeline-mob">
                    @foreach(array_slice($profileTimeline ?? [], 0, 2) as $tl)
                    <div class="timeline-row-mob">
                        <div>
                            <div class="timeline-title-mob">{{ $tl['title'] }}</div>
                            <div class="timeline-date-mob">{{ $tl['date'] }}</div>
                        </div>
                        <div class="timeline-score-mob">{{ $tl['score'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- RECOMMENDED PATHS --}}
    <section style="margin-bottom: 1rem;">
        <div class="section-header-mob">
            <h2 class="section-title-mob">Top <em>Filières</em></h2>
            <a href="{{ route('student.recommendations') }}" style="font-size: 0.75rem; font-weight: 700; color: var(--accent); text-decoration: none;">Voir tout</a>
        </div>

        <div class="filiere-list-mob">
            @if(!empty($predictions))
                @foreach($predictions as $p)
                <a href="{{ route('student.orientation') }}" class="filiere-card-mob">
                    <div class="filiere-info-mob">
                        <h4 class="filiere-name-mob">{{ $p['name'] }}</h4>
                        <p class="filiere-univ-mob">{{ $p['univ'] }}</p>
                    </div>
                    <div class="filiere-score-mob">
                        {{ isset($p['score']) ? $p['score'] . '%' : 'Bac' }}
                    </div>
                </a>
                @endforeach
            @else
                <div style="background: var(--paper); border: 1px dashed var(--glass-border); padding: 1.5rem; text-align: center; border-radius: var(--rl); font-size: 0.85rem; color: var(--ink60);">
                    <p>Complétez votre profil IA pour voir vos filières recommandées.</p>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
