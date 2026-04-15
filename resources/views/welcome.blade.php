<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CapAvenir — Trouve ta voie, construis ton avenir</title>
<meta name="description" content="Plateforme d'orientation intelligente propulsée par l'IA. Découvre ton profil unique et construis ton avenir.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,600;1,9..144,300;1,9..144,400&display=swap" rel="stylesheet">
<style>
/* ── TOKENS ── */
:root {
  --ink: #0b0c10;
  --paper: #f7f5f0;
  --cream: #ede9e1;
  --warm: #e8e1d4;
  --accent: #d4622a;
  --accent2: #1a4f6e;
  --accent3: #4a7c59;
  --gold: #c8973a;
  --ink60: rgba(11,12,16,.6);
  --ink30: rgba(11,12,16,.3);
  --ink10: rgba(11,12,16,.1);
  --r: 6px;
  --rl: 16px;
  --rx: 999px;
  --ease: cubic-bezier(.16,1,.3,1);
}
[data-theme="dark"] {
  --ink: #f0ede6;
  --paper: #10100d;
  --cream: #18170f;
  --warm: #1f1e14;
  --ink60: rgba(240,237,230,.6);
  --ink30: rgba(240,237,230,.3);
  --ink10: rgba(240,237,230,.08);
}

/* ── RESET ── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;font-size:16px}
body{font-family:'DM Sans',sans-serif;background:var(--paper);color:var(--ink);overflow-x:hidden;line-height:1.6;transition:background .4s,color .4s}
a{color:inherit;text-decoration:none}
img{max-width:100%;display:block}

/* ── NOISE TEXTURE ── */
body::before{
  content:'';position:fixed;inset:0;z-index:0;pointer-events:none;
  background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.04'/%3E%3C/svg%3E");
  opacity:.5;
}

/* ── NAV ── */
nav{
  position:fixed;top:0;left:0;right:0;z-index:900;
  display:flex;align-items:center;justify-content:space-between;
  padding:1.25rem 3rem;
  transition:all .4s var(--ease);
}
nav.scrolled{
  background:color-mix(in srgb, var(--paper) 85%, transparent);
  backdrop-filter:blur(20px);
  -webkit-backdrop-filter:blur(20px);
  border-bottom:1px solid var(--ink10);
  padding:.9rem 3rem;
}
.nav-logo{
  display:flex;align-items:center;gap:.6rem;
}
.logo-mark{
  width:36px;height:36px;border-radius:8px;
  background:var(--accent);
  display:flex;align-items:center;justify-content:center;
  font-family:'Fraunces',serif;font-size:1.1rem;font-weight:600;color:#fff;
  letter-spacing:-.03em;
}
.logo-name{
  font-family:'Fraunces',serif;font-size:1.2rem;font-weight:600;
  letter-spacing:-.04em;color:var(--ink);
}
.logo-name span{color:var(--accent);}
.nav-links{display:flex;align-items:center;gap:.25rem;list-style:none;}
.nav-links a{
  font-size:.85rem;font-weight:500;color:var(--ink60);
  padding:.45rem .8rem;border-radius:var(--rx);
  transition:all .25s;
}
.nav-links a:hover{color:var(--ink);background:var(--ink10);}
.nav-right{display:flex;align-items:center;gap:.75rem;}
.btn-nav{
  font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:500;
  padding:.5rem 1.25rem;border-radius:var(--rx);border:1px solid var(--ink30);
  background:transparent;color:var(--ink);cursor:pointer;transition:all .25s;
}
.btn-nav:hover{background:var(--ink10);}
.btn-nav-fill{
  background:var(--accent);color:#fff;border-color:var(--accent);
  box-shadow:0 4px 20px color-mix(in srgb,var(--accent) 35%,transparent);
}
.btn-nav-fill:hover{background:color-mix(in srgb,var(--accent) 85%,#000);transform:translateY(-1px);}
#themeBtn{
  width:36px;height:36px;border-radius:50%;background:var(--ink10);
  border:none;cursor:pointer;font-size:.95rem;display:flex;align-items:center;justify-content:center;
  color:var(--ink);transition:all .25s;
}
#themeBtn:hover{background:var(--ink);color:var(--paper);}

/* ── HERO ── */
.hero{
  min-height:100vh;position:relative;overflow:hidden;
  display:grid;place-items:center;
  padding:8rem 3rem 5rem;
}

/* Large editorial BG text */
.hero-bg-word{
  position:absolute;
  font-family:'Fraunces',serif;font-weight:300;font-style:italic;
  font-size:clamp(10rem,22vw,22rem);
  color:transparent;
  -webkit-text-stroke:1px color-mix(in srgb,var(--ink) 6%,transparent);
  line-height:1;letter-spacing:-.05em;
  pointer-events:none;user-select:none;z-index:0;
  top:50%;transform:translateY(-50%);
  right:-4%;
}

/* Decorative circle */
.hero-circle{
  position:absolute;
  width:clamp(320px,45vw,600px);
  height:clamp(320px,45vw,600px);
  border-radius:50%;
  background:radial-gradient(circle at 35% 40%,
    color-mix(in srgb,var(--accent) 18%,transparent),
    color-mix(in srgb,var(--accent2) 12%,transparent) 45%,
    transparent 70%);
  right:-8%;top:50%;transform:translateY(-50%);
  z-index:0;pointer-events:none;
  animation:circleBreath 8s ease-in-out infinite;
}
@keyframes circleBreath{
  0%,100%{transform:translateY(-50%) scale(1);}
  50%{transform:translateY(-54%) scale(1.06);}
}

/* Orbital rings around circle */
.hero-ring{
  position:absolute;border-radius:50%;border:1px solid;
  right:-8%;top:50%;
  pointer-events:none;z-index:0;
  animation:ringSpin linear infinite;
}
.hero-ring-1{
  width:clamp(420px,60vw,740px);height:clamp(420px,60vw,740px);
  border-color:color-mix(in srgb,var(--accent) 15%,transparent);
  transform:translate(50%,-50%) rotate(0deg);
  animation-duration:40s;transform-origin:center;
}
.hero-ring-2{
  width:clamp(500px,72vw,900px);height:clamp(500px,72vw,900px);
  border-color:color-mix(in srgb,var(--accent2) 10%,transparent);
  transform:translate(50%,-50%) rotate(0deg);
  animation-duration:60s;animation-direction:reverse;
}
@keyframes ringSpin{to{transform:translate(50%,-50%) rotate(360deg);}}

/* Floating career pills on ring */
.ring-pill{
  position:absolute;
  padding:.35rem .9rem;border-radius:var(--rx);
  font-size:.78rem;font-weight:500;white-space:nowrap;
  backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);
  border:1px solid var(--ink10);
  background:color-mix(in srgb,var(--paper) 80%,transparent);
  color:var(--ink);
  animation:pillFloat ease-in-out infinite;
}
.rp1{right:4%;top:22%;animation-duration:4.5s;animation-delay:0s;}
.rp2{right:0%;top:44%;animation-duration:5.2s;animation-delay:-.8s;}
.rp3{right:6%;top:66%;animation-duration:4.8s;animation-delay:-1.5s;}
.rp4{right:22%;top:10%;animation-duration:6s;animation-delay:-2s;}
.rp5{right:21%;top:82%;animation-duration:5s;animation-delay:-3s;}
@keyframes pillFloat{
  0%,100%{transform:translateY(0);}
  50%{transform:translateY(-10px);}
}

