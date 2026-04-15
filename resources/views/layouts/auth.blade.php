<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page-title', 'Authentification') — CapAvenir</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: var(--bg-main);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }

        /* ═══ 2026 AUTH BACKGROUND ═══ */
        .auth-bg {
            position: fixed; inset: 0; z-index: -1;
            background: var(--bg-dark-gradient);
        }

        .auth-glow {
            position: fixed; width: 40vw; height: 40vw;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            z-index: -1;
            animation: pulse 10s infinite alternate;
        }
        .glow-1 { top: -10%; right: -5%; background: #00D4FF; }
        .glow-2 { bottom: -10%; left: -5%; background: #4C1D95; }

        @keyframes pulse {
            from { transform: scale(1); opacity: 0.1; }
            to { transform: scale(1.2); opacity: 0.2; }
        }

        /* ═══ AUTH PANEL ═══ */
        .auth-container {
            width: 100%;
            max-width: 1000px;
            padding: 1.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            glass-morphism;
            overflow: hidden;
            animation: authEnter 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes authEnter {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-visual {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(76, 29, 149, 0.1));
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-right: 1px solid var(--glass-border);
            position: relative;
        }

        .auth-form-side {
            padding: 4rem 3.5rem;
            background: rgba(10, 20, 40, 0.4);
        }

        .input-2026 {
            @apply w-full bg-white/5 border border-white/10 rounded-[12px] px-4 py-3 text-sm transition-all focus:border-neon-cyan focus:ring-1 focus:ring-neon-cyan outline-none;
        }

        @media (max-width: 768px) {
            .auth-container { grid-template-columns: 1fr; }
            .auth-visual { display: none; }
            .auth-form-side { padding: 3rem 2rem; }
        }
    </style>
</head>
<body>
    <div class="auth-bg"></div>
    <div class="auth-glow glow-1"></div>
    <div class="auth-glow glow-2"></div>

    <div class="auth-container glass-morphism">
        <!-- Visual Panel -->
        <div class="auth-visual">
            <div class="mb-10 w-16 h-16 bg-white p-2 rounded-[16px] shadow-2xl shadow-cyan-500/20">
                <img src="{{ asset('im1.jpg') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            
            <h1 class="text-4xl font-extrabold tracking-tight mb-6">
                Connectez-vous à votre <span class="text-neon-cyan">avenir intelligent.</span>
            </h1>
            <p class="text-text-secondary line-height-1.6 max-w-sm mb-8">
                Rejoignez 12 000+ étudiants qui ont déjà trouvé leur voie grâce à CapAvenir.
            </p>

            <div class="space-y-4">
                <div class="flex items-center gap-3 text-sm font-medium">
                    <span class="w-6 h-6 rounded-full bg-neon-cyan/20 border border-neon-cyan/30 flex items-center justify-center text-neon-cyan text-[10px]">✔</span>
                    Analyse de profil IA
                </div>
                <div class="flex items-center gap-3 text-sm font-medium">
                    <span class="w-6 h-6 rounded-full bg-neon-cyan/20 border border-neon-cyan/30 flex items-center justify-center text-neon-cyan text-[10px]">✔</span>
                    Accompagnement personnalisé
                </div>
            </div>
        </div>

        <!-- Form Side -->
        <div class="auth-form-side">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
