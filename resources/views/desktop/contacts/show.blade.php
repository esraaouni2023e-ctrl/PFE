@extends('layouts.admin')

@section('title', 'Détail du message')

@section('content')
<style>
    /* ════════════════════════════════════════════
       CAPAVENIR MESSAGE DETAILS VIEW
    ════════════════════════════════════════════ */
    .message-detail-wrapper {
        max-width: 900px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 2rem;
        font-family: var(--font-main);
        color: var(--ink);
    }

    .btn-nav-glass {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--r);
        border: 1px solid var(--glass-border);
        background: var(--ink06);
        color: var(--ink60);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
    }
    .btn-nav-glass:hover {
        background: var(--ink10);
        color: var(--ink);
        border-color: var(--ink30);
    }
    .btn-nav-glass.danger {
        color: var(--red);
        border-color: color-mix(in srgb, var(--red) 30%, transparent);
        background: color-mix(in srgb, var(--red) 5%, transparent);
    }
    .btn-nav-glass.danger:hover {
        background: var(--red);
        color: white;
        border-color: var(--red);
    }

    .message-main-card {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        padding: 3rem;
        box-shadow: var(--shadow-card);
    }

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
    .badge-pill-read {
        background: color-mix(in srgb, var(--accent3) 10%, transparent);
        color: var(--accent3);
        border: 1px solid color-mix(in srgb, var(--accent3) 25%, transparent);
    }

    .btn-action-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.25rem;
        border-radius: var(--r);
        font-size: 0.85rem;
        font-weight: 700;
        background: var(--accent2);
        color: white;
        text-decoration: none;
        border: 1px solid var(--accent2);
        transition: var(--transition);
    }
    .btn-action-primary:hover {
        background: color-mix(in srgb, var(--accent2) 90%, #000);
        border-color: color-mix(in srgb, var(--accent2) 90%, #000);
        transform: translateY(-1px);
    }
</style>

<div class="message-detail-wrapper">

    {{-- Top Action Row --}}
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('admin.contacts.index') }}" class="btn-nav-glass">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour à la boîte de réception
        </a>
        
        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Souhaitez-vous vraiment supprimer ce message définitivement ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-nav-glass danger">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Supprimer ce message
            </button>
        </form>
    </div>

    {{-- Message Body Card --}}
    <div class="message-main-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2.5rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 2rem; flex-wrap: wrap; gap: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <div style="width: 64px; height: 64px; border-radius: 16px; background: linear-gradient(135deg, var(--accent) 0%, #ff8a43 100%); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 700; color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                </div>
                <div>
                    <h2 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 400; font-style: italic; color: var(--ink); margin-bottom: 0.25rem;">{{ $contact->name }}</h2>
                    <p style="font-size: 0.95rem; color: var(--accent2); font-weight: 600;">{{ $contact->email }}</p>
                </div>
            </div>
            <div style="text-align: right;">
                <span class="badge-pill badge-pill-read" style="margin-bottom: 0.75rem;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.15rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4" />
                    </svg>
                    Message Lu
                </span>
                <p style="font-size: 0.82rem; color: var(--ink30); font-weight: 500;">
                    Reçu le {{ $contact->created_at->timezone('Africa/Tunis')->format('d F Y à H:i') }}
                </p>
            </div>
        </div>

        <div style="margin-bottom: 2.5rem;">
            <p style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink30); margin-bottom: 0.75rem;">Objet de la demande</p>
            <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--ink); line-height: 1.35;">{{ $contact->sujet }}</h3>
        </div>

        <div>
            <p style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink30); margin-bottom: 1rem;">Corps du message</p>
            <div style="background: var(--ink06); padding: 2rem; border-radius: var(--rl); border: 1px solid var(--glass-border); line-height: 1.8; color: var(--ink60); font-size: 1.02rem; white-space: pre-wrap;">{{ $contact->message }}</div>
        </div>
    </div>

    {{-- Mailto Quick Reply --}}
    <div class="glass-card" style="background: var(--ink06); display: flex; align-items: center; justify-content: center; padding: 2rem; gap: 1.5rem; flex-wrap: wrap; text-align: center;">
        <p style="font-size: 0.9rem; color: var(--ink60); font-weight: 500; margin: 0;">Souhaitez-vous répondre directement à cet expéditeur ?</p>
        <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->sujet }}" class="btn-action-primary">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5" />
            </svg>
            Répondre par e-mail
        </a>
    </div>

</div>
@endsection
