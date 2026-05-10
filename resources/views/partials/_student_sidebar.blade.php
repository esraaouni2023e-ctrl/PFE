<aside class="dash-sidebar" id="sidebar">
    <div class="sidebar-top" style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
        <div style="position: relative; padding: 6px; background: rgba(255,255,255,0.05); border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <img src="{{ asset('im1.jpg') }}" alt="CapAvenir" style="width: 32px; height: 32px; border-radius: 8px; object-fit: cover; mix-blend-mode: multiply; display: block;">
        </div>
        <h3 class="sidebar-brand" style="font-family: 'Inter', sans-serif; font-size: 1.125rem; font-weight: 800; color: #fff; margin: 0; letter-spacing: -0.02em;">CapAvenir</h3>
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