.hero-content{position:relative;z-index:10;max-width:700px;}

/* Status tag */
.hero-eyebrow{
  display:inline-flex;align-items:center;gap:.5rem;
  margin-bottom:2.5rem;
  font-size:.78rem;font-weight:500;letter-spacing:.08em;text-transform:uppercase;
  color:var(--accent);
  animation:fadeUp .7s var(--ease) both;
}
.eyebrow-dot{
  width:8px;height:8px;border-radius:50%;background:var(--accent3);
  animation:dotPulse 2s ease-in-out infinite;
}
@keyframes dotPulse{0%,100%{opacity:1;box-shadow:0 0 0 0 color-mix(in srgb,var(--accent3) 50%,transparent);}50%{opacity:.6;box-shadow:0 0 0 6px transparent;}}

/* Giant title */
.hero-title{
  font-family:'Fraunces',serif;
  font-size:clamp(3.2rem,7.5vw,7.5rem);
  font-weight:300;line-height:1.02;letter-spacing:-.04em;
  margin-bottom:2rem;
  animation:fadeUp .9s var(--ease) .1s both;
}
.hero-title em{font-style:italic;color:var(--accent);}
.hero-title strong{font-weight:600;}

.hero-sub{
  font-size:1.05rem;color:var(--ink60);line-height:1.75;
  max-width:520px;margin-bottom:3rem;
  animation:fadeUp .9s var(--ease) .2s both;
}

