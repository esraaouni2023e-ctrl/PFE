<x-auth-layout>
    @section('page-title', 'Inscription')

    {{-- Header --}}
    <div style="margin-bottom: 2.5rem;">
        <h2 class="heading-3 mb-2">
            Créer un <em class="text-gradient">profil</em>
        </h2>
        <p class="body-small">Initialisez votre trajectoire professionnelle.</p>
    </div>
    {{-- Registration Form --}}
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink60); margin-bottom: 0.5rem; margin-left: 0.2rem;">
                Nom complet
            </label>
            <div class="relative group">
                <div style="position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); color: var(--ink30); pointer-events: none; transition: color 0.2s;">
                    <svg style="width: 1.2rem; height: 1.2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    autocomplete="name" class="input-2026" style="padding-left: 3rem;" placeholder="Ahmed Ben Ali">
            </div>
            <x-input-error :messages="$errors->get('name')" style="margin-top: 0.5rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
        </div>

        {{-- Email --}}
        <div>
            <label for="email" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink60); margin-bottom: 0.5rem; margin-left: 0.2rem;">
                Email
            </label>
            <div class="relative group">
                <div style="position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); color: var(--ink30); pointer-events: none; transition: color 0.2s;">
                    <svg style="width: 1.2rem; height: 1.2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    autocomplete="username" class="input-2026" style="padding-left: 3rem;" placeholder="ahmed@ecole.com">
            </div>
            <x-input-error :messages="$errors->get('email')" style="margin-top: 0.5rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
        </div>

        {{-- Passwords --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div>
                <label for="password" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink60); margin-bottom: 0.5rem; margin-left: 0.2rem;">
                    Mot de passe
                </label>
                <div class="relative group">
                    <div style="position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); color: var(--ink30); pointer-events: none; transition: color 0.2s;">
                        <svg style="width: 1.2rem; height: 1.2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="input-2026" style="padding-left: 3rem; padding-right: 3rem;" placeholder="••••••••">
                    <button type="button" class="toggle-password" data-target="password" style="position: absolute; top: 50%; right: 1rem; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--ink30); display: flex; align-items: center;">
                        <svg class="eye-icon" style="width: 1.2rem; height: 1.2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <label for="password_confirmation" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink60); margin-bottom: 0.5rem; margin-left: 0.2rem;">
                    Confirmation
                </label>
                <div class="relative group">
                    <div style="position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); color: var(--ink30); pointer-events: none; transition: color 0.2s;">
                        <svg style="width: 1.2rem; height: 1.2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="input-2026" style="padding-left: 3rem; padding-right: 3rem;" placeholder="••••••••">
                    <button type="button" class="toggle-password" data-target="password_confirmation" style="position: absolute; top: 50%; right: 1rem; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--ink30); display: flex; align-items: center;">
                        <svg class="eye-icon" style="width: 1.2rem; height: 1.2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <x-input-error :messages="$errors->get('password')" style="margin-top: 0.5rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />

        {{-- Role Selection --}}
        <div>
            <label style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink60); margin-bottom: 0.75rem; margin-left: 0.2rem;">
                Profil utilisateur
            </label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                {{-- Student --}}
                <label style="cursor: pointer; display: block; position: relative;">
                    <input type="radio" name="role" value="student" style="display: none;" checked>
                    <div class="role-card" style="display: flex; align-items: flex-start; gap: 0.8rem; padding: 1.25rem 1rem;">
                        <div class="role-indicator" style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid var(--ink30); margin-top: 0.15rem; transition: all 0.2s ease; flex-shrink: 0; position: relative;">
                            <div style="position: absolute; inset: 3px; border-radius: 50%; background: white; opacity: 0; transition: opacity 0.2s ease;"></div>
                        </div>
                        <div>
                            <h4 style="font-weight: 700; font-size: 0.95rem; color: var(--ink); margin-bottom: 0.2rem; letter-spacing: -0.01em;">Candidat</h4>
                            <p style="font-size: 0.75rem; color: var(--ink60); line-height: 1.3;">Orientation post-bac & bilans</p>
                        </div>
                    </div>
                </label>

                {{-- Counselor --}}
                <label style="cursor: pointer; display: block; position: relative;">
                    <input type="radio" name="role" value="counselor" style="display: none;">
                    <div class="role-card" style="display: flex; align-items: flex-start; gap: 0.8rem; padding: 1.25rem 1rem;">
                        <div class="role-indicator" style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid var(--ink30); margin-top: 0.15rem; transition: all 0.2s ease; flex-shrink: 0; position: relative;">
                            <div style="position: absolute; inset: 3px; border-radius: 50%; background: white; opacity: 0; transition: opacity 0.2s ease;"></div>
                        </div>
                        <div>
                            <h4 style="font-weight: 700; font-size: 0.95rem; color: var(--ink); margin-bottom: 0.2rem; letter-spacing: -0.01em;">Conseiller</h4>
                            <p style="font-size: 0.75rem; color: var(--ink60); line-height: 1.3;">Expertise & suivi éducatif</p>
                        </div>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" style="margin-top: 0.5rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
        </div>

        {{-- Counselor Extra Fields --}}
        <div id="counselor-extra-fields" style="display: none; flex-direction: column; gap: 1.5rem; padding-top: 1.5rem; margin-top: 0.5rem; overflow: hidden; transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease; max-height: 0; opacity: 0;">

            {{-- Info Banner --}}
            <div style="display: flex; align-items: flex-start; gap: 0.75rem; background: linear-gradient(135deg, rgba(255, 94, 0, 0.08), rgba(0, 45, 107, 0.06)); border: 1px solid rgba(255, 94, 0, 0.2); border-radius: var(--r); padding: 1rem 1.25rem;">
                <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255, 94, 0, 0.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 0.1rem;">
                    <svg style="width: 1.1rem; height: 1.1rem; color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h5 style="font-weight: 700; font-size: 0.85rem; color: var(--ink); margin: 0 0 0.2rem 0;">Vérification manuelle requise</h5>
                    <p style="font-size: 0.75rem; color: var(--ink60); line-height: 1.5; margin: 0;">Votre candidature sera examinée par l'administration CapAvenir sous 24 à 48h. Remplissez soigneusement ces champs pour accélérer la validation.</p>
                </div>
            </div>

            {{-- Section Header --}}
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
                <div style="height: 1px; flex: 1; background: linear-gradient(90deg, var(--ink10), transparent);"></div>
                <span style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink30);">Profil Professionnel</span>
                <div style="height: 1px; flex: 1; background: linear-gradient(270deg, var(--ink10), transparent);"></div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                {{-- Specialty --}}
                <div>
                    <label for="specialty" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink60); margin-bottom: 0.5rem;">
                        Spécialité d'orientation
                    </label>
                    <div class="relative group">
                        <div style="position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); color: var(--ink30); pointer-events: none;">
                            <svg style="width: 1.1rem; height: 1.1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <input id="specialty" type="text" name="specialty" value="{{ old('specialty') }}" class="input-2026" style="padding-left: 3rem;" placeholder="ex: Orientation Post-Bac, PsyEN...">
                    </div>
                    <x-input-error :messages="$errors->get('specialty')" style="margin-top: 0.5rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
                </div>

                {{-- Experience Years --}}
                <div>
                    <label for="experience_years" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink60); margin-bottom: 0.5rem;">
                        Années d'expérience
                    </label>
                    <div class="relative group">
                        <div style="position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); color: var(--ink30); pointer-events: none;">
                            <svg style="width: 1.1rem; height: 1.1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <input id="experience_years" type="number" name="experience_years" value="{{ old('experience_years') }}" class="input-2026" style="padding-left: 3rem;" min="0" max="50" placeholder="ex: 5">
                    </div>
                    <x-input-error :messages="$errors->get('experience_years')" style="margin-top: 0.5rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
                </div>
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink60); margin-bottom: 0.5rem;">
                    Numéro de Téléphone Professionnel
                </label>
                <div class="relative group">
                    <div style="position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); color: var(--ink30); pointer-events: none;">
                        <svg style="width: 1.1rem; height: 1.1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="input-2026" style="padding-left: 3rem;" placeholder="+216 XX XXX XXX">
                </div>
                <x-input-error :messages="$errors->get('phone')" style="margin-top: 0.5rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
            </div>

            {{-- Bio --}}
            <div>
                <label for="bio" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink60); margin-bottom: 0.5rem;">
                    Biographie courte (Parcours)
                </label>
                <div class="relative group">
                    <div style="position: absolute; top: 1rem; left: 1rem; color: var(--ink30); pointer-events: none;">
                        <svg style="width: 1.1rem; height: 1.1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <textarea id="bio" name="bio" class="input-2026" style="min-height: 80px; resize: vertical; padding: 0.75rem 1rem 0.75rem 3rem;" placeholder="Décrivez brièvement votre expérience d'accompagnement éducatif...">{{ old('bio') }}</textarea>
                </div>
                <x-input-error :messages="$errors->get('bio')" style="margin-top: 0.5rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
            </div>

            {{-- CV Upload — Drag & Drop Zone --}}
            <div>
                <label style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink60); margin-bottom: 0.5rem;">
                    Curriculum Vitae (PDF)
                </label>
                <div id="cv-dropzone" style="position: relative; border: 2px dashed var(--ink10); border-radius: var(--r); padding: 1.5rem; text-align: center; cursor: pointer; transition: all 0.3s ease; background: var(--ink06);"
                    onmouseover="this.style.borderColor='var(--accent)'; this.style.background='rgba(255, 94, 0, 0.04)';"
                    onmouseout="if(!this.classList.contains('has-file')){this.style.borderColor='var(--ink10)'; this.style.background='var(--ink06)';}"
                    onclick="document.getElementById('cv').click();">
                    <input id="cv" type="file" name="cv" accept="application/pdf" style="display: none;">
                    <div id="cv-placeholder">
                        <svg style="width: 2rem; height: 2rem; margin: 0 auto 0.5rem; color: var(--ink30);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p style="font-size: 0.85rem; font-weight: 600; color: var(--ink60); margin: 0 0 0.25rem 0;">Glissez votre CV ici ou <span style="color: var(--accent); font-weight: 700;">parcourir</span></p>
                        <p style="font-size: 0.7rem; color: var(--ink30); margin: 0;">Format PDF uniquement • Max 4 Mo</p>
                    </div>
                    <div id="cv-preview" style="display: none; align-items: center; justify-content: center; gap: 0.5rem;">
                        <svg style="width: 1.4rem; height: 1.4rem; color: var(--accent3);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span id="cv-filename" style="font-size: 0.85rem; font-weight: 600; color: var(--accent3);"></span>
                        <button type="button" id="cv-remove" style="background: none; border: none; cursor: pointer; color: var(--danger); display: flex; align-items: center; padding: 0.25rem;" onclick="event.stopPropagation(); removeCV();">
                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('cv')" style="margin-top: 0.5rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-futuristic" style="margin-top: 0.5rem;">
            <span>Créer mon compte</span>
        </button>
    </form>

    {{-- Footer --}}
    <div style="margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid var(--ink10); text-align: center;">
        <p class="body-small" style="margin-bottom: 1rem;">Déjà membre de CapAvenir ?</p>
        <a href="{{ route('login') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; font-weight: 700; color: var(--ink); text-decoration: none;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: color-mix(in srgb, var(--accent) 10%, transparent); color: var(--accent); display: flex; align-items: center; justify-content: center;">
                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </div>
            <span>Retour à la connexion</span>
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle
            const toggleBtns = document.querySelectorAll('.toggle-password');
            toggleBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    const eyeIcon = this.querySelector('.eye-icon');
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    if (type === 'text') {
                        eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />`;
                    } else {
                        eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
                    }
                });
            });

            // Toggle dynamic counselor fields with smooth animation
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const counselorFields = document.getElementById('counselor-extra-fields');
            const counselorInputs = counselorFields.querySelectorAll('input:not([type="file"]), textarea');

            function toggleFields() {
                const selectedRole = document.querySelector('input[name="role"]:checked').value;
                if (selectedRole === 'counselor') {
                    counselorFields.style.display = 'flex';
                    requestAnimationFrame(() => {
                        counselorFields.style.maxHeight = counselorFields.scrollHeight + 'px';
                        counselorFields.style.opacity = '1';
                    });
                    counselorInputs.forEach(input => input.setAttribute('required', 'required'));
                } else {
                    counselorFields.style.maxHeight = '0';
                    counselorFields.style.opacity = '0';
                    setTimeout(() => {
                        if (document.querySelector('input[name="role"]:checked').value !== 'counselor') {
                            counselorFields.style.display = 'none';
                        }
                    }, 500);
                    counselorInputs.forEach(input => input.removeAttribute('required'));
                }
            }

            roleRadios.forEach(radio => radio.addEventListener('change', toggleFields));
            toggleFields();

            // --- CV Drag & Drop ---
            const dropzone = document.getElementById('cv-dropzone');
            const cvInput = document.getElementById('cv');
            const cvPlaceholder = document.getElementById('cv-placeholder');
            const cvPreview = document.getElementById('cv-preview');
            const cvFilename = document.getElementById('cv-filename');

            if (dropzone) {
                ['dragenter', 'dragover'].forEach(evt => {
                    dropzone.addEventListener(evt, e => {
                        e.preventDefault();
                        e.stopPropagation();
                        dropzone.style.borderColor = 'var(--accent)';
                        dropzone.style.background = 'rgba(255, 94, 0, 0.06)';
                    });
                });

                ['dragleave', 'drop'].forEach(evt => {
                    dropzone.addEventListener(evt, e => {
                        e.preventDefault();
                        e.stopPropagation();
                        if (!dropzone.classList.contains('has-file')) {
                            dropzone.style.borderColor = 'var(--ink10)';
                            dropzone.style.background = 'var(--ink06)';
                        }
                    });
                });

                dropzone.addEventListener('drop', e => {
                    const files = e.dataTransfer.files;
                    if (files.length > 0 && files[0].type === 'application/pdf') {
                        cvInput.files = files;
                        showCvPreview(files[0].name);
                    }
                });

                cvInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        showCvPreview(this.files[0].name);
                    }
                });
            }

            function showCvPreview(name) {
                cvPlaceholder.style.display = 'none';
                cvPreview.style.display = 'flex';
                cvFilename.textContent = name;
                dropzone.classList.add('has-file');
                dropzone.style.borderColor = 'var(--accent3)';
                dropzone.style.background = 'rgba(16, 185, 129, 0.04)';
            }

            window.removeCV = function() {
                cvInput.value = '';
                cvPlaceholder.style.display = 'block';
                cvPreview.style.display = 'none';
                dropzone.classList.remove('has-file');
                dropzone.style.borderColor = 'var(--ink10)';
                dropzone.style.background = 'var(--ink06)';
            };
        });
    </script>
</x-auth-layout>
