<x-app-layout>
    <x-slot name="header">
        <div class="profile-header-wrapper">
            <div class="profile-header-content">
                <div class="header-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div>
                    <h2 class="profile-header-title">{{ __('Profile') }}</h2>
                    <p class="profile-header-subtitle">Gérez vos informations personnelles et paramètres de sécurité</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="profile-page-container">
        <div class="profile-content-wrapper">
            <!-- Profile Information Card -->
            <div class="profile-card">
                <div class="card-header">
                    <div class="card-header-content">
                        <div class="card-icon card-icon-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Informations du Profil</h3>
                            <p class="card-description">Mettez à jour vos informations personnelles et votre adresse email</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-content">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Password Update Card -->
            <div class="profile-card">
                <div class="card-header">
                    <div class="card-header-content">
                        <div class="card-icon card-icon-warning">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Modifier le Mot de Passe</h3>
                            <p class="card-description">Assurez-vous que votre compte utilise un mot de passe long et sécurisé</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-content">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="profile-card profile-card-danger">
                <div class="card-header">
                    <div class="card-header-content">
                        <div class="card-icon card-icon-danger">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                        </div>
                        <div>
                            <h3 class="card-title">Supprimer le Compte</h3>
                            <p class="card-description">Supprimez définitivement votre compte et toutes vos données</p>
                        </div>
                    </div>
                    <span class="danger-badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        Zone Dangereuse
                    </span>
                </div>
                <div class="card-body">
                    <div class="card-content">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-header-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem 0;
            border-bottom: 4px solid rgba(255, 255, 255, 0.1);
        }

        .profile-header-content {
            max-width: 80rem;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 14px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
        }

        .profile-header-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            letter-spacing: -0.025em;
        }

        .profile-header-subtitle {
            font-size: 0.9375rem;
            color: rgba(255, 255, 255, 0.9);
            margin: 0.25rem 0 0 0;
            font-weight: 400;
        }

        .profile-page-container {
            padding: 3rem 0;
            background: linear-gradient(to bottom, #f9fafb 0%, #ffffff 100%);
            min-height: calc(100vh - 200px);
        }

        .profile-content-wrapper {
            max-width: 80rem;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .profile-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }

        .profile-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .profile-card-danger {
            border: 2px solid #fee2e2;
            background: linear-gradient(to bottom, #fef2f2 0%, #ffffff 100%);
        }

        .profile-card-danger:hover {
            border-color: #fca5a5;
        }

        .card-header {
            padding: 1.75rem;
            background: linear-gradient(to bottom, #fafbfc, #ffffff);
            border-bottom: 1px solid #f1f3f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .profile-card-danger .card-header {
            background: linear-gradient(to bottom, #fef2f2, #ffffff);
            border-bottom: 1px solid #fee2e2;
        }

        .card-header-content {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            flex: 1;
        }

        .card-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .card-icon-primary {
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            color: #4f46e5;
            border: 1px solid #c7d2fe;
        }

        .card-icon-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #d97706;
            border: 1px solid #fde047;
        }

        .card-icon-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
            border: 1px solid #fca5a5;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 0.25rem 0;
            letter-spacing: -0.015em;
        }

        .card-description {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
            line-height: 1.5;
        }

        .danger-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.875rem;
            background: #fee2e2;
            color: #dc2626;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: 1px solid #fca5a5;
        }

        .card-body {
            padding: 2rem 1.75rem;
        }

        .card-content {
            max-width: 42rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-header-wrapper {
                padding: 1.5rem 0;
            }

            .profile-header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-icon {
                width: 48px;
                height: 48px;
            }

            .profile-header-title {
                font-size: 1.5rem;
            }

            .profile-header-subtitle {
                font-size: 0.875rem;
            }

            .profile-page-container {
                padding: 1.5rem 0;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                padding: 1.25rem;
            }

            .card-body {
                padding: 1.5rem 1.25rem;
            }

            .danger-badge {
                align-self: flex-start;
            }
        }

        /* Animation d'entrée */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-card {
            animation: slideInUp 0.4s ease-out;
        }

        .profile-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .profile-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .profile-card:nth-child(3) {
            animation-delay: 0.3s;
        }
    </style>
</x-app-layout>