.hero-ctas{
  display:flex;gap:1rem;flex-wrap:wrap;
  animation:fadeUp .9s var(--ease) .3s both;
}
.btn-main{
  display:inline-flex;align-items:center;gap:.65rem;
  padding:1rem 2rem;border-radius:var(--r);
  background:var(--accent);color:#fff;
  font-family:'DM Sans',sans-serif;font-size:1rem;font-weight:500;
  border:none;cursor:pointer;
  box-shadow:0 8px 32px color-mix(in srgb,var(--accent) 40%,transparent),0 2px 8px rgba(0,0,0,.15);
  transition:all .3s var(--ease);
  position:relative;overflow:hidden;
}
.btn-main::after{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(255,255,255,.15),transparent);
  opacity:0;transition:.3s;
}
.btn-main:hover{transform:translateY(-3px);box-shadow:0 16px 48px color-mix(in srgb,var(--accent) 45%,transparent);}
.btn-main:hover::after{opacity:1;}
.btn-ghost{
  display:inline-flex;align-items:center;gap:.5rem;
  padding:1rem 2rem;border-radius:var(--r);
  background:transparent;border:1px solid var(--ink30);
  color:var(--ink);font-family:'DM Sans',sans-serif;font-size:1rem;font-weight:500;
  cursor:pointer;transition:all .3s;
}
.btn-ghost:hover{background:var(--ink10);border-color:var(--ink60);}

/* Trust bar */
.hero-trust{
  display:flex;align-items:center;gap:2.5rem;flex-wrap:wrap;
  margin-top:3.5rem;padding-top:2rem;
  border-top:1px solid var(--ink10);
  animation:fadeUp .9s var(--ease) .4s both;
}
.trust-stat{}
.trust-num{
  font-family:'Fraunces',serif;font-size:2rem;font-weight:600;
  letter-spacing:-.04em;color:var(--ink);
  display:block;line-height:1;
}
.trust-label{font-size:.78rem;color:var(--ink60);margin-top:.25rem;}
.trust-divider{width:1px;height:40px;background:var(--ink10);}

/* ── MARQUEE ── */
.marquee-wrap{
  overflow:hidden;border-top:1px solid var(--ink10);border-bottom:1px solid var(--ink10);
  background:var(--cream);padding:.9rem 0;
  position:relative;z-index:10;
}
.marquee-track{
  display:flex;gap:3rem;width:max-content;
  animation:marqueeScroll 30s linear infinite;
}
@keyframes marqueeScroll{to{transform:translateX(-50%);}}
.marquee-item{
  font-family:'Fraunces',serif;font-style:italic;font-size:1rem;font-weight:300;
  color:var(--ink60);white-space:nowrap;display:flex;align-items:center;gap:1rem;
}
.marquee-item::before{content:'✦';font-style:normal;font-size:.65rem;color:var(--accent);}

/* ── SECTIONS ── */
.section{position:relative;z-index:10;}
.container{max-width:1200px;margin:0 auto;padding:0 3rem;}
.section-pad{padding:8rem 0;}
.section-tag{
  display:inline-flex;align-items:center;gap:.5rem;
  font-size:.72rem;font-weight:600;letter-spacing:.12em;text-transform:uppercase;
  color:var(--accent);margin-bottom:1.5rem;
}
.section-tag::before{content:'';width:20px;height:1px;background:var(--accent);}
.section-heading{
  font-family:'Fraunces',serif;
  font-size:clamp(2.4rem,5vw,4.2rem);
  font-weight:300;letter-spacing:-.03em;line-height:1.08;
  margin-bottom:1.25rem;
}
.section-heading em{font-style:italic;color:var(--accent);}
.section-body{
  font-size:1.05rem;color:var(--ink60);line-height:1.8;max-width:520px;
}

/* Reveal */
.reveal{opacity:0;transform:translateY(30px);transition:opacity .8s var(--ease),transform .8s var(--ease);}
.reveal.vis{opacity:1;transform:translateY(0);}
.reveal-d1{transition-delay:.1s;}
.reveal-d2{transition-delay:.2s;}
.reveal-d3{transition-delay:.3s;}
.reveal-d4{transition-delay:.4s;}

/* ── FEATURES ── */
.features-layout{display:grid;grid-template-columns:1fr 1.2fr;gap:6rem;align-items:center;}
.features-intro{}
.features-grid{
  display:grid;grid-template-columns:1fr 1fr;gap:1px;
  background:var(--ink10);border:1px solid var(--ink10);border-radius:var(--rl);
  overflow:hidden;
}
.feat-cell{
  background:var(--paper);padding:2rem 1.75rem;
  transition:all .3s var(--ease);
  position:relative;overflow:hidden;
}
.feat-cell::before{
  content:'';position:absolute;inset:0;
  background:color-mix(in srgb,var(--accent) 5%,transparent);
  opacity:0;transition:.3s;
}
.feat-cell:hover{background:var(--cream);}
.feat-cell:hover::before{opacity:1;}
.feat-icon{
  font-size:1.5rem;margin-bottom:1.25rem;display:block;
  width:44px;height:44px;
  background:color-mix(in srgb,var(--accent) 10%,transparent);
  border-radius:var(--r);display:flex;align-items:center;justify-content:center;
}
.feat-title{
  font-family:'Fraunces',serif;font-size:1.05rem;font-weight:600;
  letter-spacing:-.02em;color:var(--ink);margin-bottom:.5rem;
}
.feat-desc{font-size:.88rem;color:var(--ink60);line-height:1.65;}
.feat-tag{
  margin-top:1rem;font-size:.72rem;font-weight:600;letter-spacing:.06em;
  text-transform:uppercase;color:var(--accent3);
}

