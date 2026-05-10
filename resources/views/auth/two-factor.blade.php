<x-auth-layout>
    @section('page-title', 'Vérification en deux étapes')

    <div style="margin-bottom:2rem;">
        <h2 class="heading-3 mb-2">Vérification <em>🔐</em></h2>
        <p class="body-small">Un code de vérification à 6 chiffres vous a été envoyé par email. Veuillez le saisir ci-dessous pour continuer.</p>
    </div>

    @if (session('success'))
        <div style="padding: 1rem; background-color: rgba(39, 174, 96, 0.1); border: 1px solid #27ae60; color: #27ae60; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.875rem; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('two-factor.store') }}" style="display:flex;flex-direction:column;gap:1.5rem;">
        @csrf

        {{-- Code 2FA --}}
        <div class="relative">
            <label for="two_factor_code" style="display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--ink60);margin-bottom:.4rem;">
                Code de vérification
            </label>
            <div class="relative">
                <div style="position:absolute;top:50%;left:.9rem;transform:translateY(-50%);color:var(--ink30);pointer-events:none;">
                    <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <input
                    id="two_factor_code" type="text" name="two_factor_code"
                    required autofocus maxlength="6" pattern="\d{6}"
                    class="input-2026"
                    style="padding-left:2.75rem; text-align: center; letter-spacing: 0.5em; font-size: 1.25rem; font-weight: 700;"
                    placeholder="000000"
                >
            </div>
            @error('two_factor_code')
                <p style="margin-top:.35rem;font-size:.8rem;color:#c0392b;">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-futuristic">
            <span style="position:relative;z-index:10;font-weight:600;letter-spacing:.02em;">Vérifier le code</span>
            <svg class="btn-arrow" style="width:18px;height:18px;margin-left:.5rem;position:relative;z-index:10;transition:transform .3s var(--ease);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </button>
    </form>

    <div style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem;">
        {{-- Resend Code --}}
        <form method="POST" action="{{ route('two-factor.resend') }}">
            @csrf
            <button type="submit" style="width: 100%; background: none; border: none; color: var(--accent); font-size: 0.875rem; font-weight: 600; cursor: pointer; text-decoration: underline;">
                Renvoyer le code
            </button>
        </form>

        {{-- Logout / Cancel --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="width: 100%; background: none; border: none; color: var(--ink60); font-size: 0.8125rem; cursor: pointer;">
                Annuler la connexion
            </button>
        </form>
    </div>

    <script>
        document.querySelector('.btn-futuristic').addEventListener('mouseenter', () => {
            document.querySelector('.btn-arrow').style.transform = 'translateX(4px)';
        });
        document.querySelector('.btn-futuristic').addEventListener('mouseleave', () => {
            document.querySelector('.btn-arrow').style.transform = 'translateX(0)';
        });

        // Auto-submit when 6 digits are entered
        document.getElementById('two_factor_code').addEventListener('input', function(e) {
            if (this.value.length === 6) {
                this.form.submit();
            }
        });
    </script>
</x-auth-layout>
