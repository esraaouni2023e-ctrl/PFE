<x-auth-layout>
    @section('page-title', 'Inscription')

    <div style="margin-bottom: 1.5rem; text-align: center;">
        <h2 class="heading-3">
            Créer un <em class="text-gradient">profil</em>
        </h2>
        <p class="body-small" style="margin-top: 4px;">Commence ton parcours d'avenir.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.25rem;">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink60); margin-bottom: 0.4rem;">
                Nom complet
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="input-2026" placeholder="Ahmed Ben Ali">
            <x-input-error :messages="$errors->get('name')" style="margin-top: 0.4rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
        </div>

        {{-- Email --}}
        <div>
            <label for="email" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink60); margin-bottom: 0.4rem;">
                Adresse email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="input-2026" placeholder="ahmed@ecole.com">
            <x-input-error :messages="$errors->get('email')" style="margin-top: 0.4rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
        </div>

        {{-- Password --}}
        <div>
            <label for="password" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink60); margin-bottom: 0.4rem;">
                Mot de passe
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password" class="input-2026" placeholder="••••••••">
        </div>

        {{-- Confirmation --}}
        <div>
            <label for="password_confirmation" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink60); margin-bottom: 0.4rem;">
                Confirmer le mot de passe
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="input-2026" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" style="margin-top: 0.4rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
        </div>

        {{-- Role Selection --}}
        <div>
            <label style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink60); margin-bottom: 0.5rem;">
                Profil utilisateur
            </label>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                {{-- Student --}}
                <label style="cursor: pointer; display: block; position: relative;">
                    <input type="radio" name="role" value="student" style="display: none;" checked>
                    <div class="role-card" style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 1rem; border: 1.5px solid var(--ink10); border-radius: var(--r); background: var(--ink05);">
                        <div class="role-indicator" style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid var(--ink30); margin-top: 0.1rem; flex-shrink: 0; position: relative;">
                            <div style="position: absolute; inset: 3px; border-radius: 50%; background: var(--accent); opacity: 0; transition: opacity 0.2s ease;"></div>
                        </div>
                        <div>
                            <h4 style="font-weight: 700; font-size: 0.9rem; color: var(--ink); margin-bottom: 0.15rem;">Candidat</h4>
                            <p style="font-size: 0.75rem; color: var(--ink60); line-height: 1.3;">Orientation post-bac & bilans</p>
                        </div>
                    </div>
                </label>

                {{-- Counselor --}}
                <label style="cursor: pointer; display: block; position: relative;">
                    <input type="radio" name="role" value="counselor" style="display: none;">
                    <div class="role-card" style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 1rem; border: 1.5px solid var(--ink10); border-radius: var(--r); background: var(--ink05);">
                        <div class="role-indicator" style="width: 18px; height: 18px; border-radius: 50%; border: 2px solid var(--ink30); margin-top: 0.1rem; flex-shrink: 0; position: relative;">
                            <div style="position: absolute; inset: 3px; border-radius: 50%; background: var(--accent); opacity: 0; transition: opacity 0.2s ease;"></div>
                        </div>
                        <div>
                            <h4 style="font-weight: 700; font-size: 0.9rem; color: var(--ink); margin-bottom: 0.15rem;">Conseiller</h4>
                            <p style="font-size: 0.75rem; color: var(--ink60); line-height: 1.3;">Expertise & suivi éducatif</p>
                        </div>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" style="margin-top: 0.4rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
        </div>

        {{-- Counselor Extra Fields --}}
        <div id="counselor-extra-fields" style="display: none; flex-direction: column; gap: 1.25rem; padding-top: 1rem; border-top: 1px dashed var(--warm);">
            
            {{-- Info Banner --}}
            <div style="display: flex; align-items: flex-start; gap: 0.5rem; background: color-mix(in srgb, var(--accent) 8%, var(--paper)); border: 1px solid color-mix(in srgb, var(--accent) 20%, transparent); border-radius: var(--r); padding: 0.75rem;">
                <span style="font-size: 1.1rem; margin-top: -0.1rem;">⚠️</span>
                <div>
                    <h5 style="font-weight: 700; font-size: 0.8rem; color: var(--ink); margin-bottom: 0.15rem;">Validation administration</h5>
                    <p style="font-size: 0.7rem; color: var(--ink60); line-height: 1.4;">Votre candidature sera examinée sous 24 à 48h. Remplissez tous les champs.</p>
                </div>
            </div>

            {{-- Specialty --}}
            <div>
                <label for="specialty" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--ink60); margin-bottom: 0.4rem;">
                    Spécialité d'orientation
                </label>
                <input id="specialty" type="text" name="specialty" value="{{ old('specialty') }}" class="input-2026" placeholder="ex: Orientation Post-Bac...">
                <x-input-error :messages="$errors->get('specialty')" style="margin-top: 0.4rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
            </div>

            {{-- Experience Years --}}
            <div>
                <label for="experience_years" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--ink60); margin-bottom: 0.4rem;">
                    Années d'expérience
                </label>
                <input id="experience_years" type="number" name="experience_years" value="{{ old('experience_years') }}" class="input-2026" min="0" max="50" placeholder="ex: 5">
                <x-input-error :messages="$errors->get('experience_years')" style="margin-top: 0.4rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--ink60); margin-bottom: 0.4rem;">
                    Numéro de Téléphone
                </label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="input-2026" placeholder="+216 XX XXX XXX">
                <x-input-error :messages="$errors->get('phone')" style="margin-top: 0.4rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
            </div>

            {{-- Bio --}}
            <div>
                <label for="bio" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--ink60); margin-bottom: 0.4rem;">
                    Biographie courte
                </label>
                <textarea id="bio" name="bio" class="input-2026" style="min-height: 80px; resize: vertical;" placeholder="Décrivez votre expérience d'orientation...">{{ old('bio') }}</textarea>
                <x-input-error :messages="$errors->get('bio')" style="margin-top: 0.4rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
            </div>

            {{-- CV Upload --}}
            <div>
                <label style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--ink60); margin-bottom: 0.4rem;">
                    Curriculum Vitae (PDF)
                </label>
                <input id="cv" type="file" name="cv" accept="application/pdf" style="margin-top: 0.25rem;">
                <p style="font-size: 0.7rem; color: var(--ink30); margin-top: 0.2rem;">Format PDF uniquement • Max 4 Mo</p>
                <x-input-error :messages="$errors->get('cv')" style="margin-top: 0.4rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-futuristic" style="margin-top: 0.5rem;">
            <span>Créer mon compte</span>
        </button>
     </form>

    {{-- Divider --}}
    <div style="display: flex; align-items: center; margin: 1.5rem 0; color: var(--ink30);">
        <div style="flex: 1; height: 1px; background: var(--ink10);"></div>
        <span style="padding: 0 1rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink50); text-align: center;">ou continuer avec</span>
        <div style="flex: 1; height: 1px; background: var(--ink10);"></div>
    </div>

    {{-- Social Buttons Row --}}
    <div class="social-row">
        <a href="{{ route('auth.social', 'google') }}" class="social-btn social-google" title="S'inscrire avec Google">
            <svg viewBox="0 0 24 24">
                <path fill="#EA4335" d="M12 5.04c1.7 0 3.2.6 4.4 1.7l3.3-3.3C17.7 1.5 15 0 12 0 7.3 0 3.3 2.7 1.4 6.6l3.9 3C6.3 7 8.9 5.04 12 5.04z"/>
                <path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.6-.2-2.3H12v4.4h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.1-2 3.7-4.9 3.7-8.7z"/>
                <path fill="#FBBC05" d="M5.3 14.3c-.3-.8-.4-1.7-.4-2.6 0-.9.1-1.8.4-2.6L1.4 6.1C.5 7.9 0 9.9 0 12c0 2.1.5 4.1 1.4 5.9l3.9-3z"/>
                <path fill="#34A853" d="M12 18.96c-3.1 0-5.7-1.96-6.7-4.66l-3.9 3C3.3 21.3 7.3 24 12 24c3.1 0 5.9-1.1 7.9-3l-3.7-2.9c-1.1.76-2.6 1.86-4.2 1.86z"/>
            </svg>
        </a>
        <a href="{{ route('auth.social', 'github') }}" class="social-btn social-github" title="S'inscrire avec GitHub">
            <svg viewBox="0 0 24 24">
                <path fill="currentColor" d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.385.6.11.82-.26.82-.577v-2.234c-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.43.372.82 1.102.82 2.222v3.293c0 .319.22.694.825.576C20.565 21.795 24 17.3 24 12c0-6.63-5.37-12-12-12z"/>
            </svg>
        </a>
    </div>

    <style>
        .social-row {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin: 0.5rem 0;
        }
        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 50%;
            border: 1.5px solid var(--ink10);
            background: var(--ink05);
            color: var(--ink);
            cursor: pointer;
            transition: all 0.25s var(--ease);
            text-decoration: none;
        }
        .social-btn svg {
            width: 1.35rem;
            height: 1.35rem;
            flex-shrink: 0;
        }
        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px var(--ink10);
            border-color: var(--ink30);
        }
        .social-btn:active {
            transform: translateY(0);
        }
        
        .social-google:hover {
            background: color-mix(in srgb, #4285F4 8%, var(--paper));
            border-color: #4285F4;
            color: #4285F4;
            box-shadow: 0 8px 20px color-mix(in srgb, #4285F4 20%, transparent);
        }
        .social-github:hover {
            background: color-mix(in srgb, #24292e 8%, var(--paper));
            border-color: #24292e;
            color: #24292e;
            box-shadow: 0 8px 20px color-mix(in srgb, #24292e 20%, transparent);
        }
        
        [data-theme="dark"] .social-github:hover {
            background: color-mix(in srgb, #ffffff 12%, var(--paper));
            border-color: #ffffff;
            color: #ffffff;
            box-shadow: 0 8px 20px color-mix(in srgb, #ffffff 20%, transparent);
        }
    </style>

    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--ink10); text-align: center;">
        <p class="body-small" style="margin-bottom: 0.75rem;">Déjà membre de CapAvenir ?</p>
        <a href="{{ route('login') }}" style="font-size: 0.9rem; font-weight: 700; color: var(--accent); text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
            Retour à la connexion <i class="bi bi-chevron-right"></i>
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const counselorFields = document.getElementById('counselor-extra-fields');
            const counselorInputs = counselorFields.querySelectorAll('input:not([type="file"]), textarea');

            function updateRoleCards() {
                roleRadios.forEach(radio => {
                    const card = radio.closest('label').querySelector('.role-card');
                    const indicator = radio.closest('label').querySelector('.role-indicator div');
                    if (radio.checked) {
                        card.style.borderColor = 'var(--accent)';
                        card.style.background = 'color-mix(in srgb, var(--accent) 7%, var(--paper))';
                        indicator.style.opacity = '1';
                    } else {
                        card.style.borderColor = 'var(--ink10)';
                        card.style.background = 'var(--ink05)';
                        indicator.style.opacity = '0';
                    }
                });
            }

            function toggleFields() {
                const selectedRole = document.querySelector('input[name="role"]:checked').value;
                if (selectedRole === 'counselor') {
                    counselorFields.style.display = 'flex';
                    counselorInputs.forEach(input => input.setAttribute('required', 'required'));
                } else {
                    counselorFields.style.display = 'none';
                    counselorInputs.forEach(input => input.removeAttribute('required'));
                }
                updateRoleCards();
            }

            roleRadios.forEach(radio => radio.addEventListener('change', toggleFields));
            toggleFields();
        });
    </script>
</x-auth-layout>
