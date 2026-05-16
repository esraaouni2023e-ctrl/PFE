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
    <form method="POST" action="{{ route('register') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
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
                    autocomplete="name" class="input-2026" style="padding-left: 3rem;" placeholder="Votre nom complet">
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
                    autocomplete="username" class="input-2026" style="padding-left: 3rem;" placeholder="votre@email.com">
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
        });
    </script>
</x-auth-layout>
