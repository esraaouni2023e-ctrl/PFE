<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page introuvable · CapAvenir</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --cream: #F5F0E8;
            --cream-mid: #EDE8DF;
            --orange: #C94B22;
            --orange-hover: #A83A16;
            --dark: #18160F;
            --muted: #7A7060;
            --border: rgba(24, 22, 15, 0.09);
        }

        html,
        body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--dark);
            overflow: hidden;
        }

        /* NAV */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2.5rem;
            height: 56px;
            background: var(--cream);
            border-bottom: 0.5px solid var(--border);
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 9px;
            text-decoration: none;
        }

        .logo-mark {
            width: 32px;
            height: 32px;
            background: var(--orange);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 13px;
            font-weight: 500;
        }

        .logo-text {
            font-size: 15px;
            font-weight: 500;
            color: var(--dark);
        }

        .logo-text span {
            color: var(--orange);
        }

        .nav-actions {
            display: flex;
            gap: 10px;
        }

        .btn-ghost {
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--dark);
            background: transparent;
            border: 0.5px solid var(--border);
            border-radius: 8px;
            padding: 7px 16px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-ghost:hover {
            background: var(--cream-mid);
        }

        .btn-orange {
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: white;
            background: var(--orange);
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-orange:hover {
            background: var(--orange-hover);
        }

        /* PAGE CENTER */
        main {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 56px;
        }

        /* CARD */
        .card {
            background: white;
            border-radius: 20px;
            border: 0.5px solid var(--border);
            padding: 52px 56px 48px;
            max-width: 460px;
            width: calc(100% - 2rem);
            text-align: center;
            box-shadow: 0 2px 8px rgba(24, 22, 15, 0.05), 0 12px 40px rgba(24, 22, 15, 0.08);
            animation: up 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            opacity: 0;
        }

        @keyframes up {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* 404 */
        .num {
            font-family: 'Playfair Display', serif;
            font-size: 110px;
            font-weight: 700;
            line-height: 1;
            letter-spacing: -5px;
            color: var(--dark);
            margin-bottom: 1.8rem;
            user-select: none;
        }

        .num .o {
            font-style: italic;
            color: var(--orange);
            display: inline-block;
            transform: rotate(-4deg);
        }

        /* Illustration */
        .illo {
            width: 100px;
            margin: 0 auto 1.8rem;
            display: block;
        }

        /* Spin keyframe for compass ring */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Headline */
        .label {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        /* Sub */
        .sub {
            font-size: 14px;
            font-weight: 300;
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 2.2rem;
        }

        /* Buttons */
        .btns {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--dark);
            color: white;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
        }

        .btn-home:hover {
            background: #2e2820;
            transform: translateY(-1px);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--cream);
            color: var(--muted);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 400;
            padding: 12px 22px;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }

        .btn-back:hover {
            background: var(--cream-mid);
            color: var(--dark);
        }

        /* Sorry */
        .sorry {
            margin-top: 1.6rem;
            font-size: 12.5px;
            font-weight: 300;
            font-style: italic;
            color: #C0B8AE;
        }

        @media (max-width: 500px) {
            nav {
                padding: 0 1.25rem;
            }

            .card {
                padding: 38px 24px 34px;
            }

            .num {
                font-size: 80px;
            }

            .btns {
                flex-direction: column;
            }

            .btn-home,
            .btn-back {
                justify-content: center;
            }
        }
    </style>
</head>

<body>

    <nav>
        <a href="/" class="nav-logo">
            <div class="logo-mark">Ca</div>
            <span class="logo-text">Cap<span>Avenir</span></span>
        </a>
        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn-ghost">Se connecter</a>
            <a href="{{ route('register') }}" class="btn-orange">Commencer →</a>
        </div>
    </nav>

    <main>
        <div class="card">

            <div class="num">4<span class="o">0</span>4</div>

            <!-- Illustration: lost student -->
            <svg class="illo" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- bg circle -->
                <circle cx="50" cy="50" r="46" fill="#F5F0E8" />
                <!-- spinning dashed ring -->
                <circle cx="50" cy="50" r="46" stroke="#C94B22" stroke-width="0.9" stroke-dasharray="4 8" opacity="0.3"
                    style="transform-origin:50px 50px; animation:spin 14s linear infinite;" />

                <!-- Body -->
                <rect x="36" y="56" width="28" height="30" rx="8" fill="#18160F" opacity="0.88" />
                <!-- Backpack -->
                <rect x="57" y="61" width="12" height="20" rx="5" fill="#C94B22" />
                <rect x="60" y="58" width="6" height="5" rx="2" fill="#A83A16" />
                <!-- Legs -->
                <rect x="37" y="82" width="10" height="13" rx="5" fill="#18160F" opacity="0.8" />
                <rect x="53" y="82" width="10" height="13" rx="5" fill="#18160F" opacity="0.8" />
                <!-- Shoes -->
                <rect x="34" y="91" width="15" height="6" rx="3" fill="#C94B22" />
                <rect x="51" y="91" width="15" height="6" rx="3" fill="#C94B22" />
                <!-- Neck -->
                <rect x="44" y="42" width="12" height="14" rx="4" fill="#F0C9A8" />
                <!-- Head -->
                <circle cx="50" cy="34" r="16" fill="#F0C9A8" />
                <!-- Hair -->
                <path d="M35 30 Q36 18 50 17 Q64 18 65 30 Q60 22 50 22 Q40 22 35 30Z" fill="#18160F" />
                <!-- Eyes -->
                <circle cx="45" cy="33" r="2.5" fill="#18160F" />
                <circle cx="55" cy="33" r="2.5" fill="#18160F" />
                <circle cx="45.8" cy="32.2" r="1" fill="white" />
                <circle cx="55.8" cy="32.2" r="1" fill="white" />
                <!-- Brows confused -->
                <path d="M42 27 Q45 25 48 27" stroke="#18160F" stroke-width="1.5" stroke-linecap="round" fill="none" />
                <path d="M53 26 Q56 28 59 26" stroke="#18160F" stroke-width="1.5" stroke-linecap="round" fill="none" />
                <!-- Mouth -->
                <path d="M45 41 Q50 37.5 55 41" stroke="#18160F" stroke-width="1.4" stroke-linecap="round"
                    fill="none" />
                <!-- Thought bubble -->
                <circle cx="74" cy="20" r="10" fill="white" opacity="0.92" />
                <circle cx="68" cy="29" r="3.2" fill="white" opacity="0.78" />
                <circle cx="63" cy="35" r="1.8" fill="white" opacity="0.58" />
                <text x="74" y="25" font-family="Playfair Display,serif" font-style="italic" font-size="13"
                    fill="#C94B22" text-anchor="middle">?</text>
            </svg>

            <p class="label">Page introuvable</p>
            <p class="sub">La page que tu cherches n'existe plus<br>ou a été déplacée. Mais ton avenir,<br>lui, est bien
                au bon endroit.</p>

            <div class="btns">
                <a href="/" class="btn-home">🏠 Retour à l'accueil</a>
                <a href="javascript:history.back()" class="btn-back">← Retour</a>
            </div>

            <p class="sorry">😔 Désolé pour la gêne occasionnée.</p>
        </div>
    </main>

</body>

</html>