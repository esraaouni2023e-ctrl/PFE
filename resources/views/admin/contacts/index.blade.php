@extends('layouts.admin')

@section('title', 'Messagerie & Contacts')

@section('content')
<div style="display:flex;flex-direction:column;gap:2.5rem;">

    {{-- ═══ TOP OVERVIEW ═══ --}}
    <div class="glass-card" style="background: var(--cream); border: 1px solid var(--ink10); padding: 1.75rem 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <h3 style="font-size:1.25rem;font-weight:900;color:var(--ink);letter-spacing:-0.01em;">Gestion des Messages</h3>
            <p style="font-size:0.82rem;color:var(--ink60);margin-top:0.3rem;">Consultez et gérez les demandes de contact de la landing page.</p>
        </div>
        <div style="display:flex;gap:2.5rem;">
            <div style="text-align:center;">
                <div style="font-size:1.75rem;font-weight:900;color:var(--ink);line-height:1;">{{ $contacts->count() }}</div>
                <div style="font-size:0.65rem;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:var(--accent);margin-top:0.4rem;">Total</div>
            </div>
            <div style="text-align:center;">
                <div id="non-lus-stat" style="font-size:1.75rem;font-weight:900;color:var(--red);line-height:1;">{{ $nonLusCount }}</div>
                <div style="font-size:0.65rem;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:var(--red);margin-top:0.4rem;">Non lus</div>
            </div>
        </div>
    </div>

    {{-- ═══ CONTACTS TABLE ═══ --}}
    <div class="glass-card" style="padding:0;overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;" id="contactsTable">
                <thead>
                    <tr style="background:rgba(255,255,255,0.03);">
                        <th style="padding:1.1rem 2rem;text-align:left;font-size:0.68rem;font-weight:800;color:var(--ink);text-transform:uppercase;letter-spacing:0.12em;">Expéditeur</th>
                        <th style="padding:1.1rem 2rem;text-align:left;font-size:0.68rem;font-weight:800;color:var(--ink);text-transform:uppercase;letter-spacing:0.12em;">Sujet</th>
                        <th style="padding:1.1rem 2rem;text-align:left;font-size:0.68rem;font-weight:800;color:var(--ink);text-transform:uppercase;letter-spacing:0.12em;">Date</th>
                        <th style="padding:1.1rem 2rem;text-align:left;font-size:0.68rem;font-weight:800;color:var(--ink);text-transform:uppercase;letter-spacing:0.12em;">Statut</th>
                        <th style="padding:1.1rem 2rem;text-align:right;font-size:0.68rem;font-weight:800;color:var(--ink);text-transform:uppercase;letter-spacing:0.12em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                    <tr id="contact-row-{{ $contact->id }}" style="border-top:1px solid var(--glass-border);transition:0.2s;"
                        onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background=''">
                        <td style="padding:1.25rem 2rem; overflow: hidden; max-width: 250px; box-sizing: border-box;">
                            <div style="display:flex;align-items:center;gap:1.1rem; max-width: 100%;">
                                <div style="width:40px;height:40px;border-radius:10px;background:{{ $contact->lire ? 'var(--ink10)' : 'linear-gradient(135deg, var(--accent), var(--accent2))' }};display:flex;align-items:center;justify-content:center;font-weight:800;color:white;flex-shrink:0;">
                                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                                </div>
                                <div style="flex: 1; min-width: 0; overflow: hidden;">
                                    <div style="font-weight:800;font-size:0.95rem;color:var(--ink);line-height:1.2;margin-bottom:0.15rem; word-break: break-word; overflow-wrap: break-word; white-space: pre-wrap;">{{ $contact->name }}</div>
                                    <div style="font-size:0.75rem;color:var(--ink60); word-break: break-word; overflow-wrap: break-word; white-space: pre-wrap;">{{ $contact->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:1.25rem 2rem; overflow: hidden; max-width: 300px; box-sizing: border-box;">
                            <div style="font-size:0.88rem;font-weight:600;color:var(--ink); word-break: break-word; overflow-wrap: break-word; white-space: pre-wrap; max-width: 100%; overflow: hidden;">{{ $contact->sujet }}</div>
                        </td>
                        <td style="padding:1.25rem 2rem;">
                            <div style="font-size:0.82rem;color:var(--ink60);">{{ $contact->created_at->timezone('Africa/Tunis')->format('d/m/Y H:i') }}</div>
                        </td>
                        <td style="padding:1.25rem 2rem;">
                            @if($contact->lire)
                                <span class="badge badge-green">Lu</span>
                            @else
                                <span class="badge badge-red">Non lu</span>
                            @endif
                        </td>
                        <td style="padding:1.25rem 2rem;text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;align-items:center;">
                                <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn-glass" 
                                   style="width:34px;height:34px;padding:0;border-radius:10px;display:flex;align-items:center;justify-content:center;text-decoration:none;{{ $contact->lire ? '' : 'color:var(--accent2);border-color:rgba(26,79,110,0.3);background:rgba(26,79,110,0.05);' }}" 
                                   title="Voir le message">
                                    👁️
                                </a>
                                <button onclick="deleteContact({{ $contact->id }})" class="btn-glass" 
                                        style="width:34px;height:34px;padding:0;border-radius:10px;color:var(--red);border-color:rgba(192,57,43,0.2);display:flex;align-items:center;justify-content:center;" 
                                        title="Supprimer">
                                    🗑️
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:4rem 2rem;text-align:center;">
                            <div style="font-size:3rem;margin-bottom:1rem;opacity:0.3;">📥</div>
                            <h4 style="font-weight:600;color:var(--ink60);">Aucun message pour le moment.</h4>
                            <p style="font-size:0.82rem;color:var(--ink30);margin-top:0.5rem;">Les demandes de contact apparaîtront ici.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function deleteContact(id) {
        if (!confirm('Souhaitez-vous vraiment supprimer ce message ?')) return;

        fetch(`/admin/contacts/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`contact-row-${id}`);
                row.style.opacity = '0';
                row.style.transform = 'translateX(20px)';
                setTimeout(() => {
                    row.remove();
                    if (document.querySelectorAll('tbody tr[id^="contact-row"]').length === 0) {
                        location.reload(); // Reload to show empty state if last item deleted
                    }
                }, 300);

                // Update notification counts
                updateNotificationBadges(data.nonLusCount);
                
                // Update local stat
                const statEl = document.getElementById('non-lus-stat');
                if (statEl) statEl.textContent = data.nonLusCount;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue lors de la suppression.');
        });
    }

    function updateNotificationBadges(count) {
        const badge = document.getElementById('notif-badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
        
        // Also update the dot in the navbar if it exists
        const dot = document.querySelector('.notif-dot');
        if (dot) {
            dot.style.display = count > 0 ? 'block' : 'none';
        }
    }
</script>
@endsection
