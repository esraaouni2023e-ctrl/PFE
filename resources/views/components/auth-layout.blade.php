<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page-title', 'Authentification') — CapAvenir</title>

    {{-- Welcome-page fonts: DM Sans (body) + Fraunces (display) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300;1,9..40,400&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;1,9..144,300;1,9..144,400;1,9..144,600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ══════════════════════════════════════════
           WELCOME PAGE DESIGN TOKENS — AUTH EDITION
           ══════════════════════════════════════════ */
        :root {
            --ink: #0b0c10;
            --paper: #f7f5f0;
            --cream: #ede9e1;
            --warm: #e8e1d4;
            --accent:  #d4622a;
            --accent2: #1a4f6e;
            --accent3: #4a7c59;
            --gold:    #c8973a;
            --ink60: rgba(11,12,16,.6);
            --ink30: rgba(11,12,16,.3);
            --ink10: rgba(11,12,16,.1);
            --ink05: rgba(11,12,16,.05);
            --r:  6px;
            --rl: 16px;
            --rx: 999px;
            --ease: cubic-bezier(.16,1,.3,1);
        }
        [data-theme="dark"] {
            --ink:   #f0ede6;
            --paper: #10100d;
            --cream: #18170f;
            --warm:  #1f1e14;
            --ink60: rgba(240,237,230,.6);
            --ink30: rgba(240,237,230,.3);
            --ink10: rgba(240,237,230,.08);
            --ink05: rgba(240,237,230,.04);
        }

        /* ── RESET ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 16px; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--paper);
            color: var(--ink);
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; display: block; }

        /* ── NOISE TEXTURE (same as welcome) ── */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.04'/%3E%3C/svg%3E");
            opacity: .5;
        }

        /* ── AMBIENT DECORATIVE CIRCLE (welcome hero orb) ── */
        .auth-orb {
            position: fixed;
            width: clamp(300px, 45vw, 560px);
            height: clamp(300px, 45vw, 560px);
            border-radius: 50%;
            background: radial-gradient(circle at 35% 40%,
                color-mix(in srgb, var(--accent) 14%, transparent),
                color-mix(in srgb, var(--accent2) 10%, transparent) 45%,
                transparent 70%);
            right: -8%; top: 50%; transform: translateY(-50%);
            z-index: 0; pointer-events: none;
            animation: orbBreath 10s ease-in-out infinite;
        }
        @keyframes orbBreath {
            0%,100% { transform: translateY(-50%) scale(1); }
            50%      { transform: translateY(-54%) scale(1.07); }
        }

        /* ── PAGE LAYOUT ── */
        .auth-page {
            position: relative; z-index: 1;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 2rem 1.25rem;
        }

        /* ── SPLIT CARD ── */
        .auth-card {
            width: 100%;
            max-width: 980px;
            display: grid;
            grid-template-columns: 1fr 1.1fr;
            border: 1px solid var(--ink10);
            border-radius: var(--rl);
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,.12);
            animation: cardEnter .85s var(--ease) both;
        }
        @keyframes cardEnter {
            from { opacity: 0; transform: translateY(32px) scale(.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ══════════════════════
           LEFT VISUAL PANEL
           ══════════════════════ */
        .auth-visual {
            background: var(--accent2);
            padding: 3.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        /* Grain overlay on panel */
        .auth-visual::before {
            content: '';
            position: absolute; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.06'/%3E%3C/svg%3E");
            opacity: .6; pointer-events: none;
        }

        /* Radial accent glow inside panel */
        .auth-visual::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, color-mix(in srgb, var(--accent) 30%, transparent), transparent 65%);
            right: -20%; bottom: -20%;
            pointer-events: none;
        }

        .visual-inner { position: relative; z-index: 1; }

        /* Logo */
        .visual-logo {
            display: flex; align-items: center; gap: .65rem;
            margin-bottom: 3rem;
        }
        .logo-mark {
            width: 52px; height: 52px;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .logo-mark img { width: 100%; height: 100%; object-fit: contain; }
        .logo-name {
            font-family: 'Fraunces', serif; font-size: 2rem; font-weight: 600;
            letter-spacing: -.04em; color: #fff;
        }

        /* Headline on visual panel */
        .visual-heading {
            font-family: 'Fraunces', serif;
            font-size: clamp(2rem, 3.5vw, 3rem);
            font-weight: 300; line-height: 1.1; letter-spacing: -.04em;
            color: #fff; margin-bottom: 1.25rem;
        }
        .visual-heading em { font-style: italic; color: var(--accent); }

        .visual-sub {
            font-size: .95rem; color: rgba(255,255,255,.65);
            line-height: 1.75; max-width: 320px; margin-bottom: 2.5rem;
        }

        /* Feature pills */
        .visual-pills { display: flex; flex-direction: column; gap: .75rem; }
        .vpill {
            display: inline-flex; align-items: center; gap: .625rem;
            padding: .5rem .875rem;
            border-radius: var(--rx);
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.12);
            color: rgba(255,255,255,.85);
            font-size: .82rem; font-weight: 500;
            backdrop-filter: blur(8px);
            width: fit-content;
        }
        .vpill-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--accent3);
            box-shadow: 0 0 6px color-mix(in srgb, var(--accent3) 60%, transparent);
            animation: dotPulse 2s ease-in-out infinite;
        }
        .vpill-dot.d2 { background: var(--gold); animation-delay: -.7s; }
        .vpill-dot.d3 { background: var(--accent); animation-delay: -1.4s; }
        @keyframes dotPulse {
            0%,100% { opacity: 1; }
            50%      { opacity: .4; }
        }

        /* Stats row at bottom of panel */
        .visual-stats {
            display: flex; gap: 2rem; margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,.1);
        }
        .vstat-num {
            font-family: 'Fraunces', serif;
            font-size: 1.75rem; font-weight: 600;
            letter-spacing: -.04em; color: #fff;
            display: block; line-height: 1;
        }
        .vstat-lbl { font-size: .72rem; color: rgba(255,255,255,.5); margin-top: .2rem; }

        /* ══════════════════════
           RIGHT FORM PANEL
           ══════════════════════ */
        .auth-form-side {
            background: var(--paper);
            padding: 3.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        /* Top security badge */
        .sec-badge {
            position: absolute; top: 1.25rem; right: 1.25rem;
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .3rem .8rem;
            border-radius: var(--rx);
            background: color-mix(in srgb, var(--accent3) 10%, transparent);
            border: 1px solid color-mix(in srgb, var(--accent3) 25%, transparent);
            font-size: .7rem; font-weight: 600; letter-spacing: .05em;
            color: var(--accent3);
        }
        .sec-badge svg { width: 12px; height: 12px; }

        /* ── Typography helpers ── */
        .heading-3 {
            font-family: 'Fraunces', serif;
            font-size: clamp(1.5rem, 3vw, 2.1rem);
            font-weight: 300; letter-spacing: -.03em; line-height: 1.15;
            color: var(--ink);
        }
        .heading-3 em { font-style: italic; color: var(--accent); }
        .body-small { font-size: .9rem; color: var(--ink60); }
        .mb-2 { margin-bottom: .5rem; }
        .mb-8 { margin-bottom: 2rem; }

        /* ── INPUTS ── */
        .input-2026 {
            width: 100%;
            padding: .875rem 1.1rem;
            font-family: 'DM Sans', sans-serif;
            font-size: .95rem;
            color: var(--ink);
            background: var(--ink05);
            border: 1.5px solid var(--ink10);
            border-radius: var(--r);
            outline: none;
            transition: all .25s var(--ease);
            caret-color: var(--accent);
        }
        .input-2026::placeholder { color: var(--ink30); }
        .input-2026:focus {
            border-color: var(--accent);
            background: color-mix(in srgb, var(--accent) 4%, var(--paper));
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 15%, transparent);
        }

        /* ── SUBMIT BUTTON ── */
        .btn-futuristic {
            position: relative;
            display: flex; align-items: center; justify-content: center;
            width: 100%;
            padding: 1rem 2rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem; font-weight: 600;
            color: #fff;
            background: var(--accent);
            border: none; border-radius: var(--r);
            cursor: pointer; overflow: hidden;
            transition: all .3s var(--ease);
            box-shadow: 0 6px 24px color-mix(in srgb, var(--accent) 35%, transparent);
        }
        .btn-futuristic::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.15), transparent);
            opacity: 0; transition: .3s;
        }
        .btn-futuristic:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px color-mix(in srgb, var(--accent) 45%, transparent);
        }
        .btn-futuristic:hover::after { opacity: 1; }
        .btn-futuristic:active { transform: translateY(0); }

        /* ── ROLE CARDS ── */
        .role-card {
            padding: 1.1rem 1rem;
            border-radius: var(--r);
            border: 1.5px solid var(--ink10);
            background: var(--ink05);
            transition: all .25s var(--ease);
            cursor: pointer;
        }
        label:has(input[type="radio"]:checked) .role-card {
            border-color: var(--accent);
            background: color-mix(in srgb, var(--accent) 7%, var(--paper));
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 15%, transparent);
        }
        label:has(input[type="radio"]) .role-card:hover {
            border-color: color-mix(in srgb, var(--accent) 50%, transparent);
            transform: translateY(-2px);
        }
        label:has(input[type="radio"]:checked) .role-indicator {
            border-color: var(--accent);
            background: var(--accent);
        }
        label:has(input[type="radio"]:checked) .role-indicator > div {
            opacity: 1 !important;
        }

        /* ── MISC ── */
        .relative { position: relative; }
        .text-gradient {
            background: linear-gradient(135deg, var(--accent), var(--gold));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 860px) {
            .auth-card { grid-template-columns: 1fr; max-width: 480px; }
            .auth-visual { display: none; }
            .auth-form-side { padding: 2.5rem 2rem; border-radius: var(--rl); }
        }
    </style>
