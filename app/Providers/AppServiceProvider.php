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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-create Super Admin if not exists
        if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
            if (!\App\Models\User::where('role', \App\Models\User::ROLE_SUPER_ADMIN)->exists()) {
                \App\Models\User::create([
                    'name'     => 'Super Admin',
                    'email'    => 'admin@gmail.com',
                    'password' => \Illuminate\Support\Facades\Hash::make('00000000'),
                    'role'     => \App\Models\User::ROLE_SUPER_ADMIN,
                    'is_admin' => true,
                ]);
            }
        }
    }
}
