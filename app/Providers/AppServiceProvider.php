<?php

namespace App\Providers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

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
        // ... (otros códigos)

        // ✅ AÑADIR ESTE BLOQUE PARA COMPARTIR EL USUARIO
        Inertia::share([
            'auth' => fn() => [
                'user' => Auth::user() ? [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,

                ] : null,
            ],
            // Esto es necesario para que Vue sepa si el usuario está logueado

            'stripe_key' => Config::get('cashier.key'),
            // Añade aquí cualquier otro campo que necesites en Vue
        ]);
    }
}
