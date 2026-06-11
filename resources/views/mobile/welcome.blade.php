<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CapAvenir — Orientation IA (Mobile)</title>
    
    <!-- Google Fonts: Sora + Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;1,9..144,300;1,9..144,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --ink:     #1E293B;
            --paper:   #FFFFFF;
            --cream:   #F8FAFC;
            --warm:    #E2E8F0;
            --accent:  #EA580C;
            --accent2: #0A2540;
            --accent3: #10B981;
            --gold:    #F59E0B;
            --red:     #EF4444;
            --rl:      16px;
            --r:       8px;
            --rx:      999px;
            --font-main: 'DM Sans', sans-serif;
            --font-serif: 'Fraunces', serif;
            --transition: 0.3s cubic-bezier(.4,0,.2,1);
        }

        [data-theme="dark"] {
            --ink:   #F1F5F9;
            --paper: #0E1324;
            --cream: #070A10;
            --warm:  #1D2433;
            --accent: #F97316;
            --accent2: #38BDF8;
            --accent3: #34D399;
            --gold:    #FBBF24;
            --red:     #F87171;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font-main);
            background: var(--cream);
            color: var(--ink);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            transition: background .4s, color .4s;
            padding-top: 60px;
        }

        /* Ambient Orbs */
        .bg-orb {
            position: fixed; border-radius: 50%; filter: blur(80px); pointer-events: none; z-index: 0; opacity: 0.6;
        }
        .bg-orb-1 { width: 300px; height: 300px; top: -100px; right: -50px; background: radial-gradient(circle, color-mix(in srgb,var(--accent) 15%,transparent) 0%, transparent 70%); }
        .bg-orb-2 { width: 250px; height: 250px; bottom: 50px; left: -100px; background: radial-gradient(circle, color-mix(in srgb,var(--accent2) 12%,transparent) 0%, transparent 70%); }

        /* Mobile Header */
        header {
            position: fixed; top: 0; left: 0; right: 0; height: 60px; background: rgba(248, 250, 252, 0.92);
            backdrop-filter: blur(12px); border-bottom: 1px solid var(--warm); z-index: 1000;
            display: flex; align-items: center; justify-content: space-between; padding: 0 1.25rem;
        }
        [data-theme="dark"] header { background: rgba(7, 10, 16, 0.92); }

        .logo { display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: var(--ink); font-family: var(--font-serif); font-weight: 700; font-size: 1.1rem; }
        .logo img { height: 32px; width: 32px; }

        .nav-actions { display: flex; align-items: center; gap: 0.5rem; }
        .theme-btn, .burger-btn {
            width: 38px; height: 38px; border-radius: var(--r); border: 1px solid var(--warm); background: transparent;
            color: var(--ink); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; cursor: pointer;
        }
        .btn-start {
            padding: 0.5rem 1rem; border-radius: var(--r); background: var(--accent); color: #fff; text-decoration: none;
            font-size: 0.8rem; font-weight: 700; border: none; box-shadow: 0 4px 10px color-mix(in srgb, var(--accent) 25%, transparent);
        }

        /* Drawer menu */
        .drawer {
            position: fixed; top: 0; left: 0; bottom: 0; right: 0; z-index: 1001; opacity: 0; pointer-events: none; transition: var(--transition);
        }
        .drawer.open { opacity: 1; pointer-events: all; }
        .drawer-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); }
        .drawer-content {
            position: absolute; top: 0; right: 0; bottom: 0; width: 80%; max-width: 300px; background: var(--paper);
            padding: 4rem 1.5rem 2rem; display: flex; flex-direction: column; gap: 1.5rem; border-left: 1px solid var(--warm);
        }
        .drawer-close { position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 1.5rem; color: var(--ink); cursor: pointer; }
        .drawer-link { text-decoration: none; color: var(--ink); font-size: 1.1rem; font-weight: 600; padding: 0.5rem 0; border-bottom: 1px solid var(--warm); }

        /* Hero Section */
        .hero { padding: 3rem 1.25rem 2.5rem; text-align: center; position: relative; z-index: 1; }
        .hero-eyebrow { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--accent); margin-bottom: 1rem; }
        .hero-title { font-family: var(--font-serif); font-size: 2.2rem; font-weight: 300; line-height: 1.1; margin-bottom: 1.25rem; }
        .hero-title em { font-style: italic; color: var(--accent); font-weight: 400; }
        .hero-title strong { font-weight: 700; display: block; }
        .hero-sub { font-size: 0.9rem; color: color-mix(in srgb, var(--ink) 65%, transparent); line-height: 1.6; margin-bottom: 2rem; }
        .hero-ctas { display: flex; flex-direction: column; gap: 0.8rem; }
        .btn-primary-mob { padding: 0.9rem; border-radius: var(--r); background: var(--accent); color: #fff; text-decoration: none; font-weight: 700; font-size: 0.95rem; display: block; box-shadow: 0 6px 16px color-mix(in srgb, var(--accent) 30%, transparent); }
        .btn-secondary-mob { padding: 0.9rem; border-radius: var(--r); border: 1px solid var(--warm); background: var(--paper); color: var(--ink); text-decoration: none; font-weight: 600; font-size: 0.95rem; display: block; }

        /* Stats Bar */
        .stats-bar { background: var(--paper); border-top: 1px solid var(--warm); border-bottom: 1px solid var(--warm); padding: 1.5rem 1.25rem; margin-top: 1.5rem; position: relative; z-index: 1; }
        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; text-align: center; }
        .stat-item h3 { font-size: 1.8rem; font-family: var(--font-serif); color: var(--accent); margin-bottom: 0.25rem; }
        .stat-item p { font-size: 0.75rem; color: color-mix(in srgb, var(--ink) 55%, transparent); font-weight: 500; }

        /* Features Section */
        .section-pad { padding: 3.5rem 1.25rem; position: relative; z-index: 1; }
        .section-tag { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: var(--accent); margin-bottom: 0.5rem; text-align: center; display: block; }
        .section-heading { font-family: var(--font-serif); font-size: 1.65rem; text-align: center; margin-bottom: 2rem; line-height: 1.2; }
        .section-heading em { font-style: italic; color: var(--accent); }
        
        .feat-stack { display: flex; flex-direction: column; gap: 1.25rem; }
        .feat-card { background: var(--paper); border: 1px solid var(--warm); border-radius: var(--rl); padding: 1.5rem; }
        .feat-icon { width: 40px; height: 40px; border-radius: var(--r); background: color-mix(in srgb, var(--accent) 8%, transparent); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 1rem; }
        .feat-title { font-family: var(--font-serif); font-size: 1.15rem; margin-bottom: 0.5rem; }
        .feat-desc { font-size: 0.85rem; color: color-mix(in srgb, var(--ink) 60%, transparent); line-height: 1.5; }

        /* Testimonials Slider */
        .testimonials { background: var(--paper); border-top: 1px solid var(--warm); border-bottom: 1px solid var(--warm); }
        .testimonial-slider { position: relative; width: 100%; overflow: hidden; padding: 1.5rem 0.5rem; }
        .testimonial-card { background: var(--cream); border: 1px solid var(--warm); border-radius: var(--rl); padding: 1.5rem; margin: 0 0.5rem; display: none; flex-direction: column; justify-content: space-between; min-height: 200px; }
        .testimonial-card.active { display: flex; }
        .testi-quote { font-family: var(--font-serif); font-style: italic; font-size: 1.05rem; line-height: 1.5; margin-bottom: 1.25rem; color: var(--ink); }
        .testi-meta { display: flex; align-items: center; gap: 0.75rem; border-top: 1px solid var(--warm); padding-top: 0.85rem; }
        .testi-ava { width: 38px; height: 38px; border-radius: 50%; background: var(--accent); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; overflow: hidden; }
        .testi-ava img { width: 100%; height: 100%; object-fit: cover; }
        .testi-name { font-size: 0.8rem; font-weight: 700; }
        .testi-role { font-size: 0.72rem; color: color-mix(in srgb, var(--ink) 50%, transparent); }
        .testi-stars { color: var(--gold); font-size: 0.75rem; margin-top: 0.15rem; }
        .slider-dots { display: flex; justify-content: center; gap: 0.5rem; margin-top: 1.25rem; }
        .slider-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--warm); border: none; }
        .slider-dot.active { background: var(--accent); width: 16px; border-radius: var(--rx); }

        /* Contact Section */
        .contact-card { background: var(--paper); border: 1px solid var(--warm); border-radius: var(--rl); padding: 2rem 1.25rem; }
        .form-group { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1.25rem; }
        .form-group label { font-size: 0.8rem; font-weight: 700; color: color-mix(in srgb, var(--ink) 60%, transparent); }
        .form-control { background: var(--cream); border: 1px solid var(--warm); border-radius: var(--r); padding: 0.8rem 1rem; font-family: inherit; font-size: 0.95rem; color: var(--ink); outline: none; transition: border-color 0.2s; }
        .form-control:focus { border-color: var(--accent); }

        /* Footer */
        footer { background: var(--paper); border-top: 1px solid var(--warm); padding: 3rem 1.25rem 2rem; text-align: center; }
        .footer-logo { font-family: var(--font-serif); font-size: 1.25rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem; text-decoration: none; color: var(--ink); }
        .footer-logo img { height: 36px; width: 36px; }
        .footer-socials { display: flex; justify-content: center; gap: 1rem; margin-bottom: 2rem; }
        .footer-socials a { width: 36px; height: 36px; border-radius: 50%; border: 1px solid var(--warm); display: flex; align-items: center; justify-content: center; color: color-mix(in srgb, var(--ink) 60%, transparent); text-decoration: none; }
        .footer-copy { font-size: 0.72rem; color: color-mix(in srgb, var(--ink) 40%, transparent); }
    </style>
