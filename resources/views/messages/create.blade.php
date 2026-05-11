@extends(auth()->user()->isStudent() ? 'layouts.student' : 'layouts.counselor')

@section('title', 'Nouveau Message')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 2rem;">

    <a href="{{ route('messages.index') }}" style="display:inline-flex; align-items:center; gap:0.5rem; text-decoration:none; color:var(--ink60); font-size:0.85rem; font-weight:600; margin-bottom:2rem; transition:0.2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--ink60)'">
        ← Retour à la boîte de réception
    </a>

    <div class="glass-card" style="background: var(--paper); border: 1px solid var(--ink10); border-radius: var(--rl); overflow:hidden; padding: 3rem;">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <div style="width: 60px; height: 60px; background: color-mix(in srgb, var(--accent) 10%, transparent); color: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; margin: 0 auto 1rem;">✉️</div>
            <h2 style="font-family:'Fraunces', serif; font-size:1.8rem; color:var(--ink); margin-bottom:0.5rem;">Envoyer un nouveau message</h2>
            <p style="color:var(--ink60); font-size:0.95rem;">Remplissez les détails ci-dessous pour contacter un {{ auth()->user()->isStudent() ? 'conseiller' : 'étudiant' }}.</p>
        </div>

        @if(session('error'))
            <div style="background: color-mix(in srgb, #ef4444 10%, transparent); color: #ef4444; padding: 1rem; border-radius: var(--r); border: 1px solid color-mix(in srgb, #ef4444 25%, transparent); margin-bottom: 2rem; font-size: 0.9rem; font-weight: 600; text-align: center;">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('messages.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--ink30); margin-bottom: 0.5rem; letter-spacing: 0.05em;">Email du destinataire</label>
                    <input type="email" name="receiver_email" placeholder="destinataire@exemple.com" value="{{ old('receiver_email') }}" required 
                        style="width: 100%; padding: 0.95rem 1.25rem; border-radius: var(--r); border: 1px solid var(--ink10); background: var(--paper); color: var(--ink); font-family: var(--font-main); outline: none; transition: 0.3s;"
                        onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--ink10)'">
                    <p style="font-size:0.7rem; color:var(--ink30); margin-top:0.4rem;">Saisissez l'adresse e-mail exacte du {{ auth()->user()->isStudent() ? 'conseiller' : 'étudiant' }}.</p>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--ink30); margin-bottom: 0.5rem; letter-spacing: 0.05em;">Sujet</label>
                    <input type="text" name="subject" placeholder="Sujet de votre message" value="{{ old('subject') }}" required 
                        style="width: 100%; padding: 0.95rem 1.25rem; border-radius: var(--r); border: 1px solid var(--ink10); background: var(--paper); color: var(--ink); font-family: var(--font-main); outline: none; transition: 0.3s;"
                        onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--ink10)'">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--ink30); margin-bottom: 0.5rem; letter-spacing: 0.05em;">Message</label>
                    <textarea name="body" rows="7" placeholder="Écrivez votre message ici..." required 
                        style="width: 100%; padding: 0.95rem 1.25rem; border-radius: var(--r); border: 1px solid var(--ink10); background: var(--paper); color: var(--ink); font-family: var(--font-main); outline: none; transition: 0.3s; resize: vertical;"
                        onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--ink10)'">{{ old('body') }}</textarea>
                </div>
                <div style="text-align: center; margin-top: 1.5rem;">
                    <button type="submit" class="btn-fill" style="padding: 12px 28px; font-size: 0.95rem; background: var(--accent); color: white; border-radius: 8px; font-weight: 500; border: none; cursor: pointer; transition: 0.3s;" onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-1px)'" onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)'">
                        Envoyer le message 🚀
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
