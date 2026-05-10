<style>
/* ══════════════════════════════════════════
   ORIENTATION — CapAvenir Design System
══════════════════════════════════════════ */
.or {
    --ink:#0b0c10; --paper:#f7f5f0; --cream:#ede9e1; --warm:#e8e1d4;
    --accent:#d4622a; --accent2:#1a4f6e; --accent3:#4a7c59; --gold:#c8973a;
    --ink60:rgba(11,12,16,.6); --ink30:rgba(11,12,16,.3);
    --ink15:rgba(11,12,16,.15); --ink10:rgba(11,12,16,.1); --ink06:rgba(11,12,16,.06);
    --r:6px; --rl:16px; --rx:999px; --ease:cubic-bezier(.16,1,.3,1);
    font-family:'DM Sans',sans-serif; color:var(--ink); background:var(--paper);
    padding:2rem 2.5rem 5rem;
}
[data-theme="dark"] .or {
    --ink:#f0ede6; --paper:#10100d; --cream:#18170f; --warm:#1f1e14;
    --ink60:rgba(240,237,230,.6); --ink30:rgba(240,237,230,.3);
    --ink15:rgba(240,237,230,.15); --ink10:rgba(240,237,230,.08); --ink06:rgba(240,237,230,.04);
}
.or *,.or *::before,.or *::after { box-sizing:border-box; margin:0; padding:0; }
.or a { color:inherit; text-decoration:none; }

/* ── Reveal ── */
.or .rev { opacity:0; transform:translateY(22px); transition:opacity .7s var(--ease),transform .7s var(--ease); }
.or .rev.vis { opacity:1; transform:none; }
.or .rev-d1{transition-delay:.08s;} .or .rev-d2{transition-delay:.16s;} .or .rev-d3{transition-delay:.24s;} .or .rev-d4{transition-delay:.32s;}

/* ── Tags ── */
.or .stag { font-size:.72rem; font-weight:600; letter-spacing:.12em; text-transform:uppercase; color:var(--accent); display:inline-flex; align-items:center; gap:.5rem; margin-bottom:1rem; }
.or .stag::before { content:''; width:18px; height:1px; background:var(--accent); }

/* ── Pills ── */
.or .pill { display:inline-flex; align-items:center; gap:.35rem; padding:.28rem .8rem; border-radius:var(--rx); font-size:.72rem; font-weight:600; letter-spacing:.05em; }
.or .pill-accent { background:color-mix(in srgb,var(--accent) 10%,transparent); color:var(--accent); border:1px solid color-mix(in srgb,var(--accent) 25%,transparent); }
.or .pill-sage   { background:color-mix(in srgb,var(--accent3) 10%,transparent); color:var(--accent3); border:1px solid color-mix(in srgb,var(--accent3) 25%,transparent); }
.or .pill-marine { background:color-mix(in srgb,var(--accent2) 10%,transparent); color:var(--accent2); border:1px solid color-mix(in srgb,var(--accent2) 25%,transparent); }
.or .pill-gold   { background:color-mix(in srgb,var(--gold) 12%,transparent); color:var(--gold); border:1px solid color-mix(in srgb,var(--gold) 28%,transparent); }
.or .pill-ink    { background:var(--ink06); color:var(--ink60); border:1px solid var(--ink10); }

