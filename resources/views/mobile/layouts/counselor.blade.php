<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Conseiller') — CapAvenir</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;1,9..144,300;1,9..144,400&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink:     #1E293B;
            --paper:   #FFFFFF;
            --cream:   #F8FAFC;
            --warm:    #E2E8F0;
            --accent:  #EA580C;
            --accent2: #0A2540;
            --accent3: #10B981;
            --gold:    #F97316;
            --red:     #EF4444;
            --ink60:   rgba(30, 41, 59, 0.6);
            --ink30:   rgba(30, 41, 59, 0.3);
            --ink15:   rgba(30, 41, 59, 0.15);
            --ink10:   rgba(30, 41, 59, 0.1);
            --ink06:   rgba(30, 41, 59, 0.06);

            --r:   8px;
            --rl:  16px;
            --rx:  999px;
            --ease: cubic-bezier(.16,1,.3,1);

            --font-main:     'DM Sans', sans-serif;
            --font-serif:    'Fraunces', serif;
            --navbar-bg:     rgba(248, 250, 252, 0.92);
            --glass-border:  rgba(30, 41, 59, 0.08);
            --shadow-card:   0 4px 20px rgba(10, 37, 64, 0.06);
            --transition:    0.3s cubic-bezier(.4,0,.2,1);

            --success:       #10B981;
            --warning:       #F59E0B;
        }

        [data-theme="dark"] {
            --ink:   #F1F5F9;
            --paper: #0E1324;
            --cream: #070A10;
            --warm:  #1D2433;
            --accent: #F97316;
            --accent2: #38BDF8;
            --accent3: #34D399;
            --gold:    #FB923C;
            --red:     #F87171;
            --ink60: rgba(241, 245, 249, 0.6);
            --ink30: rgba(241, 245, 249, 0.3);
            --ink15: rgba(241, 245, 249, 0.15);
            --ink10: rgba(241, 245, 249, 0.08);
            --ink06: rgba(241, 245, 249, 0.04);
            --navbar-bg:     rgba(7, 10, 16, 0.92);
            --glass-border:  rgba(241, 245, 249, 0.08);
            --shadow-card:   0 4px 20px rgba(0, 0, 0, 0.3);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: var(--font-main);
            background: var(--cream);
            color: var(--ink);
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 60px;
            padding-bottom: 70px;
            -webkit-font-smoothing: antialiased;
            transition: background .3s, color .3s;
        }

        /* Ambient Orbs */
        .bg-orb {
            position: fixed; border-radius: 50%;
            filter: blur(80px); pointer-events: none; z-index: 0; opacity: 0.5;
        }
        .bg-orb-1 { width: 300px; height: 300px; top: -100px; right: -50px; background: radial-gradient(circle, color-mix(in srgb,var(--accent2) 12%,transparent) 0%, transparent 70%); }
        .bg-orb-2 { width: 250px; height: 250px; bottom: 100px; left: -100px; background: radial-gradient(circle, color-mix(in srgb,var(--accent) 10%,transparent) 0%, transparent 70%); }

        /* Mobile Header */
        header.mob-header {
            position: fixed; top: 0; left: 0; right: 0; height: 60px;
            background: var(--navbar-bg); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border); z-index: 900;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1rem;
        }
        .logo-mob {
            display: flex; align-items: center; gap: 0.4rem; text-decoration: none;
            color: var(--ink); font-family: var(--font-serif); font-weight: 700; font-size: 1.05rem;
        }
        .logo-mob img { height: 32px; width: 32px; }
        
        .header-actions { display: flex; align-items: center; gap: 0.6rem; }
        .header-btn {
            width: 38px; height: 38px; border-radius: var(--r);
            border: 1px solid var(--glass-border); background: var(--paper);
            color: var(--ink); display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; cursor: pointer; position: relative;
        }

        /* Bottom Tab Bar */
        .bottom-nav {
            position: fixed; bottom: 0; left: 0; right: 0; height: 64px;
            background: var(--navbar-bg); backdrop-filter: blur(16px);
            border-top: 1px solid var(--glass-border); z-index: 900;
            display: flex; align-items: center; justify-content: space-around;
            padding-bottom: env(safe-area-inset-bottom);
        }
        .tab-item {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            flex: 1; height: 100%; text-decoration: none; color: var(--ink60);
            font-size: 0.72rem; font-weight: 500; gap: 2px;
            transition: var(--transition);
        }
        .tab-item i { font-size: 1.25rem; }
        .tab-item.active {
            color: var(--accent);
            font-weight: 700;
        }

        /* Mobile Drawer Menu */
        .drawer {
            position: fixed; top: 0; left: 0; bottom: 0; right: 0; z-index: 1001;
            opacity: 0; pointer-events: none; transition: var(--transition);
        }
        .drawer.open { opacity: 1; pointer-events: all; }
        .drawer-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); }
        .drawer-content {
            position: absolute; top: 0; right: 0; bottom: 0; width: 80%; max-width: 300px;
            background: var(--paper); padding: 4.5rem 1.5rem 2rem; display: flex; flex-direction: column;
            gap: 0.8rem; border-left: 1px solid var(--glass-border);
            overflow-y: auto;
        }
        .drawer-close { position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; border-radius: var(--r); border: 1px solid var(--glass-border); background: var(--cream); color: var(--ink); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; cursor: pointer; }
        .drawer-section { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--ink30); margin-top: 1rem; margin-bottom: 0.2rem; }
        .drawer-link {
            display: flex; align-items: center; gap: 0.75rem; text-decoration: none;
            color: var(--ink); font-size: 0.95rem; font-weight: 600; padding: 0.65rem 0.8rem;
            border-radius: var(--r); transition: var(--transition);
        }
        .drawer-link:hover, .drawer-link.active {
            background: var(--ink06);
            color: var(--accent);
        }
        .drawer-logout {
            margin-top: auto; border-top: 1px solid var(--warm); padding-top: 1rem;
        }

        /* Page Content Area */
        .page-content-mob {
            position: relative; z-index: 1;
            padding: 1.25rem 1rem;
            width: 100%;
        }

        /* Tactile and Form elements optimization */
        button, .btn { min-height: 44px; display: inline-flex; align-items: center; justify-content: center; }
        input, select, textarea { font-size: 16px !important; } /* Prevents iOS auto-zoom */
    </style>
