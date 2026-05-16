<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Étudiant') — CapAvenir</title>

    <!-- Google Fonts: DM Sans + Fraunces (same as dashboard) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300;1,9..40,400&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;1,9..144,300;1,9..144,400;1,9..144,600&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ═══════════════════════════════════════════
           DESIGN TOKENS — CapAvenir System (aligned with dashboard)
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
            --r:   6px;
            --rl:  16px;
            --rx:  999px;
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

            /* ── Legacy aliases (used by child views) ── */
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
            border-radius: var(--r);
            transition: var(--transition);
        }
        .navbar-nav a:hover {
            color: var(--ink);
            background: var(--ink06);
        }
        .navbar-nav a.active {
            color: var(--accent);
            background: color-mix(in srgb, var(--accent) 8%, transparent);
        }

        /* Right area */
        .navbar-right {
            display: flex; align-items: center; gap: .6rem;
        }

        /* Theme toggle */
        .theme-toggle {
            width: 34px; height: 34px; border-radius: var(--r);
            background: var(--ink06); border: 1px solid var(--glass-border);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: .95rem;
            transition: var(--transition); color: var(--ink60);
        }
        .theme-toggle:hover { border-color: var(--ink30); color: var(--ink); }

        /* Chat button */
        .chat-bubble-nav {
            position: relative; width: 34px; height: 34px; border-radius: var(--r);
            border: 1px solid color-mix(in srgb, var(--accent3) 28%, transparent);
            background: color-mix(in srgb, var(--accent3) 10%, transparent);
            display: flex; align-items: center; justify-content: center;
            font-size: .95rem; cursor: pointer;
            transition: var(--transition);
        }
        .chat-bubble-nav:hover {
            background: color-mix(in srgb, var(--accent3) 18%, transparent);
            border-color: color-mix(in srgb, var(--accent3) 45%, transparent);
        }
        .chat-online-dot {
            position: absolute; top: 5px; right: 5px;
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--accent3);
            border: 1.5px solid var(--paper);
            animation: dotPulse 2s ease infinite;
        }
        @keyframes dotPulse {
            0%,100% { opacity: 1; }
            50%      { opacity: .35; }
        }

        /* Avatar */
        .avatar-nav {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--ink);
            display: flex; align-items: center; justify-content: center;
            font-size: .82rem; font-weight: 700; color: var(--paper);
            cursor: pointer;
            transition: var(--transition);
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

        /* Burger */
        .burger-btn {
            display: none; flex-direction: column; gap: 5px;
            background: none; border: none; cursor: pointer; padding: 4px;
        }
        .burger-btn span {
            display: block; width: 22px; height: 2px;
            background: var(--ink); border-radius: 2px;
            transition: var(--transition);
        }

        /* Mobile nav drawer */
        .mobile-nav {
            display: none; position: fixed; inset: 0; z-index: 999;
        }
        .mobile-nav.open { display: block; }
        .mobile-nav-overlay {
            position: absolute; inset: 0;
            background: rgba(0,0,0,.5);
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
        .mobile-nav-drawer a:hover  { background: var(--ink06); color: var(--ink); }
        .mobile-nav-drawer a.active { color: var(--accent); background: color-mix(in srgb, var(--accent) 8%, transparent); }

        /* ═══ PAGE CONTENT ═══ */
        .page-content {
            position: relative; z-index: 1;
            padding-top: 64px;
        }

        /* ═══ GLASS CARD (legacy alias kept for child views) ═══ */
        .glass-card {
            background: var(--ink06);
            border: 1px solid var(--glass-border);
            border-radius: var(--rl);
            transition: border-color .3s var(--ease);
        }
        .glass-card:hover { border-color: var(--glass-border-vivid); }
        .glass-card-lg {
            background: var(--ink06);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
        }

        /* ═══ BADGES ═══ */
        .badge-ai {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .28rem .75rem; border-radius: var(--rx);
            font-size: .7rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase;
        }
        .badge-indigo { background: color-mix(in srgb,var(--accent) 10%,transparent); color: var(--accent); border: 1px solid color-mix(in srgb,var(--accent) 25%,transparent); }
        .badge-cyan   { background: color-mix(in srgb,var(--accent3) 10%,transparent); color: var(--accent3); border: 1px solid color-mix(in srgb,var(--accent3) 25%,transparent); }
        .badge-violet { background: color-mix(in srgb,var(--accent2) 10%,transparent); color: var(--accent2); border: 1px solid color-mix(in srgb,var(--accent2) 25%,transparent); }
        .badge-green  { background: color-mix(in srgb,var(--accent3) 10%,transparent); color: var(--accent3); border: 1px solid color-mix(in srgb,var(--accent3) 25%,transparent); }
        .badge-amber  { background: color-mix(in srgb,var(--gold) 12%,transparent); color: var(--gold); border: 1px solid color-mix(in srgb,var(--gold) 28%,transparent); }

        /* ═══ BUTTONS ═══ */
        .btn-cyan {
            display: inline-flex; align-items: center; gap: .6rem;
            padding: .85rem 1.75rem; font-family: var(--font-main);
            font-size: .9rem; font-weight: 600; color: #fff;
            background: var(--accent2);
            border: none; border-radius: var(--r); cursor: pointer; text-decoration: none;
            box-shadow: 0 4px 20px color-mix(in srgb, var(--accent2) 30%, transparent);
            transition: var(--transition);
        }
        .btn-cyan:hover { transform: translateY(-2px); box-shadow: 0 8px 28px color-mix(in srgb, var(--accent2) 40%, transparent); }

        .btn-violet {
            display: inline-flex; align-items: center; gap: .6rem;
            padding: .85rem 1.75rem; font-family: var(--font-main);
            font-size: .9rem; font-weight: 600; color: #fff;
            background: var(--accent);
            border: none; border-radius: var(--r); cursor: pointer; text-decoration: none;
            box-shadow: 0 4px 20px color-mix(in srgb, var(--accent) 30%, transparent);
            transition: var(--transition);
        }
        .btn-violet:hover { transform: translateY(-2px); box-shadow: 0 8px 28px color-mix(in srgb, var(--accent) 45%, transparent); }

        .btn-glass {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .7rem 1.4rem; font-family: var(--font-main);
            font-size: .85rem; font-weight: 600; color: var(--ink60);
            background: var(--ink06); border: 1px solid var(--glass-border);
            border-radius: var(--r); cursor: pointer; text-decoration: none;
            transition: var(--transition);
        }
        .btn-glass:hover { color: var(--ink); border-color: var(--ink30); background: var(--ink10); }

        /* ═══ PROGRESS RING ═══ */
        .progress-ring circle { transition: stroke-dashoffset 1s cubic-bezier(.4,0,.2,1); }

        /* ═══ SCORE NUMBER ═══ */
        .score-number {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 900; line-height: 1;
            color: var(--accent);
        }

        /* ═══ MATCH BAR ═══ */
        .match-bar-wrap { height: 4px; background: var(--ink10); border-radius: var(--rx); overflow: hidden; }
        .match-bar-fill {
            height: 100%; border-radius: var(--rx);
            background: var(--accent);
            transition: width 1.2s cubic-bezier(.4,0,.2,1);
        }

        /* ─── Float animation ─── */
        @keyframes floatY {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-10px); }
        }
        .float-anim { animation: floatY 4s ease-in-out infinite; }

        /* ─── Responsive ─── */
        @media (max-width: 1024px) {
            .navbar-nav { display: none; }
            .burger-btn { display: flex; }
        }
        @media (max-width: 768px) {
            .navbar { padding: 0 1rem; }
        }
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
        <a href="{{ route('student.dashboard') }}" class="navbar-logo">
            <div class="navbar-logo-icon">
                <img src="{{ asset('final.png') }}" alt="CapAvenir Logo">
            </div>
            <div>
                <span class="navbar-logo-text"><span>Avenir</span></span>
                <div class="navbar-logo-sub" style="padding: 2px 6px; background: rgba(11,12,16,0.04); border-radius: 4px;">Espace Étudiant</div>
            </div>
        </a>

        <!-- Nav links (desktop) -->
        <ul class="navbar-nav">
            <li><a href="{{ route('student.dashboard') }}"    class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">Accueil</a></li>
            <li><a href="{{ route('student.orientation') }}"  class="{{ request()->routeIs('student.orientation') ? 'active' : '' }}">Orientation</a></li>
            <li><a href="{{ route('student.whatif.index') }}" class="{{ request()->routeIs('student.whatif.*') ? 'active' : '' }}">What-If</a></li>
            <li><a href="{{ route('student.comparateur.index') }}" class="{{ request()->routeIs('student.comparateur.*') ? 'active' : '' }}">Comparateur</a></li>
            <li><a href="{{ route('student.voeux.index') }}"  class="{{ request()->routeIs('student.voeux.*') ? 'active' : '' }}">Vœux</a></li>
            <li><a href="{{ route('student.profil') }}"       class="{{ request()->routeIs('student.profil') ? 'active' : '' }}">Profil Académique</a></li>
            <li><a href="{{ route('student.cv.index') }}"     class="{{ request()->routeIs('student.cv.*') ? 'active' : '' }}">CV Builder</a></li>
            <li><a href="{{ route('messages.index') }}"       class="{{ request()->routeIs('messages.*') ? 'active' : '' }}">Messagerie</a></li>
        </ul>

        <!-- Right controls -->
        <div class="navbar-right">
            <button class="theme-toggle" id="themeToggle" title="Basculer le thème"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z' /></svg></button>
            @include('partials.notifications')

            <a href="{{ route('profile.edit') }}" style="text-decoration:none;">
                <div class="avatar-nav" title="{{ auth()->user()?->name ?? 'Invité' }}" style="overflow:hidden;">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        {{ strtoupper(substr(auth()->user()?->name ?? 'I', 0, 1)) }}
                    @endif
                </div>
            </a>


            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="btn-logout">
                    Déconnexion
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
            <a href="{{ route('student.dashboard') }}"   class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">Accueil</a>
            <a href="{{ route('student.orientation') }}"  class="{{ request()->routeIs('student.orientation') ? 'active' : '' }}">Orientation</a>
            <a href="{{ route('student.whatif.index') }}" class="{{ request()->routeIs('student.whatif.*') ? 'active' : '' }}">What-If</a>
            <a href="{{ route('student.comparateur.index') }}" class="{{ request()->routeIs('student.comparateur.*') ? 'active' : '' }}">Comparateur</a>
            <a href="{{ route('student.voeux.index') }}"  class="{{ request()->routeIs('student.voeux.*') ? 'active' : '' }}">Vœux</a>
            <a href="{{ route('student.profil') }}"       class="{{ request()->routeIs('student.profil') ? 'active' : '' }}">Profil Académique</a>
            <a href="{{ route('student.cv.index') }}"     class="{{ request()->routeIs('student.cv.*') ? 'active' : '' }}">CV Builder</a>
            <a href="{{ route('messages.index') }}"       class="{{ request()->routeIs('messages.*') ? 'active' : '' }}">Messagerie</a>
            <a href="{{ route('profile.edit') }}">Mon Profil</a>
            <form method="POST" action="{{ route('logout') }}" style="margin-top:auto">
                @csrf
                <button type="submit" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;padding:.7rem .9rem;font-family:var(--font-main);font-size:.9rem;font-weight:600;color:#ef4444;border-radius:var(--r);">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>

    <!-- ═══ PAGE CONTENT ═══ -->
    <div class="page-content">
        @yield('content')
    </div>

    <!-- ═══ FLOATING CHAT BUBBLE (mobile) ═══ -->
    <button class="floating-chat-bubble" id="floatingChat" title="ORIENTIA">
        <span><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z' /></svg></span>
        <div class="chat-ping"></div>
    </button>

    <!-- ═══ CHAT PANEL ═══ -->
    <div class="chat-panel" id="chatPanel">
        <div class="chat-panel-header">
            <div style="display:flex;align-items:center;gap:.65rem;">
                <div class="chat-ai-avatar"><svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M8.25 3v1.5M4.5 8.25H3m18 0h-1.5m-15 7.5H3m18 0h-1.5m-15 4.5V21m3-18v1.5m.375 0h.008v.008H12V3.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z' /></svg></div>
                <div>
                    <div style="font-weight:700;font-size:.87rem;">ORIENTIA</div>
                    <div style="font-size:.7rem;color:var(--accent3);font-weight:600;">● En ligne</div>
                </div>
            </div>
            <button class="chat-close" id="closeChatBtn">✕</button>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="chat-msg ai">
                <div class="chat-msg-content">
                    Bonjour <strong>{{ explode(' ', auth()->user()?->name ?? 'Invité')[0] }}</strong>. Je suis ORIENTIA, ton conseiller RIASEC. Pour commencer, indique-moi ton age, ton niveau d'etudes, les filieres que tu envisages, puis les matieres que tu aimes et celles que tu aimes moins.
                </div>
            </div>
        </div>
        <div class="chat-input-wrap">
            <input type="text" class="chat-input" id="chatInput" placeholder="Pose ta question à l'IA…" />
            <button class="chat-send" id="chatSend">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
    </div>

    <style>
        /* ─── Floating chat bubble ─── */
        .floating-chat-bubble {
            display: flex;
            position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 990;
            width: 54px; height: 54px; border-radius: 50%;
            background: var(--accent);
            border: none; cursor: pointer; font-size: 1.3rem;
            box-shadow: 0 4px 20px color-mix(in srgb, var(--accent) 40%, transparent);
            align-items: center; justify-content: center;
            transition: var(--transition);
        }
        .floating-chat-bubble:hover { transform: scale(1.07); }
        .chat-ping {
            position: absolute; top: 6px; right: 6px;
            width: 11px; height: 11px; border-radius: 50%;
            background: var(--accent3); border: 2px solid var(--paper);
        }

        /* ─── Chat panel ─── */
        .chat-panel {
            position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 995;
            width: 360px; max-height: 520px;
            background: var(--chat-panel-bg);
            backdrop-filter: blur(32px);
            border: 1px solid var(--glass-border-vivid);
            border-radius: 20px;
            display: flex; flex-direction: column;
            overflow: hidden;
            box-shadow: var(--shadow-card);
            transform: scale(.92) translateY(16px);
            opacity: 0; pointer-events: none;
            transition: all .35s cubic-bezier(.34,1.56,.64,1), background .4s ease;
        }
        .chat-panel.open {
            transform: scale(1) translateY(0);
            opacity: 1; pointer-events: all;
        }
        @media (max-width: 480px) {
            .chat-panel { width: calc(100vw - 2rem); bottom: 5rem; right: 1rem; }
        }

        .chat-panel-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: .9rem 1.1rem;
            border-bottom: 1px solid var(--glass-border);
            background: color-mix(in srgb, var(--accent) 5%, transparent);
        }
        .chat-ai-avatar {
            width: 34px; height: 34px; border-radius: 50%; font-size: 1rem;
            background: var(--ink);
            display: flex; align-items: center; justify-content: center;
        }
        .chat-close {
            background: none; border: none; cursor: pointer;
            color: var(--ink30); font-size: .9rem;
            width: 26px; height: 26px; border-radius: var(--r);
            display: flex; align-items: center; justify-content: center;
            transition: var(--transition);
        }
        .chat-close:hover { background: var(--ink06); color: var(--ink); }

        .chat-messages {
            flex: 1; overflow-y: auto; padding: .9rem;
            display: flex; flex-direction: column; gap: .65rem;
            max-height: 320px;
        }
        .chat-messages::-webkit-scrollbar { width: 4px; }
        .chat-messages::-webkit-scrollbar-track { background: transparent; }
        .chat-messages::-webkit-scrollbar-thumb { background: var(--ink10); border-radius: 4px; }

        .chat-msg { display: flex; }
        .chat-msg.ai   { justify-content: flex-start; }
        .chat-msg.user { justify-content: flex-end; }

        .chat-msg-content {
            max-width: 85%; padding: .65rem .9rem;
            border-radius: 14px; font-size: .83rem; line-height: 1.55;
        }
        .chat-msg.ai .chat-msg-content {
            background: var(--ink06); border: 1px solid var(--glass-border);
            border-bottom-left-radius: 4px; color: var(--ink);
        }
        .chat-msg.user .chat-msg-content {
            background: var(--accent);
            color: #fff; border-bottom-right-radius: 4px;
        }

        .chat-input-wrap {
            display: flex; gap: .5rem; padding: .7rem .9rem;
            border-top: 1px solid var(--glass-border);
        }
        .chat-input {
            flex: 1; background: var(--ink06); border: 1px solid var(--glass-border);
            border-radius: var(--r); padding: .55rem .85rem;
            color: var(--ink); font-family: var(--font-main); font-size: .83rem;
            transition: border-color .2s;
        }
        .chat-input:focus { outline: none; border-color: var(--accent); }
        .chat-input::placeholder { color: var(--ink30); }

        .chat-send {
            width: 34px; height: 34px; border-radius: var(--r);
            background: var(--accent); border: none; cursor: pointer; color: #fff;
            display: flex; align-items: center; justify-content: center;
            transition: var(--transition); flex-shrink: 0;
        }
        .chat-send:hover { transform: scale(1.05); opacity: .88; }

        /* ─── Typing indicator ─── */
        .chat-typing {
            display: flex; align-items: center; gap: 5px;
            padding: .65rem .9rem !important;
        }
        .chat-typing span {
            display: block; width: 7px; height: 7px; border-radius: 50%;
            background: var(--ink30);
            animation: typingBounce 1.2s ease infinite;
        }
        .chat-typing span:nth-child(2) { animation-delay: .2s; }
        .chat-typing span:nth-child(3) { animation-delay: .4s; }
        @keyframes typingBounce {
            0%, 60%, 100% { transform: translateY(0);    opacity: .5; }
            30%            { transform: translateY(-6px); opacity: 1;  }
        }

        /* Disabled state for input/send during fetch */
        .chat-input:disabled { opacity: .55; cursor: not-allowed; }
        .chat-send:disabled  { opacity: .45; cursor: not-allowed; transform: none; }
    </style>

    <script>
        /* ── Theme toggle ── */
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;

        (function () {
            const saved = localStorage.getItem('cap-theme') || 'light';
            html.setAttribute('data-theme', saved);
            if (themeToggle) themeToggle.innerHTML = saved === 'dark' ? `<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z' /></svg>` : `<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M3 12h2.25m.386-6.364l1.591 1.591M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z' /></svg>`;
        })();

        themeToggle?.addEventListener('click', () => {
            const isDark = html.getAttribute('data-theme') === 'dark';
            const next   = isDark ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            themeToggle.innerHTML = next === 'dark' ? `<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z' /></svg>` : `<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M3 12h2.25m.386-6.364l1.591 1.591M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z' /></svg>`;
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

        /* ── Chat panel ── */
        const chatPanel   = document.getElementById('chatPanel');
        const closeChatBtn= document.getElementById('closeChatBtn');
        const floatingChat= document.getElementById('floatingChat');
        const chatInput   = document.getElementById('chatInput');
        const chatSend    = document.getElementById('chatSend');
        const chatMessages= document.getElementById('chatMessages');

        // Keeps full conversation history for multi-turn context
        let chatHistory = [];

        const openChat  = () => { chatPanel.classList.add('open'); chatInput?.focus(); };
        const closeChat = () => chatPanel.classList.remove('open');

        floatingChat?.addEventListener('click', openChat);
        closeChatBtn?.addEventListener('click', closeChat);

        /** Append a message bubble to the chat panel */
        function appendMessage(role, text) {
            const wrap = document.createElement('div');
            wrap.className = `chat-msg ${role}`;
            // Sanitise newlines so they render as line-breaks
            const safe = text.replace(/</g, '&lt;').replace(/>/g, '&gt;')
                             .replace(/\n/g, '<br>');
            wrap.innerHTML = `<div class="chat-msg-content">${safe}</div>`;
            chatMessages.appendChild(wrap);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            return wrap;
        }

        /** Show / remove typing indicator */
        function showTyping() {
            const el = document.createElement('div');
            el.className = 'chat-msg ai';
            el.id = 'typingIndicator';
            el.innerHTML = `<div class="chat-msg-content chat-typing">
                <span></span><span></span><span></span>
            </div>`;
            chatMessages.appendChild(el);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        function removeTyping() {
            document.getElementById('typingIndicator')?.remove();
        }

        /** Send message to Gemini via Laravel backend */
        async function sendChatMsg() {
            const text = chatInput.value.trim();
            if (!text) return;

            // 1. Render the user's message
            appendMessage('user', text);
            chatInput.value = '';
            chatInput.disabled = true;
            chatSend.disabled = true;

            // 2. Save to history (role "user")
            chatHistory.push({ role: 'user', content: text });

            // 3. Show typing animation
            showTyping();

            try {
                // 4. POST to Laravel -> Gemini
                const res = await fetch('{{ route("student.chatbot") }}', {
                    method:  'POST',
                    headers: {
                        'Content-Type':     'application/json',
                        'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                        'Accept':           'application/json',
                    },
                    body: JSON.stringify({
                        message: text,
                        history: chatHistory.slice(-6), // max 6 tours pour économiser le quota
                    }),
                });

                removeTyping();
                const data = await res.json();

                if (data.error) {
                    appendMessage('ai', `<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='#ef4444' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 12.376zM12 15.75h.008v.008H12v-.008z' /></svg> ` + data.error);
                } else {
                    const reply = data.reply ?? `<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M8.25 3v1.5M4.5 8.25H3m18 0h-1.5m-15 7.5H3m18 0h-1.5m-15 4.5V21m3-18v1.5m.375 0h.008v.008H12V3.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z' /></svg> Je n'ai pas pu générer une réponse.`;
                    appendMessage('ai', reply);
                    // 5. Save AI reply to history (role "model")
                    chatHistory.push({ role: 'model', content: reply });
                }

            } catch (err) {
                removeTyping();
                appendMessage('ai', `<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1rem;height:1rem;display:inline-block;vertical-align:middle;'><path stroke-linecap='round' stroke-linejoin='round' d='M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z' /></svg> Erreur de connexion. Vérifiez votre réseau et réessayez.`);
                console.error('Chatbot error:', err);
            } finally {
                chatInput.disabled = false;
                chatSend.disabled  = false;
                chatInput.focus();
            }
        }

        chatSend?.addEventListener('click', sendChatMsg);
        chatInput?.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) sendChatMsg(); });

    </script>
</body>
</html>
