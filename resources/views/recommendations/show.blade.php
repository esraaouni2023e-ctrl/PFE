@extends('layouts.student')
@section('title', 'Recommandations Personnalisées — CapAvenir')

@section('content')
<style>
.rec{padding:2.5rem 2.5rem 5rem;max-width:1100px;margin:0 auto;font-family:var(--font-main)}
.rec-eye{display:inline-flex;align-items:center;gap:.5rem;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--accent);margin-bottom:1rem}
.rec-eye::before{content:'';width:14px;height:1px;background:var(--accent)}
.rec-title{font-family:var(--font-serif);font-size:clamp(2rem,4vw,2.8rem);font-weight:300;letter-spacing:-.04em;font-style:italic;color:var(--ink);line-height:1.1;margin-bottom:.75rem}
.rec-title em{color:var(--accent);font-style:italic}
.rec-sub{font-size:.92rem;color:var(--ink60);max-width:640px;line-height:1.7;margin-bottom:2rem}

/* Diagnostic banner */
.rec-diag{background:color-mix(in srgb,var(--accent2) 6%,transparent);border:1px solid color-mix(in srgb,var(--accent2) 20%,transparent);border-radius:var(--rl);padding:1.5rem 1.75rem;margin-bottom:2rem;display:flex;align-items:flex-start;gap:1rem}
.rec-diag-ic{flex-shrink:0;width:44px;height:44px;border-radius:12px;background:var(--accent2);color:#fff;display:flex;align-items:center;justify-content:center}
.rec-diag-body p{font-size:.9rem;color:var(--ink60);line-height:1.7;margin:0}
.rec-diag-body strong{color:var(--ink)}

/* Cards grid */
.rec-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem;margin-bottom:2rem}

/* Card */
.rec-card{background:var(--ink06);border:1px solid var(--glass-border);border-radius:var(--rl);padding:1.4rem;display:flex;flex-direction:column;transition:border-color .25s,transform .25s;position:relative;overflow:hidden}
.rec-card:hover{border-color:var(--glass-border-vivid);transform:translateY(-2px)}
.rec-card-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem}
.rec-rank{width:30px;height:30px;border-radius:8px;background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:800;flex-shrink:0}
.rec-rank.top1{background:var(--gold)}
.rec-rank.top2{background:var(--accent2)}
.rec-rank.top3{background:var(--accent3)}
.rec-match{text-align:right}
.rec-match-num{font-family:var(--font-serif);font-size:1.9rem;font-weight:600;line-height:1;color:var(--accent)}
.rec-match-lbl{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink30);margin-top:.1rem}
.rec-nom{font-size:1rem;font-weight:700;color:var(--ink);line-height:1.3;margin-bottom:.35rem}
.rec-eta{font-size:.78rem;color:var(--ink60);display:flex;flex-direction:column;gap:.18rem;margin-bottom:1.1rem}
.rec-eta-ic{display:inline-flex;align-items:center;gap:.3rem}

/* Mini bars */
.rec-bars{display:flex;flex-direction:column;gap:.5rem;margin-bottom:1rem}
.rec-bar-row{display:flex;align-items:center;gap:.5rem}
.rec-bar-lbl{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--ink30);width:70px;flex-shrink:0}
.rec-bar-track{flex:1;height:5px;background:var(--ink10);border-radius:var(--rx);overflow:hidden}
.rec-bar-fill{height:100%;border-radius:var(--rx);transition:width 1.2s cubic-bezier(.4,0,.2,1)}
.rec-bar-val{font-size:.65rem;font-weight:700;width:28px;text-align:right;flex-shrink:0}

