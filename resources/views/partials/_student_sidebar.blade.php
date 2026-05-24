<aside class="dash-sidebar" id="sidebar">
    <div class="sidebar-top">
        <img src="{{ asset('images/capavenir.png') }}" alt="CapAvenir" class="sidebar-logo">
        <h3 class="sidebar-brand">CapAvenir</h3>
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
        <form method="POST" action="/logout">
            @csrf
            <button class="btn-logout" type="submit">Se déconnecter</button>
        </form>
    </div>
</aside>