<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Conseiller') — CapAvenir</title>

    <!-- Google Fonts: DM Sans + Fraunces -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300;1,9..40,400&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;1,9..144,300;1,9..144,400;1,9..144,600&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ═══════════════════════════════════════════
           DESIGN TOKENS — CapAvenir System (conseiller)
           Aligned with student layout & dashboard
        ═══════════════════════════════════════════ */
        :root {
            /* ── Core palette ── */
            --ink:     #0b0c10;
            --paper:   #f7f5f0;
            --cream:   #ede9e1;
            --warm:    #e8e1d4;
            --accent:  #d4622a;   /* terracotta */
            --accent2: #1a4f6e;   /* marine */
            --accent3: #4a7c59;   /* sage */
            --gold:    #c8973a;
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
            --glass-border-vivid: rgba(212,98,42,.30);
            --chat-panel-bg: rgba(247,245,240,.97);
            --shadow-card:   0 8px 40px rgba(0,0,0,.08);
            --transition:    0.3s cubic-bezier(.4,0,.2,1);

            /* ── Legacy aliases (child views) ── */
            --bg-base:       var(--paper);
            --bg-1:          var(--cream);
            --bg-2:          var(--warm);
            --indigo:        var(--accent);
            --indigo-light:  #e07848;
            --violet:        var(--accent2);
            --violet-dark:   #0f3a52;
            --cyan:          var(--accent3);
            --glass-bg:      rgba(11,12,16,.04);
            --glass-bg-md:   rgba(11,12,16,.07);
            --text-primary:  var(--ink);
            --text-secondary: var(--ink60);
            --text-muted:    var(--ink30);
            --success:       #4a7c59;
            --warning:       #c8973a;
            --input-bg:      rgba(11,12,16,.04);
            --card-surface:  rgba(11,12,16,.04);
            --card-surface-md: rgba(11,12,16,.07);
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
            --glass-border-vivid: rgba(212,98,42,.35);
            --chat-panel-bg: rgba(16,16,13,.97);
            --shadow-card:   0 8px 40px rgba(0,0,0,.35);
            --glass-bg:      rgba(240,237,230,.04);
            --glass-bg-md:   rgba(240,237,230,.07);
            --input-bg:      rgba(240,237,230,.07);
            --card-surface:  rgba(240,237,230,.04);
            --card-surface-md: rgba(240,237,230,.07);
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
            background: radial-gradient(circle, color-mix(in srgb,var(--accent2) 8%,transparent) 0%, transparent 70%);
        }
        .bg-orb-3 {
            width: 400px; height: 400px; top: 40%; left: 35%;
            background: radial-gradient(circle, color-mix(in srgb,var(--accent3) 6%,transparent) 0%, transparent 70%);
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
            height: 40px; width: 40px;
            display: flex; align-items: center; justify-content: center;
        }
        .navbar-logo-icon img { 
            height: 100%; width: 100%; object-fit: contain; 
            mix-blend-mode: normal;
        }
        [data-theme="dark"] .navbar-logo-icon img { filter: invert(1) brightness(1.2); }
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
            color: var(--accent2);
            background: color-mix(in srgb, var(--accent2) 8%, transparent);
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

        /* Role badge — marine (accent2) for counselor */
        .role-badge {
            display: flex; align-items: center; gap: .4rem;
            padding: .28rem .75rem; border-radius: var(--rx);
            background: color-mix(in srgb, var(--accent2) 10%, transparent);
            border: 1px solid color-mix(in srgb, var(--accent2) 25%, transparent);
            font-size: .7rem; font-weight: 700;
            color: var(--accent2); letter-spacing: .05em; text-transform: uppercase;
        }
        .role-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--accent2);
            animation: rolePulse 2s ease infinite;
        }
        @keyframes rolePulse { 0%,100%{opacity:1;} 50%{opacity:.35;} }

        /* Avatar */
        .avatar-nav {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--accent2);
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

        /* Mobile nav drawer */
        .mobile-nav {
            display: none; position: fixed; inset: 0; z-index: 999;
        }
        .mobile-nav.open { display: block; }
        .mobile-nav-overlay {
            position: absolute; inset: 0; background: rgba(0,0,0,.5);
            backdrop-filter: blur(4px);
        }
        .mobile-nav-drawer {
            position: absolute; top: 0; left: 0; bottom: 0; width: 280px;
            background: var(--chat-panel-bg);
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
            color: var(--accent2);
            background: color-mix(in srgb, var(--accent2) 8%, transparent);
        }

        /* ═══ PAGE CONTENT ═══ */
        .page-content {
            position: relative; z-index: 1;
            padding-top: 64px;
        }

        /* ═══ GLASS CARD (alias for child views) ═══ */
        .glass-card {
            background: var(--ink06);
            border: 1px solid var(--glass-border);
            border-radius: var(--rl);
            transition: border-color .3s var(--ease);
            padding: 1.5rem;
        }
        .glass-card:hover { border-color: var(--glass-border-vivid); }

        /* ═══ BUTTONS ═══ */
        .btn-violet {
            display: inline-flex; align-items: center; gap: .6rem;
            padding: .85rem 1.75rem; font-family: var(--font-main);
            font-size: .9rem; font-weight: 600; color: #fff;
            background: var(--accent2);
            border: none; border-radius: var(--r); cursor: pointer; text-decoration: none;
            box-shadow: 0 4px 20px color-mix(in srgb, var(--accent2) 30%, transparent);
            transition: var(--transition);
        }
        .btn-violet:hover { transform: translateY(-2px); box-shadow: 0 8px 28px color-mix(in srgb, var(--accent2) 42%, transparent); }

        .btn-glass {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .7rem 1.4rem; font-family: var(--font-main);
            font-size: .85rem; font-weight: 600; color: var(--ink60);
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
        .badge-violet { background: color-mix(in srgb,var(--accent2) 10%,transparent); color: var(--accent2); border: 1px solid color-mix(in srgb,var(--accent2) 25%,transparent); }
        .badge-indigo { background: color-mix(in srgb,var(--accent) 10%,transparent); color: var(--accent); border: 1px solid color-mix(in srgb,var(--accent) 25%,transparent); }
        .badge-cyan   { background: color-mix(in srgb,var(--accent3) 10%,transparent); color: var(--accent3); border: 1px solid color-mix(in srgb,var(--accent3) 25%,transparent); }
        .badge-green  { background: color-mix(in srgb,var(--accent3) 10%,transparent); color: var(--accent3); border: 1px solid color-mix(in srgb,var(--accent3) 25%,transparent); }
        .badge-amber  { background: color-mix(in srgb,var(--gold) 12%,transparent); color: var(--gold); border: 1px solid color-mix(in srgb,var(--gold) 28%,transparent); }
        .badge-red    { background: color-mix(in srgb,#ef4444 10%,transparent); color: #ef4444; border: 1px solid color-mix(in srgb,#ef4444 25%,transparent); }

        /* ═══ MATCH BAR ═══ */
        .match-bar-wrap { height: 4px; background: var(--ink10); border-radius: var(--rx); overflow: hidden; }
        .match-bar-fill {
            height: 100%; border-radius: var(--rx);
            background: var(--accent2);
            transition: width 1.2s cubic-bezier(.4,0,.2,1);
        }

        /* ═══ PAGE HEADER ═══ */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 1rem; margin-bottom: 2.5rem;
        }
        .page-header-eyebrow {
            font-size: .7rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
            color: var(--accent2); margin-bottom: .35rem;
            display: flex; align-items: center; gap: .45rem;
        }
        .page-header-eyebrow::before { content: ''; width: 14px; height: 1px; background: var(--accent2); }
        .page-header-title {
            font-family: var(--font-serif);
            font-size: clamp(1.6rem, 3vw, 2.4rem);
            font-weight: 300; letter-spacing: -.04em; line-height: 1.1;
            color: var(--ink);
        }
        .page-header-title em { font-style: italic; color: var(--accent2); }
        .page-header-sub {
            font-size: .8rem; color: var(--ink30); margin-top: .3rem; font-weight: 500;
        }

        /* System status pill */
        .status-pill {
            display: flex; align-items: center; gap: .4rem;
            padding: .3rem .85rem; border-radius: var(--rx);
            background: color-mix(in srgb, var(--accent3) 10%, transparent);
            border: 1px solid color-mix(in srgb, var(--accent3) 25%, transparent);
            font-size: .72rem; font-weight: 700;
            color: var(--accent3);
        }
        .status-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--accent3); animation: rolePulse 2s ease infinite;
        }

        /* ─── Responsive ─── */
        @media (max-width: 1024px) {
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
        <!-- Logo -->
        <a href="{{ route('counselor.dashboard') }}" class="navbar-logo">
            <div class="navbar-logo-icon">
                <img src="{{ asset('final.png') }}" alt="CapAvenir Logo">
            </div>
            <span class="navbar-logo-text"><span>Avenir</span></span>
            <div>
                <div class="navbar-logo-sub" style="padding: 2px 6px; background: rgba(11,12,16,0.04); border-radius: 4px;">Espace Conseiller</div>
            </div>
        </a>

        <!-- Nav links (desktop) -->
        <ul class="navbar-nav">
            <li><a href="{{ route('counselor.dashboard') }}" class="{{ request()->routeIs('counselor.dashboard') ? 'active' : '' }}">Tableau de bord</a></li>
            <li><a href="#">Mes Étudiants</a></li>
            <li><a href="#">Agenda</a></li>
            <li><a href="{{ route('messages.index') }}" class="{{ request()->routeIs('messages.*') ? 'active' : '' }}">📬 Messagerie</a></li>
            <li><a href="#">Ressources IA</a></li>
            <li><a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">Mon Profil</a></li>
        </ul>

        <!-- Right controls -->
        <div class="navbar-right">
            <button class="theme-toggle" id="themeToggle" title="Basculer le thème">🌙</button>
            @include('partials.notifications')

            <div class="role-badge">
                <span class="role-dot"></span>
                Conseiller
            </div>

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

            <button class="burger-btn" id="burgerBtn" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- ═══ MOBILE NAV ═══ -->
    <div class="mobile-nav" id="mobileNav">
        <div class="mobile-nav-overlay" id="navOverlay"></div>
        <div class="mobile-nav-drawer">
            <a href="{{ route('counselor.dashboard') }}" class="{{ request()->routeIs('counselor.dashboard') ? 'active' : '' }}">📊 Tableau de bord</a>
            <a href="#">👥 Mes Étudiants</a>
            <a href="#">📅 Agenda</a>
            <a href="{{ route('messages.index') }}" class="{{ request()->routeIs('messages.*') ? 'active' : '' }}">📬 Messagerie</a>
            <a href="#">🧠 Ressources IA</a>
            <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">⚙️ Mon Profil</a>
            <form method="POST" action="{{ route('logout') }}" style="margin-top:auto">
                @csrf
                <button type="submit" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;padding:.7rem .9rem;font-family:var(--font-main);font-size:.9rem;font-weight:600;color:#ef4444;border-radius:var(--r);">
                    🚪 Déconnexion
                </button>
            </form>
        </div>
    </div>

    <!-- ═══ PAGE CONTENT ═══ -->
    <div class="page-content">
        <div style="max-width:1400px;margin:0 auto;padding:2.5rem 2rem;">

            <!-- Page header -->
            <div class="page-header">
                <div>
                    <p class="page-header-eyebrow">
                        Bonjour {{ explode(' ', auth()->user()->name)[0] }}
                    </p>
                    <h1 class="page-header-title" style="font-style:italic;">
                        @yield('title', 'Espace Conseiller')
                    </h1>
                    <p class="page-header-sub">
                        Surveillance en temps réel · Plateforme CapAvenir 2026
                    </p>
                </div>
                <div>
                    <div class="status-pill">
                        <span class="status-dot"></span>
                        Système opérationnel
                    </div>
                </div>
            </div>

            @yield('content')
        </div>
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