@extends('layouts.student')
@section('title', 'Résultats RIASEC — ' . $profil->code_holland)

@section('content')
<style>
.res-page { padding: 2.5rem 2.5rem 5rem; max-width: 900px; margin: 0 auto; }

/* ── Eyebrow ── */
.res-eyebrow {
    font-size: .7rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
    color: var(--accent); margin-bottom: 1rem;
    display: flex; align-items: center; gap: .45rem;
}
.res-eyebrow::before { content:''; width:14px; height:1px; background:var(--accent); }

/* ── Trigram hero ── */
.trigram-row { display: flex; align-items: center; gap: .75rem; margin-bottom: 1rem; }
.trigram-letter {
    width: 60px; height: 60px; border-radius: var(--rl);
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-serif); font-size: 1.6rem;
    font-weight: 600; font-style: italic; color: #fff;
}

.res-title {
    font-family: var(--font-serif);
    font-size: clamp(1.6rem,3.5vw,2.4rem);
    font-weight: 300; letter-spacing: -.04em; font-style: italic;
    color: var(--ink); line-height: 1.15; margin-bottom: .6rem;
}
.res-desc { font-size: .9rem; color: var(--ink60); max-width: 580px; line-height: 1.7; margin-bottom: 1.5rem; }

.coherence-pill {
    display: inline-flex; align-items: center; gap: .45rem;
    padding: .3rem .85rem; border-radius: var(--rx);
    font-size: .72rem; font-weight: 700;
    background: var(--ink06); border: 1px solid var(--glass-border);
    color: var(--ink30);
}

/* ── Two-column charts ── */
.res-charts-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; margin-bottom: 1.5rem; }
@media(max-width:700px){ .res-charts-grid{ grid-template-columns:1fr; } }

.res-card {
    background: var(--ink06); border: 1px solid var(--glass-border);
    border-radius: var(--rl); padding: 1.4rem 1.6rem;
    transition: border-color .3s var(--ease);
}
.res-card:hover { border-color: var(--glass-border-vivid); }
.res-card-title {
    font-size: .68rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--ink30); margin-bottom: 1.1rem;
}

/* ── Score bars ── */
.score-row { display: flex; align-items: center; gap: .7rem; margin-bottom: .75rem; }
.score-row-label { font-size: .78rem; font-weight: 700; color: var(--ink60); width: 20px; flex-shrink: 0; text-align:center; }
.score-bar-track { flex: 1; height: 7px; background: var(--ink10); border-radius: var(--rx); overflow: hidden; }
.score-bar-fill  { height: 100%; border-radius: var(--rx); transition: width 1.2s cubic-bezier(.4,0,.2,1); }
.score-row-val   { font-size: .72rem; font-weight: 700; width: 34px; text-align: right; flex-shrink: 0; }

/* ── Dim profiles ── */
.dim-profiles { display: grid; grid-template-columns: repeat(3,1fr); gap: .85rem; margin-bottom: 1.5rem; }
@media(max-width:600px){ .dim-profiles{ grid-template-columns:1fr; } }

