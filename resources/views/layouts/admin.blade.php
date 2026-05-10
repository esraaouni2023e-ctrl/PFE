<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — CapAvenir</title>

    <!-- Google Fonts: DM Sans + Fraunces -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300;1,9..40,400&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;1,9..144,300;1,9..144,400;1,9..144,600&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ═══════════════════════════════════════════
           DESIGN TOKENS — CapAvenir System (admin)
           Accent : terracotta + red-alert for admin
        ═══════════════════════════════════════════ */
        :root {
            /* ── Core palette ── */
            --ink:     #0b0c10;
            --paper:   #f7f5f0;
            --cream:   #ede9e1;
            --warm:    #e8e1d4;
            --accent:  #d4622a;
            --accent2: #1a4f6e;
            --accent3: #4a7c59;
            --gold:    #c8973a;
            --red:     #c0392b;
            --ink60:   rgba(11,12,16,.6);
            --ink30:   rgba(11,12,16,.3);
            --ink15:   rgba(11,12,16,.15);
            --ink10:   rgba(11,12,16,.1);
            --ink06:   rgba(11,12,16,.06);

            /* ── Radii & easing ── */
            --r:    6px;
            --rl:   16px;
            --rx:   999px;
            --ease: cubic-bezier(.16,1,.3,1);

            /* ── Component tokens ── */
            --font-main:     'DM Sans', sans-serif;
            --font-serif:    'Fraunces', serif;
            --navbar-bg:     rgba(247,245,240,.88);
            --glass-border:  rgba(11,12,16,.10);
            --shadow-card:   0 8px 40px rgba(0,0,0,.08);
            --transition:    0.3s cubic-bezier(.4,0,.2,1);
        }

        /* ── Dark mode overrides ── */
        [data-theme="dark"] {
            --ink:   #f0ede6;
            --paper: #10100d;
            --cream: #18170f;
            --warm:  #1f1e14;
            --ink60: rgba(240,237,230,.6);
            --ink30: rgba(240,237,230,.3);
            --ink15: rgba(240,237,230,.15);
            --ink10: rgba(240,237,230,.08);
            --ink06: rgba(240,237,230,.04);
            --navbar-bg:     rgba(16,16,13,.88);
            --glass-border:  rgba(240,237,230,.08);
            --shadow-card:   0 8px 40px rgba(0,0,0,.35);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-main);
            background: var(--paper);
            color: var(--ink);
            overflow-x: hidden;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            transition: background .4s ease, color .4s ease;
        }

        /* ─── Noise overlay ─── */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
            opacity: .02;
        }

        /* ─── Background orbs ─── */
        .bg-orb {
            position: fixed; border-radius: 50%;
            filter: blur(120px); pointer-events: none; z-index: 0;
        }
        .bg-orb-1 {
            width: 700px; height: 700px; top: -200px; right: -150px;
            background: radial-gradient(circle, color-mix(in srgb,var(--accent) 10%,transparent) 0%, transparent 70%);
        }
        .bg-orb-2 {
            width: 600px; height: 600px; bottom: -100px; left: -100px;
            background: radial-gradient(circle, color-mix(in srgb,var(--red) 7%,transparent) 0%, transparent 70%);
        }
        .bg-orb-3 {
            width: 400px; height: 400px; top: 40%; left: 35%;
            background: radial-gradient(circle, color-mix(in srgb,var(--accent2) 6%,transparent) 0%, transparent 70%);
        }

        /* ═══ NAVBAR ═══ */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem; height: 64px;
            background: var(--navbar-bg);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid var(--glass-border);
            transition: background .4s ease, border-color .4s ease;
        }

        /* Logo */
        .navbar-logo {
            display: flex; align-items: center; gap: .55rem; text-decoration: none;
        }
        .navbar-logo-icon {
            width: 34px; height: 34px; border-radius: var(--r);
            background: white; overflow: hidden;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid var(--ink10);
        }
        .navbar-logo-icon img { width: 100%; height: 100%; object-fit: cover; }
        .navbar-logo-text {
            font-family: var(--font-serif);
            font-size: 1.05rem; font-weight: 600;
            letter-spacing: -.04em; color: var(--ink);
        }
        .navbar-logo-sub {
            font-size: .6rem; color: var(--ink30); font-weight: 600;
            letter-spacing: .07em; text-transform: uppercase;
        }

        /* Nav links */
        .navbar-nav {
            display: flex; align-items: center; gap: .2rem; list-style: none;
        }
        .navbar-nav a {
            display: block; padding: .38rem .8rem;
            font-size: .8rem; font-weight: 600;
            color: var(--ink60); text-decoration: none;
            border-radius: var(--r); transition: var(--transition);
        }
        .navbar-nav a:hover { color: var(--ink); background: var(--ink06); }
        .navbar-nav a.active {
            color: var(--accent);
            background: color-mix(in srgb, var(--accent) 8%, transparent);
        }

        /* Right area */
        .navbar-right { display: flex; align-items: center; gap: .6rem; }

        /* Theme toggle */
        .theme-toggle {
            width: 34px; height: 34px; border-radius: var(--r);
            background: var(--ink06); border: 1px solid var(--glass-border);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: .95rem;
            transition: var(--transition); color: var(--ink60);
        }
        .theme-toggle:hover { border-color: var(--ink30); color: var(--ink); }

        /* Notification */
        .notif-btn {
            width: 34px; height: 34px; border-radius: var(--r);
            background: var(--ink06); border: 1px solid var(--glass-border);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: .95rem;
            color: var(--ink60); position: relative; transition: var(--transition);
        }
        .notif-btn:hover { border-color: var(--ink30); color: var(--ink); }
        .notif-dot {
            position: absolute; top: 5px; right: 5px;
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--red); border: 2px solid var(--paper);
        }

        /* Admin role badge — red accent */
        .role-badge-admin {
            display: flex; align-items: center; gap: .4rem;
            padding: .28rem .75rem; border-radius: var(--rx);
            background: color-mix(in srgb, var(--red) 10%, transparent);
            border: 1px solid color-mix(in srgb, var(--red) 25%, transparent);
            font-size: .7rem; font-weight: 700;
            color: var(--red); letter-spacing: .05em; text-transform: uppercase;
        }
        .admin-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--red); animation: adminPulse 2s ease infinite;
        }
        @keyframes adminPulse { 0%,100%{opacity:1;} 50%{opacity:.35;} }

        /* Avatar */
        .avatar-nav {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: .82rem; font-weight: 700; color: #fff;
            cursor: pointer; transition: var(--transition);
        }
        .avatar-nav:hover { opacity: .85; }

        /* Logout */
        .btn-logout {
            display: flex; align-items: center; gap: .4rem;
            padding: .38rem .8rem; border-radius: var(--r);
            background: color-mix(in srgb, #ef4444 8%, transparent);
            border: 1px solid color-mix(in srgb, #ef4444 22%, transparent);
            color: #ef4444;
            font-family: var(--font-main); font-size: .76rem; font-weight: 600;
            cursor: pointer; transition: var(--transition);
        }
        .btn-logout:hover { background: color-mix(in srgb, #ef4444 14%, transparent); }
        @media (max-width: 1024px) { .btn-logout .btn-logout-label { display: none; } }

        /* Burger */
        .burger-btn {
            display: none; flex-direction: column; gap: 5px;
            background: none; border: none; cursor: pointer; padding: 4px;
        }
        .burger-btn span {
            display: block; width: 22px; height: 2px;
            background: var(--ink); border-radius: 2px; transition: var(--transition);
        }

        /* Mobile nav */
        .mobile-nav { display: none; position: fixed; inset: 0; z-index: 999; }
        .mobile-nav.open { display: block; }
        .mobile-nav-overlay {
            position: absolute; inset: 0; background: rgba(0,0,0,.5);
            backdrop-filter: blur(4px);
        }
        .mobile-nav-drawer {
            position: absolute; top: 0; left: 0; bottom: 0; width: 280px;
            background: var(--paper);
            backdrop-filter: blur(32px);
            border-right: 1px solid var(--glass-border);
            padding: 5rem 1.25rem 2rem;
            display: flex; flex-direction: column; gap: .4rem;
            transition: background .4s ease;
        }
        .mobile-nav-drawer a {
            display: block; padding: .7rem .9rem;
            font-weight: 600; color: var(--ink60);
            text-decoration: none; border-radius: var(--r);
            transition: var(--transition); font-size: .9rem;
        }
        .mobile-nav-drawer a:hover { background: var(--ink06); color: var(--ink); }
        .mobile-nav-drawer a.active {
            color: var(--accent);
            background: color-mix(in srgb, var(--accent) 8%, transparent);
        }
        .mobile-section-label {
            font-size: .62rem; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase; color: var(--ink30);
            padding: .6rem .9rem .2rem; display: block;
        }

        /* ═══ PAGE CONTENT ═══ */
        .page-content {
            position: relative; z-index: 1;
            padding-top: 64px;
        }

        /* ═══ PAGE HEADER ═══ */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 1rem; margin-bottom: 2.5rem;
        }
        .page-header-eyebrow {
            font-size: .7rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
            color: var(--accent); margin-bottom: .35rem;
            display: flex; align-items: center; gap: .45rem;
        }
        .page-header-eyebrow::before { content: ''; width: 14px; height: 1px; background: var(--accent); }
        .page-header-title {
            font-family: var(--font-serif);
            font-size: clamp(1.6rem, 3vw, 2.4rem);
            font-weight: 300; letter-spacing: -.04em; line-height: 1.1;
            font-style: italic; color: var(--ink);
        }
        .page-header-sub {
            font-size: .8rem; color: var(--ink30); margin-top: .3rem; font-weight: 500;
        }

        /* System status pill */
        .status-pill {
            display: flex; align-items: center; gap: .4rem;
            padding: .3rem .85rem; border-radius: var(--rx);
            background: color-mix(in srgb, var(--accent3) 10%, transparent);
            border: 1px solid color-mix(in srgb, var(--accent3) 25%, transparent);
            font-size: .72rem; font-weight: 700; color: var(--accent3);
        }
        .status-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--accent3); animation: adminPulse 2s ease infinite;
        }

        /* ═══ GLASS CARD (alias) ═══ */
        .glass-card {
            background: var(--ink06);
            border: 1px solid var(--glass-border);
            border-radius: var(--rl);
            transition: border-color .3s var(--ease);
            padding: 1.5rem;
        }
        .glass-card:hover { border-color: var(--ink15); }

        /* ═══ BUTTONS ═══ */
        .btn-primary {
            display: inline-flex; align-items: center; gap: .55rem;
            padding: .78rem 1.6rem; font-family: var(--font-main);
            font-size: .85rem; font-weight: 600; color: #fff;
            background: var(--accent);
            border: none; border-radius: var(--r); cursor: pointer; text-decoration: none;
            box-shadow: 0 4px 18px color-mix(in srgb, var(--accent) 30%, transparent);
            transition: var(--transition);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px color-mix(in srgb, var(--accent) 42%, transparent); }

        .btn-glass {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .65rem 1.3rem; font-family: var(--font-main);
            font-size: .82rem; font-weight: 600; color: var(--ink60);
            background: var(--ink06); border: 1px solid var(--glass-border);
            border-radius: var(--r); cursor: pointer; text-decoration: none;
            transition: var(--transition);
        }
        .btn-glass:hover { color: var(--ink); border-color: var(--ink30); background: var(--ink10); }

        /* ═══ BADGES ═══ */
        .badge {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .28rem .75rem; border-radius: var(--rx);
            font-size: .7rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase;
        }
        .badge-indigo { background: color-mix(in srgb,var(--accent) 10%,transparent); color: var(--accent); border: 1px solid color-mix(in srgb,var(--accent) 25%,transparent); }
        .badge-red    { background: color-mix(in srgb,var(--red) 10%,transparent); color: var(--red); border: 1px solid color-mix(in srgb,var(--red) 25%,transparent); }
        .badge-green  { background: color-mix(in srgb,var(--accent3) 10%,transparent); color: var(--accent3); border: 1px solid color-mix(in srgb,var(--accent3) 25%,transparent); }
        .badge-amber  { background: color-mix(in srgb,var(--gold) 12%,transparent); color: var(--gold); border: 1px solid color-mix(in srgb,var(--gold) 28%,transparent); }
        .badge-violet { background: color-mix(in srgb,var(--accent2) 10%,transparent); color: var(--accent2); border: 1px solid color-mix(in srgb,var(--accent2) 25%,transparent); }

        /* ═══ FOOTER ═══ */
        .admin-footer {
            margin-top: 4rem; padding: 1rem 2rem;
            border-top: 1px solid var(--glass-border);
            display: flex; align-items: center; justify-content: space-between;
            font-size: .72rem; color: var(--ink30);
        }

        /* ─── Responsive ─── */
        @media (max-width: 1100px) {
            .navbar-nav { display: none; }
            .burger-btn { display: flex; }
        }
        @media (max-width: 768px) { .navbar { padding: 0 1rem; } }
    </style>
</head>
<body>
    <!-- Background Orbs -->
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>

    <!-- ═══ NAVBAR ═══ -->
    <nav class="navbar">
        <a href="{{ route('admin.dashboard') }}" class="navbar-logo">
            <div class="navbar-logo-icon">
                <img src="{{ asset('im1.jpg') }}" alt="CapAvenir Logo">
            </div>
            <div>
                <div class="navbar-logo-text">CapAvenir</div>
                <div class="navbar-logo-sub">Administration</div>
            </div>
        </a>

        <!-- Desktop Nav -->
        <ul class="navbar-nav">
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Vue Générale</a></li>
            <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Utilisateurs</a></li>
            <li><a href="#">Étudiants & Profils</a></li>
            <li><a href="#">Statistiques IA</a></li>
            <li><a href="#">Paramètres</a></li>
        </ul>

        <!-- Right controls -->
        <div class="navbar-right">
            <button class="theme-toggle" id="themeToggle" title="Basculer le thème">🌙</button>
            <button class="notif-btn">
                🔔 <span class="notif-dot"></span>
            </button>
            <div class="role-badge-admin">
                <span class="admin-dot"></span>
                Super Admin
            </div>
            @auth
            <div class="avatar-nav" title="{{ auth()->user()->name }}">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="btn-logout-label">Déconnexion</span>
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn-primary" style="padding:.45rem 1rem;font-size:.75rem;">Connexion</a>
            @endauth
            <button class="burger-btn" id="burgerBtn" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- ═══ MOBILE NAV ═══ -->
    <div class="mobile-nav" id="mobileNav">
        <div class="mobile-nav-overlay" id="navOverlay"></div>
        <div class="mobile-nav-drawer">
            <span class="mobile-section-label">📊 Vue Générale</span>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>

            <span class="mobile-section-label">👥 Gestion</span>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Utilisateurs</a>
            <a href="#">Étudiants & Profils</a>
            <a href="#">Conseillers</a>

            <span class="mobile-section-label">📈 Analytique & IA</span>
            <a href="#">Statistiques Globales</a>
            <a href="#">Recommandations IA</a>

            <span class="mobile-section-label">⚙️ Système</span>
            <a href="#">Paramètres</a>
            <a href="#">Logs & Sécurité</a>

            @auth
            <form method="POST" action="{{ route('logout') }}" style="margin-top:auto">
                @csrf
                <button type="submit" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;padding:.7rem .9rem;font-family:var(--font-main);font-size:.9rem;font-weight:600;color:#ef4444;border-radius:var(--r);">
                    🚪 Déconnexion
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" style="margin-top:auto;display:block;padding:.7rem .9rem;font-weight:600;color:var(--accent);text-decoration:none;">🔑 Connexion</a>
            @endauth
        </div>
    </div>

    <!-- ═══ PAGE CONTENT ═══ -->
    <div class="page-content">
        <div style="padding:2.5rem 3rem;">

            <!-- Page header -->
            <div class="page-header">
                <div>
                    <p class="page-header-eyebrow">
                        Administration · CapAvenir
                    </p>
                    <h1 class="page-header-title">
                        @yield('title', 'Vue Générale')
                    </h1>
                    <p class="page-header-sub">
                        Plateforme CapAvenir 2026 · Gestion complète
                    </p>
                </div>
                <div>
                    <div class="status-pill">
                        <span class="status-dot"></span>
                        Système optimal · 100%
                    </div>
                </div>
            </div>

            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="admin-footer">
            <span>© 2026 <strong style="color:var(--accent)">CapAvenir</strong> · Tous droits réservés</span>
            <span style="display:flex;align-items:center;gap:.4rem;">
                <span style="width:6px;height:6px;border-radius:50%;background:var(--accent3);display:inline-block;"></span>
                Propulsé par IA · v2.4.0
            </span>
        </footer>
    </div>

    <script>
        /* ── Theme toggle ── */
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;

        (function () {
            const saved = localStorage.getItem('cap-theme');
            if (saved) {
                html.setAttribute('data-theme', saved);
                if (themeToggle) themeToggle.textContent = saved === 'dark' ? '🌙' : '☀️';
            }
        })();

        themeToggle?.addEventListener('click', () => {
            const isDark = html.getAttribute('data-theme') === 'dark';
            const next   = isDark ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            themeToggle.textContent = next === 'dark' ? '🌙' : '☀️';
            localStorage.setItem('cap-theme', next);
        });

        /* ── Burger menu ── */
        const burgerBtn  = document.getElementById('burgerBtn');
        const mobileNav  = document.getElementById('mobileNav');
        const navOverlay = document.getElementById('navOverlay');
        burgerBtn?.addEventListener('click', () => mobileNav.classList.add('open'));
        navOverlay?.addEventListener('click', () => mobileNav.classList.remove('open'));
        document.querySelectorAll('.mobile-nav-drawer a').forEach(link => {
            link.addEventListener('click', () => mobileNav.classList.remove('open'));
        });
    </script>
</body>
</html>