</head>
<body>
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>

    <!-- Mobile Header -->
    <header class="mob-header">
        <a href="{{ route('counselor.dashboard') }}" class="logo-mob">
            <img src="{{ asset('final.png') }}" alt="Logo">
            <span>CapAvenir</span>
        </a>
        <div class="header-actions">
            <button class="header-btn" id="drawerBtn" title="Menu"><i class="bi bi-list"></i></button>
        </div>
    </header>

    <!-- Drawer Navigation (Secondary Menu) -->
    <div class="drawer" id="sideDrawer">
        <div class="drawer-overlay" id="drawerOverlay"></div>
        <div class="drawer-content">
            <button class="drawer-close" id="drawerClose"><i class="bi bi-x-lg"></i></button>
            
            <div class="drawer-section">Outils & IA</div>
            <a href="{{ route('counselor.resources') }}" class="drawer-link {{ request()->routeIs('counselor.resources') ? 'active' : '' }}">
                <i class="bi bi-cpu-fill"></i> Ressources IA
            </a>
            <a href="{{ route('testimonial.edit') }}" class="drawer-link {{ request()->routeIs('testimonial.edit') ? 'active' : '' }}">
                <i class="bi bi-star"></i> Mon Témoignage
            </a>

            <div class="drawer-section">Compte</div>
            <a href="{{ route('profile.edit') }}" class="drawer-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Paramètres
            </a>
            <button class="drawer-link" id="themeToggle" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                <i class="bi bi-sun" id="themeIcon"></i> <span id="themeLabel">Mode Sombre</span>
            </button>

            <div class="drawer-logout">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="drawer-link" style="width: 100%; border: none; background: none; color: var(--red); text-align: left; font-weight: 700;">
                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bottom Tab Bar (Primary Navigation) -->
    <nav class="bottom-nav">
        <a href="{{ route('counselor.dashboard') }}" class="tab-item {{ request()->routeIs('counselor.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('counselor.students') }}" class="tab-item {{ request()->routeIs('counselor.students') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>Mes Étudiants</span>
        </a>
        <a href="{{ route('counselor.agenda') }}" class="tab-item {{ request()->routeIs('counselor.agenda') ? 'active' : '' }}">
            <i class="bi bi-calendar-event"></i>
            <span>Agenda</span>
        </a>
        <a href="{{ route('messages.index') }}" class="tab-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
            <i class="bi bi-chat-text"></i>
            <span>Messages</span>
        </a>
    </nav>

    <!-- Page Content Area -->
    <main class="page-content-mob">
        @yield('content')
    </main>

    <script>
        // Drawer Menu Control
        const drawerBtn = document.getElementById('drawerBtn');
        const sideDrawer = document.getElementById('sideDrawer');
        const drawerClose = document.getElementById('drawerClose');
        const drawerOverlay = document.getElementById('drawerOverlay');

        drawerBtn?.addEventListener('click', () => sideDrawer.classList.add('open'));
        drawerClose?.addEventListener('click', () => sideDrawer.classList.remove('open'));
        drawerOverlay?.addEventListener('click', () => sideDrawer.classList.remove('open'));

        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const themeLabel = document.getElementById('themeLabel');
        const html = document.documentElement;

        const updateThemeUI = (theme) => {
            if (theme === 'dark') {
                themeIcon.className = 'bi bi-moon';
                themeLabel.textContent = 'Mode Clair';
            } else {
                themeIcon.className = 'bi bi-sun';
                themeLabel.textContent = 'Mode Sombre';
            }
        };

        const savedTheme = localStorage.getItem('cap-theme') || 'light';
        html.setAttribute('data-theme', savedTheme);
        updateThemeUI(savedTheme);

        themeToggle?.addEventListener('click', () => {
            const isDark = html.getAttribute('data-theme') === 'dark';
            const nextTheme = isDark ? 'light' : 'dark';
            html.setAttribute('data-theme', nextTheme);
            localStorage.setItem('cap-theme', nextTheme);
            updateThemeUI(nextTheme);
        });
    </script>
</body>
</html>
