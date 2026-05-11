@extends('layouts.admin')

@section('title', 'Détail du message')

@section('content')
<div style="max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 2rem;">

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('admin.contacts.index') }}" class="btn-glass" style="text-decoration: none; display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
            ← Retour à la liste
        </a>
        
        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Supprimer ce message définitivement ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-glass" style="color: var(--red); border-color: rgba(192,57,43,0.2); font-size: 0.85rem;">
                🗑️ Supprimer ce message
            </button>
        </form>
    </div>

    <div class="glass-card" style="padding: 3rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2.5rem; border-bottom: 1px solid var(--glass-border); padding-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <div style="width: 64px; height: 64px; border-radius: 16px; background: linear-gradient(135deg, var(--accent), var(--accent2)); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 800; color: white;">
                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                </div>
                <div>
                    <h2 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 600; color: var(--ink); margin-bottom: 0.25rem;">{{ $contact->name }}</h2>
                    <p style="font-size: 0.95rem; color: var(--accent2); font-weight: 500;">{{ $contact->email }}</p>
                </div>
            </div>
            <div style="text-align: right;">
                <span class="badge badge-green" style="margin-bottom: 0.75rem;">Message Lu</span>
                <p style="font-size: 0.82rem; color: var(--ink30);">Reçu le {{ $contact->created_at->timezone('Africa/Tunis')->format('d F Y à H:i') }}</p>
            </div>
        </div>

        <div style="margin-bottom: 2.5rem; overflow: hidden; width: 100%; box-sizing: border-box;">
            <p style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink30); margin-bottom: 0.75rem;">Sujet</p>
            <h3 style="font-size: 1.2rem; font-weight: 600; color: var(--ink); word-break: break-word; overflow-wrap: break-word; white-space: pre-wrap; max-width: 100%; overflow: hidden;">{{ $contact->sujet }}</h3>
        </div>

        <div style="overflow: hidden; width: 100%; box-sizing: border-box;">
            <p style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink30); margin-bottom: 1rem;">Message</p>
            <div style="background: var(--ink06); padding: 2rem; border-radius: var(--rl); border: 1px solid var(--glass-border); line-height: 1.8; color: var(--ink); font-size: 1.05rem; white-space: pre-wrap; word-break: break-word; overflow-wrap: break-word; max-width: 100%; overflow: hidden;">{{ $contact->message }}</div>
        </div>
    </div>

    <div class="glass-card" style="background: rgba(26, 79, 110, 0.05); border-color: rgba(26, 79, 110, 0.1); display: flex; align-items: center; justify-content: center; padding: 2rem; gap: 1rem;">
        <p style="font-size: 0.9rem; color: var(--ink60);">Vous souhaitez répondre à cet utilisateur ?</p>
        <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->sujet }}" class="btn-primary" style="font-size: 0.85rem;">
            ✉️ Répondre par Email
        </a>
    </div>

</div>
@endsection
