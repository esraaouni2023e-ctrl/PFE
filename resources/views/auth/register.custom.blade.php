@extends('layouts.auth')

@section('title','S\'inscrire (backup)')

@section('content')
<div class="text-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-900">Créer un compte (backup)</h2>
    <p class="text-sm text-gray-500 mt-2">Commencez votre parcours avec CapAvenir</p>
</div>

<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
        <input id="name" name="name" type="text" required class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:ring focus:ring-primary-300 focus:border-primary-600" />
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" name="email" type="email" required class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:ring focus:ring-primary-300 focus:border-primary-600" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
            <input id="password" name="password" type="password" required class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:ring focus:ring-primary-300 focus:border-primary-600" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:ring focus:ring-primary-300 focus:border-primary-600" />
        </div>
    </div>

    <div>
        <button type="submit" class="w-full py-2 px-4 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-semibold shadow">S\'inscrire</button>
    </div>

    <div class="text-center text-sm text-gray-500">
        Déjà inscrit ? <a href="{{ route('login') }}" class="text-primary-600 font-medium hover:underline">Se connecter</a>
    </div>
</form>

@endsection