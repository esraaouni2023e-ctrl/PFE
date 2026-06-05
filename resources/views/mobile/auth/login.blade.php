<x-auth-layout>
    @section('page-title', 'Connexion')

    <div style="margin-bottom: 1.5rem; text-align: center;">
        <h2 class="heading-3">
            Bon retour <em class="text-gradient">parmi nous !</em>
        </h2>
        <p class="body-small" style="margin-top: 4px;">Prêt à explorer ton avenir ?</p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-2" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" style="display: flex; flex-direction: column; gap: 1.25rem;">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" style="display:block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink60); margin-bottom: 0.4rem;">
                Adresse email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="input-2026" placeholder="votre@email.com">
            <x-input-error :messages="$errors->get('email')" style="margin-top: 0.4rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
        </div>

        {{-- Password --}}
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                <label for="password" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ink60);">
                    Mot de passe
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 0.75rem; font-weight: 600; color: var(--accent); text-decoration: none;">
                        Oublié ?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="input-2026" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" style="margin-top: 0.4rem; font-size: 0.75rem; color: #ef4444; font-style: italic;" />
        </div>

        {{-- Options --}}
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <label for="remember_me" style="display: inline-flex; align-items: center; cursor: pointer;">
                <input id="remember_me" type="checkbox" name="remember" style="width: 1.1rem; height: 1.1rem; border-radius: 4px; border: 1.5px solid var(--ink10); accent-color: var(--accent);">
                <span style="margin-left: 0.5rem; font-size: 0.85rem; color: var(--ink60); user-select: none;">Rester connecté</span>
            </label>
        </div>

        {{-- Action --}}
        <button type="submit" class="btn-futuristic" style="margin-top: 0.5rem;">
            <span>Se connecter</span>
            <i class="bi bi-arrow-right-short" style="font-size: 1.2rem;"></i>
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

    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--ink10); text-align: center;">
        <p class="body-small" style="margin-bottom: 0.75rem;">Nouveau sur CapAvenir ?</p>
        <a href="{{ route('register') }}" style="font-size: 0.9rem; font-weight: 700; color: var(--accent); text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
            Créer un compte <i class="bi bi-chevron-right"></i>
        </a>
    </div>
</x-auth-layout>
