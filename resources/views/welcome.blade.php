<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CapAvenir — Trouve ta voie, construis ton avenir</title>
  <meta name="description"
    content="Plateforme d'orientation intelligente propulsée par l'IA. Découvre ton profil unique et construis ton avenir.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;1,9..144,300;1,9..144,400&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    /* ── TOKENS ── */
    :root {
      --ink: #0b0c10;
      --paper: #f7f5f0;
      --cream: #ede9e1;
      --warm: #e8e1d4;
      --accent: #FF6A00;
      --accent2: #0057B8;
      --accent3: #4a7c59;
      --gold: #FF8C1A;
      --ink60: rgba(11, 12, 16, .6);
      --ink30: rgba(11, 12, 16, .3);
      --ink10: rgba(11, 12, 16, .1);
      --r: 6px;
      --rl: 16px;
      --rx: 999px;
      --ease: cubic-bezier(.16, 1, .3, 1);
    }

    [data-theme="dark"] {
      --ink: #f0ede6;
      --paper: #10100d;
      --cream: #18170f;
      --warm: #1f1e14;
      --ink60: rgba(240, 237, 230, .6);
      --ink30: rgba(240, 237, 230, .3);
      --ink10: rgba(240, 237, 230, .08);
    }

    /* ── RESET ── */
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    html {
      scroll-behavior: smooth;
      font-size: 16px
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--paper);
      color: var(--ink);
      overflow-x: hidden;
      line-height: 1.6;
      transition: background .4s, color .4s
    }

    a {
      color: inherit;
      text-decoration: none
    }

    img {
      max-width: 100%;
      display: block
    }

    /* ── NOISE TEXTURE ── */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      z-index: 0;
      pointer-events: none;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.04'/%3E%3C/svg%3E");
      opacity: .5;
    }

    /* ── NAV ── */
    nav {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 900;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1.25rem 3rem;
      transition: all .4s var(--ease);
    }

    nav.scrolled {
      background: color-mix(in srgb, var(--paper) 85%, transparent);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--ink10);
      padding: .9rem 3rem;
    }

    .nav-logo {
      display: flex;
      align-items: center;
      gap: .75rem;
    }

    .logo-mark {
      height: 48px;
      width: 48px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .logo-mark img {
      height: 100%;
      width: 100%;
      object-fit: contain;
    }

    [data-theme="dark"] .logo-mark img {
      filter: invert(1) brightness(1.2);
    }

    .logo-name {
      font-family: 'Fraunces', serif;
      font-size: 1.35rem;
      font-weight: 600;
      letter-spacing: -.03em;
      color: var(--ink);
      line-height: 1;
    }

    .logo-name span {
      color: var(--accent);
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: .25rem;
      list-style: none;
    }

    .nav-links a {
      font-size: .85rem;
      font-weight: 500;
      color: var(--ink60);
      padding: .45rem .8rem;
      border-radius: var(--rx);
      transition: all .25s;
    }

    .nav-links a:hover {
      color: var(--ink);
      background: var(--ink10);
    }

    .nav-right {
      display: flex;
      align-items: center;
      gap: .75rem;
    }

    .btn-nav {
      font-family: 'DM Sans', sans-serif;
      font-size: .85rem;
      font-weight: 500;
      padding: .5rem 1.25rem;
      border-radius: var(--rx);
      border: 1px solid var(--ink30);
      background: transparent;
      color: var(--ink);
      cursor: pointer;
      transition: all .25s;
    }

    .btn-nav:hover {
      background: var(--ink10);
    }

    .btn-nav-fill {
      background: var(--accent);
      color: #fff;
      border-color: var(--accent);
      box-shadow: 0 4px 20px color-mix(in srgb, var(--accent) 35%, transparent);
    }

    .btn-nav-fill:hover {
      background: color-mix(in srgb, var(--accent) 85%, #000);
      transform: translateY(-1px);
    }

    #themeBtn {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: var(--ink10);
      border: none;
      cursor: pointer;
      font-size: .95rem;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--ink);
      transition: all .25s;
    }

    #themeBtn:hover {
      background: var(--ink);
      color: var(--paper);
    }

    /* ── HERO ── */
    .hero {
      min-height: 100vh;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      padding: 8rem 3rem 5rem;
    }

    .hero-inner {
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 5rem;
      align-items: center;
      position: relative;
      z-index: 10;
    }

    /* Subtle radial glow BG */
    .hero::before {
      content: '';
      position: absolute;
      width: 700px;
      height: 700px;
      border-radius: 50%;
      background: radial-gradient(circle,
          color-mix(in srgb, var(--accent) 10%, transparent),
          transparent 65%);
      right: -10%;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      z-index: 0;
    }

    .hero::after {
      content: '';
      position: absolute;
      width: 400px;
      height: 400px;
      border-radius: 50%;
      background: radial-gradient(circle,
          color-mix(in srgb, var(--accent2) 12%, transparent),
          transparent 65%);
      left: -5%;
      bottom: 10%;
      pointer-events: none;
      z-index: 0;
    }

    .hero-left {
      position: relative;
      z-index: 10;
    }

    .hero-right {
      position: relative;
      z-index: 10;
      display: flex;
      justify-content: center;
      align-items: center;
      animation: fadeUp .9s var(--ease) .35s both;
    }

    /* ── BENTO GRID ── */
    .bento-grid {
      width: 100%;
      max-width: 500px;
      display: grid;
      grid-template-columns: 1.4fr 1fr;
      grid-template-rows: auto auto auto;
      gap: 12px;
      animation: fadeUp .9s var(--ease) .4s both;
    }

    .bento-card {
      position: relative;
      overflow: hidden;
      background: color-mix(in srgb, var(--paper) 85%, transparent);
      border: 1px solid var(--ink10);
      border-radius: 16px;
      padding: 1.25rem;
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      transition: border-color .3s, transform .3s var(--ease);
    }

    .bento-card:hover {
      border-color: color-mix(in srgb, var(--accent) 40%, transparent);
      transform: translateY(-3px);
    }

    .bento-glow {
      position: absolute;
      width: 120px;
      height: 120px;
      border-radius: 50%;
      filter: blur(50px);
      opacity: .25;
      pointer-events: none;
    }

    .bento-glow-accent {
      background: var(--accent);
      top: -30px;
      right: -20px;
    }

    .bento-glow-gold {
      background: var(--gold);
      bottom: -20px;
      left: -10px;
    }

    .bento-glow-marine {
      background: var(--accent2);
      top: -20px;
      left: -20px;
    }

    .bento-glow-sage {
      background: var(--accent3);
      bottom: -20px;
      right: -20px;
    }

    /* Grid placement */
    .bento-lg {
      grid-row: span 2;
    }

    .bento-sm {}

    .bento-wide {
      grid-column: span 2;
    }

    /* Tags & labels */
    .bento-tag {
      font-size: .68rem;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: .65rem;
    }

    .bento-label {
      font-size: .88rem;
      color: var(--ink60);
      line-height: 1.5;
      margin-top: .5rem;
    }

    .bento-label strong {
      color: var(--ink);
      font-weight: 700;
    }

    /* RIASEC card */
    .bento-riasec {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 1.5rem 1rem;
    }

    .bento-radar {
      width: 140px;
      height: 130px;
      margin-bottom: .25rem;
    }

    .bento-shape {
      fill: color-mix(in srgb, var(--accent) 15%, transparent);
      stroke: var(--accent);
      stroke-width: 1.5;
      animation: radarDraw 1.5s var(--ease) .5s both;
    }

    @keyframes radarDraw {
      from {
        opacity: 0;
        transform: scale(.3) rotate(-10deg);
      }

      to {
        opacity: 1;
        transform: scale(1) rotate(0);
      }
    }

    .bento-radar-dims {
      position: relative;
      width: 160px;
      height: 0;
      margin-top: -8px;
    }

    .bento-radar-dims span {
      position: absolute;
      font-size: .65rem;
      font-weight: 800;
      color: var(--ink30);
      letter-spacing: .04em;
    }

    /* Score card */
    .bento-score {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    .bento-score-circle {
      position: relative;
      width: 70px;
      height: 70px;
    }

    .bento-score-circle svg {
      width: 100%;
      height: 100%;
      transform: rotate(-90deg);
    }

    .bento-ring-anim {
      animation: ringDraw 1.5s var(--ease) .6s both;
    }

    @keyframes ringDraw {
      from {
        stroke-dashoffset: 214;
      }
    }

    .bento-score-val {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Fraunces', serif;
      font-size: 1.45rem;
      font-weight: 700;
      color: var(--accent);
      letter-spacing: -.03em;
    }

    .bento-score-val small {
      font-size: .7rem;
      font-weight: 400;
    }

    /* What-If card */
    .bento-whatif {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    .bento-whatif-icon {
      display: flex;
      align-items: flex-end;
      gap: 5px;
      height: 50px;
    }

    .bento-bar {
      width: 14px;
      border-radius: 4px 4px 0 0;
      background: var(--c, var(--accent));
      height: 0;
      animation: barUp 1s var(--ease) both;
    }

    .bento-bar:nth-child(1) {
      animation-delay: .5s;
    }

    .bento-bar:nth-child(2) {
      animation-delay: .65s;
    }

    .bento-bar:nth-child(3) {
      animation-delay: .8s;
    }

    .bento-bar:nth-child(4) {
      animation-delay: .95s;
    }

    @keyframes barUp {
      to {
        height: var(--h, 60%);
      }
    }

    /* CV Builder card */
    .bento-cv {
      display: flex;
      gap: 1.25rem;
      align-items: center;
    }

    .bento-cv-preview {
      width: 60px;
      flex-shrink: 0;
      background: var(--ink06);
      border: 1px solid var(--ink10);
      border-radius: 6px;
      padding: 8px;
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .bento-cv-line {
      height: 3px;
      border-radius: 2px;
      background: var(--ink10);
      animation: lineSlide .8s var(--ease) both;
    }

    .bento-cv-line.w80 {
      width: 80%;
      animation-delay: .6s;
    }

    .bento-cv-line.w60 {
      width: 60%;
      animation-delay: .7s;
    }

    .bento-cv-line.w90 {
      width: 90%;
      animation-delay: .8s;
    }

    .bento-cv-line.w45 {
      width: 45%;
      animation-delay: .9s;
    }

    @keyframes lineSlide {
      from {
        width: 0;
        opacity: 0;
      }
    }

    .bento-cv-dots {
      display: flex;
      gap: 3px;
      margin-top: 2px;
    }

    .bento-cv-dots span {
      width: 6px;
      height: 6px;
      border-radius: 50%;
    }

    .bento-cv-right {
      flex: 1;
      min-width: 0;
    }

    .bento-formats {
      display: flex;
      gap: .4rem;
      margin-top: .5rem;
    }

    .bf-chip {
      font-size: .65rem;
      font-weight: 700;
      letter-spacing: .06em;
      padding: .2rem .55rem;
      border-radius: 99px;
      text-transform: uppercase;
    }

    .bf-pdf {
      background: color-mix(in srgb, #dc2626 12%, transparent);
      color: #dc2626;
      border: 1px solid color-mix(in srgb, #dc2626 20%, transparent);
    }

    .bf-docx {
      background: color-mix(in srgb, var(--accent2) 12%, transparent);
      color: var(--accent2);
      border: 1px solid color-mix(in srgb, var(--accent2) 20%, transparent);
    }

    /* ── Responsive bento ── */
    @media(max-width:500px) {
      .bento-grid {
        grid-template-columns: 1fr 1fr;
      }

      .bento-lg {
        grid-row: auto;
      }
    }


    /* Large editorial BG text */
    .hero-bg-word {
      position: absolute;
      font-family: 'Fraunces', serif;
      font-weight: 300;
      font-style: italic;
      font-size: clamp(10rem, 22vw, 22rem);
      color: transparent;
      -webkit-text-stroke: 1px color-mix(in srgb, var(--ink) 6%, transparent);
      line-height: 1;
      letter-spacing: -.05em;
      pointer-events: none;
      user-select: none;
      z-index: 0;
      top: 50%;
      transform: translateY(-50%);
      right: -4%;
    }

    /* Decorative circle */
    .hero-circle {
      position: absolute;
      width: clamp(320px, 45vw, 600px);
      height: clamp(320px, 45vw, 600px);
      border-radius: 50%;
      background: radial-gradient(circle at 35% 40%,
          color-mix(in srgb, var(--accent) 18%, transparent),
          color-mix(in srgb, var(--accent2) 12%, transparent) 45%,
          transparent 70%);
      right: -8%;
      top: 50%;
      transform: translateY(-50%);
      z-index: 0;
      pointer-events: none;
      animation: circleBreath 8s ease-in-out infinite;
    }

    @keyframes circleBreath {

      0%,
      100% {
        transform: translateY(-50%) scale(1);
      }

      50% {
        transform: translateY(-54%) scale(1.06);
      }
    }

    /* Orbital rings around circle */
    .hero-ring {
      position: absolute;
      border-radius: 50%;
      border: 1px solid;
      right: -8%;
      top: 50%;
      pointer-events: none;
      z-index: 0;
      animation: ringSpin linear infinite;
    }

    .hero-ring-1 {
      width: clamp(420px, 60vw, 740px);
      height: clamp(420px, 60vw, 740px);
      border-color: color-mix(in srgb, var(--accent) 15%, transparent);
      transform: translate(50%, -50%) rotate(0deg);
      animation-duration: 40s;
      transform-origin: center;
    }

    .hero-ring-2 {
      width: clamp(500px, 72vw, 900px);
      height: clamp(500px, 72vw, 900px);
      border-color: color-mix(in srgb, var(--accent2) 10%, transparent);
      transform: translate(50%, -50%) rotate(0deg);
      animation-duration: 60s;
      animation-direction: reverse;
    }

    @keyframes ringSpin {
      to {
        transform: translate(50%, -50%) rotate(360deg);
      }
    }

    /* Floating career pills on ring */
    .ring-pill {
      position: absolute;
      padding: .35rem .9rem;
      border-radius: var(--rx);
      font-size: .78rem;
      font-weight: 500;
      white-space: nowrap;
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid var(--ink10);
      background: color-mix(in srgb, var(--paper) 80%, transparent);
      color: var(--ink);
      animation: pillFloat ease-in-out infinite;
    }

    .rp1 {
      right: 4%;
      top: 22%;
      animation-duration: 4.5s;
      animation-delay: 0s;
    }

    .rp2 {
      right: 0%;
      top: 44%;
      animation-duration: 5.2s;
      animation-delay: -.8s;
    }

    .rp3 {
      right: 6%;
      top: 66%;
      animation-duration: 4.8s;
      animation-delay: -1.5s;
    }

    .rp4 {
      right: 22%;
      top: 10%;
      animation-duration: 6s;
      animation-delay: -2s;
    }

    .rp5 {
      right: 21%;
      top: 82%;
      animation-duration: 5s;
      animation-delay: -3s;
    }

    @keyframes pillFloat {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-10px);
      }
    }

    .hero-content {
      position: relative;
      z-index: 10;
      max-width: 700px;
    }

    /* Status tag */
    .hero-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      margin-bottom: 2.5rem;
      font-size: .78rem;
      font-weight: 500;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--accent);
      animation: fadeUp .7s var(--ease) both;
    }

    .eyebrow-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--accent3);
      animation: dotPulse 2s ease-in-out infinite;
    }

    @keyframes dotPulse {

      0%,
      100% {
        opacity: 1;
        box-shadow: 0 0 0 0 color-mix(in srgb, var(--accent3) 50%, transparent);
      }

      50% {
        opacity: .6;
        box-shadow: 0 0 0 6px transparent;
      }
    }

    /* Giant title */
    .hero-title {
      font-family: 'Fraunces', serif;
      font-size: clamp(3.2rem, 7.5vw, 7.5rem);
      font-weight: 300;
      line-height: 1.02;
      letter-spacing: -.04em;
      margin-bottom: 2rem;
      animation: fadeUp .9s var(--ease) .1s both;
    }

    .hero-title em {
      font-style: italic;
      color: var(--accent);
    }

    .hero-title strong {
      font-weight: 600;
    }

    .hero-sub {
      font-size: 1.05rem;
      color: var(--ink60);
      line-height: 1.75;
      max-width: 520px;
      margin-bottom: 3rem;
      animation: fadeUp .9s var(--ease) .2s both;
    }

    .hero-ctas {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      animation: fadeUp .9s var(--ease) .3s both;
    }

    .btn-main {
      display: inline-flex;
      align-items: center;
      gap: .65rem;
      padding: 1rem 2rem;
      border-radius: var(--r);
      background: var(--accent);
      color: #fff;
      font-family: 'DM Sans', sans-serif;
      font-size: 1rem;
      font-weight: 500;
      border: none;
      cursor: pointer;
      box-shadow: 0 8px 32px color-mix(in srgb, var(--accent) 40%, transparent), 0 2px 8px rgba(0, 0, 0, .15);
      transition: all .3s var(--ease);
      position: relative;
      overflow: hidden;
    }

    .btn-main::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255, 255, 255, .15), transparent);
      opacity: 0;
      transition: .3s;
    }

    .btn-main:hover {
      transform: translateY(-3px);
      box-shadow: 0 16px 48px color-mix(in srgb, var(--accent) 45%, transparent);
    }

    .btn-main:hover::after {
      opacity: 1;
    }

    .btn-ghost {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      padding: 1rem 2rem;
      border-radius: var(--r);
      background: transparent;
      border: 1px solid var(--ink30);
      color: var(--ink);
      font-family: 'DM Sans', sans-serif;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: all .3s;
    }

    .btn-ghost:hover {
      background: var(--ink10);
      border-color: var(--ink60);
    }

    /* Trust bar */

    .trust-stat {}

    /* ── (old orb / ring / pill styles removed — replaced by hero-mockup above) ── */

    .hero-content {
      position: relative;
      z-index: 10;
      max-width: 700px;
    }

    /* Status tag */
    .hero-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      margin-bottom: 2rem;
      padding: .4rem 1rem;
      border-radius: var(--rx);
      border: 1px solid color-mix(in srgb, var(--accent) 25%, transparent);
      background: color-mix(in srgb, var(--accent) 8%, transparent);
      font-size: .75rem;
      font-weight: 600;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--accent);
      animation: fadeUp .7s var(--ease) both;
      width: fit-content;
    }

    .eyebrow-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: var(--accent3);
      animation: dotPulse 2s ease-in-out infinite;
    }

    @keyframes dotPulse {

      0%,
      100% {
        opacity: 1;
        box-shadow: 0 0 0 0 color-mix(in srgb, var(--accent3) 50%, transparent);
      }

      50% {
        opacity: .6;
        box-shadow: 0 0 0 6px transparent;
      }
    }

    /* Giant title */
    .hero-title {
      font-family: 'Fraunces', serif;
      font-size: clamp(2.8rem, 5.5vw, 5.5rem);
      font-weight: 300;
      line-height: 1.06;
      letter-spacing: -.04em;
      margin-bottom: 1.5rem;
      animation: fadeUp .9s var(--ease) .1s both;
    }

    .hero-title em {
      font-style: italic;
      color: var(--accent);
    }

    .hero-title strong {
      font-weight: 600;
    }

    .hero-sub {
      font-size: 1rem;
      color: var(--ink60);
      line-height: 1.8;
      max-width: 480px;
      margin-bottom: 2.5rem;
      animation: fadeUp .9s var(--ease) .2s both;
    }

    .hero-ctas {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      animation: fadeUp .9s var(--ease) .3s both;
    }

    .btn-main {
      display: inline-flex;
      align-items: center;
      gap: .65rem;
      padding: .9rem 1.85rem;
      border-radius: var(--r);
      background: var(--accent);
      color: #fff;
      font-family: 'DM Sans', sans-serif;
      font-size: .95rem;
      font-weight: 500;
      border: none;
      cursor: pointer;
      box-shadow: 0 8px 32px color-mix(in srgb, var(--accent) 40%, transparent), 0 2px 8px rgba(0, 0, 0, .15);
      transition: all .3s var(--ease);
      position: relative;
      overflow: hidden;
    }

    .btn-main::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255, 255, 255, .15), transparent);
      opacity: 0;
      transition: .3s;
    }

    .btn-main:hover {
      transform: translateY(-3px);
      box-shadow: 0 16px 48px color-mix(in srgb, var(--accent) 45%, transparent);
    }

    .btn-main:hover::after {
      opacity: 1;
    }

    .btn-ghost {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      padding: .9rem 1.85rem;
      border-radius: var(--r);
      background: transparent;
      border: 1px solid var(--ink30);
      color: var(--ink);
      font-family: 'DM Sans', sans-serif;
      font-size: .95rem;
      font-weight: 500;
      cursor: pointer;
      transition: all .3s;
    }

    .btn-ghost:hover {
      background: var(--ink10);
      border-color: var(--ink60);
    }

    /* Trust bar */

    .trust-num {
      font-family: 'Fraunces', serif;
      font-size: 2rem;
      font-weight: 600;
      letter-spacing: -.04em;
      color: var(--ink);
      display: block;
      line-height: 1;
    }

    .trust-label {
      font-size: .78rem;
      color: var(--ink60);
      margin-top: .25rem;
    }

    .trust-divider {
      width: 1px;
      height: 40px;
      background: var(--ink10);
    }

    /* ── MARQUEE ── */
    .marquee-wrap {
      overflow: hidden;
      border-top: 1px solid var(--ink10);
      border-bottom: 1px solid var(--ink10);
      background: var(--cream);
      padding: .9rem 0;
      position: relative;
      z-index: 10;
    }

    .marquee-track {
      display: flex;
      gap: 3rem;
      width: max-content;
      animation: marqueeScroll 30s linear infinite;
    }

    @keyframes marqueeScroll {
      to {
        transform: translateX(-50%);
      }
    }

    .marquee-item {
      font-family: 'Fraunces', serif;
      font-style: italic;
      font-size: 1rem;
      font-weight: 300;
      color: var(--ink60);
      white-space: nowrap;
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .marquee-item::before {
      content: '✦';
      font-style: normal;
      font-size: .65rem;
      color: var(--accent);
    }

    /* ── SECTIONS ── */
    .section {
      position: relative;
      z-index: 10;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 3rem;
    }

    .section-pad {
      padding: 8rem 0;
    }

    .section-tag {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      font-size: .72rem;
      font-weight: 600;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 1.5rem;
    }

    .section-tag::before {
      content: '';
      width: 20px;
      height: 1px;
      background: var(--accent);
    }

    .section-heading {
      font-family: 'Fraunces', serif;
      font-size: clamp(2.4rem, 5vw, 4.2rem);
      font-weight: 300;
      letter-spacing: -.03em;
      line-height: 1.08;
      margin-bottom: 1.25rem;
    }

    .section-heading em {
      font-style: italic;
      color: var(--accent);
    }

    .section-body {
      font-size: 1.05rem;
      color: var(--ink60);
      line-height: 1.8;
      max-width: 520px;
    }

    /* Reveal */
    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity .8s var(--ease), transform .8s var(--ease);
    }

    .reveal.vis {
      opacity: 1;
      transform: translateY(0);
    }

    .reveal-d1 {
      transition-delay: .1s;
    }

    .reveal-d2 {
      transition-delay: .2s;
    }

    .reveal-d3 {
      transition-delay: .3s;
    }

    .reveal-d4 {
      transition-delay: .4s;
    }

    /* ── FEATURES ── */
    .features-layout {
      display: grid;
      grid-template-columns: 1fr 1.2fr;
      gap: 6rem;
      align-items: center;
    }

    .features-intro {}

    .features-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1px;
      background: var(--ink10);
      border: 1px solid var(--ink10);
      border-radius: var(--rl);
      overflow: hidden;
    }

    .feat-cell {
      background: var(--paper);
      padding: 2rem 1.75rem;
      transition: all .3s var(--ease);
      position: relative;
      overflow: hidden;
    }

    .feat-cell::before {
      content: '';
      position: absolute;
      inset: 0;
      background: color-mix(in srgb, var(--accent) 5%, transparent);
      opacity: 0;
      transition: .3s;
    }

    .feat-cell:hover {
      background: var(--cream);
    }

    .feat-cell:hover::before {
      opacity: 1;
    }

    .feat-icon {
      font-size: 1.5rem;
      margin-bottom: 1.25rem;
      width: 44px;
      height: 44px;
      background: color-mix(in srgb, var(--accent) 10%, transparent);
      color: var(--accent);
      border-radius: var(--r);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .feat-title {
      font-family: 'Fraunces', serif;
      font-size: 1.05rem;
      font-weight: 600;
      letter-spacing: -.02em;
      color: var(--ink);
      margin-bottom: .5rem;
    }

    .feat-desc {
      font-size: .88rem;
      color: var(--ink60);
      line-height: 1.65;
    }

    .feat-tag {
      margin-top: 1rem;
      font-size: .72rem;
      font-weight: 600;
      letter-spacing: .06em;
      text-transform: uppercase;
      color: var(--accent3);
    }

    /* ── PROCESS ── */
    .process-section {
      background: var(--cream);
    }

    .process-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 0;
      margin-top: 4rem;
      border: 1px solid var(--ink10);
      border-radius: var(--rl);
      overflow: hidden;
    }

    .process-step {
      padding: 2.5rem 2rem;
      border-right: 1px solid var(--ink10);
      position: relative;
      transition: .3s var(--ease);
    }

    .process-step:last-child {
      border-right: none;
    }

    .process-step:hover {
      background: var(--warm);
    }

    .ps-num {
      font-family: 'Fraunces', serif;
      font-size: 3.5rem;
      font-weight: 300;
      color: var(--ink10);
      letter-spacing: -.05em;
      line-height: 1;
      margin-bottom: 1.5rem;
      display: block;
      transition: .3s;
    }

    .process-step:hover .ps-num {
      color: var(--accent);
      opacity: .3;
    }

    .ps-icon {
      width: 44px;
      height: 44px;
      border-radius: var(--r);
      background: var(--ink);
      color: var(--paper);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      margin-bottom: 1.25rem;
    }

    .ps-title {
      font-family: 'Fraunces', serif;
      font-size: 1.1rem;
      font-weight: 600;
      letter-spacing: -.02em;
      margin-bottom: .6rem;
    }

    .ps-desc {
      font-size: .88rem;
      color: var(--ink60);
      line-height: 1.65;
    }

    .ps-time {
      margin-top: 1rem;
      font-size: .75rem;
      font-weight: 600;
      color: var(--accent);
      letter-spacing: .04em;
    }

    /* ── TESTIMONIALS ── */
    .testi-section {}

    .testi-featured {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
      margin-top: 4rem;
    }

    .testi-big {
      background: var(--accent);
      color: #fff;
      border-radius: var(--rl);
      padding: 3rem;
      grid-row: span 2;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .testi-quote {
      font-family: 'Fraunces', serif;
      font-size: 1.5rem;
      font-weight: 300;
      font-style: italic;
      line-height: 1.6;
      letter-spacing: -.02em;
      flex: 1;
    }

    .testi-quote em {
      font-style: normal;
      font-weight: 600;
    }

    .testi-author {
      margin-top: 2rem;
      display: flex;
      align-items: center;
      gap: .875rem;
    }

    .testi-ava {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .25);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Fraunces', serif;
      font-size: 1rem;
      font-weight: 600;
      color: #fff;
      flex-shrink: 0;
    }

    .testi-name {
      font-size: .88rem;
      font-weight: 600;
      color: #fff;
    }

    .testi-role {
      font-size: .78rem;
      color: rgba(255, 255, 255, .65);
      margin-top: .15rem;
    }

    .testi-stars {
      font-size: .8rem;
      color: rgba(255, 255, 255, .8);
      margin-top: .2rem;
    }

    .testi-small {
      background: var(--cream);
      border: 1px solid var(--ink10);
      border-radius: var(--rl);
      padding: 2rem;
      transition: .3s var(--ease);
    }

    .testi-small:hover {
      border-color: var(--accent);
      background: var(--warm);
    }

    .testi-small .testi-quote {
      font-size: 1rem;
      color: var(--ink);
    }

    .testi-small .testi-name {
      color: var(--ink);
    }

    .testi-small .testi-role {
      color: var(--ink60);
    }

    .testi-small .testi-stars {
      color: var(--gold);
    }

    /* ── PRICING ── */
    .pricing-section {
      background: var(--cream);
    }

    .pricing-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
      margin-top: 4rem;
    }

    .pricing-card {
      border-radius: var(--rl);
      padding: 2.5rem 2rem;
      border: 1px solid var(--ink10);
      background: var(--paper);
      transition: .3s var(--ease);
      display: flex;
      flex-direction: column;
    }

    .pricing-card:hover {
      border-color: var(--ink30);
      transform: translateY(-4px);
      box-shadow: 0 20px 60px rgba(0, 0, 0, .08);
    }

    .pricing-card.featured {
      background: var(--ink);
      color: var(--paper);
      border-color: var(--ink);
    }

    .pricing-card.featured .pricing-plan {
      color: var(--gold);
    }

    .pricing-card.featured .pricing-period {
      color: color-mix(in srgb, var(--paper) 55%, transparent);
    }

    .pricing-card.featured .pricing-feat li {
      border-color: rgba(255, 255, 255, .1);
      color: color-mix(in srgb, var(--paper) 75%, transparent);
    }

    .pricing-card.featured .pricing-feat li::before {
      color: var(--accent3);
    }

    .pricing-popular {
      display: inline-block;
      margin-bottom: 1.5rem;
      padding: .3rem .85rem;
      border-radius: var(--rx);
      background: var(--gold);
      color: var(--ink);
      font-size: .72rem;
      font-weight: 700;
      letter-spacing: .06em;
      text-transform: uppercase;
    }

    .pricing-plan {
      font-size: .75rem;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 1.25rem;
      display: block;
    }

    .pricing-price {
      font-family: 'Fraunces', serif;
      font-size: 3.5rem;
      font-weight: 600;
      letter-spacing: -.05em;
      line-height: 1;
      display: flex;
      align-items: baseline;
      gap: .35rem;
    }

    .pricing-price small {
      font-size: 1.25rem;
      font-weight: 300;
    }

    .pricing-period {
      font-size: .85rem;
      color: var(--ink60);
      margin-top: .5rem;
      margin-bottom: 1.5rem;
    }

    .pricing-desc {
      font-size: .9rem;
      color: var(--ink60);
      line-height: 1.65;
      margin-bottom: 2rem;
      flex: 1;
    }

    .pricing-feat {
      list-style: none;
      margin-bottom: 2rem;
    }

    .pricing-feat li {
      font-size: .88rem;
      color: var(--ink60);
      padding: .5rem 0;
      border-bottom: 1px solid var(--ink10);
      display: flex;
      align-items: center;
      gap: .5rem;
    }

    .pricing-feat li::before {
      content: '✓';
      color: var(--accent3);
      font-weight: 700;
      flex-shrink: 0;
    }

    .btn-price {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .5rem;
      width: 100%;
      padding: .875rem 1.5rem;
      border-radius: var(--r);
      font-family: 'DM Sans', sans-serif;
      font-size: .95rem;
      font-weight: 500;
      cursor: pointer;
      transition: all .3s var(--ease);
      text-decoration: none;
    }

    .btn-price-outline {
      background: transparent;
      border: 1px solid var(--ink30);
      color: var(--ink);
    }

    .btn-price-outline:hover {
      background: var(--ink10);
    }

    .btn-price-fill {
      background: var(--accent);
      color: #fff;
      border: 1px solid var(--accent);
      box-shadow: 0 6px 24px color-mix(in srgb, var(--accent) 35%, transparent);
    }

    .btn-price-fill:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 40px color-mix(in srgb, var(--accent) 45%, transparent);
    }

    .btn-price-light {
      background: rgba(255, 255, 255, .1);
      color: var(--paper);
      border: 1px solid rgba(255, 255, 255, .2);
    }

    .btn-price-light:hover {
      background: rgba(255, 255, 255, .18);
    }

    /* ── CTA FINALE ── */
    .cta-section {
      position: relative;
      overflow: hidden;
      padding: 8rem 0;
    }

    .cta-bg {
      position: absolute;
      inset: 0;
      background: var(--accent2);
    }

    .cta-grain {
      position: absolute;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.06'/%3E%3C/svg%3E");
      opacity: .8;
    }

    .cta-deco {
      position: absolute;
      width: 600px;
      height: 600px;
      border-radius: 50%;
      background: radial-gradient(circle, color-mix(in srgb, var(--accent) 35%, transparent), transparent 70%);
      right: -15%;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
    }

    .cta-inner {
      position: relative;
      z-index: 10;
      text-align: center;
      max-width: 680px;
      margin: 0 auto;
      padding: 0 2rem;
    }

    .cta-label {
      display: inline-block;
      margin-bottom: 2rem;
      padding: .35rem 1rem;
      border-radius: var(--rx);
      background: rgba(255, 255, 255, .1);
      border: 1px solid rgba(255, 255, 255, .2);
      font-size: .75rem;
      font-weight: 600;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, .75);
    }

    .cta-title {
      font-family: 'Fraunces', serif;
      font-size: clamp(2.8rem, 6vw, 5rem);
      font-weight: 300;
      line-height: 1.06;
      letter-spacing: -.04em;
      color: #fff;
      margin-bottom: 1.5rem;
    }

    .cta-title em {
      font-style: italic;
    }

    .cta-sub {
      font-size: 1.05rem;
      color: rgba(255, 255, 255, .65);
      line-height: 1.75;
      margin-bottom: 3rem;
    }

    .btn-cta {
      display: inline-flex;
      align-items: center;
      gap: .65rem;
      padding: 1.1rem 2.5rem;
      border-radius: var(--r);
      background: #fff;
      color: var(--accent2);
      font-family: 'DM Sans', sans-serif;
      font-size: 1rem;
      font-weight: 600;
      border: none;
      cursor: pointer;
      box-shadow: 0 8px 40px rgba(0, 0, 0, .25);
      transition: all .3s var(--ease);
    }

    .btn-cta:hover {
      transform: translateY(-3px);
      box-shadow: 0 20px 60px rgba(0, 0, 0, .3);
    }

    .cta-trust-row {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 2rem;
      margin-top: 2rem;
      flex-wrap: wrap;
    }

    .cta-trust-item {
      display: flex;
      align-items: center;
      gap: .4rem;
      font-size: .82rem;
      color: rgba(255, 255, 255, .55);
    }

    .cta-trust-item span {
      color: rgba(255, 255, 255, .8);
      font-weight: 500;
    }

    /* ── FOOTER ── */
    footer {
      background: var(--cream);
      border-top: 1px solid var(--ink10);
      position: relative;
      z-index: 10;
    }

    .footer-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 4rem 3rem 2.5rem;
    }

    .footer-top {
      display: grid;
      grid-template-columns: 1.6fr 1fr 1fr 1fr;
      gap: 3rem;
      padding-bottom: 3rem;
      border-bottom: 1px solid var(--ink10);
    }

    .footer-brand-desc {
      font-size: .88rem;
      color: var(--ink60);
      line-height: 1.7;
      margin: .875rem 0 1.5rem;
      max-width: 260px;
    }

    .footer-socials {
      display: flex;
      gap: .5rem;
    }

    .soc {
      width: 36px;
      height: 36px;
      border-radius: var(--r);
      background: var(--ink10);
      border: none;
      cursor: pointer;
      font-size: .8rem;
      font-weight: 700;
      color: var(--ink60);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: .25s;
    }

    .soc:hover {
      background: var(--ink);
      color: var(--paper);
    }

    .footer-col-title {
      font-size: .72rem;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--ink);
      margin-bottom: 1.25rem;
    }

    .footer-col-links {
      list-style: none;
    }

    .footer-col-links li {
      margin-bottom: .55rem;
    }

    .footer-col-links a {
      font-size: .88rem;
      color: var(--ink60);
      transition: .2s;
    }

    .footer-col-links a:hover {
      color: var(--accent);
    }

    .footer-bottom {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-top: 2rem;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .footer-copy {
      font-size: .82rem;
      color: var(--ink30);
    }

    .footer-made {
      font-size: .82rem;
      color: var(--ink30);
      display: flex;
      align-items: center;
      gap: .35rem;
    }

    .footer-made strong {
      color: var(--accent);
    }

    /* ── STATS BAR ── */
    .stats-bar {
      background: var(--ink);
      color: var(--paper);
      position: relative;
      z-index: 10;
    }

    .stats-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 3rem;
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 2rem;
      text-align: center;
    }

    .stat-n {
      font-family: 'Fraunces', serif;
      font-size: 3rem;
      font-weight: 600;
      letter-spacing: -.05em;
      line-height: 1;
      color: var(--paper);
      display: block;
    }

    .stat-l {
      font-size: .85rem;
      color: color-mix(in srgb, var(--paper) 55%, transparent);
      margin-top: .4rem;
    }

    /* ── MISC ANIMATIONS ── */
    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(24px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* ── RESPONSIVE ── */
    @media(max-width:1100px) {
      .hero-inner {
        grid-template-columns: 1fr;
        gap: 3rem;
      }

      .hero-right {
        order: -1;
      }

      /* mockup above text on tablet */
      .hero-mockup {
        max-width: 460px;
      }

      .features-layout {
        grid-template-columns: 1fr;
      }

      .features-intro {
        max-width: 600px;
      }

      .process-grid {
        grid-template-columns: 1fr 1fr;
      }

      .process-step:nth-child(2) {
        border-right: none;
      }

      .process-step:nth-child(3) {
        border-top: 1px solid var(--ink10);
      }

      .process-step:nth-child(4) {
        border-top: 1px solid var(--ink10);
      }

      .testi-featured {
        grid-template-columns: 1fr;
      }

      .testi-big {
        grid-row: auto;
      }

      .pricing-grid {
        grid-template-columns: 1fr;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
      }
    }

    @media(max-width:768px) {
      nav {
        padding: 1rem 1.5rem;
      }

      nav.scrolled {
        padding: .75rem 1.5rem;
      }

      .nav-links,
      .nav-right .btn-nav:first-child {
        display: none;
      }

      .hero {
        padding: 6rem 1.5rem 4rem;
      }

      .hero-inner {
        gap: 2.5rem;
      }

      .hero-right {
        display: none;
      }

      /* hide mockup on small phones */
      .hero-title {
        font-size: clamp(2.4rem, 9vw, 3.5rem);
      }

      .container {
        padding: 0 1.5rem;
      }

      .section-pad {
        padding: 5rem 0;
      }

      .stats-inner {
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
      }

      .features-grid {
        grid-template-columns: 1fr;
      }

      .footer-top {
        grid-template-columns: 1fr 1fr;
      }

      .process-grid {
        grid-template-columns: 1fr;
        border-radius: 0;
      }

      .process-step {
        border-right: none;
        border-bottom: 1px solid var(--ink10);
      }

      .trust-divider {
        display: none;
      }
    }

    /* ── CONTACT ── */
    .contact-section {
      background: var(--paper);
      position: relative;
      overflow: hidden;
    }

    .contact-grid {
      display: grid;
      grid-template-columns: 1fr 1.2fr;
      gap: 5rem;
      align-items: start;
      margin-top: 4rem;
    }

    .contact-info-card {
      background: var(--cream);
      padding: 3rem;
      border-radius: var(--rl);
      border: 1px solid var(--ink10);
    }

    .contact-method {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .cm-icon {
      width: 44px;
      height: 44px;
      border-radius: var(--r);
      background: var(--accent);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      flex-shrink: 0;
    }

    .cm-text h4 {
      font-family: var(--font-serif);
      font-size: 1.1rem;
      margin-bottom: .25rem;
    }

    .cm-text p {
      font-size: .9rem;
      color: var(--ink60);
    }

    .contact-form {
      display: grid;
      gap: 1.5rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: .5rem;
    }

    .form-group label {
      font-size: .85rem;
      font-weight: 600;
      color: var(--ink60);
    }

    .form-control {
      background: var(--cream);
      border: 1px solid var(--ink10);
      border-radius: var(--r);
      padding: .85rem 1rem;
      font-family: inherit;
      font-size: .95rem;
      color: var(--ink);
      transition: .3s;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 4px color-mix(in srgb, var(--accent) 10%, transparent);
    }

    .error-msg {
      font-size: .75rem;
      color: #ef4444;
      margin-top: .25rem;
      font-weight: 500;
    }

    .success-banner {
      background: var(--accent3);
      color: #fff;
      padding: 1.25rem;
      border-radius: var(--r);
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      gap: .75rem;
      font-weight: 500;
      animation: fadeUp .5s var(--ease);
    }

    @media(max-width: 1024px) {
      .contact-grid {
        grid-template-columns: 1fr;
        gap: 3rem;
      }
    }
  </style>
</head>

<body>

  <!-- ═══ NAV ═══ -->
  <nav id="nav">
    <a href="#" class="nav-logo">
      <div class="logo-mark">
        <img src="{{ asset('final.png') }}" alt="CapAvenir Logo">
      </div>
      <span class="logo-name">Cap<span>Avenir</span></span>
    </a>
    <ul class="nav-links">
      <li><a href="#features">Fonctionnalités</a></li>
      <li><a href="#process">Comment ça marche</a></li>
      <li><a href="#testimonials">Témoignages</a></li>
      <li><a href="#pricing">Tarifs</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
    <div class="nav-right">
      <button id="themeBtn" title="Changer de thème"><i class="bi bi-sun-fill"></i></button>
      <a href="/login" class="btn-nav">Se connecter</a>
      <a href="/register" class="btn-nav btn-nav-fill">Commencer → </a>
    </div>
  </nav>

  <!-- ═══ HERO ═══ -->
  <section class="hero">
    <div class="hero-inner">

      <!-- ── LEFT: Copy ── -->
      <div class="hero-left">
        <div class="hero-eyebrow">
          <div class="logo-mark" style="height: 18px; width: 18px; margin-right: -4px;">
            <img src="{{ asset('final.png') }}" alt="">
          </div>
          Orientation IA · Tunisie 2026
        </div>

        <h1 class="hero-title">
          Trouve la voie<br>
          qui te <em>ressemble</em><br>
          <strong>vraiment.</strong>
        </h1>

        <p class="hero-sub">
          CapAvenir analyse tes aptitudes, valeurs et ambitions grâce à l'IA pour te proposer un parcours universitaire
          sur mesure — adapté au système éducatif tunisien.
        </p>

        <div class="hero-ctas">
          <a href="/register" class="btn-main">
            Découvrir mon orientation <span>→</span>
          </a>
          <a href="#process" class="btn-ghost">
            Comment ça marche
          </a>
        </div>


      </div>

      <!-- ── RIGHT: Bento Grid Feature Showcase ── -->
      <div class="hero-right">
        <div class="bento-grid">

          <!-- Card 1: RIASEC Radar (large) -->
          <div class="bento-card bento-lg bento-riasec">
            <div class="bento-glow bento-glow-accent"></div>
            <div class="bento-tag">Test RIASEC</div>
            <svg viewBox="0 0 200 180" class="bento-radar">
              <polygon points="100,15 160,45 160,125 100,155 40,125 40,45" fill="none" stroke="var(--ink10)"
                stroke-width=".5" />
              <polygon points="100,35 148,55 148,115 100,135 52,115 52,55" fill="none" stroke="var(--ink10)"
                stroke-width=".5" />
              <polygon points="100,55 136,67 136,103 100,115 64,103 64,67" fill="none" stroke="var(--ink10)"
                stroke-width=".5" />
              <polygon class="bento-shape" points="100,20 155,48 148,122 100,145 55,110 45,50" />
              <circle cx="100" cy="20" r="4" fill="var(--accent)" />
              <circle cx="155" cy="48" r="4" fill="var(--accent)" />
              <circle cx="148" cy="122" r="3" fill="var(--accent2)" />
              <circle cx="100" cy="145" r="2.5" fill="var(--ink30)" />
              <circle cx="55" cy="110" r="3" fill="var(--accent2)" />
              <circle cx="45" cy="50" r="2.5" fill="var(--ink30)" />
            </svg>
            <div class="bento-radar-dims">
              <span style="top:0;left:50%;transform:translateX(-50%)">R</span>
              <span style="top:20%;right:0">I</span>
              <span style="bottom:18%;right:0">A</span>
              <span style="bottom:0;left:50%;transform:translateX(-50%)">S</span>
              <span style="bottom:18%;left:0">E</span>
              <span style="top:20%;left:0">C</span>
            </div>
            <div class="bento-label">Découvre ton profil<br><strong>psychométrique</strong></div>
          </div>

          <!-- Card 2: AI Score (small top-right) -->
          <div class="bento-card bento-sm bento-score">
            <div class="bento-glow bento-glow-gold"></div>
            <div class="bento-score-circle">
              <svg viewBox="0 0 80 80">
                <circle cx="40" cy="40" r="34" fill="none" stroke="var(--ink10)" stroke-width="3" />
                <circle cx="40" cy="40" r="34" fill="none" stroke="var(--accent)" stroke-width="3.5"
                  stroke-dasharray="214" stroke-dashoffset="40" stroke-linecap="round" class="bento-ring-anim" />
              </svg>
              <div class="bento-score-val">92<small>%</small></div>
            </div>
            <div class="bento-tag" style="margin-top:.5rem;">Score IA</div>
          </div>

          <!-- Card 3: What-If (small bottom-right) -->
          <div class="bento-card bento-sm bento-whatif">
            <div class="bento-glow bento-glow-marine"></div>
            <div class="bento-whatif-icon">
              <div class="bento-bar" style="--h:70%;--c:var(--accent)"></div>
              <div class="bento-bar" style="--h:50%;--c:var(--accent2)"></div>
              <div class="bento-bar" style="--h:85%;--c:var(--accent3)"></div>
              <div class="bento-bar" style="--h:40%;--c:var(--gold)"></div>
            </div>
            <div class="bento-tag" style="margin-top:.5rem;">Simulateur</div>
          </div>

          <!-- Card 4: CV Builder (wide bottom) -->
          <div class="bento-card bento-wide bento-cv">
            <div class="bento-glow bento-glow-sage"></div>
            <div class="bento-cv-preview">
              <div class="bento-cv-line w80"></div>
              <div class="bento-cv-line w60"></div>
              <div class="bento-cv-line w90"></div>
              <div class="bento-cv-line w45"></div>
              <div class="bento-cv-dots">
                <span style="background:var(--accent)"></span>
                <span style="background:var(--accent2)"></span>
                <span style="background:var(--accent3)"></span>
              </div>
            </div>
            <div class="bento-cv-right">
              <div class="bento-tag">CV Builder</div>
              <div class="bento-label">Génère ton CV<br><strong>en 1 clic</strong></div>
              <div class="bento-formats">
                <span class="bf-chip bf-pdf">PDF</span>
                <span class="bf-chip bf-docx">DOCX</span>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>

  <!-- ═══ MARQUEE ═══ -->
  <div class="marquee-wrap">
    <div class="marquee-track" id="marquee">
      <span class="marquee-item">INSAT Tunis</span>
      <span class="marquee-item">ENIT</span>
      <span class="marquee-item">Faculté de Médecine</span>
      <span class="marquee-item">IHEC Carthage</span>
      <span class="marquee-item">ISG Tunis</span>
      <span class="marquee-item">ENI Sfax</span>
      <span class="marquee-item">FSS Sfax</span>
      <span class="marquee-item">Université de Tunis El Manar</span>
      <span class="marquee-item">Universités privées</span>
      <span class="marquee-item">Lycées pilotes</span>
      <span class="marquee-item">Bac Math</span>
      <span class="marquee-item">Bac Sciences</span>
      <span class="marquee-item">INSAT Tunis</span>
      <span class="marquee-item">ENIT</span>
      <span class="marquee-item">Faculté de Médecine</span>
      <span class="marquee-item">IHEC Carthage</span>
      <span class="marquee-item">ISG Tunis</span>
      <span class="marquee-item">ENI Sfax</span>
      <span class="marquee-item">FSS Sfax</span>
      <span class="marquee-item">Université de Tunis El Manar</span>
      <span class="marquee-item">Universités privées</span>
      <span class="marquee-item">Lycées pilotes</span>
      <span class="marquee-item">Bac Math</span>
      <span class="marquee-item">Bac Sciences</span>
    </div>
  </div>

  <!-- ═══ STATS ═══ -->
  <div class="stats-bar">
    <div class="stats-inner">
      <div class="reveal">
        <span class="stat-n" data-count="12400">0</span>
        <p class="stat-l">Étudiants orientés avec succès</p>
      </div>
      <div class="reveal reveal-d1">
        <span class="stat-n" data-count="94">0</span>
        <p class="stat-l">% de satisfaction moyenne</p>
      </div>
      <div class="reveal reveal-d2">
        <span class="stat-n" data-count="87">0</span>
        <p class="stat-l">% rapportent plus de clarté</p>
      </div>
      <div class="reveal reveal-d3">
        <span class="stat-n">3×</span>
        <p class="stat-l">Plus efficace pour les conseillers</p>
      </div>
    </div>
  </div>

  <!-- ═══ FEATURES ═══ -->
  <section id="features" class="section section-pad">
    <div class="container">
      <div class="features-layout">
        <div class="features-intro reveal">
          <p class="section-tag">Fonctionnalités</p>
          <h2 class="section-heading">L'orientation comme elle devrait <em>être</em></h2>
          <p class="section-body">Fini les QCM génériques. CapAvenir utilise une IA conversationnelle pour te comprendre
            vraiment — aptitudes, valeurs, style d'apprentissage — et te guider vers les formations qui font sens pour
            toi.</p>
          <a href="/register" class="btn-main" style="margin-top:2.5rem;display:inline-flex;">Essayer gratuitement →</a>
        </div>

        <div class="features-grid reveal reveal-d2">
          <div class="feat-cell">
            <div class="feat-icon"><i class="bi bi-cpu"></i></div>
            <h3 class="feat-title">Test IA adaptatif</h3>
            <p class="feat-desc">Quiz intelligent qui s'adapte à tes réponses. Jamais ennuyeux, toujours révélateur.</p>
            <p class="feat-tag">↑ 87% de clarté rapportée</p>
          </div>
          <div class="feat-cell">
            <div class="feat-icon"><i class="bi bi-person-bounding-box"></i></div>
            <h3 class="feat-title">Profil personnalisé</h3>
            <p class="feat-desc">Ton avatar IA évolue avec toi. Aptitudes, motivations, valeurs, style d'apprentissage.
            </p>
            <p class="feat-tag">✦ Mis à jour à chaque session</p>
          </div>
          <div class="feat-cell">
            <div class="feat-icon"><i class="bi bi-bullseye"></i></div>
            <h3 class="feat-title">Matching ultra-précis</h3>
            <p class="feat-desc">+90% de compatibilité avec les formations. Algorithme entraîné sur le marché tunisien.
            </p>
            <p class="feat-tag">→ Score de compatibilité vérifié</p>
          </div>
          <div class="feat-cell">
            <div class="feat-icon"><i class="bi bi-compass"></i></div>
            <h3 class="feat-title">Simulateur de vie</h3>
            <p class="feat-desc">Visualise ton futur selon chaque filière : emplois, salaires, évolution de carrière.
            </p>
            <p class="feat-tag">◎ Décision éclairée en 1 séance</p>
          </div>
          <div class="feat-cell">
            <div class="feat-icon"><i class="bi bi-people"></i></div>
            <h3 class="feat-title">Coaching humain + IA</h3>
            <p class="feat-desc">Conseillers certifiés + chatbot IA 24h/7j. L'humain et la tech, jamais l'un sans
              l'autre.</p>
            <p class="feat-tag">⚡ Réponse en moins de 2 min</p>
          </div>
          <div class="feat-cell">
            <div class="feat-icon"><i class="bi bi-map"></i></div>
            <h3 class="feat-title">100% Tunisie</h3>
            <p class="feat-desc">INSAT, ENIT, FSS, IHEC, universités privées, concours nationaux — tout est intégré.</p>
            <p class="feat-tag">✓ Système éducatif tunisien intégré</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ PROCESS ═══ -->
  <section id="process" class="section process-section">
    <div class="container">
      <div class="reveal">
        <p class="section-tag">Comment ça marche</p>
        <h2 class="section-heading">De zéro à une vision claire — <em>en 20 minutes</em></h2>
      </div>
      <div class="process-grid">
        <div class="process-step reveal reveal-d1">
          <span class="ps-num">01</span>
          <div class="ps-icon"><i class="bi bi-person-fill"></i></div>
          <h3 class="ps-title">Crée ton compte</h3>
          <p class="ps-desc">Inscription en 30 secondes. Aucune carte bancaire. Aucun engagement.</p>
          <p class="ps-time"><i class="bi bi-stopwatch"></i> 30 secondes</p>
        </div>
        <div class="process-step reveal reveal-d2">
          <span class="ps-num">02</span>
          <div class="ps-icon"><i class="bi bi-cpu-fill"></i></div>
          <h3 class="ps-title">Passe le test intelligent</h3>
          <p class="ps-desc">Quiz adaptatif IA sur tes aptitudes, valeurs et personnalité. Engageant, pas ennuyeux.</p>
          <p class="ps-time"><i class="bi bi-stopwatch"></i> 12 minutes</p>
        </div>
        <div class="process-step reveal reveal-d3">
          <span class="ps-num">03</span>
          <div class="ps-icon"><i class="bi bi-bar-chart-fill"></i></div>
          <h3 class="ps-title">Découvre ton profil IA</h3>
          <p class="ps-desc">Rapport complet, formations recommandées, scores de compatibilité détaillés.</p>
          <p class="ps-time"><i class="bi bi-stopwatch"></i> Résultats instantanés</p>
        </div>
        <div class="process-step reveal reveal-d4">
          <span class="ps-num">04</span>
          <div class="ps-icon"><i class="bi bi-rocket-takeoff-fill"></i></div>
          <h3 class="ps-title">Simule, discute, choisis</h3>
          <p class="ps-desc">Utilise le simulateur de vie, le chatbot IA ou réserve une séance avec un conseiller
            humain.</p>
          <p class="ps-time"><i class="bi bi-stopwatch"></i> À ton rythme</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ TESTIMONIALS ═══ -->
  <section id="testimonials" class="section section-pad">
    <div class="container">
      <div class="reveal">
        <p class="section-tag">Témoignages</p>
        <h2 class="section-heading">Ils ont osé changer d'avis sur leur <em>avenir</em></h2>
      </div>

      <div class="testi-featured">
        <div class="testi-big reveal reveal-d1">
          <blockquote class="testi-quote">
            « J'hésitais entre médecine et informatique. L'IA m'a ouvert les yeux sur la <em>data science</em>.
            Aujourd'hui je suis à l'INSAT et je me sens enfin à ma place. »
          </blockquote>
          <div class="testi-author">
            <div class="testi-ava">E</div>
            <div>
              <div class="testi-name">Esraa Mansouri, 19 ans</div>
              <div class="testi-role">Data Science · INSAT Tunis</div>
              <div class="testi-stars">★★★★★</div>
            </div>
          </div>
        </div>

        <div class="testi-small reveal reveal-d2">
          <blockquote class="testi-quote">
            « En tant que conseillère, je gagne <em>3× plus de temps</em> pour vraiment accompagner mes élèves. Le
            rapport IA est bluffant de précision. »
          </blockquote>
          <div class="testi-author" style="margin-top:1.5rem;display:flex;align-items:center;gap:.75rem;">
            <div class="testi-ava" style="background:var(--ink10);color:var(--ink);">S</div>
            <div>
              <div class="testi-name">Sarah Ben Amor</div>
              <div class="testi-role">Conseillère · Lycée Pilote</div>
              <div class="testi-stars">★★★★★</div>
            </div>
          </div>
        </div>

        <div class="testi-small reveal reveal-d3">
          <blockquote class="testi-quote">
            « Le simulateur de vie m'a montré <em>concrètement les débouchés</em> de chaque filière. Je n'aurais jamais
            pensé à l'architecture avant ça. »
          </blockquote>
          <div class="testi-author" style="margin-top:1.5rem;display:flex;align-items:center;gap:.75rem;">
            <div class="testi-ava" style="background:var(--ink10);color:var(--ink);">Y</div>
            <div>
              <div class="testi-name">Youssef Chaabane, 20 ans</div>
              <div class="testi-role">Architecture · Tunis El Manar</div>
              <div class="testi-stars">★★★★★</div>
            </div>
          </div>
        </div>

        <div class="testi-small reveal reveal-d2">
          <blockquote class="testi-quote">
            « Mon fils voulait partir à l'étranger sans savoir pourquoi. Après CapAvenir, il a choisi l'<em>ENIT avec
              conviction</em>. »
          </blockquote>
          <div class="testi-author" style="margin-top:1.5rem;display:flex;align-items:center;gap:.75rem;">
            <div class="testi-ava" style="background:var(--ink10);color:var(--ink);">K</div>
            <div>
              <div class="testi-name">Kamel Trabelsi</div>
              <div class="testi-role">Parent d'un étudiant · Sousse</div>
              <div class="testi-stars">★★★★★</div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ═══ PRICING ═══ -->
  <section id="pricing" class="section pricing-section section-pad">
    <div class="container">
      <div class="reveal" style="text-align:center;max-width:560px;margin:0 auto;">
        <p class="section-tag" style="justify-content:center;">Tarifs</p>
        <h2 class="section-heading">Gratuit pour commencer, <em>premium</em> pour aller plus loin</h2>
      </div>

      <div class="pricing-grid">
        <div class="pricing-card reveal reveal-d1">
          <span class="pricing-plan">Free</span>
          <div class="pricing-price"><small>TND</small>0</div>
          <p class="pricing-period">Pour toujours · Sans engagement</p>
          <p class="pricing-desc">Commence à explorer ton potentiel dès maintenant, sans aucune carte bancaire.</p>
          <ul class="pricing-feat">
            <li>Test IA adaptatif (12 min)</li>
            <li>Profil personnalisé complet</li>
            <li>3 recommandations de formation</li>
            <li>Accès à la communauté</li>
            <li>1 session chatbot / mois</li>
          </ul>
          <a href="/register" class="btn-price btn-price-outline">Commencer gratuitement</a>
        </div>

        <div class="pricing-card featured reveal reveal-d2">
          <div class="pricing-popular"><i class="bi bi-star-fill"></i> Le plus populaire</div>
          <span class="pricing-plan">Premium Étudiant</span>
          <div class="pricing-price"><small>TND</small>39</div>
          <p class="pricing-period">par mois · Annuler à tout moment</p>
          <p class="pricing-desc" style="color:color-mix(in srgb,var(--paper) 65%,transparent);">Tout ce qu'il faut pour
            décider avec confiance et sérénité.</p>
          <ul class="pricing-feat">
            <li>Tout le plan Free inclus</li>
            <li>Recommandations illimitées</li>
            <li>Simulateur de vie complet</li>
            <li>Chatbot IA 24h/7j illimité</li>
            <li>2 séances conseiller humain</li>
            <li>Rapport PDF partageable</li>
          </ul>
          <a href="/register" class="btn-price btn-price-light">Démarrer Premium ✦</a>
        </div>

        <div class="pricing-card reveal reveal-d3">
          <span class="pricing-plan">Institutions</span>
          <div class="pricing-price" style="font-size:2.5rem;">Sur devis</div>
          <p class="pricing-period">Par établissement · Volume disponible</p>
          <p class="pricing-desc">Pour lycées, universités, cabinets d'orientation et conseillers indépendants.</p>
          <ul class="pricing-feat">
            <li>Tout le plan Premium inclus</li>
            <li>Tableau de bord conseiller</li>
            <li>Gestion multi-élèves</li>
            <li>Rapports agrégés + analytics</li>
            <li>Intégration LMS</li>
            <li>Support dédié &amp; formation</li>
          </ul>
          <a href="mailto:contact@capavenir.tn" class="btn-price btn-price-outline">Contacter l'équipe →</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ CTA FINALE ═══ -->
  <section class="cta-section reveal">
    <div class="cta-bg"></div>
    <div class="cta-grain"></div>
    <div class="cta-deco"></div>
    <div class="cta-inner">
      <div class="cta-label">Prêt à commencer ?</div>
      <h2 class="cta-title">Révèle ton <em>potentiel.</em><br>C'est gratuit.</h2>
      <p class="cta-sub">Ton avenir n'est plus une loterie. En 12 minutes, découvre le parcours fait pour toi — adapté à
        la Tunisie, propulsé par l'IA.</p>
      <a href="/register" class="btn-cta">S'inscrire maintenant →</a>
      <div class="cta-trust-row">
        <div class="cta-trust-item">✓ <span>Sans carte bancaire</span></div>
        <div class="cta-trust-item">✓ <span>Sans engagement</span></div>
        <div class="cta-trust-item">✓ <span>Résultats en 12 minutes</span></div>
      </div>
    </div>
  </section>

  <!-- ═══ CONTACT ═══ -->
  <section id="contact" class="section contact-section section-pad">
    <div class="container">
      <div class="reveal">
        <p class="section-tag">Contact</p>
        <h2 class="section-heading">Une question ?<br><em>Parlons-en.</em></h2>
      </div>

      @if(session('success'))
        <div class="success-banner reveal">
          <span>✓</span> {{ session('success') }}
        </div>
      @endif

      <div class="contact-grid">
        <div class="reveal reveal-d1">
          <div class="contact-info-card">
            <div class="contact-method">
              <div class="cm-icon"><i class="bi bi-envelope-fill"></i></div>
              <div class="cm-text">
                <h4>Email</h4>
                <p>contact@capavenir.tn</p>
              </div>
            </div>
            <div class="contact-method">
              <div class="cm-icon"><i class="bi bi-geo-alt-fill"></i></div>
              <div class="cm-text">
                <h4>Bureau</h4>
                <p>Tunis, Tunisie · Digital Nomad Hub</p>
              </div>
            </div>
            <div class="contact-method">
              <div class="cm-icon"><i class="bi bi-telephone-fill"></i></div>
              <div class="cm-text">
                <h4>Téléphone</h4>
                <p>+216 71 000 000</p>
              </div>
            </div>
          </div>
        </div>

        <div class="reveal reveal-d2">
          <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
            @csrf
            <div class="form-group">
              <label for="name">Nom complet</label>
              <input type="text" id="name" name="name" class="form-control" placeholder="Ex: Ahmed Ben Salah" required
                value="{{ old('name') }}">
              @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="ahmed@example.com" required
                value="{{ old('email') }}">
              @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="sujet">Sujet</label>
              <input type="text" id="sujet" name="sujet" class="form-control" placeholder="Demande d'information"
                required value="{{ old('sujet') }}">
              @error('sujet') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
              <label for="message">Message</label>
              <textarea id="message" name="message" class="form-control" rows="5"
                placeholder="Comment pouvons-nous vous aider ?" required>{{ old('message') }}</textarea>
              @error('message') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn-main" style="width: 100%; justify-content: center;">Envoyer le message
              <span>→</span></button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ FOOTER ═══ -->
  <footer>
    <div class="footer-inner">
      <div class="footer-top">
        <div>
          <div class="nav-logo" style="margin-bottom:1.5rem">
            <div class="logo-mark" style="height:56px; width:56px;">
              <img src="{{ asset('final.png') }}" alt="CapAvenir Logo">
            </div>
            <span class="logo-name" style="font-size: 1.5rem;">Cap<span>Avenir</span></span>
          </div>
          <p class="footer-brand-desc">La plateforme d'orientation intelligente propulsée par l'IA, conçue pour le
            système éducatif tunisien.</p>
          <div class="footer-socials">
            <button class="soc" title="Twitter / X"><i class="bi bi-twitter-x"></i></button>
            <button class="soc" title="LinkedIn"><i class="bi bi-linkedin"></i></button>
            <button class="soc" title="Facebook"><i class="bi bi-facebook"></i></button>
            <button class="soc" title="YouTube"><i class="bi bi-youtube"></i></button>
          </div>
        </div>
        <div>
          <p class="footer-col-title">Plateforme</p>
          <ul class="footer-col-links">
            <li><a href="#">Pour les étudiants</a></li>
            <li><a href="#">Pour les parents</a></li>
            <li><a href="#">Pour les conseillers</a></li>
            <li><a href="#">Pour les écoles</a></li>
            <li><a href="#pricing">Tarifs</a></li>
          </ul>
        </div>
        <div>
          <p class="footer-col-title">Ressources</p>
          <ul class="footer-col-links">
            <li><a href="#">Blog orientation</a></li>
            <li><a href="#">Guide des formations</a></li>
            <li><a href="#">Universités tunisiennes</a></li>
            <li><a href="#">Débouchés & emplois</a></li>
            <li><a href="#">FAQ</a></li>
          </ul>
        </div>
        <div>
          <p class="footer-col-title">Légal & Contact</p>
          <ul class="footer-col-links">
            <li><a href="#">À propos</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Confidentialité</a></li>
            <li><a href="#">Conditions d'utilisation</a></li>
            <li><a href="#">Accessibilité</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <span class="footer-copy">© 2026 CapAvenir · Tous droits réservés · Tunis, Tunisie</span>
        <span class="footer-made">Made with <strong>IA</strong> <i class="bi bi-heart-fill" style="color: #dc2626;"></i>
          in Tunisia <i class="bi bi-globe-americas" style="color: var(--accent);"></i></span>
      </div>
    </div>
  </footer>

  <script>
    // ── NAVBAR ──
    const nav = document.getElementById('nav');
    window.addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 60), { passive: true });

    // ── THEME ──
    const themeBtn = document.getElementById('themeBtn');
    let dark = false;
    themeBtn.addEventListener('click', () => {
      dark = !dark;
      document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
      themeBtn.innerHTML = dark ? '<i class="bi bi-moon-fill"></i>' : '<i class="bi bi-sun-fill"></i>';
    });

    // ── REVEAL ──
    const revEls = document.querySelectorAll('.reveal');
    const revObs = new IntersectionObserver(entries => {
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); revObs.unobserve(e.target); } });
    }, { threshold: .1, rootMargin: '0px 0px -50px 0px' });
    revEls.forEach(el => revObs.observe(el));

    // ── COUNTERS ──
    const counters = document.querySelectorAll('[data-count]');
    const cntObs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        const el = e.target;
        const target = +el.dataset.count;
        const dur = 2000, start = performance.now();
        const isPercent = target < 101;
        const anim = now => {
          const t = Math.min((now - start) / dur, 1);
          const eased = 1 - Math.pow(1 - t, 4);
          const val = Math.round(eased * target);
          el.textContent = target >= 1000 ? val.toLocaleString('fr') + (t < 1 ? '' : '+') : val + (isPercent ? '%' : '');
          if (t < 1) requestAnimationFrame(anim);
        };
        requestAnimationFrame(anim);
        cntObs.unobserve(el);
      });
    }, { threshold: .5 });
    counters.forEach(c => cntObs.observe(c));

    // ── SMOOTH SCROLL ──
    document.querySelectorAll('a[href^="#"]').forEach(a => {
      a.addEventListener('click', e => {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
      });
    });
  </script>
</body>

</html>