/* Tags */
.rec-tags{display:flex;flex-wrap:wrap;gap:.35rem;margin-top:auto}
.rec-tag{font-size:.62rem;font-weight:700;padding:.22rem .6rem;border-radius:var(--rx);border:1px solid}
.rec-tag.transition{color:var(--accent2);border-color:color-mix(in srgb,var(--accent2) 30%,transparent);background:color-mix(in srgb,var(--accent2) 8%,transparent)}
.rec-tag.emploi{color:var(--accent3);border-color:color-mix(in srgb,var(--accent3) 30%,transparent);background:color-mix(in srgb,var(--accent3) 8%,transparent)}
.rec-tag.warn{color:var(--gold);border-color:color-mix(in srgb,var(--gold) 30%,transparent);background:color-mix(in srgb,var(--gold) 8%,transparent)}
.rec-tag.confidence{display:inline-flex;align-items:center;gap:.35rem}
.rec-tag.confidence::before{content:'';width:7px;height:7px;border-radius:50%;background:currentColor;box-shadow:0 0 0 3px color-mix(in srgb,currentColor 13%,transparent)}
.rec-tag.confidence.high{color:var(--accent3);border-color:color-mix(in srgb,var(--accent3) 30%,transparent);background:color-mix(in srgb,var(--accent3) 8%,transparent)}
.rec-tag.confidence.medium{color:var(--gold);border-color:color-mix(in srgb,var(--gold) 32%,transparent);background:color-mix(in srgb,var(--gold) 10%,transparent)}
.rec-tag.confidence.low{color:#ef4444;border-color:color-mix(in srgb,#ef4444 32%,transparent);background:color-mix(in srgb,#ef4444 8%,transparent)}

/* Explication */
.rec-expliq{font-size:.74rem;color:var(--ink60);line-height:1.5;margin-bottom:.85rem;font-style:italic}

/* Gap Analysis */
.rec-gap{background:var(--ink06);border:1px solid var(--glass-border);border-radius:var(--rl);padding:1.5rem 1.75rem;margin-bottom:2rem}
.rec-gap-title{font-size:.68rem;font-weight:700;letter-spacing:.09em;text-transform:uppercase;color:var(--ink30);margin-bottom:1rem}
.rec-gap-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:.85rem;margin-bottom:1.1rem}
.rec-gap-stat{text-align:center;padding:.9rem;background:var(--paper);border:1px solid var(--glass-border);border-radius:var(--r)}
.rec-gap-stat-num{font-family:var(--font-serif);font-size:1.6rem;font-weight:600;line-height:1}
.rec-gap-stat-lbl{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--ink30);margin-top:.25rem}
.rec-gap-axes{display:flex;flex-direction:column;gap:.45rem}
.rec-gap-axe{display:flex;align-items:center;gap:.5rem;font-size:.8rem;color:var(--ink60)}
.rec-gap-axe::before{content:'';width:6px;height:6px;border-radius:50%;background:var(--accent);flex-shrink:0}

