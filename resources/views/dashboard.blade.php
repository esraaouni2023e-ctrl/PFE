<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl font-extrabold text-white tracking-tight flex items-center gap-3">
            <span class="w-1 h-8 bg-gradient-to-b from-cyan-400 to-navy-700 rounded-sm"></span>
            Sélecteur d'Interface
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12 px-6">
        <div class="text-center mb-16 animate-pulse-slow">
            <h1 class="font-display text-4xl font-black text-white mb-4 tracking-tight">Bienvenue, <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-cyan-600">{{ auth()->user()->name }}</span></h1>
            <p class="text-text-muted text-lg font-medium">Choisissez l'espace que vous souhaitez explorer aujourd'hui.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            @if(auth()->user()->isStudent() || auth()->user()->isAdmin())
            <!-- Student Portal -->
            <a href="{{ route('student.dashboard') }}" class="glass-card group relative overflow-hidden hover:border-cyan-400/50 transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-400/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="w-16 h-16 rounded-2xl glass-morphism flex items-center justify-center text-4xl mb-6 text-cyan-400 group-hover:scale-110 transition-transform">🎓</div>
                
                <h3 class="text-2xl font-bold text-white mb-3">Espace Étudiant</h3>
                <p class="text-text-secondary text-sm leading-relaxed mb-6">Accédez à vos tests d'orientation, analyses IA et suivi de progression.</p>
                
                <div class="flex items-center gap-2 text-cyan-400 font-bold text-sm uppercase tracking-wider group-hover:gap-4 transition-all">
                    Entrer dans l'espace <span>➜</span>
                </div>
                
                <div class="absolute -bottom-4 -right-4 text-8xl font-black text-white/5 select-none">STUDENT</div>
            </a>
            @endif

            @if(auth()->user()->isCounselor() || auth()->user()->isAdmin())
            <!-- Counselor Portal -->
            <a href="{{ route('counselor.dashboard') }}" class="glass-card group relative overflow-hidden hover:border-blue-400/50 transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-400/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="w-16 h-16 rounded-2xl glass-morphism flex items-center justify-center text-4xl mb-6 text-blue-400 group-hover:scale-110 transition-transform">👨‍🏫</div>
                
                <h3 class="text-2xl font-bold text-white mb-3">Portail Conseiller</h3>
                <p class="text-text-secondary text-sm leading-relaxed mb-6">Gérez vos étudiants, analysez leurs résultats et apportez votre expertise.</p>
                
                <div class="flex items-center gap-2 text-blue-400 font-bold text-sm uppercase tracking-wider group-hover:gap-4 transition-all">
                    Accéder au portail <span>➜</span>
                </div>
                
                <div class="absolute -bottom-4 -right-4 text-8xl font-black text-white/5 select-none">EXPERT</div>
            </a>
            @endif

            @if(auth()->user()->isAdmin())
            <!-- Admin Portal -->
            <a href="{{ route('admin.dashboard') }}" class="glass-card group relative overflow-hidden hover:border-red-400/50 transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="w-16 h-16 rounded-2xl glass-morphism flex items-center justify-center text-4xl mb-6 text-red-500 group-hover:scale-110 transition-transform">⚙️</div>
                
                <h3 class="text-2xl font-bold text-white mb-3">Administration</h3>
                <p class="text-text-secondary text-sm leading-relaxed mb-6">Contrôle total de la plateforme, gestion des utilisateurs et systèmes.</p>
                
                <div class="flex items-center gap-2 text-red-500 font-bold text-sm uppercase tracking-wider group-hover:gap-4 transition-all">
                    Gérer la plateforme <span>➜</span>
                </div>
                
                <div class="absolute -bottom-4 -right-4 text-8xl font-black text-white/5 select-none">ADMIN</div>
            </a>
            @endif

        </div>
    </div>
</x-app-layout>