</head>
<body>
    <!-- Ambient orb background -->
    <div class="auth-orb"></div>

    <div class="auth-page">
        <div class="auth-card">

            <!-- ══ LEFT — Visual Panel ══ -->
            <div class="auth-visual">
                <div class="visual-inner">
                    <!-- Logo -->
                    <div class="visual-logo">
                        <div class="logo-mark">
                            <img src="{{ asset('final.png') }}" alt="Logo">
                        </div>
                        <span class="logo-name">CapAvenir</span>
                    </div>

                    <!-- Headline -->
                    <h1 class="visual-heading">
                        Trouve la voie<br>
                        qui te <em>ressemble</em><br>
                        vraiment.
                    </h1>
                    <p class="visual-sub">
                        CapAvenir analyse tes aptitudes, valeurs et ambitions grâce à l'IA pour te proposer un parcours universitaire sur mesure.
                    </p>

                    <!-- Feature pills -->
                    <div class="visual-pills">
                        <div class="vpill">
                            <span class="vpill-dot"></span>
                            Analyse de profil par IA
                        </div>
                        <div class="vpill">
                            <span class="vpill-dot d2"></span>
                            Recommandations personnalisées
                        </div>
                        <div class="vpill">
                            <span class="vpill-dot d3"></span>
                            Accompagnement conseiller
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="visual-stats">
                        <div>
                            <span class="vstat-num">15k+</span>
                            <div class="vstat-lbl">Jeunes orientés</div>
                        </div>
                        <div>
                            <span class="vstat-num">94%</span>
                            <div class="vstat-lbl">Satisfaction</div>
                        </div>
                        <div>
                            <span class="vstat-num">100%</span>
                            <div class="vstat-lbl">Gratuit</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ══ RIGHT — Form Panel ══ -->
            <div class="auth-form-side">
                <!-- Security badge -->
                <div class="sec-badge">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    Connexion sécurisée
                </div>

                {{ $slot }}
            </div>

        </div>
    </div>
</body>
</html>