/* Actions */
.rec-actions{display:flex;gap:.75rem;flex-wrap:wrap;margin-top:2rem}
.btn-fill{display:inline-flex;align-items:center;gap:.5rem;padding:.85rem 2rem;font-family:var(--font-main);font-size:.9rem;font-weight:600;color:#fff;background:linear-gradient(135deg,var(--accent),var(--accent2));border:none;border-radius:var(--r);cursor:pointer;text-decoration:none;box-shadow:0 4px 18px color-mix(in srgb,var(--accent) 30%,transparent);transition:all .25s}
.btn-fill:hover{transform:translateY(-2px);box-shadow:0 8px 28px color-mix(in srgb,var(--accent) 40%,transparent)}
.btn-ghost{display:inline-flex;align-items:center;gap:.5rem;padding:.75rem 1.4rem;font-family:var(--font-main);font-size:.84rem;font-weight:600;color:var(--ink60);background:transparent;border:1px solid var(--glass-border);border-radius:var(--r);cursor:pointer;text-decoration:none;transition:all .2s}
.btn-ghost:hover{color:var(--ink);border-color:var(--ink30);background:var(--ink06)}
.rec-stats-bar{display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:2rem}
.rec-stat{display:flex;align-items:center;gap:.4rem;padding:.38rem .8rem;border-radius:var(--rx);background:var(--ink06);border:1px solid var(--glass-border);font-size:.74rem;font-weight:600;color:var(--ink60)}
.rec-stat strong{color:var(--ink)}
/* Feedback buttons */
.feedback-btn {
    border: 1px solid var(--glass-border);
    background: var(--ink06);
    border-radius: var(--rx);
    padding: 0.3rem 0.6rem;
    cursor: pointer;
    font-size: 0.75rem;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--ink);
}
.feedback-btn:hover {
    background: var(--ink10);
    border-color: var(--ink30);
    transform: scale(1.05);
}
.feedback-btn.active.like-btn {
    background: color-mix(in srgb, var(--accent3) 15%, transparent);
    border-color: var(--accent3);
    color: var(--accent3);
    box-shadow: 0 0 10px color-mix(in srgb, var(--accent3) 20%, transparent);
}
.feedback-btn.active.dislike-btn {
    background: color-mix(in srgb, #ef4444 15%, transparent);
    border-color: #ef4444;
    color: #ef4444;
    box-shadow: 0 0 10px color-mix(in srgb, #ef4444 20%, transparent);
}
.feedback-stars {
    display: inline-flex;
    gap: 0.15rem;
}
.feedback-star {
    cursor: pointer;
    font-size: 0.9rem;
    color: var(--ink30);
    transition: color 0.15s ease;
}
.feedback-star:hover, .feedback-star.active {
    color: var(--gold);
}

/* Accordion styles */
.career-acc {
    margin-top: 1rem;
    border: 1px solid var(--glass-border);
    border-radius: var(--rx);
    overflow: hidden;
    background: var(--paper);
}
.career-acc-trigger {
    width: 100%;
    background: var(--ink06);
    border: none;
    padding: 0.65rem 0.85rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.74rem;
    font-weight: 700;
    color: var(--ink);
    cursor: pointer;
    transition: background 0.2s;
}
.career-acc-trigger:hover {
    background: var(--ink10);
}
.career-acc-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
}
.career-acc-inner {
    padding: 0.85rem;
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
    border-top: 1px solid var(--glass-border);
}
.career-domain-title {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--accent2);
    font-weight: 800;
    margin-bottom: 0.25rem;
}
.career-item {
    padding-bottom: 0.75rem;
    border-bottom: 1px dashed var(--glass-border);
}
.career-item:last-child {
    padding-bottom: 0;
    border-bottom: none;
}
.career-item-title {
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 0.2rem;
}
.career-item-desc {
    font-size: 0.74rem;
    color: var(--ink60);
    line-height: 1.4;
    margin-bottom: 0.4rem;
}
.career-meta-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
    margin-bottom: 0.4rem;
}
.career-meta-badge {
    font-size: 0.65rem;
    padding: 0.15rem 0.45rem;
    border-radius: 4px;
    font-weight: 600;
    background: var(--ink06);
    color: var(--ink60);
    border: 1px solid var(--glass-border);
}
.career-meta-badge.salary {
    color: var(--accent3);
    border-color: color-mix(in srgb, var(--accent3) 30%, transparent);
    background: color-mix(in srgb, var(--accent3) 6%, transparent);
}
.career-meta-badge.employability {
    color: var(--gold);
    border-color: color-mix(in srgb, var(--gold) 30%, transparent);
    background: color-mix(in srgb, var(--gold) 6%, transparent);
}
.career-skills-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
    margin-top: 0.3rem;
}
.career-skill-tag {
    font-size: 0.6rem;
    padding: 0.08rem 0.35rem;
    border-radius: 3px;
    background: var(--ink06);
    color: var(--ink40);
    font-weight: 600;
}

/* Tabs UI styling */
.rec-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 2rem;
    border-bottom: 1px solid var(--glass-border);
    padding-bottom: 0.5rem;
}
.rec-tab-btn {
    background: transparent;
    border: none;
    padding: 0.65rem 1.25rem;
    font-family: var(--font-main);
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--ink60);
    cursor: pointer;
    border-radius: var(--r);
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}
.rec-tab-btn:hover {
    color: var(--ink);
    background: var(--ink06);
}
.rec-tab-btn.active {
    color: var(--accent);
    background: color-mix(in srgb, var(--accent) 8%, transparent);
    font-weight: 700;
}
.rec-tab-count {
    font-size: 0.7rem;
    background: var(--ink10);
    color: var(--ink60);
    padding: 0.1rem 0.45rem;
    border-radius: 10px;
    font-weight: 700;
}
.rec-tab-btn.active .rec-tab-count {
    background: var(--accent);
    color: #fff;
}
.tab-panel {
    display: none;
    opacity: 0;
    transition: opacity 0.25s ease-in-out;
}
.tab-panel.active {
    display: block;
    opacity: 1;
}
</style>