/* ── PROCESS ── */
.process-section{background:var(--cream);}
.process-grid{
  display:grid;grid-template-columns:repeat(4,1fr);gap:0;
  margin-top:4rem;
  border:1px solid var(--ink10);border-radius:var(--rl);overflow:hidden;
}
.process-step{
  padding:2.5rem 2rem;border-right:1px solid var(--ink10);
  position:relative;
  transition:.3s var(--ease);
}
.process-step:last-child{border-right:none;}
.process-step:hover{background:var(--warm);}
.ps-num{
  font-family:'Fraunces',serif;font-size:3.5rem;font-weight:300;
  color:var(--ink10);letter-spacing:-.05em;line-height:1;
  margin-bottom:1.5rem;display:block;
  transition:.3s;
}
.process-step:hover .ps-num{color:var(--accent);opacity:.3;}
.ps-icon{
  width:44px;height:44px;border-radius:var(--r);
  background:var(--ink);color:var(--paper);
  display:flex;align-items:center;justify-content:center;
  font-size:1.1rem;margin-bottom:1.25rem;
}
.ps-title{
  font-family:'Fraunces',serif;font-size:1.1rem;font-weight:600;
  letter-spacing:-.02em;margin-bottom:.6rem;
}
.ps-desc{font-size:.88rem;color:var(--ink60);line-height:1.65;}
.ps-time{
  margin-top:1rem;font-size:.75rem;font-weight:600;
  color:var(--accent);letter-spacing:.04em;
}

