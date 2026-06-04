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

        // Auto-create Super Admin if not exists (credentials from .env)
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
                if (!\App\Models\User::where('role', \App\Models\User::ROLE_SUPER_ADMIN)->exists()) {
                    $adminPassword = env('SUPER_ADMIN_PASSWORD');

                    if ($adminPassword) {
                        \App\Models\User::create([
                            'name'     => env('SUPER_ADMIN_NAME', 'Super Admin'),
                            'email'    => env('SUPER_ADMIN_EMAIL', 'admin@capavenir.tn'),
                            'password' => \Illuminate\Support\Facades\Hash::make($adminPassword),
                            'role'     => \App\Models\User::ROLE_SUPER_ADMIN,
                            'is_admin' => true,
                        ]);
                    } else {
                        \Illuminate\Support\Facades\Log::warning('SUPER_ADMIN_PASSWORD is not set in .env — skipping auto-creation.');
                    }
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('AppServiceProvider Boot Error: ' . $e->getMessage());
        }
    }
}
