<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CapAvenir') }} — @yield('page-title', 'Authentification')</title>

        <!-- Variable font for expressive headline + body font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400;1,9..40,500;1,9..40,600;1,9..40,700;1,9..40,800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* ═══════════════════════════════════════════════════════
               CAPAVENIR 2026 — Ultra-Premium Auth
               Liquid Glassmorphism · Volumetric Lighting
               Welcome page unified design tokens
               ═══════════════════════════════════════════════════════ */

            :root {
                --bg-deep: #06091F;
                --bg-mid: #0C1033;
                --bg-card: rgba(12, 16, 51, 0.55);
                --glass-border: rgba(255,255,255,0.06);
                --glass-border-hover: rgba(255,255,255,0.14);

                --violet: #8B5CF6;
                --violet-glow: rgba(139,92,246,0.25);
                --cyan: #06D6A0;
                --cyan-glow: rgba(6,214,160,0.2);
                --magenta: #EC4899;
                --magenta-glow: rgba(236,72,153,0.2);
                --lime: #84CC16;
                --lime-glow: rgba(132,204,22,0.2);

                --white: #FAFBFF;
                --gray-100: #E2E8F0;
                --gray-200: #CBD5E1;
                --gray-300: #94A3B8;
                --gray-400: #64748B;
                --gray-500: #475569;
                --gray-600: #334155;
                --error: #EF4444;
            }

            *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

            body {
                font-family: 'Inter', -apple-system, sans-serif;
                background: var(--bg-deep);
                color: var(--white);
                min-height: 100vh;
                overflow-x: hidden;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            /* ═══ BACKGROUND — welcome.blade.php tokens ═══ */
            .bg-canvas {
                position: fixed; inset: 0; z-index: 0;
                background:
                    radial-gradient(ellipse at 15% 15%, rgba(139,92,246,.12) 0%, transparent 55%),
                    radial-gradient(ellipse at 85% 85%, rgba(6,214,160,.08) 0%, transparent 55%),
                    radial-gradient(ellipse at 50% 50%, rgba(236,72,153,.05) 0%, transparent 60%);
                background-color: var(--bg-deep);
            }
            .bg-canvas::before {
                content: '';
                position: absolute; inset: 0;
                background-image:
                    linear-gradient(rgba(139,92,246,0.025) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(139,92,246,0.025) 1px, transparent 1px);
                background-size: 80px 80px;
                animation: gridDrift 80s linear infinite;
                opacity: 0.5;
            }
            @keyframes gridDrift {
                0% { transform: translate(0,0); }
                100% { transform: translate(80px,80px); }
            }

            /* Volumetric soft light — cinematic warmth */
            .volumetric-light {
                position: fixed; inset: 0; z-index: 0; pointer-events: none;
                background:
                    radial-gradient(circle at 50% 20%, rgba(139,92,246,.06) 0%, transparent 50%),
                    radial-gradient(circle at 50% 80%, rgba(6,214,160,.04) 0%, transparent 45%);
            }

            /* Orbs — welcome.blade.php */
            .orb {
                position: fixed; border-radius: 50%;
                filter: blur(100px); pointer-events: none;
                animation: orbFloat 28s ease-in-out infinite;
            }
            .orb-1 { width: 500px; height: 500px; background: var(--violet-glow); top: -15%; right: 5%; }
            .orb-2 { width: 400px; height: 400px; background: var(--cyan-glow); bottom: 5%; left: 10%; animation-delay: -9s; }
            .orb-3 { width: 350px; height: 350px; background: var(--magenta-glow); top: 40%; left: 60%; animation-delay: -18s; }
            .orb-4 { width: 250px; height: 250px; background: var(--lime-glow); top: 70%; right: 20%; animation-delay: -5s; }
            @keyframes orbFloat {
                0%, 100% { transform: translate(0,0) scale(1); }
                33%  { transform: translate(60px,-40px) scale(1.12); }
                66%  { transform: translate(-40px,50px) scale(.88); }
            }

            /* Career constellation canvas */
            #constellationCanvas {
                position: fixed; inset: 0; z-index: 0; pointer-events: none;
            }

            /* ═══ AUTH LAYOUT — vertical centered ═══ */
            .auth-center {
                position: relative; z-index: 1;
                min-height: 100vh;
                display: flex; flex-direction: column;
                align-items: center; justify-content: center;
                padding: 2.5rem 1.25rem;
            }

            /* ═══ LOGO ═══ */
            .brand {
                margin-bottom: 2.5rem;
                animation: slideDown .7s cubic-bezier(.16,1,.3,1);
            }
            .brand a {
                display: flex; align-items: center; gap: .875rem;
                text-decoration: none;
                transition: transform .3s ease;
            }
            .brand:hover a { transform: translateX(3px); }
            .brand img {
                width: 46px; height: 46px; border-radius: 14px;
                object-fit: cover;
                box-shadow: 0 0 30px var(--violet-glow);
                border: 2px solid rgba(139,92,246,.3);
            }
            .brand-name {
                font-family: 'Space Grotesk', sans-serif;
                font-size: 1.5rem; font-weight: 700; letter-spacing: -.02em;
                background: linear-gradient(135deg, #fff 30%, var(--cyan));
                -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            }

            /* ═══════════════════════════════════════════
               FROSTED TRANSLUCENT CARD
               blur(24px) + soft inner glow + neon edge
               ═══════════════════════════════════════════ */
            .glass-card-wrap {
                width: 100%; max-width: 460px;
                position: relative;
                animation: cardUp .9s cubic-bezier(.16,1,.3,1) .12s backwards;
            }

            /* Neon edge highlight — violet-cyan gradient */
            .glass-card-wrap::before {
                content: '';
                position: absolute; inset: -1.5px;
                border-radius: 29px;
                background: linear-gradient(160deg, var(--violet), var(--cyan), var(--violet));
                background-size: 300% 300%;
                animation: edgeGlow 8s ease-in-out infinite;
                opacity: 0.2;
                transition: opacity .5s ease;
            }
            .glass-card-wrap:hover::before { opacity: 0.35; }

            .glass-card {
                position: relative; z-index: 1;
                background: var(--bg-card);
                backdrop-filter: blur(24px) saturate(140%);
                -webkit-backdrop-filter: blur(24px) saturate(140%);
                border: 1px solid var(--glass-border);
                border-radius: 28px;
                padding: 3rem 2.75rem 2.75rem;
                box-shadow:
                    0 24px 64px rgba(0,0,0,.45),
                    inset 0 1px 0 rgba(255,255,255,.04),
                    inset 0 0 60px rgba(139,92,246,.015);
                transition: border-color .4s ease;
            }
            .glass-card-wrap:hover .glass-card {
                border-color: var(--glass-border-hover);
            }
            /* Top accent bar */
            .glass-card::before {
                content: '';
                position: absolute; top: 0; left: 0; right: 0; height: 2px;
                background: linear-gradient(90deg, var(--violet), var(--cyan), var(--magenta));
                background-size: 200% 100%;
                animation: gradShift 6s ease-in-out infinite;
                border-radius: 28px 28px 0 0;
                opacity: .7;
            }

            @keyframes edgeGlow {
                0%,100% { background-position: 0% 50%; }
                50%     { background-position: 100% 50%; }
            }
            @keyframes gradShift {
                0%,100% { background-position: 0% 50%; }
                50%     { background-position: 100% 50%; }
            }
            @keyframes cardUp {
                from { opacity: 0; transform: translateY(36px) scale(.97); }
                to   { opacity: 1; transform: translateY(0) scale(1); }
            }
            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-28px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* ═══ HEADLINE — variable font, bold·italic mix ═══ */
            .hero-block {
                text-align: center;
                margin-bottom: 2.5rem;
                animation: fadeIn .9s cubic-bezier(.16,1,.3,1) .25s backwards;
            }
            .hero-title {
                font-family: 'DM Sans', sans-serif;
                font-size: 2rem;
                font-weight: 800;
                line-height: 1.18;
                letter-spacing: -.03em;
                margin-bottom: .5rem;
                color: var(--white);
            }
            .hero-title em {
                font-style: italic;
                font-weight: 700;
                background: linear-gradient(135deg, var(--cyan) 0%, var(--violet) 50%, var(--magenta) 100%);
                -webkit-background-clip: text; -webkit-text-fill-color: transparent;
                background-clip: text;
                background-size: 200% 200%;
                animation: gradShift 6s ease-in-out infinite;
            }
            .hero-sub {
                color: var(--gray-300);
                font-size: .875rem;
                font-weight: 400;
                line-height: 1.6;
                margin-bottom: 1rem;
            }
            .hero-hint {
                display: inline-flex; align-items: center; gap: .5rem;
                padding: .4375rem 1rem;
                border-radius: 50px;
                background: rgba(6,214,160,.05);
                border: 1px solid rgba(6,214,160,.12);
                color: var(--cyan);
                font-size: .8rem; font-weight: 600;
            }
            .hero-hint .name { color: var(--white); font-weight: 700; }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(16px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* ═══ GLASS INPUT FIELDS ═══ */
            .field {
                position: relative;
                margin-bottom: 1.25rem;
            }
            .field-input {
                width: 100%;
                padding: 1.125rem 2.875rem 0.5rem 1.125rem;
                background: rgba(255,255,255,.02);
                border: 1.5px solid rgba(255,255,255,.06);
                border-radius: 14px;
                color: var(--white);
                font-size: .9375rem;
                font-family: 'Inter', sans-serif;
                outline: none;
                caret-color: var(--cyan);
                transition: all .3s cubic-bezier(.4,0,.2,1);
                box-shadow: inset 0 2px 6px rgba(0,0,0,.15);
            }
            .field-label {
                position: absolute;
                top: 50%; left: 1.125rem;
                transform: translateY(-50%);
                color: var(--gray-400);
                font-size: .9375rem;
                font-weight: 400;
                pointer-events: none;
                transition: all .25s cubic-bezier(.4,0,.2,1);
            }
            /* Float up on focus / filled */
            .field-input:focus ~ .field-label,
            .field-input:not(:placeholder-shown) ~ .field-label {
                top: .35rem;
                transform: translateY(0);
                font-size: .625rem;
                font-weight: 700;
                color: var(--cyan);
                letter-spacing: .07em;
                text-transform: uppercase;
            }
            /* Micro cyan glow on focus */
            .field-input:focus {
                border-color: rgba(6,214,160,.35);
                background: rgba(6,214,160,.02);
                box-shadow:
                    inset 0 2px 6px rgba(0,0,0,.12),
                    0 0 0 3px rgba(6,214,160,.07),
                    0 0 16px rgba(6,214,160,.04);
            }
            .field-icon {
                position: absolute;
                right: 1rem; top: 50%; transform: translateY(-50%);
                color: var(--gray-500);
                pointer-events: none;
                transition: color .3s ease;
            }
            .field-input:focus ~ .field-icon { color: var(--cyan); }

            .field .eye-toggle {
                position: absolute;
                right: 1rem; top: 50%; transform: translateY(-50%);
                background: none; border: none;
                color: var(--gray-500);
                cursor: pointer; padding: .25rem;
                transition: color .3s ease;
                line-height: 0;
            }
            .field .eye-toggle:hover { color: var(--cyan); }

            /* ═══ META ROW ═══ */
            .meta-row {
                display: flex; align-items: center; justify-content: space-between;
                margin: .375rem 0 1.75rem;
            }
            .remember-label {
                display: flex; align-items: center; gap: .5rem; cursor: pointer;
            }
            .remember-label input[type="checkbox"] {
                width: 15px; height: 15px;
                accent-color: var(--violet);
                cursor: pointer;
                border-radius: 3px;
            }
            .remember-label span {
                color: var(--gray-300); font-size: .8rem; font-weight: 500;
            }
            .reset-link {
                color: var(--gray-300); text-decoration: none;
                font-size: .8rem; font-weight: 500;
                position: relative;
                transition: color .25s ease;
            }
            .reset-link::after {
                content: '';
                position: absolute; bottom: -2px; left: 0;
                width: 0; height: 1px;
                background: var(--cyan);
                transition: width .3s ease;
            }
            .reset-link:hover { color: var(--cyan); }
            .reset-link:hover::after { width: 100%; }

            /* ═══ SUBMIT ═══ */
            .btn-go {
                width: 100%;
                padding: 1rem 2rem;
                background: linear-gradient(135deg, var(--violet), var(--magenta));
                color: #fff;
                font-weight: 700; font-size: .9375rem;
                font-family: 'Inter', sans-serif;
                border: none; border-radius: 14px;
                cursor: pointer;
                position: relative; overflow: hidden;
                transition: all .35s cubic-bezier(.4,0,.2,1);
                box-shadow: 0 4px 24px var(--violet-glow);
                letter-spacing: .01em;
            }
            .btn-go::before {
                content: '';
                position: absolute; inset: 0;
                background: linear-gradient(135deg, rgba(255,255,255,.1), transparent 60%);
                opacity: 0; transition: opacity .3s ease;
            }
            .btn-go:hover {
                transform: translateY(-3px) scale(1.02);
                box-shadow: 0 16px 48px var(--violet-glow);
            }
            .btn-go:hover::before { opacity: 1; }
            .btn-go:active { transform: translateY(-1px) scale(.99); }
            .btn-go .btn-label {
                position: relative; z-index: 1;
                display: flex; align-items: center; justify-content: center; gap: .5rem;
            }
            .btn-go .arrow-icon {
                transition: transform .3s cubic-bezier(.4,0,.2,1);
            }
            .btn-go:hover .arrow-icon { transform: translateX(4px); }

            /* ═══ DIVIDER ═══ */
            .sep {
                display: flex; align-items: center; gap: 1rem;
                margin: 1.75rem 0;
            }
            .sep::before, .sep::after {
                content: ''; flex: 1; height: 1px;
                background: rgba(255,255,255,.05);
            }
            .sep span {
                color: var(--gray-500);
                font-size: .6875rem; font-weight: 600;
                letter-spacing: .08em; text-transform: uppercase;
                white-space: nowrap;
            }

            /* ═══ SOCIAL — 3D subtle lift ═══ */
            .social-pair {
                display: grid; grid-template-columns: 1fr 1fr; gap: .75rem;
            }
            .s-btn {
                display: flex; align-items: center; justify-content: center; gap: .625rem;
                padding: .875rem 1rem;
                background: rgba(255,255,255,.025);
                border: 1.5px solid rgba(255,255,255,.07);
                border-radius: 14px;
                color: var(--gray-100);
                font-size: .8125rem; font-weight: 600;
                font-family: 'Inter', sans-serif;
                cursor: pointer; text-decoration: none;
                transition: all .4s cubic-bezier(.4,0,.2,1);
                box-shadow:
                    0 2px 8px rgba(0,0,0,.15),
                    inset 0 1px 0 rgba(255,255,255,.03);
                position: relative;
            }
            .s-btn::after {
                content: '';
                position: absolute; inset: 0; border-radius: 14px;
                background: linear-gradient(180deg, rgba(255,255,255,.03), transparent 50%);
                opacity: 0; transition: opacity .3s ease;
            }
            .s-btn:hover {
                transform: translateY(-4px);
                border-color: rgba(255,255,255,.14);
                box-shadow:
                    0 12px 32px rgba(0,0,0,.3),
                    0 2px 12px rgba(139,92,246,.06);
            }
            .s-btn:hover::after { opacity: 1; }
            .s-btn svg { width: 20px; height: 20px; flex-shrink: 0; position: relative; z-index: 1; }
            .s-btn span { position: relative; z-index: 1; }

            /* ═══ REGISTER FOOTER ═══ */
            .bottom-link {
                margin-top: 2rem;
                text-align: center;
                padding-top: 1.5rem;
                border-top: 1px solid rgba(255,255,255,.04);
            }
            .bottom-link p {
                color: var(--gray-400); font-size: .8125rem;
                margin-bottom: .375rem;
            }
            .bottom-link a {
                color: var(--gray-200); text-decoration: none;
                font-weight: 600; font-size: .875rem;
                display: inline-flex; align-items: center; gap: .375rem;
                position: relative;
                transition: color .25s ease;
            }
            .bottom-link a::after {
                content: '';
                position: absolute; bottom: -2px; left: 0;
                width: 0; height: 1.5px;
                background: var(--cyan);
                transition: width .3s ease;
            }
            .bottom-link a:hover { color: var(--cyan); }
            .bottom-link a:hover::after { width: 100%; }
            .bottom-link a svg {
                transition: transform .3s ease;
            }
            .bottom-link a:hover svg { transform: translateX(3px); }

            /* ═══ ERROR ═══ */
            .err { color: var(--error); font-size: .75rem; margin-top: .375rem; font-weight: 500; }

            /* ═══ TRUST ═══ */
            .trust {
                display: flex; align-items: center; justify-content: center; gap: .375rem;
                margin-top: 1.5rem;
                color: var(--gray-500);
                font-size: .6875rem; font-weight: 500;
                letter-spacing: .03em;
                animation: fadeIn .9s cubic-bezier(.16,1,.3,1) .6s backwards;
            }
            .trust svg { width: 13px; height: 13px; color: var(--cyan); }

            /* ═══ RESPONSIVE ═══ */
            @media (max-width: 520px) {
                .glass-card {
                    padding: 2.25rem 1.5rem 2rem;
                    border-radius: 24px;
                }
                .glass-card-wrap::before { border-radius: 25.5px; }
                .hero-title { font-size: 1.625rem; }
                .social-pair { grid-template-columns: 1fr; }
            }

            ::selection { background: rgba(6,214,160,.25); color: #fff; }

            /* ═══ LEGACY BRIDGE (register.blade.php) ═══ */
            .form-header{text-align:center;margin-bottom:2rem}
            .form-title{font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:700;color:var(--white)}
            .form-description{color:var(--gray-300);font-size:.9rem;margin-top:.25rem}
            .form-group{margin-bottom:1.5rem}
            .form-label{display:block;color:var(--gray-100);font-size:.8125rem;font-weight:600;margin-bottom:.5rem;letter-spacing:.02em}
            .form-input{width:100%;padding:.9rem 1.125rem;background:rgba(255,255,255,.02);border:1.5px solid rgba(255,255,255,.06);border-radius:14px;color:var(--white);font-size:.9375rem;font-family:'Inter',sans-serif;transition:all .3s ease;outline:none;box-shadow:inset 0 2px 6px rgba(0,0,0,.15)}
            .form-input::placeholder{color:var(--gray-500)}
            .form-input:focus{border-color:rgba(6,214,160,.35);background:rgba(6,214,160,.02);box-shadow:inset 0 2px 6px rgba(0,0,0,.12),0 0 0 3px rgba(6,214,160,.07),0 0 16px rgba(6,214,160,.04)}
            .form-extras{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem}
            .form-checkbox-group{display:flex;align-items:center;gap:.5rem}
            .form-checkbox{accent-color:var(--violet);width:15px;height:15px}
            .checkbox-label{color:var(--gray-300);font-size:.8rem;cursor:pointer}
            .btn-primary{width:100%;padding:1rem 2rem;background:linear-gradient(135deg,var(--violet),var(--magenta));color:#fff;font-weight:700;font-size:.9375rem;border:none;border-radius:14px;cursor:pointer;font-family:'Inter',sans-serif;transition:all .35s cubic-bezier(.4,0,.2,1);box-shadow:0 4px 24px var(--violet-glow)}
            .btn-primary:hover{transform:translateY(-3px) scale(1.02);box-shadow:0 16px 48px var(--violet-glow)}
            .form-footer{margin-top:2rem;text-align:center;padding-top:1.5rem;border-top:1px solid rgba(255,255,255,.04)}
            .footer-text{color:var(--gray-400);font-size:.8125rem;margin-bottom:.375rem}
            .footer-link{color:var(--gray-200);text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:.375rem;transition:color .25s ease}
            .footer-link:hover{color:var(--cyan)}
            .forgot-link{color:var(--gray-300);text-decoration:none;font-size:.8rem;font-weight:500;transition:color .25s ease}
            .forgot-link:hover{color:var(--cyan)}
            .error-text{color:var(--error);font-size:.75rem;margin-top:.375rem;font-weight:500}
            .select-field{appearance:none;background-image:url('data:image/svg+xml,%3Csvg width=%2212%22 height=%228%22 viewBox=%220 0 12 8%22 fill=%22none%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cpath d=%22M1 1.5L6 6.5L11 1.5%22 stroke=%22%238B5CF6%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3C/svg%3E');background-repeat:no-repeat;background-position:right 1.125rem center;cursor:pointer}
            .select-field option{background:var(--bg-mid);color:var(--white)}
            .form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
            @media(max-width:540px){.form-row{grid-template-columns:1fr}}
        </style>
    </head>
    <body>
        <!-- Background — welcome tokens -->
        <div class="bg-canvas">
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
            <div class="orb orb-4"></div>
        </div>
        <div class="volumetric-light"></div>
        <canvas id="constellationCanvas"></canvas>

        <div class="auth-center">
            <!-- Logo -->
            <div class="brand">
                <a href="/">
                    <img src="{{ asset('im1.jpg') }}" alt="CapAvenir">
                    <span class="brand-name">CapAvenir</span>
                </a>
            </div>

            <!-- Frosted glass card with neon edge -->
            <div class="glass-card-wrap">
                <div class="glass-card">
                    {{ $slot }}
                </div>
            </div>

            <!-- Trust -->
            <div class="trust">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                Connexion sécurisée · Chiffrement de bout en bout
            </div>
        </div>

        <!-- Career Constellation — very subtle floating icons -->
        <script>
        (() => {
            const c = document.getElementById('constellationCanvas');
            const ctx = c.getContext('2d');
            let W, H;

            function resize() { W = c.width = innerWidth; H = c.height = innerHeight; }
            resize();
            addEventListener('resize', resize);

            // Career icons drawn as paths
            const icons = [
                // Briefcase
                (x, y, s, a) => {
                    ctx.save(); ctx.translate(x, y); ctx.globalAlpha = a;
                    ctx.strokeStyle = 'rgba(139,92,246,.35)'; ctx.lineWidth = 1.2;
                    ctx.strokeRect(-s/2, -s/3, s, s*.65);
                    ctx.beginPath(); ctx.moveTo(-s*.2, -s/3); ctx.lineTo(-s*.2, -s*.55);
                    ctx.lineTo(s*.2, -s*.55); ctx.lineTo(s*.2, -s/3); ctx.stroke();
                    ctx.restore();
                },
                // Graduation cap
                (x, y, s, a) => {
                    ctx.save(); ctx.translate(x, y); ctx.globalAlpha = a;
                    ctx.strokeStyle = 'rgba(6,214,160,.3)'; ctx.lineWidth = 1.2;
                    ctx.beginPath(); ctx.moveTo(0, -s*.4); ctx.lineTo(s*.55, 0);
                    ctx.lineTo(0, s*.2); ctx.lineTo(-s*.55, 0); ctx.closePath(); ctx.stroke();
                    ctx.beginPath(); ctx.moveTo(-s*.35, s*.05); ctx.lineTo(-s*.35, s*.4); ctx.stroke();
                    ctx.restore();
                },
                // Lightbulb
                (x, y, s, a) => {
                    ctx.save(); ctx.translate(x, y); ctx.globalAlpha = a;
                    ctx.strokeStyle = 'rgba(236,72,153,.25)'; ctx.lineWidth = 1.2;
                    ctx.beginPath(); ctx.arc(0, -s*.1, s*.3, Math.PI*1.15, Math.PI*1.85); ctx.stroke();
                    ctx.beginPath(); ctx.moveTo(-s*.15, s*.2); ctx.lineTo(-s*.15, s*.35);
                    ctx.lineTo(s*.15, s*.35); ctx.lineTo(s*.15, s*.2); ctx.stroke();
                    ctx.restore();
                },
                // Compass / path
                (x, y, s, a) => {
                    ctx.save(); ctx.translate(x, y); ctx.globalAlpha = a;
                    ctx.strokeStyle = 'rgba(132,204,22,.25)'; ctx.lineWidth = 1.2;
                    ctx.beginPath(); ctx.arc(0, 0, s*.35, 0, Math.PI*2); ctx.stroke();
                    ctx.beginPath(); ctx.moveTo(0, -s*.2); ctx.lineTo(s*.1, s*.1);
                    ctx.lineTo(-s*.1, s*.05); ctx.closePath(); ctx.stroke();
                    ctx.restore();
                }
            ];

            class FloatingIcon {
                constructor() { this.init(); }
                init() {
                    this.x = Math.random() * W;
                    this.y = Math.random() * H;
                    this.vx = (Math.random() - .5) * .12;
                    this.vy = (Math.random() - .5) * .12;
                    this.size = Math.random() * 18 + 14;
                    this.baseAlpha = Math.random() * .06 + .02;
                    this.alpha = this.baseAlpha;
                    this.pulseSpeed = Math.random() * .002 + .0008;
                    this.pulseOffset = Math.random() * Math.PI * 2;
                    this.iconIdx = Math.floor(Math.random() * icons.length);
                    this.rotation = Math.random() * .3 - .15;
                }
                update(t) {
                    this.x += this.vx;
                    this.y += this.vy;
                    this.alpha = this.baseAlpha + Math.sin(t * this.pulseSpeed + this.pulseOffset) * .015;
                    if (this.x < -40) this.x = W + 40;
                    if (this.x > W + 40) this.x = -40;
                    if (this.y < -40) this.y = H + 40;
                    if (this.y > H + 40) this.y = -40;
                }
                draw(t) {
                    ctx.save();
                    ctx.translate(this.x, this.y);
                    ctx.rotate(this.rotation + Math.sin(t * .001) * .05);
                    icons[this.iconIdx](0, 0, this.size, this.alpha);
                    ctx.restore();
                }
            }

            // Soft particles (dots)
            class SoftDot {
                constructor() { this.init(); }
                init() {
                    this.x = Math.random() * W;
                    this.y = Math.random() * H;
                    this.vx = (Math.random() - .5) * .25;
                    this.vy = (Math.random() - .5) * .25;
                    this.r = Math.random() * 1.5 + .4;
                    this.alpha = Math.random() * .3 + .05;
                    const palette = ['139,92,246','6,214,160','236,72,153','132,204,22'];
                    this.color = palette[Math.floor(Math.random() * palette.length)];
                }
                update() {
                    this.x += this.vx; this.y += this.vy;
                    if (this.x < 0 || this.x > W || this.y < 0 || this.y > H) this.init();
                }
                draw() {
                    // Soft bokeh glow
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.r * 3, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(${this.color},${this.alpha * .12})`;
                    ctx.fill();
                    // Core
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(${this.color},${this.alpha})`;
                    ctx.fill();
                }
            }

            const floatingIcons = [];
            const dots = [];
            const iconCount = Math.min(12, Math.floor((W * H) / 100000));
            const dotCount = Math.min(50, Math.floor((W * H) / 30000));

            for (let i = 0; i < iconCount; i++) floatingIcons.push(new FloatingIcon());
            for (let i = 0; i < dotCount; i++) dots.push(new SoftDot());

            let t = 0;
            function loop() {
                t++;
                ctx.clearRect(0, 0, W, H);

                // Dots
                dots.forEach(d => { d.update(); d.draw(); });

                // Connection lines
                for (let i = 0; i < dots.length; i++) {
                    for (let j = i + 1; j < dots.length; j++) {
                        const dx = dots[i].x - dots[j].x;
                        const dy = dots[i].y - dots[j].y;
                        const dist = Math.sqrt(dx*dx + dy*dy);
                        if (dist < 150) {
                            ctx.beginPath();
                            ctx.moveTo(dots[i].x, dots[i].y);
                            ctx.lineTo(dots[j].x, dots[j].y);
                            ctx.strokeStyle = `rgba(139,92,246,${.05*(1-dist/150)})`;
                            ctx.lineWidth = .5;
                            ctx.stroke();
                        }
                    }
                }

                // Career icons
                floatingIcons.forEach(fi => { fi.update(t); fi.draw(t); });

                requestAnimationFrame(loop);
            }
            loop();
        })();
        </script>
    </body>
</html>
