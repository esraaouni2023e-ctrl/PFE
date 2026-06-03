<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// Chargement explicite du fichier .env pour s'assurer que les variables
// d'environnement sont disponibles en CLI (évite d'avoir à définir
// $env:DB_DATABASE manuellement dans PowerShell).
if (file_exists($app->environmentPath().'/'.$app->environmentFile())) {
    try {
        if (($_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? '') !== 'testing') {
            \Dotenv\Dotenv::createImmutable($app->environmentPath(), $app->environmentFile())->safeLoad();
        } else {
            // En mode testing, synchroniser $_SERVER avec $_ENV pour éviter que les variables
            // héritées du processus parent (artisan test) dans $_SERVER ne l'emportent sur $_ENV (sqlite).
            foreach ($_ENV as $key => $value) {
                $_SERVER[$key] = $value;
            }
        }
    } catch (\Throwable $e) {
        // Ne pas casser le bootstrap si le fichier .env est invalide.
    }
}

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
