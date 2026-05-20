<x-app-layout>
    <!-- Google Fonts direct import to ensure DM Sans & Fraunces are loaded -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;1,9..144,300;1,9..144,400;1,9..144,600&display=swap');

        /* ════════════════════════════════════════════
           CAPAVENIR PORTAL SELECTOR SYSTEM
           High-end Glassmorphic & Minimalist Style
        ════════════════════════════════════════════ */
        .portal-container {
            font-family: 'DM Sans', sans-serif;
            min-height: calc(100vh - 64px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem 1.5rem;
            position: relative;
            z-index: 2;
        }

        /* Ambient glowing orbs */
        .portal-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(140px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.15;
        }
        .portal-orb-1 {
            width: 500px;
            height: 500px;
            top: 10%;
            left: 15%;
            background: radial-gradient(circle, #EA580C 0%, transparent 70%);
        }
        .portal-orb-2 {
            width: 450px;
            height: 450px;
            bottom: 10%;
            right: 15%;
            background: radial-gradient(circle, #0A2540 0%, transparent 70%);
        }

        .portal-title-area {
            text-align: center;
            margin-bottom: 4rem;
            z-index: 10;
        }
        .portal-eyebrow {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #EA580C; /* Terracotta signature */
            margin-bottom: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .portal-eyebrow::before, .portal-eyebrow::after {
            content: '';
            width: 16px;
            height: 1px;
            background: #EA580C;
        }
        .portal-title {
            font-family: 'Fraunces', serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 300;
            color: #ffffff;
            letter-spacing: -0.03em;
            line-height: 1.15;
            margin: 0;
        }
        .portal-title em {
            font-family: 'Fraunces', serif;
            font-style: italic;
            font-weight: 400;
            color: #EA580C;
        }
        .portal-subtitle {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.6);
            max-width: 540px;
            margin: 1rem auto 0 auto;
            line-height: 1.5;
        }

        /* 3-Column Layout */
        .portal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.25rem;
            width: 100%;
            max-width: 1150px;
            z-index: 10;
        }
        @media (min-width: 1025px) and (max-width: 1200px) {
            .portal-grid {
                max-width: 960px;
            }
        }

        /* Glassmorphic Cards */
        .portal-card {
            background: rgba(10, 20, 40, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 2.5rem 2.25rem;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 380px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .portal-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, transparent 100%);
            opacity: 1;
            transition: opacity 0.4s ease;
        }
        .portal-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.45);
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* Card Icon */
        .portal-icon-box {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            position: relative;
            z-index: 2;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .portal-card:hover .portal-icon-box {
            transform: scale(1.1);
            background: rgba(255, 255, 255, 0.08);
        }

        /* Highlight overlays on hover */
        .portal-card-glow {
            position: absolute;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            filter: blur(50px);
            top: -50px;
            right: -50px;
            opacity: 0.15;
            transition: opacity 0.4s ease, transform 0.4s ease;
            z-index: 1;
        }
        .portal-card:hover .portal-card-glow {
            opacity: 0.3;
            transform: scale(1.2);
        }

        .portal-card-student .portal-card-glow { background: #EA580C; }
        .portal-card-counselor .portal-card-glow { background: #0A2540; }
        .portal-card-admin .portal-card-glow { background: #8b5cf6; }

        .portal-card-student:hover { border-color: rgba(234, 88, 12, 0.4); }
        .portal-card-counselor:hover { border-color: rgba(10, 37, 64, 0.5); }
        .portal-card-admin:hover { border-color: rgba(139, 92, 246, 0.4); }

        /* Card Content */
        .portal-card-title {
            font-family: 'Fraunces', serif;
            font-size: 1.6rem;
            font-weight: 400;
            color: #ffffff;
            margin: 0 0 1rem 0;
            position: relative;
            z-index: 2;
        }
        .portal-card-desc {
            font-size: 0.88rem;
            color: rgba(255, 255, 255, 0.55);
            line-height: 1.6;
            margin-bottom: 2rem;
            flex-grow: 1;
            position: relative;
            z-index: 2;
        }

        /* Action Link */
        .portal-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.8);
            margin-top: auto;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }
        .portal-action-btn svg {
            width: 14px;
            height: 14px;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .portal-card:hover .portal-action-btn {
            color: #ffffff;
        }
        .portal-card:hover .portal-action-btn svg {
            transform: translateX(6px);
        }

        .portal-card-student .portal-action-btn { color: #EA580C; }
        .portal-card-counselor .portal-action-btn { color: #38BDF8; }
        .portal-card-admin .portal-action-btn { color: #A78BFA; }

        /* Huge watermark text in background */
        .portal-watermark {
            position: absolute;
            bottom: -2.5rem;
            right: -1rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 5.5rem;
            font-weight: 900;
            letter-spacing: -0.05em;
            color: rgba(255, 255, 255, 0.025);
            user-select: none;
            z-index: 1;
            transition: color 0.4s ease;
        }
        .portal-card:hover .portal-watermark {
            color: rgba(255, 255, 255, 0.04);
        }
    </style>

    <div class="portal-container">
        <!-- Glowing background decorations -->
        <div class="portal-orb portal-orb-1"></div>
        <div class="portal-orb portal-orb-2"></div>

        {{-- Welcome Header --}}
        <div class="portal-title-area">
            <span class="portal-eyebrow">CapAvenir Portal</span>
            <h1 class="portal-title">
                Bienvenue, <em>{{ auth()->user()->name }}</em>
            </h1>
            <p class="portal-subtitle">
                Choisissez l'interface avec laquelle vous souhaitez travailler aujourd'hui. Vos droits d'accès vous permettent de naviguer entre ces différents espaces.
            </p>
        </div>

        {{-- Selector Grid --}}
        <div class="portal-grid">
            
            @if(auth()->user()->isStudent() || auth()->user()->isAdmin())
            <!-- Student Portal Card -->
            <a href="{{ route('student.dashboard') }}" class="portal-card portal-card-student">
                <div class="portal-card-glow"></div>
                
                <div class="portal-icon-box">
                    <svg class="w-7 h-7 text-[#EA580C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6" />
                    </svg>
                </div>
                
                <h3 class="portal-card-title">Espace Étudiant</h3>
                <p class="portal-card-desc">
                    Réalisez vos évaluations d'orientation basées sur le modèle RIASEC, explorez vos aptitudes, consultez vos rapports d'analyse IA et suivez l'évolution de votre projet d'avenir.
                </p>
                
                <span class="portal-action-btn">
                    Accéder à l'espace
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </span>
                
                <div class="portal-watermark">STUDENT</div>
            </a>
            @endif

            @if(auth()->user()->isCounselor() || auth()->user()->isAdmin())
            <!-- Counselor Portal Card -->
            <a href="{{ route('counselor.dashboard') }}" class="portal-card portal-card-counselor">
                <div class="portal-card-glow"></div>
                
                <div class="portal-icon-box">
                    <svg class="w-7 h-7 text-[#519bc7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                
                <h3 class="portal-card-title">Portail Conseiller</h3>
                <p class="portal-card-desc">
                    Suivez et encadrez vos étudiants, analysez graphiquement leurs résultats psychométriques, gérez vos consultations d'orientation et planifiez vos rendez-vous depuis votre CRM.
                </p>
                
                <span class="portal-action-btn">
                    Rejoindre le portail
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </span>
                
                <div class="portal-watermark">EXPERT</div>
            </a>
            @endif

            @if(auth()->user()->isAdmin())
            <!-- Admin Portal Card -->
            <a href="{{ route('admin.dashboard') }}" class="portal-card portal-card-admin">
                <div class="portal-card-glow"></div>
                
                <div class="portal-icon-box">
                    <svg class="w-7 h-7 text-[#c084fc]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                
                <h3 class="portal-card-title">Administration</h3>
                <p class="portal-card-desc">
                    Gérez les utilisateurs et les candidatures des conseillers, ajustez le référentiel des coefficients des matières, configurez les filières et auditez la sécurité du système.
                </p>
                
                <span class="portal-action-btn">
                    Gérer la plateforme
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </span>
                
                <div class="portal-watermark">SYSTEM</div>
            </a>
            @endif

        </div>
    </div>
</x-app-layout>