<div class="rec">
    <p class="rec-eye">Moteur SIAEPI v8.0 · Ranking multi-objectif déterministe</p>
    <h1 class="rec-title">Vos filières <em>recommandées</em></h1>
    <p class="rec-sub">Système expert hybride multicritère optimisant vos forces cognitives GATB, votre adéquation RIASEC, vos performances académiques FG et les indicateurs du marché de l'emploi tunisien.</p>

    {{-- Stats rapides --}}
    <div class="rec-stats-bar">
        <div class="rec-stat">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            <strong>{{ count($recommendations['recommandations'] ?? []) }}</strong> filières analysées
        </div>
        <div class="rec-stat">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--accent2)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Score FG : <strong>{{ $scoreFg > 0 ? round($scoreFg, 1) : '—' }}</strong>
        </div>
        @if($codeHolland)
        <div class="rec-stat">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--accent3)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
            Code Holland : <strong>{{ $codeHolland }}</strong>
        </div>
        @endif
        <div class="rec-stat">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            <strong>{{ $recommendations['total_filieres_accessibles'] ?? 0 }}</strong> filières évaluées
        </div>
    </div>

    {{-- Diagnostic --}}
    @if(!empty($recommendations['diagnostic']['diagnostic']))
    <div class="rec-diag">
        <div class="rec-diag-ic">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
        </div>
        <div class="rec-diag-body">
            <p>{!! nl2br(e($recommendations['diagnostic']['diagnostic'])) !!}</p>
            @if(isset($recommendations['diagnostic']['niveau_fg']))
            <p style="margin-top:.5rem;font-size:.8rem;color:var(--accent)">
                Niveau académique : <strong>{{ $recommendations['diagnostic']['niveau_fg'] }}</strong>
            </p>
            @endif
        </div>
    </div>
    @endif

    {{-- Error state --}}
    @if(isset($recommendations['error']))
    <div style="padding:2rem;text-align:center;background:color-mix(in srgb,#ef4444 8%,transparent);border:1px solid color-mix(in srgb,#ef4444 22%,transparent);border-radius:var(--rl);color:#ef4444;margin-bottom:2rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto .75rem;display:block"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <p style="font-weight:600">{{ $recommendations['error'] }}</p>
        <p style="font-size:.85rem;margin-top:.4rem">Vérifiez que vous avez complété votre profil académique et le test psychométrique.</p>
    </div>
    @endif

    {{-- Onglets de Navigation --}}
    <div class="rec-tabs">
        <button type="button" class="rec-tab-btn active" onclick="switchRecTab('ambitieuses')">
            🚀 Choix Ambitieux
            <span class="rec-tab-count">{{ count($recommendations['ambitieuses'] ?? []) }}</span>
        </button>
        <button type="button" class="rec-tab-btn" onclick="switchRecTab('optimal')">
            🎯 Top Recommandations
            <span class="rec-tab-count">{{ count($recommendations['recommandations'] ?? []) }}</span>
        </button>
        <button type="button" class="rec-tab-btn" onclick="switchRecTab('accessible')">
            ⚡ Opportunités Accessibles
            <span class="rec-tab-count">{{ count($recommendations['accessibles'] ?? []) }}</span>
        </button>
        <button type="button" class="rec-tab-btn" onclick="switchRecTab('securite')">
            🛡️ Filières de repli si besoin
            <span class="rec-tab-count">{{ count($recommendations['securite'] ?? []) }}</span>
        </button>
    </div>

    {{-- Onglet 1 : Choix Ambitieux --}}
    <div id="panel-ambitieuses" class="tab-panel active">
        <div class="rec-grid">
            @forelse($recommendations['ambitieuses'] ?? [] as $f)
                @include('recommendations.card', ['f' => $f, 'loop' => $loop])
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:3rem;background:var(--ink06);border:1px solid var(--glass-border);border-radius:var(--rl);color:var(--ink30);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 1rem;display:block;opacity:.3"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <p style="font-size:.9rem;font-weight:600">Aucune filière ambitieuse (sélectivité légèrement supérieure à votre FG) dans cette catégorie.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Onglet 2 : Top Recommandations --}}
    <div id="panel-optimal" class="tab-panel">
        <div class="rec-grid">
            @forelse($recommendations['recommandations'] ?? [] as $f)
                @include('recommendations.card', ['f' => $f, 'loop' => $loop])
            @empty
                @if(!isset($recommendations['error']))
                <div style="grid-column:1/-1;text-align:center;padding:3rem;background:var(--ink06);border:1px solid var(--glass-border);border-radius:var(--rl);color:var(--ink30);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 1rem;display:block;opacity:.3"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <p style="font-size:.9rem;font-weight:600">Aucune filière recommandée dans cette catégorie.</p>
                </div>
                @endif
            @endforelse
        </div>
    </div>

    {{-- Onglet 3 : Opportunités Accessibles --}}
    <div id="panel-accessible" class="tab-panel">
        <div class="rec-grid">
            @forelse($recommendations['accessibles'] ?? [] as $f)
                @include('recommendations.card', ['f' => $f, 'loop' => $loop])
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:3rem;background:var(--ink06);border:1px solid var(--glass-border);border-radius:var(--rl);color:var(--ink30);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 1rem;display:block;opacity:.3"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <p style="font-size:.9rem;font-weight:600">Aucune opportunité accessible détectée.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Onglet 4 : Filières de repli si besoin --}}
    <div id="panel-securite" class="tab-panel">
        <div class="rec-grid">
            @forelse($recommendations['securite'] ?? [] as $f)
                @include('recommendations.card', ['f' => $f, 'loop' => $loop])
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:3rem;background:var(--ink06);border:1px solid var(--glass-border);border-radius:var(--rl);color:var(--ink30);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 1rem;display:block;opacity:.3"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <p style="font-size:.9rem;font-weight:600">Aucune filière de repli requise.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Gap Analysis --}}
    @if(!empty($gapAnalysis) && !empty($gapAnalysis['filiere_cible']))
    <div class="rec-gap">
        <p class="rec-gap-title">Gap Analysis — Filière cible vs votre profil</p>
        <div class="rec-gap-grid">
            <div class="rec-gap-stat">
                <div class="rec-gap-stat-num" style="color:var(--accent)">{{ $gapAnalysis['score_fg_etudiant'] ?? '—' }}</div>
                <div class="rec-gap-stat-lbl">Votre Score FG</div>
            </div>
            <div class="rec-gap-stat">
                <div class="rec-gap-stat-num" style="color:var(--accent2)">{{ $gapAnalysis['sdo_filiere'] > 0 ? round($gapAnalysis['sdo_filiere'], 1) : 'N/A' }}</div>
                <div class="rec-gap-stat-lbl">SDO Filière Top-1</div>
            </div>
            <div class="rec-gap-stat">
                @php $ecart = $gapAnalysis['ecart_fg'] ?? 0; $ec = $ecart <= 0 ? 'var(--accent3)' : ($ecart <= 15 ? 'var(--gold)' : '#ef4444'); @endphp
                <div class="rec-gap-stat-num" style="color:{{ $ec }}">{{ $ecart <= 0 ? '✓ (+'.round(abs($ecart), 1).')' : '+'.round(abs($ecart), 1) }}</div>
                <div class="rec-gap-stat-lbl">Écart FG</div>
            </div>
            <div class="rec-gap-stat">
                @php $statut = $gapAnalysis['statut'] ?? ''; $sc = match($statut){'Accès Sécurisé','Accessible'=>'var(--accent3)','Effort requis'=>'var(--gold)',default=>'#ef4444'}; @endphp
                <div class="rec-gap-stat-num" style="font-size:1rem;color:{{ $sc }}">{{ $statut }}</div>
                <div class="rec-gap-stat-lbl">Statut</div>
            </div>
        </div>
        @if(!empty($gapAnalysis['axes_amelioration']))
        <p style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink30);margin-bottom:.6rem">Axes d'amélioration</p>
        <div class="rec-gap-axes">
            @foreach($gapAnalysis['axes_amelioration'] as $axe)
            <div class="rec-gap-axe">{{ $axe }}</div>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    {{-- Actions --}}
    <div class="rec-actions">
        <a href="{{ route('riasec.results') }}" class="btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Mon profil RIASEC
        </a>
        <a href="{{ route('student.whatif.index') }}" class="btn-fill">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/><path d="M12 8v4l3 3"/></svg>
            Simulateur What-If
        </a>
        <form action="{{ route('riasec.initialize') }}" method="POST" style="margin:0">
            @csrf
            <input type="hidden" name="restart" value="1">
            <button type="submit" class="btn-ghost" onclick="return confirm('Refaire le test psychométrique ?')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                Refaire le test
            </button>
        </form>
    </div>

    <p style="font-size:.7rem;color:var(--ink30);margin-top:1.5rem;line-height:1.6">
        Recommandations générées par SIAEPI v8.0 · FinalScore unique · Pareto Fit/Market/Access · MMR déterministe · Données SDO 2023–2025
    </p>
