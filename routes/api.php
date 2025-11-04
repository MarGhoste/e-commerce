<?php

// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicCatalogController;
//use App\Http\Controllers\CartController;
use Laravel\Cashier\Http\Controllers\WebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products-catalog', [PublicCatalogController::class, 'index']);

// Esta ruta no pasa por el CSRF de Laravel
//Route::post('stripe/webhook', WebhookController::class);
