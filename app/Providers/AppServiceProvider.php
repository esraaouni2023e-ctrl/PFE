<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        \Laravel\Fortify\Fortify::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Mail::extend('brevo', function (array $config) {
            return new \App\Mail\Transport\BrevoTransport($config['api_key']);
        });

        if (app()->runningInConsole()) {
            return;
        }

        // Force HTTPS detection behind Render's reverse proxy.
        // This MUST run before the session middleware so that cookies with the
        // Secure flag are correctly handled by the browser.
        if (
            app()->environment('production')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
        ) {
            \Illuminate\Support\Facades\URL::forceScheme('https');

            // Also tell PHP/Symfony Request that we are on HTTPS,
            // so Request::isSecure() returns true and Secure cookies work.
            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
                $_SERVER['HTTPS'] = 'on';
            }
        }

        // Auto-create or sync Super Admin (credentials from config/capavenir.php)
        try {
            // Cache the sync status to avoid querying the DB on every HTTP request
            if (!\Illuminate\Support\Facades\Cache::has('super_admin_synced')) {
                if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
                    $adminEmail = config('capavenir.super_admin.email', 'admin@capavenir.tn');
                    $adminPassword = config('capavenir.super_admin.password');

                    $superAdmin = \App\Models\User::where('role', \App\Models\User::ROLE_SUPER_ADMIN)->first();

                    if (!$superAdmin) {
                        if ($adminPassword) {
                            \App\Models\User::create([
                                'name'     => config('capavenir.super_admin.name', 'Super Admin'),
                                'email'    => $adminEmail,
                                'password' => \Illuminate\Support\Facades\Hash::make($adminPassword),
                                'role'     => \App\Models\User::ROLE_SUPER_ADMIN,
                                'is_admin' => true,
                            ]);
                        } else {
                            \Illuminate\Support\Facades\Log::warning('SUPER_ADMIN_PASSWORD is not set — skipping auto-creation.');
                        }
                    } else {
                        // Mettre à jour l'email si la configuration a changé
                        if ($superAdmin->email !== $adminEmail && !empty($adminEmail)) {
                            $superAdmin->email = $adminEmail;
                            $superAdmin->save();
                        }
                    }

                    // Mark as synced in cache for 24 hours (86400 seconds)
                    \Illuminate\Support\Facades\Cache::put('super_admin_synced', true, 86400);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('AppServiceProvider Boot Error: ' . $e->getMessage());
        }
    }
}
