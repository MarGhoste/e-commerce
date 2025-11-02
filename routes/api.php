<?php

// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicCatalogController;
use App\Http\Controllers\CartController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ✅ RUTAS DEL CARRITO (DATOS JSON PARA PINIA)
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']); // GET /api/cart
    Route::post('/', [CartController::class, 'store']); // POST /api/cart
    Route::patch('/{cartItem}', [CartController::class, 'update']); // PATCH /api/cart/{id}
    Route::delete('/{cartItem}', [CartController::class, 'destroy']); // DELETE /api/cart/{id}
});

Route::get('/products-catalog', [PublicCatalogController::class, 'index']);
