<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShoppingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::get('reports', [ProductController::class, 'reports'])->name('reports');
});

//Route::get('/', [ShoppingController::class, 'shop'])->name('shop');
Route::get('/', function () {
    return app()->call([app(ShoppingController::class), 'shop']);
})->name('shop');
Route::get('/cart', [ShoppingController::class, 'cart'])->name('cart.index');
Route::post('/add', [ShoppingController::class, 'add'])->name('cart.store');
Route::post('/update', [ShoppingController::class, 'update'])->name('cart.update');
Route::post('/remove', [ShoppingController::class, 'remove'])->name('cart.remove');
Route::post('/clear', [ShoppingController::class, 'clear'])->name('cart.clear');



require __DIR__.'/auth.php';
