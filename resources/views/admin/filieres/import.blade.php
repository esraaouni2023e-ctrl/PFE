@extends('layouts.admin')

@section('title', 'Import des filières — Admin')

@section('content')
<div class="container py-6">

    {{-- ── En-tête ──────────────────────────────────────────────────── --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Import des filières</h1>
            <p class="text-sm text-gray-500 mt-1">
                Importez vos fichiers Excel de filières universitaires tunisiennes.
                Chemin Artisan : <code class="bg-gray-100 px-1 rounded">storage/app/excels/</code>
            </p>
        </div>
        <span class="text-sm font-semibold bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full">
            {{ number_format($totalRows) }} filières en base
        </span>
    </div>

    {{-- ── Alertes ─────────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">
            ❌ {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── Formulaire d'import ──────────────────────────────────── --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">📤 Importer un fichier Excel</h2>

            <form action="{{ route('admin.filieres.import.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Catégorie --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    <select name="categorie" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">— Sélectionner —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('categorie') === $cat ? 'selected' : '' }}>
                                {{ $cat }} — {{ \App\Models\Filiere::CATEGORIES[$cat] ?? $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Fichier --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Fichier Excel (.xlsx / .xls / .csv) <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="fichier" accept=".xlsx,.xls,.csv" required
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                  file:rounded-lg file:border-0 file:text-sm file:font-semibold
                                  file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="text-xs text-gray-400 mt-1">
                        Colonnes requises : Code_Filiere, Nom_Filiere, Universite, Etablissement,
                        SDO_2023, SDO_2024, SDO_2025, Code_RIASEC, Taux_Employabilite,
                        Croissance_Domaine, Alignment_National, source
                    </p>
                </div>

                <button type="submit"
                        class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white
                               font-semibold rounded-lg transition text-sm">
                    Lancer l'import
                </button>
            </form>

            {{-- ── Commande Artisan ──────────────────────────────────── --}}
            <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-xs font-semibold text-gray-500 mb-2">Ou via Artisan (tous les fichiers) :</p>
                <code class="text-xs text-gray-700 block">php artisan filieres:import</code>
                <code class="text-xs text-gray-400 block mt-1">php artisan filieres:import --file=INFO_Filieres.xlsx</code>
                <code class="text-xs text-gray-400 block mt-1">php artisan filieres:import --dry-run</code>
            </div>
        </div>

        {{-- ── Stats par catégorie ──────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">📊 Filières en base</h2>

            @forelse($categories as $cat)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                    <div>
                        <span class="text-xs font-bold text-indigo-600">{{ $cat }}</span>
                        <span class="text-xs text-gray-500 ml-1">{{ \App\Models\Filiere::CATEGORIES[$cat] ?? '' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-gray-700">
                            {{ number_format($stats[$cat] ?? 0) }}
                        </span>
                        {{-- Bouton purge --}}
                        @if(($stats[$cat] ?? 0) > 0)
                            <form action="{{ route('admin.filieres.import.destroy', $cat) }}"
                                  method="POST"
                                  onsubmit="return confirm('Supprimer toutes les filières {{ $cat }} ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-xs text-red-400 hover:text-red-600 transition"
                                        title="Purger {{ $cat }}">
                                    ✕
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400">Aucune donnée.</p>
            @endforelse

            <div class="mt-4 pt-3 border-t border-gray-200 flex justify-between text-sm font-bold text-gray-700">
                <span>Total</span>
                <span>{{ number_format($totalRows) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