/* ── TESTIMONIALS ── */
.testi-section{}
.testi-featured{
  display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:4rem;
}
.testi-big{
  background:var(--accent);color:#fff;
  border-radius:var(--rl);padding:3rem;
  grid-row:span 2;
  display:flex;flex-direction:column;justify-content:space-between;
}
.testi-quote{
  font-family:'Fraunces',serif;font-size:1.5rem;font-weight:300;font-style:italic;
  line-height:1.6;letter-spacing:-.02em;flex:1;
}
.testi-quote em{font-style:normal;font-weight:600;}
.testi-author{margin-top:2rem;display:flex;align-items:center;gap:.875rem;}
.testi-ava{
  width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,.25);
  display:flex;align-items:center;justify-content:center;
  font-family:'Fraunces',serif;font-size:1rem;font-weight:600;color:#fff;
  flex-shrink:0;
}
.testi-name{font-size:.88rem;font-weight:600;color:#fff;}
.testi-role{font-size:.78rem;color:rgba(255,255,255,.65);margin-top:.15rem;}
.testi-stars{font-size:.8rem;color:rgba(255,255,255,.8);margin-top:.2rem;}
.testi-small{
  background:var(--cream);border:1px solid var(--ink10);
  border-radius:var(--rl);padding:2rem;
  transition:.3s var(--ease);
}
.testi-small:hover{border-color:var(--accent);background:var(--warm);}
.testi-small .testi-quote{font-size:1rem;color:var(--ink);}
.testi-small .testi-name{color:var(--ink);}
.testi-small .testi-role{color:var(--ink60);}
.testi-small .testi-stars{color:var(--gold);}

/* ── PRICING ── */
.pricing-section{background:var(--cream);}
.pricing-grid{
  display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;
  margin-top:4rem;
}
.pricing-card{
  border-radius:var(--rl);padding:2.5rem 2rem;
  border:1px solid var(--ink10);
  background:var(--paper);
  transition:.3s var(--ease);
  display:flex;flex-direction:column;
}
.pricing-card:hover{border-color:var(--ink30);transform:translateY(-4px);box-shadow:0 20px 60px rgba(0,0,0,.08);}
.pricing-card.featured{
  background:var(--ink);color:var(--paper);
  border-color:var(--ink);
}
.pricing-card.featured .pricing-plan{color:var(--gold);}
.pricing-card.featured .pricing-period{color:color-mix(in srgb,var(--paper) 55%,transparent);}
.pricing-card.featured .pricing-feat li{border-color:rgba(255,255,255,.1);color:color-mix(in srgb,var(--paper) 75%,transparent);}
.pricing-card.featured .pricing-feat li::before{color:var(--accent3);}
.pricing-popular{
  display:inline-block;margin-bottom:1.5rem;
  padding:.3rem .85rem;border-radius:var(--rx);
  background:var(--gold);color:var(--ink);
  font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;
}
.pricing-plan{
  font-size:.75rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;
  color:var(--accent);margin-bottom:1.25rem;display:block;
}
.pricing-price{
  font-family:'Fraunces',serif;font-size:3.5rem;font-weight:600;
  letter-spacing:-.05em;line-height:1;
  display:flex;align-items:baseline;gap:.35rem;
}
.pricing-price small{font-size:1.25rem;font-weight:300;}
.pricing-period{font-size:.85rem;color:var(--ink60);margin-top:.5rem;margin-bottom:1.5rem;}
.pricing-desc{font-size:.9rem;color:var(--ink60);line-height:1.65;margin-bottom:2rem;flex:1;}
.pricing-feat{list-style:none;margin-bottom:2rem;}
.pricing-feat li{
  font-size:.88rem;color:var(--ink60);padding:.5rem 0;
  border-bottom:1px solid var(--ink10);
  display:flex;align-items:center;gap:.5rem;
}
.pricing-feat li::before{content:'✓';color:var(--accent3);font-weight:700;flex-shrink:0;}
.btn-price{
  display:flex;align-items:center;justify-content:center;gap:.5rem;
  width:100%;padding:.875rem 1.5rem;border-radius:var(--r);
  font-family:'DM Sans',sans-serif;font-size:.95rem;font-weight:500;
  cursor:pointer;transition:all .3s var(--ease);
  text-decoration:none;
}
.btn-price-outline{
  background:transparent;border:1px solid var(--ink30);color:var(--ink);
}
.btn-price-outline:hover{background:var(--ink10);}
.btn-price-fill{
  background:var(--accent);color:#fff;border:1px solid var(--accent);
  box-shadow:0 6px 24px color-mix(in srgb,var(--accent) 35%,transparent);
}
.btn-price-fill:hover{transform:translateY(-2px);box-shadow:0 12px 40px color-mix(in srgb,var(--accent) 45%,transparent);}
.btn-price-light{
  background:rgba(255,255,255,.1);color:var(--paper);
  border:1px solid rgba(255,255,255,.2);
}
.btn-price-light:hover{background:rgba(255,255,255,.18);}

/* ── CTA FINALE ── */
.cta-section{
  position:relative;overflow:hidden;
  padding:8rem 0;
}
.cta-bg{
  position:absolute;inset:0;
  background:var(--accent2);
}
.cta-grain{
  position:absolute;inset:0;
  background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.06'/%3E%3C/svg%3E");
  opacity:.8;
}
.cta-deco{
  position:absolute;width:600px;height:600px;border-radius:50%;
  background:radial-gradient(circle,color-mix(in srgb,var(--accent) 35%,transparent),transparent 70%);
  right:-15%;top:50%;transform:translateY(-50%);
  pointer-events:none;
}
.cta-inner{
  position:relative;z-index:10;text-align:center;
  max-width:680px;margin:0 auto;padding:0 2rem;
}
.cta-label{
  display:inline-block;margin-bottom:2rem;
  padding:.35rem 1rem;border-radius:var(--rx);
  background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);
  font-size:.75rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;
  color:rgba(255,255,255,.75);
}
.cta-title{
  font-family:'Fraunces',serif;
  font-size:clamp(2.8rem,6vw,5rem);font-weight:300;
  line-height:1.06;letter-spacing:-.04em;
  color:#fff;margin-bottom:1.5rem;
}
.cta-title em{font-style:italic;}
.cta-sub{font-size:1.05rem;color:rgba(255,255,255,.65);line-height:1.75;margin-bottom:3rem;}
.btn-cta{
  display:inline-flex;align-items:center;gap:.65rem;
  padding:1.1rem 2.5rem;border-radius:var(--r);
  background:#fff;color:var(--accent2);
  font-family:'DM Sans',sans-serif;font-size:1rem;font-weight:600;
  border:none;cursor:pointer;
  box-shadow:0 8px 40px rgba(0,0,0,.25);
  transition:all .3s var(--ease);
}
.btn-cta:hover{transform:translateY(-3px);box-shadow:0 20px 60px rgba(0,0,0,.3);}
.cta-trust-row{
  display:flex;align-items:center;justify-content:center;gap:2rem;
  margin-top:2rem;flex-wrap:wrap;
}
.cta-trust-item{
  display:flex;align-items:center;gap:.4rem;
  font-size:.82rem;color:rgba(255,255,255,.55);
}
.cta-trust-item span{color:rgba(255,255,255,.8);font-weight:500;}

