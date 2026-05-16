@extends('layouts.student')

@section('title', 'Nova - Test d\'Orientation IA')

@section('content')
@include('student.nova.styles')

<div class="db" id="novaRoot">
    
    <section class="db-section rev">
        <div class="db-section-header" style="text-align: center;">
            <p class="stag">Outil Officiel</p>
            <h2 class="sh">Calculateur de <em>Formule Globale (FG)</em></h2>
            <p style="color: var(--ink60); max-width: 600px; margin: 1rem auto 0; font-size: 1.05rem;">
                Calculez instantanément et avec précision votre Formule Globale selon votre section et les formules officielles du Baccalauréat tunisien.
            </p>
        </div>

        @if(session('error'))
        <div style="background: color-mix(in srgb, #ef4444 10%, transparent); border: 1px solid color-mix(in srgb, #ef4444 30%, transparent); color: #ef4444; padding: 1rem; border-radius: var(--r); margin-bottom: 2rem; text-align: center; font-weight: 500;">
            {{ session('error') }}
        </div>
        @endif

        <div class="card" style="max-width: 800px; margin: 0 auto; padding: 3rem 2.5rem;">
            <form id="fgForm" onsubmit="event.preventDefault(); calculateFG();">
                <!-- Section du Bac -->
                <div style="margin-bottom: 2.5rem;">
                    <label for="section_bac" style="display:block; font-weight: 600; margin-bottom: .75rem;">Section du Baccalauréat</label>
                    <select id="section_bac" name="section_bac" required class="input-field">
                        <option value="" disabled selected>Choisissez votre section...</option>
                        <option value="Mathématiques">Mathématiques</option>
                        <option value="Sciences expérimentales">Sciences expérimentales</option>
                        <option value="Technique">Technique</option>
                        <option value="Informatique">Informatique</option>
                        <option value="Économie et gestion">Économie et gestion</option>
                        <option value="Lettres">Lettres</option>
                        <option value="Sport">Sport</option>
                    </select>
                </div>

                <hr style="border: none; border-top: 1px solid var(--ink10); margin: 2rem 0;">

                <!-- Notes Principales -->
                <div style="margin-bottom: 2.5rem;">
                    <h3 style="font-family: 'Fraunces', serif; font-size: 1.4rem; font-weight: 600; margin-bottom: .5rem;">Vos Résultats</h3>
                    <p style="color: var(--ink60); font-size: .9rem; margin-bottom: 1.5rem;">Saisissez vos notes sur 20 pour calculer votre Formule Globale.</p>
                    
                    <div class="nova-form-grid">
                        <div>
                            <label for="mg" style="display:block; font-weight: 600; margin-bottom: .5rem; font-size: .9rem;">Moyenne Générale (MG)</label>
                            <input type="number" step="0.01" min="0" max="20" name="mg" id="mg" required class="input-field" placeholder="ex: 15.5">
                        </div>
                    </div>
                    
                    <div class="nova-form-grid" id="dynamic-notes-container" style="margin-top: 1.5rem;">
                        <!-- Injected via JS -->
                    </div>
                </div>

                <hr style="border: none; border-top: 1px solid var(--ink10); margin: 2rem 0;">

                <!-- Bouton de soumission -->
                <div style="text-align: right; margin-top: 2rem;">
                    <button type="submit" id="submitBtn" class="btn-fill" style="padding: 1rem 2rem; font-size: 1rem;">
                        <span id="btnText">Calculer mon Score FG <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='var(--accent)' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M15.75 15.75V18m-3-3V18m3-3l3 3m-9-3l-3 3m2.25-13.5h1.5a2.25 2.25 0 012.25 2.25v6.75a2.25 2.25 0 01-2.25 2.25h-1.5a2.25 2.25 0 01-2.25-2.25V5.25a2.25 2.25 0 012.25-2.25z' /></svg></span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Section de Résultat (Cachée par défaut) -->
        <div id="resultContainer" class="card rev" style="max-width: 800px; margin: 2rem auto 0; padding: 3rem 2.5rem; text-align: center; display: none;">
            <p class="stag" id="resultSectionName">Section</p>
            <h2 class="sh" style="margin-bottom: 1.5rem;">Votre Score Formule Globale</h2>
            
            <div class="nova-score-display" id="resultScore" style="font-size: 5.5rem; margin: 1.5rem 0;">
                0.00
            </div>
            
            <div style="display: flex; align-items: center; justify-content: center; gap: .5rem; margin-top: 1rem;">
                <span class="pill pill-sage">Calcul Validé</span>
                <span style="font-size: .85rem; color: var(--ink60);">Score officiel sur ~200</span>
            </div>
        </div>
    </section>
</div>

