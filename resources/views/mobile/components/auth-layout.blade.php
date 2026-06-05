<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>@yield('page-title', 'Authentification') — CapAvenir</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;1,9..144,300;1,9..144,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
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
            --r:  8px;
            --rl: 16px;
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

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--paper);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 1.5rem 1rem;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient background glow */
        .auth-orb-mob {
            position: fixed;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: radial-gradient(circle, color-mix(in srgb, var(--accent) 15%, transparent), transparent 70%);
            top: -50px; right: -50px;
            z-index: 0; pointer-events: none;
        }
        .auth-orb-mob-2 {
            position: fixed;
            width: 250px; height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle, color-mix(in srgb, var(--accent2) 12%, transparent), transparent 70%);
            bottom: -50px; left: -50px;
            z-index: 0; pointer-events: none;
        }

        .auth-container-mob {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
            background: rgba(247, 245, 240, 0.85);
            backdrop-filter: blur(8px);
            border: 1px solid var(--ink10);
            border-radius: var(--rl);
            padding: 2.25rem 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        [data-theme="dark"] .auth-container-mob {
            background: rgba(16, 16, 13, 0.85);
        }

        /* Branding */
        .brand-mob {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }
        .brand-logo { width: 44px; height: 44px; }
        .brand-name {
            font-family: 'Fraunces', serif;
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.03em;
        }

        /* Inputs */
        .input-2026 {
            width: 100%;
            padding: 0.85rem 1rem;
            font-family: inherit;
            font-size: 16px !important; /* Forces iOS not to zoom */
            color: var(--ink);
            background: var(--ink05);
            border: 1.5px solid var(--ink10);
            border-radius: var(--r);
            outline: none;
            transition: all 0.2s;
        }
        .input-2026:focus {
            border-color: var(--accent);
            background: var(--paper);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 15%, transparent);
        }

        /* Buttons */
        .btn-futuristic {
            width: 100%;
            min-height: 48px;
            padding: 0.75rem 1.5rem;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: var(--r);
            font-size: 0.95rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            box-shadow: 0 4px 15px color-mix(in srgb, var(--accent) 30%, transparent);
            transition: all 0.2s;
        }
        .btn-futuristic:active {
            transform: translateY(1px);
            opacity: 0.95;
        }

        .heading-3 {
            font-family: 'Fraunces', serif;
            font-size: 1.45rem;
            font-weight: 600;
            line-height: 1.25;
            color: var(--ink);
        }
        .text-gradient {
            background: linear-gradient(135deg, var(--accent), var(--gold));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .body-small { font-size: 0.85rem; color: var(--ink60); }
    </style>
</head>
<body>
    <div class="auth-orb-mob"></div>
    <div class="auth-orb-mob-2"></div>

    <div class="auth-container-mob">
        <div class="brand-mob">
            <img src="{{ asset('final.png') }}" class="brand-logo" alt="Logo">
        </div>

        {{ $slot }}
    </div>
</body>
</html>