/* ── FOOTER ── */
footer{
  background:var(--cream);
  border-top:1px solid var(--ink10);
  position:relative;z-index:10;
}
.footer-inner{
  max-width:1200px;margin:0 auto;padding:4rem 3rem 2.5rem;
}
.footer-top{
  display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;
  gap:3rem;padding-bottom:3rem;border-bottom:1px solid var(--ink10);
}
.footer-brand-desc{
  font-size:.88rem;color:var(--ink60);line-height:1.7;
  margin:.875rem 0 1.5rem;max-width:260px;
}
.footer-socials{display:flex;gap:.5rem;}
.soc{
  width:36px;height:36px;border-radius:var(--r);
  background:var(--ink10);border:none;cursor:pointer;
  font-size:.8rem;font-weight:700;color:var(--ink60);
  display:flex;align-items:center;justify-content:center;
  transition:.25s;
}
.soc:hover{background:var(--ink);color:var(--paper);}
.footer-col-title{
  font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;
  color:var(--ink);margin-bottom:1.25rem;
}
.footer-col-links{list-style:none;}
.footer-col-links li{margin-bottom:.55rem;}
.footer-col-links a{
  font-size:.88rem;color:var(--ink60);transition:.2s;
}
.footer-col-links a:hover{color:var(--accent);}
.footer-bottom{
  display:flex;align-items:center;justify-content:space-between;
  padding-top:2rem;gap:1rem;flex-wrap:wrap;
}
.footer-copy{font-size:.82rem;color:var(--ink30);}
.footer-made{
  font-size:.82rem;color:var(--ink30);
  display:flex;align-items:center;gap:.35rem;
}
.footer-made strong{color:var(--accent);}

/* ── STATS BAR ── */
.stats-bar{
  background:var(--ink);color:var(--paper);
  position:relative;z-index:10;
}
.stats-inner{
  max-width:1200px;margin:0 auto;padding:3rem;
  display:grid;grid-template-columns:repeat(4,1fr);
  gap:2rem;text-align:center;
}
.stat-n{
  font-family:'Fraunces',serif;font-size:3rem;font-weight:600;
  letter-spacing:-.05em;line-height:1;
  color:var(--paper);display:block;
}
.stat-l{font-size:.85rem;color:color-mix(in srgb,var(--paper) 55%,transparent);margin-top:.4rem;}

/* ── MISC ANIMATIONS ── */
@keyframes fadeUp{from{opacity:0;transform:translateY(24px);}to{opacity:1;transform:translateY(0);}}

/* ── RESPONSIVE ── */
@media(max-width:1100px){
  .features-layout{grid-template-columns:1fr;}
  .features-intro{max-width:600px;}
  .process-grid{grid-template-columns:1fr 1fr;}
  .process-step:nth-child(2){border-right:none;}
  .process-step:nth-child(3){border-top:1px solid var(--ink10);}
  .process-step:nth-child(4){border-top:1px solid var(--ink10);}
  .testi-featured{grid-template-columns:1fr;}
  .testi-big{grid-row:auto;}
  .pricing-grid{grid-template-columns:1fr;max-width:500px;margin-left:auto;margin-right:auto;}
}
@media(max-width:768px){
  nav{padding:1rem 1.5rem;}
  nav.scrolled{padding:.75rem 1.5rem;}
  .nav-links,.nav-right .btn-nav:first-child{display:none;}
  .hero{padding:7rem 1.5rem 4rem;}
  .hero-bg-word,.hero-circle,.hero-ring,.ring-pill{display:none;}
  .container{padding:0 1.5rem;}
  .section-pad{padding:5rem 0;}
  .stats-inner{grid-template-columns:1fr 1fr;gap:1.5rem;}
  .features-grid{grid-template-columns:1fr;}
  .footer-top{grid-template-columns:1fr 1fr;}
  .process-grid{grid-template-columns:1fr;border-radius:0;}
  .process-step{border-right:none;border-bottom:1px solid var(--ink10);}
  .hero-trust{gap:1.5rem;}
  .trust-divider{display:none;}
}
</style>
</head>
<body>

<!-- ═══ NAV ═══ -->
<nav id="nav">
  <a href="#" class="nav-logo">
    <div class="logo-mark">Ca</div>
    <span class="logo-name">Cap<span>Avenir</span></span>
  </a>
  <ul class="nav-links">
    <li><a href="#features">Fonctionnalités</a></li>
    <li><a href="#process">Comment ça marche</a></li>
    <li><a href="#testimonials">Témoignages</a></li>
    <li><a href="#pricing">Tarifs</a></li>
  </ul>
  <div class="nav-right">
    <button id="themeBtn" title="Changer de thème">🌙</button>
    <a href="/login" class="btn-nav">Se connecter</a>
    <a href="/register" class="btn-nav btn-nav-fill">Commencer → </a>
  </div>
