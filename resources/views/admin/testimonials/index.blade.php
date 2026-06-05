@extends('layouts.admin')

@section('title', "Gestion des Témoignages")

@section('content')
<style>
    .admin-testimonials {
        display: flex;
        flex-direction: column;
        gap: 2.5rem;
        font-family: var(--font-main);
        color: var(--ink);
    }

    /* KPI Grid */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    .kpi-card {
        background: var(--ink06);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 1.5rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        border-color: var(--glass-border-vivid);
        background: var(--ink10);
        box-shadow: var(--shadow-card);
    }
    .kpi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .kpi-title {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--ink60);
    }
    .kpi-icon-wrapper {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: color-mix(in srgb, var(--accent) 8%, transparent);
        color: var(--accent);
    }
    .kpi-value {
        font-family: var(--font-serif);
        font-size: 2.2rem;
        font-weight: 400;
        line-height: 1.1;
        color: var(--ink);
    }
    .kpi-sub {
        font-size: 0.75rem;
        color: var(--ink30);
        margin-top: 0.35rem;
        font-weight: 500;
    }

    /* Section Layout */
    .dashboard-section {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 2rem;
        box-shadow: var(--shadow-card);
        position: relative;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--glass-border);
        padding-bottom: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .section-title-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .section-title {
        font-family: var(--font-serif);
        font-size: 1.35rem;
        font-weight: 400;
        font-style: italic;
        color: var(--ink);
    }
    .section-icon {
        color: var(--accent);
        flex-shrink: 0;
    }

    /* Table Design */
    .custom-table-wrapper {
        overflow-x: auto;
    }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .custom-table th {
        padding: 0.85rem 1.25rem;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--ink30);
        border-bottom: 2px solid var(--glass-border);
    }
    .custom-table td {
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid var(--glass-border);
        font-size: 0.875rem;
        color: var(--ink60);
        vertical-align: middle;
    }
    .custom-table tr:hover td {
        background: var(--ink06);
        color: var(--ink);
    }

    /* Flex helpers */
    .user-meta-info {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }
    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: var(--shadow-card);
    }
    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .user-avatar-text {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--accent);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    .user-name-label {
        font-weight: 600;
        color: var(--ink);
        line-height: 1.25;
    }
    .user-email-label {
        font-size: 0.75rem;
        color: var(--ink30);
    }

    /* Action buttons */
    .action-btn-group {
        display: flex;
        gap: 0.4rem;
        flex-wrap: wrap;
    }
    .btn-sm-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.45rem 0.85rem;
        border-radius: var(--r);
        font-size: 0.76rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        border: 1px solid var(--glass-border);
        background: var(--ink06);
        color: var(--ink60);
        transition: var(--transition);
    }
    .btn-sm-action:hover {
        background: var(--ink10);
        color: var(--ink);
        border-color: var(--ink30);
    }
    .btn-sm-action.approve {
        background: var(--accent3);
        color: #fff;
        border-color: var(--accent3);
    }
    .btn-sm-action.approve:hover {
        background: color-mix(in srgb, var(--accent3) 90%, #000);
        transform: translateY(-1px);
    }
    .btn-sm-action.reject {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
    }
    .btn-sm-action.reject:hover {
        background: color-mix(in srgb, var(--accent) 90%, #000);
        transform: translateY(-1px);
    }
    .btn-sm-action.archive {
        background: var(--accent2);
        color: #fff;
        border-color: var(--accent2);
    }
    .btn-sm-action.archive:hover {
        background: color-mix(in srgb, var(--accent2) 90%, #000);
        transform: translateY(-1px);
    }
    .btn-sm-action.delete {
        background: #ef4444;
        color: #fff;
        border-color: #ef4444;
    }
    .btn-sm-action.delete:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }

    /* Badge styles */
    .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.65rem;
        border-radius: var(--rx);
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .badge-pill-orange {
        background: color-mix(in srgb, var(--accent) 10%, transparent);
        color: var(--accent);
        border: 1px solid color-mix(in srgb, var(--accent) 25%, transparent);
    }
    .badge-pill-green {
        background: color-mix(in srgb, var(--accent3) 10%, transparent);
        color: var(--accent3);
        border: 1px solid color-mix(in srgb, var(--accent3) 25%, transparent);
    }
    .badge-pill-red {
        background: color-mix(in srgb, #ef4444 10%, transparent);
        color: #ef4444;
        border: 1px solid color-mix(in srgb, #ef4444 25%, transparent);
    }
    .badge-pill-violet {
        background: color-mix(in srgb, var(--accent2) 10%, transparent);
        color: var(--accent2);
        border: 1px solid color-mix(in srgb, var(--accent2) 25%, transparent);
    }

    /* Filters bar */
    .filters-bar {
        background: var(--ink06);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 1rem 1.5rem;
        display: flex;
        gap: 1.5rem;
        align-items: center;
        flex-wrap: wrap;
    }
    .filters-bar label {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--ink60);
    }
    .filters-bar select {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        padding: 0.45rem 1rem;
        font-family: inherit;
        font-size: 0.85rem;
        color: var(--ink);
    }

    /* Empty state */
    .empty-state-card {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--ink30);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }
</style>

<div class="admin-testimonials">
    {{-- ═══ TOP KPIS ═══ --}}
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Total Soumis</span>
                <div class="kpi-icon-wrapper">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value">{{ $totalCount }}</div>
            <p class="kpi-sub">Total des avis rédigés</p>
        </div>

        <div class="kpi-card" style="border-bottom: 3px solid {{ $pendingCount > 0 ? 'var(--accent)' : 'var(--glass-border)' }};">
            <div class="kpi-header">
                <span class="kpi-title">En Attente</span>
                <div class="kpi-icon-wrapper" style="background: color-mix(in srgb, var(--accent) 8%, transparent); color: var(--accent);">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" style="color: {{ $pendingCount > 0 ? 'var(--accent)' : 'var(--ink)' }};">{{ $pendingCount }}</div>
            <p class="kpi-sub">Modérations requises</p>
        </div>

        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Approuvés</span>
                <div class="kpi-icon-wrapper" style="background: color-mix(in srgb, var(--accent3) 8%, transparent); color: var(--accent3);">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" style="color: var(--accent3);">{{ $approvedCount }}</div>
            <p class="kpi-sub">Visibles publiquement</p>
        </div>

        <div class="kpi-card">
            <div class="kpi-header">
                <span class="kpi-title">Note Moyenne</span>
                <div class="kpi-icon-wrapper" style="background: color-mix(in srgb, var(--gold) 8%, transparent); color: var(--gold);">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.969 0 1.371 1.24.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.18 0l-3.97 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h4.906a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
            </div>
            <div class="kpi-value" style="color: var(--gold);">{{ number_format($averageRating, 1) }}<small style="font-size: 1rem;">/5</small></div>
            <p class="kpi-sub">Moyenne des avis validés</p>
        </div>
    </div>

    {{-- ═══ NOTIFICATIONS ═══ --}}
    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.25); color: #10b981; padding: 1rem 1.25rem; border-radius: var(--r); font-weight: 500; font-size: 0.9rem;">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- ═══ SECTION : TAB & FILTERS ═══ --}}
    <div class="dashboard-section">
        <div class="section-header">
            <div class="section-title-wrapper">
                <svg class="section-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
                <h2 class="section-title">Modération des avis</h2>
            </div>
        </div>

        {{-- Filters form --}}
        <form method="GET" action="{{ route('admin.testimonials.index') }}" class="filters-bar" style="margin-bottom: 2rem;">
            <div>
                <label for="status" style="margin-right: 0.5rem;">Statut :</label>
                <select name="status" id="status" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Validés</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejetés</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archivés</option>
                </select>
            </div>

            <div>
                <label for="role" style="margin-right: 0.5rem;">Rôle :</label>
                <select name="role" id="role" onchange="this.form.submit()">
                    <option value="">Tous les rôles</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Étudiant</option>
                    <option value="counselor" {{ request('role') === 'counselor' ? 'selected' : '' }}>Conseiller</option>
                </select>
            </div>

            @if(request('status') || request('role'))
                <a href="{{ route('admin.testimonials.index') }}" class="btn-sm-action" style="padding: 0.38rem 0.8rem; font-size: 0.8rem;">Réinitialiser</a>
            @endif
        </form>

        {{-- Testimonials Table --}}
        @if($testimonials->count() > 0)
            <div class="custom-table-wrapper">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Auteur</th>
                            <th>Rôle</th>
                            <th>Note</th>
                            <th style="width: 40%;">Témoignage</th>
                            <th>Statut</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($testimonials as $t)
                            <tr>
                                <td>
                                    <div class="user-meta-info">
                                        @if($t->user?->avatar)
                                            <div class="user-avatar">
                                                <img src="{{ asset('storage/' . $t->user->avatar) }}" alt="">
                                            </div>
                                        @else
                                            <div class="user-avatar-text" style="background: {{ $t->user?->role === 'counselor' ? 'var(--accent2)' : 'var(--accent)' }};">
                                                {{ strtoupper(substr($t->user?->name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="user-name-label">{{ $t->user?->name ?? 'Utilisateur supprimé' }}</div>
                                            <div class="user-email-label">{{ $t->user?->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($t->user?->role === 'counselor')
                                        <span class="badge-pill badge-pill-violet">Conseiller</span>
                                    @else
                                        <span class="badge-pill badge-pill-orange">Étudiant</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="color: var(--gold); font-size: 0.85rem; letter-spacing: 1px; white-space: nowrap;">
                                        {{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}
                                    </div>
                                </td>
                                <td>
                                    <div style="line-height: 1.5; color: var(--ink60); font-size: 0.85rem; word-break: break-word;">
                                        {{ $t->comment }}
                                    </div>
                                </td>
                                <td>
                                    @if($t->status === 'approved')
                                        <span class="badge-pill badge-pill-green">Validé</span>
                                    @elseif($t->status === 'pending')
                                        <span class="badge-pill badge-pill-orange">En attente</span>
                                    @elseif($t->status === 'rejected')
                                        <span class="badge-pill badge-pill-red">Rejeté</span>
                                    @else
                                        <span class="badge-pill badge-pill-violet">Archivé</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div class="action-btn-group" style="justify-content: flex-end;">
                                        {{-- Approve action --}}
                                        @if($t->status !== 'approved')
                                            <form action="{{ route('admin.testimonials.approve', $t) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="submit" class="btn-sm-action approve" title="Approuver l'avis">
                                                    Approuver
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Reject action --}}
                                        @if($t->status === 'pending')
                                            <form action="{{ route('admin.testimonials.reject', $t) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="submit" class="btn-sm-action reject" title="Rejeter l'avis">
                                                    Rejeter
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Archive action --}}
                                        @if($t->status === 'approved' || $t->status === 'rejected')
                                            <form action="{{ route('admin.testimonials.archive', $t) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="submit" class="btn-sm-action archive" title="Archiver l'avis">
                                                    Archiver
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Delete action --}}
                                        <form action="{{ route('admin.testimonials.delete', $t) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce témoignage ? Cette action est irréversible.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sm-action delete" title="Supprimer l'avis">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1.5rem;">
                {{ $testimonials->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state-card">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <div style="font-weight: 700; font-size: 1.1rem; color: var(--ink); margin-top: 0.5rem;">Aucun témoignage trouvé</div>
                <p style="font-size: 0.85rem; color: var(--ink30); max-width: 350px; margin: 0 auto;">
                    Aucun avis ne correspond à vos filtres actuels ou la base de données est vide.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
