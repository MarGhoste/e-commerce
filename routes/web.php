<?php

//use routes;
use Inertia\Inertia;
use App\Models\Order;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\PublicCatalogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductImageController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;


//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canRegister' => Features::enabled(Features::registration()),
//    ]);
//})->name('home');
Route::post(
    'stripe/webhook',
    [\Laravel\Cashier\Http\Controllers\WebhookController::class, 'handleWebhook']
)->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware(['guest'])
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(['guest']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');


//Laravel\Fortify\Fortify::routes();



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
//Route::get('/cart', [PublicCatalogController::class, 'showCartPage'])->name('cart.view');

Route::get('/checkout/cart', [PublicCatalogController::class, 'showCartPage'])->name('cart.view');


// API routes for the cart, moved here to use web middleware (sessions)
Route::prefix('api/cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/', [CartController::class, 'store']);
    Route::patch('/{cartItem}', [CartController::class, 'update']);
    Route::delete('/{cartItem}', [CartController::class, 'destroy']);
});

Route::get('/checkout', [CheckoutController::class, 'index'])->middleware(['auth'])->name('checkout.view');

Route::post('/checkout/process', [CheckoutController::class, 'process'])->middleware(['auth'])->name('checkout.process');

Route::get('/order/{order}', function (Order $order) {
    // Asegura que la orden pertenezca al usuario actual (o admin)
    if (Auth::id() !== $order->user_id) {
        // Redirigir si la orden no pertenece al usuario.
        return redirect('/')->with('error', 'Acceso denegado a esta orden.');
    }

    // Comparte la orden con el componente Vue
    return Inertia::render('Checkout/OrderConfirmation', [
        'order' => $order->load('items.product'), // Carga los detalles para mostrar
    ]);
})->middleware(['auth'])->name('order.confirmation');



require __DIR__ . '/settings.php';
