@extends('layouts.admin')
@section('title', 'Questions RIASEC')

@section('content')
<style>
.filters-bar {
    display:flex; align-items:center; gap:.7rem; flex-wrap:wrap;
    margin-bottom:1.8rem;
}
.filter-select, .filter-input {
    font-family:var(--font-main); font-size:.8rem; font-weight:500;
    background:var(--ink06); border:1px solid var(--glass-border);
    color:var(--ink); border-radius:var(--r);
    padding:.45rem .8rem; outline:none; transition:var(--transition);
}
.filter-select:focus, .filter-input:focus { border-color:var(--accent); }
.filter-input { min-width:200px; }

.q-table { width:100%; border-collapse:collapse; }
.q-table th {
    font-size:.68rem; font-weight:700; letter-spacing:.08em;
    text-transform:uppercase; color:var(--ink30);
    padding:.65rem 1rem; text-align:left;
    border-bottom:2px solid var(--glass-border); white-space:nowrap;
}
.q-table td {
    padding:.75rem 1rem; font-size:.82rem;
    color:var(--ink60); border-bottom:1px solid var(--ink06);
    vertical-align:middle; transition:color .2s;
}
.q-table tr:hover td { color:var(--ink); background:var(--ink06); }
.q-table tr:last-child td { border-bottom:none; }

.q-text { max-width:380px; line-height:1.45; }

.dim-dot {
    display:inline-flex; align-items:center; justify-content:center;
    width:30px; height:30px; border-radius:8px;
    font-weight:800; font-size:.85rem; color:#fff; flex-shrink:0;
}

.act-toggle {
    display:inline-block; width:38px; height:20px; position:relative; cursor:pointer;
}
.act-toggle input { display:none; }
.act-toggle-track {
    width:100%; height:100%; border-radius:99px;
    background:var(--ink15); transition:background .3s;
}
.act-toggle input:checked ~ .act-toggle-track { background:var(--accent3); }
.act-toggle-thumb {
    position:absolute; top:2px; left:2px;
    width:16px; height:16px; border-radius:50%;
    background:#fff; transition:transform .3s var(--ease); box-shadow:0 1px 4px rgba(0,0,0,.25);
}
.act-toggle input:checked ~ .act-toggle-thumb { transform:translateX(18px); }

.btn-icon {
    display:inline-flex; align-items:center; justify-content:center;
    width:30px; height:30px; border-radius:var(--r);
    border:1px solid var(--glass-border); background:var(--ink06);
    cursor:pointer; transition:var(--transition); color:var(--ink60);
    font-size:.85rem;
}
.btn-icon:hover { border-color:var(--ink30); color:var(--ink); background:var(--ink10); }
.btn-icon.danger:hover { border-color:var(--red); color:var(--red); background:color-mix(in srgb,var(--red) 8%,transparent); }

.dim-count-bar {
    display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap;
    margin-bottom:1.8rem;
}
.dim-count-item {
    display:flex; align-items:center; gap:.5rem;
    font-size:.8rem; font-weight:600; color:var(--ink60);
}
</style>

{{-- Flash --}}
@if(session('success'))
<div style="background:color-mix(in srgb,var(--accent3) 12%,transparent);border:1px solid color-mix(in srgb,var(--accent3) 25%,transparent);color:var(--accent3);border-radius:var(--r);padding:.65rem 1rem;margin-bottom:1.5rem;font-size:.83rem;">
    ✅ {{ session('success') }}
</div>
@endif

