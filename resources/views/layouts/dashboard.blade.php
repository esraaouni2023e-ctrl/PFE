<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name')) — CapAvenir</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen">
    <div class="dash-app">
        @includeIf('partials._student_sidebar')

        <main class="dash-main">
            <header class="dash-header">
                <button id="sidebarToggle" class="sidebar-toggle" aria-label="Toggle sidebar">☰</button>
                <div class="header-right">
                    <div class="header-user">
                        <div class="user-avatar">{{ strtoupper(mb_substr(auth()->user()->name ?? 'E',0,1)) }}</div>
                        <div class="user-meta">
                            <div class="user-name">{{ auth()->user()->name ?? 'Étudiant' }}</div>
                            <div class="user-role">Étudiant</div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="dash-content">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const toggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        toggle?.addEventListener('click', () => {
            sidebar?.classList.toggle('collapsed');
        });
    </script>
</body>
</html>