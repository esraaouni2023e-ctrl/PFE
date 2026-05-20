@extends('layouts.admin')

@section('title', 'Gestion Utilisateurs')

@section('content')
<style>
    /* ════════════════════════════════════════════
       CAPAVENIR USER MANAGEMENT PANEL
    ════════════════════════════════════════════ */
    .user-panel {
        display: flex;
        flex-direction: column;
        gap: 2.5rem;
        font-family: var(--font-main);
        color: var(--ink);
    }

    /* Filters and search row */
    .search-filter-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    .search-input-wrapper {
        position: relative;
        flex: 1;
        min-width: 300px;
    }
    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--ink30);
        pointer-events: none;
    }
    .search-input {
        width: 100%;
        background: var(--ink06);
        border: 1px solid var(--glass-border);
        border-radius: 14px;
        padding: 0.75rem 1rem 0.75rem 2.6rem;
        font-size: 0.875rem;
        color: var(--ink);
        font-family: var(--font-main);
        outline: none;
        transition: var(--transition);
    }
    .search-input:focus {
        border-color: var(--accent);
        background: var(--paper);
        box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 15%, transparent);
    }

    .filter-select {
        background: var(--ink06);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        padding: 0.65rem 2.5rem 0.65rem 1rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--ink60);
        outline: none;
        cursor: pointer;
        font-family: var(--font-main);
        transition: var(--transition);
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%231e293b' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem;
    }
    .filter-select:focus {
        border-color: var(--accent);
    }

    /* Table styles matching dashboard */
    .custom-table-wrapper {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        overflow: hidden;
        box-shadow: var(--shadow-card);
    }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .custom-table th {
        padding: 1.1rem 2rem;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: var(--ink30);
        border-bottom: 2px solid var(--glass-border);
    }
    .custom-table td {
        padding: 1.25rem 2rem;
        border-bottom: 1px solid var(--glass-border);
        font-size: 0.875rem;
        color: var(--ink60);
        vertical-align: middle;
    }
    .custom-table tr:hover td {
        background: var(--ink06);
        color: var(--ink);
    }

    /* User avatar gradients */
    .avatar-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        color: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        flex-shrink: 0;
        position: relative;
    }
    .avatar-student { background: linear-gradient(135deg, var(--accent) 0%, #ff8a43 100%); }
    .avatar-counselor { background: linear-gradient(135deg, var(--accent2) 0%, #1e60d1 100%); }
    .avatar-pending { background: linear-gradient(135deg, #d97706 0%, #fbbf24 100%); }
    .avatar-admin { background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%); }
    .avatar-blocked { background: linear-gradient(135deg, #64748b 0%, #94a3b8 100%); }

    /* Action button overrides */
    .btn-action-outline {
        width: 34px;
        height: 34px;
        padding: 0;
        border-radius: 10px;
        border: 1px solid var(--glass-border);
        background: var(--ink06);
        color: var(--ink60);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }
    .btn-action-outline:hover {
        background: var(--ink10);
        color: var(--ink);
        border-color: var(--ink30);
    }
    .btn-action-outline.danger:hover {
        background: color-mix(in srgb, var(--red) 10%, transparent);
        color: var(--red);
        border-color: color-mix(in srgb, var(--red) 30%, transparent);
    }

    /* Badges */
    .badge-role {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.28rem 0.75rem;
        border-radius: var(--rx);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .badge-role-student {
        background: color-mix(in srgb, var(--accent) 10%, transparent);
        color: var(--accent);
        border: 1px solid color-mix(in srgb, var(--accent) 25%, transparent);
    }
    .badge-role-counselor {
        background: color-mix(in srgb, var(--accent2) 10%, transparent);
        color: var(--accent2);
        border: 1px solid color-mix(in srgb, var(--accent2) 25%, transparent);
    }
    .badge-role-pending {
        background: rgba(217, 119, 6, 0.1);
        color: #d97706;
        border: 1px solid rgba(217, 119, 6, 0.25);
    }
    .badge-role-admin {
        background: rgba(124, 58, 237, 0.1);
        color: #7c3aed;
        border: 1px solid rgba(124, 58, 237, 0.25);
    }

    /* Blocked notification overlay */
    .blocked-badge {
        position: absolute;
        bottom: -4px;
        right: -4px;
        width: 18px;
        height: 18px;
        background: var(--red);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border: 2px solid var(--paper);
    }
</style>

<div class="user-panel">
    {{-- ═══ TOP OVERVIEW ═══ --}}
    <div class="glass-card" style="background: var(--ink06); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <h3 style="font-family: var(--font-serif); font-size: 1.4rem; font-weight: 400; font-style: italic; color: var(--ink);">Gestion des Utilisateurs</h3>
            <p style="font-size: 0.82rem; color: var(--ink60); margin-top: 0.3rem;">Supervisez les accès, gérez les rôles de la communauté CapAvenir et visualisez les statuts de validation.</p>
        </div>
        <div style="display: flex; gap: 2.5rem;">
            <div style="text-align: center;">
                <div style="font-family: var(--font-serif); font-size: 2rem; font-weight: 400; color: var(--ink); line-height: 1;">{{ $users->total() }}</div>
                <div style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: var(--accent); margin-top: 0.4rem;">Total Inscrits</div>
            </div>
            <div style="text-align: center;">
                <div style="font-family: var(--font-serif); font-size: 2rem; font-weight: 400; color: var(--ink); line-height: 1;">+{{ $newUsersToday ?? 0 }}</div>
                <div style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: var(--accent); margin-top: 0.4rem;">Dernières 24h</div>
            </div>
        </div>
    </div>

    {{-- ═══ ACTIONS & FILTERS ═══ --}}
    <div class="search-filter-row">
        <div class="search-input-wrapper">
            <span class="search-icon">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" id="searchInput" class="search-input" placeholder="Rechercher par nom, email, rôle..." onkeyup="filterTable()">
        </div>
        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <select id="roleFilter" class="filter-select" onchange="filterRole()">
                <option value="">Tous les rôles</option>
                <option value="étudiant">Étudiants</option>
                <option value="conseiller">Conseillers Validés</option>
                <option value="en attente">Conseillers en Attente</option>
                <option value="directeur">Administrateurs</option>
            </select>
        </div>
    </div>

    {{-- ═══ USERS TABLE ═══ --}}
    <div class="custom-table-wrapper">
        <div style="overflow-x: auto;">
            <table class="custom-table" id="usersTable">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Rôle / Statut</th>
                        <th>Date d'inscription</th>
                        <th style="text-align: right;">Actions de gestion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        @php
                            // Determine avatar class
                            $avatarClass = 'avatar-student';
                            if ($user->is_blocked) {
                                $avatarClass = 'avatar-blocked';
                            } elseif ($user->is_admin) {
                                $avatarClass = 'avatar-admin';
                            } elseif ($user->role === App\Models\User::ROLE_COUNSELOR) {
                                $avatarClass = 'avatar-counselor';
                            } elseif ($user->role === App\Models\User::ROLE_COUNSELOR_PENDING) {
                                $avatarClass = 'avatar-pending';
                            }

                            // Determine role badge
                            $roleLabel = 'Étudiant';
                            $roleBadgeClass = 'badge-role-student';

                            if ($user->is_admin) {
                                $roleLabel = 'Directeur';
                                $roleBadgeClass = 'badge-role-admin';
                            } elseif ($user->role === App\Models\User::ROLE_COUNSELOR) {
                                $roleLabel = 'Conseiller';
                                $roleBadgeClass = 'badge-role-counselor';
                            } elseif ($user->role === App\Models\User::ROLE_COUNSELOR_PENDING) {
                                $roleLabel = 'Conseiller (En attente)';
                                $roleBadgeClass = 'badge-role-pending';
                            }
                        @endphp
                        <tr style="{{ $user->is_blocked ? 'opacity: 0.7;' : '' }}">
                            <td>
                                <div class="user-meta-info">
                                    <div class="avatar-wrapper {{ $avatarClass }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                        @if($user->is_blocked)
                                            <div class="blocked-badge">
                                                <svg width="8" height="8" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="user-name-label" style="{{ $user->is_blocked ? 'text-decoration: line-through; color: var(--ink30);' : '' }}">
                                            {{ $user->name }}
                                            @if($user->created_at->gte(now()->subDay()) && !$user->is_blocked)
                                                <span class="badge-pill badge-pill-green" style="font-size: 0.55rem; padding: 0.1rem 0.4rem; vertical-align: middle; margin-left: 0.25rem;">Nouveau</span>
                                            @endif
                                        </div>
                                        <div class="user-email-label">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-role {{ $roleBadgeClass }}">
                                    @if($user->role === App\Models\User::ROLE_COUNSELOR_PENDING)
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.15rem;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($user->role === App\Models\User::ROLE_COUNSELOR)
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.15rem;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4" />
                                        </svg>
                                    @endif
                                    {{ $roleLabel }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.8rem; color: var(--ink60);">
                                    {{ $user->created_at->timezone('Africa/Tunis')->format('d/m/Y') }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="action-btn-group" style="justify-content: flex-end;">
                                    @if(auth()->id() !== $user->id)
                                        {{-- Toggle Block/Unblock --}}
                                        <form action="{{ route('admin.users.block', $user) }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button class="btn-action-outline {{ $user->is_blocked ? 'danger' : '' }}" 
                                                title="{{ $user->is_blocked ? 'Débloquer' : 'Bloquer l\'accès' }}" type="submit">
                                                @if($user->is_blocked)
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 10v2m-6-8h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2z" />
                                                    </svg>
                                                @else
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>

                                        @if(!$user->is_blocked)
                                            @if(!$user->is_admin)
                                                {{-- Promote --}}
                                                <form action="{{ route('admin.users.promote', $user) }}" method="POST" style="margin:0;">
                                                    @csrf
                                                    <button class="btn-action-outline" title="Promouvoir au rang d'Administrateur" type="submit">
                                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                {{-- Demote --}}
                                                <form action="{{ route('admin.users.demote', $user) }}" method="POST" style="margin:0;" onsubmit="return confirm('Rétrograder cet administrateur au rôle étudiant ?');">
                                                    @csrf
                                                    <button class="btn-action-outline" title="Rétrograder l'administrateur" type="submit">
                                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0-3a9 9 0 110 18 9 9 0 010-18z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                        {{-- Delete --}}
                                        <form action="{{ route('admin.users.delete', $user) }}" method="POST" style="margin:0;" onsubmit="return confirm('Supprimer cet utilisateur définitivement ? Cette action est irréversible.');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-action-outline danger" title="Supprimer le compte" type="submit">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span style="font-size:0.75rem; font-weight:700; color:var(--ink30); font-style:italic; padding-right:0.5rem;">Vous-même</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div style="padding:1.5rem 2rem; display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--glass-border); background:var(--ink06);">
            <div style="font-size:0.78rem; color:var(--ink60); font-weight:500;">
                Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }}
            </div>
            <div class="pagination-custom">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function filterTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('usersTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            if (cells.length > 0) {
                const text = cells[0].textContent.toLowerCase();
                const role = cells[1].textContent.toLowerCase();
                rows[i].style.display = text.includes(filter) || role.includes(filter) ? '' : 'none';
            }
        }
    }

    function filterRole() {
        const select = document.getElementById('roleFilter');
        const filter = select.value.toLowerCase();
        const table = document.getElementById('usersTable');
        const rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            if (cells.length > 0) {
                const role = cells[1].textContent.toLowerCase();
                if (filter === "") {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = role.includes(filter) ? '' : 'none';
                }
            }
        }
    }

    // Auto-select filter from URL param
    document.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(window.location.search);
        const role = params.get('role');
        if (role) {
            const select = document.getElementById('roleFilter');
            if (select) {
                if (role === 'student') {
                    select.value = 'étudiant';
                } else if (role === 'counselor') {
                    select.value = 'conseiller';
                } else if (role === 'pending') {
                    select.value = 'en attente';
                } else if (role === 'admin') {
                    select.value = 'directeur';
                }
                filterRole();
            }
        }
    });
</script>

<style>
    /* Pagination style overrides */
    .pagination-custom nav svg { width: 1.25rem; height: 1.25rem; }
    .pagination-custom nav p { display: none; }
    .pagination-custom nav .flex.items-center.justify-between { flex-direction: row-reverse; }
</style>
@endsection
