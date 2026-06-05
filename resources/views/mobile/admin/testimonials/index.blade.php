@extends('layouts.admin')

@section('title', "Gestion des Témoignages")

@section('content')
<style>
    .admin-testi-mob {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    /* Horizontal swipeable stats for admin */
    .stats-scroll-mob {
        display: flex;
        gap: 0.75rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
    }
    .stats-scroll-mob::-webkit-scrollbar {
        display: none;
    }
    .stat-card-mob {
        min-width: 150px;
        flex: 1;
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 4px;
        scroll-snap-align: start;
        box-shadow: var(--shadow-card);
    }
    .stat-card-mob b {
        font-family: var(--font-serif);
        font-size: 1.8rem;
        color: var(--ink);
    }
    .stat-card-mob span {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ink30);
    }

    /* Filters card */
    .filter-card-mob {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 1rem;
        box-shadow: var(--shadow-card);
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .filter-group-mob {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .filter-group-mob label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ink30);
    }
    .filter-select-mob {
        width: 100%;
        min-height: 40px;
        padding: 0.5rem;
        background: var(--cream);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        font-size: 0.9rem;
        color: var(--ink);
    }

    /* Testimonials stack */
    .testi-stack-mob {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .testi-card-mob {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 1.25rem;
        box-shadow: var(--shadow-card);
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }
    .testi-card-mob.pending {
        border-color: var(--accent);
    }
    .user-info-mob {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .avatar-mob {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #fff;
        overflow: hidden;
        flex-shrink: 0;
    }
    .avatar-mob img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .user-details-mob h4 {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--ink);
    }
    .user-details-mob p {
        font-size: 0.72rem;
        color: var(--ink30);
    }

    /* Badges */
    .badge-pill-mob {
        display: inline-flex;
        padding: 2px 8px;
        border-radius: var(--rx);
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        width: fit-content;
    }
    .badge-pill-mob.orange { background: color-mix(in srgb, var(--accent) 10%, transparent); color: var(--accent); }
    .badge-pill-mob.green  { background: color-mix(in srgb, var(--accent3) 10%, transparent); color: var(--accent3); }
    .badge-pill-mob.red    { background: color-mix(in srgb, var(--red) 10%, transparent); color: var(--red); }
    .badge-pill-mob.violet { background: color-mix(in srgb, var(--accent2) 10%, transparent); color: var(--accent2); }

    /* Action buttons block */
    .actions-block-mob {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        border-top: 1px solid var(--glass-border);
        padding-top: 0.85rem;
        margin-top: 0.25rem;
    }
    .btn-action-mob {
        min-height: 38px;
        border-radius: var(--r);
        border: 1px solid var(--glass-border);
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
        background: var(--cream);
        color: var(--ink60);
    }
    .btn-action-mob.approve {
        background: var(--accent3);
        color: #fff;
        border-color: var(--accent3);
        grid-column: span 2;
    }
    .btn-action-mob.reject {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
    }
    .btn-action-mob.archive {
        background: var(--accent2);
        color: #fff;
        border-color: var(--accent2);
    }
    .btn-action-mob.delete {
        background: var(--red);
        color: #fff;
        border-color: var(--red);
    }
    .btn-action-mob.full-w {
        grid-column: span 2;
    }
</style>

<div class="admin-testi-mob">
    {{-- Header --}}
    <div>
        <h1 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 700; color: var(--ink); margin-bottom: 2px;">
            Modération Avis
        </h1>
        <p style="font-size: 0.8rem; color: var(--ink60);">Gestion et validation des retours d'expérience</p>
    </div>

    {{-- KPIs Swiper --}}
    <div class="stats-scroll-mob">
        <div class="stat-card-mob">
            <span>Soumis</span>
            <b>{{ $totalCount }}</b>
        </div>
        <div class="stat-card-mob" style="{{ $pendingCount > 0 ? 'border-bottom: 3px solid var(--accent);' : '' }}">
            <span style="{{ $pendingCount > 0 ? 'color: var(--accent);' : '' }}">En attente</span>
            <b style="{{ $pendingCount > 0 ? 'color: var(--accent);' : '' }}">{{ $pendingCount }}</b>
        </div>
        <div class="stat-card-mob">
            <span style="color: var(--accent3);">Approuvés</span>
            <b style="color: var(--accent3);">{{ $approvedCount }}</b>
        </div>
        <div class="stat-card-mob">
            <span style="color: var(--gold);">Note Moyenne</span>
            <b style="color: var(--gold);">{{ number_format($averageRating, 1) }}<small style="font-size: 0.9rem;">/5</small></b>
        </div>
    </div>

    @if(session('success'))
        <div style="background: color-mix(in srgb, var(--success) 8%, var(--paper)); border: 1px solid color-mix(in srgb, var(--success) 20%, transparent); color: var(--success); padding: 0.75rem; border-radius: var(--r); font-size: 0.8rem; font-weight: 600; text-align: center;">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Filters Card --}}
    <section class="filter-card-mob">
        <form method="GET" action="{{ route('admin.testimonials.index') }}" style="display:flex; flex-direction:column; gap:0.75rem;">
            <div class="filter-group-mob">
                <label for="status">Statut</label>
                <select name="status" id="status" class="filter-select-mob" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Validés</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejetés</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archivés</option>
                </select>
            </div>

            <div class="filter-group-mob">
                <label for="role">Rôle auteur</label>
                <select name="role" id="role" class="filter-select-mob" onchange="this.form.submit()">
                    <option value="">Tous les rôles</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Étudiant</option>
                    <option value="counselor" {{ request('role') === 'counselor' ? 'selected' : '' }}>Conseiller</option>
                </select>
            </div>

            @if(request('status') || request('role'))
                <a href="{{ route('admin.testimonials.index') }}" class="btn-action-mob" style="min-height: 34px;">Réinitialiser les filtres</a>
            @endif
        </form>
    </section>

    {{-- Testimonials stack --}}
    <section class="testi-stack-mob">
        @if($testimonials->count() > 0)
            @foreach($testimonials as $t)
                <div class="testi-card-mob {{ $t->status === 'pending' ? 'pending' : '' }}">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:0.5rem;">
                        <div class="user-info-mob">
                            @if($t->user?->avatar)
                                <div class="avatar-mob">
                                    <img src="{{ asset('storage/' . $t->user->avatar) }}" alt="">
                                </div>
                            @else
                                <div class="avatar-mob" style="background: {{ $t->user?->role === 'counselor' ? 'var(--accent2)' : 'var(--accent)' }};">
                                    {{ strtoupper(substr($t->user?->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div class="user-details-mob">
                                <h4>{{ $t->user?->name ?? 'Auteur supprimé' }}</h4>
                                <p>{{ $t->user?->email }}</p>
                            </div>
                        </div>
                        
                        @if($t->user?->role === 'counselor')
                            <span class="badge-pill-mob violet">Conseiller</span>
                        @else
                            <span class="badge-pill-mob orange">Étudiant</span>
                        @endif
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div style="color: var(--gold); font-size: 0.85rem;">
                            {{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}
                        </div>
                        @if($t->status === 'approved')
                            <span class="badge-pill-mob green">Validé</span>
                        @elseif($t->status === 'pending')
                            <span class="badge-pill-mob orange">En attente</span>
                        @elseif($t->status === 'rejected')
                            <span class="badge-pill-mob red">Rejeté</span>
                        @else
                            <span class="badge-pill-mob violet">Archivé</span>
                        @endif
                    </div>

                    <div style="font-size: 0.85rem; color: var(--ink60); line-height: 1.5; word-break: break-word; font-style: italic;">
                        « {{ $t->comment }} »
                    </div>

                    <div class="actions-block-mob">
                        {{-- Approve --}}
                        @if($t->status !== 'approved')
                            <form action="{{ route('admin.testimonials.approve', $t) }}" method="POST" style="grid-column: span 2; display: flex;">
                                @csrf
                                <button type="submit" class="btn-action-mob approve" style="width: 100%;">Valider l'avis</button>
                            </form>
                        @endif

                        {{-- Reject --}}
                        @if($t->status === 'pending')
                            <form action="{{ route('admin.testimonials.reject', $t) }}" method="POST" style="display: flex;">
                                @csrf
                                <button type="submit" class="btn-action-mob reject" style="width: 100%;">Rejeter</button>
                            </form>
                        @endif

                        {{-- Archive --}}
                        @if($t->status === 'approved' || $t->status === 'rejected')
                            <form action="{{ route('admin.testimonials.archive', $t) }}" method="POST" style="display: flex; {{ $t->status === 'approved' ? 'grid-column: span 2;' : '' }}">
                                @csrf
                                <button type="submit" class="btn-action-mob archive" style="width: 100%;">Archiver</button>
                            </form>
                        @endif

                        {{-- Delete --}}
                        <form action="{{ route('admin.testimonials.delete', $t) }}" method="POST" style="display: flex; {{ $t->status !== 'pending' && $t->status !== 'approved' && $t->status !== 'rejected' ? 'grid-column: span 2;' : '' }}" onsubmit="return confirm('Supprimer définitivement ce témoignage ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action-mob delete" style="width: 100%;">Supprimer</button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div style="margin-top: 1rem;">
                {{ $testimonials->appends(request()->query())->links() }}
            </div>
        @else
            <div style="background:var(--paper); border: 1px dashed var(--glass-border); padding:3rem 1.5rem; text-align:center; border-radius: var(--rl); color: var(--ink30);">
                <i class="bi bi-chat-left-dots" style="font-size: 2.5rem; opacity:0.3; display:block; margin-bottom: 0.5rem;"></i>
                <h4 style="font-weight:700; color:var(--ink); font-size: 1rem;">Aucun témoignage</h4>
                <p style="font-size:0.75rem; margin-top:2px;">Aucun avis ne correspond à la recherche.</p>
            </div>
        @endif
    </section>
</div>
@endsection