</div>

<script>
// Switch between recommendation tabs dynamically
function switchRecTab(tabId) {
    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.remove('active');
    });
    document.querySelectorAll('.rec-tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    const activePanel = document.getElementById('panel-' + tabId);
    if (activePanel) {
        activePanel.classList.add('active');
        // Re-trigger progress bar animations inside the active panel
        const fills = activePanel.querySelectorAll('.rec-bar-fill');
        fills.forEach(f => {
            const target = f.style.width;
            f.style.width = '0%';
            setTimeout(() => { f.style.width = target; }, 100);
        });
    }

    const clickedBtn = Array.from(document.querySelectorAll('.rec-tab-btn')).find(btn => btn.getAttribute('onclick').includes(tabId));
    if (clickedBtn) {
        clickedBtn.classList.add('active');
    }
}

// Animate bars on load & handle default active tab if ambitious is empty
document.addEventListener('DOMContentLoaded', () => {
    const hasAmbitious = {{ count($recommendations['ambitieuses'] ?? []) }};
    if (hasAmbitious === 0) {
        switchRecTab('optimal');
    } else {
        // Initial animation for active tab
        const activePanel = document.querySelector('.tab-panel.active');
        if (activePanel) {
            const fills = activePanel.querySelectorAll('.rec-bar-fill');
            fills.forEach(f => {
                const target = f.style.width;
                f.style.width = '0%';
                setTimeout(() => { f.style.width = target; }, 200);
            });
        }
    }
});

