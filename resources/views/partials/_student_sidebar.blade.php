<aside class="dash-sidebar" id="sidebar">
    <div class="sidebar-top" style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
        <img src="{{ asset('final.png') }}" alt="CapAvenir" style="height: 40px; width: auto; object-fit: contain;">
        <h3 class="sidebar-brand" style="font-family: 'Fraunces', serif; font-size: 1.25rem; font-weight: 700; color: #fff; margin: 0; letter-spacing: -0.02em;">CapAvenir</h3>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-item active">Dashboard</a>
        <a href="#" class="nav-item">Profil</a>
        <a href="#" class="nav-item">Tests</a>
        <a href="#" class="nav-item">Recommandations</a>
        <a href="#" class="nav-item">Simulateur</a>
        <a href="#" class="nav-item">Chatbot</a>
        <a href="#" class="nav-item">Notifications <span class="pill">3</span></a>
        <a href="#" class="nav-item">Badges</a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn-logout" type="submit">Se déconnecter</button>
        </form>
    </div>
</aside>