@extends('layouts.student')
@section('title', 'Profil Académique')

@section('content')
<style>
    .pr-mob {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    .pr-card-mob {
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--rl);
        box-shadow: var(--shadow-card);
        overflow: hidden;
    }
    .pr-card-head-mob {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--glass-border);
        background: var(--cream);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .pr-card-head-mob h2 {
        font-family: var(--font-serif);
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--ink);
    }
    .pr-card-body-mob {
        padding: 1.25rem;
    }
    .pr-avatar-block-mob {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
    }
    .pr-avatar-mob {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent), var(--accent2));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: #fff;
        font-family: var(--font-serif);
        font-weight: 700;
        flex-shrink: 0;
    }
    .pr-progress-bar-mob {
        height: 6px;
        background: var(--warm);
        border-radius: var(--rx);
        overflow: hidden;
        margin-top: 4px;
    }
    .pr-progress-fill-mob {
        height: 100%;
        border-radius: var(--rx);
        background: linear-gradient(90deg, var(--accent), var(--gold));
    }

    /* Score Box */
    .fg-box-mob {
        background: linear-gradient(135deg, color-mix(in srgb, var(--accent) 6%, transparent), color-mix(in srgb, var(--accent2) 4%, transparent));
        border: 1px solid color-mix(in srgb, var(--accent) 15%, transparent);
        border-radius: var(--rl);
        padding: 1.25rem;
        text-align: center;
        margin-bottom: 1rem;
    }
    .fg-num-mob {
        font-family: var(--font-serif);
        font-size: 2.8rem;
        font-weight: 700;
        line-height: 1;
        display: block;
        margin-bottom: 4px;
    }

    /* Forms */
    .field-mob {
        margin-bottom: 1rem;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .label-mob {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ink30);
    }
    .input-mob {
        width: 100%;
        min-height: 44px;
        padding: 0.65rem 0.85rem;
        background: var(--cream);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        color: var(--ink);
        font-family: inherit;
        font-size: 16px !important;
        outline: none;
    }
    .input-mob:focus {
        border-color: var(--accent);
    }

    /* Notes rows */
    .note-row-mob {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.65rem 0.85rem;
        background: var(--cream);
        border-radius: var(--r);
        border: 1px solid var(--glass-border);
        margin-bottom: 0.5rem;
    }
    .note-label-mob {
        flex: 1;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--ink60);
    }
    .note-coef-mob {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--accent);
        width: 32px;
        text-align: center;
    }
    .note-input-mob {
        width: 70px;
        min-height: 38px;
        padding: 0.25rem;
        background: var(--paper);
        border: 1px solid var(--glass-border);
        border-radius: var(--r);
        text-align: center;
        font-family: var(--font-serif);
        font-size: 1rem;
        font-weight: 700;
        color: var(--ink);
    }

    /* Button */
    .btn-submit-mob {
        width: 100%;
        min-height: 46px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: var(--r);
        font-weight: 700;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px color-mix(in srgb, var(--accent) 30%, transparent);
        margin-top: 1rem;
    }
    .btn-submit-mob:active {
        transform: translateY(1px);
    }
</style>

