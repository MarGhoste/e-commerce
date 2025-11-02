<?php

use Inertia\Inertia;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\PublicCatalogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductImageController;

//Route::get('/', function () {
//return Inertia::render('Welcome', [
//'canRegister' => Features::enabled(Features::registration()),
//]);
//})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');





// Ruta única para el panel (Laravel carga la vista principal)
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'is_admin']], function () {

    // Ruta principal del Panel (El Dashboard de Vue)
    Route::get('/', function () {
        return view('admin.dashboard'); // Una vista Blade simple que carga el componente Vue principal
    })->name('admin.dashboard');

    // Rutas para la API de gestión (consumidas por los componentes Vue)
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('categories', CategoryController::class);

    Route::prefix('products/{product}')->group(function () {
        Route::post('images', [ProductImageController::class, 'store']); // /admin/products/{id}/images [Subir]
    });

    // Rutas para acciones sobre una imagen específica
    Route::prefix('images')->group(function () {
        Route::post('{image}/main', [ProductImageController::class, 'setMain']); // /admin/images/{id}/main [Principal]
        Route::delete('{image}', [ProductImageController::class, 'destroy']); // /admin/images/{id} [Eliminar]
    });
});


//Route::get('/', function () {
// Asumimos que esta vista Blade carga tu archivo resources/js/app.ts o js/app.js
//return view('public.app');
//})->name('catalog');

Route::get('/', [PublicCatalogController::class, 'showCatalogPage'])->name('catalog');
Route::get('/products/{slug}', [PublicCatalogController::class, 'showProductDetail'])->name('product.detail');
Route::get('/cart', [PublicCatalogController::class, 'showCartPage'])->name('cart.view');

require __DIR__ . '/settings.php';