/* ── Buttons ── */
.or .btn-fill { display:inline-flex; align-items:center; gap:.5rem; padding:.75rem 1.5rem; border-radius:var(--r); background:var(--accent); color:#fff; font-family:'DM Sans',sans-serif; font-size:.88rem; font-weight:500; border:none; cursor:pointer; box-shadow:0 4px 20px color-mix(in srgb,var(--accent) 30%,transparent); transition:all .3s var(--ease); }
.or .btn-fill:hover { transform:translateY(-2px); box-shadow:0 10px 28px color-mix(in srgb,var(--accent) 40%,transparent); }
.or .btn-ghost { display:inline-flex; align-items:center; gap:.5rem; padding:.65rem 1.3rem; border-radius:var(--r); background:transparent; border:1px solid var(--ink30); color:var(--ink); font-family:'DM Sans',sans-serif; font-size:.85rem; font-weight:500; cursor:pointer; transition:all .25s; }
.or .btn-ghost:hover { background:var(--ink10); border-color:var(--ink60); }
.or .btn-danger { display:inline-flex; align-items:center; gap:.4rem; padding:.7rem .9rem; border-radius:var(--r); background:color-mix(in srgb,#ef4444 8%,transparent); border:1px solid color-mix(in srgb,#ef4444 22%,transparent); color:#ef4444; font-size:.85rem; font-weight:500; cursor:pointer; transition:all .25s; text-decoration:none; }
.or .btn-danger:hover { background:color-mix(in srgb,#ef4444 14%,transparent); }

/* ══════════════════════
   § HERO
══════════════════════ */
.or-hero {
    position:relative; background:var(--cream); border:1px solid var(--ink10);
    border-radius:20px; padding:3.5rem 4rem 3rem; overflow:hidden;
    margin-bottom:1.75rem; animation:orFadeUp .9s var(--ease) both;
}
@keyframes orFadeUp { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:none} }
.or-hero-bgword {
    position:absolute; font-family:'Fraunces',serif; font-weight:300; font-style:italic;
    font-size:clamp(8rem,16vw,14rem); color:transparent;
    -webkit-text-stroke:1px color-mix(in srgb,var(--ink) 5%,transparent);
    right:-1%; top:50%; transform:translateY(-50%); pointer-events:none; user-select:none; white-space:nowrap;
}
.or-hero-orb {
    position:absolute; width:380px; height:380px; border-radius:50%;
    background:radial-gradient(circle at 40% 40%,color-mix(in srgb,var(--accent) 14%,transparent),color-mix(in srgb,var(--accent2) 9%,transparent) 55%,transparent 75%);
    right:4%; top:50%; transform:translateY(-50%); pointer-events:none; animation:orbBreath 7s ease-in-out infinite;
}
@keyframes orbBreath { 0%,100%{transform:translateY(-50%) scale(1)} 50%{transform:translateY(-54%) scale(1.07)} }
.or-hero-inner { position:relative; z-index:10; max-width:680px; }
.or-eyebrow { display:inline-flex; align-items:center; gap:.5rem; font-size:.75rem; font-weight:600; letter-spacing:.1em; text-transform:uppercase; color:var(--accent); margin-bottom:1.5rem; }
.or-eyebrow::before { content:''; width:18px; height:1px; background:var(--accent); }
.or-eyebrow-dot { width:8px; height:8px; border-radius:50%; background:var(--accent3); animation:dotPulse 2s ease-in-out infinite; }
@keyframes dotPulse { 0%,100%{opacity:1} 50%{opacity:.4} }
.or-hero-title { font-family:'Fraunces',serif; font-size:clamp(2.4rem,4.5vw,3.8rem); font-weight:300; line-height:1.08; letter-spacing:-.04em; margin-bottom:1rem; }
.or-hero-title em { font-style:italic; color:var(--accent); }
.or-hero-sub { font-size:.95rem; color:var(--ink60); line-height:1.75; margin-bottom:1.75rem; max-width:520px; }
.or-hero-actions { display:flex; align-items:center; flex-wrap:wrap; gap:1rem; }
.or-hero-meta { display:flex; flex-wrap:wrap; gap:.5rem; }
.or-cta-btn { background:var(--ink); color:var(--paper); box-shadow:none; }
.or-cta-btn:hover { background:color-mix(in srgb,var(--ink) 88%,var(--accent)); transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,.2); }

/* ══════════════════════
   § LAYOUT SIDEBAR + MAIN
══════════════════════ */
.or-layout { display:grid; grid-template-columns:280px 1fr; gap:1.75rem; align-items:start; }

/* ══════════════════════
   § SIDEBAR
══════════════════════ */
.or-sidebar {
    position:sticky; top:80px;
    background:var(--cream); border:1px solid var(--ink10); border-radius:var(--rl);
    overflow:hidden; display:flex; flex-direction:column;
}
.or-sidebar-block { padding:1.25rem 1.25rem 1rem; border-bottom:1px solid var(--ink10); }
.or-sidebar-block:last-child { border-bottom:none; }
.or-sidebar-label { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--ink30); margin-bottom:.75rem; }

/* Search inside sidebar */
.or-search-inner { position:relative; }
.or-search-icon { position:absolute; left:.85rem; top:50%; transform:translateY(-50%); font-size:.9rem; color:var(--ink30); pointer-events:none; }
.or-search-input { width:100%; padding:.7rem .9rem .7rem 2.5rem; background:var(--paper); border:1px solid var(--ink10); border-radius:var(--r); color:var(--ink); font-family:'DM Sans',sans-serif; font-size:.85rem; transition:border-color .2s; }
.or-search-input:focus { outline:none; border-color:var(--accent); }
.or-search-input::placeholder { color:var(--ink30); }

/* Domain tabs */
.or-sidebar-tabs { display:flex; flex-direction:column; gap:.25rem; }
.or-sidebar-tab { display:block; padding:.45rem .85rem; border-radius:var(--r); font-size:.82rem; font-weight:500; color:var(--ink60); border:1px solid transparent; transition:all .2s; cursor:pointer; }
.or-sidebar-tab:hover { background:var(--warm); color:var(--ink); }
.or-sidebar-tab.active { background:var(--accent); color:#fff; font-weight:600; }

/* Niveau buttons */
.or-nivel-grid { display:grid; grid-template-columns:1fr 1fr; gap:.35rem; }
.or-nivel-btn { padding:.45rem .5rem; border-radius:var(--r); font-family:'DM Sans',sans-serif; font-size:.78rem; font-weight:600; background:var(--paper); border:1px solid var(--ink10); color:var(--ink60); cursor:pointer; transition:all .2s; text-align:center; }
.or-nivel-btn:hover { border-color:var(--ink30); color:var(--ink); }
.or-nivel-btn.active { background:var(--ink); color:var(--paper); border-color:var(--ink); }

/* Spec pills list */
.or-spec-list { display:flex; flex-direction:column; gap:.3rem; }
.or-spec-pill { display:flex; align-items:center; gap:.6rem; padding:.5rem .75rem; border-radius:var(--r); border:1px solid transparent; cursor:pointer; transition:all .2s; }
.or-spec-pill:hover { background:var(--warm); }
.or-spec-pill.active { background:color-mix(in srgb,var(--accent) 8%,transparent); border-color:color-mix(in srgb,var(--accent) 25%,transparent); }
.or-spec-pill-icon { font-size:1rem; flex-shrink:0; }
.or-spec-pill-name { font-size:.82rem; font-weight:500; color:var(--ink); flex:1; line-height:1.3; }
.or-spec-pill.active .or-spec-pill-name { color:var(--accent); font-weight:600; }
.or-spec-pill-count { font-size:.7rem; font-weight:600; color:var(--ink30); background:var(--paper); padding:.15rem .5rem; border-radius:var(--rx); border:1px solid var(--ink10); white-space:nowrap; }

/* ══════════════════════
   § MAIN CONTENT
══════════════════════ */
.or-main {}

/* Results header */
.or-results-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; gap:.75rem; flex-wrap:wrap; }
.or-results-info { display:flex; align-items:baseline; gap:.5rem; flex-wrap:wrap; }
.or-results-count-big { font-family:'Fraunces',serif; font-size:2rem; font-weight:600; letter-spacing:-.04em; color:var(--accent); line-height:1; }
.or-results-count-label { font-size:.88rem; color:var(--ink60); }
.or-results-count-label strong { color:var(--ink); }
.or-results-count-label em { font-style:italic; }
.or-filter-toggle { display:none; align-items:center; gap:.4rem; padding:.45rem .9rem; border-radius:var(--r); background:var(--cream); border:1px solid var(--ink10); color:var(--ink60); font-family:'DM Sans',sans-serif; font-size:.82rem; font-weight:600; cursor:pointer; transition:all .2s; }
.or-filter-toggle:hover { border-color:var(--ink30); color:var(--ink); }

/* ══════════════════════
   § FORMATIONS GRID
══════════════════════ */
.or-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:1px; background:var(--ink10); border:1px solid var(--ink10); border-radius:var(--rl); overflow:hidden; margin-bottom:1.5rem; }

.or-card { background:var(--paper); display:flex; flex-direction:column; cursor:pointer; transition:background .25s var(--ease); position:relative; overflow:hidden; }
.or-card:hover { background:var(--cream); }

.or-card-stripe { height:3px; background:var(--stripe-color,var(--accent)); width:0; transition:width .4s var(--ease); }
.or-card:hover .or-card-stripe { width:100%; }

.or-card-body { padding:1.4rem 1.5rem 1.25rem; display:flex; flex-direction:column; flex:1; gap:.75rem; }

/* ── Row 1: badges + match chip ── */
.or-card-row-top { display:flex; justify-content:space-between; align-items:center; gap:.5rem; }
.or-card-badges { display:flex; flex-wrap:wrap; gap:.35rem; flex:1; }
.or-match-chip {
    display:flex; flex-direction:column; align-items:center; flex-shrink:0;
    padding:.3rem .65rem; border-radius:var(--r);
    background:color-mix(in srgb,var(--chip-color,var(--accent)) 10%,transparent);
    border:1px solid color-mix(in srgb,var(--chip-color,var(--accent)) 25%,transparent);
}
.or-match-num { font-family:'Fraunces',serif; font-size:1.1rem; font-weight:600; letter-spacing:-.04em; line-height:1; color:var(--chip-color,var(--accent)); }
.or-match-lbl { font-size:.55rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--ink30); }

