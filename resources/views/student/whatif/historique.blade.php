@extends('layouts.student')
@section('title', 'Historique des Simulations — CapAvenir')

@section('content')
@include('student.whatif._styles')

<style>
.hist-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-top: 2rem;
}
.hist-card {
    background: var(--paper);
    border: 1px solid var(--ink10);
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.02);
}
.hist-card:hover {
    border-color: var(--ink30);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.04);
}
.hist-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid var(--ink10);
}
.hist-title-group {
    flex: 1;
}
.hist-card-title {
    font-family: 'Fraunces', serif;
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--ink);
}
.hist-card-date {
    font-size: 0.78rem;
    color: var(--ink30);
    margin-top: 0.25rem;
}
.hist-score-badge {
    text-align: right;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}
.hist-score-val {
    font-family: 'Fraunces', serif;
    font-size: 2rem;
    font-weight: 600;
    color: var(--accent);
    line-height: 1;
}
.hist-badge-label {
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--ink30);
    margin-top: 0.25rem;
}
.hist-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    padding-top: 1.25rem;
}
@media(max-width: 768px) {
    .hist-details {
        grid-template-columns: 1fr;
    }
}
.hist-section-title {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--ink30);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.hist-notes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 0.6rem;
}
.hist-note-badge {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0.75rem;
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: 8px;
    font-size: 0.8rem;
}
.hist-note-name {
    color: var(--ink60);
    font-weight: 500;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.hist-note-val {
    font-weight: 700;
    color: var(--ink);
    font-family: 'Fraunces', serif;
}
.hist-formations-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.hist-formation-item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.6rem 0.85rem;
    background: var(--cream);
    border: 1px solid var(--ink10);
    border-radius: 10px;
}
.hist-formation-icon {
    font-size: 1rem;
    width: 28px;
    height: 28px;
    background: color-mix(in srgb, var(--accent) 10%, transparent);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.hist-formation-info {
    flex: 1;
    min-width: 0;
}
.hist-formation-name {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--ink);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.hist-formation-univ {
    font-size: 0.7rem;
    color: var(--ink60);
}
.hist-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.25rem;
    padding-top: 1rem;
    border-top: 1px solid var(--ink06);
}
.hist-delete-btn {
    background: none;
    border: none;
    color: #ef4444;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    transition: all 0.2s;
}
.hist-delete-btn:hover {
    background: color-mix(in srgb, #ef4444 8%, transparent);
}
.pagination-wrap {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}
.pagination-wrap nav {
    width: 100%;
}
</style>

<div class="fs">
    {{-- Hero --}}
    <section class="fs-hero" style="margin-bottom: 1.5rem;">
        <div class="fs-hero-bg">History</div>
        <div class="fs-hero-inner">
            <div class="fs-eyebrow">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.9rem;height:.9rem"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Historique des simulations
            </div>
            <h1 class="fs-title">Tes <em>scénarios</em> enregistrés</h1>
            <p class="fs-sub">Consulte l'historique complet de tes simulations de Formule Globale (FG) et compare les filières accessibles.</p>
        </div>
    </section>

    {{-- Navigation back --}}
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('student.whatif.index') }}" class="btn-ghost" style="text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem; font-size:0.85rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Retour au Simulateur What-If
        </a>
    </div>

    {{-- Sessions Status --}}
    @if(session('success'))
    <div style="background:color-mix(in srgb,var(--accent3) 8%,transparent);border:1px solid color-mix(in srgb,var(--accent3) 22%,transparent);color:var(--accent3);border-radius:var(--r);padding:.75rem 1rem;margin-bottom:1.5rem;font-size:.83rem;font-weight: 500;">
        {!! get_pro_icon('✅') !!} {{ session('success') }}
    </div>
    @endif

    {{-- Content --}}
    @if($historique->isEmpty())
        <div style="padding: 5rem 2rem; text-align: center; background: var(--paper); border: 1px dashed var(--ink10); border-radius: 20px;">
            <div style="font-size: 3.5rem; margin-bottom: 1rem; opacity: 0.5; color: var(--accent);">{!! get_pro_icon('🔮') !!}</div>
            <h3 style="font-family:'Fraunces', serif; font-size: 1.4rem; color: var(--ink); margin-bottom: 0.5rem;">Aucune simulation trouvée</h3>
            <p style="font-size: 0.9rem; color: var(--ink60); max-width: 400px; margin: 0 auto 1.5rem; line-height: 1.6;">
                Vous n'avez pas encore enregistré de simulation. Allez sur le simulateur pour tester vos notes et sauvegarder vos résultats.
            </p>
            <a href="{{ route('student.whatif.index') }}" class="fs-btn" style="max-width: 260px; margin: 0 auto; text-decoration: none;">
                Lancer une simulation
            </a>
        </div>
    @else
        <div class="hist-list">
            @foreach($historique as $h)
            <div class="hist-card">
                <div class="hist-header">
                    <div class="hist-title-group">
                        <h3 class="hist-card-title">{{ $h->label_ou_date }}</h3>
                        <div class="hist-card-date">
                            Bac {{ $h->section_bac }} · Moyenne Générale: <strong>{{ number_format($h->moyenne_generale, 2) }}</strong> · Effectué le {{ $h->created_at->format('d/m/Y à H:i') }}
                        </div>
                    </div>
                    <div class="hist-score-badge">
                        <span class="hist-score-val">{{ number_format($h->score_fg, 2) }}</span>
                        @php
                            $badgeClass = match($h->niveau_score) {
                                'excellent' => 'fs-badge-green',
                                'bon'       => 'fs-badge-blue',
                                'moyen'      => 'fs-badge-gold',
                                default     => 'fs-badge-red'
                            };
                        @endphp
                        <span class="fs-badge {{ $badgeClass }}">{{ $h->niveau_score }}</span>
                    </div>
                </div>

                <div class="hist-details">
                    {{-- Left col: Notes --}}
                    <div>
                        <h4 class="hist-section-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                            Notes saisies
                        </h4>
                        @if(!empty($h->notes_matieres))
                            <div class="hist-notes-grid">
                                @foreach($h->notes_matieres as $matiere => $note)
                                    @if($note !== null && $note !== '')
                                        <div class="hist-note-badge" title="{{ $matiere }}">
                                            <span class="hist-note-name">{{ $matiere }}</span>
                                            <span class="hist-note-val">{{ number_format((float)$note, 2) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p style="font-size:0.8rem; color:var(--ink30); font-style:italic;">Aucune note individuelle enregistrée.</p>
                        @endif
                    </div>

                    {{-- Right col: Formations --}}
                    <div>
                        <h4 class="hist-section-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                            Formations accessibles
                        </h4>
                        @if(!empty($h->formations_accessibles))
                            <div class="hist-formations-list">
                                @foreach($h->formations_accessibles as $f)
                                    <div class="hist-formation-item">
                                        <div class="hist-formation-icon">{!! get_pro_icon($f['icon'] ?? '🎯') !!}</div>
                                        <div class="hist-formation-info">
                                            <div class="hist-formation-name">{{ $f['nom'] }}</div>
                                            <div class="hist-formation-univ">{{ $f['etablissement'] ?? '' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p style="font-size:0.8rem; color:var(--ink30); font-style:italic;">Aucune formation enregistrée dans ce scénario.</p>
                        @endif
                    </div>
                </div>

                <div class="hist-footer">
                    <div></div>
                    <form action="{{ route('student.whatif.historique.destroy', $h->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette simulation de votre historique ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="hist-delete-btn">
                            {!! get_pro_icon('bi bi-trash') !!} Supprimer la simulation
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pagination-wrap">
            {{ $historique->links() }}
        </div>
    @endif
</div>
@endsection
