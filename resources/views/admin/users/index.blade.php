@extends('layouts.admin')

@section('title', 'Gestion Utilisateurs')

@section('content')
<div style="display:flex;flex-direction:column;gap:2.5rem;">

    {{-- ═══ TOP OVERVIEW ═══ --}}
    <div class="glass-card" style="background: var(--cream); border: 1px solid var(--ink10); padding: 1.75rem 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <h3 style="font-size:1.25rem;font-weight:900;color:var(--ink);letter-spacing:-0.01em;">Gestion des Utilisateurs</h3>
            <p style="font-size:0.82rem;color:var(--ink60);margin-top:0.3rem;">Contrôlez les accès et les rôles de votre communauté.</p>
        </div>
        <div style="display:flex;gap:2.5rem;">
            <div style="text-align:center;">
                <div style="font-size:1.75rem;font-weight:900;color:var(--ink);line-height:1;">{{ $users->total() }}</div>
                <div style="font-size:0.65rem;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:var(--accent);margin-top:0.4rem;">Total</div>
            </div>
            <div style="text-align:center;">
                <div style="font-size:1.75rem;font-weight:900;color:var(--ink);line-height:1;">+{{ $newCount ?? 0 }}</div>
                <div style="font-size:0.65rem;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:var(--accent);margin-top:0.4rem;">24h</div>
            </div>
        </div>
    </div>

    {{-- ═══ ACTIONS & FILTERS ═══ --}}
    <div style="display:flex;justify-content:space-between;align-items:center;gap:1.5rem;flex-wrap:wrap;">
        <div style="position:relative;flex:1;min-width:300px;">
            <span style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--text-muted);pointer-events:none;"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='var(--ink30)' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196 7.5 7.5 0 0010.607 10.607z' /></svg></span>
            <input type="text" id="searchInput" placeholder="Rechercher par nom, email ou rôle..." 
                onkeyup="filterTable()"
                style="width:100%;background:var(--glass-bg-md);border:1px solid var(--glass-border);border-radius:14px;padding:0.75rem 1rem 0.75rem 2.6rem;font-size:0.875rem;color:var(--ink);font-family:var(--font-main);outline:none;transition:0.3s;"
                onfocus="this.style.borderColor='var(--accent)';this.style.background='rgba(255,255,255,0.06)'"
                onblur="this.style.borderColor='var(--glass-border)';this.style.background='var(--glass-bg-md)'">
        </div>
        <div style="display:flex;gap:0.75rem;align-items:center;">
            <select style="background:var(--glass-bg-md);border:1px solid var(--glass-border);border-radius:12px;padding:0.65rem 1rem;font-size:0.82rem;font-weight:600;color:var(--ink60);outline:none;cursor:pointer;font-family:var(--font-main);transition:0.2s;"
                onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--glass-border)'">
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
                        <th style="padding:1.1rem 2rem;text-align:left;font-size:0.68rem;font-weight:800;color:var(--ink);text-transform:uppercase;letter-spacing:0.12em;">Utilisateur</th>
                        <th style="padding:1.1rem 2rem;text-align:left;font-size:0.68rem;font-weight:800;color:var(--ink);text-transform:uppercase;letter-spacing:0.12em;">Status / Rôle</th>
                        <th style="padding:1.1rem 2rem;text-align:left;font-size:0.68rem;font-weight:800;color:var(--ink);text-transform:uppercase;letter-spacing:0.12em;">Temporalité</th>
                        <th style="padding:1.1rem 2rem;text-align:right;font-size:0.68rem;font-weight:800;color:var(--ink);text-transform:uppercase;letter-spacing:0.12em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr style="border-top:1px solid var(--glass-border);transition:0.2s;{{ $user->is_blocked ? 'background:rgba(220, 53, 69, 0.07);' : '' }}"
                        onmouseover="this.style.background='{{ $user->is_blocked ? 'rgba(220, 53, 69, 0.1)' : 'rgba(255,255,255,0.03)' }}'" 
                        onmouseout="this.style.background='{{ $user->is_blocked ? 'rgba(220, 53, 69, 0.07)' : '' }}'">
                        <td style="padding:1.25rem 2rem;">
                            <div style="display:flex;align-items:center;gap:1.1rem;">
                                {{-- Avatar with dynamic gradient --}}
                                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,{{ $user->is_blocked ? 'var(--ink30),var(--ink10)' : ($user->is_admin ? 'var(--red-alert),var(--violet-dark)' : 'var(--accent),var(--violet)') }});display:flex;align-items:center;justify-content:center;font-weight:900;font-size:1.1rem;color:white;box-shadow:0 4px 12px rgba(0,0,0,0.2);flex-shrink:0;position:relative;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @if($user->is_blocked)
                                        <div style="position:absolute;bottom:-4px;right:-4px;width:18px;height:18px;background:var(--red);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.6rem;border:2px solid var(--paper);"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M16.5 10.5V6.75a4.5 4.5 0 10-9 0V10.5m-2.25 0h13.5c.621 0 1.125.504 1.125 1.125v6.75c0 .621-.504 1.125-1.125 1.125H5.25a1.125 1.125 0 01-1.125-1.125v-6.75c0-.621.504-1.125 1.125-1.125z' /></svg></div>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight:800;font-size:0.95rem;color:{{ $user->is_blocked ? 'var(--ink30)' : 'var(--ink)' }};line-height:1.2;margin-bottom:0.15rem;display:flex;align-items:center;gap:0.5rem;{{ $user->is_blocked ? 'text-decoration:line-through;' : '' }}">
                                        {{ $user->name }}
                                        @if($user->is_blocked)
                                            <span style="font-size:0.55rem;font-weight:900;text-transform:uppercase;background:var(--red);color:white;padding:0.1rem 0.4rem;border-radius:999px;">Bloqué</span>
                                        @endif
                                        @if($user->created_at->gte(now()->subDay()) && !$user->is_blocked)
                                            <span style="font-size:0.55rem;font-weight:900;text-transform:uppercase;background:var(--red-alert);color:white;padding:0.1rem 0.4rem;border-radius:999px;">New</span>
                                        @endif
                                    </div>
                                    <div style="font-size:0.75rem;color:var(--ink60);font-weight:400;{{ $user->is_blocked ? 'opacity:0.5;' : '' }}">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:1.25rem 2rem;">
                            @php
                                $roleClass = $user->is_admin ? 'badge-red' : ($user->role == 'student' ? 'badge-indigo' : 'badge-violet');
                                $roleLabel = $user->is_admin ? 'Directeur' : ($user->role == 'student' ? 'Étudiant' : 'Conseiller');
                            @endphp
                             <span class="badge {{ $roleClass }}" style="{{ $user->is_blocked ? 'opacity:0.4;filter:grayscale(1);' : '' }}">{{ $roleLabel }}</span>
                        </td>
                        <td style="padding:1.25rem 2rem;">
                            <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--ink30);margin-bottom:0.15rem;">Inscrit le</div>
                            <div style="font-size:0.82rem;color:var(--ink60);font-style:normal;">{{ $user->created_at->timezone('Africa/Tunis')->format('d/m/Y') }}</div>
                        </td>
                        <td style="padding:1.25rem 2rem;text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;align-items:center;">
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.block', $user) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button class="btn-glass" style="width:34px;height:34px;padding:0;border-radius:10px;font-size:0.9rem;{{ $user->is_blocked ? 'color:var(--red);border-color:rgba(220,53,69,0.3);background:rgba(220,53,69,0.05);' : '' }}" 
                                            title="{{ $user->is_blocked ? 'Débloquer' : 'Bloquer' }}" type="submit">
                                            {{ $user->is_blocked ? '<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z' /></svg>' : '<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M16.5 10.5V6.75a4.5 4.5 0 10-9 0V10.5m-2.25 0h13.5c.621 0 1.125.504 1.125 1.125v6.75c0 .621-.504 1.125-1.125 1.125H5.25a1.125 1.125 0 01-1.125-1.125v-6.75c0-.621.504-1.125 1.125-1.125z' /></svg>' }}
                                        </button>
                                    </form>

                                    @if(!$user->is_blocked)
                                        @if(!$user->is_admin)
                                            <form action="{{ route('admin.users.promote', $user) }}" method="POST" style="margin:0;">
                                                @csrf
                                                <button class="btn-glass" style="width:34px;height:34px;padding:0;border-radius:10px;font-size:0.9rem;" title="Promouvoir" type="submit"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='var(--gold)' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z' /></svg></button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.demote', $user) }}" method="POST" style="margin:0;" onsubmit="return confirm('Rétrograder cet administrateur ?');">
                                                @csrf
                                                <button class="btn-glass" style="width:34px;height:34px;padding:0;border-radius:10px;" title="Rétrograder" type="submit"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3' /></svg></button>
                                            </form>
                                        @endif
                                    @endif

                                    <form action="{{ route('admin.users.delete', $user) }}" method="POST" style="margin:0;" onsubmit="return confirm('Supprimer cet utilisateur définitivement ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-glass" style="width:34px;height:34px;padding:0;border-radius:10px;color:#f87171;border-color:rgba(248,113,113,0.2);" 
                                            onmouseover="this.style.background='rgba(248,113,113,0.1)';this.style.borderColor='rgba(248,113,113,0.4)'"
                                            onmouseout="this.style.background='var(--glass-bg)';this.style.borderColor='var(--glass-border)'"
                                            title="Supprimer" type="submit">
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0' /></svg>
                                        </button>
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
            <div style="font-size:0.78rem;color:var(--ink60);font-weight:400;">
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
</script>

<style>
    /* Pagination style overrides for dark mode */
    .pagination-custom nav svg { width: 1.25rem; height: 1.25rem; }
    .pagination-custom nav p { display: none; }
    .pagination-custom nav .flex.items-center.justify-between { flex-direction: row-reverse; }
    /* Blade pagination often generates specific classes, these will vary but we try to keep it minimalist */
</style>
@endsection
