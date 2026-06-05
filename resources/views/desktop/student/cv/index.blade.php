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
            <h1 style="display:flex; align-items:center; gap:.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:2rem;height:2rem;color:var(--accent2);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                Mes CV
            </h1>
            <div class="cv-header-sub">Créez, modifiez et exportez vos CV professionnels</div>
        </div>
        <a href="{{ route('student.cv.create') }}" class="btn-create-cv">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouveau CV
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="flash-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1.25rem;height:1.25rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- CV List --}}
    @if($cvProfiles->isEmpty())
        <div class="cv-empty">
            <div class="cv-empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:4rem;height:4rem;margin:0 auto; opacity:.4;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </div>
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
                    <div class="cv-card-template">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.75rem;height:.75rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-3.015-3.015 3 3 0 00-3.015 3.015 3 3 0 003.015 3.015 3 3 0 003.015-3.015zM17.03 16.122a3 3 0 00-3.015-3.015 3 3 0 00-3.015 3.015 3 3 0 003.015 3.015 3 3 0 003.015-3.015zM13.28 10.122a3 3 0 00-3.015-3.015 3 3 0 00-3.015 3.015 3 3 0 003.015 3.015 3 3 0 003.015-3.015z" />
                        </svg>
                        {{ ucfirst($cv->template_name) }}
                    </div>

                    <div class="cv-card-meta">
                        <div class="cv-card-stat">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:.85rem;height:.85rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .621-.504 1.125-1.125 1.125H4.875c-.621 0-1.125-.504-1.125-1.125v-4.25m16.5 0a2.25 2.25 0 00-2.25-2.25H5.625a2.25 2.25 0 00-2.25 2.25m16.5 0V9.45c0-.621-.504-1.125-1.125-1.125h-1.35m-14.4 0h1.35m14.4 0V5.25c0-.621-.504-1.125-1.125-1.125H5.625c-.621 0-1.125.504-1.125 1.125v2.925m14.4 0H5.625" />
                            </svg>
                            <strong>{{ $cv->experiences_count }}</strong> exp.
                        </div>
                        <div class="cv-card-stat">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:.85rem;height:.85rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147L12 15l7.74-4.853a4.5 4.5 0 00-2.122-3.933L12 3 6.382 6.214a4.5 4.5 0 00-2.122 3.933z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v7.5" />
                            </svg>
                            <strong>{{ $cv->educations_count }}</strong> form.
                        </div>
                        <div class="cv-card-stat">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:.85rem;height:.85rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                            <strong>{{ $cv->skills_count }}</strong> comp.
                        </div>
                        <div class="cv-card-stat">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:.85rem;height:.85rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9s2.015-9 4.5-9m0 0a9.015 9.015 0 018.716 6.747M12 3a9.015 9.015 0 00-8.716 6.747m17.432 0c.11.526.168 1.07.168 1.628 0 4.846-3.92 8.772-8.75 8.772-4.83 0-8.75-3.926-8.75-8.772 0-.558.058-1.102.168-1.628m17.432 0a8.74 8.74 0 01-17.432 0m17.432 0c-.674 2.04-2.186 3.758-4.032 4.796m4.032-4.796c.674-2.04 2.186-3.758 4.032-4.796" />
                            </svg>
                            <strong>{{ $cv->languages_count }}</strong> lang.
                        </div>
                    </div>

                    <div class="cv-card-date">Modifié le {{ $cv->updated_at->format('d/m/Y à H:i') }}</div>

                    <div class="cv-card-actions">
                        <div style="display:flex;gap:.4rem;flex:1;flex-wrap:wrap;">
                            <a href="{{ route('student.cv.pdf', $cv) }}" class="cv-action pdf" title="Télécharger en PDF">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.85rem;height:.85rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <strong>PDF</strong>
                            </a>
                            <a href="{{ route('student.cv.docx', $cv) }}" class="cv-action docx" title="Télécharger en DOCX">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.85rem;height:.85rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <strong>DOCX</strong>
                            </a>
                        </div>
                        <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                            <a href="{{ route('student.cv.preview', $cv) }}" class="cv-action" target="_blank" title="Aperçu">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.85rem;height:.85rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>
                            <a href="{{ route('student.cv.edit', $cv) }}" class="cv-action primary" title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.85rem;height:.85rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            <form action="{{ route('student.cv.duplicate', $cv) }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit" class="cv-action" title="Dupliquer">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.85rem;height:.85rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                                    </svg>
                                </button>
                            </form>
                            <form action="{{ route('student.cv.destroy', $cv) }}" method="POST" style="display:inline"
                                  onsubmit="return confirm('Supprimer ce CV ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="cv-action danger" title="Supprimer">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:.85rem;height:.85rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
