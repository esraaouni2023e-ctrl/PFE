<script>
(function () {
    /* ── Scroll reveal ── */
    const revEls = document.querySelectorAll('#orRoot .rev');
    const revObs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); revObs.unobserve(e.target); } });
    }, { threshold: .08, rootMargin: '0px 0px -40px 0px' });
    revEls.forEach(el => revObs.observe(el));

    /* ── Match bar animate ── */
    const barObs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            const b = e.target, w = b.style.width;
            b.style.width = '0';
            setTimeout(() => { b.style.width = w; }, 120);
            barObs.unobserve(b);
        });
    }, { threshold: .3 });
    document.querySelectorAll('.or-bar-fill').forEach(b => barObs.observe(b));

    /* ── Mobile sidebar toggle ── */
    const sidebar  = document.getElementById('orSidebar');
    const toggle   = document.getElementById('filterToggle');
    const overlay  = document.createElement('div');
    overlay.className = 'or-sidebar-overlay';
    document.body.appendChild(overlay);

    const openSidebar  = () => { sidebar.classList.add('open');  overlay.classList.add('open');  document.body.style.overflow = 'hidden'; };
    const closeSidebar = () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); document.body.style.overflow = ''; };

    toggle?.addEventListener('click', openSidebar);
    overlay.addEventListener('click', closeSidebar);

    /* ── Fiche modal ── */
    const modal   = document.getElementById('ficheModal');
    const panel   = document.getElementById('fichePanel');
    const content = document.getElementById('ficheContent');

    const openModal  = () => { modal.classList.add('open');  document.body.style.overflow = 'hidden'; };
    const closeModal = () => {
        modal.classList.remove('open');
        setTimeout(() => { content.innerHTML = ''; document.body.style.overflow = ''; }, 400);
    };

    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    function niveauPillClass(n) {
        return { Licence:'pill-sage', Master:'pill-marine', 'Ingénierie':'pill-accent', Doctorat:'pill-gold' }[n] || 'pill-ink';
    }

    function matchColor(score) {
        return score >= 80 ? 'var(--accent3)' : score >= 60 ? 'var(--gold)' : 'var(--accent)';
    }

    function renderFiche(d) {
        const circ  = 2 * Math.PI * 40;
        const offset = circ * (1 - d.score_matching / 100);
        const mc     = matchColor(d.score_matching);

        return `
        <div class="or-modal-header">
            <button class="or-modal-close" id="ficheClose">✕</button>
            <div style="display:flex;flex-wrap:wrap;gap:1rem;align-items:flex-start;">
                <div class="or-modal-icon">${d.icon}</div>
                <div style="flex:1;min-width:200px;">
                    <div class="or-modal-tags" style="margin:0 0 .75rem;">
                        <span class="pill ${niveauPillClass(d.niveau)}">${d.niveau}</span>
                        <span class="pill pill-ink">${d.specialite_icon} ${d.specialite_domaine}</span>
                        <span class="pill pill-ink">⏱ ${d.duree}</span>
                    </div>
                    <div class="or-modal-title">${d.nom}</div>
                    <div class="or-modal-subtitle">🏛️ ${d.etablissement} — 📍 ${d.ville}</div>
                </div>
            </div>
        </div>

        <div class="or-modal-score-row">
            <div>
                <div class="or-modal-score-big" style="color:${mc}">${d.score_matching}%</div>
                <div class="or-modal-score-sub">Compatibilité IA</div>
            </div>
            <div class="or-modal-bar-track" style="flex:1;">
                <div class="or-modal-bar-fill" style="width:${d.score_matching}%;background:${mc};"></div>
            </div>
            <svg width="88" height="88" style="flex-shrink:0;">
                <defs><linearGradient id="ringG" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#d4622a"/>
                    <stop offset="100%" stop-color="#1a4f6e"/>
                </linearGradient></defs>
                <g transform="rotate(-90 44 44)">
                    <circle cx="44" cy="44" r="40" fill="none" stroke-width="6" stroke="rgba(11,12,16,.07)"/>
                    <circle cx="44" cy="44" r="40" fill="none" stroke="url(#ringG)" stroke-width="6"
                        stroke-linecap="round" stroke-dasharray="${circ}" stroke-dashoffset="${offset}"
                        style="transition:stroke-dashoffset 1.2s cubic-bezier(.16,1,.3,1) .3s;"/>
                </g>
            </svg>
        </div>

        <div class="or-modal-body">
            <div class="or-modal-section full">
                <div class="or-modal-section-label">📝 Description</div>
                <div class="or-modal-text-box">${d.description}</div>
            </div>
            <div class="or-modal-section">
                <div class="or-modal-section-label">🚀 Débouchés</div>
                <div class="or-modal-text-box">
                    <div class="or-modal-list">
                        ${d.debouches.split(',').map(s => `<div class="or-modal-list-item">${s.trim()}</div>`).join('')}
                    </div>
                </div>
            </div>
            <div class="or-modal-section">
                <div class="or-modal-section-label">📋 Conditions d'accès</div>
                <div class="or-modal-text-box">
                    <div class="or-modal-list">
                        ${d.conditions_acces.split('.').filter(s => s.trim()).map(s => `<div class="or-modal-list-item">${s.trim()}</div>`).join('')}
                    </div>
                </div>
            </div>
            <div class="or-modal-section">
                <div class="or-modal-section-label">💰 Salaire estimé</div>
                <div class="or-modal-kv">
                    <div class="or-modal-kv-val">${d.salaire_min} → ${d.salaire_max}</div>
                    <div class="or-modal-kv-sub">/mois · après quelques années d'expérience</div>
                </div>
            </div>
            <div class="or-modal-section">
                <div class="or-modal-section-label">🏢 Secteur</div>
                <div class="or-modal-kv">
                    <div class="or-modal-kv-val" style="font-size:1rem;">${d.secteur}</div>
                    <div class="or-modal-kv-sub">Spécialité : ${d.specialite_nom}</div>
                </div>
            </div>
        </div>

        <div class="or-modal-footer">
            <button class="btn-ghost" id="ficheCloseBtn">← Retour</button>
            <button class="btn-fill" onclick="alert('⭐ Fonctionnalité disponible bientôt !')">⭐ Sauvegarder</button>
        </div>`;
    }

    function attachCard(el) {
        el.addEventListener('click', function (e) {
            e.stopPropagation();
            const id  = this.dataset.id;
            const raw = document.getElementById('fiche-data-' + id);
            if (!raw) return;
            try {
                const data = JSON.parse(raw.textContent);
                content.innerHTML = renderFiche(data);
                setTimeout(() => {
                    const fill = panel.querySelector('.or-modal-bar-fill');
                    if (fill) { const w = fill.style.width; fill.style.width = '0'; setTimeout(() => { fill.style.width = w; }, 80); }
                }, 50);
                openModal();
                document.getElementById('ficheClose')?.addEventListener('click', closeModal);
                document.getElementById('ficheCloseBtn')?.addEventListener('click', closeModal);
            } catch {
                content.innerHTML = '<div style="padding:2rem;text-align:center;color:#ef4444;">Erreur de chargement.</div>';
                openModal();
            }
        });
    }

    document.querySelectorAll('.btn-fiche, .or-card').forEach(attachCard);

    /* ── Search live debounce ── */
    const searchInput = document.getElementById('searchInput');
    const searchForm  = document.getElementById('searchForm');
    let searchTimer;
    searchInput?.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => searchForm.submit(), 600);
    });

    /* ── Toggle Voeu AJAX ── */
    window.toggleVoeu = async function(btn, formationId) {
        btn.disabled = true;
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        try {
            const res = await fetch(`/student/voeux/toggle/${formationId}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
            });
            const data = await res.json();
            if (data.success) {
                const isActive = data.action === 'added';
                btn.classList.toggle('active', isActive);
                btn.textContent = isActive ? '❤️' : '🤍';
            }
        } catch(e) {
            console.error('Erreur toggle voeu', e);
        } finally {
            btn.disabled = false;
        }
    };

})();
</script>