@extends('layouts.student')

@section('title', 'Vos Recommandations')

@section('content')
<div style="max-width: 1100px; margin: 0 auto; padding: 2rem 1rem;">
    
    <!-- En-tête -->
    <div style="text-align: center; margin-bottom: 3rem;">
        <span class="badge-cyan" style="margin-bottom: 1rem; display: inline-block;">CapAvenir IA</span>
        <h1 style="font-family: var(--font-serif); font-size: 2.5rem; margin-bottom: 1rem; color: var(--ink);">
            Vos Recommandations Personnalisées
        </h1>
        <p style="color: var(--ink60); font-size: 1.1rem; max-width: 600px; margin: 0 auto 1.5rem;">
            Basées sur votre profil psychométrique (RIASEC), vos résultats académiques et les tendances du marché du travail.
        </p>
        <form method="POST" action="{{ route('riasec.reset') }}" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-glass">
                🔄 Repasser le test RIASEC
            </button>
        </form>
    </div>

    <!-- Diagnostic et Résumé -->
    @if(!empty($recommendations['diagnostic']))
        <div class="glass-card" style="padding: 1.5rem; margin-bottom: 2rem; border-left: 4px solid var(--accent2);">
            <h3 style="font-family: var(--font-serif); margin-bottom: 0.5rem; color: var(--accent2);">Diagnostic de votre filière actuelle</h3>
            <p style="color: var(--ink60); font-size: 0.95rem;">
                <strong>{{ $recommendations['diagnostic']['diagnostic'] ?? '' }}</strong><br>
                Score d'alignement : <span style="font-weight: 700; color: var(--accent);">{{ ($recommendations['diagnostic']['score'] ?? 0) * 100 }}%</span>
            </p>
        </div>
    @endif

    <!-- Liste des filières recommandées -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
        @forelse($recommendations['recommandations'] ?? [] as $filiere)
            <div class="glass-card" style="padding: 1.5rem; display: flex; flex-direction: column;">
                
                <!-- Rang et Score -->
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div style="width: 32px; height: 32px; border-radius: var(--r); background: var(--accent); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                        #{{ $filiere['Rang'] }}
                    </div>
                    
                    <div style="text-align: right;">
                        <div class="score-number" style="font-size: 1.8rem;">{{ round($filiere['Score_Final_Contextuel'] * 100) }}<span style="font-size: 1rem;">%</span></div>
                        <div style="font-size: 0.7rem; color: var(--ink30); text-transform: uppercase; font-weight: 600;">Match global</div>
                    </div>
                </div>

                <!-- Informations Filière -->
                <h3 style="font-family: var(--font-main); font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--ink); line-height: 1.3;">
                    {{ $filiere['Nom_Filiere'] }}
                </h3>
                
                <div style="font-size: 0.85rem; color: var(--ink60); margin-bottom: 1.5rem; display: flex; flex-direction: column; gap: 0.3rem;">
                    <div>🏛️ {{ $filiere['Etablissement'] }}</div>
                    <div style="opacity: 0.8;">📍 {{ $filiere['Universite'] }}</div>
                </div>

                <!-- Barre de progression Match -->
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; margin-bottom: 0.3rem; font-weight: 600;">
                        <span style="color: var(--ink60);">Compatibilité Psychométrique</span>
                        <span style="color: var(--accent);">{{ round($filiere['Compatibilite_Psychometrique'] * 100) }}%</span>
                    </div>
                    <div class="match-bar-wrap">
                        <div class="match-bar-fill" style="width: {{ $filiere['Compatibilite_Psychometrique'] * 100 }}%;"></div>
                    </div>
                </div>
                
                <!-- Détails (Académique & Marché) -->
                <div style="display: flex; gap: 0.5rem; margin-top: auto; flex-wrap: wrap;">
                    <span class="badge-violet" title="Score Académique (SDO)">
                        🎓 {{ round($filiere['Score_Academique'] * 100) }}%
                    </span>
                    <span class="badge-amber" title="Score Marché (Employabilité)">
                        💼 {{ round($filiere['Score_Marche'] * 100) }}%
                    </span>
                    @if(isset($filiere['Type_Transition']) && $filiere['Type_Transition'] !== 'Nouvelle orientation')
                        <span class="badge-cyan" title="Transition depuis votre filière">
                            🔄 {{ $filiere['Type_Transition'] }}
                        </span>
                    @endif
                </div>

            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--ink60);" class="glass-card">
                Aucune recommandation n'a pu être générée pour votre profil.
            </div>
        @endforelse
    </div>

    @if(isset($recommendations['resume']))
        <!-- Résumé IA -->
        <div class="glass-card" style="padding: 2rem; background: var(--chat-panel-bg);">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                <span style="font-size: 1.5rem;">🤖</span>
                <h3 style="font-family: var(--font-serif); font-size: 1.3rem;">Analyse détaillée de l'IA</h3>
            </div>
            <pre style="font-family: var(--font-main); font-size: 0.95rem; line-height: 1.6; color: var(--ink60); white-space: pre-wrap; background: transparent; border: none; padding: 0;">{{ $recommendations['resume'] }}</pre>
        </div>
    @endif

</div>
@endsection
