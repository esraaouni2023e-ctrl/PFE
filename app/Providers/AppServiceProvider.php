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

        if (app()->environment('production') || env('APP_ENV') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
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