<script>
    (function() {
        const sectionSelect = document.getElementById('section_bac');
        const notesContainer = document.getElementById('dynamic-notes-container');
        const form = document.getElementById('novaForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');

        const subjectsBySection = {
            'Mathématiques': { 'M': 'Mathématiques', 'SP': 'Sciences Physiques', 'SVT': 'SVT', 'F': 'Français', 'Ang': 'Anglais' },
            'Sciences expérimentales': { 'M': 'Mathématiques', 'SP': 'Sciences Physiques', 'SVT': 'SVT', 'F': 'Français', 'Ang': 'Anglais' },
            'Technique': { 'TE': 'Technologie', 'M': 'Mathématiques', 'SP': 'Sciences Physiques', 'F': 'Français', 'Ang': 'Anglais' },
            'Informatique': { 'Algo': 'Algorithmique', 'SP': 'Sciences Physiques', 'STI': 'STI', 'F': 'Français', 'Ang': 'Anglais' },
            'Économie et gestion': { 'Ec': 'Économie', 'Ge': 'Gestion', 'M': 'Mathématiques', 'HG': 'Histoire-Géo', 'F': 'Français', 'Ang': 'Anglais' },
            'Lettres': { 'A': 'Arabe', 'PH': 'Philosophie', 'HG': 'Histoire-Géo', 'F': 'Français', 'Ang': 'Anglais' },
            'Sport': { 'SB': 'Sciences Biologiques', 'Sp-sport': 'Spécialité Sport', 'EP': 'Éducation Physique', 'SP': 'Sciences Physiques', 'PH': 'Philosophie', 'F': 'Français', 'Ang': 'Anglais' }
        };

        function renderNoteFields() {
            const section = sectionSelect.value;
            notesContainer.innerHTML = '';
            
            if (subjectsBySection[section]) {
                Object.entries(subjectsBySection[section]).forEach(([key, label]) => {
                    const html = `
                        <div class="rev vis">
                            <label for="note_${key}" style="display:block; font-weight: 600; margin-bottom: .5rem; font-size: .9rem;">${label}</label>
                            <input type="number" step="0.01" min="0" max="20" name="notes[${key}]" id="note_${key}" required
                                class="input-field" placeholder="0 - 20">
                        </div>
                    `;
                    notesContainer.insertAdjacentHTML('beforeend', html);
                });
            }
        }

        sectionSelect.addEventListener('change', renderNoteFields);
        
        if (sectionSelect.value) {
            renderNoteFields();
        }

    // Calculation Formulas (Global)
    window.calculateFG = function() {
        const section = document.getElementById('section_bac').value;
        const mg = parseFloat(document.getElementById('mg').value) || 0;
        
        let fg = 4 * mg;
        
        const getNote = (id) => parseFloat(document.getElementById('note_' + id)?.value) || 0;

        if (section === 'Lettres') {
            fg += 1.5 * getNote('A') + 1.5 * getNote('PH') + 1 * getNote('HG') + 1 * getNote('F') + 1 * getNote('Ang');
        } else if (section === 'Mathématiques') {
            fg += 2 * getNote('M') + 1.5 * getNote('SP') + 0.5 * getNote('SVT') + 1 * getNote('F') + 1 * getNote('Ang');
        } else if (section === 'Sciences expérimentales') {
            fg += 1 * getNote('M') + 1.5 * getNote('SP') + 1.5 * getNote('SVT') + 1 * getNote('F') + 1 * getNote('Ang');
        } else if (section === 'Économie et gestion') {
            fg += 1.5 * getNote('Ec') + 1.5 * getNote('Ge') + 0.5 * getNote('M') + 0.5 * getNote('HG') + 1 * getNote('F') + 1 * getNote('Ang');
        } else if (section === 'Technique') {
            fg += 1.5 * getNote('TE') + 1.5 * getNote('M') + 1 * getNote('SP') + 1 * getNote('F') + 1 * getNote('Ang');
        } else if (section === 'Informatique') {
            fg += 1.5 * getNote('Algo') + 0.5 * getNote('SP') + 0.5 * getNote('STI') + 1 * getNote('F') + 1 * getNote('Ang');
        } else if (section === 'Sport') {
            fg += 1.5 * getNote('SB') + 1 * getNote('Sp-sport') + 0.5 * getNote('EP') + 0.5 * getNote('SP') + 0.5 * getNote('PH') + 1 * getNote('F') + 1 * getNote('Ang');
        }

        // Show result
        document.getElementById('resultSectionName').textContent = 'Section ' + section;
        document.getElementById('resultScore').textContent = fg.toFixed(2);
        
        const resultContainer = document.getElementById('resultContainer');
        resultContainer.style.display = 'block';
        
        // Trigger animation
        resultContainer.classList.remove('vis');
        void resultContainer.offsetWidth; // trigger reflow
        resultContainer.classList.add('vis');
        
        // Scroll to result
        resultContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    };
})();
</script>
@endsection