/* ── Row 2: icon + name ── */
.or-card-identity { display:flex; align-items:center; gap:.75rem; }
.or-card-icon { width:38px; height:38px; border-radius:var(--r); flex-shrink:0; background:color-mix(in srgb,var(--icon-bg,var(--accent)) 10%,transparent); border:1px solid color-mix(in srgb,var(--icon-bg,var(--accent)) 20%,transparent); display:flex; align-items:center; justify-content:center; font-size:1.15rem; transition:transform .25s; }
.or-card:hover .or-card-icon { transform:scale(1.06); }
.or-card-name { font-family:'Fraunces',serif; font-size:.92rem; font-weight:600; letter-spacing:-.02em; line-height:1.3; color:var(--ink); }

/* ── Row 3: meta (établissement · ville · durée) ── */
.or-card-meta { display:flex; align-items:center; flex-wrap:wrap; gap:.3rem; }
.or-meta-item { font-size:.72rem; color:var(--ink60); font-weight:500; }
.or-meta-sep { font-size:.65rem; color:var(--ink30); }

/* ── Row 4: bar ── */
.or-bar-track { height:3px; background:var(--ink10); border-radius:var(--rx); overflow:hidden; }
.or-bar-fill { height:100%; border-radius:var(--rx); transition:width .9s var(--ease); }