</nav>

<!-- ═══ HERO ═══ -->
<section class="hero">
  <!-- Large BG editorial text -->
  <div class="hero-bg-word">Avenir</div>

  <!-- Decorative orb -->
  <div class="hero-circle"></div>
  <div class="hero-ring hero-ring-1"></div>
  <div class="hero-ring hero-ring-2"></div>

  <!-- Floating career pills -->
  <div class="ring-pill rp1">🎨 Design UX</div>
  <div class="ring-pill rp2">💻 Génie logiciel</div>
  <div class="ring-pill rp3">🔬 Data Science</div>
  <div class="ring-pill rp4">⚕️ Médecine</div>
  <div class="ring-pill rp5">🏛️ Architecture</div>

  <div class="hero-content">
    <div class="hero-eyebrow">
      <span class="eyebrow-dot"></span>
      Orientation IA · Tunisie 2026
    </div>

    <h1 class="hero-title">
      Trouve la voie<br>
      qui te <em>ressemble</em><br>
      <strong>vraiment.</strong>
    </h1>

    <p class="hero-sub">
      CapAvenir analyse tes aptitudes, valeurs et ambitions grâce à l'IA pour te proposer un parcours universitaire sur mesure — adapté au système éducatif tunisien.
    </p>

    <div class="hero-ctas">
      <a href="/register" class="btn-main">
        Découvrir mon orientation <span>→</span>
      </a>
      <a href="#process" class="btn-ghost">
        Voir comment ça marche
      </a>
    </div>

    <div class="hero-trust">
      <div class="trust-stat">
        <span class="trust-num" data-count="15000">0</span>
        <div class="trust-label">Jeunes orientés</div>
      </div>
      <div class="trust-divider"></div>
      <div class="trust-stat">
        <span class="trust-num" data-count="94">0</span>
        <div class="trust-label">% de satisfaction</div>
      </div>
      <div class="trust-divider"></div>
      <div class="trust-stat">
        <span class="trust-num">12 min</span>
        <div class="trust-label">Pour ton profil complet</div>
      </div>
      <div class="trust-divider"></div>
      <div class="trust-stat">
        <span class="trust-num">100%</span>
        <div class="trust-label">Gratuit pour commencer</div>
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
        <p class="section-body">Fini les QCM génériques. CapAvenir utilise une IA conversationnelle pour te comprendre vraiment — aptitudes, valeurs, style d'apprentissage — et te guider vers les formations qui font sens pour toi.</p>
        <a href="/register" class="btn-main" style="margin-top:2.5rem;display:inline-flex;">Essayer gratuitement →</a>
      </div>

      <div class="features-grid reveal reveal-d2">
        <div class="feat-cell">
          <div class="feat-icon">🧪</div>
          <h3 class="feat-title">Test IA adaptatif</h3>
          <p class="feat-desc">Quiz intelligent qui s'adapte à tes réponses. Jamais ennuyeux, toujours révélateur.</p>
          <p class="feat-tag">↑ 87% de clarté rapportée</p>
        </div>
        <div class="feat-cell">
          <div class="feat-icon">🪐</div>
          <h3 class="feat-title">Profil personnalisé</h3>
          <p class="feat-desc">Ton avatar IA évolue avec toi. Aptitudes, motivations, valeurs, style d'apprentissage.</p>
          <p class="feat-tag">✦ Mis à jour à chaque session</p>
        </div>
        <div class="feat-cell">
          <div class="feat-icon">🎯</div>
          <h3 class="feat-title">Matching ultra-précis</h3>
          <p class="feat-desc">+90% de compatibilité avec les formations. Algorithme entraîné sur le marché tunisien.</p>
          <p class="feat-tag">→ Score de compatibilité vérifié</p>
        </div>
        <div class="feat-cell">
          <div class="feat-icon">🔮</div>
          <h3 class="feat-title">Simulateur de vie</h3>
          <p class="feat-desc">Visualise ton futur selon chaque filière : emplois, salaires, évolution de carrière.</p>
          <p class="feat-tag">◎ Décision éclairée en 1 séance</p>
        </div>
        <div class="feat-cell">
          <div class="feat-icon">🤝</div>
          <h3 class="feat-title">Coaching humain + IA</h3>
          <p class="feat-desc">Conseillers certifiés + chatbot IA 24h/7j. L'humain et la tech, jamais l'un sans l'autre.</p>
          <p class="feat-tag">⚡ Réponse en moins de 2 min</p>
        </div>
        <div class="feat-cell">
          <div class="feat-icon">🗺️</div>
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
        <div class="ps-icon">👤</div>
        <h3 class="ps-title">Crée ton compte</h3>
        <p class="ps-desc">Inscription en 30 secondes. Aucune carte bancaire. Aucun engagement.</p>
        <p class="ps-time">⏱ 30 secondes</p>
      </div>
      <div class="process-step reveal reveal-d2">
        <span class="ps-num">02</span>
        <div class="ps-icon">🧪</div>
        <h3 class="ps-title">Passe le test intelligent</h3>
        <p class="ps-desc">Quiz adaptatif IA sur tes aptitudes, valeurs et personnalité. Engageant, pas ennuyeux.</p>
        <p class="ps-time">⏱ 12 minutes</p>
      </div>
      <div class="process-step reveal reveal-d3">
        <span class="ps-num">03</span>
        <div class="ps-icon">📊</div>
        <h3 class="ps-title">Découvre ton profil IA</h3>
        <p class="ps-desc">Rapport complet, formations recommandées, scores de compatibilité détaillés.</p>
        <p class="ps-time">⏱ Résultats instantanés</p>
      </div>
      <div class="process-step reveal reveal-d4">
        <span class="ps-num">04</span>
        <div class="ps-icon">🚀</div>
        <h3 class="ps-title">Simule, discute, choisis</h3>
        <p class="ps-desc">Utilise le simulateur de vie, le chatbot IA ou réserve une séance avec un conseiller humain.</p>
        <p class="ps-time">⏱ À ton rythme</p>
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
          « J'hésitais entre médecine et informatique. L'IA m'a ouvert les yeux sur la <em>data science</em>. Aujourd'hui je suis à l'INSAT et je me sens enfin à ma place. »
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
          « En tant que conseillère, je gagne <em>3× plus de temps</em> pour vraiment accompagner mes élèves. Le rapport IA est bluffant de précision. »
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
          « Le simulateur de vie m'a montré <em>concrètement les débouchés</em> de chaque filière. Je n'aurais jamais pensé à l'architecture avant ça. »
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
          « Mon fils voulait partir à l'étranger sans savoir pourquoi. Après CapAvenir, il a choisi l'<em>ENIT avec conviction</em>. »
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
        <div class="pricing-popular">⭐ Le plus populaire</div>
        <span class="pricing-plan">Premium Étudiant</span>
        <div class="pricing-price"><small>TND</small>39</div>
        <p class="pricing-period">par mois · Annuler à tout moment</p>
        <p class="pricing-desc" style="color:color-mix(in srgb,var(--paper) 65%,transparent);">Tout ce qu'il faut pour décider avec confiance et sérénité.</p>
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
    <p class="cta-sub">Ton avenir n'est plus une loterie. En 12 minutes, découvre le parcours fait pour toi — adapté à la Tunisie, propulsé par l'IA.</p>
    <a href="/register" class="btn-cta">S'inscrire maintenant →</a>
    <div class="cta-trust-row">
      <div class="cta-trust-item">✓ <span>Sans carte bancaire</span></div>
      <div class="cta-trust-item">✓ <span>Sans engagement</span></div>
      <div class="cta-trust-item">✓ <span>Résultats en 12 minutes</span></div>
    </div>
  </div>
