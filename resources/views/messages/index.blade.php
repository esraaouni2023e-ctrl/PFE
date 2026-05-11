@extends(auth()->user()->isStudent() ? 'layouts.student' : 'layouts.counselor')

@section('title', 'Messagerie')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 2rem;">

    <div class="glass-card" style="background: var(--cream); border: 1px solid var(--ink10); padding: 1.75rem 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2rem; border-radius: var(--rl);">
        <div>
            <h3 style="font-size:1.25rem;font-weight:900;color:var(--ink);letter-spacing:-0.01em;">Boîte de réception</h3>
            <p style="font-size:0.82rem;color:var(--ink60);margin-top:0.3rem;">Gérez vos échanges avec {{ auth()->user()->isStudent() ? 'vos conseillers' : 'vos étudiants' }}.</p>
        </div>
        <div style="display:flex; align-items:center; gap:2.5rem;">
            <div style="display:flex;gap:2.5rem; margin-right: 1.5rem;">
                <div style="text-align:center;">
                    <div style="font-size:1.75rem;font-weight:900;color:var(--ink);line-height:1;">{{ $messages->total() }}</div>
                    <div style="font-size:0.65rem;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:var(--accent);margin-top:0.4rem;">Total</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:1.75rem;font-weight:900;color:var(--ink);line-height:1;">{{ auth()->user()->receiverMessages()->unread()->count() }}</div>
                    <div style="font-size:0.65rem;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:var(--accent);margin-top:0.4rem;">Non lus</div>
                </div>
            </div>
            <a href="{{ route('messages.create') }}" class="btn-fill" style="padding: 10px 20px; font-size: 0.85rem; background: var(--accent); color: white; border-radius: 8px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: 0.3s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                <span style="font-size: 1.1rem;">+</span> Nouveau Message
            </a>
        </div>
    </div>

    @if(session('status'))
        <div style="background: color-mix(in srgb, var(--accent3) 10%, transparent); color: var(--accent3); padding: 1rem; border-radius: var(--r); border: 1px solid color-mix(in srgb, var(--accent3) 25%, transparent); margin-bottom: 2rem; font-size: 0.85rem; font-weight: 600;">
            ✅ {{ session('status') }}
        </div>
    @endif

    <div class="glass-card" style="padding:0; overflow:hidden; border-radius: var(--rl); background: var(--paper); border: 1px solid var(--ink10);">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:rgba(255,255,255,0.03);">
                        <th style="padding:1.1rem 2rem;text-align:left;font-size:0.68rem;font-weight:800;color:var(--ink);text-transform:uppercase;letter-spacing:0.12em;">Expéditeur</th>
                        <th style="padding:1.1rem 2rem;text-align:left;font-size:0.68rem;font-weight:800;color:var(--ink);text-transform:uppercase;letter-spacing:0.12em;">Sujet</th>
                        <th style="padding:1.1rem 2rem;text-align:left;font-size:0.68rem;font-weight:800;color:var(--ink);text-transform:uppercase;letter-spacing:0.12em;">Date</th>
                        <th style="padding:1.1rem 2rem;text-align:right;font-size:0.68rem;font-weight:800;color:var(--ink);text-transform:uppercase;letter-spacing:0.12em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $msg)
                    <tr style="border-top:1px solid var(--ink10); transition:0.2s; {{ !$msg->is_read ? 'background:rgba(212, 98, 42, 0.05);' : '' }}"
                        onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='{{ !$msg->is_read ? 'rgba(212, 98, 42, 0.05)' : 'transparent' }}'">
                        <td style="padding:1.25rem 2rem;">
                            <div style="display:flex; align-items:center; gap:0.75rem;">
                                <div style="width:36px; height:36px; border-radius:50%; background:{{ !$msg->is_read ? 'var(--accent)' : 'var(--ink10)' }}; color:white; display:flex; align-items:center; justify-content:center; font-size:0.85rem; font-weight:900; position:relative;">
                                    {{ strtoupper(substr($msg->sender->name, 0, 1)) }}
                                    @if(!$msg->is_read)
                                        <div style="position:absolute; top:0; right:0; width:10px; height:10px; background:var(--accent); border:2px solid var(--paper); border-radius:50%;"></div>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-size:0.85rem; color:var(--ink); font-weight:{{ !$msg->is_read ? '800' : '500' }};">{{ $msg->sender->name }}</div>
                                    <div style="font-size:0.7rem; color:var(--ink30);">{{ $msg->sender_email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:1.25rem 2rem;">
                            <a href="{{ route('messages.show', $msg) }}" style="text-decoration:none; display:block;">
                                <div style="font-size:0.88rem; color:var(--ink); font-weight:{{ !$msg->is_read ? '900' : '600' }}; margin-bottom:0.15rem;">{{ $msg->subject }}</div>
                                <div style="font-size:0.75rem; color:var(--ink60); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:320px;">{{ Str::limit($msg->body, 60) }}</div>
                            </a>
                        </td>
                        <td style="padding:1.25rem 2rem;">
                            <div style="font-size:0.78rem; color:var(--ink60); font-weight:500;">{{ $msg->created_at->timezone('Africa/Tunis')->format('d M, H:i') }}</div>
                        </td>
                        <td style="padding:1.25rem 2rem; text-align:right;">
                            <div style="display:flex; justify-content:flex-end; align-items:center; gap:0.6rem;">
                                <a href="{{ route('messages.show', $msg) }}" class="btn-glass" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; padding: 0; font-size: 0.9rem; border-radius: 6px; border: 1px solid var(--ink10); background: transparent; color: var(--ink60); text-decoration: none; transition: 0.2s;" title="Voir le message" onmouseover="this.style.background='var(--ink06)'; this.style.color='var(--accent)'" onmouseout="this.style.background='transparent'; this.style.color='var(--ink60)'">
                                    👁
                                </a>
                                <form action="{{ route('messages.destroy', $msg) }}" method="POST" onsubmit="return confirm('Supprimer ce message définitivement ?');" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-glass" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; padding: 0; font-size: 0.85rem; border-radius: 6px; border: 1px solid var(--ink10); background: transparent; color: #ef4444; cursor: pointer; transition: 0.2s;" title="Supprimer" onmouseover="this.style.background='rgba(239, 68, 68, 0.05)'; this.style.borderColor='rgba(239, 68, 68, 0.2)'" onmouseout="this.style.background='transparent'; this.style.borderColor='var(--ink10)'">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding:4rem; text-align:center; color:var(--ink30);">
                            <div style="font-size:2.5rem; margin-bottom:1rem;">📬</div>
                            Votre boîte est vide.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="padding:1.5rem 2rem; border-top:1px solid var(--ink10);">
            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection
