<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demande de Validation — CapAvenir</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;1,9..144,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink: #0A2540;
            --paper: #FFFFFF;
            --cream: #F8FAFC;
            --accent: #EA580C;
            --accent2: #0A2540;
            --accent3: #10b981;
            --danger: #EF4444;
            --gold: #FBBF24;
            --ink06: rgba(10, 37, 64, 0.06);
            --ink10: rgba(10, 37, 64, 0.1);
            --ink30: rgba(10, 37, 64, 0.3);
            --ink60: rgba(10, 37, 64, 0.6);
            --font-main: 'DM Sans', sans-serif;
            --font-serif: 'Fraunces', serif;
            --r: 12px;
            --rl: 24px;
            --rx: 40px;
        }

        [data-theme="dark"] {
            --paper: #0E1324;
            --cream: #070A10;
            --ink: #F1F5F9;
            --ink06: rgba(241, 245, 249, 0.06);
            --ink10: rgba(241, 245, 249, 0.1);
            --ink30: rgba(241, 245, 249, 0.3);
            --ink60: rgba(241, 245, 249, 0.6);
            --accent2: #38BDF8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--paper);
            color: var(--ink);
            font-family: var(--font-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Noise texture */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            opacity: 0.04;
            pointer-events: none;
            z-index: 10;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3e%3cfilter id='noiseFilter'%3e%3cfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3e%3c/filter%3e%3crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3e%3c/svg%3e");
        }

        .ambient-glow {
            position: fixed;
            width: 50vw;
            height: 50vw;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.08;
            z-index: -1;
            pointer-events: none;
        }
        .glow-1 { top: -10%; right: -5%; background: var(--accent); }
        .glow-2 { bottom: -10%; left: -5%; background: var(--accent2); }
        .glow-3 { top: 40%; left: 30%; width: 30vw; height: 30vw; background: var(--accent3); opacity: 0.04; }

        /* ── Animations ── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulseGlow {
            0%   { box-shadow: 0 0 0 0 rgba(234, 88, 12, 0.45); }
            70%  { box-shadow: 0 0 0 10px rgba(234, 88, 12, 0); }
            100% { box-shadow: 0 0 0 0 rgba(234, 88, 12, 0); }
        }
        @keyframes pulseBadge {
            0%, 100% { opacity: 1; }
            50%      { opacity: 0.7; }
        }
        @keyframes dangerPulse {
            0%   { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70%  { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        @keyframes shimmer {
            0%   { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        /* ── Main card ── */
        .pending-card {
            width: 100%;
            max-width: 760px;
            margin: 2rem;
            background: var(--cream);
            border: 1px solid var(--ink10);
            border-radius: var(--rl);
            padding: 3rem 2.5rem 2.5rem;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.18), 0 0 0 1px var(--ink06);
            position: relative;
            z-index: 2;
            backdrop-filter: blur(16px);
            animation: fadeInUp 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        /* ── Header ── */
        .header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        .header-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }
        .logo-mark {
            height: 40px;
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-text {
            font-family: var(--font-serif);
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--ink);
        }

        .submitted-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.85rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--ink60);
            background: var(--ink06);
            border: 1px solid var(--ink10);
            white-space: nowrap;
        }
        .submitted-badge svg {
            width: 0.85rem;
            height: 0.85rem;
            opacity: 0.6;
        }

        /* ── Status badges ── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.15rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 1rem;
        }
        .status-badge.pending {
            background: rgba(234, 88, 12, 0.12);
            color: var(--accent);
            border: 1px solid rgba(234, 88, 12, 0.25);
            animation: pulseBadge 2.5s ease-in-out infinite;
        }
        .status-badge.rejected {
            background: rgba(239, 68, 68, 0.12);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        h1 {
            font-family: var(--font-serif);
            font-size: 2.1rem;
            font-weight: 600;
            line-height: 1.2;
            color: var(--ink);
            margin: 0 0 0.75rem 0;
            animation: fadeInUp 0.7s 0.1s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        h1 em {
            font-style: italic;
            font-family: var(--font-serif);
            font-weight: 400;
            color: var(--accent);
        }

        .subtitle {
            font-size: 0.95rem;
            color: var(--ink60);
            line-height: 1.65;
            margin: 0 0 2rem 0;
            animation: fadeInUp 0.7s 0.15s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        /* ══════════════════════════════════════
           HORIZONTAL STEPPER
        ══════════════════════════════════════ */
        .stepper {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            position: relative;
            margin: 2.5rem 0;
            padding: 0 0.5rem;
            animation: fadeInUp 0.7s 0.2s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        /* Progress line behind markers */
        .stepper-line {
            position: absolute;
            top: 20px;
            left: calc(16.66%);
            right: calc(16.66%);
            height: 3px;
            background: var(--ink10);
            border-radius: 2px;
            z-index: 0;
        }
        .stepper-line-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            border-radius: 2px;
            background: linear-gradient(90deg, var(--accent3), var(--accent3));
            transition: width 0.6s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .stepper-line-fill.fill-66 { width: 50%; }
        .stepper-line-fill.fill-100-danger {
            width: 100%;
            background: linear-gradient(90deg, var(--accent3), var(--accent3) 50%, var(--danger) 100%);
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
            position: relative;
            z-index: 1;
        }

        .step-marker {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            border: 3px solid transparent;
            transition: all 0.3s ease;
            margin-bottom: 0.75rem;
        }
        .step-marker svg {
            width: 1.1rem;
            height: 1.1rem;
        }

        .step-marker.done {
            background: var(--accent3);
            color: white;
            border-color: var(--accent3);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }
        .step-marker.active {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
            animation: pulseGlow 2s infinite;
        }
        .step-marker.danger {
            background: var(--danger);
            color: white;
            border-color: var(--danger);
            animation: dangerPulse 2s infinite;
        }
        .step-marker.waiting {
            background: var(--ink06);
            color: var(--ink30);
            border-color: var(--ink10);
        }

        .step-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 0.2rem;
        }
        .step-desc {
            font-size: 0.7rem;
            color: var(--ink60);
            max-width: 130px;
        }

        /* ══════════════════════════════════════
           REJECTION BOX
        ══════════════════════════════════════ */
        .rejection-box {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.08), rgba(239, 68, 68, 0.03));
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: var(--r);
            padding: 1.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.7s 0.25s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .rejection-box::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--danger);
            border-radius: 4px 0 0 4px;
        }
        .rejection-box-header {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 0.75rem;
        }
        .rejection-box-header .icon-wrap {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .rejection-box-header .icon-wrap svg {
            width: 1rem;
            height: 1rem;
            color: var(--danger);
        }
        .rejection-box-header h4 {
            font-size: 0.95rem;
            color: var(--danger);
            font-weight: 700;
        }
        .rejection-box p {
            font-size: 0.88rem;
            line-height: 1.6;
            color: var(--ink);
            padding-left: 2.6rem;
            margin-bottom: 1rem;
        }
        .rejection-box .support-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            border-radius: var(--r);
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            margin-left: 2.6rem;
            transition: all 0.2s ease;
            border: 1px solid rgba(239, 68, 68, 0.15);
        }
        .rejection-box .support-link:hover {
            background: rgba(239, 68, 68, 0.18);
            transform: translateY(-1px);
        }
        .rejection-box .support-link svg {
            width: 0.9rem;
            height: 0.9rem;
        }

        /* ══════════════════════════════════════
           PROFILE INFO CARDS
        ══════════════════════════════════════ */
        .profile-section {
            animation: fadeInUp 0.7s 0.3s cubic-bezier(0.22, 1, 0.36, 1) both;
            margin-bottom: 2rem;
        }
        .profile-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--ink60);
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .profile-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--ink10);
        }

        .info-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
        }

        .info-card {
            background: var(--paper);
            border: 1px solid var(--ink10);
            border-radius: var(--r);
            padding: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            transition: all 0.25s cubic-bezier(0.22, 1, 0.36, 1);
            cursor: default;
        }
        .info-card:hover {
            border-color: var(--ink30);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }
        [data-theme="dark"] .info-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        }

        .info-card .card-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .info-card .card-icon svg {
            width: 1.1rem;
            height: 1.1rem;
        }
        .info-card .card-icon.blue   { background: rgba(10, 37, 64, 0.1); color: var(--accent2); }
        .info-card .card-icon.orange { background: rgba(234, 88, 12, 0.1); color: var(--accent); }
        .info-card .card-icon.green  { background: rgba(16, 185, 129, 0.1); color: var(--accent3); }
        [data-theme="dark"] .info-card .card-icon.blue { background: rgba(56, 189, 248, 0.1); }

        .info-card .card-content {
            flex: 1;
            min-width: 0;
        }
        .info-card .card-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--ink60);
            margin-bottom: 0.2rem;
            font-weight: 500;
        }
        .info-card .card-value {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--ink);
            word-break: break-word;
        }
        .info-card .card-value a {
            color: var(--accent);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-weight: 700;
            transition: opacity 0.2s;
        }
        .info-card .card-value a:hover {
            opacity: 0.75;
        }

        /* ── Actions ── */
        .actions-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: fadeInUp 0.7s 0.35s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: transparent;
            color: var(--ink);
            border: 1px solid var(--ink30);
            padding: 0.85rem 1.75rem;
            border-radius: var(--r);
            font-family: var(--font-main);
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.22, 1, 0.36, 1);
            width: 100%;
        }
        .btn-logout:hover {
            background: var(--ink10);
            border-color: var(--ink60);
            transform: translateY(-2px);
        }
        .btn-logout svg {
            width: 1.1rem;
            height: 1.1rem;
        }

        /* ── Footer ── */
        .page-footer {
            text-align: center;
            padding: 1.5rem 2rem;
            font-size: 0.8rem;
            color: var(--ink30);
            z-index: 2;
            animation: fadeInUp 0.7s 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .page-footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }
        .page-footer a:hover {
            text-decoration: underline;
        }

        /* ══════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════ */
        @media (max-width: 700px) {
            .pending-card {
                margin: 1rem;
                padding: 2rem 1.5rem 1.5rem;
            }
            .info-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            h1 { font-size: 1.7rem; }
            .header-row { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
            .step-label { font-size: 0.72rem; }
            .step-desc { font-size: 0.65rem; max-width: 100px; }
            .step-marker { width: 34px; height: 34px; }
            .step-marker svg { width: 0.95rem; height: 0.95rem; }
        }
        @media (max-width: 480px) {
            .info-cards {
                grid-template-columns: 1fr;
            }
            .pending-card {
                margin: 0.5rem;
                padding: 1.5rem 1.15rem 1.25rem;
                border-radius: 16px;
            }
            h1 { font-size: 1.4rem; }
            .stepper { margin: 1.5rem 0; padding: 0; }
            .step-marker { width: 30px; height: 30px; border-width: 2px; }
            .step-marker svg { width: 0.85rem; height: 0.85rem; }
            .step-desc { display: none; }
            .stepper-line { top: 15px; }
        }
    </style>
</head>
<body>

    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>
    <div class="ambient-glow glow-3"></div>

    <div class="pending-card">
        {{-- ── Header ── --}}
        <div class="header-row">
            <a href="/" class="header-logo">
                <div class="logo-mark">
                    <img src="{{ asset('final.png') }}" alt="Logo" style="height: 100%; width: 100%; object-fit: contain;">
                </div>
                <span class="logo-text">CapAvenir</span>
            </a>

            <div class="submitted-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Soumis {{ $user->created_at->diffForHumans() }}
            </div>
        </div>

        @if($user->status === \App\Models\User::STATUS_REJECTED)
            {{-- ═══ REJECTED STATE ═══ --}}
            <div class="status-badge rejected">
                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Candidature refusée</span>
            </div>

            <h1>Demande <em>non validée</em></h1>
            <p class="subtitle">Après examen de vos pièces justificatives, l'équipe d'administration CapAvenir n'a pas pu valider votre demande.</p>

            {{-- Horizontal Stepper --}}
            <div class="stepper">
                <div class="stepper-line">
                    <div class="stepper-line-fill fill-100-danger"></div>
                </div>

                <div class="step">
                    <div class="step-marker done">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="step-label">Création du compte</div>
                    <div class="step-desc">Espace conseiller initialisé</div>
                </div>

                <div class="step">
                    <div class="step-marker done">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="step-label">Dossier soumis</div>
                    <div class="step-desc">Documents transmis avec succès</div>
                </div>

                <div class="step">
                    <div class="step-marker danger">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <div class="step-label">Validation admin</div>
                    <div class="step-desc">Demande refusée</div>
                </div>
            </div>

            {{-- Rejection Details --}}
            <div class="rejection-box">
                <div class="rejection-box-header">
                    <div class="icon-wrap">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h4>Motif du refus</h4>
                </div>
                <p>« {{ $profile->verification_notes ?? 'Vos diplômes ou informations d\'expérience n\'ont pas pu être authentifiés.' }} »</p>
                <a href="mailto:support@capavenir.com" class="support-link">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Contacter le support
                </a>
            </div>

        @else
            {{-- ═══ PENDING STATE ═══ --}}
            <div class="status-badge pending">
                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Validation en cours</span>
            </div>

            <h1>Demande de <em>validation</em></h1>
            <p class="subtitle">Votre profil conseiller est actuellement en cours de vérification par l'équipe d'administration CapAvenir. Cette étape prend généralement entre 24 et 48 heures.</p>

            {{-- Horizontal Stepper --}}
            <div class="stepper">
                <div class="stepper-line">
                    <div class="stepper-line-fill fill-66"></div>
                </div>

                <div class="step">
                    <div class="step-marker done">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="step-label">Création du compte</div>
                    <div class="step-desc">Effectuée avec succès</div>
                </div>

                <div class="step">
                    <div class="step-marker done">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="step-label">Dossier soumis</div>
                    <div class="step-desc">Documents bien reçus</div>
                </div>

                <div class="step">
                    <div class="step-marker active">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="step-label">Validation admin</div>
                    <div class="step-desc">En attente d'évaluation</div>
                </div>
            </div>
        @endif

        {{-- ═══ PROFILE INFO CARDS ═══ --}}
        <div class="profile-section">
            <div class="profile-section-title">Votre dossier transmis</div>
            <div class="info-cards">
                {{-- Nom complet --}}
                <div class="info-card">
                    <div class="card-icon blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="card-content">
                        <div class="card-label">Nom complet</div>
                        <div class="card-value">{{ $user->name }}</div>
                    </div>
                </div>

                {{-- Email --}}
                <div class="info-card">
                    <div class="card-icon orange">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="card-content">
                        <div class="card-label">Adresse email</div>
                        <div class="card-value">{{ $user->email }}</div>
                    </div>
                </div>

                {{-- Téléphone --}}
                <div class="info-card">
                    <div class="card-icon green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div class="card-content">
                        <div class="card-label">Téléphone</div>
                        <div class="card-value">{{ $profile->phone ?? 'Non spécifié' }}</div>
                    </div>
                </div>

                {{-- Spécialité --}}
                <div class="info-card">
                    <div class="card-icon orange">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div class="card-content">
                        <div class="card-label">Spécialité</div>
                        <div class="card-value">{{ $profile->specialty ?? 'Non spécifiée' }}</div>
                    </div>
                </div>

                {{-- Expérience --}}
                <div class="info-card">
                    <div class="card-icon blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="card-content">
                        <div class="card-label">Expérience</div>
                        <div class="card-value">{{ $profile->experience_years ?? 0 }} an(s)</div>
                    </div>
                </div>

                {{-- CV --}}
                <div class="info-card">
                    <div class="card-icon green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="card-content">
                        <div class="card-label">CV transmis</div>
                        <div class="card-value">
                            @if($profile && $profile->cv_path)
                                <a href="{{ asset('storage/' . $profile->cv_path) }}" target="_blank">
                                    <svg style="width: 0.85rem; height: 0.85rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Voir le PDF
                                </a>
                            @else
                                Aucun document
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Actions ── --}}
        <div class="actions-row">
            <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>

    {{-- ── Footer ── --}}
    <div class="page-footer">
        Pour toute question, contactez-nous à <a href="mailto:support@capavenir.com">support@capavenir.com</a>
    </div>

</body>
</html>