/* ── Row 5: description ── */
.or-card-desc { font-size:.8rem; color:var(--ink60); line-height:1.65; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; margin:0; flex:1; }

/* ── Row 6: footer ── */
.or-card-footer { display:flex; justify-content:space-between; align-items:center; padding-top:.875rem; border-top:1px solid var(--ink10); margin-top:auto; gap:.5rem; }
.or-salary-label { font-size:.6rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--ink30); margin-bottom:.15rem; }
.or-salary-val { font-family:'Fraunces',serif; font-size:.9rem; font-weight:600; letter-spacing:-.02em; color:var(--accent); }
.or-card-btn { display:inline-flex; align-items:center; gap:.35rem; padding:.42rem .85rem; border-radius:var(--r); background:var(--paper); border:1px solid var(--ink10); font-family:'DM Sans',sans-serif; font-size:.75rem; font-weight:600; color:var(--ink60); cursor:pointer; transition:all .22s; white-space:nowrap; flex-shrink:0; }
.or-card:hover .or-card-btn { background:var(--accent); color:#fff; border-color:var(--accent); }
.btn-voeu { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:var(--r); background:var(--paper); border:1px solid var(--ink10); cursor:pointer; transition:all .22s; font-size:.9rem; }
.btn-voeu:hover { border-color:var(--ink30); transform:scale(1.05); }
.btn-voeu.active { background:color-mix(in srgb,var(--accent) 8%,transparent); border-color:color-mix(in srgb,var(--accent) 30%,transparent); }

/* ══════════════════════
   § EMPTY STATE
══════════════════════ */
.or-empty { padding:5rem 2rem; text-align:center; background:var(--cream); border:1px solid var(--ink10); border-radius:var(--rl); }
.or-empty-icon { font-size:3rem; margin-bottom:1.25rem; }
.or-empty-title { font-family:'Fraunces',serif; font-size:1.8rem; font-weight:300; letter-spacing:-.03em; margin-bottom:.625rem; }
.or-empty-sub { font-size:.9rem; color:var(--ink60); line-height:1.7; margin-bottom:2rem; }

/* ══════════════════════
   § PAGINATION
══════════════════════ */
.or-pagination { display:flex; justify-content:center; gap:.375rem; flex-wrap:wrap; margin-top:1.75rem; padding-bottom:1rem; }
.or-page-item { display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:var(--r); font-size:.82rem; font-weight:600; cursor:pointer; text-decoration:none; border:1px solid var(--ink10); background:var(--paper); color:var(--ink60); transition:all .2s; }
.or-page-item:hover { border-color:var(--ink30); color:var(--ink); }
.or-page-item.active { background:var(--accent); color:#fff; border-color:var(--accent); }
.or-page-item.disabled { opacity:.35; pointer-events:none; }
.or-page-wide { width:auto; padding:0 .875rem; }

/* ══════════════════════
   § FICHE MODAL
══════════════════════ */
.or-modal-backdrop { display:none; position:fixed; inset:0; z-index:2000; justify-content:center; align-items:center; padding:1rem; background:rgba(0,0,0,.55); backdrop-filter:blur(8px); }
.or-modal-backdrop.open { display:flex; }
.or-modal-panel { position:relative; width:100%; max-width:800px; max-height:90vh; overflow-y:auto; border-radius:20px; background:var(--paper); border:1px solid var(--ink15); box-shadow:0 32px 80px rgba(0,0,0,.35); transform:scale(.94) translateY(20px); opacity:0; transition:all .4s var(--ease); }
.or-modal-backdrop.open .or-modal-panel { transform:none; opacity:1; }
.or-modal-panel::-webkit-scrollbar { width:4px; }
.or-modal-panel::-webkit-scrollbar-thumb { background:var(--ink10); border-radius:4px; }
.or-modal-header { padding:2rem 2rem 1.5rem; border-bottom:1px solid var(--ink10); background:var(--cream); position:relative; }
.or-modal-close { position:absolute; top:1.25rem; right:1.25rem; width:32px; height:32px; border-radius:var(--r); background:var(--ink06); border:1px solid var(--ink10); color:var(--ink60); font-size:.9rem; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; }
.or-modal-close:hover { background:var(--ink10); color:var(--ink); }
.or-modal-icon { width:56px; height:56px; border-radius:var(--r); background:color-mix(in srgb,var(--accent) 10%,transparent); border:1px solid color-mix(in srgb,var(--accent) 22%,transparent); display:flex; align-items:center; justify-content:center; font-size:1.6rem; margin-bottom:1.25rem; }
.or-modal-title { font-family:'Fraunces',serif; font-size:1.6rem; font-weight:600; letter-spacing:-.03em; margin-bottom:.35rem; color:var(--ink); }
.or-modal-subtitle { font-size:.88rem; color:var(--ink60); }
.or-modal-tags { display:flex; flex-wrap:wrap; gap:.5rem; margin-top:1rem; }
.or-modal-score-row { display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap; padding:1.25rem 2rem; border-bottom:1px solid var(--ink10); background:var(--paper); }
.or-modal-score-big { font-family:'Fraunces',serif; font-size:3rem; font-weight:600; letter-spacing:-.05em; color:var(--accent); line-height:1; }
.or-modal-score-sub { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--ink30); }
.or-modal-bar-track { height:6px; background:var(--ink10); border-radius:var(--rx); overflow:hidden; flex:1; }
.or-modal-bar-fill { height:100%; background:var(--accent); border-radius:var(--rx); transition:width 1s var(--ease) .3s; }
.or-modal-body { padding:2rem; display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; }
.or-modal-section.full { grid-column:1 / -1; }
.or-modal-section-label { font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--ink30); margin-bottom:.75rem; }
.or-modal-text-box { background:var(--cream); border:1px solid var(--ink10); border-radius:var(--r); padding:1rem 1.125rem; font-size:.85rem; color:var(--ink60); line-height:1.75; }
.or-modal-list { display:flex; flex-direction:column; gap:.5rem; }
.or-modal-list-item { display:flex; align-items:flex-start; gap:.6rem; font-size:.83rem; color:var(--ink60); line-height:1.6; }
.or-modal-list-item::before { content:'✦'; color:var(--accent); flex-shrink:0; font-size:.75rem; margin-top:2px; }
.or-modal-kv { background:var(--cream); border:1px solid var(--ink10); border-radius:var(--r); padding:1rem 1.125rem; }
.or-modal-kv-val { font-family:'Fraunces',serif; font-size:1.3rem; font-weight:600; letter-spacing:-.03em; color:var(--accent); margin-bottom:.25rem; }
.or-modal-kv-sub { font-size:.72rem; color:var(--ink30); }
.or-modal-footer { padding:1.25rem 2rem; border-top:1px solid var(--ink10); display:flex; justify-content:flex-end; gap:.75rem; flex-wrap:wrap; background:var(--cream); }

