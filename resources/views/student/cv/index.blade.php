@extends('layouts.student')
@section('title', 'Mes CV — CapAvenir')

@section('content')
<style>
    .cv-page { max-width: 1100px; margin: 0 auto; padding: 2.5rem 1.5rem 4rem; }
    .cv-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
    .cv-header h1 { font-family: var(--font-serif); font-size: 1.8rem; font-weight: 600; }
    .cv-header-sub { color: var(--ink60); font-size: .88rem; margin-top: .25rem; }

    .btn-create-cv {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .7rem 1.4rem; background: var(--accent); color: #fff;
        border: none; border-radius: var(--r); cursor: pointer;
        font-family: var(--font-main); font-size: .88rem; font-weight: 600;
        text-decoration: none; transition: var(--transition);
        box-shadow: 0 4px 16px color-mix(in srgb, var(--accent) 30%, transparent);
    }
    .btn-create-cv:hover { transform: translateY(-2px); box-shadow: 0 8px 24px color-mix(in srgb, var(--accent) 40%, transparent); }

    /* CV Cards Grid */
    .cv-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.25rem; }

    .cv-card {
        background: var(--ink06); border: 1px solid var(--glass-border);
        border-radius: var(--rl); padding: 1.5rem; position: relative;
        transition: border-color .3s var(--ease), transform .3s var(--ease);
    }
    .cv-card:hover { border-color: var(--glass-border-vivid); transform: translateY(-3px); }

    .cv-card-title { font-weight: 700; font-size: 1.05rem; margin-bottom: .35rem; color: var(--ink); }
    .cv-card-template {
        display: inline-flex; align-items: center; gap: .3rem;
        font-size: .72rem; font-weight: 600; color: var(--accent);
        background: color-mix(in srgb, var(--accent) 10%, transparent);
        padding: .15rem .55rem; border-radius: var(--rx);
        text-transform: uppercase; letter-spacing: .04em;
    }
    .cv-card-meta { display: flex; gap: 1rem; margin-top: .75rem; flex-wrap: wrap; }
    .cv-card-stat {
        display: flex; align-items: center; gap: .3rem;
        font-size: .78rem; color: var(--ink60);
    }
    .cv-card-stat strong { color: var(--ink); font-weight: 700; }

    .cv-card-date { font-size: .72rem; color: var(--ink30); margin-top: .75rem; }

    .cv-card-actions {
        display: flex; gap: .4rem; margin-top: 1rem; padding-top: 1rem;
        border-top: 1px solid var(--ink10); flex-wrap: wrap;
    }
    .cv-action {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .4rem .7rem; font-size: .75rem; font-weight: 600;
        border-radius: var(--r); cursor: pointer; text-decoration: none;
        transition: var(--transition); font-family: var(--font-main);
        border: 1px solid var(--glass-border); background: var(--ink06); color: var(--ink60);
    }
    .cv-action:hover { color: var(--ink); border-color: var(--ink30); background: var(--ink10); }
    .cv-action.primary { background: var(--accent2); color: #fff; border-color: var(--accent2); }
    .cv-action.primary:hover { opacity: .88; }
    .cv-action.danger { color: #ef4444; border-color: color-mix(in srgb, #ef4444 25%, transparent); }
    .cv-action.danger:hover { background: color-mix(in srgb, #ef4444 10%, transparent); }
    .cv-action.pdf { color: #dc2626; border-color: color-mix(in srgb, #dc2626 25%, transparent); }
    .cv-action.docx { color: var(--accent2); border-color: color-mix(in srgb, var(--accent2) 25%, transparent); }

    /* Empty state */
    .cv-empty {
        text-align: center; padding: 4rem 2rem;
        background: var(--ink06); border: 2px dashed var(--ink15);
        border-radius: var(--rl);
    }
    .cv-empty-icon { font-size: 3rem; margin-bottom: 1rem; opacity: .6; }
    .cv-empty h3 { font-family: var(--font-serif); font-size: 1.3rem; margin-bottom: .5rem; }
    .cv-empty p { color: var(--ink60); font-size: .9rem; max-width: 400px; margin: 0 auto 1.5rem; }

    /* Flash message */
    .flash-success {
        background: color-mix(in srgb, var(--accent3) 10%, transparent);
        border: 1px solid color-mix(in srgb, var(--accent3) 30%, transparent);
        color: var(--accent3); padding: .65rem 1rem; border-radius: var(--r);
        font-size: .85rem; font-weight: 600; margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: .5rem;
    }

    @media (max-width: 640px) {
        .cv-grid { grid-template-columns: 1fr; }
        .cv-header { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="cv-page">
    {{-- Header --}}
    <div class="cv-header">
        <div>
            <h1>📄 Mes CV</h1>
            <div class="cv-header-sub">Créez, modifiez et exportez vos CV professionnels</div>
        </div>
        <a href="{{ route('student.cv.create') }}" class="btn-create-cv">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouveau CV
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="flash-success">✅ {{ session('success') }}</div>
    @endif

    {{-- CV List --}}
    @if($cvProfiles->isEmpty())
        <div class="cv-empty">
            <div class="cv-empty-icon">📝</div>
            <h3>Aucun CV pour le moment</h3>
            <p>Commencez par créer votre premier CV professionnel. Remplissez vos informations et téléchargez-le en PDF ou DOCX.</p>
            <a href="{{ route('student.cv.create') }}" class="btn-create-cv">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Créer mon premier CV
            </a>
        </div>
    @else
        <div class="cv-grid">
            @foreach($cvProfiles as $cv)
                <div class="cv-card">
                    <div class="cv-card-title">{{ $cv->title }}</div>
                    <div class="cv-card-template">🎨 {{ ucfirst($cv->template_name) }}</div>

                    <div class="cv-card-meta">
                        <div class="cv-card-stat">💼 <strong>{{ $cv->experiences_count }}</strong> exp.</div>
                        <div class="cv-card-stat">🎓 <strong>{{ $cv->educations_count }}</strong> form.</div>
                        <div class="cv-card-stat">⚡ <strong>{{ $cv->skills_count }}</strong> comp.</div>
                        <div class="cv-card-stat">🌐 <strong>{{ $cv->languages_count }}</strong> lang.</div>
                    </div>

                    <div class="cv-card-date">Modifié le {{ $cv->updated_at->format('d/m/Y à H:i') }}</div>

                    <div class="cv-card-actions">
                        <div style="display:flex;gap:.4rem;flex:1;flex-wrap:wrap;">
                            <a href="{{ route('student.cv.pdf', $cv) }}" class="cv-action pdf" title="Télécharger en PDF">
                                📕 <strong>Télécharger PDF</strong>
                            </a>
                            <a href="{{ route('student.cv.docx', $cv) }}" class="cv-action docx" title="Télécharger en DOCX">
                                📘 <strong>DOCX</strong>
                            </a>
                        </div>
                        <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                            <a href="{{ route('student.cv.preview', $cv) }}" class="cv-action" target="_blank">👁️ Aperçu</a>
                            <a href="{{ route('student.cv.edit', $cv) }}" class="cv-action primary">✏️ Modifier</a>
                            <form action="{{ route('student.cv.duplicate', $cv) }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit" class="cv-action">📋</button>
                            </form>
                            <form action="{{ route('student.cv.destroy', $cv) }}" method="POST" style="display:inline"
                                  onsubmit="return confirm('Supprimer ce CV ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="cv-action danger">🗑️</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
