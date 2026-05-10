@extends('layouts.admin')

@section('title', 'Gestion Utilisateurs')

@section('content')
<div style="display:flex;flex-direction:column;gap:2.5rem;">

    {{-- ═══ TOP OVERVIEW ═══ --}}
    <div class="glass-card" style="border-color:rgba(99,102,241,0.25);background:rgba(99,102,241,0.05);padding:1.75rem 2rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1.5rem;">
        <div>
            <h3 style="font-size:1.25rem;font-weight:900;color:var(--text-primary);letter-spacing:-0.01em;">Gestion des Utilisateurs</h3>
            <p style="font-size:0.82rem;color:var(--text-secondary);margin-top:0.3rem;">Contrôlez les accès et les rôles de votre communauté.</p>
        </div>
        <div style="display:flex;gap:2.5rem;">
            <div style="text-align:center;">
                <div style="font-size:1.75rem;font-weight:900;color:var(--text-primary);line-height:1;">{{ $users->total() }}</div>
                <div style="font-size:0.65rem;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:var(--indigo-light);margin-top:0.4rem;">Total</div>
            </div>
            <div style="text-align:center;">
                <div style="font-size:1.75rem;font-weight:900;color:var(--text-primary);line-height:1;">+{{ $newCount ?? 0 }}</div>
                <div style="font-size:0.65rem;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:var(--indigo-light);margin-top:0.4rem;">24h</div>
            </div>
        </div>
    </div>

    {{-- ═══ ACTIONS & FILTERS ═══ --}}
    <div style="display:flex;justify-content:space-between;align-items:center;gap:1.5rem;flex-wrap:wrap;">
        <div style="position:relative;flex:1;min-width:300px;">
            <span style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--text-muted);pointer-events:none;">🔍</span>
            <input type="text" id="searchInput" placeholder="Rechercher par nom, email ou rôle..." 
                onkeyup="filterTable()"
                style="width:100%;background:var(--glass-bg-md);border:1px solid var(--glass-border);border-radius:14px;padding:0.75rem 1rem 0.75rem 2.6rem;font-size:0.875rem;color:var(--text-primary);font-family:var(--font-main);outline:none;transition:0.3s;"
                onfocus="this.style.borderColor='var(--indigo)';this.style.background='rgba(255,255,255,0.06)'"
                onblur="this.style.borderColor='var(--glass-border)';this.style.background='var(--glass-bg-md)'">
        </div>
        <div style="display:flex;gap:0.75rem;align-items:center;">
            <select style="background:var(--glass-bg-md);border:1px solid var(--glass-border);border-radius:12px;padding:0.65rem 1rem;font-size:0.82rem;font-weight:600;color:var(--text-secondary);outline:none;cursor:pointer;font-family:var(--font-main);transition:0.2s;"
                onfocus="this.style.borderColor='var(--indigo)'" onblur="this.style.borderColor='var(--glass-border)'">
                <option>Tous les rôles</option>
                <option>Étudiant</option>
                <option>Conseiller</option>
                <option>Admin</option>
            </select>
            <button class="btn-primary" style="padding:0.65rem 1.25rem;font-size:0.8rem;gap:0.4rem;white-space:nowrap;">
                <span style="font-size:1.1rem;line-height:0;">+</span> Nouvel Utilisateur
            </button>
        </div>
    </div>

    {{-- ═══ USERS TABLE ═══ --}}
    <div class="glass-card" style="padding:0;overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;" id="usersTable">
                <thead>
                    <tr style="background:rgba(255,255,255,0.03);">
                        <th style="padding:1.1rem 2rem;text-align:left;font-size:0.68rem;font-weight:800;color:var(--text-primary);text-transform:uppercase;letter-spacing:0.12em;">Utilisateur</th>
                        <th style="padding:1.1rem 2rem;text-align:left;font-size:0.68rem;font-weight:800;color:var(--text-primary);text-transform:uppercase;letter-spacing:0.12em;">Status / Rôle</th>
                        <th style="padding:1.1rem 2rem;text-align:left;font-size:0.68rem;font-weight:800;color:var(--text-primary);text-transform:uppercase;letter-spacing:0.12em;">Temporalité</th>
                        <th style="padding:1.1rem 2rem;text-align:right;font-size:0.68rem;font-weight:800;color:var(--text-primary);text-transform:uppercase;letter-spacing:0.12em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr style="border-top:1px solid var(--glass-border);transition:0.2s;"
                        onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background=''">
                        <td style="padding:1.25rem 2rem;">
                            <div style="display:flex;align-items:center;gap:1.1rem;">
                                {{-- Avatar with dynamic gradient --}}
                                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,{{ $user->is_admin ? 'var(--red-alert),var(--violet-dark)' : 'var(--indigo),var(--violet)' }});display:flex;align-items:center;justify-content:center;font-weight:900;font-size:1.1rem;color:white;box-shadow:0 4px 12px rgba(0,0,0,0.2);flex-shrink:0;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:800;font-size:0.95rem;color:var(--text-primary);line-height:1.2;margin-bottom:0.15rem;display:flex;align-items:center;gap:0.5rem;">
                                        {{ $user->name }}
                                        @if($user->created_at->gte(now()->subDay()))
                                            <span style="font-size:0.55rem;font-weight:900;text-transform:uppercase;background:var(--red-alert);color:white;padding:0.1rem 0.4rem;border-radius:999px;">New</span>
                                        @endif
                                    </div>
                                    <div style="font-size:0.75rem;color:var(--text-muted);">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:1.25rem 2rem;">
                            @php
                                $roleClass = $user->is_admin ? 'badge-red' : ($user->role == 'student' ? 'badge-indigo' : 'badge-violet');
                                $roleLabel = $user->is_admin ? 'Directeur' : ($user->role == 'student' ? 'Étudiant' : 'Conseiller');
                            @endphp
                            <span class="badge {{ $roleClass }}">{{ $roleLabel }}</span>
                        </td>
                        <td style="padding:1.25rem 2rem;">
                            <div style="font-size:0.85rem;font-weight:700;color:var(--text-primary);">Inscrit le</div>
                            <div style="font-size:0.72rem;color:var(--text-muted);font-style:italic;">{{ $user->created_at->format('d/m/Y') }}</div>
                        </td>
                        <td style="padding:1.25rem 2rem;text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;align-items:center;">
                                @if(auth()->id() !== $user->id && !$user->is_admin)
                                    <form action="{{ route('admin.users.promote', $user) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button class="btn-glass" style="width:34px;height:34px;padding:0;border-radius:10px;font-size:0.9rem;" title="Promouvoir" type="submit">✨</button>
                                    </form>
                                    <form action="{{ route('admin.users.delete', $user) }}" method="POST" style="margin:0;" onsubmit="return confirm('⚠️ Supprimer cet utilisateur définitivement ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-glass" style="width:34px;height:34px;padding:0;border-radius:10px;color:#f87171;border-color:rgba(248,113,113,0.2);" 
                                            onmouseover="this.style.background='rgba(248,113,113,0.1)';this.style.borderColor='rgba(248,113,113,0.4)'"
                                            onmouseout="this.style.background='var(--glass-bg)';this.style.borderColor='var(--glass-border)'"
                                            title="Supprimer" type="submit">
                                            🗑️
                                        </button>
                                    </form>
                                @elseif($user->is_admin && auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.demote', $user) }}" method="POST" style="margin:0;" onsubmit="return confirm('⬇️ Rétrograder cet administrateur ?');">
                                        @csrf
                                        <button class="btn-glass" style="width:34px;height:34px;padding:0;border-radius:10px;" title="Rétrograder" type="submit">⬇️</button>
                                    </form>
                                @else
                                    <span style="font-size:0.72rem;font-weight:700;color:var(--text-muted);font-style:italic;padding-right:0.5rem;">Vous</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div style="padding:1.5rem 2rem;display:flex;justify-content:space-between;align-items:center;border-top:1px solid var(--glass-border);background:rgba(0,0,0,0.1);">
            <div style="font-size:0.78rem;color:var(--text-muted);font-weight:600;">
                Affichage de <span style="color:var(--text-primary);font-weight:800;">{{ $users->firstItem() }}</span> à <span style="color:var(--text-primary);font-weight:800;">{{ $users->lastItem() }}</span> sur <span style="color:var(--text-primary);font-weight:800;">{{ $users->total() }}</span>
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
</script>

<style>
    /* Pagination style overrides for dark mode */
    .pagination-custom nav svg { width: 1.25rem; height: 1.25rem; }
    .pagination-custom nav p { display: none; }
    .pagination-custom nav .flex.items-center.justify-between { flex-direction: row-reverse; }
    /* Blade pagination often generates specific classes, these will vary but we try to keep it minimalist */
</style>
@endsection
