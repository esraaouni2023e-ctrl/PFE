<x-auth-layout>
    @section('page-title', 'Connexion')

    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div style="margin-bottom:2rem;">
        <h2 class="heading-3 mb-2">Bon retour ! <em>👋</em></h2>
        <p class="body-small">Prêt à explorer de nouveaux horizons ?</p>
    </div>

    <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:1.25rem;">
        @csrf

        {{-- Email --}}
        <div class="relative">
            <label for="email" style="display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--ink60);margin-bottom:.4rem;">
                Adresse email
            </label>
            <div class="relative">
                <div style="position:absolute;top:50%;left:.9rem;transform:translateY(-50%);color:var(--ink30);pointer-events:none;">
                    <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input
                    id="email" type="email" name="email"
                    value="{{ old('email') }}"
                    required autofocus autocomplete="username"
                    class="input-2026"
                    style="padding-left:2.75rem;"
                    placeholder="votre@email.com"
                >
            </div>
            <x-input-error :messages="$errors->get('email')" style="margin-top:.35rem;font-size:.8rem;color:#c0392b;" />
        </div>

        {{-- Password --}}
        <div class="relative">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.4rem;">
                <label for="password" style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--ink60);">
                    Mot de passe
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size:.75rem;font-weight:600;color:var(--accent);transition:opacity .2s;" onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'">
                        Oublié ?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div style="position:absolute;top:50%;left:.9rem;transform:translateY(-50%);color:var(--ink30);pointer-events:none;">
                    <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input
                    id="password" type="password" name="password"
                    required autocomplete="current-password"
                    class="input-2026"
                    style="padding-left:2.75rem;"
                    placeholder="••••••••"
                >
            </div>
            <x-input-error :messages="$errors->get('password')" style="margin-top:.35rem;font-size:.8rem;color:#c0392b;" />
        </div>

        {{-- Remember Me --}}
        <div style="display:flex;align-items:center;gap:.6rem;">
            <input id="remember_me" type="checkbox" name="remember"
                style="width:16px;height:16px;accent-color:var(--accent);border-radius:3px;cursor:pointer;">
            <label for="remember_me" style="font-size:.875rem;color:var(--ink60);cursor:pointer;">
                Maintenir la connexion
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-futuristic" style="margin-top:.5rem;">
            <span style="position:relative;z-index:10;font-weight:600;letter-spacing:.02em;">Se connecter</span>
            <svg id="login-arrow" style="width:18px;height:18px;margin-left:.5rem;position:relative;z-index:10;transition:transform .3s var(--ease);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </button>
    </form>

    {{-- Footer link --}}
    <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid var(--ink10);text-align:center;">
        <p style="font-size:.875rem;color:var(--ink60);margin-bottom:.75rem;">
            Nouveau sur CapAvenir ?
        </p>
        <a href="{{ route('register') }}"
           style="display:inline-flex;align-items:center;gap:.4rem;font-size:.875rem;font-weight:600;color:var(--accent);transition:opacity .2s;"
           onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'">
            Créer un compte
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>

    <script>
        document.querySelector('.btn-futuristic').addEventListener('mouseenter', () => {
            document.getElementById('login-arrow').style.transform = 'translateX(4px)';
        });
        document.querySelector('.btn-futuristic').addEventListener('mouseleave', () => {
            document.getElementById('login-arrow').style.transform = 'translateX(0)';
        });
    </script>
</x-auth-layout>