.dim-profile-card {
    background: var(--ink06); border: 1px solid var(--glass-border);
    border-radius: var(--rl); padding: 1.1rem 1.2rem;
    transition: border-color .3s var(--ease);
}
.dim-profile-card:hover { border-color: var(--glass-border-vivid); }
.dim-profile-top { display: flex; align-items: center; gap: .6rem; margin-bottom: .6rem; }
.dim-profile-letter { width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;color:#fff;flex-shrink:0; }
.dim-profile-name { font-size:.82rem;font-weight:700;color:var(--ink); }
.dim-profile-rank { font-size:.65rem;color:var(--ink30); }
.dim-profile-desc { font-size:.75rem;color:var(--ink60);line-height:1.55;margin-bottom:.7rem; }
.dim-forces { display:flex;flex-wrap:wrap;gap:.35rem; }
.force-tag {
    font-size:.65rem;font-weight:700;
    padding:.22rem .55rem;border-radius:var(--rx);
    background:color-mix(in srgb,var(--gold) 10%,transparent);
    border:1px solid color-mix(in srgb,var(--gold) 25%,transparent);
    color:var(--gold);
}

/* ── Pills ── */
.pills-row { display:flex;flex-wrap:wrap;gap:.5rem; }
.pill-metier {
    padding:.35rem .8rem;border-radius:var(--rx);font-size:.78rem;font-weight:600;
    background:color-mix(in srgb,var(--accent) 8%,transparent);
    border:1px solid color-mix(in srgb,var(--accent) 22%,transparent);
    color:var(--accent);
    transition:var(--transition);
}
.pill-metier:hover { background:color-mix(in srgb,var(--accent) 15%,transparent); }
.pill-filiere {
    padding:.35rem .8rem;border-radius:var(--rx);font-size:.78rem;font-weight:600;
    background:color-mix(in srgb,var(--accent3) 8%,transparent);
    border:1px solid color-mix(in srgb,var(--accent3) 22%,transparent);
    color:var(--accent3);
}

/* ── Actions ── */
.res-actions { display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;margin-top:2rem; }
.btn-fill {
    display:inline-flex;align-items:center;gap:.5rem;
    padding:.8rem 1.8rem;font-family:var(--font-main);font-size:.88rem;font-weight:600;color:#fff;
    background:var(--accent);border:none;border-radius:var(--r);cursor:pointer;text-decoration:none;
    box-shadow:0 4px 18px color-mix(in srgb,var(--accent) 30%,transparent);transition:var(--transition);
}
.btn-fill:hover { transform:translateY(-2px);box-shadow:0 8px 28px color-mix(in srgb,var(--accent) 42%,transparent); }
.btn-ghost {
    display:inline-flex;align-items:center;gap:.5rem;
    padding:.75rem 1.3rem;font-family:var(--font-main);font-size:.84rem;font-weight:600;color:var(--ink60);
    background:transparent;border:1px solid var(--glass-border);border-radius:var(--r);cursor:pointer;text-decoration:none;
    transition:var(--transition);
}
.btn-ghost:hover { color:var(--ink);border-color:var(--ink30);background:var(--ink06); }
</style>

<div class="res-page">

    @if(session('success'))
    <div style="background:color-mix(in srgb,var(--accent3) 8%,transparent);border:1px solid color-mix(in srgb,var(--accent3) 22%,transparent);color:var(--accent3);border-radius:var(--r);padding:.65rem 1rem;margin-bottom:1.5rem;font-size:.83rem;">
        ✅ {{ session('success') }}
    </div>
    @endif

    {{-- ── Eyebrow ── --}}
    <p class="res-eyebrow">Profil RIASEC · Code Holland</p>

    {{-- ── Trigram ── --}}
    @php
    $triColors = [
        'R'=>'#d4622a','I'=>'#1a4f6e','A'=>'#c8973a',
        'S'=>'#4a7c59','E'=>'#7c4a7c','C'=>'#4a6e6e'
    ];
    $letters = str_split($profil->code_holland);
    @endphp
    <div class="trigram-row">
        @foreach($letters as $i => $l)
        <div class="trigram-letter"
             style="background:{{ $triColors[$l] ?? 'var(--accent)' }};opacity:{{ 1 - $i*0.15 }}">
            {{ $l }}
        </div>
        @endforeach
    </div>

    <h1 class="res-title">{{ $profil->code_holland_libelle }}</h1>
    <p class="res-desc">
        {{ $interp['description'] ?? 'Ton profil unique reflète tes centres d\'intérêt, tes forces naturelles et les environnements où tu t\'épanouis le mieux.' }}
    </p>

    @if($profil->score_coherence !== null)
    <div class="coherence-pill" style="margin-bottom:2rem;">
        {{ ($profil->score_coherence ?? 0) >= 60 ? '✅' : '⚠️' }}
        Fiabilité du test : <strong style="color:{{ ($profil->score_coherence??0)>=60?'var(--accent3)':'var(--gold)' }}">
            {{ $profil->score_coherence }}%
        </strong>
        &nbsp;·&nbsp; {{ $profil->niveau_coherence ?? '' }}
    </div>
    @endif

    {{-- ── Graphiques ── --}}
    <div class="res-charts-grid">

        {{-- Radar --}}
        <div class="res-card">
            <p class="res-card-title">Graphique radar</p>
            <canvas id="radarChart" height="250"></canvas>
        </div>

        {{-- Barres --}}
        <div class="res-card">
            <p class="res-card-title">Scores par dimension</p>
            @php
            $barColors = ['R'=>'#d4622a','I'=>'#1a4f6e','A'=>'#c8973a','S'=>'#4a7c59','E'=>'#7c4a7c','C'=>'#4a6e6e'];
            $dimLabels = ['R'=>'Réaliste','I'=>'Investigateur','A'=>'Artistique','S'=>'Social','E'=>'Entreprenant','C'=>'Conventionnel'];
            $dimEmoji  = ['R'=>'🔧','I'=>'🔬','A'=>'🎨','S'=>'🤝','E'=>'🚀','C'=>'📋'];
            arsort($scoresSorted);
            @endphp
            @foreach($scoresSorted as $dim => $sc)
            @php $norm = $scores ? ($scores->normalizedScores[$dim] ?? $sc) : $sc; @endphp
            <div class="score-row">
                <span class="score-row-label">{{ $dimEmoji[$dim] }}</span>
                <div class="score-bar-track">
                    <div class="score-bar-fill"
                         style="width:0%;background:{{ $barColors[$dim] }}"
                         data-w="{{ round($norm) }}">
                    </div>
                </div>
                <span class="score-row-val" style="color:{{ $barColors[$dim] }}">{{ round($norm) }}%</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── Top 3 dimensions détaillées ── --}}
    <div class="dim-profiles">
        @foreach($dimProfiles as $i => $dimP)
        @if($dimP)
        @php $letter = $letters[$i] ?? '?'; $col = $triColors[$letter] ?? 'var(--accent)'; @endphp
        <div class="dim-profile-card">
            <div class="dim-profile-top">
                <div class="dim-profile-letter" style="background:{{ $col }}">{{ $letter }}</div>
                <div>
                    <div class="dim-profile-name">{{ $dimP['label'] }}</div>
                    <div class="dim-profile-rank">Dimension #{{ $i+1 }}</div>
                </div>
            </div>
            <p class="dim-profile-desc">{{ Str::limit($dimP['description'] ?? '', 120) }}</p>
            <div class="dim-forces">
                @foreach(array_slice($dimP['forces'] ?? [], 0, 3) as $f)
                <span class="force-tag">{{ $f }}</span>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
    </div>

    {{-- ── Métiers & Filières ── --}}
    <div class="res-charts-grid" style="margin-bottom:1.5rem;">
        <div class="res-card">
            <p class="res-card-title">💼 Métiers compatibles</p>
            <div class="pills-row">
                @forelse($interp['metiers_suggeres'] ?? [] as $m)
                <span class="pill-metier">{{ $m }}</span>
                @empty
                <p style="font-size:.8rem;color:var(--ink30);">Données en cours de calcul.</p>
                @endforelse
            </div>
        </div>
        <div class="res-card">
            <p class="res-card-title">🎓 Filières recommandées</p>
            <div class="pills-row">
                <a href="{{ route('recommendations.show') }}" class="pill-filiere" style="text-decoration:none; display:inline-block;">
                    ✨ Voir mes recommandations IA →
                </a>
            </div>
        </div>
    </div>

    {{-- ── Fiabilité ── --}}
    @if(!empty($interp['fiabilite'] ?? ''))
    <div class="res-card" style="display:flex;align-items:flex-start;gap:1rem;margin-bottom:1.5rem;">
        <span style="font-size:1.4rem;flex-shrink:0;">{{ ($profil->score_coherence??0)>=60?'✅':'⚠️' }}</span>
        <p style="font-size:.83rem;color:var(--ink60);line-height:1.6;">{{ $interp['fiabilite'] }}</p>
    </div>
    @endif

    {{-- ── Call to Action : Recommandations IA ── --}}
    <div style="margin-bottom:2rem; background: linear-gradient(135deg, var(--ink06) 0%, rgba(212,98,42,0.05) 100%); border: 1px solid var(--glass-border-vivid); border-radius: var(--rl); padding: 2rem; text-align: center;">
        <span style="background:var(--accent);color:#fff;border-radius:var(--rx);padding:.2rem .6rem;font-size:.7rem;font-weight:700;margin-bottom:1rem;display:inline-block;">
            NOUVEAU · CapAvenir IA
        </span>
        <h2 style="font-family:var(--font-serif);font-size:1.6rem;font-weight:600;color:var(--ink);margin-bottom:1rem;">
            Découvrez vos filières recommandées par l'IA
        </h2>
        <p style="font-size:.95rem;color:var(--ink60);max-width:600px;margin:0 auto 1.5rem;line-height:1.6;">
            Notre nouveau moteur d'intelligence artificielle croise vos résultats psychométriques complets (RIASEC, Big Five, GATB, Schwartz) avec vos performances académiques pour vous proposer les meilleures filières d'orientation en Tunisie.
        </p>
        <a href="{{ route('recommendations.show') }}" class="btn-fill" style="font-size: 1rem; padding: 1rem 2.5rem; box-shadow: 0 4px 20px rgba(212,98,42,0.4);">
            ✨ Générer mes recommandations IA
        </a>
    </div>

    {{-- ── Actions ── --}}
    <div class="res-actions">
        @auth
        <button onclick="saveProfile()" class="btn-fill">
            💾 Enregistrer mon profil
        </button>
        @endauth
        <form action="{{ route('riasec.initialize') }}" method="POST">
            @csrf <input type="hidden" name="restart" value="1">
            <button type="submit" class="btn-ghost"
                    onclick="return confirm('Recommencer le test ?')">
                🔄 Refaire le test
            </button>
        </form>
        <a href="{{ route('student.orientation') }}" class="btn-ghost">
            🗺️ Explorer les filières →
        </a>
    </div>

    <p style="font-size:.7rem;color:var(--ink30);margin-top:1.2rem;">
        Profil généré le {{ $profil->complete_at?->format('d/m/Y') ?? now()->format('d/m/Y') }}
        · Code Holland : <strong style="color:var(--accent)">{{ $profil->code_holland }}</strong>
    </p>