{{-- ── Header actions ──────────────────────────────────────────────────── --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.8rem;">
    <div class="dim-count-bar">
        @php
        $dimColors2 = ['R'=>'#f97316','I'=>'#3b82f6','A'=>'#ec4899','S'=>'#10b981','E'=>'#8b5cf6','C'=>'#94a3b8'];
        $dimEmojis2 = ['R'=>'🔧','I'=>'🔬','A'=>'🎨','S'=>'🤝','E'=>'🚀','C'=>'📋'];
        @endphp
        @foreach($dimColors2 as $d => $color)
        <div class="dim-count-item">
            <div class="dim-dot" style="background:{{ $color }};width:24px;height:24px;border-radius:6px;font-size:.75rem;">
                {{ $d }}
            </div>
            <span>{{ $stats['byDim'][$d] ?? 0 }}</span>
        </div>
        @endforeach
        <span style="font-size:.75rem;color:var(--ink30);">Total : {{ $stats['total'] }} · Actives : {{ $stats['actives'] }}</span>
    </div>
    <div style="display:flex;gap:.6rem;">
        <a href="{{ route('admin.riasec.questions.create') }}" class="btn-primary">＋ Nouvelle question</a>
        <a href="{{ route('admin.riasec.export') }}" class="btn-glass">📥 CSV</a>
        <a href="{{ route('admin.riasec.dashboard') }}" class="btn-glass">📊 Dashboard</a>
    </div>
</div>

{{-- ── Filtres ──────────────────────────────────────────────────────────── --}}
<form method="GET" action="{{ route('admin.riasec.questions.index') }}" class="filters-bar">
    <input type="text" name="q" placeholder="Rechercher dans le texte…"
           value="{{ request('q') }}" class="filter-input">

    <select name="dimension" class="filter-select">
        <option value="">Toutes les dimensions</option>
        @foreach(['R','I','A','S','E','C'] as $d)
        <option value="{{ $d }}" {{ request('dimension')===$d?'selected':'' }}>{{ $d }} — {{ ['R'=>'Réaliste','I'=>'Investigateur','A'=>'Artistique','S'=>'Social','E'=>'Entreprenant','C'=>'Conventionnel'][$d] }}</option>
        @endforeach
    </select>

    <select name="categorie" class="filter-select">
        <option value="">Toutes catégories</option>
        <option value="loisirs" {{ request('categorie')==='loisirs'?'selected':'' }}>Loisirs</option>
        <option value="preferences_professionnelles" {{ request('categorie')==='preferences_professionnelles'?'selected':'' }}>Préférences pro.</option>
        <option value="qualites_personnelles" {{ request('categorie')==='qualites_personnelles'?'selected':'' }}>Qualités personnelles</option>
    </select>

    <select name="actif" class="filter-select">
        <option value="">Toutes (actif/inactif)</option>
        <option value="1" {{ request('actif')==='1'?'selected':'' }}>Actives seulement</option>
        <option value="0" {{ request('actif')==='0'?'selected':'' }}>Inactives seulement</option>
    </select>

    <button type="submit" class="btn-primary" style="padding:.45rem 1rem;">Filtrer</button>
    @if(request()->hasAny(['q','dimension','categorie','actif']))
    <a href="{{ route('admin.riasec.questions.index') }}" class="btn-glass" style="padding:.45rem .9rem;font-size:.78rem;">✕ Réinitialiser</a>
    @endif
</form>

{{-- ── Tableau ──────────────────────────────────────────────────────────── --}}
<div class="glass-card" style="padding:0;overflow:hidden;">
    <table class="q-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Dim.</th>
                <th>Catégorie</th>
                <th>Texte de la question</th>
                <th>Poids</th>
                <th>Ordre</th>
                <th>Type</th>
                <th>Actif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($questions as $q)
            @php
            $catLabels = ['loisirs'=>'Loisirs','preferences_professionnelles'=>'Pref. Pro','qualites_personnelles'=>'Qualités'];
            $catColors = ['loisirs'=>'var(--gold)','preferences_professionnelles'=>'var(--accent2)','qualites_personnelles'=>'var(--accent3)'];
            @endphp
            <tr>
                <td style="color:var(--ink30);font-size:.75rem;">{{ $q->id }}</td>

                <td>
                    <div class="dim-dot" style="background:{{ $dimColors2[$q->dimension] ?? '#888' }}">
                        {{ $q->dimension }}
                    </div>
                </td>

                <td>
                    <span style="font-size:.7rem;font-weight:700;color:{{ $catColors[$q->categorie] ?? 'var(--ink30)' }}">
                        {{ $catLabels[$q->categorie] ?? $q->categorie }}
                    </span>
                </td>

                <td class="q-text">{{ Str::limit($q->texte_fr, 80) }}</td>

                <td>
                    <span style="font-weight:700;color:{{ $q->poids > 1 ? 'var(--gold)' : 'var(--ink30)' }}">
                        {{ $q->poids }}{{ $q->poids > 1 ? ' ⭐' : '' }}
                    </span>
                </td>

                <td style="font-size:.75rem;color:var(--ink30);">{{ $q->ordre }}</td>

                <td>
                    <span style="font-size:.7rem;font-weight:600;color:var(--ink30);">
                        {{ strtoupper($q->type_reponse) }}
                    </span>
                </td>

                <td>
                    <form action="{{ route('admin.riasec.questions.toggle', $q) }}" method="POST">
                        @csrf
                        <label class="act-toggle" title="{{ $q->actif ? 'Désactiver' : 'Activer' }}">
                            <input type="checkbox" {{ $q->actif ? 'checked' : '' }}
                                   onchange="this.closest('form').submit()">
                            <div class="act-toggle-track"></div>
                            <div class="act-toggle-thumb"></div>
                        </label>
                    </form>
                </td>

                <td>
                    <div style="display:flex;align-items:center;gap:.4rem;">
                        <a href="{{ route('admin.riasec.questions.edit', $q) }}"
                           class="btn-icon" title="Modifier">✏️</a>

                        <form action="{{ route('admin.riasec.questions.destroy', $q) }}" method="POST"
                              onsubmit="return confirm('Supprimer cette question ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon danger" title="Supprimer">🗑️</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center;padding:3rem;color:var(--ink30);">
                    Aucune question trouvée.
                    <a href="{{ route('admin.riasec.questions.create') }}" style="color:var(--accent);margin-left:.5rem;">
                        Créer la première →
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($questions->hasPages())
<div style="margin-top:1.5rem;display:flex;justify-content:center;">
    {{ $questions->links() }}
</div>
@endif
@endsection
