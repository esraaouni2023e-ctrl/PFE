@extends('layouts.admin')

@section('title', 'Référentiel Universitaire')

@section('content')
<style>
.ref-container { padding: 2rem; }
.ref-card {
    background: var(--paper); border: 1px solid var(--ink10);
    border-radius: var(--rl); padding: 1.5rem; margin-bottom: 1.5rem;
}
.ref-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
.ref-title { font-family: 'Fraunces', serif; font-size: 1.25rem; }
.ref-form-group { margin-bottom: 1rem; }
.ref-input { width: 100%; padding: 0.5rem; border: 1px solid var(--ink10); border-radius: var(--r); }
.ref-btn { background: var(--ink); color: #fff; padding: 0.5rem 1rem; border-radius: var(--r); cursor: pointer; border: none; }
.ref-btn-danger { background: var(--red); color: #fff; padding: 0.3rem 0.6rem; border-radius: var(--r); border: none; cursor: pointer; font-size: 0.8rem; }
.ref-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
.ref-table th, .ref-table td { padding: 0.5rem; border-bottom: 1px solid var(--ink10); text-align: left; }
.ref-table th { font-weight: 600; font-size: 0.85rem; color: var(--ink60); }
</style>

<div class="ref-container">
    <div class="ref-header">
        <h2 class="ad-sh">Référentiel <em>Universitaire</em></h2>
    </div>

    @if(session('success'))
        <div style="background:var(--accent3); color:#fff; padding:1rem; border-radius:var(--r); margin-bottom:1rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="ref-card">
        <h3 class="ref-title">Ajouter une Filière</h3>
        <form action="{{ route('admin.references.store') }}" method="POST">
            @csrf
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="ref-form-group">
                    <label>Nom de la filière</label>
                    <input type="text" name="name" class="ref-input" required placeholder="ex: Médecine">
                </div>
                <div class="ref-form-group">
                    <label>Score BAC Minimum (sur 20)</label>
                    <input type="number" step="0.01" name="required_bac_score" class="ref-input" required value="10">
                </div>
            </div>
            <div class="ref-form-group">
                <label>Description</label>
                <textarea name="description" class="ref-input"></textarea>
            </div>
            <button type="submit" class="ref-btn">Créer la filière</button>
        </form>
    </div>

    @foreach($sections as $section)
        <div class="ref-card">
            <div class="ref-header">
                <h3 class="ref-title">{{ $section->name }} <span style="font-size:0.8rem; color:var(--ink60); font-family:'DM Sans';">(Score Min: {{ $section->required_bac_score }})</span></h3>
                <form action="{{ route('admin.references.destroy', $section) }}" method="POST" onsubmit="return confirm('Supprimer cette filière ?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="ref-btn-danger">Supprimer la filière</button>
                </form>
            </div>
            <p style="margin-bottom:1rem; color:var(--ink60); font-size:0.9rem;">{{ $section->description }}</p>

            <table class="ref-table">
                <thead>
                    <tr>
                        <th>Matière (Critère)</th>
                        <th>Coefficient</th>
                        <th style="width:100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($section->criteria as $criterion)
                        <tr>
                            <td>{{ $criterion->subject }}</td>
                            <td>{{ $criterion->coefficient }}</td>
                            <td>
                                <form action="{{ route('admin.references.criteria.destroy', $criterion) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="ref-btn-danger">X</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top:1rem; padding:1rem; background:var(--ink03); border-radius:var(--r);">
                <form action="{{ route('admin.references.criteria.store') }}" method="POST" style="display:flex; gap:1rem; align-items:flex-end;">
                    @csrf
                    <input type="hidden" name="reference_section_id" value="{{ $section->id }}">
                    <div style="flex:1;">
                        <label style="font-size:0.8rem;">Matière</label>
                        <input type="text" name="subject" class="ref-input" required placeholder="ex: Mathématiques">
                    </div>
                    <div style="width:150px;">
                        <label style="font-size:0.8rem;">Coefficient</label>
                        <input type="number" step="0.1" name="coefficient" class="ref-input" required value="1">
                    </div>
                    <button type="submit" class="ref-btn" style="height:38px;">Ajouter Critère</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
