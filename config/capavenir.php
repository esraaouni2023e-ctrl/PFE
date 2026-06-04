<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Super Admin Auto-Creation
    |--------------------------------------------------------------------------
    |
    | These credentials are used by AppServiceProvider to auto-create the
    | Super Admin user on first boot. They MUST live in a config file
    | so they survive `php artisan config:cache`.
    |
    */

    'super_admin' => [
        'name'     => env('SUPER_ADMIN_NAME', 'Super Admin'),
        'email'    => env('SUPER_ADMIN_EMAIL', 'admin@capavenir.tn'),
        'password' => env('SUPER_ADMIN_PASSWORD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Python API (Early Stopping / RIASEC)
    |--------------------------------------------------------------------------
    */

    'python_api_url' => env('PYTHON_API_URL', 'http://127.0.0.1:5000'),

];
