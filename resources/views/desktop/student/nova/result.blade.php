@extends('layouts.student')

@section('title', 'Resultat Nova')

@section('content')
@include('student.nova.styles')

<div class="db">
    <section class="db-section rev vis">
        <div class="card" style="max-width: 800px; margin: 0 auto; padding: 3rem 2.5rem; text-align: center;">
            <p class="stag">Calcul Nova</p>
            <h2 class="sh" style="margin-bottom: 1.5rem;">Votre Score Formule Globale</h2>

            <div class="nova-score-display" style="font-size: 5.5rem; margin: 1.5rem 0;">
                {{ number_format((float) ($result['score_fg'] ?? 0), 2) }}
            </div>

            <p style="color: var(--ink60); margin-bottom: 1.5rem;">
                Section : <strong>{{ $result['section_bac'] ?? 'Non precisee' }}</strong>
            </p>

            <a href="{{ route('student.orientation.nova') }}" class="btn-fill">
                Refaire le calcul
            </a>
        </div>
    </section>
</div>
@endsection