/* ══════════════════════
   § RESPONSIVE
══════════════════════ */
@media (max-width:1100px) {
    .or-layout { grid-template-columns:240px 1fr; gap:1.25rem; }
}
@media (max-width:900px) {
    .or { padding:1.25rem 1.25rem 4rem; }
    .or-layout { grid-template-columns:1fr; }
    .or-sidebar { position:fixed; inset:0; z-index:1100; border-radius:0; transform:translateX(-100%); transition:transform .4s var(--ease); overflow-y:auto; max-width:300px; }
    .or-sidebar.open { transform:translateX(0); }
    .or-filter-toggle { display:flex; }
    .or-hero { padding:2.5rem 2rem 2rem; }
    .or-hero-bgword,.or-hero-orb { display:none; }
    .or-modal-body { grid-template-columns:1fr; }
}
@media (max-width:600px) {
    .or { padding:1rem 1rem 3rem; }
    .or-hero { padding:2rem 1.25rem; border-radius:var(--rl); }
    .or-grid { grid-template-columns:1fr; }
    .or-hero-actions { flex-direction:column; align-items:flex-start; }
    .or-results-count-big { font-size:1.5rem; }
}

/* ── Sidebar overlay (mobile) ── */
.or-sidebar-overlay { display:none; position:fixed; inset:0; z-index:1099; background:rgba(0,0,0,.45); backdrop-filter:blur(4px); }
.or-sidebar-overlay.open { display:block; }
</style>