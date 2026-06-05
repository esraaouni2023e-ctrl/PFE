<x-auth-layout>
    @section('page-title', 'Connexion')

    {{-- Header --}}
    <div style="margin-bottom: 2.5rem;">
        <h2 class="heading-3 mb-2">
            Bon retour <em class="text-gradient">parmi nous !</em>
        </h2>
        <p class="body-small">Prêt à explorer de nouveaux horizons ?</p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-2" :status="session('status')" />

    {{-- Login Form --}}
    <form method="POST" action="{{ route('login') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink60); margin-bottom: 0.5rem; margin-left: 0.2rem;">
                Adresse email
            </label>
            <div class="relative group">
                <div style="position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); color: var(--ink30); pointer-events: none; transition: color 0.2s;">
                    <svg style="width: 1.2rem; height: 1.2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    autocomplete="username" class="input-2026" style="padding-left: 3rem;" placeholder="votre@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" style="margin-top: 0.5rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
        </div>

        {{-- Password --}}
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 0.2rem; margin-bottom: 0.5rem;">
                <label for="password" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--ink60);">
                    Mot de passe
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 0.75rem; font-weight: 600; color: var(--accent); text-decoration: none;">
                        Oublié ?
                    </a>
                @endif
            </div>
            <div class="relative group">
                <div style="position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); color: var(--ink30); pointer-events: none; transition: color 0.2s;">
                    <svg style="width: 1.2rem; height: 1.2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="input-2026" style="padding-left: 3rem; padding-right: 3rem;" placeholder="••••••••">
                
                <button type="button" id="togglePassword" style="position: absolute; top: 50%; right: 1rem; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--ink30); display: flex; align-items: center;">
                    <svg id="eyeIcon" style="width: 1.2rem; height: 1.2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" style="margin-top: 0.5rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
        </div>

        {{-- Options --}}
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 0 0.2rem;">
            <label for="remember_me" style="display: inline-flex; align-items: center; cursor: pointer;">
                <input id="remember_me" type="checkbox" name="remember" 
                    style="border-radius: 4px; border: 1.5px solid var(--ink10); background: var(--ink05); color: var(--accent); width: 1rem; height: 1rem;">
                <span style="margin-left: 0.5rem; font-size: 0.85rem; color: var(--ink60); user-select: none;">Rester connecté</span>
            </label>
        </div>

        {{-- Action --}}
        <button type="submit" class="btn-futuristic" style="margin-top: 0.5rem;">
            <span>Accéder au Dashboard</span>
            <svg style="width: 1.2rem; height: 1.2rem; margin-left: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
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
        <a href="{{ route('auth.social', 'google') }}" class="social-btn social-google" title="Se connecter avec Google">
            <svg viewBox="0 0 24 24">
                <path fill="#EA4335" d="M12 5.04c1.7 0 3.2.6 4.4 1.7l3.3-3.3C17.7 1.5 15 0 12 0 7.3 0 3.3 2.7 1.4 6.6l3.9 3C6.3 7 8.9 5.04 12 5.04z"/>
                <path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.6-.2-2.3H12v4.4h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.1-2 3.7-4.9 3.7-8.7z"/>
                <path fill="#FBBC05" d="M5.3 14.3c-.3-.8-.4-1.7-.4-2.6 0-.9.1-1.8.4-2.6L1.4 6.1C.5 7.9 0 9.9 0 12c0 2.1.5 4.1 1.4 5.9l3.9-3z"/>
                <path fill="#34A853" d="M12 18.96c-3.1 0-5.7-1.96-6.7-4.66l-3.9 3C3.3 21.3 7.3 24 12 24c3.1 0 5.9-1.1 7.9-3l-3.7-2.9c-1.1.76-2.6 1.86-4.2 1.86z"/>
            </svg>
        </a>
        <a href="{{ route('auth.social', 'github') }}" class="social-btn social-github" title="Se connecter avec GitHub">
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

    {{-- Footer --}}
    <div style="margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid var(--ink10); text-align: center;">
        <p class="body-small" style="margin-bottom: 1rem;">Nouveau sur CapAvenir ?</p>
        <a href="{{ route('register') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; font-weight: 700; color: var(--ink); text-decoration: none;">
            <span>Rejoindre l'aventure</span>
            <div style="width: 32px; height: 32px; border-radius: 50%; background: color-mix(in srgb, var(--accent) 10%, transparent); color: var(--accent); display: flex; align-items: center; justify-content: center;">
                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const passwordInput = document.querySelector('#password');
            const eyeIcon = document.querySelector('#eyeIcon');

            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                if (type === 'text') {
                    eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />`;
                } else {
                    eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
                }
            });
        });
    </script>
</x-auth-layout>