</section>

<!-- ═══ FOOTER ═══ -->
<footer>
  <div class="footer-inner">
    <div class="footer-top">
      <div>
        <div class="nav-logo">
          <div class="logo-mark">Ca</div>
          <span class="logo-name">Cap<span>Avenir</span></span>
        </div>
        <p class="footer-brand-desc">La plateforme d'orientation intelligente propulsée par l'IA, conçue pour le système éducatif tunisien.</p>
        <div class="footer-socials">
          <button class="soc">𝕏</button>
          <button class="soc">in</button>
          <button class="soc">f</button>
          <button class="soc">▶</button>
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
      <span class="footer-made">Made with <strong>IA</strong> ❤️ in Tunisia 🇹🇳</span>
    </div>
  </div>
</footer>

<script>
// ── NAVBAR ──
const nav = document.getElementById('nav');
window.addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 60), {passive:true});

// ── THEME ──
const themeBtn = document.getElementById('themeBtn');
let dark = true;
themeBtn.addEventListener('click', () => {
  dark = !dark;
  document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
  themeBtn.textContent = dark ? '🌙' : '☀️';
});

// ── REVEAL ──
const revEls = document.querySelectorAll('.reveal');
const revObs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); revObs.unobserve(e.target); }});
}, {threshold: .1, rootMargin:'0px 0px -50px 0px'});
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
}, {threshold: .5});
counters.forEach(c => cntObs.observe(c));

// ── SMOOTH SCROLL ──
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const target = document.querySelector(a.getAttribute('href'));
    if (target) { e.preventDefault(); target.scrollIntoView({behavior:'smooth', block:'start'}); }
  });
});
</script>
</body>
</html>