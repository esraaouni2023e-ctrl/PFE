<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Étudiant') — CapAvenir</title>

    <!-- Google Fonts: DM Sans + Fraunces -->
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
            --input-bg:      rgba(30, 41, 59, 0.04);
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
            --input-bg:      rgba(241, 245, 249, 0.07);
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

        /* ─── Ambient Orbs ─── */
        .bg-orb {
            position: fixed; border-radius: 50%;
            filter: blur(80px); pointer-events: none; z-index: 0; opacity: 0.5;
        }
        .bg-orb-1 { width: 300px; height: 300px; top: -100px; right: -50px; background: radial-gradient(circle, color-mix(in srgb,var(--accent) 12%,transparent) 0%, transparent 70%); }
        .bg-orb-2 { width: 250px; height: 250px; bottom: 100px; left: -100px; background: radial-gradient(circle, color-mix(in srgb,var(--accent2) 10%,transparent) 0%, transparent 70%); }

        /* ─── MOBILE HEADER ─── */
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
        .notif-badge-mob {
            position: absolute; top: -2px; right: -2px;
            background: var(--red); color: #fff; border-radius: 50%;
            width: 16px; height: 16px; font-size: 0.65rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--paper);
        }

        /* ─── BOTTOM TAB BAR ─── */
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

        /* ─── MOBILE DRAWER MENU ─── */
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

        /* ─── PAGE CONTENT ─── */
        .page-content-mob {
            position: relative; z-index: 1;
            padding: 1.25rem 1rem;
            width: 100%;
        }

        /* ─── Floating chat bubble (mobile) ─── */
        .floating-chat-bubble-mob {
            position: fixed; bottom: 5rem; right: 1.25rem; z-index: 880;
            width: 50px; height: 50px; border-radius: 50%;
            background: var(--accent); border: none; cursor: pointer;
            color: #fff; font-size: 1.25rem; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 16px color-mix(in srgb, var(--accent) 30%, transparent);
            transition: var(--transition);
        }
        .chat-ping-mob {
            position: absolute; top: 3px; right: 3px;
            width: 10px; height: 10px; border-radius: 50%;
            background: var(--accent3); border: 2px solid var(--paper);
        }

        /* ─── Chat panel full screen on mobile ─── */
        .chat-panel-mob {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1002;
            background: var(--paper); display: flex; flex-direction: column;
            transform: translateY(100%); transition: transform 0.4s cubic-bezier(0.1, 0.76, 0.55, 0.94);
        }
        .chat-panel-mob.open { transform: translateY(0); }
        .chat-header-mob {
            height: 60px; border-bottom: 1px solid var(--glass-border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.25rem; background: color-mix(in srgb, var(--accent) 4%, transparent);
        }
        .chat-close-mob { background: none; border: none; font-size: 1.4rem; color: var(--ink60); cursor: pointer; }
        .chat-messages-mob {
            flex: 1; overflow-y: auto; padding: 1rem; display: flex; flex-direction: column; gap: 0.8rem;
        }
        .chat-msg-mob { display: flex; }
        .chat-msg-mob.ai { justify-content: flex-start; }
        .chat-msg-mob.user { justify-content: flex-end; }
        .chat-msg-content-mob {
            max-width: 85%; padding: 0.75rem 1rem; border-radius: 16px; font-size: 0.9rem; line-height: 1.5;
        }
        .chat-msg-mob.ai .chat-msg-content-mob {
            background: var(--cream); border: 1px solid var(--glass-border); border-bottom-left-radius: 4px; color: var(--ink);
        }
        .chat-msg-mob.user .chat-msg-content-mob {
            background: var(--accent); color: #fff; border-bottom-right-radius: 4px;
        }
        .chat-input-wrap-mob {
            padding: 0.75rem 1rem calc(0.75rem + env(safe-area-inset-bottom));
            border-top: 1px solid var(--glass-border); display: flex; gap: 0.5rem;
        }
        .chat-input-mob {
            flex: 1; background: var(--cream); border: 1px solid var(--glass-border);
            border-radius: var(--r); padding: 0.75rem 1rem; color: var(--ink);
            font-family: inherit; font-size: 1rem; outline: none;
        }
        .chat-input-mob:focus { border-color: var(--accent); }
        .chat-send-mob {
            width: 44px; height: 44px; border-radius: var(--r); background: var(--accent);
            border: none; color: #fff; display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; cursor: pointer;
        }

        /* ─── Tactile and Form elements optimization ─── */
        button, .btn { min-height: 44px; display: inline-flex; align-items: center; justify-content: center; }
        input, select, textarea { font-size: 16px !important; } /* Prevents iOS auto-zoom */

        /* Progression indicator banner */
        .pipeline-progress-banner-mob {
            background: var(--paper); border: 1px solid var(--glass-border); border-radius: var(--rl);
            padding: 1rem; margin-bottom: 1rem; box-shadow: var(--shadow-card);
        }
    </style>
</head>
<body>
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>

    <!-- Mobile Header -->
    <header class="mob-header">
        <a href="{{ route('student.dashboard') }}" class="logo-mob">
            <img src="{{ asset('final.png') }}" alt="Logo">
            <span>CapAvenir</span>
        </a>
        <div class="header-actions">
            @include('partials.notifications')
            <button class="header-btn" id="drawerBtn" title="Menu"><i class="bi bi-list"></i></button>
        </div>
    </header>

    <!-- Drawer Navigation (Secondary Menu) -->
    <div class="drawer" id="sideDrawer">
        <div class="drawer-overlay" id="drawerOverlay"></div>
        <div class="drawer-content">
            <button class="drawer-close" id="drawerClose"><i class="bi bi-x-lg"></i></button>
            
            <div class="drawer-section">Outils & Services</div>
            <a href="{{ route('student.cv.index') }}" class="drawer-link {{ request()->routeIs('student.cv.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-person"></i> CV Builder
            </a>
            <a href="{{ route('student.comparateur.index') }}" class="drawer-link {{ request()->routeIs('student.comparateur.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i> Comparateur
            </a>
            <a href="{{ route('testimonial.edit') }}" class="drawer-link {{ request()->routeIs('testimonial.edit') ? 'active' : '' }}">
                <i class="bi bi-star"></i> Témoignage
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
        <a href="{{ route('student.dashboard') }}" class="tab-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i>
            <span>Accueil</span>
        </a>
        <a href="{{ route('student.orientation') }}" class="tab-item {{ request()->routeIs('student.orientation') ? 'active' : '' }}">
            <i class="bi bi-compass"></i>
            <span>Orientation</span>
        </a>
        <a href="{{ route('student.whatif.index') }}" class="tab-item {{ request()->routeIs('student.whatif.*') ? 'active' : '' }}">
            <i class="bi bi-magic"></i>
            <span>Simulateur</span>
        </a>
        <a href="{{ route('messages.index') }}" class="tab-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
            <i class="bi bi-chat-text"></i>
            <span>Messages</span>
        </a>
        <a href="{{ route('student.profil') }}" class="tab-item {{ request()->routeIs('student.profil') ? 'active' : '' }}">
            <i class="bi bi-mortarboard"></i>
            <span>Profil Acad.</span>
        </a>
    </nav>

    <!-- Page Content Area -->
    <main class="page-content-mob">
        @auth
            @if(auth()->user()->role === 'student')
                @php
                    $u = auth()->user();
                    $prof = $u->profile;
                    $hasAcad = $prof && ($prof->score_fg || $prof->is_academique_complet);
                    $hasRia = \App\Models\ProfileRiasec::pourUser($u->id)->complets()->exists();
                    $hasRecs = \App\Models\Recommendation::where('user_id', $u->id)->where('source', 'SIAEPI_v5')->exists();
                    
                    $step1Done = (bool)$hasAcad;
                    $step2Done = (bool)$hasRia;
                    $step3Done = (bool)$hasRecs;
                    
                    $progressPercent = 0;
                    if ($step1Done) $progressPercent += 33;
                    if ($step2Done) $progressPercent += 33;
                    if ($step3Done) $progressPercent += 34;
                @endphp
                
                @if($progressPercent < 100)
                    <div class="pipeline-progress-banner-mob">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: color-mix(in srgb, var(--accent) 10%, transparent); display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 1rem;">
                                🎯
                            </div>
                            <div style="flex: 1;">
                                <h4 style="font-size: 0.85rem; font-weight: 700; color: var(--ink);">Progression Orientation</h4>
                                <div style="height: 4px; background: var(--warm); border-radius: var(--rx); margin-top: 4px; overflow: hidden; position: relative;">
                                    <div style="height: 100%; background: linear-gradient(90deg, var(--accent), var(--accent3)); width: {{ $progressPercent }}%;"></div>
                                </div>
                            </div>
                        </div>
                        <p style="font-size: 0.75rem; color: var(--ink60); line-height: 1.4; margin-bottom: 0.75rem;">
                            @if(!$step1Done)
                                Étape 1 : Renseigne tes notes pour calculer ton score Formule Globale (FG).
                            @elseif(!$step2Done)
                                Étape 2 : Passe le test RIASEC pour déterminer tes intérêts et aptitudes GATB.
                            @else
                                Étape 3 : Consulte tes recommandations personnalisées de filières.
                            @endif
                        </p>
                        @if(!$step1Done)
                            <a href="{{ route('student.profil') }}" class="btn-fill" style="width:100%; min-height:38px; border-radius:6px; background:var(--accent2); color:#fff; text-decoration:none; font-size:0.8rem; font-weight:700;">Compléter mon profil</a>
                        @elseif(!$step2Done)
                            <a href="{{ route('student.pipeline') }}" class="btn-fill" style="width:100%; min-height:38px; border-radius:6px; background:var(--accent); color:#fff; text-decoration:none; font-size:0.8rem; font-weight:700;">Commencer le test</a>
                        @else
                            <a href="{{ route('student.recommendations') }}" class="btn-fill" style="width:100%; min-height:38px; border-radius:6px; background:var(--accent); color:#fff; text-decoration:none; font-size:0.8rem; font-weight:700;">Voir mes filières</a>
                        @endif
                    </div>
                @endif
            @endif
        @endauth

        @yield('content')
    </main>

    <!-- Floating Chat Bubble -->
    <button class="floating-chat-bubble-mob" id="floatingChatMob" title="ORIENTIA">
        <i class="bi bi-chat-dots-fill"></i>
        <div class="chat-ping-mob"></div>
    </button>

    <!-- Full-screen Chat Panel for Mobile -->
    <div class="chat-panel-mob" id="chatPanelMob">
        <div class="chat-header-mob">
            <div style="display: flex; align-items: center; gap: 0.6rem;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--ink); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">🤖</div>
                <div>
                    <div style="font-weight: 700; font-size: 0.85rem;">ORIENTIA</div>
                    <div style="font-size: 0.65rem; color: var(--accent3); font-weight: 600;">● En ligne</div>
                </div>
            </div>
            <button class="chat-close-mob" id="closeChatBtnMob">✕</button>
        </div>
        
        <div class="chat-messages-mob" id="chatMessagesMob">
            <div class="chat-msg-mob ai">
                <div class="chat-msg-content-mob">
                    Bonjour <strong>{{ explode(' ', auth()->user()?->name ?? 'Invité')[0] }}</strong>. Je suis ORIENTIA, ton conseiller RIASEC. Pour commencer, indique-moi ton age, ton niveau d'etudes, les filieres que tu envisages, puis les matieres que tu aimes et celles que tu aimes moins.
                </div>
            </div>
        </div>

        <div class="chat-input-wrap-mob">
            <input type="text" class="chat-input-mob" id="chatInputMob" placeholder="Pose ta question à l'IA…" />
            <button class="chat-send-mob" id="chatSendMob">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>

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

        // Mobile Chat Panel Controls
        const floatingChatMob = document.getElementById('floatingChatMob');
        const chatPanelMob = document.getElementById('chatPanelMob');
        const closeChatBtnMob = document.getElementById('closeChatBtnMob');
        const chatInputMob = document.getElementById('chatInputMob');
        const chatSendMob = document.getElementById('chatSendMob');
        const chatMessagesMob = document.getElementById('chatMessagesMob');

        let chatHistoryMob = [];

        floatingChatMob?.addEventListener('click', () => {
            chatPanelMob.classList.add('open');
            chatInputMob.focus();
        });
        closeChatBtnMob?.addEventListener('click', () => {
            chatPanelMob.classList.remove('open');
        });

        function appendMessageMob(role, text) {
            const wrap = document.createElement('div');
            wrap.className = `chat-msg-mob ${role}`;
            const safe = text.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>');
            wrap.innerHTML = `<div class="chat-msg-content-mob">${safe}</div>`;
            chatMessagesMob.appendChild(wrap);
            chatMessagesMob.scrollTop = chatMessagesMob.scrollHeight;
            return wrap;
        }

        chatSendMob?.addEventListener('click', handleChatSubmitMob);
        chatInputMob?.addEventListener('keypress', (e) => {
            if(e.key === 'Enter') handleChatSubmitMob();
        });

        function handleChatSubmitMob() {
            const query = chatInputMob.value.trim();
            if(!query) return;

            appendMessageMob('user', query);
            chatInputMob.value = '';

            // Loading bubble
            const loading = document.createElement('div');
            loading.className = 'chat-msg-mob ai';
            loading.innerHTML = `<div class="chat-msg-content-mob" style="opacity: 0.6;"><em>Recherche en cours...</em></div>`;
            chatMessagesMob.appendChild(loading);
            chatMessagesMob.scrollTop = chatMessagesMob.scrollHeight;

            fetch('{{ route('student.chatbot') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: query, history: chatHistoryMob })
            })
            .then(r => r.json())
            .then(data => {
                loading.remove();
                if(data.success && data.response) {
                    appendMessageMob('ai', data.response);
                    chatHistoryMob.push({ role: 'user', content: query });
                    chatHistoryMob.push({ role: 'model', content: data.response });
                } else {
                    appendMessageMob('ai', "Une erreur est survenue lors de l'envoi du message.");
                }
            })
            .catch(() => {
                loading.remove();
                appendMessageMob('ai', "Erreur de connexion.");
            });
        }
    </script>
</body>
</html>
