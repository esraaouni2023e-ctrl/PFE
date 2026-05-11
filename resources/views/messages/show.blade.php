@extends(auth()->user()->isStudent() ? 'layouts.student' : 'layouts.counselor')

@section('title', 'Détail du Message')

@section('content')
    <div style="max-width: 800px; margin: 0 auto; padding: 2rem;">

        <a href="{{ route('messages.index') }}"
            style="display:inline-flex; align-items:center; gap:0.5rem; text-decoration:none; color:var(--ink60); font-size:0.85rem; font-weight:600; margin-bottom:2rem; transition:0.2s;"
            onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--ink60)'">
            ← Retour à la boîte de réception
        </a>

        <div class="glass-card"
            style="background: var(--paper); border: 1px solid var(--ink10); border-radius: var(--rl); overflow:hidden;">
            <div
                style="padding:1.75rem 2.5rem; border-bottom:1px solid var(--ink10); background:var(--cream); display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:1.5rem; flex:1;">
                    <div>
                        <h2
                            style="font-family:'Fraunces', serif; font-size:1.5rem; color:var(--ink); margin-bottom:0.5rem;">
                            {{ $message->subject }}</h2>
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <div
                                style="width:36px; height:36px; border-radius:50%; background:var(--accent); color:white; display:flex; align-items:center; justify-content:center; font-size:0.9rem; font-weight:900;">
                                {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size:0.9rem; font-weight:700; color:var(--ink);">
                                    {{ $message->sender->name }}</div>
                                <div style="font-size:0.75rem; color:var(--ink60);">{{ $message->sender_email }}</div>
                            </div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div
                            style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--ink30); letter-spacing:0.05em; margin-bottom:0.25rem;">
                            Reçu le</div>
                        <div style="font-size:0.85rem; color:var(--ink60);">
                            {{ $message->created_at->timezone('Africa/Tunis')->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
                <div style="margin-left: 2rem;">
                    <form action="{{ route('messages.destroy', $message) }}" method="POST"
                        onsubmit="return confirm('Supprimer ce message définitivement ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-glass"
                            style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; padding: 0; font-size: 0.9rem; border-radius: 8px; border: 1px solid var(--ink10); background: transparent; color: #ef4444; cursor: pointer; transition: 0.2s;"
                            title="Supprimer"
                            onmouseover="this.style.background='rgba(239, 68, 68, 0.05)'; this.style.borderColor='rgba(239, 68, 68, 0.2)'"
                            onmouseout="this.style.background='transparent'; this.style.borderColor='var(--ink10)'">
                            🗑️
                        </button>
                    </form>
                </div>
            </div>

            <div
                style="padding:2.5rem; font-size:0.95rem; line-height:1.7; color:var(--ink); white-space:pre-wrap; word-break:break-word;">
                {{ $message->body }}</div>

            <div style="padding:2rem 2.5rem; background:var(--cream); border-top:1px solid var(--ink10);">
                <h4 style="font-family:'Fraunces', serif; font-size:1.1rem; margin-bottom:1.5rem;">Répondre à ce message
                </h4>

                <form action="{{ route('messages.reply', $message) }}" method="POST">
                    @csrf
                    <textarea name="body" rows="4" placeholder="Tapez votre réponse ici..." required
                        style="width: 100%; padding: 1rem; border-radius: var(--r); border: 1px solid var(--ink10); background: var(--paper); color: var(--ink); font-family: var(--font-main); font-size:0.9rem; outline: none; transition: 0.3s; resize: vertical;"
                        onfocus="this.style.borderColor='var(--accent)'"
                        onblur="this.style.borderColor='var(--ink10)'"></textarea>

                    <div style="text-align:right; margin-top:1.5rem;">
                        <button type="submit" class="btn-fill"
                            style="padding: 12px 28px; font-size: 0.85rem; background: var(--accent); color: white; border-radius: 8px; font-weight: 500; border: none; cursor: pointer; transition: 0.3s;"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                            Envoyer la réponse ✉️
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection