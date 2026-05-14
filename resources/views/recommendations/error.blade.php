@extends('layouts.student')

@section('title', 'Erreur de recommandation')

@section('content')
<div style="max-width: 600px; margin: 4rem auto; padding: 2rem; text-align: center;" class="glass-card">
    <div style="font-size: 3rem; margin-bottom: 1rem;">⚠️</div>
    <h1 style="font-family: var(--font-serif); font-size: 1.8rem; margin-bottom: 1rem; color: var(--accent);">Oups, une erreur est survenue</h1>
    
    <p style="color: var(--ink60); line-height: 1.6; margin-bottom: 2rem;">
        {{ $message ?? 'Nous n\'avons pas pu générer vos recommandations pour le moment.' }}
    </p>

    <a href="{{ url()->previous() }}" class="btn-glass" style="display: inline-flex; justify-content: center;">
        Retour à la page précédente
    </a>
</div>
@endsection
