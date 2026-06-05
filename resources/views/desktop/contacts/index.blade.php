@extends('layouts.admin')

@section('title', 'Messagerie & Contacts')

@section('content')
<style>
    /* ════════════════════════════════════════════
       CAPAVENIR INBOX & CONTACTS MANAGEMENT
    ════════════════════════════════════════════ */
    .contacts-wrapper {
        display: flex;
        flex-direction: column;
        gap: 2.5rem;
        font-family: var(--font-main);
        color: var(--ink);
    }

    /* KPI Row */
    .stats-row {
        background: var(--ink06);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 1.75rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    /* Table styles */
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

    /* Avatar design */
    .avatar-box {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }
    .avatar-unread {
        background: linear-gradient(135deg, var(--accent) 0%, #ff8a43 100%);
    }
    .avatar-read {
        background: var(--ink30);
    }

    /* Buttons */
    .btn-action-glass {
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
        text-decoration: none;
        transition: var(--transition);
    }
    .btn-action-glass:hover {
        background: var(--ink10);
        color: var(--ink);
        border-color: var(--ink30);
    }
    .btn-action-glass.read-link {
        color: var(--accent2);
        border-color: color-mix(in srgb, var(--accent2) 30%, transparent);
        background: color-mix(in srgb, var(--accent2) 5%, transparent);
    }
    .btn-action-glass.read-link:hover {
        background: color-mix(in srgb, var(--accent2) 12%, transparent);
        color: var(--accent2);
    }
    .btn-action-glass.danger:hover {
        background: color-mix(in srgb, var(--red) 10%, transparent);
        color: var(--red);
        border-color: color-mix(in srgb, var(--red) 30%, transparent);
    }

    /* Badges */
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
    .badge-pill-unread {
        background: color-mix(in srgb, var(--red) 10%, transparent);
        color: var(--red);
        border: 1px solid color-mix(in srgb, var(--red) 25%, transparent);
    }
    .badge-pill-read {
        background: color-mix(in srgb, var(--accent3) 10%, transparent);
        color: var(--accent3);
        border: 1px solid color-mix(in srgb, var(--accent3) 25%, transparent);
    }

    .empty-state-card {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--ink30);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }
    .empty-state-card svg {
        opacity: 0.4;
    }
</style>

<div class="contacts-wrapper">
    {{-- Header --}}
    <div class="glass-card" style="background: var(--ink06); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        <div>
            <h3 style="font-family: var(--font-serif); font-size: 1.4rem; font-weight: 400; font-style: italic; color: var(--ink);">Messagerie & Contacts</h3>
            <p style="font-size: 0.82rem; color: var(--ink60); margin-top: 0.3rem;">Consultez et gérez les requêtes d'information issues de la page de contact de la plateforme.</p>
        </div>
        <div style="display: flex; gap: 2.5rem;">
            <div style="text-align: center;">
                <div style="font-family: var(--font-serif); font-size: 2rem; font-weight: 400; color: var(--ink); line-height: 1;">{{ $contacts->count() }}</div>
                <div style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: var(--accent); margin-top: 0.4rem;">Total Reçus</div>
            </div>
            <div style="text-align: center;">
                <div id="non-lus-stat" style="font-family: var(--font-serif); font-size: 2rem; font-weight: 400; color: var(--red); line-height: 1;">{{ $nonLusCount }}</div>
                <div style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: var(--red); margin-top: 0.4rem;">Non Lus</div>
            </div>
        </div>
    </div>

    {{-- Messages Table --}}
    <div class="custom-table-wrapper">
        <div style="overflow-x:auto;">
            <table class="custom-table" id="contactsTable">
                <thead>
                    <tr>
                        <th>Expéditeur</th>
                        <th>Sujet du message</th>
                        <th>Reçu le</th>
                        <th>Statut</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr id="contact-row-{{ $contact->id }}" style="transition:0.2s;">
                            <td>
                                <div style="display: flex; align-items: center; gap: 1.1rem;">
                                    <div class="avatar-box {{ $contact->lire ? 'avatar-read' : 'avatar-unread' }}">
                                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: var(--ink); line-height: 1.25; margin-bottom: 0.15rem;">
                                            {{ $contact->name }}
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--ink30);">
                                            {{ $contact->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 320px;">
                                    {{ $contact->sujet }}
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.82rem; color: var(--ink60);">
                                    {{ $contact->created_at->timezone('Africa/Tunis')->format('d/m/Y H:i') }}
                                </span>
                            </td>
                            <td>
                                @if($contact->lire)
                                    <span class="badge-pill badge-pill-read">Lu</span>
                                @else
                                    <span class="badge-pill badge-pill-unread">Non lu</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                                    <a href="{{ route('admin.contacts.show', $contact->id) }}" 
                                       class="btn-action-glass {{ $contact->lire ? '' : 'read-link' }}" 
                                       title="Consulter le message">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <button onclick="deleteContact({{ $contact->id }})" class="btn-action-glass danger" title="Supprimer définitivement">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state-card">
                                    <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5M12 14v2m-3-1v1m6-1v1" />
                                    </svg>
                                    <div style="font-weight: 600; color: var(--ink);">Boîte de réception vide</div>
                                    <div style="font-size: 0.8rem; color: var(--ink30);">Aucune demande de contact externe n'a été reçue pour le moment.</div>
                                </div>
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
        if (!confirm('Souhaitez-vous vraiment supprimer définitivement ce message ?')) return;

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
                        location.reload(); 
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
        
        const dot = document.querySelector('.notif-dot');
        if (dot) {
            dot.style.display = count > 0 ? 'block' : 'none';
        }
    }
</script>
@endsection