<div class="pr-mob">
    {{-- Header --}}
    <div>
        <h1 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 700; color: var(--ink); margin-bottom: 2px;">
            Profil Académique
        </h1>
        <p style="font-size: 0.8rem; color: var(--ink60);">Calcul de votre Formule Globale (FG)</p>
    </div>

    @if(session('success'))
        <div style="background: color-mix(in srgb, var(--success) 8%, var(--paper)); border: 1px solid color-mix(in srgb, var(--success) 20%, transparent); color: var(--success); padding: 0.75rem; border-radius: var(--r); font-size: 0.8rem; font-weight: 600; text-align: center;">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background: color-mix(in srgb, var(--red) 8%, var(--paper)); border: 1px solid color-mix(in srgb, var(--red) 20%, transparent); color: var(--red); padding: 0.75rem; border-radius: var(--r); font-size: 0.8rem; font-weight: 600; text-align: center;">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Completeness --}}
    <div class="pr-card-mob">
        <div class="pr-avatar-block-mob">
            <div class="pr-avatar-mob">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div style="flex: 1;">
                <h3 style="font-size: 0.95rem; font-weight: 700;">{{ $user->name }}</h3>
                <div class="pr-progress-bar-mob">
                    <div class="pr-progress-fill-mob" style="width: {{ $profile->progression }}%;"></div>
                </div>
                <div style="font-size: 0.72rem; color: var(--ink60); margin-top: 2px;">{{ $profile->progression }}% complété</div>
            </div>
        </div>
    </div>

    {{-- Score FG --}}
    <div class="pr-card-mob">
        <div class="pr-card-head-mob">
            <i class="bi bi-patch-check" style="color:var(--accent); font-size: 1.1rem;"></i>
            <h2>Mon Score FG</h2>
        </div>
        <div class="pr-card-body-mob">
            @if($profile->score_fg)
                <div class="fg-box-mob">
                    <span class="fg-num-mob" style="color:{{ $profile->couleur_fg }}">{{ number_format($profile->score_fg, 2) }}</span>
                    <span style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:{{ $profile->couleur_fg }}; background:color-mix(in srgb, {{ $profile->couleur_fg }} 8%, transparent); padding: 3px 8px; border-radius:var(--rx);">
                        {{ $profile->niveau_fg }}
                    </span>
                    @if($profile->score_fg_updated_at)
                        <div style="font-size: 0.65rem; color: var(--ink30); margin-top: 0.5rem;">Mis à jour {{ $profile->score_fg_updated_at->diffForHumans() }}</div>
                    @endif
                </div>
                <a href="{{ route('student.whatif.index') }}" style="width: 100%; min-height: 38px; border: 1px solid var(--glass-border); background: var(--cream); border-radius: var(--r); text-decoration: none; color: var(--ink60); font-size: 0.8rem; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 0.4rem;">
                    <i class="bi bi-lightning-charge"></i> Simuler un scénario
                </a>
            @else
                <div style="text-align: center; padding: 1rem; color: var(--ink30);">
                    <i class="bi bi-bar-chart-steps" style="font-size: 2rem; opacity: 0.3;"></i>
                    <p style="font-size: 0.8rem; font-weight: 700; margin-top: 0.25rem;">Score non encore calculé</p>
                    <p style="font-size: 0.72rem; margin-top: 2px;">Renseignez vos notes ci-dessous pour l'estimer.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Main Form --}}
    <form method="POST" action="{{ route('student.profil.update') }}">
        @csrf @method('PUT')

        {{-- Bac info --}}
        <div class="pr-card-mob" style="margin-bottom: 1rem;">
            <div class="pr-card-head-mob">
                <i class="bi bi-mortarboard" style="color:var(--accent2); font-size: 1.1rem;"></i>
                <h2>Baccalauréat</h2>
            </div>
            <div class="pr-card-body-mob" style="display: flex; flex-direction: column; gap: 0.85rem;">
                <div class="field-mob">
                    <label class="label-mob" for="section_bac">Section BAC</label>
                    <select class="input-mob" id="section_bac" name="section_bac" required onchange="loadMatieres(this.value)">
                        <option value="">— Choisissez —</option>
                        @foreach($sections as $s)
                            <option value="{{ $s }}" {{ $profile->section_bac === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field-mob">
                    <label class="label-mob" for="annee_bac">Année du BAC</label>
                    <select class="input-mob" id="annee_bac" name="annee_bac" required>
                        @for($y = date('Y') + 1; $y >= 2015; $y--)
                            <option value="{{ $y }}" {{ ($profile->annee_bac ?? date('Y') + 1) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="field-mob">
                    <label class="label-mob" for="moyenne_generale">Moyenne Générale</label>
                    <input type="number" class="input-mob" id="moyenne_generale" name="moyenne_generale" min="0" max="20" step="0.01" value="{{ old('moyenne_generale', $profile->moyenne_generale) }}" placeholder="Ex: 14.50" required>
                </div>

                <div class="field-mob">
                    <label class="label-mob" for="gouvernorat">Gouvernorat</label>
                    <select class="input-mob" id="gouvernorat" name="gouvernorat" required>
                        <option value="">— Gouvernorat —</option>
                        @foreach($gouvernorats as $g)
                            <option value="{{ $g }}" {{ $profile->gouvernorat === $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Notes par matière --}}
        <div class="pr-card-mob" style="margin-bottom: 1rem;">
            <div class="pr-card-head-mob">
                <i class="bi bi-pencil-square" style="color:var(--accent3); font-size: 1.1rem;"></i>
                <h2>Notes par matière</h2>
            </div>
            <div class="pr-card-body-mob">
                <div id="notesContainer">
                    @if($profile->section_bac && $profile->notes_matieres)
                        <div style="font-size:0.75rem; color:var(--ink60); margin-bottom:0.75rem; font-style:italic">
                            Section sélectionnée : <strong>{{ $profile->section_bac }}</strong>
                        </div>
                        @php
                            $matiereLabels = \App\Services\ScoreFGService::MATIERES_LABELS;
                            $formules = [
                                'Mathématiques' => ['math'=>2,'sp'=>1.5,'svt'=>0.5,'fr'=>1,'ang'=>1],
                                'Sciences expérimentales' => ['math'=>1,'sp'=>1.5,'svt'=>1.5,'fr'=>1,'ang'=>1],
                                'Économie et gestion' => ['eco'=>1.5,'gest'=>1.5,'math'=>0.5,'hg'=>0.5,'fr'=>1,'ang'=>1],
                                'Technique' => ['tech'=>1.5,'math'=>1.5,'sp'=>1,'fr'=>1,'ang'=>1],
                                'Informatique' => ['math'=>1.5,'algo'=>1.5,'sp'=>0.5,'sti'=>0.5,'fr'=>1,'ang'=>1],
                                'Lettres' => ['ar'=>1.5,'philo'=>1.5,'hg'=>1,'fr'=>1,'ang'=>1],
                                'Sport' => ['bio'=>1.5,'sport'=>1,'ep'=>0.5,'sp'=>0.5,'ph'=>0.5,'fr'=>1,'ang'=>1],
                            ];
                            $section_matieres = $formules[$profile->section_bac] ?? [];
                        @endphp
                        @foreach($section_matieres as $code => $coef)
                            <div class="note-row-mob">
                                <span class="note-label-mob">{{ $matiereLabels[$code] ?? $code }}</span>
                                <span class="note-coef-mob">×{{ $coef }}</span>
                                <input type="number" class="note-input-mob" name="notes_matieres[{{ $code }}]" min="0" max="20" step="0.25" value="{{ old('notes_matieres.'.$code, $profile->notes_matieres[$code] ?? '') }}" placeholder="—">
                            </div>
                        @endforeach
                    @else
                        <div id="notesPlaceholder" style="text-align:center; padding:1.5rem; color:var(--ink30); font-size:0.8rem;">
                            <i class="bi bi-info-circle" style="font-size:1.2rem; vertical-align:middle; display:block; margin-bottom:4px;"></i> Sélectionnez une section BAC pour saisir vos notes.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Interests --}}
        <div class="pr-card-mob" style="margin-bottom: 1rem;">
            <div class="pr-card-head-mob">
                <i class="bi bi-heart" style="color:var(--gold); font-size: 1.1rem;"></i>
                <h2>Intérêts & Atouts</h2>
            </div>
            <div class="pr-card-body-mob" style="display: flex; flex-direction: column; gap: 0.85rem;">
                <div class="field-mob">
                    <label class="label-mob" for="interests">Centres d'intérêt</label>
                    <textarea class="input-mob" id="interests" name="interests" style="min-height: 80px;" placeholder="Ex: Informatique, IA, entrepreneuriat…">{{ old('interests', $profile->interests) }}</textarea>
                </div>
                <div class="field-mob">
                    <label class="label-mob" for="skills">Compétences & Atouts</label>
                    <textarea class="input-mob" id="skills" name="skills" style="min-height: 80px;" placeholder="Ex: Programmation Python, langues…">{{ old('skills', $profile->skills) }}</textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-submit-mob">
            <i class="bi bi-save"></i> Enregistrer & recalculer
        </button>
    </form>
</div>

<script>
async function loadMatieres(section) {
    const container = document.getElementById('notesContainer');
    if (!section) {
        container.innerHTML = '<div id="notesPlaceholder" style="text-align:center; padding:1.5rem; color:var(--ink30); font-size:0.8rem;"><i class="bi bi-info-circle" style="font-size:1.2rem; vertical-align:middle; display:block; margin-bottom:4px;"></i> Sélectionnez d\'abord votre section BAC</div>';
        return;
    }
    container.innerHTML = '<div style="padding:1rem; text-align:center; color:var(--ink30); font-size:0.8rem;">Chargement des coefficients...</div>';
    const res = await fetch(`/student/whatif/matieres?section=${encodeURIComponent(section)}`);
    const data = await res.json();
    const matieres = data.matieres;
    const existing = @json($profile->notes_matieres ?? []);

    container.innerHTML = `<div style="font-size:0.75rem; color:var(--ink60); margin-bottom:0.75rem; font-style:italic">Section sélectionnée : <strong>${section}</strong></div>`;
    Object.entries(matieres).forEach(([code, info]) => {
        const div = document.createElement('div');
        div.className = 'note-row-mob';
        div.innerHTML = `
            <span class="note-label-mob">${info.label}</span>
            <span class="note-coef-mob">×${info.coef}</span>
            <input type="number" class="note-input-mob" name="notes_matieres[${code}]" min="0" max="20" step="0.25" value="${existing[code] ?? ''}" placeholder="—">
        `;
        container.appendChild(div);
    });
}
</script>
@endsection
