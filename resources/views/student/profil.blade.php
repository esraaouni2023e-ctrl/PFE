@extends('layouts.student')
@section('title', 'Mon Profil Académique')

@section('content')
<style>
:root{--ink:#0b0c10;--paper:#f7f5f0;--cream:#ede9e1;--warm:#e8e1d4;--accent:#d4622a;--accent2:#1a4f6e;--accent3:#4a7c59;--gold:#c8973a;--ink60:rgba(11,12,16,.6);--ink30:rgba(11,12,16,.3);--ink15:rgba(11,12,16,.15);--ink10:rgba(11,12,16,.1);--ink06:rgba(11,12,16,.06);--r:8px;--rl:16px;--rx:999px;--ease:cubic-bezier(.16,1,.3,1)}
.pr{font-family:'DM Sans',sans-serif;color:var(--ink);background:var(--paper);padding:2rem 2.5rem 5rem;max-width:1100px;margin:0 auto}
.pr *,.pr *::before,.pr *::after{box-sizing:border-box;margin:0;padding:0}
/* Layout */
.pr-layout{display:grid;grid-template-columns:300px 1fr;gap:1.75rem;align-items:start}
@media(max-width:900px){.pr-layout{grid-template-columns:1fr}}
/* Card générale */
.pr-card{background:var(--paper);border:1px solid var(--ink10);border-radius:var(--rl);overflow:hidden;margin-bottom:1.25rem}
.pr-card-head{padding:1.25rem 1.5rem;border-bottom:1px solid var(--ink10);background:var(--cream);display:flex;align-items:center;gap:.75rem}
.pr-card-head h2{font-family:'Fraunces',serif;font-size:1.1rem;font-weight:600;letter-spacing:-.02em}
.pr-card-body{padding:1.5rem}
/* Avatar block */
.pr-avatar-block{display:flex;flex-direction:column;align-items:center;text-align:center;padding:2rem 1.5rem}
.pr-avatar{width:96px;height:96px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:2.5rem;color:#fff;font-family:'Fraunces',serif;font-weight:600;margin-bottom:1rem;box-shadow:0 8px 24px color-mix(in srgb,var(--accent) 30%,transparent)}
.pr-name{font-family:'Fraunces',serif;font-size:1.4rem;font-weight:600;letter-spacing:-.02em;margin-bottom:.25rem}
.pr-email{font-size:.8rem;color:var(--ink60);margin-bottom:1.25rem}
/* Progression */
.pr-progress-label{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink30);margin-bottom:.5rem}
.pr-progress-bar{height:6px;background:var(--ink10);border-radius:var(--rx);overflow:hidden;margin-bottom:.35rem}
.pr-progress-fill{height:100%;border-radius:var(--rx);background:linear-gradient(90deg,var(--accent),var(--gold));transition:width 1s var(--ease)}
.pr-progress-pct{font-family:'Fraunces',serif;font-size:.9rem;font-weight:600;color:var(--accent)}
/* Score FG card */
.pr-fg-box{background:linear-gradient(135deg,color-mix(in srgb,var(--accent) 8%,transparent),color-mix(in srgb,var(--accent2) 5%,transparent));border:1px solid color-mix(in srgb,var(--accent) 18%,transparent);border-radius:var(--rl);padding:1.5rem;text-align:center;margin-bottom:1rem}
.pr-fg-num{font-family:'Fraunces',serif;font-size:3.5rem;font-weight:600;letter-spacing:-.06em;line-height:1;display:block;margin-bottom:.25rem}
.pr-fg-label{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--ink30);margin-bottom:.5rem}
.pr-fg-niveau{display:inline-flex;align-items:center;gap:.35rem;padding:.28rem .8rem;border-radius:var(--rx);font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em}
.pr-fg-updated{font-size:.68rem;color:var(--ink30);margin-top:.625rem}
/* Form */
.pr-field{margin-bottom:1.25rem}
.pr-label{display:block;font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink30);margin-bottom:.45rem}
.pr-input{width:100%;padding:.75rem 1rem;background:var(--cream);border:1px solid var(--ink15);border-radius:var(--r);color:var(--ink);font-family:'DM Sans',sans-serif;font-size:.9rem;transition:border-color .2s}
.pr-input:focus{outline:none;border-color:var(--accent)}
.pr-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.pr-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:.75rem}
/* Notes matières */
.pr-note-row{display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;background:var(--cream);border-radius:var(--r);border:1px solid var(--ink10);margin-bottom:.5rem}
.pr-note-label{flex:1;font-size:.82rem;font-weight:500;color:var(--ink60)}
.pr-note-coef{font-size:.68rem;font-weight:700;text-transform:uppercase;color:var(--accent);letter-spacing:.06em;flex-shrink:0;width:38px;text-align:center}
.pr-note-input{width:80px;padding:.45rem .75rem;background:var(--paper);border:1px solid var(--ink15);border-radius:var(--r);text-align:center;font-family:'Fraunces',serif;font-size:1rem;font-weight:600;color:var(--ink);transition:border-color .2s}
.pr-note-input:focus{outline:none;border-color:var(--accent)}
/* Save button */
.pr-btn{display:flex;align-items:center;justify-content:center;gap:.5rem;width:100%;padding:.9rem;background:var(--accent);color:#fff;border:none;border-radius:var(--r);font-family:'DM Sans',sans-serif;font-size:.92rem;font-weight:600;cursor:pointer;box-shadow:0 4px 16px color-mix(in srgb,var(--accent) 28%,transparent);transition:all .3s var(--ease)}
.pr-btn:hover{transform:translateY(-2px);box-shadow:0 8px 24px color-mix(in srgb,var(--accent) 38%,transparent)}
/* Alert */
.pr-alert{padding:.875rem 1.125rem;border-radius:var(--r);font-size:.85rem;font-weight:500;margin-bottom:1.25rem}
.pr-alert-success{background:color-mix(in srgb,var(--accent3) 8%,transparent);border:1px solid color-mix(in srgb,var(--accent3) 22%,transparent);color:var(--accent3)}
.pr-alert-error{background:color-mix(in srgb,#ef4444 8%,transparent);border:1px solid color-mix(in srgb,#ef4444 22%,transparent);color:#ef4444}
/* Textarea */
.pr-textarea{width:100%;padding:.75rem 1rem;background:var(--cream);border:1px solid var(--ink15);border-radius:var(--r);color:var(--ink);font-family:'DM Sans',sans-serif;font-size:.87rem;line-height:1.6;resize:vertical;min-height:80px;transition:border-color .2s}
.pr-textarea:focus{outline:none;border-color:var(--accent)}
</style>

<div class="pr">

    {{-- Page title --}}
    <div style="margin-bottom:2rem">
        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--accent);margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem">
            <span style="width:18px;height:1px;background:var(--accent);display:inline-block"></span>
            Espace Étudiant
        </div>
        <h1 style="font-family:'Fraunces',serif;font-size:2.5rem;font-weight:300;letter-spacing:-.04em;line-height:1.1">
            Mon profil <em style="font-style:italic;color:var(--accent)">académique</em>
        </h1>
    </div>

    @if(session('success'))
        <div class="pr-alert pr-alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="pr-alert pr-alert-error">{{ $errors->first() }}</div>
    @endif

    <div class="pr-layout">

        {{-- ══ SIDEBAR PROFIL ══ --}}
        <div>
            {{-- Avatar & Info --}}
            <div class="pr-card">
                <div class="pr-avatar-block">
                    <div class="pr-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div class="pr-name">{{ $user->name }}</div>
                    <div class="pr-email">{{ $user->email }}</div>
                    <div style="width:100%">
                        <div class="pr-progress-label">Complétude du profil</div>
                        <div class="pr-progress-bar">
                            <div class="pr-progress-fill" style="width:{{ $profile->progression }}%"></div>
                        </div>
                        <div class="pr-progress-pct">{{ $profile->progression }}% complété</div>
                    </div>
                </div>
            </div>

            {{-- Score FG --}}
            <div class="pr-card">
                <div class="pr-card-head">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.25rem;height:1.25rem;color:var(--accent);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2>Mon Score FG</h2>
                </div>
                <div class="pr-card-body">
                    @if($profile->score_fg)
                        <div class="pr-fg-box">
                            <div class="pr-fg-label">Score Formule Globale</div>
                            <span class="pr-fg-num" style="color:{{ $profile->couleur_fg }}">{{ number_format($profile->score_fg, 2) }}</span>
                            <div>
                                <span class="pr-fg-niveau"
                                    style="background:color-mix(in srgb,{{ $profile->couleur_fg }} 10%,transparent);border:1px solid color-mix(in srgb,{{ $profile->couleur_fg }} 25%,transparent);color:{{ $profile->couleur_fg }}">
                                    {{ $profile->niveau_fg }}
                                </span>
                            </div>
                            @if($profile->score_fg_updated_at)
                                <div class="pr-fg-updated">Mis à jour {{ $profile->score_fg_updated_at->diffForHumans() }}</div>
                            @endif
                        </div>
                        <a href="{{ route('student.whatif.index') }}"
                           style="display:flex;align-items:center;justify-content:center;gap:.4rem;padding:.75rem;border-radius:var(--r);background:var(--cream);border:1px solid var(--ink10);font-size:.82rem;font-weight:600;color:var(--ink60);text-decoration:none;transition:all .2s">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:1rem;height:1rem;">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v17.792M14.25 3.104v17.792M4.5 7.875h15M4.5 12h15M4.5 16.125h15" />
                           </svg>
                           Simuler un scénario
                        </a>
                    @else
                        <div style="text-align:center;padding:1.5rem;color:var(--ink30)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:2.5rem;height:2.5rem;margin: 0 auto .75rem; opacity:.4;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            <div style="font-size:.85rem;font-weight:500;margin-bottom:1rem">Score non encore calculé</div>
                            <p style="font-size:.78rem;color:var(--ink30);margin-bottom:1rem">Complétez vos notes ci-contre pour calculer votre score automatiquement.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Liens rapides --}}
            <div class="pr-card">
                <div class="pr-card-body" style="display:flex;flex-direction:column;gap:.5rem;">
                    <a href="{{ route('student.voeux.index') }}" style="display:flex;align-items:center;gap:.625rem;padding:.75rem .875rem;border-radius:var(--r);background:var(--cream);border:1px solid var(--ink10);font-size:.83rem;font-weight:500;color:var(--ink);text-decoration:none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.1rem;height:1.1rem;color:var(--accent);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                        Mes vœux d'orientation
                    </a>
                    <a href="{{ route('student.comparateur.index') }}" style="display:flex;align-items:center;gap:.625rem;padding:.75rem .875rem;border-radius:var(--r);background:var(--cream);border:1px solid var(--ink10);font-size:.83rem;font-weight:500;color:var(--ink);text-decoration:none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.1rem;height:1.1rem;color:var(--accent2);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                        Comparateur de filières
                    </a>
                    <a href="{{ route('student.whatif.historique') }}" style="display:flex;align-items:center;gap:.625rem;padding:.75rem .875rem;border-radius:var(--r);background:var(--cream);border:1px solid var(--ink10);font-size:.83rem;font-weight:500;color:var(--ink);text-decoration:none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.1rem;height:1.1rem;color:var(--ink30);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Historique des simulations
                    </a>
                </div>
            </div>
        </div>

        {{-- ══ FORMULAIRE PRINCIPAL ══ --}}
        <div>
            <form method="POST" action="{{ route('student.profil.update') }}">
                @csrf @method('PUT')

                {{-- Informations BAC --}}
                <div class="pr-card">
                    <div class="pr-card-head">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.25rem;height:1.25rem;color:var(--accent2);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147L12 15l7.74-4.853a4.5 4.5 0 00-2.122-3.933L12 3 6.382 6.214a4.5 4.5 0 00-2.122 3.933z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v7.5" />
                        </svg>
                        <h2>Baccalauréat</h2>
                    </div>
                    <div class="pr-card-body">
                        <div class="pr-grid-2">
                            <div class="pr-field">
                                <label class="pr-label" for="section_bac">Section BAC</label>
                                <select class="pr-input" id="section_bac" name="section_bac" required onchange="loadMatieres(this.value)">
                                    <option value="">— Choisissez —</option>
                                    @foreach($sections as $s)
                                        <option value="{{ $s }}" {{ $profile->section_bac === $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pr-field">
                                <label class="pr-label" for="annee_bac">Année du BAC</label>
                                <select class="pr-input" id="annee_bac" name="annee_bac" required>
                                    @for($y = date('Y') + 1; $y >= 2015; $y--)
                                        <option value="{{ $y }}" {{ ($profile->annee_bac ?? date('Y') + 1) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="pr-grid-2">
                            <div class="pr-field">
                                <label class="pr-label" for="moyenne_generale">Moyenne Générale</label>
                                <input type="number" class="pr-input" id="moyenne_generale" name="moyenne_generale"
                                    min="0" max="20" step="0.01"
                                    value="{{ old('moyenne_generale', $profile->moyenne_generale) }}"
                                    placeholder="Ex: 14.50" required>
                            </div>
                            <div class="pr-field">
                                <label class="pr-label" for="gouvernorat">Gouvernorat</label>
                                <select class="pr-input" id="gouvernorat" name="gouvernorat" required>
                                    <option value="">— Gouvernorat —</option>
                                    @foreach($gouvernorats as $g)
                                        <option value="{{ $g }}" {{ $profile->gouvernorat === $g ? 'selected' : '' }}>{{ $g }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notes par matière --}}
                <div class="pr-card">
                    <div class="pr-card-head">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.25rem;height:1.25rem;color:var(--accent3);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        <h2>Notes par matière</h2>
                    </div>
                    <div class="pr-card-body">
                        <div id="notesContainer">
                            @if($profile->section_bac && $profile->notes_matieres)
                                <div style="font-size:.82rem;color:var(--ink60);margin-bottom:1rem;font-style:italic">
                                    Notes pour la section : <strong>{{ $profile->section_bac }}</strong>
                                </div>
                                @php
                                    $matiereLabels = \App\Services\ScoreFGService::MATIERES_LABELS;
                                    $formules = [
                                        'Mathématiques' => ['math'=>2,'sp'=>1.5,'svt'=>0.5,'fr'=>1,'ang'=>1],
                                        'Sciences expérimentales' => ['math'=>1,'sp'=>1.5,'svt'=>1.5,'fr'=>1,'ang'=>1],
                                        'Économie et gestion' => ['eco'=>1.5,'gest'=>1.5,'math'=>0.5,'hg'=>0.5,'fr'=>1,'ang'=>1],
                                        'Technique' => ['tech'=>1.5,'math'=>1.5,'sp'=>1,'fr'=>1,'ang'=>1],
                                        'Informatique' => ['algo'=>1.5,'sp'=>0.5,'sti'=>0.5,'fr'=>1,'ang'=>1],
                                        'Lettres' => ['ar'=>1.5,'philo'=>1.5,'hg'=>1,'fr'=>1,'ang'=>1],
                                        'Sport' => ['bio'=>1.5,'sport'=>1,'ep'=>0.5,'sp'=>0.5,'ph'=>0.5,'fr'=>1,'ang'=>1],
                                    ];
                                    $section_matieres = $formules[$profile->section_bac] ?? [];
                                @endphp
                                @foreach($section_matieres as $code => $coef)
                                    <div class="pr-note-row">
                                        <div class="pr-note-label">{{ $matiereLabels[$code] ?? $code }}</div>
                                        <div class="pr-note-coef">×{{ $coef }}</div>
                                        <input type="number" class="pr-note-input"
                                            name="notes_matieres[{{ $code }}]"
                                            min="0" max="20" step="0.25"
                                            value="{{ old('notes_matieres.'.$code, $profile->notes_matieres[$code] ?? '') }}"
                                            placeholder="—">
                                    </div>
                                @endforeach
                            @else
                                <div id="notesPlaceholder" style="text-align:center;padding:2rem;color:var(--ink30);font-size:.85rem">
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18' /></svg> Sélectionnez d'abord votre section BAC pour saisir vos notes
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Intérêts & Compétences --}}
                <div class="pr-card">
                    <div class="pr-card-head">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.25rem;height:1.25rem;color:var(--gold);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.503 7.503 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                        </svg>
                        <h2>Intérêts & Compétences</h2>
                    </div>
                    <div class="pr-card-body">
                        <div class="pr-field">
                            <label class="pr-label" for="interests">Centres d'intérêt</label>
                            <textarea class="pr-textarea" id="interests" name="interests" placeholder="Ex: Informatique, IA, entrepreneuriat, sport…">{{ old('interests', $profile->interests) }}</textarea>
                        </div>
                        <div class="pr-field">
                            <label class="pr-label" for="skills">Compétences & Atouts</label>
                            <textarea class="pr-textarea" id="skills" name="skills" placeholder="Ex: Programmation Python, leadership, langues étrangères…">{{ old('skills', $profile->skills) }}</textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" class="pr-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:1.2rem;height:1.2rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0113.186 0z" />
                    </svg>
                    Sauvegarder et recalculer mon Score FG
                </button>
            </form>
        </div>

    </div>
</div>

<script>
async function loadMatieres(section) {
    const container = document.getElementById('notesContainer');
    if (!section) {
        container.innerHTML = '<div id="notesPlaceholder" style="text-align:center;padding:2rem;color:var(--ink30);font-size:.85rem"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18' /></svg> Sélectionnez d\'abord votre section BAC</div>';
        return;
    }
    container.innerHTML = '<div style="padding:1rem;text-align:center;color:var(--ink30)">Chargement…</div>';
    const res = await fetch(`/student/whatif/matieres?section=${encodeURIComponent(section)}`);
    const data = await res.json();
    const matieres = data.matieres;
    const existing = @json($profile->notes_matieres ?? []);

    container.innerHTML = `<div style="font-size:.82rem;color:var(--ink60);margin-bottom:1rem;font-style:italic">Notes pour la section : <strong>${section}</strong></div>`;
    Object.entries(matieres).forEach(([code, info]) => {
        const div = document.createElement('div');
        div.className = 'pr-note-row';
        div.innerHTML = `
            <div class="pr-note-label">${info.label}</div>
            <div class="pr-note-coef">×${info.coef}</div>
            <input type="number" class="pr-note-input" name="notes_matieres[${code}]"
                min="0" max="20" step="0.25"
                value="${existing[code] ?? ''}" placeholder="—">
        `;
        container.appendChild(div);
    });
}
</script>
@endsection
