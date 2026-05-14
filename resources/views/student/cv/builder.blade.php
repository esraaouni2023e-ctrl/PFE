@extends('layouts.student')
@section('title', ($cvProfile ? 'Modifier' : 'Créer') . ' un CV — CapAvenir')

@section('content')
<style>
    .builder-page { max-width: 900px; margin: 0 auto; padding: 2rem 1.5rem 4rem; }
    .builder-header { margin-bottom: 2rem; }
    .builder-header h1 { font-family: var(--font-serif); font-size: 1.6rem; font-weight: 600; }
    .builder-header-sub { color: var(--ink60); font-size: .85rem; margin-top: .25rem; }

    .builder-form { display: flex; flex-direction: column; gap: 1.75rem; }

    /* Section blocks */
    .form-section {
        background: var(--ink06); border: 1px solid var(--glass-border);
        border-radius: var(--rl); padding: 1.5rem; transition: border-color .3s var(--ease);
    }
    .form-section:hover { border-color: var(--glass-border-vivid); }
    .form-section-title {
        font-weight: 700; font-size: 1rem; margin-bottom: 1rem;
        display: flex; align-items: center; gap: .5rem;
        padding-bottom: .75rem; border-bottom: 1px solid var(--ink10);
    }

    /* Input styling */
    .form-group { margin-bottom: 1rem; }
    .form-group:last-child { margin-bottom: 0; }
    .form-label {
        display: block; font-size: .8rem; font-weight: 600; color: var(--ink60);
        margin-bottom: .35rem; text-transform: uppercase; letter-spacing: .04em;
    }
    .form-input, .form-textarea, .form-select {
        width: 100%; padding: .6rem .85rem; background: var(--input-bg);
        border: 1px solid var(--glass-border); border-radius: var(--r);
        color: var(--ink); font-family: var(--font-main); font-size: .88rem;
        transition: border-color .2s;
    }
    .form-input:focus, .form-textarea:focus, .form-select:focus {
        outline: none; border-color: var(--accent);
    }
    .form-textarea { resize: vertical; min-height: 80px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
    .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: .75rem; }

    /* Dynamic items */
    .dynamic-list { display: flex; flex-direction: column; gap: 1rem; }
    .dynamic-item {
        background: var(--card-surface); border: 1px solid var(--ink10);
        border-radius: var(--r); padding: 1rem; position: relative;
    }
    .dynamic-item .remove-btn {
        position: absolute; top: .5rem; right: .5rem;
        width: 26px; height: 26px; border-radius: 50%;
        background: color-mix(in srgb, #ef4444 10%, transparent);
        border: 1px solid color-mix(in srgb, #ef4444 25%, transparent);
        color: #ef4444; cursor: pointer; font-size: .7rem;
        display: flex; align-items: center; justify-content: center;
        transition: var(--transition);
    }
    .dynamic-item .remove-btn:hover { background: color-mix(in srgb, #ef4444 20%, transparent); }

    .add-btn {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .5rem 1rem; background: color-mix(in srgb, var(--accent3) 10%, transparent);
        border: 1px dashed color-mix(in srgb, var(--accent3) 35%, transparent);
        color: var(--accent3); border-radius: var(--r); cursor: pointer;
        font-family: var(--font-main); font-size: .82rem; font-weight: 600;
        transition: var(--transition); margin-top: .5rem;
    }
    .add-btn:hover { background: color-mix(in srgb, var(--accent3) 18%, transparent); }

    /* Template selector */
    .template-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: .75rem; }
    .template-option {
        padding: 1rem; border: 2px solid var(--glass-border); border-radius: var(--r);
        cursor: pointer; text-align: center; transition: var(--transition);
    }
    .template-option:hover { border-color: var(--ink30); }
    .template-option.selected { border-color: var(--accent); background: color-mix(in srgb, var(--accent) 8%, transparent); }
    .template-option input { display: none; }
    .template-option-icon { font-size: 1.5rem; margin-bottom: .3rem; }
    .template-option-name { font-weight: 700; font-size: .85rem; }
    .template-option-desc { font-size: .72rem; color: var(--ink60); margin-top: .15rem; }

    /* Submit area */
    .form-submit {
        display: flex; gap: .75rem; justify-content: flex-end;
        padding-top: 1rem; border-top: 1px solid var(--ink10);
    }
    .btn-save {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .75rem 1.6rem; background: var(--accent); color: #fff;
        border: none; border-radius: var(--r); cursor: pointer;
        font-family: var(--font-main); font-size: .9rem; font-weight: 600;
        transition: var(--transition);
        box-shadow: 0 4px 16px color-mix(in srgb, var(--accent) 30%, transparent);
    }
    .btn-save:hover { transform: translateY(-2px); }
    .btn-back {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .75rem 1.4rem; background: var(--ink06); color: var(--ink60);
        border: 1px solid var(--glass-border); border-radius: var(--r);
        cursor: pointer; font-family: var(--font-main); font-size: .88rem;
        font-weight: 600; text-decoration: none; transition: var(--transition);
    }
    .btn-back:hover { color: var(--ink); border-color: var(--ink30); }

    /* Checkbox */
    .form-check { display: flex; align-items: center; gap: .5rem; margin-top: .35rem; }
    .form-check input[type="checkbox"] { accent-color: var(--accent); width: 16px; height: 16px; }
    .form-check label { font-size: .82rem; color: var(--ink60); cursor: pointer; }

    /* Errors */
    .form-error { color: #ef4444; font-size: .75rem; margin-top: .25rem; }

    @media (max-width: 640px) {
        .form-row, .form-row-3, .template-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="builder-page" x-data="cvBuilder()">
    <div class="builder-header">
        <h1>{{ $cvProfile ? '✏️ Modifier' : '📝 Créer' }} un CV</h1>
        <div class="builder-header-sub">Remplissez les sections ci-dessous puis exportez en PDF ou DOCX</div>
    </div>

    @if($errors->any())
        <div data-errors style="
            background: color-mix(in srgb,#ef4444 8%,transparent);
            border: 1px solid color-mix(in srgb,#ef4444 25%,transparent);
            border-radius: var(--rl); padding: 1rem 1.25rem; margin-bottom: 1.5rem;
        ">
            <div style="display:flex;align-items:center;gap:.5rem;color:#ef4444;font-weight:700;font-size:.88rem;margin-bottom:.65rem;">
                ⚠️ Corriger les erreurs suivantes avant de sauvegarder :
            </div>
            <ul style="list-style:none;display:flex;flex-direction:column;gap:.35rem;">
                @foreach($errors->all() as $error)
                    <li style="font-size:.82rem;color:#ef4444;display:flex;align-items:baseline;gap:.4rem;">
                        <span style="flex-shrink:0;">→</span> {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="cv-form" class="builder-form"
          method="POST"
          action="{{ $cvProfile ? route('student.cv.update', $cvProfile) : route('student.cv.store') }}">
        @csrf
        @if($cvProfile) @method('PUT') @endif

        {{-- ═══ Informations Générales ═══ --}}
        <div class="form-section">
            <div class="form-section-title">📋 Informations Générales</div>

            <div class="form-group">
                <label class="form-label">Titre du CV *</label>
                <input type="text" name="title" class="form-input"
                       value="{{ old('title', $cvProfile?->title) }}"
                       placeholder="Ex: CV Développeur Web — Alternance 2026">
                @error('title') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Poste visé</label>
                    <input type="text" name="target_job" class="form-input"
                           value="{{ old('target_job', $cvProfile?->target_job) }}"
                           placeholder="Ex: Ingénieur Logiciel">
                </div>
                <div class="form-group">
                    <label class="form-label">Template</label>
                    <div class="template-grid">
                        @foreach($templates as $key => $tpl)
                            <label class="template-option"
                                   :class="{ 'selected': template === '{{ $key }}' }"
                                   @click="template = '{{ $key }}'">
                                <input type="radio" name="template_name" value="{{ $key }}"
                                       :checked="template === '{{ $key }}'">
                                <div class="template-option-icon">{{ $tpl['icon'] }}</div>
                                <div class="template-option-name">{{ $tpl['name'] }}</div>
                                <div class="template-option-desc">{{ $tpl['desc'] }}</div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Résumé Professionnel</label>
                <textarea name="summary" class="form-textarea" rows="4"
                          placeholder="Décrivez votre profil en quelques lignes…">{{ old('summary', $cvProfile?->summary) }}</textarea>
            </div>
        </div>

        {{-- ═══ Expériences Professionnelles ═══ --}}
        <div class="form-section">
            <div class="form-section-title">💼 Expériences Professionnelles</div>
            <div class="dynamic-list">
                <template x-for="(exp, idx) in experiences" :key="idx">
                    <div class="dynamic-item">
                        <button type="button" class="remove-btn" @click="experiences.splice(idx, 1)" title="Supprimer">✕</button>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Entreprise *</label>
                                <input type="text" :name="'experiences['+idx+'][company]'" class="form-input"
                                       x-model="exp.company" placeholder="Nom de l'entreprise">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Poste *</label>
                                <input type="text" :name="'experiences['+idx+'][position]'" class="form-input"
                                       x-model="exp.position" placeholder="Titre du poste">
                            </div>
                        </div>
                        <div class="form-row-3">
                            <div class="form-group">
                                <label class="form-label">Début *</label>
                                <input type="date" :name="'experiences['+idx+'][start_date]'" class="form-input"
                                       x-model="exp.start_date">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Fin</label>
                                <input type="date" :name="'experiences['+idx+'][end_date]'" class="form-input"
                                       x-model="exp.end_date" :disabled="exp.is_current">
                            </div>
                            <div class="form-group">
                                <div class="form-check" style="margin-top:1.5rem;">
                                    <input type="hidden" :name="'experiences['+idx+'][is_current]'" value="0">
                                    <input type="checkbox" :name="'experiences['+idx+'][is_current]'" value="1"
                                           :id="'exp_current_'+idx" x-model="exp.is_current">
                                    <label :for="'exp_current_'+idx">En cours</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea :name="'experiences['+idx+'][description]'" class="form-textarea" rows="3"
                                      x-model="exp.description"
                                      placeholder="• Réalisation clé #1&#10;• Réalisation clé #2"></textarea>
                        </div>
                    </div>
                </template>
            </div>
            <button type="button" class="add-btn" @click="addExperience()">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajouter une expérience
            </button>
        </div>

        {{-- ═══ Formations ═══ --}}
        <div class="form-section">
            <div class="form-section-title">🎓 Formation</div>
            <div class="dynamic-list">
                <template x-for="(edu, idx) in educations" :key="idx">
                    <div class="dynamic-item">
                        <button type="button" class="remove-btn" @click="educations.splice(idx, 1)" title="Supprimer">✕</button>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Établissement *</label>
                                <input type="text" :name="'educations['+idx+'][institution]'" class="form-input"
                                       x-model="edu.institution" placeholder="Université, école…">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Diplôme *</label>
                                <input type="text" :name="'educations['+idx+'][degree]'" class="form-input"
                                       x-model="edu.degree" placeholder="Licence, Master…">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Domaine d'étude</label>
                            <input type="text" :name="'educations['+idx+'][field_of_study]'" class="form-input"
                                   x-model="edu.field_of_study" placeholder="Informatique, Gestion…">
                        </div>
                        <div class="form-row-3">
                            <div class="form-group">
                                <label class="form-label">Début *</label>
                                <input type="date" :name="'educations['+idx+'][start_date]'" class="form-input"
                                       x-model="edu.start_date">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Fin</label>
                                <input type="date" :name="'educations['+idx+'][end_date]'" class="form-input"
                                       x-model="edu.end_date" :disabled="edu.is_current">
                            </div>
                            <div class="form-group">
                                <div class="form-check" style="margin-top:1.5rem;">
                                    <input type="hidden" :name="'educations['+idx+'][is_current]'" value="0">
                                    <input type="checkbox" :name="'educations['+idx+'][is_current]'" value="1"
                                           :id="'edu_current_'+idx" x-model="edu.is_current">
                                    <label :for="'edu_current_'+idx">En cours</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea :name="'educations['+idx+'][description]'" class="form-textarea" rows="2"
                                      x-model="edu.description" placeholder="Détails optionnels…"></textarea>
                        </div>
                    </div>
                </template>
            </div>
            <button type="button" class="add-btn" @click="addEducation()">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajouter une formation
            </button>
        </div>

        {{-- ═══ Compétences ═══ --}}
        <div class="form-section">
            <div class="form-section-title">⚡ Compétences</div>
            <div class="dynamic-list">
                <template x-for="(skill, idx) in skills" :key="idx">
                    <div class="dynamic-item" style="padding:.7rem 1rem;">
                        <button type="button" class="remove-btn" @click="skills.splice(idx, 1)" title="Supprimer" style="top:.4rem;right:.4rem;">✕</button>
                        <div class="form-row">
                            <div class="form-group" style="margin-bottom:0;">
                                <input type="text" :name="'skills['+idx+'][name]'" class="form-input"
                                       x-model="skill.name" placeholder="PHP, Laravel, Figma…">
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <select :name="'skills['+idx+'][level]'" class="form-select" x-model="skill.level">
                                    <option value="">Niveau…</option>
                                    <option value="Débutant">Débutant</option>
                                    <option value="Intermédiaire">Intermédiaire</option>
                                    <option value="Avancé">Avancé</option>
                                    <option value="Expert">Expert</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <button type="button" class="add-btn" @click="addSkill()">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajouter une compétence
            </button>
        </div>

        {{-- ═══ Langues ═══ --}}
        <div class="form-section">
            <div class="form-section-title">🌐 Langues</div>
            <div class="dynamic-list">
                <template x-for="(lang, idx) in languages" :key="idx">
                    <div class="dynamic-item" style="padding:.7rem 1rem;">
                        <button type="button" class="remove-btn" @click="languages.splice(idx, 1)" title="Supprimer" style="top:.4rem;right:.4rem;">✕</button>
                        <div class="form-row">
                            <div class="form-group" style="margin-bottom:0;">
                                <input type="text" :name="'languages['+idx+'][name]'" class="form-input"
                                       x-model="lang.name" placeholder="Français, Anglais, Arabe…">
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <input type="text" :name="'languages['+idx+'][level]'" class="form-input"
                                       x-model="lang.level" placeholder="Natif, Courant, B2, A1…">
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <button type="button" class="add-btn" @click="addLanguage()">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajouter une langue
            </button>
        </div>

        {{-- ═══ Actions ═══ --}}
        <div class="form-submit">
            <a href="{{ route('student.cv.index') }}" class="btn-back">← Retour</a>
            <button type="button" class="btn-save" @click.prevent="submitForm()">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ $cvProfile ? 'Enregistrer les modifications' : 'Créer le CV' }}
            </button>
        </div>
    </form>
</div>

<script>
function cvBuilder() {
    @php
        $oldExp = old('experiences', $cvProfile?->experiences?->map(fn($e) => [
            'company' => $e->company,
            'position' => $e->position,
            'start_date' => $e->start_date->format('Y-m-d'),
            'end_date' => $e->end_date?->format('Y-m-d') ?? '',
            'is_current' => $e->is_current,
            'description' => $e->description,
        ])?->toArray() ?? []);

        $oldEdu = old('educations', $cvProfile?->educations?->map(fn($e) => [
            'institution' => $e->institution,
            'degree' => $e->degree,
            'field_of_study' => $e->field_of_study ?? '',
            'start_date' => $e->start_date->format('Y-m-d'),
            'end_date' => $e->end_date?->format('Y-m-d') ?? '',
            'is_current' => $e->is_current,
            'description' => $e->description ?? '',
        ])?->toArray() ?? []);

        $oldSkills = old('skills', $cvProfile?->skills?->map(fn($s) => [
            'name' => $s->name, 'level' => $s->level ?? '',
        ])?->toArray() ?? []);

        $oldLangs = old('languages', $cvProfile?->languages?->map(fn($l) => [
            'name' => $l->name, 'level' => $l->level ?? '',
        ])?->toArray() ?? []);
    @endphp

    return {
        template: '{{ old("template_name", $cvProfile?->template_name ?? "modern") }}',
        experiences: {!! json_encode($oldExp, JSON_UNESCAPED_UNICODE) !!},
        educations: {!! json_encode($oldEdu, JSON_UNESCAPED_UNICODE) !!},
        skills: {!! json_encode($oldSkills, JSON_UNESCAPED_UNICODE) !!},
        languages: {!! json_encode($oldLangs, JSON_UNESCAPED_UNICODE) !!},

        addExperience() {
            this.experiences.push({
                company: '', position: '', start_date: '', end_date: '',
                is_current: false, description: ''
            });
        },
        addEducation() {
            this.educations.push({
                institution: '', degree: '', field_of_study: '',
                start_date: '', end_date: '', is_current: false, description: ''
            });
        },
        addSkill() {
            this.skills.push({ name: '', level: '' });
        },
        addLanguage() {
            this.languages.push({ name: '', level: '' });
        },

        // Nettoie les lignes vides avant soumission
        submitForm() {
            // Supprimer les lignes vides
            this.skills     = this.skills.filter(s => s.name && s.name.trim());
            this.languages  = this.languages.filter(l => l.name && l.name.trim());
            this.experiences = this.experiences.filter(e => e.company && e.company.trim() && e.position && e.position.trim());
            this.educations  = this.educations.filter(e => e.institution && e.institution.trim() && e.degree && e.degree.trim());

            // Soumettre via l’API native (contourne les listeners Alpine)
            this.$nextTick(() => {
                const form = document.getElementById('cv-form');
                HTMLFormElement.prototype.submit.call(form);
            });
        },
    };
}

// Auto-scroll vers les erreurs au chargement
document.addEventListener('DOMContentLoaded', function () {
    const errorBox = document.querySelector('[data-errors]');
    if (errorBox) {
        errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
@endsection