</head>
<body>
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>

    <!-- Mobile Header -->
    <header>
        <a href="#" class="logo">
            <img src="{{ asset('final.png') }}" alt="Logo">
            <span>CapAvenir</span>
        </a>
        <div class="nav-actions">
            <button class="theme-btn" id="themeToggle" title="Thème"><i class="bi bi-sun"></i></button>
            <a href="/login" class="btn-start">Se connecter</a>
            <button class="burger-btn" id="burgerBtn" title="Menu"><i class="bi bi-list"></i></button>
        </div>
    </header>

    <!-- Mobile Drawer Menu -->
    <div class="drawer" id="mobileDrawer">
        <div class="drawer-overlay" id="drawerOverlay"></div>
        <div class="drawer-content">
            <button class="drawer-close" id="drawerClose"><i class="bi bi-x-lg"></i></button>
            <a href="#features" class="drawer-link">Fonctionnalités</a>
            <a href="#testimonials" class="drawer-link">Témoignages</a>
            <a href="#contact" class="drawer-link">Contact</a>
            <a href="/register" class="btn-primary-mob" style="text-align:center; margin-top:2rem;">Commencer gratuitement</a>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-eyebrow">Orientation IA · Tunisie 2026</div>
        <h1 class="hero-title">
            Trouve la voie<br>
            qui te <em>ressemble</em><br>
            <strong>vraiment.</strong>
        </h1>
        <p class="hero-sub">
            CapAvenir analyse tes aptitudes, valeurs et ambitions grâce à l'IA pour te proposer un parcours universitaire sur mesure en Tunisie.
        </p>
        <div class="hero-ctas">
            <a href="/register" class="btn-primary-mob">Découvrir mon orientation</a>
            <a href="/login" class="btn-secondary-mob">Accéder à mon espace</a>
        </div>
    </section>

    <!-- Stats Bar -->
    <div class="stats-bar">
        <div class="stats-grid">
            <div class="stat-item">
                <h3>12 400+</h3>
                <p>Étudiants orientés</p>
            </div>
            <div class="stat-item">
                <h3>94%</h3>
                <p>De satisfaction</p>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section id="features" class="section-pad">
        <span class="section-tag">Avantages</span>
        <h2 class="section-heading">L'orientation réinventée par <em>l'IA</em></h2>
        <div class="feat-stack">
            <div class="feat-card">
                <div class="feat-icon"><i class="bi bi-cpu"></i></div>
                <h3 class="feat-title">Test IA conversationnel</h3>
                <p class="feat-desc">Un dialogue intelligent qui s'adapte à tes réponses pour cerner tes véritables intérêts.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon"><i class="bi bi-bar-chart"></i></div>
                <h3 class="feat-title">Simulateur What-If</h3>
                <p class="feat-desc">Simule tes notes de Bac tunisien pour estimer tes chances d'admission dans les meilleures écoles.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon"><i class="bi bi-people"></i></div>
                <h3 class="feat-title">Échange avec conseillers</h3>
                <p class="feat-desc">Prends rendez-vous avec des conseillers d'orientation directement depuis ton espace.</p>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="section-pad testimonials">
        <span class="section-tag">Retours</span>
        <h2 class="section-heading">Ils ont construit leur <em>avenir</em></h2>
        
        <div class="testimonial-slider">
            @if(isset($testimonials) && $testimonials->count() > 0)
                @foreach($testimonials as $index => $t)
                    <div class="testimonial-card {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                        <blockquote class="testi-quote">
                            « {{ $t->comment }} »
                        </blockquote>
                        <div class="testi-meta">
                            @php
                                $avatarUrl = $t->user?->getAvatarUrl();
                            @endphp
                            @if($avatarUrl)
                                <div class="testi-ava">
                                    <img src="{{ $avatarUrl }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" alt="">
                                    <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; color: #fff; background: {{ $t->user?->role === 'counselor' ? 'var(--accent2)' : 'var(--accent)' }};">
                                        {{ strtoupper(substr($t->user?->name ?? 'U', 0, 1)) }}
                                    </div>
                                </div>
                            @else
                                <div class="testi-ava" style="background: {{ $t->user?->role === 'counselor' ? 'var(--accent2)' : 'var(--accent)' }};">
                                    {{ strtoupper(substr($t->user?->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="testi-name">{{ $t->user?->name ?? 'Anonyme' }}</div>
                                <div class="testi-role">
                                    @if($t->user?->role === 'student')
                                        Étudiant · CapAvenir
                                    @elseif($t->user?->role === 'counselor')
                                        Conseiller · CapAvenir
                                    @else
                                        {{ ucfirst($t->user?->role) }}
                                    @endif
                                </div>
                                <div class="testi-stars">
                                    {{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="slider-dots">
                    @foreach($testimonials as $index => $t)
                        <button class="slider-dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></button>
                    @endforeach
                </div>
            @else
                <!-- Fallback Testimony -->
                <div class="testimonial-card active" data-index="0">
                    <blockquote class="testi-quote">
                        « J'hésitais entre médecine et informatique. L'IA m'a ouvert les yeux sur la data science. Aujourd'hui je suis à l'INSAT et je me sens à ma place. »
                    </blockquote>
                    <div class="testi-meta">
                        <div class="testi-ava">E</div>
                        <div>
                            <div class="testi-name">Esraa Mansouri</div>
                            <div class="testi-role">Étudiante · INSAT Tunis</div>
                            <div class="testi-stars">★★★★★</div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card" data-index="1">
                    <blockquote class="testi-quote">
                        « En tant que conseillère, le rapport généré par l'IA me fait gagner un temps précieux pour me concentrer sur l'écoute active des élèves. »
                    </blockquote>
                    <div class="testi-meta">
                        <div class="testi-ava" style="background:var(--accent2)">S</div>
                        <div>
                            <div class="testi-name">Sarah Ben Amor</div>
                            <div class="testi-role">Conseillère d'orientation</div>
                            <div class="testi-stars">★★★★★</div>
                        </div>
                    </div>
                </div>
                <div class="slider-dots">
                    <button class="slider-dot active" data-slide="0"></button>
                    <button class="slider-dot" data-slide="1"></button>
                </div>
            @endif
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section-pad">
        <span class="section-tag">Échanger</span>
        <h2 class="section-heading">Une question ?<br><em>Contacte-nous</em></h2>
        
        <div class="contact-card">
            @if(session('success'))
                <div style="background: var(--accent3); color: #fff; padding: 1rem; border-radius: var(--r); margin-bottom: 1.5rem; font-weight: 500; font-size: 0.85rem;">
                    ✓ {{ session('success') }}
                </div>
            @endif
            <form action="{{ route('contact.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Ahmed Ben Salah" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="ahmed@example.com" required>
                </div>
                <div class="form-group">
                    <label for="sujet">Sujet</label>
                    <input type="text" id="sujet" name="sujet" class="form-control" placeholder="Demande d'informations" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" class="form-control" rows="4" placeholder="Votre message..." required></textarea>
                </div>
                <button type="submit" class="btn-primary-mob" style="width: 100%; border: none; cursor: pointer; text-align: center;">Envoyer le message</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <a href="#" class="footer-logo">
            <img src="{{ asset('final.png') }}" alt="Logo">
            <span>CapAvenir</span>
        </a>
        <div class="footer-socials">
            <a href="#" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
            <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
        </div>
        <p class="footer-copy">© 2026 CapAvenir · Tous droits réservés · Tunis, Tunisie</p>
    </footer>

    <script>
        // Drawer Menu Control
        const burgerBtn = document.getElementById('burgerBtn');
        const drawer = document.getElementById('mobileDrawer');
        const drawerClose = document.getElementById('drawerClose');
        const drawerOverlay = document.getElementById('drawerOverlay');

        burgerBtn.addEventListener('click', () => drawer.classList.add('open'));
        drawerClose.addEventListener('click', () => drawer.classList.remove('open'));
        drawerOverlay.addEventListener('click', () => drawer.classList.remove('open'));

        document.querySelectorAll('.drawer-link').forEach(link => {
            link.addEventListener('click', () => drawer.classList.remove('open'));
        });

        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        let dark = false;
        themeToggle.addEventListener('click', () => {
            dark = !dark;
            html.setAttribute('data-theme', dark ? 'dark' : 'light');
            themeToggle.innerHTML = dark ? '<i class="bi bi-moon"></i>' : '<i class="bi bi-sun"></i>';
        });

        // Testimonials Slider
        const cards = document.querySelectorAll('.testimonial-card');
        const dots = document.querySelectorAll('.slider-dot');
        let currentSlide = 0;

        function showSlide(index) {
            cards.forEach(card => card.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            cards[index].classList.add('active');
            dots[index].classList.add('active');
            currentSlide = index;
        }

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => showSlide(index));
        });

        // Auto-play slider
        if (cards.length > 1) {
            setInterval(() => {
                let next = (currentSlide + 1) % cards.length;
                showSlide(next);
            }, 5000);
        }
    </script>
</body>
</html>
