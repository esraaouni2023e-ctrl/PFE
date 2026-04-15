<x-auth-layout>
    @section('page-title', 'Inscription')

    <div style="margin-bottom:2rem;">
        <h2 class="heading-3 mb-2">Créer un <em>profil</em></h2>
        <p class="body-small">Initialisez votre trajectoire professionnelle.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:1.1rem;">
        @csrf

        {{-- Name --}}
        <div class="relative">
            <label for="name" style="display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--ink60);margin-bottom:.4rem;">
                Nom complet
            </label>
            <div class="relative">
                <div style="position:absolute;top:50%;left:.9rem;transform:translateY(-50%);color:var(--ink30);pointer-events:none;">
                    <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input
                    id="name" type="text" name="name"
                    value="{{ old('name') }}"
                    required autofocus autocomplete="name"
                    class="input-2026" style="padding-left:2.75rem;"
                    placeholder="Votre nom complet"
                >
            </div>
            <x-input-error :messages="$errors->get('name')" style="margin-top:.35rem;font-size:.8rem;color:#c0392b;" />
        </div>

        {{-- Email --}}
        <div class="relative">
            <label for="email" style="display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--ink60);margin-bottom:.4rem;">
                Email
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
                    required autocomplete="username"
                    class="input-2026" style="padding-left:2.75rem;"
                    placeholder="votre@email.com"
                >
            </div>
            <x-input-error :messages="$errors->get('email')" style="margin-top:.35rem;font-size:.8rem;color:#c0392b;" />
        </div>

        {{-- Passwords row --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.875rem;">
            <div class="relative">
                <label for="password" style="display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--ink60);margin-bottom:.4rem;">
                    Mot de passe
                </label>
                <div class="relative">
                    <div style="position:absolute;top:50%;left:.9rem;transform:translateY(-50%);color:var(--ink30);pointer-events:none;">
                        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input
                        id="password" type="password" name="password"
                        required autocomplete="new-password"
                        class="input-2026" style="padding-left:2.75rem;"
                        placeholder="••••••••"
                    >
                </div>
                <x-input-error :messages="$errors->get('password')" style="margin-top:.35rem;font-size:.8rem;color:#c0392b;" />
            </div>
            <div class="relative">
                <label for="password_confirmation" style="display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--ink60);margin-bottom:.4rem;">
                    Confirmation
                </label>
                <div class="relative">
                    <div style="position:absolute;top:50%;left:.9rem;transform:translateY(-50%);color:var(--ink30);pointer-events:none;">
                        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <input
                        id="password_confirmation" type="password" name="password_confirmation"
                        required autocomplete="new-password"
                        class="input-2026" style="padding-left:2.75rem;"
                        placeholder="••••••••"
                    >
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" style="margin-top:.35rem;font-size:.8rem;color:#c0392b;" />
            </div>
        </div>

        {{-- Role Selection --}}
        <div>
            <label style="display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--ink60);margin-bottom:.6rem;">
                Choisissez votre voie
            </label>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.875rem;">

                {{-- Student --}}
                <label style="cursor:pointer;position:relative;">
                    <input type="radio" name="role" value="student" class="peer"
                        style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border-width:0;" checked>
                    <div class="role-card">
                        <div style="font-size:1.75rem;margin-bottom:.5rem;">🎓</div>
                        <div style="font-family:'Fraunces',serif;font-weight:600;font-size:.95rem;color:var(--ink);margin-bottom:.2rem;">Étudiant</div>
                        <div style="font-size:.75rem;color:var(--ink60);line-height:1.4;">Je veux découvrir mon avenir</div>
                    </div>
                    <div class="role-indicator" style="position:absolute;top:.6rem;right:.6rem;width:14px;height:14px;border-radius:50%;border:2px solid var(--ink10);background:transparent;transition:all .25s var(--ease);"></div>
                </label>

                {{-- Counselor --}}
                <label style="cursor:pointer;position:relative;">
                    <input type="radio" name="role" value="counselor" class="peer"
                        style="position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border-width:0;">
                    <div class="role-card">
                        <div style="font-size:1.75rem;margin-bottom:.5rem;">👨‍🏫</div>
                        <div style="font-family:'Fraunces',serif;font-weight:600;font-size:.95rem;color:var(--ink);margin-bottom:.2rem;">Conseiller</div>
                        <div style="font-size:.75rem;color:var(--ink60);line-height:1.4;">Je guide les talents</div>
                    </div>
                    <div class="role-indicator" style="position:absolute;top:.6rem;right:.6rem;width:14px;height:14px;border-radius:50%;border:2px solid var(--ink10);background:transparent;transition:all .25s var(--ease);"></div>
                </label>

            </div>
            <x-input-error :messages="$errors->get('role')" style="margin-top:.35rem;font-size:.8rem;color:#c0392b;" />
        </div>

        {{-- Submit --}}
        <div style="padding-top:.5rem;">
            <button type="submit" class="btn-futuristic">
                <span style="position:relative;z-index:10;font-weight:600;letter-spacing:.02em;">Créer mon compte</span>
                <svg id="reg-arrow" style="width:18px;height:18px;margin-left:.5rem;position:relative;z-index:10;transition:transform .3s var(--ease);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </button>
        </div>
    </form>

    {{-- Footer link --}}
    <div style="margin-top:1.75rem;padding-top:1.5rem;border-top:1px solid var(--ink10);text-align:center;">
        <p style="font-size:.875rem;color:var(--ink60);margin-bottom:.6rem;">
            Déjà membre de CapAvenir ?
        </p>
        <a href="{{ route('login') }}"
           style="display:inline-flex;align-items:center;gap:.4rem;font-size:.875rem;font-weight:600;color:var(--accent);transition:opacity .2s;"
           onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'">
            <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14"/>
            </svg>
            Se connecter
        </a>
    </div>

    <style>
        @media (max-width: 540px) {
            div:has(> .role-card) { grid-template-columns: 1fr !important; }
            div:has(> #password) { grid-template-columns: 1fr !important; }
        }
    </style>

    <script>
        const btn = document.querySelector('.btn-futuristic');
        const arr = document.getElementById('reg-arrow');
        if (btn && arr) {
            btn.addEventListener('mouseenter', () => arr.style.transform = 'translateX(4px)');
            btn.addEventListener('mouseleave', () => arr.style.transform = 'translateX(0)');
        }
    </script>
</x-auth-layout>