</div>

<script>
const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
const tc = isDark ? 'rgba(240,237,230,.4)' : 'rgba(11,12,16,.4)';
const gc = isDark ? 'rgba(240,237,230,.06)' : 'rgba(11,12,16,.06)';

// Radar
new Chart(document.getElementById('radarChart'), {
    type: 'radar',
    data: {
        labels: ['Réaliste','Investigateur','Artistique','Social','Entreprenant','Conventionnel'],
        datasets: [{
            data: [{{ $scoresSorted['R']??0 }},{{ $scoresSorted['I']??0 }},{{ $scoresSorted['A']??0 }},
                   {{ $scoresSorted['S']??0 }},{{ $scoresSorted['E']??0 }},{{ $scoresSorted['C']??0 }}],
            backgroundColor: 'rgba(212,98,42,.12)',
            borderColor: 'rgba(212,98,42,.75)',
            borderWidth: 2,
            pointBackgroundColor: ['#d4622a','#1a4f6e','#c8973a','#4a7c59','#7c4a7c','#4a6e6e'],
            pointRadius: 5,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { r: {
            min: 0, max: 100,
            ticks: { stepSize: 25, color: tc, font: { size: 9 } },
            grid: { color: gc },
            pointLabels: { color: tc, font: { size: 10 } },
            angleLines: { color: gc },
        }}
    }
});

// Animate bars
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('.score-bar-fill').forEach(b => {
            b.style.width = b.dataset.w + '%';
        });
    }, 300);
});

// Save profile
async function saveProfile() {
    try {
        const r = await fetch('/student/profil', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ code_riasec: '{{ $profil->code_holland }}' }),
        });
        alert(r.ok ? '✅ Profil enregistré !' : 'Erreur lors de l\'enregistrement.');
    } catch { alert('Erreur réseau.'); }
}
</script>
@endsection