// Submit binary relevance feedback
function submitFeedback(btn, filiereCode, isRelevant) {
    const container = btn.closest('.rec-feedback-container');
    const starsContainer = container.querySelector('.feedback-stars-container');
    
    // Toggle active classes
    container.querySelectorAll('.feedback-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    // Show stars rating if feedback is sent
    starsContainer.style.display = 'flex';
    
    // Send feedback via fetch API
    sendFeedbackRequest(filiereCode, isRelevant, isRelevant ? 5 : 1);
}

// Submit star rating feedback
function rateFeedback(starSpan, filiereCode, rating) {
    const starsContainer = starSpan.closest('.feedback-stars');
    const container = starSpan.closest('.rec-feedback-container');
    const isRelevant = container.querySelector('.like-btn').classList.contains('active');
    
    // Update star active states
    starsContainer.querySelectorAll('.feedback-star').forEach((star, idx) => {
        if (idx < rating) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
    
    sendFeedbackRequest(filiereCode, isRelevant, rating);
}

function sendFeedbackRequest(filiereCode, isRelevant, rating) {
    fetch("{{ route('student.recommendations.feedback') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            filiere_code: filiereCode,
            is_relevant: isRelevant ? 1 : 0,
            rating: rating
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            console.log("Feedback enregistré !", data);
        } else {
            console.error("Erreur lors de l'enregistrement", data);
        }
    })
    .catch(err => {
        console.error("Erreur réseau :", err);
    });
}

function toggleCareerAccordion(btn) {
    const content = btn.nextElementSibling;
    const chevron = btn.querySelector('.chevron');
    
    if (content.style.maxHeight && content.style.maxHeight !== '0px') {
        content.style.maxHeight = '0px';
        chevron.style.transform = 'rotate(0deg)';
    } else {
        content.style.maxHeight = content.scrollHeight + 'px';
        chevron.style.transform = 'rotate(180deg)';
        
        // Log interaction (view)
        const container = btn.closest('.rec-card').querySelector('.rec-feedback-container');
        const codeFiliere = container.dataset.filiere;
        logStudentInteraction(codeFiliere, 'view');
    }
}

function logStudentInteraction(filiereCode, action) {
    fetch("{{ route('student.interaction') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            filiere_code: filiereCode,
            action: action
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            console.log("Interaction enregistrée !", data.interaction);
        }
    })
    .catch(err => {
        console.error("Erreur réseau interaction :", err);
    });
}
</script>
@endsection
