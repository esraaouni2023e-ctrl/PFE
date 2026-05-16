@extends('layouts.student')
@section('title', 'Passer le Test — CapAvenir')

@section('content')
<style>
.tp{padding:2.5rem 2.5rem 4rem;max-width:860px;margin:0 auto;font-family:var(--font-main)}
.tp-eye{display:inline-flex;align-items:center;gap:.5rem;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--accent);margin-bottom:1rem}
.tp-eye::before{content:'';width:14px;height:1px;background:var(--accent)}
.tp-title{font-family:var(--font-serif);font-size:clamp(2rem,4vw,2.8rem);font-weight:300;letter-spacing:-.04em;font-style:italic;color:var(--ink);line-height:1.1;margin-bottom:.85rem}
.tp-title em{color:var(--accent);font-style:italic}
.tp-sub{font-size:.92rem;color:var(--ink60);max-width:600px;line-height:1.7;margin-bottom:2rem}
.tp-stats{display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:2.5rem}
.tp-stat{display:flex;align-items:center;gap:.45rem;padding:.4rem .85rem;border-radius:var(--rx);background:var(--ink06);border:1px solid var(--glass-border);font-size:.75rem;font-weight:600;color:var(--ink60)}
.tp-stat strong{color:var(--ink)}
.tp-tabs{display:flex;border-bottom:2px solid var(--ink10);margin-bottom:2rem}
.tp-tab{flex:1;padding:1rem 1.5rem;font-size:.82rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--ink30);background:none;border:none;cursor:pointer;transition:all .25s;position:relative;display:flex;align-items:center;justify-content:center;gap:.6rem}
.tp-tab:hover{color:var(--ink60);background:var(--ink06)}
.tp-tab.active{color:var(--accent)}
.tp-tab.active::after{content:'';position:absolute;bottom:-2px;left:0;right:0;height:2px;background:var(--accent);border-radius:2px 2px 0 0}
.tp-tab-n{width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.68rem;font-weight:800;background:var(--ink10);color:var(--ink30)}
.tp-tab.active .tp-tab-n{background:var(--accent);color:#fff}
.tp-tab.done .tp-tab-n{background:var(--accent3);color:#fff}
.tp-panel{display:none}
.tp-panel.active{display:block;animation:tpUp .4s ease both}
@keyframes tpUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
.tp-card{background:var(--ink06);border:1px solid var(--glass-border);border-radius:var(--rl);padding:2.5rem;margin-bottom:2rem;box-shadow:0 10px 40px rgba(11,12,16,.04)}
.tp-field{margin-bottom:1.5rem}
.tp-label{display:block;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink60);margin-bottom:.5rem}
.tp-input{width:100%;padding:.85rem 1rem;background:color-mix(in srgb,var(--paper) 88%,#fff 12%);border:1px solid var(--glass-border);border-radius:var(--r);font-family:var(--font-main);font-size:.95rem;color:var(--ink);transition:border-color .2s}
.tp-input:focus{outline:none;border-color:var(--accent)}
.tp-g2{display:grid;grid-template-columns:1fr 1fr;gap:1.2rem}
@media(max-width:600px){.tp-g2{grid-template-columns:1fr}}
.tp-nr{display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;background:color-mix(in srgb,var(--paper) 88%,#fff 12%);border-radius:var(--r);border:1px solid var(--glass-border);margin-bottom:.5rem}
.tp-nr-l{flex:1;font-size:.85rem;font-weight:500;color:var(--ink)}
.tp-nr-c{font-size:.7rem;font-weight:700;color:var(--accent);width:35px;text-align:center}
.tp-nr-i{width:80px;padding:.5rem .75rem;background:var(--paper);border:1px solid var(--ink15);border-radius:var(--r);text-align:center;font-family:var(--font-serif);font-size:1.05rem;font-weight:600;color:var(--ink);transition:border-color .2s}
.tp-nr-i:focus{outline:none;border-color:var(--accent)}
.tp-blocs{display:grid;grid-template-columns:repeat(2,1fr);gap:.85rem;margin-bottom:2rem}
@media(max-width:600px){.tp-blocs{grid-template-columns:1fr}}
.tp-bloc{background:var(--ink06);border:1px solid var(--glass-border);border-radius:var(--rl);padding:1.1rem 1.2rem;transition:border-color .25s,transform .25s}
.tp-bloc:hover{border-color:var(--glass-border-vivid);transform:translateY(-2px)}
.tp-bloc-top{display:flex;align-items:center;gap:.6rem;margin-bottom:.5rem}
.tp-bloc-ic{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.78rem;color:#fff;flex-shrink:0}
.tp-bloc-nm{font-size:.82rem;font-weight:700;color:var(--ink)}
.tp-bloc-ds{font-size:.72rem;color:var(--ink30);line-height:1.5}
.tp-instr{display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:2rem}
@media(max-width:540px){.tp-instr{grid-template-columns:1fr}}
.tp-ins{display:flex;align-items:flex-start;gap:.6rem;padding:1rem;background:var(--ink06);border:1px solid var(--glass-border);border-radius:var(--r)}
.tp-ins-ic{flex-shrink:0;width:28px;height:28px;border-radius:8px;background:color-mix(in srgb,var(--accent) 10%,transparent);display:flex;align-items:center;justify-content:center}
.tp-ins-tx{font-size:.82rem;color:var(--ink60);line-height:1.6}
.tp-ins-tx strong{color:var(--ink)}
.tp-cta{display:flex;align-items:center;justify-content:space-between;gap:1rem;border-top:1px solid var(--ink06);padding-top:1.5rem;margin-top:2rem}
.tp-resume{background:color-mix(in srgb,var(--accent) 6%,transparent);border:1px solid color-mix(in srgb,var(--accent) 22%,transparent);border-radius:var(--rl);padding:1.1rem 1.4rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:2rem}
.tp-resume-txt{font-size:.85rem;color:var(--ink60)}
.tp-resume-txt strong{color:var(--ink)}
.tp-resume-acts{display:flex;gap:.6rem}
.btn-fill{display:inline-flex;align-items:center;gap:.5rem;padding:.9rem 2.2rem;font-family:var(--font-main);font-size:.95rem;font-weight:600;color:#fff;background:linear-gradient(135deg,var(--accent),var(--accent2));border:none;border-radius:var(--r);cursor:pointer;text-decoration:none;box-shadow:0 4px 18px color-mix(in srgb,var(--accent) 30%,transparent);transition:transform .25s var(--ease),box-shadow .25s var(--ease)}
.btn-fill:hover{transform:translateY(-2px);box-shadow:0 8px 28px color-mix(in srgb,var(--accent) 42%,transparent)}
.btn-ghost{display:inline-flex;align-items:center;gap:.5rem;padding:.75rem 1.4rem;font-family:var(--font-main);font-size:.84rem;font-weight:600;color:var(--ink60);background:transparent;border:1px solid var(--glass-border);border-radius:var(--r);cursor:pointer;text-decoration:none;transition:all .2s}
.btn-ghost:hover{color:var(--ink);border-color:var(--ink30);background:var(--ink06)}
.tp-alert{padding:.875rem 1.125rem;border-radius:var(--r);font-size:.85rem;font-weight:500;margin-bottom:1.5rem;background:color-mix(in srgb,var(--accent) 8%,transparent);border:1px solid color-mix(in srgb,var(--accent) 22%,transparent);color:var(--accent)}
.tp-alert.error{color:#ef4444;border-color:color-mix(in srgb,#ef4444 22%,transparent);background:color-mix(in srgb,#ef4444 8%,transparent)}
</style>

<div class="tp">
    <p class="tp-eye">Test d'Orientation · CapAvenir</p>
    <h1 class="tp-title">Passer le <em>test</em> d'orientation</h1>
    <p class="tp-sub">Complète ton profil académique puis réponds au test psychométrique pour obtenir des recommandations de filières précises et personnalisées.</p>

    <div class="tp-stats">
        <div class="tp-stat"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--accent3)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg><strong>2</strong> étapes</div>
        <div class="tp-stat"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><strong>~20</strong> min</div>
        <div class="tp-stat"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--accent2)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg><strong>4</strong> blocs</div>
        <div class="tp-stat"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg><strong>100%</strong> confidentiel</div>
    </div>

    @if(session('info'))<div class="tp-alert">{{ session('info') }}</div>@endif
    @if($errors->any())<div class="tp-alert error">{{ $errors->first() }}</div>@endif

    {{-- TABS --}}
    <div class="tp-tabs">
        <button class="tp-tab {{ ($activeTab ?? 'score') === 'score' ? 'active' : '' }} {{ ($hasScore ?? false) ? 'done' : '' }}" data-tab="score" type="button">
            <span class="tp-tab-n">1</span> Profil Académique
        </button>
        <button class="tp-tab {{ ($activeTab ?? 'score') === 'psycho' ? 'active' : '' }}" data-tab="psycho" type="button">
            <span class="tp-tab-n">2</span> Test Psychométrique
        </button>
    </div>

    {{-- TAB 1 : PROFIL ACADÉMIQUE --}}
    <div class="tp-panel {{ ($activeTab ?? 'score') === 'score' ? 'active' : '' }}" id="panel-score">
        <form action="{{ route('student.pipeline.storeStep1') }}" method="POST">
            @csrf
            @php $currentSection = old('section_bac', $profile->section_bac ?? ''); @endphp
            <div class="tp-card">
                <div class="tp-g2">
                    <div class="tp-field">
                        <label class="tp-label" for="section_bac">Section du Bac</label>
                        <select class="tp-input" id="section_bac" name="section_bac" required onchange="loadMatieres(this.value)">
                            <option value="">— Choisissez votre section —</option>
                            @foreach($sections as $s)
                                <option value="{{ $s }}" {{ $currentSection === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="tp-field">
                        <label class="tp-label" for="annee_bac">Année d'obtention</label>
                        <select class="tp-input" id="annee_bac" name="annee_bac" required>
                            @for($y = date('Y') + 1; $y >= 2015; $y--)
                                <option value="{{ $y }}" {{ ($profile->annee_bac ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="tp-g2">
                    <div class="tp-field">
                        <label class="tp-label" for="moyenne_generale">Moyenne Générale</label>
                        <input type="number" class="tp-input" id="moyenne_generale" name="moyenne_generale" min="0" max="20" step="0.01" value="{{ old('moyenne_generale', $profile->moyenne_generale ?? '') }}" placeholder="Ex: 14.50" required>
                    </div>
                    <div class="tp-field">
                        <label class="tp-label" for="gouvernorat">Gouvernorat</label>
                        <select class="tp-input" id="gouvernorat" name="gouvernorat" required>
                            <option value="">— Votre gouvernorat —</option>
                            @foreach($gouvernorats as $g)
                                <option value="{{ $g }}" {{ ($profile->gouvernorat ?? '') === $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="margin-top:2rem">
                    <label class="tp-label">Notes par matière</label>
                    <div id="notesContainer">
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
                        @endphp
                        @if($currentSection)
                            <div style="font-size:.82rem;color:var(--ink60);margin-bottom:1rem;font-style:italic">Saisissez les notes de votre relevé de baccalauréat.</div>
                            @php $section_matieres = $formules[$currentSection] ?? []; @endphp
                            @foreach($section_matieres as $code => $coef)
                                <div class="tp-nr">
                                    <div class="tp-nr-l">{{ $matiereLabels[$code] ?? $code }}</div>
                                    <div class="tp-nr-c">×{{ $coef }}</div>
                                    <input type="number" class="tp-nr-i" name="notes_matieres[{{ $code }}]" min="0" max="20" step="0.25" value="{{ old('notes_matieres.'.$code, $profile->notes_matieres[$code] ?? '') }}" placeholder="—" required>
                                </div>
                            @endforeach
                        @else
                            <div id="notesPlaceholder" style="text-align:center;padding:2.5rem;color:var(--ink30);font-size:.85rem;background:var(--ink06);border-radius:var(--r);border:1px dashed var(--ink15);">Sélectionnez votre section de Bac pour afficher les matières.</div>
                        @endif
                    </div>
                </div>
                <div class="tp-cta">
                    <a href="{{ route('student.orientation') }}" class="btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Retour
                    </a>
                    <button type="submit" class="btn-fill">Valider et passer au test <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></button>
                </div>
            </div>
        </form>
    </div>

    {{-- TAB 2 : TEST PSYCHOMÉTRIQUE --}}
    <div class="tp-panel {{ ($activeTab ?? 'score') === 'psycho' ? 'active' : '' }}" id="panel-psycho">

        <div class="tp-blocs">
            @php
            $blocIcons = [
                'R' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
                'B5' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>',
                'G' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m16 16-3.5-3.5"/><circle cx="11" cy="11" r="4"/></svg>',
                'V' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
            ];
            @endphp
            @foreach([
                ['label'=>'RIASEC · Holland','color'=>'#d4622a','code'=>'R','desc'=>'6 dimensions vocales : Réaliste, Investigateur, Artistique, Social, Entreprenant, Conventionnel.'],
                ['label'=>'Big Five · OCEAN','color'=>'#1a4f6e','code'=>'B5','desc'=>'5 traits de personnalité : Ouverture, Conscienciosité, Extraversion, Agréabilité, Stabilité.'],
                ['label'=>'Aptitudes · GATB','color'=>'#c8973a','code'=>'G','desc'=>'4 aptitudes cognitives : Intelligence générale, Verbal, Numérique, Spatial.'],
                ['label'=>'Valeurs · Schwartz','color'=>'#4a7c59','code'=>'V','desc'=>'4 valeurs fondamentales : Sécurité, Réussite, Bienveillance, Autonomie.'],
            ] as $bloc)
            <div class="tp-bloc">
                <div class="tp-bloc-top">
                    <div class="tp-bloc-ic" style="background:{{ $bloc['color'] }}">{!! $blocIcons[$bloc['code']] !!}</div>
                    <span class="tp-bloc-nm">{{ $bloc['label'] }}</span>
                </div>
                <div class="tp-bloc-ds">{{ $bloc['desc'] }}</div>
            </div>
            @endforeach
        </div>

        @if($hasOngoingTest ?? false)
        <div class="tp-resume">
            <div class="tp-resume-txt"><strong>Test en cours</strong> — {{ $progress->answered }} / {{ $progress->total }} questions ({{ round($progress->percentage) }}%)</div>
            <div class="tp-resume-acts">
                <a href="{{ route('riasec.question', ['step' => $progress->answered + 1]) }}" class="btn-fill" style="padding:.7rem 1.5rem;font-size:.88rem;">Continuer</a>
                <form action="{{ route('riasec.initialize') }}" method="POST">@csrf <input type="hidden" name="restart" value="1"><button type="submit" class="btn-ghost" onclick="return confirm('Effacer le test en cours ?')">Recommencer</button></form>
            </div>
        </div>
        @endif

        <div class="tp-instr">
            <div class="tp-ins">
                <div class="tp-ins-ic"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="2"/></svg></div>
                <p class="tp-ins-tx">Réponds selon ce que tu <strong>ressens vraiment</strong>, pas selon la « bonne » réponse.</p>
            </div>
            <div class="tp-ins">
                <div class="tp-ins-ic"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div>
                <p class="tp-ins-tx">Va vite : ta <strong>première réaction</strong> est souvent la plus honnête.</p>
            </div>
            <div class="tp-ins">
                <div class="tp-ins-ic"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
                <p class="tp-ins-tx">Échelle de <strong>1</strong> (Pas du tout) à <strong>5</strong> (Tout à fait) pour chaque question.</p>
            </div>
            <div class="tp-ins">
                <div class="tp-ins-ic"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg></div>
                <p class="tp-ins-tx">Tes réponses sont <strong>sauvegardées automatiquement</strong> à chaque étape.</p>
            </div>
        </div>

        @if(!($hasOngoingTest ?? false))
        <form action="{{ route('riasec.initialize') }}" method="POST">
            @csrf
            <div class="tp-cta" style="border-top:none;padding-top:0;margin-top:0">
                <a href="{{ route('student.orientation') }}" class="btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg> Retour
                </a>
                <button type="submit" class="btn-fill">Démarrer le test <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></button>
            </div>
        </form>
        @endif
    </div>
</div>

<script>
const formulesData = @json($formules ?? []);
const labelsData = @json($matiereLabels ?? []);
const existingData = @json(old('notes_matieres', $profile->notes_matieres ?? []));

function loadMatieres(section) {
    const c = document.getElementById('notesContainer');
    if (!section) { c.innerHTML = '<div id="notesPlaceholder" style="text-align:center;padding:2.5rem;color:var(--ink30);font-size:.85rem;background:var(--ink06);border-radius:var(--r);border:1px dashed var(--ink15);">Sélectionnez votre section de Bac pour afficher les matières.</div>'; return; }
    const m = formulesData[section];
    if (!m) { c.innerHTML = '<div style="color:#ef4444;text-align:center;padding:1rem;">Section inconnue.</div>'; return; }
    c.innerHTML = '<div style="font-size:.82rem;color:var(--ink60);margin-bottom:1rem;font-style:italic">Notes pour la section ' + section + '.</div>';
    Object.entries(m).forEach(([code, coef]) => {
        const d = document.createElement('div'); d.className = 'tp-nr';
        d.innerHTML = '<div class="tp-nr-l">' + (labelsData[code]||code) + '</div><div class="tp-nr-c">×' + coef + '</div><input type="number" class="tp-nr-i" name="notes_matieres[' + code + ']" min="0" max="20" step="0.25" required value="' + (existingData[code]||'') + '" placeholder="—">';
        c.appendChild(d);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.tp-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.tp-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tp-panel').forEach(p => p.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById('panel-' + tab.dataset.tab).classList.add('active');
        });
    });
    const s = document.getElementById('section_bac')?.value;
    if (s && document.getElementById('notesPlaceholder')) loadMatieres(s);
});
</script>
@endsection
