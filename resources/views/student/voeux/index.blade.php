@extends('layouts.student')
@section('title', 'Mes Vœux d\'Orientation')

@section('content')
<style>
:root{--ink:#0b0c10;--paper:#f7f5f0;--cream:#ede9e1;--warm:#e8e1d4;--accent:#d4622a;--accent2:#1a4f6e;--accent3:#4a7c59;--gold:#c8973a;--ink60:rgba(11,12,16,.6);--ink30:rgba(11,12,16,.3);--ink15:rgba(11,12,16,.15);--ink10:rgba(11,12,16,.1);--ink06:rgba(11,12,16,.06);--r:8px;--rl:16px;--rx:999px;--ease:cubic-bezier(.16,1,.3,1)}
.vx{font-family:'DM Sans',sans-serif;color:var(--ink);background:var(--paper);padding:2rem 2.5rem 5rem;max-width:1000px;margin:0 auto}
.vx *,.vx *::before,.vx *::after{box-sizing:border-box;margin:0;padding:0}
/* Header */
.vx-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:2rem}
.vx-title{font-family:'Fraunces',serif;font-size:2.5rem;font-weight:300;letter-spacing:-.04em;line-height:1.1}
.vx-title em{font-style:italic;color:var(--accent)}
.vx-count{display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .9rem;border-radius:var(--rx);background:color-mix(in srgb,var(--accent) 10%,transparent);border:1px solid color-mix(in srgb,var(--accent) 22%,transparent);color:var(--accent);font-size:.78rem;font-weight:700}
/* CTA */
.vx-cta{display:inline-flex;align-items:center;gap:.5rem;padding:.75rem 1.4rem;border-radius:var(--r);background:var(--ink);color:var(--paper);font-family:'DM Sans',sans-serif;font-size:.88rem;font-weight:500;text-decoration:none;transition:all .3s var(--ease)}
.vx-cta:hover{background:color-mix(in srgb,var(--ink) 88%,var(--accent))}
/* Empty */
.vx-empty{text-align:center;padding:6rem 2rem;background:var(--cream);border:1px solid var(--ink10);border-radius:20px}
.vx-empty-icon{font-size:4rem;margin-bottom:1.5rem}
.vx-empty-title{font-family:'Fraunces',serif;font-size:2rem;font-weight:300;margin-bottom:.75rem;letter-spacing:-.03em}
.vx-empty-sub{font-size:.9rem;color:var(--ink60);line-height:1.7;margin-bottom:2rem}
/* Card list */
.vx-list{display:flex;flex-direction:column;gap:1px;background:var(--ink10);border:1px solid var(--ink10);border-radius:var(--rl);overflow:hidden}
.vx-card{background:var(--paper);display:flex;align-items:stretch;gap:0;transition:background .2s;position:relative}
.vx-card:hover{background:var(--cream)}
/* Drag handle */
.vx-drag{width:44px;display:flex;align-items:center;justify-content:center;cursor:grab;color:var(--ink30);font-size:1.1rem;flex-shrink:0;transition:color .2s}
.vx-drag:hover{color:var(--ink60)}
.vx-card.dragging{opacity:.5;box-shadow:0 8px 24px rgba(0,0,0,.15);z-index:100}
/* Priorité badge */
.vx-prio{width:40px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:var(--cream);border-right:1px solid var(--ink10)}
.vx-prio-num{font-family:'Fraunces',serif;font-size:1.3rem;font-weight:600;color:var(--ink30)}
/* Card content */
.vx-card-inner{display:flex;align-items:center;gap:1rem;padding:1.125rem 1.25rem;flex:1;min-width:0}
.vx-card-icon{width:44px;height:44px;border-radius:var(--r);background:color-mix(in srgb,var(--accent) 10%,transparent);border:1px solid color-mix(in srgb,var(--accent) 20%,transparent);display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0}
.vx-card-info{flex:1;min-width:0}
.vx-card-nom{font-size:.92rem;font-weight:600;color:var(--ink);line-height:1.3;margin-bottom:.2rem}
.vx-card-meta{font-size:.75rem;color:var(--ink60);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.vx-card-tags{display:flex;flex-wrap:wrap;gap:.3rem;margin-top:.4rem}
.vx-pill{display:inline-flex;align-items:center;gap:.25rem;padding:.2rem .6rem;border-radius:var(--rx);font-size:.68rem;font-weight:600;background:var(--ink06);color:var(--ink60);border:1px solid var(--ink10)}
/* Actions */
.vx-card-actions{display:flex;align-items:center;gap:.5rem;padding:.875rem 1rem;flex-shrink:0}
.vx-btn-confirm{padding:.45rem .75rem;border-radius:var(--r);background:color-mix(in srgb,var(--accent3) 10%,transparent);border:1px solid color-mix(in srgb,var(--accent3) 22%,transparent);color:var(--accent3);font-size:.72rem;font-weight:600;cursor:pointer;transition:all .2s;white-space:nowrap}
.vx-btn-confirm.confirmed{background:var(--accent3);color:#fff;border-color:var(--accent3)}
.vx-btn-del{width:32px;height:32px;border-radius:var(--r);background:color-mix(in srgb,#ef4444 8%,transparent);border:1px solid color-mix(in srgb,#ef4444 20%,transparent);color:#ef4444;font-size:.85rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s}
.vx-btn-del:hover{background:color-mix(in srgb,#ef4444 18%,transparent)}
/* Summary bar */
.vx-summary{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:2rem}
.vx-stat{background:var(--cream);border:1px solid var(--ink10);border-radius:var(--rl);padding:1.25rem;text-align:center}
.vx-stat-num{font-family:'Fraunces',serif;font-size:2rem;font-weight:600;color:var(--accent);letter-spacing:-.04em;display:block}
.vx-stat-lbl{font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.08em;color:var(--ink30);margin-top:.2rem;display:block}
/* Toast */
#vxToast{position:fixed;bottom:2rem;right:2rem;padding:.875rem 1.5rem;border-radius:var(--r);background:var(--ink);color:var(--paper);font-size:.85rem;font-weight:500;z-index:9999;opacity:0;transform:translateY(10px);transition:all .3s var(--ease);pointer-events:none}
#vxToast.show{opacity:1;transform:none}
</style>

<div class="vx">

    {{-- Header --}}
    <div class="vx-header">
        <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--accent);margin-bottom:.5rem;display:flex;align-items:center;gap:.5rem">
                <span style="width:18px;height:1px;background:var(--accent);display:inline-block"></span>
                Espace Orientation
            </div>
            <h1 class="vx-title">Mes <em>vœux</em><br>d'orientation</h1>
        </div>
        <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap">
            <span class="vx-count">❤️ {{ $voeux->count() }} vœu{{ $voeux->count() > 1 ? 'x' : '' }}</span>
            <a href="{{ route('student.orientation') }}" class="vx-cta">+ Ajouter des filières</a>
        </div>
    </div>

    @if($voeux->isEmpty())
        {{-- État vide --}}
        <div class="vx-empty">
            <div class="vx-empty-icon">💡</div>
            <h2 class="vx-empty-title">Aucun vœu enregistré</h2>
            <p class="vx-empty-sub">
                Parcourez les filières disponibles et cliquez sur le bouton ❤️<br>
                pour les ajouter à votre liste de vœux.
            </p>
            <a href="{{ route('student.orientation') }}" class="vx-cta">Explorer les filières →</a>
        </div>

    @else

        {{-- Summary stats --}}
        <div class="vx-summary">
            <div class="vx-stat">
                <span class="vx-stat-num">{{ $voeux->count() }}</span>
                <span class="vx-stat-lbl">Vœux au total</span>
            </div>
            <div class="vx-stat">
                <span class="vx-stat-num">{{ $voeux->where('est_confirme', true)->count() }}</span>
                <span class="vx-stat-lbl">Confirmés</span>
            </div>
            <div class="vx-stat">
                <span class="vx-stat-num">{{ $voeux->where('priorite', '>', 0)->count() }}</span>
                <span class="vx-stat-lbl">Classés par priorité</span>
            </div>
        </div>

        {{-- Drag & Drop info --}}
        <div style="display:flex;align-items:center;gap:.5rem;font-size:.78rem;color:var(--ink30);margin-bottom:1rem;font-weight:500">
            <span>⠿</span> Glissez-déposez les lignes pour réorganiser vos priorités
        </div>

        {{-- Liste des vœux --}}
        <div class="vx-list" id="voeuxList">
            @foreach($voeux as $voeu)
            @php $f = $voeu->formation; $spec = $f->specialite; @endphp
            <div class="vx-card" data-id="{{ $voeu->id }}" draggable="true">
                <div class="vx-drag">⠿</div>
                <div class="vx-prio">
                    <span class="vx-prio-num">{{ $voeu->priorite ?: '—' }}</span>
                </div>
                <div class="vx-card-inner">
                    <div class="vx-card-icon">{{ $f->icon }}</div>
                    <div class="vx-card-info">
                        <div class="vx-card-nom">{{ $f->nom }}</div>
                        <div class="vx-card-meta">🏛️ {{ $f->etablissement }} · 📍 {{ $f->ville }}</div>
                        <div class="vx-card-tags">
                            <span class="vx-pill">{{ $f->niveau }}</span>
                            <span class="vx-pill">⏱ {{ $f->duree }}</span>
                            <span class="vx-pill">{{ $spec->icon }} {{ $spec->domaine }}</span>
                            <span class="vx-pill" style="color:var(--accent3)">{{ $f->score_matching }}% match</span>
                        </div>
                    </div>
                </div>
                <div class="vx-card-actions">
                    <button class="vx-btn-confirm {{ $voeu->est_confirme ? 'confirmed' : '' }}"
                            data-voeu="{{ $voeu->id }}"
                            data-confirmed="{{ $voeu->est_confirme ? '1' : '0' }}"
                            onclick="toggleConfirm(this)">
                        {{ $voeu->est_confirme ? '✅ Confirmé' : 'Confirmer' }}
                    </button>
                    <button class="vx-btn-del" data-voeu="{{ $voeu->id }}" onclick="supprimerVoeu(this)" title="Supprimer">✕</button>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Export note --}}
        <div style="margin-top:1.5rem;padding:1rem 1.25rem;background:var(--cream);border:1px solid var(--ink10);border-radius:var(--r);font-size:.82rem;color:var(--ink60)">
            💡 <strong>Conseil :</strong> Classez vos vœux par ordre de préférence. Le vœu n°1 sera considéré comme votre premier choix.
        </div>

    @endif

</div>

<div id="vxToast"></div>

<script>
(function() {
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    // ── Toast ──
    function toast(msg, ok = true) {
        const t = document.getElementById('vxToast');
        t.textContent = msg;
        t.style.background = ok ? 'var(--ink)' : '#ef4444';
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3000);
    }

    // ── Supprimer un vœu ──
    window.supprimerVoeu = async function(btn) {
        if (!confirm('Retirer cette filière de vos vœux ?')) return;
        const id   = btn.dataset.voeu;
        const card = btn.closest('.vx-card');

        const res = await fetch(`/student/voeux/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
        });
        const data = await res.json();
        if (data.success) {
            card.style.height = card.offsetHeight + 'px';
            card.style.overflow = 'hidden';
            card.style.transition = 'all .3s ease';
            card.style.height = '0';
            card.style.opacity = '0';
            setTimeout(() => { card.remove(); updatePriorites(); }, 300);
            toast('❌ Vœu supprimé.');
        }
    };

    // ── Confirmer un vœu ──
    window.toggleConfirm = async function(btn) {
        const id        = btn.dataset.voeu;
        const confirmed = btn.dataset.confirmed === '1';
        const newVal    = !confirmed;

        const res = await fetch(`/student/voeux/${id}`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
            body: JSON.stringify({ est_confirme: newVal }),
        });
        const data = await res.json();
        if (data.success) {
            btn.dataset.confirmed = newVal ? '1' : '0';
            btn.textContent       = newVal ? '✅ Confirmé' : 'Confirmer';
            btn.classList.toggle('confirmed', newVal);
            toast(newVal ? '✅ Vœu confirmé !' : 'Vœu déconfirmé.');
        }
    };

    // ── Drag & Drop ──
    const list = document.getElementById('voeuxList');
    if (!list) return;

    let dragEl = null;

    list.addEventListener('dragstart', e => {
        dragEl = e.target.closest('.vx-card');
        dragEl?.classList.add('dragging');
    });
    list.addEventListener('dragend', e => {
        dragEl?.classList.remove('dragging');
        dragEl = null;
        saveOrder();
    });
    list.addEventListener('dragover', e => {
        e.preventDefault();
        const afterEl = getDragAfterEl(list, e.clientY);
        if (dragEl) {
            if (afterEl) list.insertBefore(dragEl, afterEl);
            else list.appendChild(dragEl);
        }
        updatePriorites();
    });

    function getDragAfterEl(container, y) {
        const els = [...container.querySelectorAll('.vx-card:not(.dragging)')];
        return els.reduce((closest, child) => {
            const box    = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) return { offset, element: child };
            return closest;
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function updatePriorites() {
        list.querySelectorAll('.vx-card').forEach((card, i) => {
            const badge = card.querySelector('.vx-prio-num');
            if (badge) badge.textContent = i + 1;
        });
    }

    async function saveOrder() {
        const ids = [...list.querySelectorAll('.vx-card')].map(c => parseInt(c.dataset.id));
        await fetch('/student/voeux/reordonner', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
            body: JSON.stringify({ ids }),
        });
        toast('📋 Priorités sauvegardées.');
    }
})();
</script>
@endsection
