<style>
.fs{font-family:'DM Sans',sans-serif;color:var(--ink);background:var(--paper);padding:2rem 2.5rem 5rem;max-width:1300px;margin:0 auto}
.fs *,.fs *::before,.fs *::after{box-sizing:border-box;margin:0;padding:0}
.fs-hero{background:var(--cream);border:1px solid var(--ink10);border-radius:20px;padding:3rem;margin-bottom:2rem;position:relative;overflow:hidden}
.fs-hero-bg{position:absolute;font-family:'Fraunces',serif;font-weight:300;font-style:italic;font-size:8rem;color:transparent;-webkit-text-stroke:1px color-mix(in srgb,var(--ink) 5%,transparent);right:2%;top:50%;transform:translateY(-50%);pointer-events:none;user-select:none}
.fs-hero-inner{position:relative;z-index:2;max-width:600px}
.fs-eyebrow{font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--accent);display:flex;align-items:center;gap:.5rem;margin-bottom:1rem}
.fs-eyebrow::before{content:'';width:18px;height:1px;background:var(--accent)}
.fs-title{font-family:'Fraunces',serif;font-size:2.6rem;font-weight:300;letter-spacing:-.04em;line-height:1.1;margin-bottom:.75rem}
.fs-title em{font-style:italic;color:var(--accent)}
.fs-sub{font-size:.9rem;color:var(--ink60);line-height:1.7;max-width:480px}
.fs-tabs{display:flex;gap:.35rem;flex-wrap:wrap;margin-bottom:2rem;padding:.5rem;background:var(--cream);border-radius:12px;border:1px solid var(--ink10)}
.fs-tab{padding:.6rem 1rem;border-radius:8px;border:none;background:transparent;font-family:'DM Sans',sans-serif;font-size:.78rem;font-weight:600;color:var(--ink60);cursor:pointer;transition:all .25s;display:flex;align-items:center;gap:.4rem;white-space:nowrap}
.fs-tab:hover{color:var(--ink);background:var(--ink06)}
.fs-tab.active{color:#fff;background:var(--accent);box-shadow:0 4px 14px color-mix(in srgb,var(--accent) 30%,transparent)}
.fs-layout{display:grid;grid-template-columns:1fr 1fr;gap:1.75rem;align-items:start}
@media(max-width:900px){.fs-layout{grid-template-columns:1fr}}
.fs-panel{background:var(--paper);border:1px solid var(--ink10);border-radius:16px;overflow:hidden}
.fs-panel-head{padding:1.25rem 1.5rem;border-bottom:1px solid var(--ink10);background:var(--cream);display:flex;align-items:center;gap:.75rem}
.fs-panel-head h2{font-family:'Fraunces',serif;font-size:1.05rem;font-weight:600;letter-spacing:-.02em}
.fs-panel-body{padding:1.5rem}
.fs-field{margin-bottom:1.15rem}
.fs-label{display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ink30);margin-bottom:.45rem}
.fs-input,.fs-select{width:100%;padding:.7rem 1rem;background:var(--cream);border:1px solid var(--ink15);border-radius:8px;color:var(--ink);font-family:'DM Sans',sans-serif;font-size:.87rem;transition:border-color .2s}
.fs-input:focus,.fs-select:focus{outline:none;border-color:var(--accent)}
.fs-select{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center}
.fs-btn{width:100%;padding:.9rem;background:var(--accent);color:#fff;border:none;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:.9rem;font-weight:600;cursor:pointer;transition:all .3s;box-shadow:0 4px 16px color-mix(in srgb,var(--accent) 28%,transparent);display:flex;align-items:center;justify-content:center;gap:.6rem;margin-top:1rem}
.fs-btn:hover{transform:translateY(-2px);box-shadow:0 8px 24px color-mix(in srgb,var(--accent) 40%,transparent)}
.fs-btn:disabled{opacity:.5;cursor:not-allowed;transform:none}
.fs-btn-alt{background:var(--accent2);box-shadow:0 4px 16px color-mix(in srgb,var(--accent2) 28%,transparent)}
.fs-btn-alt:hover{box-shadow:0 8px 24px color-mix(in srgb,var(--accent2) 40%,transparent)}
.fs-section{display:none}.fs-section.active{display:block}
.fs-score-box{text-align:center;padding:2rem 1.5rem;background:var(--cream);border-radius:16px;border:1px solid var(--ink10);margin-bottom:1.25rem;animation:fsUp .5s ease}
@keyframes fsUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}
.fs-score-num{font-family:'Fraunces',serif;font-size:3.8rem;font-weight:600;letter-spacing:-.05em;line-height:1;color:var(--accent);display:block}
.fs-score-label{font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--ink30);margin-bottom:.4rem}
.fs-badge{display:inline-flex;padding:.25rem .75rem;border-radius:999px;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-top:.5rem}
.fs-badge-green{background:color-mix(in srgb,var(--accent3) 10%,transparent);color:var(--accent3);border:1px solid color-mix(in srgb,var(--accent3) 22%,transparent)}
.fs-badge-gold{background:color-mix(in srgb,var(--gold) 10%,transparent);color:var(--gold);border:1px solid color-mix(in srgb,var(--gold) 22%,transparent)}
.fs-badge-red{background:color-mix(in srgb,#ef4444 8%,transparent);color:#ef4444;border:1px solid color-mix(in srgb,#ef4444 20%,transparent)}
.fs-badge-blue{background:color-mix(in srgb,var(--accent2) 10%,transparent);color:var(--accent2);border:1px solid color-mix(in srgb,var(--accent2) 22%,transparent)}
.fs-card{padding:1rem;background:var(--cream);border-radius:10px;border:1px solid var(--ink10);margin-bottom:.75rem;transition:all .2s}
.fs-card:hover{border-color:var(--ink30)}
.fs-card-row{display:flex;align-items:center;gap:.75rem}
.fs-card-icon{width:36px;height:36px;border-radius:8px;background:color-mix(in srgb,var(--accent) 10%,transparent);border:1px solid color-mix(in srgb,var(--accent) 20%,transparent);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
.fs-card-info{flex:1;min-width:0}
.fs-card-title{font-size:.83rem;font-weight:600;color:var(--ink)}
.fs-card-meta{font-size:.72rem;color:var(--ink60);margin-top:.15rem}
.fs-card-val{font-family:'Fraunces',serif;font-size:1.1rem;font-weight:600;color:var(--accent);flex-shrink:0}
.fs-bar-wrap{height:6px;background:var(--ink10);border-radius:999px;overflow:hidden;margin-top:.5rem}
.fs-bar-fill{height:100%;border-radius:999px;transition:width .8s ease}
.fs-delta{display:inline-flex;align-items:center;gap:.3rem;padding:.2rem .6rem;border-radius:6px;font-size:.78rem;font-weight:700}
.fs-delta-up{color:var(--accent3);background:color-mix(in srgb,var(--accent3) 10%,transparent)}
.fs-delta-down{color:#ef4444;background:color-mix(in srgb,#ef4444 8%,transparent)}
.fs-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:.75rem}
@media(max-width:600px){.fs-grid-2{grid-template-columns:1fr}}
.fs-stat{text-align:center;padding:1rem;background:var(--cream);border-radius:10px;border:1px solid var(--ink10)}
.fs-stat-num{font-family:'Fraunces',serif;font-size:1.6rem;font-weight:600;color:var(--accent);line-height:1.2}
.fs-stat-label{font-size:.68rem;font-weight:600;color:var(--ink30);text-transform:uppercase;letter-spacing:.06em;margin-top:.25rem}
.fs-placeholder{text-align:center;padding:2.5rem 1.5rem;color:var(--ink30)}
.fs-placeholder svg{width:3rem;height:3rem;margin:0 auto .75rem;opacity:.2}
.fs-chart-wrap{position:relative;height:280px}
.fs-notes-grid{display:flex;flex-direction:column;gap:.6rem;margin-top:.4rem}
.fs-note-row{display:flex;align-items:center;gap:.6rem;padding:.6rem .85rem;background:var(--cream);border-radius:8px;border:1px solid var(--ink10)}
.fs-note-label{flex:1;font-size:.8rem;font-weight:500;color:var(--ink60)}
.fs-note-coef{font-size:.66rem;font-weight:700;color:var(--accent);letter-spacing:.04em;width:35px;text-align:center}
.fs-note-input{width:72px;padding:.4rem .6rem;background:var(--paper);border:1px solid var(--ink15);border-radius:6px;text-align:center;font-family:'Fraunces',serif;font-size:.95rem;font-weight:600;color:var(--ink)}
.fs-note-input:focus{outline:none;border-color:var(--accent)}
.fs-alert{padding:.85rem 1rem;border-radius:8px;font-size:.83rem;font-weight:500;display:none;margin-bottom:1rem}
.fs-alert.error{background:color-mix(in srgb,#ef4444 8%,transparent);border:1px solid color-mix(in srgb,#ef4444 20%,transparent);color:#ef4444}
.fs-row-compare{display:grid;grid-template-columns:1fr auto 1fr;gap:1rem;align-items:center;margin-bottom:1rem}
.fs-vs{font-family:'Fraunces',serif;font-size:1.2rem;font-weight:600;color:var(--ink30)}
.fs-country-card{padding:1.25rem;background:var(--cream);border-radius:12px;border:1px solid var(--ink10);text-align:center;transition:all .2s}
.fs-country-card.selected{border-color:var(--accent);box-shadow:0 0 0 2px color-mix(in srgb,var(--accent) 20%,transparent)}
.fs-country-flag{font-size:2rem;margin-bottom:.4rem}
.fs-country-name{font-size:.82rem;font-weight:600;color:var(--ink)}
.fs-hist-item{display:flex;align-items:center;gap:.6rem;padding:.55rem .75rem;background:var(--cream);border-radius:8px;border:1px solid var(--ink10);margin-bottom:.4rem;cursor:pointer;transition:all .2s}
.fs-hist-item:hover{border-color:var(--ink30)}
.fs-hist-score{font-family:'Fraunces',serif;font-size:.95rem;font-weight:600;color:var(--accent);width:45px;flex-shrink:0}
.fs-hist-info{flex:1}.fs-hist-section{font-size:.76rem;font-weight:600;color:var(--ink)}.fs-hist-date{font-size:.66rem;color:var(--ink30)}
@keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}
</style>
