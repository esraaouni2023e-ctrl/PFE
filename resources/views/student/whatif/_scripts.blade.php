<script>
(function(){
const CSRF=document.querySelector('meta[name="csrf-token"]')?.content??'';
const COLORS=['#FF6A00','#0057B8','#FF8C1A','#003B8E'];
let chartInstances={};

// ── Tab switching ──
document.querySelectorAll('.fs-tab').forEach(tab=>{
    tab.addEventListener('click',()=>{
        document.querySelectorAll('.fs-tab').forEach(t=>t.classList.remove('active'));
        document.querySelectorAll('.fs-section').forEach(s=>s.classList.remove('active'));
        tab.classList.add('active');
        document.getElementById('section-'+tab.dataset.tab).classList.add('active');
    });
});

// ── Module 1: Variation Notes ──
const secSel=document.getElementById('fs-section-bac');
const mgIn=document.getElementById('fs-mg');
const notesGrid=document.getElementById('fs-notes-grid');
const simBtn=document.getElementById('fs-sim-btn');

secSel?.addEventListener('change',async function(){
    const sec=this.value;
    if(!sec){notesGrid.innerHTML='<div style="padding:1rem;text-align:center;color:var(--ink30);font-size:.82rem">Sélectionnez une section</div>';simBtn.disabled=true;return;}
    notesGrid.innerHTML='<div style="padding:.75rem;text-align:center;color:var(--ink30)">Chargement…</div>';
    const r=await fetch(`{{ route('student.whatif.matieres') }}?section=${encodeURIComponent(sec)}`);
    const d=await r.json();
    notesGrid.innerHTML='';
    Object.entries(d.matieres).forEach(([code,info])=>{
        const div=document.createElement('div');div.className='fs-note-row';
        div.innerHTML=`<div class="fs-note-label">${info.label}</div><div class="fs-note-coef">×${info.coef}</div><input type="number" class="fs-note-input" name="notes[${code}]" min="0" max="20" step="0.25" placeholder="—">`;
        notesGrid.appendChild(div);
    });
    simBtn.disabled=false;
});
if(secSel?.value) secSel.dispatchEvent(new Event('change'));

simBtn?.addEventListener('click',async()=>{
    const sec=secSel.value, mg=parseFloat(mgIn.value);
    if(!sec||isNaN(mg)){showAlert('fs-alert1','Remplissez tous les champs.','error');return;}
    const notes={};let ok=true;
    document.querySelectorAll('#fs-notes-grid .fs-note-input').forEach(i=>{const m=i.name.match(/notes\[(.+)\]/);if(m){const v=parseFloat(i.value);if(isNaN(v)){ok=false;return;}notes[m[1]]=v;}});
    if(!ok){showAlert('fs-alert1','Remplissez toutes les notes.','error');return;}
    simBtn.disabled=true;simBtn.innerHTML='<svg style="width:1.1rem;height:1.1rem;animation:spin 1s linear infinite" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Calcul…';
    try{
        const res=await fetch('{{ route("student.whatif.calculer") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({section_bac:sec,moyenne_generale:mg,notes,label:document.getElementById('fs-label')?.value||null})});
        const data=await res.json();
        if(data.success) renderNotesResult(data);
        else showAlert('fs-alert1',data.message||'Erreur','error');
    }catch(e){showAlert('fs-alert1','Erreur réseau.','error');}
    finally{simBtn.disabled=false;simBtn.innerHTML='Simuler mon Score FG';}
});

function renderNotesResult(d){
    const el=document.getElementById('fs-result-notes');el.style.display='block';
    document.getElementById('fs-result-placeholder').style.display='none';
    const nc={excellent:'var(--accent3)',bon:'var(--gold)',moyen:'var(--accent)',faible:'#ef4444'};
    const c=nc[d.niveau]||'var(--accent)';
    document.getElementById('fs-score-val').textContent=d.score_fg.toFixed(2);
    document.getElementById('fs-score-val').style.color=c;
    document.getElementById('fs-niveau-badge').textContent=d.niveau.toUpperCase();
    document.getElementById('fs-niveau-badge').className='fs-badge '+(d.niveau==='excellent'?'fs-badge-green':d.niveau==='bon'?'fs-badge-gold':'fs-badge-red');
    const list=document.getElementById('fs-formations-list');list.innerHTML='';
    if(!d.formations?.length){list.innerHTML='<div style="text-align:center;padding:1rem;color:var(--ink30);font-size:.82rem">Aucune formation accessible.</div>';return;}
    d.formations.forEach(f=>{
        list.innerHTML+=`<div class="fs-card"><div class="fs-card-row"><div class="fs-card-icon">${f.icon||'📘'}</div><div class="fs-card-info"><div class="fs-card-title">${f.nom}</div><div class="fs-card-meta">${f.etablissement||''} · ${f.niveau||''} · ${f.duree||''}</div></div><div class="fs-card-val">${f.score_matching||0}%</div></div></div>`;
    });
}

// ── Module 2: Changement Spécialité ──
document.getElementById('fs-spec-btn')?.addEventListener('click',async()=>{
    const secA=document.getElementById('fs-spec-actuelle')?.value;
    const secN=document.getElementById('fs-spec-nouvelle')?.value;
    const mg=parseFloat(document.getElementById('fs-spec-mg')?.value);
    if(!secA||!secN||secA===secN||isNaN(mg)){showAlert('fs-alert2','Sélectionnez 2 sections différentes.','error');return;}
    const btn=document.getElementById('fs-spec-btn');btn.disabled=true;
    try{
        const r=await fetch('{{ route("student.whatif.simuler-avance") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({type:'changement_specialite',section_actuelle:secA,nouvelle_section:secN,moyenne_generale:mg,notes:{}})});
        const d=await r.json();
        if(d.success) renderSpecResult(d.data);
    }catch(e){}finally{btn.disabled=false;}
});

function renderSpecResult(d){
    const el=document.getElementById('fs-result-spec');el.style.display='block';
    el.innerHTML=`
    <div class="fs-row-compare">
        <div class="fs-score-box"><div class="fs-score-label">${d.section_actuelle}</div><span class="fs-score-num" style="font-size:2.4rem">${d.score_actuel.toFixed(1)}</span><div class="fs-badge ${d.niveau_actuel==='excellent'?'fs-badge-green':'fs-badge-gold'}">${d.niveau_actuel}</div></div>
        <div class="fs-vs">VS</div>
        <div class="fs-score-box"><div class="fs-score-label">${d.section_nouvelle}</div><span class="fs-score-num" style="font-size:2.4rem">${d.score_nouveau.toFixed(1)}</span><div class="fs-badge ${d.niveau_nouveau==='excellent'?'fs-badge-green':'fs-badge-gold'}">${d.niveau_nouveau}</div></div>
    </div>
    <div style="text-align:center;margin-top:.75rem"><span class="fs-delta ${d.delta>=0?'fs-delta-up':'fs-delta-down'}">${d.delta>=0?'▲':'▼'} ${Math.abs(d.delta).toFixed(1)} pts (${d.delta_pct>0?'+':''}${d.delta_pct}%)</span></div>
    <div class="fs-grid-2" style="margin-top:1rem"><div class="fs-stat"><div class="fs-stat-num">${d.formations_actuelles}</div><div class="fs-stat-label">Formations actuelles</div></div><div class="fs-stat"><div class="fs-stat-num">${d.formations_nouvelles}</div><div class="fs-stat-label">Formations nouvelles</div></div></div>`;
}

// ── Module 3: Filière Alternative ──
document.getElementById('fs-filiere-btn')?.addEventListener('click',async()=>{
    const ids=[...document.querySelectorAll('.fs-filiere-select')].map(s=>s.value).filter(v=>v);
    if(ids.length<2){showAlert('fs-alert3','Sélectionnez au moins 2 formations.','error');return;}
    const btn=document.getElementById('fs-filiere-btn');btn.disabled=true;
    try{
        const r=await fetch('{{ route("student.whatif.simuler-avance") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({type:'filiere_alternative',formation_ids:ids})});
        const d=await r.json();
        if(d.success) renderFiliereResult(d.data);
    }catch(e){}finally{btn.disabled=false;}
});

function renderFiliereResult(formations){
    const el=document.getElementById('fs-result-filiere');el.style.display='block';
    // Radar chart
    if(chartInstances.radar) chartInstances.radar.destroy();
    const canvas=document.getElementById('fs-radar-chart');
    chartInstances.radar=new Chart(canvas,{type:'radar',data:{labels:['Compatibilité','Salaire','Rapidité','Insertion','Difficulté'],datasets:formations.map((f,i)=>({label:f.nom.substring(0,20),data:[f.radar.compatibilite,f.radar.salaire,f.radar.rapidite,f.radar.insertion,f.radar.difficulte],backgroundColor:COLORS[i]+'22',borderColor:COLORS[i],pointBackgroundColor:COLORS[i],borderWidth:2,pointRadius:3}))},options:{responsive:true,maintainAspectRatio:false,scales:{r:{beginAtZero:true,max:100,grid:{color:'rgba(11,12,16,.06)'},pointLabels:{font:{size:10,family:"'DM Sans'"}},ticks:{display:false}}},plugins:{legend:{display:false}}}});
    // Cards
    const cards=document.getElementById('fs-filiere-cards');cards.innerHTML='';
    formations.forEach((f,i)=>{
        cards.innerHTML+=`<div class="fs-card" style="border-left:3px solid ${COLORS[i]}"><div class="fs-card-row"><div class="fs-card-icon" style="background:${COLORS[i]}15;border-color:${COLORS[i]}33">${f.icon||'📘'}</div><div class="fs-card-info"><div class="fs-card-title">${f.nom}</div><div class="fs-card-meta">${f.etablissement} · ${f.duree} · ${f.salaire_min}-${f.salaire_max} TND</div></div><div class="fs-card-val" style="color:${COLORS[i]}">${f.score_matching}%</div></div></div>`;
    });
}

// ── Module 4: Études Étranger ──
document.querySelectorAll('.fs-country-card').forEach(card=>{
    card.addEventListener('click',()=>{
        document.querySelectorAll('.fs-country-card').forEach(c=>c.classList.remove('selected'));
        card.classList.add('selected');
        document.getElementById('fs-pays-val').value=card.dataset.pays;
    });
});
document.getElementById('fs-etranger-btn')?.addEventListener('click',async()=>{
    const pays=document.getElementById('fs-pays-val')?.value;
    const duree=parseInt(document.getElementById('fs-etranger-duree')?.value)||3;
    if(!pays){showAlert('fs-alert4','Choisissez un pays.','error');return;}
    const btn=document.getElementById('fs-etranger-btn');btn.disabled=true;
    try{
        const r=await fetch('{{ route("student.whatif.simuler-avance") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({type:'etudes_etranger',pays,duree})});
        const d=await r.json();
        if(d.success) renderEtrangerResult(d.data);
    }catch(e){}finally{btn.disabled=false;}
});

function renderEtrangerResult(d){
    const el=document.getElementById('fs-result-etranger');el.style.display='block';
    el.innerHTML=`
    <div class="fs-row-compare">
        <div class="fs-score-box"><div class="fs-score-label">${d.pays.flag} ${d.pays.label}</div><span class="fs-score-num" style="font-size:2rem;color:var(--accent2)">${(d.cout_net).toLocaleString()} €</span><div class="fs-stat-label">Coût net (${d.duree} ans)</div></div>
        <div class="fs-vs">VS</div>
        <div class="fs-score-box"><div class="fs-score-label">${d.tunisie.flag} Tunisie</div><span class="fs-score-num" style="font-size:2rem;color:var(--accent3)">${d.cout_tunisie.toLocaleString()} €</span><div class="fs-stat-label">Coût total</div></div>
    </div>
    <div class="fs-grid-2">
        <div class="fs-stat"><div class="fs-stat-num" style="color:var(--accent2)">${d.salaire_debut_etr.toLocaleString()} €</div><div class="fs-stat-label">Salaire/an ${d.pays.label}</div></div>
        <div class="fs-stat"><div class="fs-stat-num" style="color:var(--accent3)">${d.salaire_debut_tn.toLocaleString()} €</div><div class="fs-stat-label">Salaire/an Tunisie</div></div>
        <div class="fs-stat"><div class="fs-stat-num">${d.taux_insertion_etr}%</div><div class="fs-stat-label">Insertion ${d.pays.label}</div></div>
        <div class="fs-stat"><div class="fs-stat-num">${d.breakeven_ans} ans</div><div class="fs-stat-label">Retour investissement</div></div>
    </div>
    <div style="margin-top:1rem">${d.pays.avantages.map(a=>'<span class="fs-badge fs-badge-blue" style="margin:.15rem">'+a+'</span>').join('')}</div>`;
}

// ── Module 5: Secteurs (pre-rendered) ──
function initSecteurs(){
    const data=@json($secteursData ?? []);
    const el=document.getElementById('fs-secteurs-list');if(!el||!data.length) return;
    el.innerHTML='';
    data.forEach(s=>{
        const riskColor=s.risque==='élevé'?'#ef4444':s.risque==='modéré'?'var(--gold)':'var(--accent3)';
        el.innerHTML+=`<div class="fs-card"><div class="fs-card-row"><div class="fs-card-icon">${s.icon}</div><div class="fs-card-info"><div class="fs-card-title">${s.label}</div><div class="fs-card-meta">Insertion: ${s.insertion}% · Croissance: +${s.croissance}%/an · Saturation: <span style="color:${riskColor};font-weight:700">${s.risque}</span></div><div class="fs-bar-wrap" style="margin-top:.4rem"><div class="fs-bar-fill" style="width:${s.insertion}%;background:var(--accent3)"></div></div></div><div style="text-align:right"><div class="fs-card-val">${s.projection_5ans}%</div><div style="font-size:.62rem;color:var(--ink30);font-weight:600">2030</div></div></div></div>`;
    });
}
initSecteurs();

// ── Module 6: ROI ──
document.getElementById('fs-roi-btn')?.addEventListener('click',async()=>{
    const niveau=document.getElementById('fs-roi-niveau')?.value||'licence';
    const fid=document.getElementById('fs-roi-formation')?.value||null;
    const btn=document.getElementById('fs-roi-btn');btn.disabled=true;
    try{
        const r=await fetch('{{ route("student.whatif.simuler-avance") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({type:'roi',niveau,formation_id:fid||null})});
        const d=await r.json();
        if(d.success) renderROI(d.data);
    }catch(e){}finally{btn.disabled=false;}
});

function renderROI(d){
    const el=document.getElementById('fs-result-roi');el.style.display='block';
    el.innerHTML=`
    <div class="fs-grid-2">
        <div class="fs-stat"><div class="fs-stat-num">${d.salaire_debut} TND</div><div class="fs-stat-label">Salaire début/mois</div></div>
        <div class="fs-stat"><div class="fs-stat-num">${d.salaire_5ans} TND</div><div class="fs-stat-label">Après 5 ans</div></div>
        <div class="fs-stat"><div class="fs-stat-num">${d.salaire_10ans} TND</div><div class="fs-stat-label">Après 10 ans</div></div>
        <div class="fs-stat"><div class="fs-stat-num">${d.cout_total} TND</div><div class="fs-stat-label">Coût études total</div></div>
    </div>
    <div class="fs-grid-2" style="margin-top:.75rem">
        <div class="fs-stat" style="border:1px solid color-mix(in srgb,var(--accent3) 30%,transparent)"><div class="fs-stat-num" style="color:var(--accent3)">+${d.roi_5ans}%</div><div class="fs-stat-label">ROI à 5 ans</div></div>
        <div class="fs-stat" style="border:1px solid color-mix(in srgb,var(--accent3) 30%,transparent)"><div class="fs-stat-num" style="color:var(--accent3)">+${d.roi_10ans}%</div><div class="fs-stat-label">ROI à 10 ans</div></div>
    </div>
    <div style="margin-top:1rem"><div class="fs-chart-wrap"><canvas id="fs-roi-chart"></canvas></div></div>`;
    // Line chart
    if(chartInstances.roi) chartInstances.roi.destroy();
    chartInstances.roi=new Chart(document.getElementById('fs-roi-chart'),{type:'line',data:{labels:d.evolution.map(e=>'An '+e.annee),datasets:[{label:'Salaire mensuel (TND)',data:d.evolution.map(e=>e.salaire),borderColor:'var(--accent)',backgroundColor:'rgba(255,106,0,.08)',fill:true,tension:.4,pointRadius:3,pointBackgroundColor:'var(--accent)'}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,grid:{color:'rgba(11,12,16,.05)'},ticks:{font:{size:10}}},x:{grid:{display:false},ticks:{font:{size:10}}}}}});
}

// ── Module 7: Compatibilité (pre-rendered) ──
function initCompatibilite(){
    const data=@json($compatibilite ?? []);
    const el=document.getElementById('fs-compat-list');if(!el) return;
    if(!data.has_profile){el.innerHTML='<div class="fs-placeholder"><div style="font-size:2.5rem;margin-bottom:.75rem">🧭</div><div style="font-size:.88rem;font-weight:500;color:var(--ink60)">'+data.message+'</div><a href="{{ route("student.pipeline") }}" class="fs-btn" style="max-width:260px;margin:1rem auto 0">Passer le test RIASEC</a></div>';return;}
    el.innerHTML=`<div class="fs-score-box"><div class="fs-score-label">Profil RIASEC dominant</div><span class="fs-score-num" style="font-size:2.4rem">${data.riasec_code}</span></div>`;
    data.top_secteurs.forEach(s=>{
        const w=s.compatibilite;
        el.innerHTML+=`<div class="fs-card"><div class="fs-card-row"><div class="fs-card-icon">${s.icon}</div><div class="fs-card-info"><div class="fs-card-title">${s.label}</div><div class="fs-bar-wrap"><div class="fs-bar-fill" style="width:${w}%;background:${w>=60?'var(--accent3)':w>=35?'var(--gold)':'var(--ink30)'}"></div></div></div><div class="fs-card-val" style="color:${w>=60?'var(--accent3)':'var(--gold)'}">${w}%</div></div></div>`;
    });
}
initCompatibilite();

// ── Helpers ──
function showAlert(id,msg,type){const el=document.getElementById(id);if(!el)return;el.textContent=msg;el.className='fs-alert '+type;el.style.display='block';setTimeout(()=>el.style.display='none',4000);}
})();
</script>
